<?php

namespace App\Http\Controllers;

use App\Models\AulaVirtual;
use App\Models\Curso;
use App\Models\AulaVirtualContenido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AulaVirtualController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $showAll = $request->get('show_all', false);

        if ($user->hasRole(1)) {
            // Administrador ve todas las aulas virtuales o solo las asociadas según el filtro
            $query = AulaVirtual::with(['cursos', 'contenidos', 'usuarios']);
            
            if (!$showAll) {
                $query->whereHas('usuarios', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }
            
            $aulasVirtuales = $query->orderBy('id', 'desc')->get();
        } elseif ($user->hasRole('Docente')) {
            // Docente ve sus aulas virtuales o todas según el filtro
            $query = AulaVirtual::with(['cursos', 'contenidos', 'usuarios']);
            
            if (!$showAll) {
                $query->whereHas('usuarios', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }
            
            $aulasVirtuales = $query->orderBy('id', 'desc')->get();
        } else {
            // Usuario normal solo ve las aulas virtuales de los cursos en los que está matriculado
            $userId = $user->id;
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

        return view('aulas_virtuales.index', compact('aulasVirtuales', 'showAll'));
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
            'curso_id' => 'required|exists:cursos,id',
            'cursos' => 'nullable|array',
            'cursos.*' => 'exists:cursos,id',
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            DB::beginTransaction();
            
            $aulaVirtual = AulaVirtual::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'curso_id' => $validated['curso_id']
            ]);
            
            if ($request->has('cursos')) {
                $aulaVirtual->cursos()->sync($request->cursos);
            }
            
            // Asociar automáticamente al usuario que crea el aula virtual
            $user = User::findOrFail($validated['user_id']);
            $aulaVirtual->usuarios()->attach($user);
            
            DB::commit();
            
            return redirect()
                ->route('aulas_virtuales.index')
                ->with('success', 'Aula virtual creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error al crear el aula virtual: ' . $e->getMessage())
                ->withInput();
        }
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
        $aulasVirtuale->load(['contenidos', 'cuestionarios', 'tareas.entregas.user']);
        return view('aulas_virtuales.show', compact('aulasVirtuale'));
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
