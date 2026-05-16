<?php

namespace Database\Seeders;

use App\Models\WalkingJourney;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalkingJourneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $journeys = [
            [
                'key' => 'general',
                'name' => 'Caminhada Geral',
                'type' => 'general',
                'description' => 'Trilho principal da caminhada cristã, participação, constância, serviço, comunhão e crescimento espiritual de toda a igreja.',
                'is_active' => true,
            ],
            [
                'key' => 'youth',
                'name' => 'Caminhada Jovem',
                'type' => 'youth',
                'description' => 'Trilho específico dos Resgatados, preparado para jovens e juniores, com desafios, crescimento, serviço, comunhão e acompanhamento saudável.',
                'is_active' => true,
            ],
        ];

        foreach ($journeys as $journey) {
            WalkingJourney::updateOrCreate(
                ['key' => $journey['key']],
                $journey
            );
        }
    }
}
