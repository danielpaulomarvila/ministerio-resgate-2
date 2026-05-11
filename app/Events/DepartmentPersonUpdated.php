<?php

namespace App\Events;

use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando o vínculo de uma pessoa com departamento é atualizado
 * 
 * IMPORTANTE:
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 * - Ser líder de departamento não cria permissão automaticamente sem controle
 */
class DepartmentPersonUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * O departamento
     */
    public Department $department;

    /**
     * A pessoa
     */
    public Person $person;

    /**
     * O vínculo department_people atualizado
     */
    public DepartmentPerson $departmentPerson;

    /**
     * O usuário que atualizou o vínculo
     */
    public ?int $userId;

    /**
     * Dados anteriores (antes da atualização)
     */
    public ?array $oldData;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Department $department, Person $person, DepartmentPerson $departmentPerson, ?int $userId = null, ?array $oldData = null)
    {
        $this->department = $department;
        $this->person = $person;
        $this->departmentPerson = $departmentPerson;
        $this->userId = $userId;
        $this->oldData = $oldData;
    }
}
