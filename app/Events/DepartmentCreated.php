<?php

namespace App\Events;

use App\Models\Department;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando um departamento é criado
 * 
 * IMPORTANTE:
 * - Departamento não cria membro automaticamente
 * - Participar de departamento não cria usuário automaticamente
 */
class DepartmentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * O departamento criado
     */
    public Department $department;

    /**
     * O usuário que criou o departamento
     */
    public ?int $userId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Department $department, ?int $userId = null)
    {
        $this->department = $department;
        $this->userId = $userId;
    }
}
