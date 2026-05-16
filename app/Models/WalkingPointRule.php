<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WalkingPointRule extends Model
{
    // Regra de pontuação da Minha Caminhada, com categoria, limites e validação.
    protected $fillable = [
        'walking_journey_id',
        'key',
        'name',
        'description',
        'category',
        'points',
        'validation_type',
        'source_origin',
        'daily_limit',
        'weekly_limit',
        'monthly_limit',
        'counts_for_level',
        'counts_for_highlight',
        'is_fixed_system_rule',
        'can_edit_points',
        'is_active',
    ];

    protected $casts = [
        'points' => 'integer',
        'daily_limit' => 'integer',
        'weekly_limit' => 'integer',
        'monthly_limit' => 'integer',
        'counts_for_level' => 'boolean',
        'counts_for_highlight' => 'boolean',
        'is_fixed_system_rule' => 'boolean',
        'can_edit_points' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relacionamento: uma regra pode pertencer a uma jornada ou ser geral.
     */
    public function journey(): BelongsTo
    {
        return $this->belongsTo(WalkingJourney::class, 'walking_journey_id');
    }

    /**
     * Relacionamento: uma regra pode originar vários registros de pontos.
     */
    public function pointLogs(): HasMany
    {
        return $this->hasMany(WalkingPointLog::class);
    }
}
