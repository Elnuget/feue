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
        });
    }

    public function index()
    {
        $certificados = Certificado::with('usuario')->paginate(10);
        return view('certificados.index', compact('certificados'));
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

            $curso = \App\Models\Curso::findOrFail($request->curso_id);
            
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

                // Crear el certificado asegurando que el nombre esté entre comillas
                Certificado::create([
                    'usuario_id' => $matricula->usuario_id,
                    'nombre_completo' => "'{$matricula->usuario->name}'",
                    'horas_curso' => (int)$curso->horas, // Convertir explícitamente a entero
                    'sede_curso' => 'Sede Principal',
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