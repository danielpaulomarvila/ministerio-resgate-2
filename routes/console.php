<?php

use App\Jobs\CheckChildrenApproachingJuniorAge;
use App\Jobs\CheckDepartmentsWithoutLeader;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Comando existente
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler para Job de verificação de crianças próximas de 11 anos (Etapa 9)
// Executa diariamente às 03:00 para verificar crianças que completarão 11 anos nos próximos 60 dias
Schedule::job(new CheckChildrenApproachingJuniorAge())
    ->dailyAt('03:00')
    ->description('Verifica crianças próximas de completar 11 anos e gera alertas para Secretaria');

// Scheduler para Job de verificação de departamentos sem líder (Etapa 10)
// Executa diariamente às 04:00 para verificar departamentos ativos sem líder
Schedule::job(new CheckDepartmentsWithoutLeader())
    ->dailyAt('04:00')
    ->description('Verifica departamentos ativos sem líder e gera alertas para Secretaria');
