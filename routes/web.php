<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EstadoAcademicoController;
use App\Http\Controllers\UniversidadController;
use App\Http\Controllers\TipoCursoController;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\UserProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::resource('estados_academicos', EstadoAcademicoController::class)->parameters([
        'estados_academicos' => 'estado_academico'
    ]);
    Route::resource('universidades', UniversidadController::class)->parameters([
        'universidades' => 'universidad'
    ]);
    Route::resource('tipos_cursos', TipoCursoController::class)->parameters([
        'tipos_cursos' => 'tipoCurso'
    ]);
    Route::resource('metodos_pago', MetodoPagoController::class)->parameters([
        'metodos_pago' => 'metodoPago'
    ]);
    Route::resource('cursos', CursoController::class);
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create');
    Route::get('/cursos/{curso}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::resource('user_profiles', UserProfileController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('/user_profiles/{user}', [UserProfileController::class, 'show'])->name('user_profiles.show');
    Route::resource('user_academicos', \App\Http\Controllers\UserAcademicoController::class);
    Route::resource('user_aspiraciones', \App\Http\Controllers\UserAspiracionController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
