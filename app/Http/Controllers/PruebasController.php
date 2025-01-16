<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Asistencia;
use App\Models\Pago; // Agregar import si no existe
use Illuminate\Http\Request;

class PruebasController extends Controller
{
    public function index()
    {
        $users = User::with('profile')->get(); // Agregar relación con profile
        $cursos = Curso::all();
        $matriculas = Matricula::with(['curso', 'usuario'])->get();
        $asistencias = Asistencia::with('user')->get();
        $listas = Matricula::with(['curso', 'usuario'])->get();

        // Calcular estadísticas
        $totalEstudiantes = Matricula::distinct('usuario_id')->count();
        $cursosActivos = Curso::where('estado', 'activo')->count();
        $cursosInactivos = Curso::where('estado', 'inactivo')->count();
        $matriculasPorCurso = Matricula::with('curso')
            ->selectRaw('curso_id, COUNT(*) as total')
            ->whereHas('curso', function($q){
                $q->where('estado', 'activo');
            })
            ->groupBy('curso_id')
            ->get();
        $matriculasPorEstado = Matricula::selectRaw('estado_matricula, COUNT(*) as total')
            ->groupBy('estado_matricula')
            ->get();
        $totalAsistencias = Asistencia::count();
        $estudiantesConDeudas = Matricula::where('valor_pendiente', '>', 0)->distinct('usuario_id')->count();
        $totalUsuarios = User::count();
        $pagosRecibidos = Pago::where('estado', 'Aprobado')->sum('monto'); // Ajustar según tu lógica de pagos

        return view('pruebas.index', compact(
            'users',
            'cursos',
            'matriculas',
            'asistencias',
            'listas',
            'totalEstudiantes',
            'cursosActivos',
            'cursosInactivos',
            'matriculasPorCurso',
            'matriculasPorEstado',
            'totalAsistencias',
            'estudiantesConDeudas',
            'totalUsuarios',
            'pagosRecibidos'
        ));
    }
}
