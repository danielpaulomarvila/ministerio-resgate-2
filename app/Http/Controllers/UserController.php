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
        $term = trim((string) $request->query('q', ''));

        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $users = User::query()
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'email']);
        
        return response()->json($users);
    }
}
