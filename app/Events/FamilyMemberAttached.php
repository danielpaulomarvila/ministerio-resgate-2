<?php

namespace App\Events;

use App\Models\Family;
use App\Models\Person;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando uma pessoa é adicionada a uma família
 * 
 * Usos:
 * - Registrar log de atividade
 * - Verificar se precisa atualizar alertas
 */
class FamilyMemberAttached
{
    use Dispatchable, SerializesModels;

    /**
     * A pessoa adicionada
     */
    public Person $person;

    /**
     * A família
     */
    public Family $family;

    /**
     * O papel na família
     */
    public string $role;

    /**
     * O usuário que fez a alteração
     */
    public ?int $userId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Person $person, Family $family, string $role, ?int $userId = null)
    {
        $this->person = $person;
        $this->family = $family;
        $this->role = $role;
        $this->userId = $userId;
    }
}
