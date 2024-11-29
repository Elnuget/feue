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
    Route::resource('estados_academicos', EstadoAcademicoController::class);
    Route::get('/estados_academicos', [EstadoAcademicoController::class, 'index'])->name('estados_academicos.index');
    Route::get('/estados_academicos/create', [EstadoAcademicoController::class, 'create'])->name('estados_academicos.create');
    Route::post('/estados_academicos', [EstadoAcademicoController::class, 'store'])->name('estados_academicos.store');
    Route::get('/estados_academicos/{estado_academico}/edit', [EstadoAcademicoController::class, 'edit'])->name('estados_academicos.edit');
    Route::patch('/estados_academicos/{estado_academico}', [EstadoAcademicoController::class, 'update'])->name('estados_academicos.update');
    Route::delete('/estados_academicos/{estado_academico}', [EstadoAcademicoController::class, 'destroy'])->name('estados_academicos.destroy');
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
    Route::resource('user_profiles', UserProfileController::class)->only(['create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('/user_profiles/{user}', [UserProfileController::class, 'show'])->name('user_profiles.show');
});

require __DIR__.'/auth.php';
