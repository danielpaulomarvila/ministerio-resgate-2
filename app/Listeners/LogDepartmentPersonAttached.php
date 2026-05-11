<?php

namespace App\Listeners;

use App\Events\DepartmentPersonAttached;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando uma pessoa é adicionada a um departamento
 * 
 * IMPORTANTE:
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 */
class LogDepartmentPersonAttached
{
    /**
     * Handle the event
     */
    public function handle(DepartmentPersonAttached $event): void
    {
        ActivityLog::create([
            'action' => 'department_person_attached',
            'description' => "Pessoa '{$event->person->full_name}' foi adicionada ao departamento '{$event->department->name}' com função '{$event->departmentPerson->role}'",
            'model_type' => DepartmentPerson::class,
            'model_id' => $event->departmentPerson->id,
            'user_id' => $event->userId ?? (Auth::check() ? Auth::id() : null),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
