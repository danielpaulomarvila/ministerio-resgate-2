<?php

use App\Http\Controllers\Familia\FamilyHubController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\GuardianshipController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Secretaria\AccessProfileController;
use App\Http\Controllers\Secretaria\DepartmentController;
use App\Http\Controllers\Secretaria\DepartmentPersonController;
use App\Http\Controllers\Secretaria\UserAccessProfileController;
use App\Http\Controllers\SecretaryAlertController;
use App\Http\Controllers\SecretaryDashboardController;
use App\Http\Controllers\SecretaryRequestController;
use App\Http\Controllers\SecretaryUserAccessController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Public/Inicio', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Rotas públicas reais do site institucional.
// Cada tela pública renderiza seu próprio componente para evitar navegação por hash/âncora.
Route::get('/inicio', function () {
    return Inertia::render('Public/Inicio', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.inicio');

Route::get('/sobre_nos', function () {
    return Inertia::render('Public/SobreNos', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.sobre_nos');

Route::get('/cultos', function () {
    return Inertia::render('Public/Cultos', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.cultos');

Route::get('/eventos', function () {
    return Inertia::render('Public/Eventos', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.eventos');

Route::get('/contato', function () {
    return Inertia::render('Public/Contato', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.contato');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/familia', [FamilyHubController::class, 'index'])
    ->middleware(['auth'])
    ->name('familia.index');

Route::get('/secretaria', [SecretaryDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('secretaria.dashboard');

// Rotas para Alertas da Secretaria - Etapa 5
// Todas as rotas exigem autenticação para segurança
Route::prefix('secretaria/alertas')->name('secretaria.alerts.')->middleware(['auth'])->group(function () {
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

// Rotas para Solicitações da Secretaria - Etapa 6
// Todas as rotas exigem autenticação para segurança
Route::prefix('secretaria/solicitacoes')->name('secretaria.requests.')->middleware(['auth'])->group(function () {
    // Listar todas as solicitações
    Route::get('/', [SecretaryRequestController::class, 'index'])->name('index');

    // Mostrar formulário de criação
    Route::get('/criar', [SecretaryRequestController::class, 'create'])->name('create');

    // Salvar nova solicitação
    Route::post('/', [SecretaryRequestController::class, 'store'])->name('store');

    // Mostrar detalhes de uma solicitação
    Route::get('/{secretaryRequest}', [SecretaryRequestController::class, 'show'])->name('show');

    // Mostrar formulário de edição
    Route::get('/{secretaryRequest}/editar', [SecretaryRequestController::class, 'edit'])->name('edit');

    // Atualizar solicitação
    Route::put('/{secretaryRequest}', [SecretaryRequestController::class, 'update'])->name('update');
    Route::patch('/{secretaryRequest}', [SecretaryRequestController::class, 'update'])->name('update');

    // Marcar solicitação como em análise
    Route::patch('/{secretaryRequest}/em-analise', [SecretaryRequestController::class, 'markInReview'])->name('mark-in-review');

    // Aprovar solicitação
    Route::patch('/{secretaryRequest}/aprovar', [SecretaryRequestController::class, 'approve'])->name('approve');

    // Rejeitar solicitação
    Route::patch('/{secretaryRequest}/rejeitar', [SecretaryRequestController::class, 'reject'])->name('reject');

    // Concluir solicitação
    Route::patch('/{secretaryRequest}/concluir', [SecretaryRequestController::class, 'complete'])->name('complete');

    // Cancelar solicitação
    Route::patch('/{secretaryRequest}/cancelar', [SecretaryRequestController::class, 'cancel'])->name('cancel');
});

// Rotas para Acessos do Sistema - Etapa 7
// Todas as rotas exigem autenticação para segurança
Route::prefix('secretaria/acessos')->name('secretaria.access.')->middleware(['auth'])->group(function () {
    // Verificar elegibilidade de pessoa (deve vir antes da rota dinâmica)
    Route::get('/elegibilidade/{person}', [SecretaryUserAccessController::class, 'eligibility'])->name('eligibility');

    // Listar todos os acessos
    Route::get('/', [SecretaryUserAccessController::class, 'index'])->name('index');

    // Mostrar formulário para criar novo acesso
    Route::get('/criar', [SecretaryUserAccessController::class, 'create'])->name('create');

    // Salvar novo acesso
    Route::post('/', [SecretaryUserAccessController::class, 'store'])->name('store');

    // Mostrar detalhes de um acesso
    Route::get('/{user}', [SecretaryUserAccessController::class, 'show'])->name('show');

    // Mostrar formulário para editar acesso
    Route::get('/{user}/editar', [SecretaryUserAccessController::class, 'edit'])->name('edit');

    // Atualizar acesso
    Route::put('/{user}', [SecretaryUserAccessController::class, 'update'])->name('update');

    // Suspender acesso
    Route::patch('/{user}/suspender', [SecretaryUserAccessController::class, 'suspend'])->name('suspend');

    // Reativar acesso
    Route::patch('/{user}/reativar', [SecretaryUserAccessController::class, 'reactivate'])->name('reactivate');

    // Gerenciar perfis de acesso de um usuário
    Route::get('/{user}/perfis', [UserAccessProfileController::class, 'edit'])->name('perfis.edit');
    Route::put('/{user}/perfis', [UserAccessProfileController::class, 'update'])->name('perfis.update');
});

// Rotas para Perfis de Acesso - Secretaria - Etapa 8
Route::prefix('secretaria/perfis-acesso')->name('secretaria.access-profiles.')->middleware(['auth'])->group(function () {
    Route::get('/', [AccessProfileController::class, 'index'])->name('index');
    Route::get('/criar', [AccessProfileController::class, 'create'])->name('create');
    Route::post('/', [AccessProfileController::class, 'store'])->name('store');
    Route::get('/{accessProfile}', [AccessProfileController::class, 'show'])->name('show');
    Route::get('/{accessProfile}/editar', [AccessProfileController::class, 'edit'])->name('edit');
    Route::put('/{accessProfile}', [AccessProfileController::class, 'update'])->name('update');
    Route::delete('/{accessProfile}', [AccessProfileController::class, 'destroy'])->name('destroy');
});

// Rotas para Departamentos - Secretaria - Etapa 10
Route::prefix('secretaria/departamentos')->name('secretaria.departments.')->middleware(['auth'])->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('index');
    Route::get('/criar', [DepartmentController::class, 'create'])->name('create');
    Route::post('/', [DepartmentController::class, 'store'])->name('store');
    Route::get('/{department}', [DepartmentController::class, 'show'])->name('show');
    Route::get('/{department}/editar', [DepartmentController::class, 'edit'])->name('edit');
    Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
    Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');

    // Rotas para vínculos pessoa-departamento
    Route::post('/{department}/pessoas', [DepartmentPersonController::class, 'store'])->name('people.store');
    Route::put('/{department}/pessoas/{departmentPerson}', [DepartmentPersonController::class, 'update'])->name('people.update');
    Route::delete('/{department}/pessoas/{departmentPerson}', [DepartmentPersonController::class, 'destroy'])->name('people.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas para CRUD de Pessoas - Módulo Secretaria - Fase 2.1
    // Todas as rotas exigem autenticação para segurança
    Route::prefix('people')->name('people.')->group(function () {
        // Buscar pessoas por nome para autocomplete (deve vir antes das rotas dinâmicas)
        Route::get('/search', [PersonController::class, 'search'])->name('search');

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

    // Rotas para busca de usuários - Autocomplete (protegidas por autenticação)
    Route::prefix('users')->name('users.')->group(function () {
        // Buscar usuários por nome para autocomplete
        Route::get('/search', [UserController::class, 'search'])->name('search');
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
