<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\AccessProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * Controller para gerenciar Perfis de Acesso de Usuários
 * 
 * Permite vincular e desvincular perfis de acesso a usuários
 * Registra quem atribuiu o perfil e quando
 */
class UserAccessProfileController extends Controller
{
    /**
     * Mostra o formulário para editar perfis de um usuário
     */
    public function edit(User $user)
    {
        $user->load(['person', 'accessProfiles']);

        $availableProfiles = AccessProfile::active()
            ->orderBy('sort_order')
            ->get();

        $userProfileIds = $user->accessProfiles->pluck('id')->toArray();

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'person' => $user->person ? [
                'id' => $user->person->id,
                'full_name' => $user->person->full_name,
                'email' => $user->person->email,
            ] : null,
            'access_profiles' => $user->accessProfiles->map(function ($profile) {
                return [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'slug' => $profile->slug,
                    'is_system' => $profile->is_system,
                ];
            }),
        ];

        return Inertia::render('Secretaria/Access/UserProfiles', [
            'user' => $userData,
            'availableProfiles' => $availableProfiles,
            'userProfileIds' => $userProfileIds,
        ]);
    }

    /**
     * Atualiza os perfis de acesso de um usuário
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'profiles' => 'array',
            'profiles.*' => 'exists:access_profiles,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $profileIds = $validated['profiles'] ?? [];

        // Sincronizar perfis com rastreio de quem atribuiu
        $syncData = [];
        foreach ($profileIds as $profileId) {
            $syncData[$profileId] = [
                'assigned_by_user_id' => Auth::id(),
                'assigned_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ];
        }

        $user->accessProfiles()->sync($syncData);

        return back()->with('success', 'Perfis de acesso atualizados com sucesso.');
    }
}
