<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\AccessProfile;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

/**
 * Controller para gerenciar Perfis de Acesso
 * 
 * Permite criar, visualizar, editar e desativar perfis de acesso do sistema
 * Perfis do sistema não podem ser deletados, apenas desativados
 */
class AccessProfileController extends Controller
{
    /**
     * Lista todos os perfis de acesso
     */
    public function index()
    {
        $profiles = AccessProfile::withCount('permissions', 'users')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($profile) {
                return [
                    'id' => $profile->id,
                    'uuid' => $profile->uuid,
                    'name' => $profile->name,
                    'slug' => $profile->slug,
                    'description' => $profile->description,
                    'is_system' => $profile->is_system,
                    'is_active' => $profile->is_active,
                    'sort_order' => $profile->sort_order,
                    'permissions_count' => $profile->permissions_count,
                    'users_count' => $profile->users_count,
                    'created_at' => $profile->created_at?->format('Y-m-d H:i:s'),
                ];
            });

        $stats = [
            'total_profiles' => AccessProfile::count(),
            'active_profiles' => AccessProfile::where('is_active', true)->count(),
            'system_profiles' => AccessProfile::where('is_system', true)->count(),
            'custom_profiles' => AccessProfile::where('is_system', false)->count(),
            'total_permissions' => Permission::count(),
        ];

        return Inertia::render('Secretaria/AccessProfiles/Index', [
            'profiles' => $profiles,
            'stats' => $stats,
        ]);
    }

    /**
     * Mostra o formulário para criar um novo perfil
     */
    public function create()
    {
        $permissions = Permission::active()
            ->ordered()
            ->get()
            ->groupBy('group');

        return Inertia::render('Secretaria/AccessProfiles/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Armazena um novo perfil
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:access_profiles,slug',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Normalizar slug para perfis personalizados
        $validated['slug'] = Str::slug($validated['slug']);

        $profile = AccessProfile::create([
            'uuid' => Str::uuid(),
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        if (isset($validated['permissions'])) {
            $profile->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('secretaria.access-profiles.index')
            ->with('success', 'Perfil de acesso criado com sucesso.');
    }

    /**
     * Mostra os detalhes de um perfil
     */
    public function show(AccessProfile $accessProfile)
    {
        $accessProfile->load('permissions', 'users.person');

        $profileData = [
            'id' => $accessProfile->id,
            'uuid' => $accessProfile->uuid,
            'name' => $accessProfile->name,
            'slug' => $accessProfile->slug,
            'description' => $accessProfile->description,
            'is_system' => $accessProfile->is_system,
            'is_active' => $accessProfile->is_active,
            'sort_order' => $accessProfile->sort_order,
            'created_at' => $accessProfile->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $accessProfile->updated_at?->format('Y-m-d H:i:s'),
            'permissions' => $accessProfile->permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'slug' => $permission->slug,
                    'group' => $permission->group,
                    'description' => $permission->description,
                ];
            })->sortBy('group')->values(),
            'users' => $accessProfile->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'person_name' => $user->person?->full_name,
                    'status' => $user->status,
                ];
            }),
        ];

        return Inertia::render('Secretaria/AccessProfiles/Show', [
            'profile' => $profileData,
        ]);
    }

    /**
     * Mostra o formulário para editar um perfil
     */
    public function edit(AccessProfile $accessProfile)
    {
        $accessProfile->load('permissions');

        $allPermissions = Permission::active()
            ->ordered()
            ->get()
            ->groupBy('group');

        $profilePermissions = $accessProfile->permissions->pluck('id')->toArray();

        $profileData = [
            'id' => $accessProfile->id,
            'uuid' => $accessProfile->uuid,
            'name' => $accessProfile->name,
            'slug' => $accessProfile->slug,
            'description' => $accessProfile->description,
            'is_system' => $accessProfile->is_system,
            'is_active' => $accessProfile->is_active,
            'sort_order' => $accessProfile->sort_order,
        ];

        return Inertia::render('Secretaria/AccessProfiles/Edit', [
            'profile' => $profileData,
            'permissions' => $allPermissions,
            'profilePermissions' => $profilePermissions,
        ]);
    }

    /**
     * Atualiza um perfil
     */
    public function update(Request $request, AccessProfile $accessProfile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:access_profiles,slug,' . $accessProfile->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Proteger slug de perfis do sistema
        if ($accessProfile->is_system) {
            $validated['slug'] = $accessProfile->slug;
        } else {
            // Normalizar slug para perfis personalizados
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $accessProfile->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        if (isset($validated['permissions'])) {
            $accessProfile->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('secretaria.access-profiles.index')
            ->with('success', 'Perfil de acesso atualizado com sucesso.');
    }

    /**
     * Desativa um perfil (não deleta perfis do sistema)
     */
    public function destroy(AccessProfile $accessProfile)
    {
        if ($accessProfile->is_system) {
            return back()->with('error', 'Não é possível excluir perfis do sistema. Desative o perfil se necessário.');
        }

        $accessProfile->update(['is_active' => false]);

        return back()->with('success', 'Perfil de acesso desativado com sucesso.');
    }
}
