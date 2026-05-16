<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalkingHighlight extends Model
{
    // Destaque saudável da caminhada, sem ranking espiritual.
    protected $fillable = [
        'person_id',
        'family_id',
        'walking_journey_id',
        'type',
        'category',
        'title',
        'description',
        'period_type',
        'period_start',
        'period_end',
        'visibility',
        'generated_by',
        'approved_by',
        'approved_at',
        'is_visible',
        'metadata',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'approved_at' => 'datetime',
        'is_visible' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Relacionamento: destaque vinculado a uma pessoa, quando aplicável.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relacionamento: destaque vinculado a uma família, quando aplicável.
     */
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Relacionamento: jornada associada ao destaque, quando existir.
     */
    public function journey(): BelongsTo
    {
        return $this->belongsTo(WalkingJourney::class, 'walking_journey_id');
    }

    /**
     * Relacionamento: usuário que gerou o destaque.
     */
    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Relacionamento: usuário que aprovou o destaque.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
