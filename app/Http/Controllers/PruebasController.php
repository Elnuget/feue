<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Asistencia;
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

        return view('pruebas.index', compact(
            'users',
            'cursos',
            'matriculas',
            'asistencias',
            'listas'
        ));
    }
}
