<?php

namespace App\Events;

use App\Models\Family;
use App\Models\Person;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando uma pessoa é removida de uma família
 * 
 * Usos:
 * - Registrar log de atividade
 * - Verificar impactos em alertas
 */
class FamilyMemberDetached
{
    use Dispatchable, SerializesModels;

    /**
     * A pessoa removida
     */
    public Person $person;

    /**
     * A família
     */
    public Family $family;

    /**
     * O usuário que fez a alteração
     */
    public ?int $userId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Person $person, Family $family, ?int $userId = null)
    {
        $this->person = $person;
        $this->family = $family;
        $this->userId = $userId;
    }
}
