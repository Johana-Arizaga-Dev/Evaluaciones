<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TipoPreguntaController;

// Página de inicio redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Evaluaciones (accesibles para todos los autenticados)
    Route::get('/evaluaciones-pendientes', [EvaluacionController::class, 'pendientes'])->name('evaluaciones.pendientes');
    Route::get('/evaluaciones/create', [EvaluacionController::class, 'create'])->name('evaluaciones.create');
    Route::post('/evaluaciones', [EvaluacionController::class, 'store'])->name('evaluaciones.store');
    Route::get('/evaluaciones/{evaluacion}', [EvaluacionController::class, 'show'])->name('evaluaciones.show');
    Route::get('/evaluaciones/{evaluacion}/download', [EvaluacionController::class, 'downloadPDF'])->name('evaluaciones.download');
    Route::get('/evaluaciones', [EvaluacionController::class, 'index'])->name('evaluaciones.index');
    Route::delete('/evaluaciones/{evaluacion}', [EvaluacionController::class, 'destroy'])->name('evaluaciones.destroy');
    // Evaluaciones
Route::resource('evaluaciones', EvaluacionController::class)->except(['edit', 'update']);
Route::get('/evaluaciones-pendientes', [EvaluacionController::class, 'pendientes'])->name('evaluaciones.pendientes');
Route::get('/evaluaciones/{evaluacion}/download', [EvaluacionController::class, 'downloadPDF'])->name('evaluaciones.download');



    // Formularios (solo para quienes pueden crearlos)
    Route::middleware([])->group(function () {
        Route::resource('formularios', FormularioController::class);
    });

    // Administración (solo nivel 1)
    Route::middleware([])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('puestos', PuestoController::class);
        Route::resource('areas', AreaController::class);
        Route::resource('tipo-preguntas', TipoPreguntaController::class);

        // Ruta para ver puestos por nivel
        Route::get('puestos/nivel/{nivel}', [PuestoController::class, 'porNivel'])->name('puestos.por-nivel');
    });
});
