<?php

use App\Http\Controllers\FamilyController;
use App\Http\Controllers\GuardianshipController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecretaryAlertController;
use App\Http\Controllers\SecretaryDashboardController;
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

Route::get('/secretaria', [SecretaryDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('secretaria.dashboard');

// Rotas para Alertas da Secretaria - Etapa 5
// Todas as rotas exigem autenticação para segurança
Route::prefix('secretaria/alertas')->name('secretaria.alerts.')->group(function () {
    // Listar todos os alertas
    Route::get('/', [SecretaryAlertController::class, 'index'])->name('index');
    
    // Mostrar detalhes de um alerta específico
    Route::get('/{systemAlert}', [SecretaryAlertController::class, 'show'])->name('show');
    
    // Mostrar tela de resolução/tratamento do alerta
    Route::get('/{systemAlert}/resolver', [SecretaryAlertController::class, 'resolveShow'])->name('resolve.show');
    
    // Marcar alerta como em andamento
    Route::patch('/{systemAlert}/em-andamento', [SecretaryAlertController::class, 'markInProgress'])->name('mark-in-progress');
    
    // Verificar se o alerta foi realmente resolvido
    Route::post('/{systemAlert}/verificar-resolucao', [SecretaryAlertController::class, 'verifyResolution'])->name('verify-resolution');
    
    // Marcar alerta como ignorado
    Route::patch('/{systemAlert}/ignorar', [SecretaryAlertController::class, 'ignore'])->name('ignore');
    
    // Regenerar alertas com base nas regras atuais
    Route::post('/regenerar', [SecretaryAlertController::class, 'regenerate'])->name('regenerate');
});

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
    
    // Rotas para CRUD de Famílias - Módulo Secretaria - Etapa 2
    // Todas as rotas exigem autenticação para segurança
    Route::prefix('families')->name('families.')->group(function () {
        // Listar todas as famílias
        Route::get('/', [FamilyController::class, 'index'])->name('index');
        
        // Mostrar formulário para criar nova família
        Route::get('/create', [FamilyController::class, 'create'])->name('create');
        
        // Salvar nova família
        Route::post('/', [FamilyController::class, 'store'])->name('store');
        
        // Mostrar detalhes de uma família específica
        Route::get('/{family}', [FamilyController::class, 'show'])->name('show');
        
        // Mostrar formulário para editar família existente
        Route::get('/{family}/edit', [FamilyController::class, 'edit'])->name('edit');
        
        // Atualizar família existente
        Route::put('/{family}', [FamilyController::class, 'update'])->name('update');
        
        // Remover família (soft delete)
        Route::delete('/{family}', [FamilyController::class, 'destroy'])->name('destroy');
        
        // Vincular pessoa à família
        Route::post('/{family}/attach', [FamilyController::class, 'attachPerson'])->name('attachPerson');
        
        // Atualizar vínculo de membro
        Route::put('/{family}/members/{member}', [FamilyController::class, 'updateMember'])->name('updateMember');
        
        // Desvincular pessoa da família (soft detach)
        Route::delete('/{family}/members/{member}', [FamilyController::class, 'detachPerson'])->name('detachPerson');
    });
    
    // Rotas para CRUD de Responsáveis e Supervisores - Módulo Secretaria - Etapa 3
    // Todas as rotas exigem autenticação para segurança
    Route::prefix('guardianships')->name('guardianships.')->group(function () {
        // Listar todas as responsabilidades
        Route::get('/', [GuardianshipController::class, 'index'])->name('index');
        
        // Mostrar formulário para criar nova responsabilidade
        Route::get('/create', [GuardianshipController::class, 'create'])->name('create');
        
        // Salvar nova responsabilidade
        Route::post('/', [GuardianshipController::class, 'store'])->name('store');
        
        // Mostrar detalhes de uma responsabilidade específica
        Route::get('/{guardianship}', [GuardianshipController::class, 'show'])->name('show');
        
        // Mostrar formulário para editar responsabilidade existente
        Route::get('/{guardianship}/edit', [GuardianshipController::class, 'edit'])->name('edit');
        
        // Atualizar responsabilidade existente
        Route::put('/{guardianship}', [GuardianshipController::class, 'update'])->name('update');
        
        // Encerrar responsabilidade (soft delete com status 'ended')
        Route::delete('/{guardianship}', [GuardianshipController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
