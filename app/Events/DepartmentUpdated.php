<?php

namespace App\Events;

use App\Models\Department;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando um departamento é atualizado
 * 
 * IMPORTANTE:
 * - Departamento não cria membro automaticamente
 * - Participar de departamento não cria usuário automaticamente
 */
class DepartmentUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * O departamento atualizado
     */
    public Department $department;

    /**
     * O usuário que atualizou o departamento
     */
    public ?int $userId;

    /**
     * Dados anteriores (antes da atualização)
     */
    public ?array $oldData;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Department $department, ?int $userId = null, ?array $oldData = null)
    {
        $this->department = $department;
        $this->userId = $userId;
        $this->oldData = $oldData;
    }
}
