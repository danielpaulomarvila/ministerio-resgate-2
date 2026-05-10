<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use SoftDeletes;

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'description',
        'responsible_person_id',
        'status',
        'notes',
    ];

    /**
     * Relacionamento: Uma família tem um responsável principal
     * Responsável principal é uma pessoa
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'responsible_person_id');
    }

    /**
     * Relacionamento: Uma família pode ter várias pessoas vinculadas
     * Usa a tabela pivot family_members
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'family_members')
            ->withPivot('role', 'is_responsible', 'joined_at', 'left_at', 'notes')
            ->withTimestamps();
    }

    /**
     * Relacionamento: Uma família pode ter alertas do sistema
     * Ex: alertas sobre membros da família
     */
    public function systemAlerts(): HasMany
    {
        return $this->hasMany(SystemAlert::class, 'related_family_id');
    }

    /**
     * Verifica se a família está ativa
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
