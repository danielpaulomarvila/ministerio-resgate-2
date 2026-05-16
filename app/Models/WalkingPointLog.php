<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalkingPointLog extends Model
{
    // Registro auditável dos pontos atribuídos a uma pessoa na caminhada.
    protected $fillable = [
        'person_id',
        'user_id',
        'walking_journey_id',
        'walking_point_rule_id',
        'family_id',
        'source_type',
        'source_id',
        'category',
        'points',
        'status',
        'validation_type',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'points' => 'integer',
        'source_id' => 'integer',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relacionamento: o registro de pontos pertence a uma pessoa.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relacionamento: usuário que originou ou registrou o log, quando existir.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: jornada em que os pontos foram registrados.
     */
    public function journey(): BelongsTo
    {
        return $this->belongsTo(WalkingJourney::class, 'walking_journey_id');
    }

    /**
     * Relacionamento: regra de pontuação usada, quando aplicável.
     */
    public function pointRule(): BelongsTo
    {
        return $this->belongsTo(WalkingPointRule::class, 'walking_point_rule_id');
    }

    /**
     * Relacionamento: família vinculada ao registro, quando aplicável.
     */
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Relacionamento: usuário que aprovou o registro de pontos.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relacionamento: usuário que rejeitou o registro de pontos.
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
