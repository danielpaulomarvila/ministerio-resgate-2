<?php

namespace App\Jobs;

use App\Events\ChildApproachingJuniorAgeDetected;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job para verificar crianças próximas de completar 11 anos
 * 
 * Este job deve ser executado diariamente via scheduler
 * Verifica crianças com idade entre 10 anos e 11 anos
 * Dispara evento para criar alerta para Secretaria
 * Não duplica alertas se já existir alerta pendente equivalente
 */
class CheckChildrenApproachingJuniorAge implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Dias antes de completar 11 anos para gerar alerta
     */
    private const ALERT_DAYS_BEFORE = 60;

    /**
     * Execute the job
     */
    public function handle(): void
    {
        Log::info('Iniciando verificação de crianças próximas de completar 11 anos');

        // Buscar crianças entre 10 anos e 11 anos
        // Pessoas com birth_date entre 10 anos e 11 anos atrás
        $startDate = Carbon::now()->subYears(11)->addDays(self::ALERT_DAYS_BEFORE);
        $endDate = Carbon::now()->subYears(11);

        $children = Person::whereBetween('birth_date', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->get();

        $alertCount = 0;

        foreach ($children as $child) {
            $age = $child->age;
            
            if ($age === null) {
                continue;
            }

            $turning11Date = $child->birth_date->addYears(11);
            $daysUntil11 = Carbon::now()->diffInDays($turning11Date, false);

            // Se já passou dos 11 anos, ignorar
            if ($daysUntil11 < 0) {
                continue;
            }

            // Se ainda está muito longe, ignorar
            if ($daysUntil11 > self::ALERT_DAYS_BEFORE) {
                continue;
            }

            // Disparar evento para criar alerta
            event(new ChildApproachingJuniorAgeDetected(
                $child,
                $age,
                $turning11Date,
                $daysUntil11
            ));

            $alertCount++;
        }

        Log::info("Verificação concluída. {$alertCount} crianças identificadas próximas de completar 11 anos");
    }
}
