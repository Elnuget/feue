<?php

namespace App\Http\Controllers;

use App\Models\AcuerdoConfidencialidad;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class AcuerdoConfidencialidadController extends Controller
{
    public function index()
    {
        $acuerdos = AcuerdoConfidencialidad::with(['user', 'curso'])->get();
        return view('acuerdos-confidencialidad.index', compact('acuerdos'));
    }

    public function create()
    {
        $cursos = Curso::all();
        $usuarios = null;
        $isDocente = auth()->user()->hasRole('Docente');
        
        // Si el usuario es administrador, obtener todos los usuarios
        if (auth()->user()->hasRole('admin')) {
            $usuarios = User::all();
        }
        
        return view('acuerdos-confidencialidad.create', compact('cursos', 'usuarios', 'isDocente'));
    }

    public function store(Request $request)
    {
        $isDocente = auth()->user()->hasRole('Docente');
        
        $validationRules = [
            'acuerdo' => 'required|file|mimes:pdf,jpg,jpeg,png,gif|max:10240',
        ];

        if (!$isDocente) {
            $validationRules['curso_id'] = 'required|exists:cursos,id';
        }

        $request->validate($validationRules);

        $archivo = $request->file('acuerdo');
        $ruta = $archivo->store('acuerdos-confidencialidad', 'public');

        // Determinar el user_id según el rol del usuario autenticado
        $userId = auth()->id();
        
        // Si el usuario es administrador y se proporciona un user_id, usar ese
        if (auth()->user()->hasRole('admin') && $request->has('user_id')) {
            $userId = $request->user_id;
        }

        AcuerdoConfidencialidad::create([
            'estado' => 'Pendiente',
            'acuerdo' => $ruta,
            'curso_id' => $isDocente ? null : $request->curso_id,
            'user_id' => $userId,
        ]);

        return redirect()->route('acuerdos-confidencialidad.index')
            ->with('success', 'Acuerdo de confidencialidad creado exitosamente.');
    }

    public function show(AcuerdoConfidencialidad $acuerdoConfidencialidad)
    {
        // Cargar las relaciones user y curso antes de pasar a la vista
        $acuerdoConfidencialidad->load(['user', 'curso']);
        return view('acuerdos-confidencialidad.show', compact('acuerdoConfidencialidad'));
    }

    public function edit(AcuerdoConfidencialidad $acuerdoConfidencialidad)
    {
        $cursos = Curso::all();
        return view('acuerdos-confidencialidad.edit', compact('acuerdoConfidencialidad', 'cursos'));
    }

    public function update(Request $request, AcuerdoConfidencialidad $acuerdoConfidencialidad)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,Entregado',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        if ($request->hasFile('acuerdo')) {
            $request->validate([
                'acuerdo' => 'required|file|mimes:pdf,jpg,jpeg,png,gif|max:10240',
            ]);

            // Eliminar archivo anterior
            if ($acuerdoConfidencialidad->acuerdo) {
                Storage::disk('public')->delete($acuerdoConfidencialidad->acuerdo);
            }

            $archivo = $request->file('acuerdo');
            $ruta = $archivo->store('acuerdos-confidencialidad', 'public');
            $acuerdoConfidencialidad->acuerdo = $ruta;
        }

        $acuerdoConfidencialidad->estado = $request->estado;
        $acuerdoConfidencialidad->curso_id = $request->curso_id;
        $acuerdoConfidencialidad->save();

        return redirect()->route('acuerdos-confidencialidad.index')
            ->with('success', 'Acuerdo de confidencialidad actualizado exitosamente.');
    }

    public function destroy(AcuerdoConfidencialidad $acuerdoConfidencialidad)
    {
        if ($acuerdoConfidencialidad->acuerdo) {
            Storage::disk('public')->delete($acuerdoConfidencialidad->acuerdo);
        }

        $acuerdoConfidencialidad->delete();

        return redirect()->route('acuerdos-confidencialidad.index')
            ->with('success', 'Acuerdo de confidencialidad eliminado exitosamente.');
    }

    public function previewPdf(Request $request)
    {
        // Verificar si el perfil está completo
        $usuario = auth()->user();
        if (!$usuario->profile || !$usuario->profile->isComplete()) {
            return redirect()->route('profile.complete')
                ->with('error', 'Debes completar tu perfil antes de descargar el acuerdo de confidencialidad.');
        }

        $isDocente = $usuario->hasRole('Docente');
        
        if ($isDocente) {
            $usuario = $usuario->load(['userProfile', 'roles']);
            
            // Si el usuario no tiene perfil, creamos un array con datos vacíos
            if (!$usuario->userProfile) {
                $usuario->userProfile = (object)[
                    'cedula' => 'N/A',
                    'direccion_calle' => 'N/A',
                    'direccion_numero' => 'N/A',
                    'ciudad' => 'N/A',
                    'provincia' => 'N/A',
                    'telefono' => 'N/A'
                ];
            }

            $fechaActual = now();
            $datos = [
                'usuario' => $usuario,
                'fecha' => [
                    'dia' => $fechaActual->format('d'),
                    'mes' => ucfirst($fechaActual->formatLocalized('%B')),
                    'año' => $fechaActual->format('Y'),
                    'ciudad' => $usuario->userProfile->ciudad ?? 'Quito'
                ]
            ];

            $pdf = PDF::loadView('acuerdos-confidencialidad.pdf-docente', $datos);
        } else {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'curso_id' => 'required|exists:cursos,id',
            ]);

            $usuario = User::with(['userProfile', 'roles'])->findOrFail($request->user_id);
            
            // Si el usuario no tiene perfil, creamos un array con datos vacíos
            if (!$usuario->userProfile) {
                $usuario->userProfile = (object)[
                    'cedula' => 'N/A',
                    'direccion_calle' => 'N/A',
                    'direccion_numero' => 'N/A',
                    'ciudad' => 'N/A',
                    'provincia' => 'N/A',
                    'telefono' => 'N/A'
                ];
            }

            $curso = Curso::findOrFail($request->curso_id);
            $fechaActual = now();
            $datos = [
                'usuario' => $usuario,
                'curso' => $curso,
                'fecha' => [
                    'dia' => $fechaActual->format('d'),
                    'mes' => ucfirst($fechaActual->formatLocalized('%B')),
                    'año' => $fechaActual->format('Y'),
                    'ciudad' => $usuario->userProfile->ciudad ?? 'Quito'
                ]
            ];

            $pdf = PDF::loadView('acuerdos-confidencialidad.pdf', $datos);
        }

        $pdf->setPaper('a4');
        return $pdf->stream('acuerdo-confidencialidad.pdf');
    }

    /**
     * Aprobar un acuerdo de confidencialidad.
     *
     * @param  \App\Models\AcuerdoConfidencialidad  $acuerdoConfidencialidad
     * @return \Illuminate\Http\RedirectResponse
     */
    public function aprobar(AcuerdoConfidencialidad $acuerdoConfidencialidad)
    {
        // Verificar si el usuario es administrador
        if (!auth()->user()->hasRole(1)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $acuerdoConfidencialidad->update(['estado' => 'Entregado']);

        return redirect()->back()->with('success', 'Acuerdo aprobado exitosamente.');
    }

    /**
     * Verifica si un usuario tiene acuerdos de confidencialidad
     * 
     * @param int $userId ID del usuario
     * @return bool
     */
    public static function tieneAcuerdoConfidencialidad($userId)
    {
        return AcuerdoConfidencialidad::where('user_id', $userId)->exists();
    }
} 