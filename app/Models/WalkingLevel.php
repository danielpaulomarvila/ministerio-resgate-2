<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalkingLevel extends Model
{
    // Nível oficial de uma jornada da Minha Caminhada.
    protected $fillable = [
        'walking_journey_id',
        'level_number',
        'name',
        'description',
        'required_points',
        'icon',
        'color',
        'is_active',
    ];

    protected $casts = [
        'level_number' => 'integer',
        'required_points' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relacionamento: cada nível pertence a uma jornada específica.
     */
    public function journey(): BelongsTo
    {
        return $this->belongsTo(WalkingJourney::class, 'walking_journey_id');
    }
}
