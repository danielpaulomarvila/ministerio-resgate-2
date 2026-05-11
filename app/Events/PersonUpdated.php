<?php

namespace App\Events;

use App\Models\Person;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando uma pessoa é atualizada
 * 
 * Usos:
 * - Registrar log de atividade
 * - Verificar se dados sensíveis foram alterados
 * - Criar alerta se necessário
 */
class PersonUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * A pessoa que foi atualizada
     */
    public Person $person;

    /**
     * O usuário que atualizou a pessoa
     */
    public ?int $updatedByUserId;

    /**
     * Dados originais antes da atualização
     */
    public ?array $originalData;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Person $person, ?int $updatedByUserId = null, ?array $originalData = null)
    {
        $this->person = $person;
        $this->updatedByUserId = $updatedByUserId;
        $this->originalData = $originalData;
    }
}
