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
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\PruebasController;
use App\Http\Controllers\AulaVirtualController;

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

Route::get('/', [CursoController::class, 'welcome']);

Route::get('/dashboard', [CursoController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Agregar la ruta checkCedula fuera del grupo de middleware 'auth' y 'verified'
Route::post('/user_profiles/check-cedula', [UserProfileController::class, 'checkCedula'])->name('user_profiles.checkCedula');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('matriculas/print-credentials', [MatriculaController::class, 'printCredentials'])->name('matriculas.printCredentials');
    
    Route::resource('roles', RoleController::class);
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/{user}/qr', [\App\Http\Controllers\UserController::class, 'showQR'])->name('users.qr');
    Route::resource('estados_academicos', EstadoAcademicoController::class)->parameters([
        'estados_academicos' => 'estado_academico'
    ]);
    Route::resource('universidades', UniversidadController::class)->parameters([
        'universidades' => 'universidad'
    ]);
    Route::resource('tipos_cursos', TipoCursoController::class)->parameters([
        'tipos_cursos' => 'tipoCurso'
    ]);
    Route::get('/tipos_cursos', [TipoCursoController::class, 'index'])->name('tipos_cursos.index');
    Route::get('/tipos_cursos/create', [TipoCursoController::class, 'create'])->name('tipos_cursos.create');
    Route::get('/tipos_cursos/{tipoCurso}/edit', [TipoCursoController::class, 'edit'])->name('tipos_cursos.edit');
    Route::resource('metodos_pago', MetodoPagoController::class)->parameters([
        'metodos_pago' => 'metodoPago'
    ]);
    Route::resource('cursos', CursoController::class);
    Route::resource('aulas_virtuales', AulaVirtualController::class);
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create');
    Route::get('/cursos/{curso}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::post('/cursos/{curso}/disable', [CursoController::class, 'disable'])->name('cursos.disable');
    Route::post('/cursos/disable-multiple', [CursoController::class, 'disableMultiple'])->name('cursos.disableMultiple');
    Route::delete('/cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy'); // Ensure correct delete route
    Route::get('/cursos/disable-multiple', function () {
        return redirect()->route('cursos.index');
    });
    Route::resource('user_profiles', UserProfileController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::resource('user_academicos', \App\Http\Controllers\UserAcademicoController::class);
    Route::resource('user_aspiraciones', \App\Http\Controllers\UserAspiracionController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::resource('documentos', DocumentoController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('matriculas', MatriculaController::class);
    Route::resource('pagos', PagoController::class);
    Route::post('/pagos/{pago}/aprobar', [PagoController::class, 'aprobar'])->name('pagos.aprobar');
    Route::post('/pagos/{pago}/rechazar', [PagoController::class, 'rechazar'])->name('pagos.rechazar');
    Route::post('/matriculas/{matricula}/aprobar', [MatriculaController::class, 'aprobar'])->name('matriculas.aprobar');
    Route::post('/matriculas/{matricula}/rechazar', [MatriculaController::class, 'rechazar'])->name('matriculas.rechazar');
    Route::post('/matriculas/upload-background', [MatriculaController::class, 'uploadBackground'])->name('matriculas.uploadBackground');
    Route::get('/complete-profile', [UserProfileController::class, 'completeProfile'])->name('profile.complete');
    Route::post('/complete-profile', [UserProfileController::class, 'storeCompleteProfile'])->name('profile.storeComplete');
    Route::get('/listas', [MatriculaController::class, 'listas'])->name('matriculas.listas');
    Route::get('/listas/export-pdf', [MatriculaController::class, 'exportPdf'])->name('matriculas.exportPdf');
    Route::get('/listas/export-excel', [MatriculaController::class, 'exportExcel'])->name('matriculas.exportExcel');
    Route::get('/asistencias/scan', [AsistenciaController::class, 'scanQR'])->name('asistencias.scan');
    Route::post('/asistencias/register-scan', [AsistenciaController::class, 'registerScan'])->name('asistencias.registerScan');
    Route::resource('asistencias', AsistenciaController::class);
    Route::get('/pruebas', [PruebasController::class, 'index'])->name('pruebas');
    Route::post('/user_profiles/{user}/upload_photo', [ProfileController::class, 'uploadPhoto'])->name('profile.uploadPhoto');
    Route::get('/pagos/{pago}/recibo', [PagoController::class, 'generarRecibo'])->name('pagos.recibo');
    Route::get('/aulas_virtuales/{aulasVirtuale}/show', [AulaVirtualController::class, 'show'])
         ->name('aulas_virtuales.show');
    
    Route::post('/aulas_virtuales/{aulasVirtuale}/contenidos', [AulaVirtualController::class, 'storeContenido'])
         ->name('aulas_virtuales.contenidos.store');
    
    Route::delete('/aulas_virtuales/contenidos/{id}', [AulaVirtualController::class, 'destroyContenido'])
         ->name('aulas_virtuales.contenidos.destroy');
});
require __DIR__.'/auth.php';
