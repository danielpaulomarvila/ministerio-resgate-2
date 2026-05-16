<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WalkingAchievement extends Model
{
    // Catálogo oficial de conquistas da Minha Caminhada.
    protected $fillable = [
        'key',
        'name',
        'description',
        'type',
        'category',
        'visibility',
        'icon',
        'color',
        'criteria',
        'requires_validation',
        'is_active',
    ];

    protected $casts = [
        'criteria' => 'array',
        'requires_validation' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relacionamento: conquistas concedidas ou em progresso para pessoas.
     */
    public function peopleAchievements(): HasMany
    {
        return $this->hasMany(PersonWalkingAchievement::class);
    }
}
