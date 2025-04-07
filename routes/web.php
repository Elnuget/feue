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
use App\Http\Controllers\SesionDocenteController;
use App\Http\Controllers\AsistenciaDocenteController;
use App\Http\Controllers\CuestionarioController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\CredencialesDocenteController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\AcuerdoConfidencialidadController;

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

// Ruta pública para ver certificados
Route::get('/certificados/{certificado}', [CertificadoController::class, 'show'])->name('certificados.show');
Route::get('/certificados/{certificado}/pdf', [CertificadoController::class, 'pdf'])->name('certificados.pdf');
Route::get('/certificados/pdf/multiple', [CertificadoController::class, 'pdfMultiple'])->name('certificados.pdf.multiple');

Route::get('/dashboard', [CursoController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Rutas protegidas de certificados
    Route::resource('certificados', CertificadoController::class)->except(['show']);
});

// Agregar la ruta checkCedula fuera del grupo de middleware 'auth' y 'verified'
Route::post('/user_profiles/check-cedula', [UserProfileController::class, 'checkCedula'])->name('user_profiles.checkCedula');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('matriculas/print-credentials', [MatriculaController::class, 'printCredentials'])->name('matriculas.printCredentials');
    Route::get('matriculas/print-certificates', [MatriculaController::class, 'printCertificates'])->name('matriculas.printCertificates');
    Route::post('certificados/store-multiple', [CertificadoController::class, 'storeMultiple'])->name('certificados.store-multiple');
    
    // Rutas para búsqueda de usuarios y obtención de información
    Route::get('/usuarios/search', [\App\Http\Controllers\UserController::class, 'search'])->name('usuarios.search');
    Route::get('/usuarios/{id}/info', [\App\Http\Controllers\UserController::class, 'getInfo'])->name('usuarios.info');
    
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
    Route::post('/cursos/{curso}/enable', [CursoController::class, 'enable'])->name('cursos.enable');
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
    Route::get('/listas/export-pendientes-excel', [MatriculaController::class, 'exportPendientesExcel'])->name('matriculas.exportPendientesExcel');
    Route::get('/asistencias/scan', [AsistenciaController::class, 'scanQR'])->name('asistencias.scan');
    Route::post('/asistencias/register-scan', [AsistenciaController::class, 'registerScan'])->name('asistencias.registerScan');
    Route::post('/asistencias/register-multiple', [AsistenciaController::class, 'registerMultiple'])->name('asistencias.registerMultiple');
    Route::resource('asistencias', AsistenciaController::class);
    Route::get('/pruebas', [PruebasController::class, 'index'])->name('pruebas');
    Route::post('/user_profiles/{user}/upload_photo', [ProfileController::class, 'uploadPhoto'])->name('profile.uploadPhoto');
    Route::get('/pagos/{pago}/recibo', [PagoController::class, 'generarRecibo'])->name('pagos.recibo');
    Route::post('/aulas_virtuales/{aulasVirtuale}/contenidos', [AulaVirtualController::class, 'storeContenido'])
         ->name('aulas_virtuales.contenidos.store');
    
    Route::delete('/aulas_virtuales/contenidos/{id}', [AulaVirtualController::class, 'destroyContenido'])
         ->name('aulas_virtuales.contenidos.destroy');

    // Rutas de Sesiones Docentes
    Route::resource('sesiones-docentes', SesionDocenteController::class);
    Route::get('sesiones-docentes-export', [SesionDocenteController::class, 'export'])->name('sesiones-docentes.export');

    // Rutas para asistencias docentes
    Route::resource('asistencias-docentes', AsistenciaDocenteController::class)->parameters([
        'asistencias-docentes' => 'asistenciaDocente'
    ])->names([
        'index' => 'asistencias-docentes.index',
        'create' => 'asistencias-docentes.create',
        'store' => 'asistencias-docentes.store',
        'show' => 'asistencias-docentes.show',
        'edit' => 'asistencias-docentes.edit',
        'update' => 'asistencias-docentes.update',
        'destroy' => 'asistencias-docentes.destroy',
    ]);
    Route::get('/asistencias/reporte-mensual', [AsistenciaDocenteController::class, 'reporteMensual'])->name('asistencias.reporte-mensual');
    Route::post('/asistencias/get-data', [AsistenciaController::class, 'getAsistencias'])->name('asistencias.getData');

    // Dentro del grupo de rutas con middleware auth
    Route::get('/cuestionarios/{cuestionario}/revision', [CuestionarioController::class, 'revision'])
         ->name('cuestionarios.revision');

    Route::get('/matriculas/{matricula}/calificaciones', [MatriculaController::class, 'calificaciones'])
         ->name('matriculas.calificaciones');

    // Nueva ruta para agregar preguntas
    Route::post('/cuestionarios/{cuestionario}/preguntas', [CuestionarioController::class, 'agregarPregunta'])
         ->name('cuestionarios.preguntas.store');

    // Dentro del grupo de middleware auth
    Route::get('/cuestionarios/{cuestionario}/preguntas', [CuestionarioController::class, 'obtenerPreguntas'])
         ->name('cuestionarios.preguntas.index');

    Route::get('/asistencias/usuario/{user}', [AsistenciaController::class, 'usuarioAsistencias'])->name('asistencias.usuario');
});

// Rutas para cuestionarios
Route::middleware(['auth'])->group(function () {
    Route::get('/aulas-virtuales/{aulaVirtual}/cuestionarios/create', [CuestionarioController::class, 'create'])
         ->name('cuestionarios.create');
    
    Route::post('/aulas-virtuales/{aulaVirtual}/cuestionarios', [CuestionarioController::class, 'store'])
         ->name('cuestionarios.store');
    
    Route::get('/cuestionarios/{cuestionario}', [CuestionarioController::class, 'show'])
         ->name('cuestionarios.show');
    
    Route::post('/intentos/{intento}/respuestas', [CuestionarioController::class, 'guardarRespuesta'])
         ->name('cuestionarios.guardar-respuesta');
    
    Route::post('/intentos/{intento}/finalizar', [CuestionarioController::class, 'finalizar'])
         ->name('cuestionarios.finalizar');

    // Ruta para obtener una pregunta específica
    Route::get('/preguntas/{pregunta}/obtener', [CuestionarioController::class, 'obtenerPregunta'])
         ->name('preguntas.obtener');

    // Rutas para actualizar y eliminar preguntas
    Route::put('/preguntas/{pregunta}', [CuestionarioController::class, 'updatePregunta'])
         ->name('preguntas.update');
    Route::delete('/preguntas/{pregunta}', [CuestionarioController::class, 'destroyPregunta'])
         ->name('preguntas.destroy');

    // Nuevas rutas para funciones administrativas de cuestionarios
    Route::post('/cuestionarios/{cuestionario}/toggle-estado', [CuestionarioController::class, 'toggleEstado'])
         ->name('cuestionarios.toggle-estado');
    
    Route::post('/cuestionarios/{cuestionario}/actualizar-config', [CuestionarioController::class, 'actualizarConfig'])
         ->name('cuestionarios.actualizar-config');
    
    Route::post('/cuestionarios/{cuestionario}/programar', [CuestionarioController::class, 'programar'])
         ->name('cuestionarios.programar');
    
    Route::delete('/cuestionarios/{cuestionario}', [CuestionarioController::class, 'destroy'])
         ->name('cuestionarios.destroy');

    Route::get('/cuestionarios/{cuestionario}/edit', [CuestionarioController::class, 'edit'])
         ->name('cuestionarios.edit');
         
    Route::put('/cuestionarios/{cuestionario}', [CuestionarioController::class, 'update'])
         ->name('cuestionarios.update');

    // Rutas para cuestionarios
    Route::patch('/cuestionarios/{cuestionario}/toggle', [CuestionarioController::class, 'toggle'])
        ->name('cuestionarios.toggle')
        ->middleware(['can:update,cuestionario']);
    
    Route::get('/cuestionarios/{cuestionario}/resultados', [CuestionarioController::class, 'resultados'])
        ->name('cuestionarios.resultados')
        ->middleware(['can:view,cuestionario']);
});

Route::delete('/preguntas/{pregunta}', [CuestionarioController::class, 'eliminarPregunta'])
     ->name('preguntas.destroy');

// Rutas de tareas y entregas
Route::middleware(['auth'])->group(function () {
    // Rutas de tareas
    Route::post('/aulas-virtuales/{aula}/tareas', [TareaController::class, 'store'])->name('tareas.store');
    Route::get('/tareas/{tarea}/edit', [TareaController::class, 'edit'])->name('tareas.edit');
    Route::put('/tareas/{tarea}', [TareaController::class, 'update'])->name('tareas.update');
    Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');
    Route::patch('/tareas/{tarea}/toggle-estado', [TareaController::class, 'toggleEstado'])->name('tareas.toggle-estado');
    
    // Rutas de entregas
    Route::post('/tareas/{tarea}/entregar', [EntregaController::class, 'store'])->name('tareas.entregar');
    Route::delete('/entregas/{entrega}', [EntregaController::class, 'destroy'])->name('entregas.destroy');
    Route::post('/tareas/{tarea}/entregas/{entrega}/calificar', [EntregaController::class, 'calificar'])->name('tareas.calificar');
    Route::get('/tareas/{tarea}/entregas', [EntregaController::class, 'obtenerEntregas'])->name('tareas.entregas');
});

// Rutas para credenciales docentes
Route::get('/credenciales-docentes', [CredencialesDocenteController::class, 'index'])->name('credenciales-docentes.index');
Route::get('/credenciales-docentes/{id}', [CredencialesDocenteController::class, 'show'])->name('credenciales-docentes.show');
Route::get('/credenciales-docentes-print', [CredencialesDocenteController::class, 'printCredentials'])->name('credenciales-docentes.print');
Route::post('/credenciales-docentes/update-status', [CredencialesDocenteController::class, 'updateStatus'])->name('credenciales-docentes.updateStatus');

// Rutas para acuerdos de confidencialidad
Route::resource('acuerdos-confidencialidad', AcuerdoConfidencialidadController::class);

require __DIR__.'/auth.php';