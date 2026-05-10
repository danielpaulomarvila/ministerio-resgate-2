<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Model de Perfil de Acesso
 * 
 * Define os perfis de acesso do sistema (ex: Secretaria, Tesouraria, Membro)
 * Cada perfil pode ter múltiplas permissões
 * Cada usuário pode ter múltiplos perfis
 */
class AccessProfile extends Model
{
    /**
     * Os atributos que são atribuíveis em massa
     */
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
        'is_system',
        'is_active',
        'sort_order',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos
     */
    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Permissões associadas a este perfil
     * Relação many-to-many com tabela pivot access_profile_permission
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'access_profile_permission')
            ->withTimestamps()
            ->orderBy('group')
            ->orderBy('sort_order');
    }

    /**
     * Usuários que têm este perfil
     * Relação many-to-many com tabela pivot access_profile_user
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'access_profile_user')
            ->withPivot(['assigned_by_user_id', 'assigned_at', 'notes'])
            ->withTimestamps();
    }

    /**
     * Verifica se este perfil tem uma permissão específica
     */
    public function hasPermission(string $slug): bool
    {
        return $this->permissions()->where('slug', $slug)->exists();
    }

    /**
     * Verifica se este perfil é um perfil do sistema (não pode ser deletado)
     */
    public function isSystemProfile(): bool
    {
        return $this->is_system;
    }

    /**
     * Escopo para filtrar apenas perfis ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Escopo para filtrar apenas perfis do sistema
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Escopo para filtrar apenas perfis personalizados (não do sistema)
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }
}
