<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller para gerenciar usuários
 */
class UserController extends Controller
{
    /**
     * Busca usuários por nome para autocomplete
     * 
     * @param Request $request Request com parâmetro 'q' para busca
     * @return JsonResponse JSON com resultados da busca
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        
        $users = User::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name']);
        
        return response()->json($users);
    }
}
