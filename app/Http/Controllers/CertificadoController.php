<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Mail\CertificadoGenerado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class CertificadoController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasRole(1)) {
                abort(403, 'No tienes permiso para acceder a esta sección.');
            }
            return $next($request);
        })->except(['show', 'pdf']);
    }

    public function index()
    {
        $tiposCursos = \App\Models\TipoCurso::all();
        
        // Obtener los cursos que tienen matrículas
        $cursosMatriculados = \App\Models\Curso::whereHas('matriculas')->get();
        
        $query = Certificado::with(['usuario' => function($q) {
            $q->with('matriculas');
        }])
        ->join('users', 'certificados.usuario_id', '=', 'users.id')
        ->orderBy('users.name', 'asc')
        ->select('certificados.*');

        // Aplicar filtro por tipo de curso
        if (request()->has('tipo_curso') && request('tipo_curso') !== '') {
            $tipoCursoId = request('tipo_curso');
            $query->whereHas('usuario.matriculas', function($q) use ($tipoCursoId) {
                $q->whereHas('curso', function($q) use ($tipoCursoId) {
                    $q->where('tipo_curso_id', $tipoCursoId);
                });
            });
        }

        // Aplicar filtro por curso específico
        if (request()->has('curso_id') && request('curso_id') !== '') {
            $cursoId = request('curso_id');
            $query->whereHas('usuario.matriculas', function($q) use ($cursoId) {
                $q->where('curso_id', $cursoId);
            });
        }

        // Obtener número de registros por página (50 por defecto)
        $perPage = request('per_page', 50);

        $certificados = $query->paginate($perPage)->withQueryString();

        return view('certificados.index', compact('certificados', 'tiposCursos', 'cursosMatriculados'));
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

        $certificado = Certificado::create($validated);

        // Enviar correo electrónico
        if ($certificado->usuario->email) {
            Mail::to($certificado->usuario->email)->send(new CertificadoGenerado($certificado));
        }

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
            'nombre_curso' => 'required|string|max:255',
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

    public function pdf(Certificado $certificado)
    {
        $pdf = PDF::loadView('certificados.pdf', compact('certificado'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('certificado.pdf');
    }

    public function storeMultiple(Request $request)
    {
        try {
            $request->validate([
                'matricula_ids' => 'required|array',
                'matricula_ids.*' => 'exists:matriculas,id',
                'curso_id' => 'required|exists:cursos,id',
                'curso_nombre' => 'required|string',
                'curso_horas' => 'required|integer',
                'curso_sede' => 'required|string'
            ]);

            $curso = \App\Models\Curso::with('tipoCurso')->findOrFail($request->curso_id);
            
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

                // Crear el certificado usando los datos del modal
                $certificado = Certificado::create([
                    'usuario_id' => $matricula->usuario_id,
                    'nombre_completo' => $matricula->usuario->name,
                    'nombre_curso' => $request->curso_nombre,
                    'horas_curso' => (int)$request->curso_horas,
                    'sede_curso' => $request->curso_sede,
                    'fecha_emision' => now(),
                    'anio_emision' => $anioActual,
                    'numero_certificado' => $numeroCertificado,
                    'estado' => true,
                    'observaciones' => 'Generado automáticamente'
                ]);

                // Enviar correo electrónico
                if ($matricula->usuario->email) {
                    Mail::to($matricula->usuario->email)->send(new CertificadoGenerado($certificado));
                }

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