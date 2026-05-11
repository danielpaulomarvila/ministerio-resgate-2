<?php

namespace App\Events;

use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando uma pessoa é removida/inativada de um departamento
 * 
 * IMPORTANTE:
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 * - Ser líder de departamento não cria permissão automaticamente sem controle
 * - Remover vínculo não apaga pessoa, usuário ou membro
 */
class DepartmentPersonRemoved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * O departamento
     */
    public Department $department;

    /**
     * A pessoa removida
     */
    public Person $person;

    /**
     * O vínculo department_people removido/inativado
     */
    public DepartmentPerson $departmentPerson;

    /**
     * O usuário que removeu a pessoa do departamento
     */
    public ?int $userId;

    /**
     * Criar uma nova instância do evento
     */
    public function __construct(Department $department, Person $person, DepartmentPerson $departmentPerson, ?int $userId = null)
    {
        $this->department = $department;
        $this->person = $person;
        $this->departmentPerson = $departmentPerson;
        $this->userId = $userId;
    }
}
