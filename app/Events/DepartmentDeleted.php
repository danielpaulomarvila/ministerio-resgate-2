<?php

namespace App\Events;

use App\Models\Department;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando um departamento é excluído (soft delete)
 * 
 * IMPORTANTE:
 * - Departamento excluído não apaga pessoas, usuários, membros ou member_profile
 * - Usa soft delete (deleted_at), não exclusão física
 * - Gera ActivityLog através de listener
 */
class DepartmentDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * O departamento que foi excluído
     */
    public Department $department;

    /**
     * ID do usuário que excluiu o departamento
     */
    public ?int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(Department $department, ?int $userId = null)
    {
        $this->department = $department;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
