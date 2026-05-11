<?php

namespace App\Events;

use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando uma pessoa é adicionada a um departamento
 * 
 * IMPORTANTE:
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 * - Ser líder de departamento não cria permissão automaticamente sem controle
 */
class DepartmentPersonAttached
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * O departamento
     */
    public Department $department;

    /**
     * A pessoa adicionada
     */
    public Person $person;

    /**
     * O vínculo department_people
     */
    public DepartmentPerson $departmentPerson;

    /**
     * O usuário que adicionou a pessoa ao departamento
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
