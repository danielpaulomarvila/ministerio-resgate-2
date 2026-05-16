<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WalkingJourney extends Model
{
    // Trilho principal da Minha Caminhada, como caminhada geral ou jovem.
    protected $fillable = [
        'key',
        'name',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relacionamento: uma jornada possui seus níveis oficiais.
     */
    public function levels(): HasMany
    {
        return $this->hasMany(WalkingLevel::class);
    }

    /**
     * Relacionamento: uma jornada pode ter regras de pontuação próprias.
     */
    public function pointRules(): HasMany
    {
        return $this->hasMany(WalkingPointRule::class);
    }

    /**
     * Relacionamento: uma jornada acumula registros de pontos das pessoas.
     */
    public function pointLogs(): HasMany
    {
        return $this->hasMany(WalkingPointLog::class);
    }

    /**
     * Relacionamento: uma jornada pode estar vinculada a conquistas concedidas às pessoas.
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(PersonWalkingAchievement::class);
    }

    /**
     * Relacionamento: uma jornada pode gerar destaques saudáveis.
     */
    public function highlights(): HasMany
    {
        return $this->hasMany(WalkingHighlight::class);
    }

    /**
     * Relacionamento: uma jornada pode ter histórico de respostas do mentor.
     */
    public function mentorResponseLogs(): HasMany
    {
        return $this->hasMany(WalkingMentorResponseLog::class);
    }

    /**
     * Relacionamento: uma jornada possui eventos visíveis no histórico da caminhada.
     */
    public function historyEvents(): HasMany
    {
        return $this->hasMany(WalkingHistoryEvent::class);
    }
}
