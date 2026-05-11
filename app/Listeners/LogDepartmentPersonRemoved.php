<?php

namespace App\Listeners;

use App\Events\DepartmentPersonRemoved;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando uma pessoa é removida/inativada de um departamento
 * 
 * IMPORTANTE:
 * - Remover vínculo não apaga pessoa, usuário ou membro
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 */
class LogDepartmentPersonRemoved
{
    /**
     * Handle the event
     */
    public function handle(DepartmentPersonRemoved $event): void
    {
        ActivityLog::create([
            'action' => 'department_person_removed',
            'description' => "Pessoa '{$event->person->full_name}' foi removida/inativada do departamento '{$event->department->name}'",
            'model_type' => DepartmentPerson::class,
            'model_id' => $event->departmentPerson->id,
            'user_id' => $event->userId ?? (Auth::check() ? Auth::id() : null),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
