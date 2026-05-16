<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalkingHistoryEvent extends Model
{
    // Evento da timeline visível da caminhada, separado dos logs administrativos.
    protected $fillable = [
        'person_id',
        'walking_journey_id',
        'event_type',
        'title',
        'description',
        'points',
        'source_type',
        'source_id',
        'visibility',
        'metadata',
        'occurred_at',
    ];

    protected $casts = [
        'points' => 'integer',
        'source_id' => 'integer',
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    /**
     * Relacionamento: evento visível pertence a uma pessoa.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relacionamento: evento pertence a uma jornada da caminhada.
     */
    public function journey(): BelongsTo
    {
        return $this->belongsTo(WalkingJourney::class, 'walking_journey_id');
    }
}
