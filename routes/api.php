<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar rotas API para seu aplicativo.
| Estas rotas são carregadas pelo RouteServiceProvider e todas
| elas serão atribuídas ao grupo de middleware "api".
| Certifique-se de adicionar o middleware de autenticação quando necessário.
|
| NOTA: Esta API está em fase inicial de preparação.
| A API completa para app mobile será criada em fase futura.
|
*/

// Health check endpoint - para monitoramento e verificação de status
Route::get('/health', function (Request $request) {
    return response()->json([
        'status' => 'ok',
        'service' => 'Ministério Resgate API',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
        'environment' => app()->environment(),
    ]);
})->name('api.health');

// NOTA: Endpoints futuros para app mobile serão adicionados aqui
// Exemplos planejados para fase futura:
// - /api/v1/auth/login
// - /api/v1/auth/logout
// - /api/v1/people
// - /api/v1/families
// - /api/v1/profile
// 
// Estes endpoints usarão:
// - Laravel Sanctum para autenticação por token
// - Resources/DTOs para transformação de dados
// - Rate limiting para proteção
// - Policies para autorização
