<?php

namespace App\Events;

use App\Models\Department;
use App\Models\Person;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando o líder de um departamento é alterado
 * 
 * IMPORTANTE:
 * - Liderar departamento não cria permissão automaticamente sem controle
 * - Liderar departamento não cria usuário automaticamente
 * - Liderar departamento não cria membro automaticamente
 */
class DepartmentLeaderChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * O departamento
     */
    public Department $department;

    /**
     * Líder anterior (se houver)
     */
    public ?Person $oldLeader;

    /**
     * Novo líder (se houver)
     */
    public ?Person $newLeader;

    /**
     * O usuário que alterou o líder
     */
    public ?int $userId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Department $department, ?Person $oldLeader, ?Person $newLeader, ?int $userId = null)
    {
        $this->department = $department;
        $this->oldLeader = $oldLeader;
        $this->newLeader = $newLeader;
        $this->userId = $userId;
    }
}
