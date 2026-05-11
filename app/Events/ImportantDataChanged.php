<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento genérico para dados sensíveis alterados
 * 
 * Usos:
 * - Registrar alterações sensíveis no log
 * - Criar alerta quando necessário
 * - Auditoria de segurança
 */
class ImportantDataChanged
{
    use Dispatchable, SerializesModels;

    /**
     * Tipo de dado alterado (ex: 'person', 'family', 'user')
     */
    public string $dataType;

    /**
     * ID do registro alterado
     */
    public int $recordId;

    /**
     * Campo alterado
     */
    public string $field;

    /**
     * Valor antigo
     */
    public mixed $oldValue;

    /**
     * Valor novo
     */
    public mixed $newValue;

    /**
     * Usuário que fez a alteração
     */
    public ?int $userId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(string $dataType, int $recordId, string $field, mixed $oldValue, mixed $newValue, ?int $userId = null)
    {
        $this->dataType = $dataType;
        $this->recordId = $recordId;
        $this->field = $field;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
        $this->userId = $userId;
    }
}
