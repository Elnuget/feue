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
        $acuerdos = AcuerdoConfidencialidad::with(['user', 'curso'])
            ->orderBy('id', 'desc')
            ->get();

        $usuarios = User::all();
        $cursos = Curso::with('tipoCurso')->get();

        return view('acuerdos-confidencialidad.index', compact('acuerdos', 'usuarios', 'cursos'));
    }

    public function create()
    {
        $cursos = Curso::with('tipoCurso')->get();
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
        $user = auth()->user();
        $isDocente = $user->hasRole('Docente');

        $rules = [
            'user_id' => 'required|exists:users,id',
            'acuerdo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240'
        ];

        if (!$isDocente) {
            $rules['curso_id'] = 'required|exists:cursos,id';
        }

        $validatedData = $request->validate($rules);

        $acuerdo = new AcuerdoConfidencialidad();
        $acuerdo->user_id = $validatedData['user_id'];
        if (!$isDocente) {
            $acuerdo->curso_id = $validatedData['curso_id'];
        }
        $acuerdo->estado = 'Pendiente';

        if ($request->hasFile('acuerdo')) {
            $path = $request->file('acuerdo')->store('acuerdos', 'public');
            $acuerdo->acuerdo = $path;
        }

        $acuerdo->save();

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
            setlocale(LC_TIME, 'es_ES.utf8');
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
            return $pdf->stream('acuerdo-confidencialidad-docente.pdf');
        } else {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'curso_id' => 'required|exists:cursos,id',
            ]);

            $usuario = User::with('userProfile')->findOrFail($request->user_id);
            $curso = Curso::findOrFail($request->curso_id);

            $datos = [
                'usuario' => $usuario,
                'curso' => $curso
            ];

            $pdf = PDF::loadView('acuerdos-confidencialidad.pdf', $datos);
            return $pdf->stream('acuerdo-confidencialidad.pdf');
        }
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