<?php

namespace App\Http\Controllers;

use App\Models\AulaVirtual;
use App\Models\Curso;
use App\Models\AulaVirtualContenido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AulaVirtualController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole(1) || auth()->user()->hasRole('Docente')) {
            // Administrador y Docente ven todas las aulas virtuales
            $aulasVirtuales = AulaVirtual::with(['cursos', 'contenidos'])->orderBy('id', 'desc')->get();
        } else {
            // Usuario normal solo ve las aulas virtuales de los cursos en los que está matriculado
            $userId = auth()->id();
            $aulasVirtuales = AulaVirtual::whereHas('cursos.matriculas', function($query) use ($userId) {
                $query->where('usuario_id', $userId);
            })
            ->with(['cursos' => function($query) use ($userId) {
                $query->whereHas('matriculas', function($q) use ($userId) {
                    $q->where('usuario_id', $userId);
                });
            }, 'contenidos'])
            ->orderBy('id', 'desc')
            ->get();
        }
        return view('aulas_virtuales.index', compact('aulasVirtuales'));
    }

    public function create()
    {
        $cursos = Curso::with(['tipoCurso'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($curso) {
                $curso->nombre_completo = $curso->nombre;
                $curso->descripcion_completa = $curso->descripcion; // Adding description
                return $curso;
            });
        return view('aulas_virtuales.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cursos' => 'nullable|array',
            'cursos.*' => 'exists:cursos,id'
        ]);

        $aulaVirtual = AulaVirtual::create($validated);
        
        if ($request->has('cursos')) {
            $aulaVirtual->cursos()->sync($request->cursos);
        }

        return redirect()
            ->route('aulas_virtuales.index')
            ->with('success', 'Aula virtual creada exitosamente.');
    }

    public function edit(AulaVirtual $aulasVirtuale)
    {
        $cursos = Curso::with(['tipoCurso'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($curso) {
                $curso->nombre_completo = $curso->nombre;
                $curso->descripcion_completa = $curso->descripcion; // Adding description
                return $curso;
            });
        $aulaVirtual = $aulasVirtuale;
        return view('aulas_virtuales.edit', compact('aulaVirtual', 'cursos'));
    }

    public function update(Request $request, AulaVirtual $aulasVirtuale)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cursos' => 'nullable|array',
            'cursos.*' => 'exists:cursos,id'
        ]);

        $aulasVirtuale->update($validated);

        if ($request->has('cursos')) {
            $aulasVirtuale->cursos()->sync($request->cursos);
        }

        return redirect()
            ->route('aulas_virtuales.index')
            ->with('success', 'Aula virtual actualizada exitosamente.');
    }

    public function destroy(AulaVirtual $aulasVirtuale)
    {
        $aulasVirtuale->cursos()->detach();
        $aulasVirtuale->delete();

        return redirect()
            ->route('aulas_virtuales.index')
            ->with('success', 'Aula virtual eliminada exitosamente.');
    }

    public function show(AulaVirtual $aulasVirtuale)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            $userId = auth()->id();
            // Verificar si el usuario está matriculado en algún curso del aula virtual
            $tieneAcceso = $aulasVirtuale->cursos()
                ->whereHas('matriculas', function($query) use ($userId) {
                    $query->where('usuario_id', $userId);
                })->exists();

            if (!$tieneAcceso) {
                return redirect()
                    ->route('aulas_virtuales.index')
                    ->with('error', 'No tienes acceso a esta aula virtual.');
            }

            // Cargar solo los cursos en los que el usuario está matriculado
            $aula = $aulasVirtuale->load([
                'cursos' => function($query) use ($userId) {
                    $query->whereHas('matriculas', function($q) use ($userId) {
                        $q->where('usuario_id', $userId);
                    });
                },
                'contenidos' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ]);
        } else {
            $aula = $aulasVirtuale->load(['cursos', 'contenidos']);
        }

        return view('aulas_virtuales.show', compact('aula'));
    }

    public function storeContenido(Request $request, AulaVirtual $aulasVirtuale)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        try {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'contenido' => 'nullable|string',
                'enlace' => 'nullable|url',
                'archivo' => 'nullable|file|max:25600|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,jpg,jpeg,png,mp4,avi,mov',
            ], [
                'archivo.max' => 'El archivo es demasiado grande. El tamaño máximo permitido es 25MB. Para archivos más grandes, se recomienda subirlos a Google Drive y compartir el enlace.',
                'archivo.mimes' => 'El tipo de archivo no está permitido. Los formatos permitidos son: PDF, Word, Excel, PowerPoint, ZIP, RAR, TXT, imágenes y videos.',
                'enlace.url' => 'El enlace proporcionado no es válido. Asegúrate de incluir http:// o https://',
            ]);

            $data = $request->only(['titulo', 'contenido', 'enlace']);
            
            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                $tamanioEnMB = $archivo->getSize() / 1024 / 1024;
                
                if ($tamanioEnMB > 20) {
                    return redirect()->back()
                        ->with('warning', 'El archivo que intentas subir es de ' . number_format($tamanioEnMB, 2) . 'MB. Para archivos grandes, te recomendamos subirlo a Google Drive y compartir el enlace en su lugar.')
                        ->withInput();
                }
                
                $data['archivo'] = $archivo->store('aulas_virtuales/archivos', 'public');
            }

            $aulasVirtuale->contenidos()->create($data);

            return redirect()->back()->with('success', 'Contenido agregado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hubo un error al procesar tu solicitud. Por favor, intenta nuevamente.')
                ->withInput();
        }
    }

    public function destroyContenido($id)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $contenido = AulaVirtualContenido::findOrFail($id);
        
        if ($contenido->archivo) {
            Storage::disk('public')->delete($contenido->archivo);
        }
        
        $contenido->delete();

        return redirect()->back()->with('success', 'Contenido eliminado exitosamente');
    }
}
