<?php

namespace App\Events;

use App\Models\Person;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando uma pessoa é criada no sistema
 * 
 * Usos:
 * - Registrar log de atividade
 * - Criar notificação interna para Secretaria
 * - Verificar se precisa de alertas automáticos
 */
class PersonCreated
{
    use Dispatchable, SerializesModels;

    /**
     * A pessoa que foi criada
     */
    public Person $person;

    /**
     * O usuário que criou a pessoa
     */
    public ?int $createdByUserId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Person $person, ?int $createdByUserId = null)
    {
        $this->person = $person;
        $this->createdByUserId = $createdByUserId;
    }
}
