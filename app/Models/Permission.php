<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Model de Permissão
 * 
 * Define as permissões granulares do sistema (ex: people.view, accesses.suspend)
 * Cada permissão pode estar em múltiplos perfis
 * Permissões são agrupadas por categoria (group) para melhor organização
 */
class Permission extends Model
{
    /**
     * Os atributos que são atribuíveis em massa
     */
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'group',
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
     * Perfis que têm esta permissão
     * Relação many-to-many com tabela pivot access_profile_permission
     */
    public function accessProfiles(): BelongsToMany
    {
        return $this->belongsToMany(AccessProfile::class, 'access_profile_permission')
            ->withTimestamps();
    }

    /**
     * Verifica se esta permissão é do sistema (não pode ser deletada)
     */
    public function isSystemPermission(): bool
    {
        return $this->is_system;
    }

    /**
     * Escopo para filtrar apenas permissões ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Escopo para filtrar por grupo
     */
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Escopo para ordenar por grupo e sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('group')->orderBy('sort_order');
    }

    /**
     * Escopo para filtrar apenas permissões do sistema
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Escopo para filtrar apenas permissões personalizadas (não do sistema)
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }
}
