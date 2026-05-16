<?php

namespace Database\Seeders;

use App\Models\WalkingJourney;
use App\Models\WalkingLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RuntimeException;

class WalkingLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generalJourney = WalkingJourney::where('key', 'general')->first();
        $youthJourney = WalkingJourney::where('key', 'youth')->first();

        if (!$generalJourney || !$youthJourney) {
            throw new RuntimeException('As jornadas general e youth precisam existir antes de executar WalkingLevelSeeder.');
        }

        $generalLevels = [
            [1, 'Primeiros Passos', 0],
            [2, 'Coração Desperto', 100],
            [3, 'Semente da Palavra', 250],
            [4, 'Raízes da Fé', 450],
            [5, 'Chama Interior', 700],
            [6, 'Caminho do Discípulo', 1000],
            [7, 'Servo da Casa', 1350],
            [8, 'Voz de Esperança', 1750],
            [9, 'Guardião da Comunhão', 2200],
            [10, 'Mãos Disponíveis', 2700],
            [11, 'Caminho Firme', 3250],
            [12, 'Farol da Família', 3850],
            [13, 'Coração Missionário', 4500],
            [14, 'Obreiro Aprovado', 5200],
            [15, 'Coluna da Casa', 5950],
            [16, 'Semeador do Reino', 6750],
            [17, 'Constância Fiel', 7600],
            [18, 'Multiplicador de Fé', 8500],
            [19, 'Resgatador de Vidas', 9450],
            [20, 'Legado Vivo', 10450],
        ];

        $youthLevels = [
            [1, 'Primeiros Passos', 0],
            [2, 'Coração Desperto', 80],
            [3, 'Semente da Palavra', 200],
            [4, 'Raízes da Fé', 360],
            [5, 'Chama Interior', 560],
            [6, 'Caminho do Discípulo', 800],
            [7, 'Servo da Casa', 1080],
            [8, 'Voz de Esperança', 1400],
            [9, 'Guardião da Comunhão', 1760],
            [10, 'Mãos Disponíveis', 2160],
            [11, 'Caminho Firme', 2600],
            [12, 'Farol da Família', 3080],
            [13, 'Coração Missionário', 3600],
            [14, 'Obreiro Aprovado', 4160],
            [15, 'Coluna da Casa', 4760],
            [16, 'Semeador do Reino', 5400],
            [17, 'Constância Fiel', 6080],
            [18, 'Multiplicador de Fé', 6800],
            [19, 'Resgatador de Vidas', 7560],
            [20, 'Legado Vivo', 8360],
        ];

        $this->seedLevels($generalJourney, $generalLevels, 'Etapa da caminhada que representa crescimento, constância e participação saudável.');
        $this->seedLevels($youthJourney, $youthLevels, 'Etapa da caminhada jovem dos Resgatados, incentivando crescimento, comunhão e responsabilidade.');
    }

    private function seedLevels(WalkingJourney $journey, array $levels, string $description): void
    {
        foreach ($levels as [$levelNumber, $name, $requiredPoints]) {
            WalkingLevel::updateOrCreate(
                [
                    'walking_journey_id' => $journey->id,
                    'level_number' => $levelNumber,
                ],
                [
                    'name' => $name,
                    'description' => $description,
                    'required_points' => $requiredPoints,
                    'icon' => null,
                    'color' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
