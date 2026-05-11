<?php

namespace App\Listeners;

use App\Events\DepartmentLeaderChanged;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Listener para registrar log quando o líder de um departamento é alterado
 * 
 * IMPORTANTE:
 * - Liderar departamento não cria permissão automaticamente sem controle
 * - Liderar departamento não cria usuário automaticamente
 * - Liderar departamento não cria membro automaticamente
 */
class LogDepartmentLeaderChanged
{
    /**
     * Handle the event
     */
    public function handle(DepartmentLeaderChanged $event): void
    {
        $oldLeaderName = $event->oldLeader ? $event->oldLeader->full_name : 'Nenhum';
        $newLeaderName = $event->newLeader ? $event->newLeader->full_name : 'Nenhum';

        ActivityLog::create([
            'action' => 'department_leader_changed',
            'description' => "Líder do departamento '{$event->department->name}' foi alterado de '{$oldLeaderName}' para '{$newLeaderName}'",
            'model_type' => Department::class,
            'model_id' => $event->department->id,
            'user_id' => $event->userId ?? (Auth::check() ? Auth::id() : null),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
