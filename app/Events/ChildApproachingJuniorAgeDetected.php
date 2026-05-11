<?php

namespace App\Events;

use App\Models\Person;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando é detectado que uma criança está próxima de completar 11 anos
 * 
 * Usos:
 * - Criar alerta para Secretaria
 * - Preparar transição para Júnior/Resgatados
 * - Notificar responsáveis (futuro)
 */
class ChildApproachingJuniorAgeDetected
{
    use Dispatchable, SerializesModels;

    /**
     * A pessoa (criança) detectada
     */
    public Person $person;

    /**
     * Idade atual da criança
     */
    public int $currentAge;

    /**
     * Data em que completará 11 anos
     */
    public \Carbon\Carbon $turning11Date;

    /**
     * Dias restantes até completar 11 anos
     */
    public int $daysUntil11;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Person $person, int $currentAge, \Carbon\Carbon $turning11Date, int $daysUntil11)
    {
        $this->person = $person;
        $this->currentAge = $currentAge;
        $this->turning11Date = $turning11Date;
        $this->daysUntil11 = $daysUntil11;
    }
}
