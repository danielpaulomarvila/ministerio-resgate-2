<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalkingMentorResponseLog extends Model
{
    // Histórico de respostas exibidas pelo Mentor para evitar repetição.
    protected $fillable = [
        'person_id',
        'family_id',
        'walking_journey_id',
        'analysis_type',
        'walking_mentor_response_template_id',
        'response_variant',
        'context_summary',
        'status',
        'displayed_at',
    ];

    protected $casts = [
        'context_summary' => 'array',
        'displayed_at' => 'datetime',
    ];

    /**
     * Relacionamento: resposta exibida para uma pessoa.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relacionamento: família associada ao contexto da resposta, quando existir.
     */
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Relacionamento: jornada analisada pelo mentor, quando existir.
     */
    public function journey(): BelongsTo
    {
        return $this->belongsTo(WalkingJourney::class, 'walking_journey_id');
    }

    /**
     * Relacionamento: template pré-aprovado usado na resposta.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(WalkingMentorResponseTemplate::class, 'walking_mentor_response_template_id');
    }
}
