<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonWalkingAchievement extends Model
{
    // Conquista ou progresso de conquista vinculado a uma pessoa.
    protected $fillable = [
        'person_id',
        'walking_achievement_id',
        'walking_journey_id',
        'status',
        'progress_current',
        'progress_target',
        'awarded_by',
        'awarded_at',
        'metadata',
    ];

    protected $casts = [
        'progress_current' => 'integer',
        'progress_target' => 'integer',
        'awarded_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relacionamento: o registro pertence a uma pessoa.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relacionamento: conquista do catálogo vinculada a este progresso.
     */
    public function achievement(): BelongsTo
    {
        return $this->belongsTo(WalkingAchievement::class, 'walking_achievement_id');
    }

    /**
     * Relacionamento: jornada associada à conquista, quando existir.
     */
    public function journey(): BelongsTo
    {
        return $this->belongsTo(WalkingJourney::class, 'walking_journey_id');
    }

    /**
     * Relacionamento: usuário que concedeu a conquista, quando aplicável.
     */
    public function awardedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'awarded_by');
    }
}
