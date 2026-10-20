<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\EjemplarController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\CanjeController;
use App\Http\Controllers\RecompensaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MultaController;
require __DIR__.'/auth.php';

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('libros.catalogo')
        : redirect()->route('login');
});

// ---- Rutas para cualquier usuario autenticado ----
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
    $user = auth()->user();

    $reservasPendientes = \App\Models\Reserva::where('user_id', $user->id)
        ->where('estado', 'pendiente')
        ->count();

    $prestamosActivos = \App\Models\Prestamo::where('user_id', $user->id)
        ->whereNull('fecha_devolucion_real')
        ->with('ejemplar.libro')
        ->get();

    $misiones = \App\Models\Mision::where('activa', true)->get();

    return view('dashboard', compact('reservasPendientes', 'prestamosActivos', 'misiones'));
})->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/catalogo', [LibroController::class, 'catalogo'])->name('libros.catalogo');
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::patch('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    Route::get('/recompensas', [CanjeController::class, 'index'])->name('recompensas.index');
    Route::post('/recompensas/{recompensa}/canjear', [CanjeController::class, 'canjear'])->name('recompensas.canjear');
    Route::patch('/prestamos/{prestamo}/renovar', [PrestamoController::class, 'renovar'])->name('prestamos.renovar');
});

// ---- Bibliotecario y superadmin ----
Route::middleware(['auth', 'rol:bibliotecario,superadmin'])->group(function () {
    Route::resource('libros', LibroController::class)->except('show');
    Route::post('/libros/{libro}/ejemplares', [LibroController::class, 'agregarEjemplares'])->name('libros.ejemplares.store');
    Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
    Route::post('/reservas/{reserva}/entregar', [PrestamoController::class, 'entregar'])->name('prestamos.entregar');
    Route::patch('/prestamos/{prestamo}/devolver', [PrestamoController::class, 'devolver'])->name('prestamos.devolver');
    Route::patch('/canjes/{canje}/entregar', [CanjeController::class, 'marcarEntregado'])->name('canjes.entregar');
    Route::patch('/multas/{multa}/saldar', [MultaController::class, 'saldar'])->name('multas.saldar');
});

// ---- Solo superadmin ----
Route::middleware(['auth', 'rol:superadmin'])->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::patch('/usuarios/{user}/rol', [UsuarioController::class, 'updateRol'])->name('usuarios.rol');
    Route::resource('gestion-recompensas', RecompensaController::class);
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::patch('/multas/{multa}/saldar', [MultaController::class, 'saldar'])->name('multas.saldar');
});