<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WalkingMentorResponseTemplate extends Model
{
    // Resposta pré-aprovada do Mentor da Minha Caminhada.
    protected $fillable = [
        'analysis_type',
        'key',
        'title',
        'body',
        'tone',
        'journey_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relacionamento: registros de exibição que usaram este template.
     */
    public function responseLogs(): HasMany
    {
        return $this->hasMany(WalkingMentorResponseLog::class);
    }
}
