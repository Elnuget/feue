<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use Illuminate\Http\Request;

class CertificadoController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasRole(1)) {
                abort(403, 'No tienes permiso para acceder a esta sección.');
            }
            return $next($request);
        })->except(['show']);
    }

    public function index()
    {
        $tiposCursos = \App\Models\TipoCurso::all();
        $cursos = \App\Models\Curso::all();
        
        $query = Certificado::with('usuario')
            ->join('users', 'certificados.usuario_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('certificados.*');

        // Aplicar filtro por tipo de curso
        if (request()->has('tipo_curso') && request('tipo_curso') !== '') {
            $tipoCursoId = request('tipo_curso');
            $cursosIds = \App\Models\Curso::where('tipo_curso_id', $tipoCursoId)->pluck('id');
            $cursosFiltrados = \App\Models\Curso::whereIn('id', $cursosIds)->get();
            $nombresCursos = $cursosFiltrados->pluck('nombre')->toArray();
            $query->whereIn('nombre_curso', $nombresCursos);
        }

        // Aplicar filtro por curso específico
        if (request()->has('curso_id') && request('curso_id') !== '') {
            $curso = \App\Models\Curso::find(request('curso_id'));
            if ($curso) {
                $query->where('nombre_curso', $curso->nombre);
            }
        }

        // Obtener número de registros por página (50 por defecto)
        $perPage = request('per_page', 50);

        $certificados = $query->paginate($perPage)->withQueryString();

        return view('certificados.index', compact('certificados', 'tiposCursos', 'cursos'));
    }

    public function create()
    {
        return view('certificados.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'nombre_completo' => 'required|string|max:255',
            'horas_curso' => 'required|integer',
            'sede_curso' => 'required|string|max:100',
            'fecha_emision' => 'required|date',
            'anio_emision' => 'required|string|size:2',
            'numero_certificado' => 'required|string|max:50|unique:certificados',
            'estado' => 'boolean',
            'observaciones' => 'nullable|string',
        ]);

        Certificado::create($validated);

        return redirect()->route('certificados.index')
            ->with('success', 'Certificado creado exitosamente.');
    }

    public function show(Certificado $certificado)
    {
        return view('certificados.show', compact('certificado'));
    }

    public function edit(Certificado $certificado)
    {
        return view('certificados.edit', compact('certificado'));
    }

    public function update(Request $request, Certificado $certificado)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'nombre_completo' => 'required|string|max:255',
            'horas_curso' => 'required|integer',
            'sede_curso' => 'required|string|max:100',
            'fecha_emision' => 'required|date',
            'anio_emision' => 'required|string|size:2',
            'numero_certificado' => 'required|string|max:50|unique:certificados,numero_certificado,' . $certificado->id,
            'estado' => 'boolean',
            'observaciones' => 'nullable|string',
        ]);

        $certificado->update($validated);

        return redirect()->route('certificados.index')
            ->with('success', 'Certificado actualizado exitosamente.');
    }

    public function destroy(Certificado $certificado)
    {
        $certificado->delete();

        return redirect()->route('certificados.index')
            ->with('success', 'Certificado eliminado exitosamente.');
    }

    public function storeMultiple(Request $request)
    {
        try {
            $request->validate([
                'matricula_ids' => 'required|array',
                'matricula_ids.*' => 'exists:matriculas,id',
                'curso_id' => 'required|exists:cursos,id'
            ]);

            $curso = \App\Models\Curso::with('tipoCurso')->findOrFail($request->curso_id);
            
            // Verificar que el curso tenga horas definidas
            if (!$curso->horas) {
                return response()->json([
                    'success' => false,
                    'message' => 'El curso no tiene horas definidas. Por favor, configure las horas del curso primero.'
                ], 400);
            }

            $matriculas = \App\Models\Matricula::whereIn('id', $request->matricula_ids)
                ->with('usuario')
                ->get();

            $anioActual = date('y');
            $certificadosCreados = 0;

            foreach ($matriculas as $matricula) {
                // Generar número de certificado único
                $ultimoCertificado = Certificado::where('anio_emision', $anioActual)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $numeroSecuencial = $ultimoCertificado ? 
                    (int)substr($ultimoCertificado->numero_certificado, -4) + 1 : 
                    1;
                
                $numeroCertificado = sprintf('CERT-%s-%04d', $anioActual, $numeroSecuencial);

                // Crear el certificado usando el nombre del tipo de curso como sede
                Certificado::create([
                    'usuario_id' => $matricula->usuario_id,
                    'nombre_completo' => "'{$matricula->usuario->name}'",
                    'nombre_curso' => $curso->nombre,
                    'horas_curso' => (int)$curso->horas,
                    'sede_curso' => $curso->tipoCurso->nombre ?? 'Sin tipo de curso definido',
                    'fecha_emision' => now(),
                    'anio_emision' => $anioActual,
                    'numero_certificado' => $numeroCertificado,
                    'estado' => true,
                    'observaciones' => 'Generado automáticamente'
                ]);

                $certificadosCreados++;
            }

            return response()->json([
                'success' => true,
                'message' => "Se generaron {$certificadosCreados} certificados exitosamente."
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al generar certificados: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar los certificados: ' . $e->getMessage()
            ], 500);
        }
    }
} 