<?php

namespace App\Jobs;

use App\Models\Department;
use App\Models\SystemAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job para verificar departamentos ativos sem líder e gerar alertas
 * 
 * Este job verifica todos os departamentos ativos que não têm líder definido
 * e gera um SystemAlert para a Secretaria se não houver alerta pendente equivalente.
 * 
 * Deve ser executado diariamente via scheduler (ex: 04:00)
 */
class CheckDepartmentsWithoutLeader implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job
     */
    public function handle(): void
    {
        // Buscar departamentos ativos sem líder
        $departmentsWithoutLeader = Department::where('status', 'active')
            ->whereNull('leader_person_id')
            ->get();

        foreach ($departmentsWithoutLeader as $department) {
            // Verificar se já existe alerta pendente para este departamento
            $existingAlert = SystemAlert::where('type', 'department_without_leader')
                ->where('related_department_id', $department->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->first();

            // Se não houver alerta pendente, criar um novo
            if (!$existingAlert) {
                SystemAlert::create([
                    'type' => 'department_without_leader',
                    'title' => 'Departamento sem líder',
                    'message' => "O departamento '{$department->name}' está ativo, mas ainda não possui líder definido.",
                    'related_department_id' => $department->id,
                    'severity' => 'medium',
                    'status' => 'pending',
                ]);
            }
        }
    }
}
