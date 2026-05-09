<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rotas para CRUD de Pessoas - Módulo Secretaria - Fase 2.1
    // Todas as rotas exigem autenticação para segurança
    Route::prefix('people')->name('people.')->group(function () {
        // Listar todas as pessoas
        Route::get('/', [PersonController::class, 'index'])->name('index');
        
        // Mostrar formulário para criar nova pessoa
        Route::get('/create', [PersonController::class, 'create'])->name('create');
        
        // Salvar nova pessoa
        Route::post('/', [PersonController::class, 'store'])->name('store');
        
        // Mostrar detalhes de uma pessoa específica
        Route::get('/{person}', [PersonController::class, 'show'])->name('show');
        
        // Mostrar formulário para editar pessoa existente
        Route::get('/{person}/edit', [PersonController::class, 'edit'])->name('edit');
        
        // Atualizar pessoa existente
        Route::put('/{person}', [PersonController::class, 'update'])->name('update');
        
        // Remover pessoa (soft delete)
        Route::delete('/{person}', [PersonController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
