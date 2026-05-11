<?php

namespace App\Listeners;

use App\Events\DepartmentDeleted;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando um departamento é excluído
 * 
 * IMPORTANTE:
 * - Departamento excluído não apaga pessoas, usuários, membros ou member_profile
 * - Soft delete (deleted_at) é usado, não exclusão física
 * - Gera ActivityLog para auditoria
 */
class LogDepartmentDeleted
{
    /**
     * Handle the event.
     */
    public function handle(DepartmentDeleted $event): void
    {
        // Se não tiver usuário autenticado e não foi passado userId, usar o do evento
        $userId = $event->userId ?? Auth::id();

        // Registrar no ActivityLog
        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'department.deleted',
            'description' => "Departamento '{$event->department->name}' foi excluído (soft delete)",
            'model_type' => Department::class,
            'model_id' => $event->department->id,
            'old_values' => [
                'name' => $event->department->name,
                'slug' => $event->department->slug,
                'department_type' => $event->department->department_type,
                'status' => $event->department->status,
            ],
        ]);
    }
}
