<?php

namespace Database\Seeders;

use App\Models\WalkingAchievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalkingAchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            'description' => 'Critério preparado para validação futura por regras do sistema.',
        ];

        $achievements = [
            [
                'key' => 'general_first_attendance',
                'name' => 'Primeira Presença Registrada',
                'description' => 'Conquista de início da caminhada com a primeira presença registrada.',
                'type' => 'general',
                'category' => 'presence',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => false,
                'is_active' => true,
            ],
            [
                'key' => 'general_monthly_constancy',
                'name' => 'Constância na Casa',
                'description' => 'Conquista de constância saudável na participação da igreja.',
                'type' => 'general',
                'category' => 'presence',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => false,
                'is_active' => true,
            ],
            [
                'key' => 'general_servant_heart',
                'name' => 'Servo Disponível',
                'description' => 'Conquista para serviço validado com responsabilidade e disponibilidade.',
                'type' => 'general',
                'category' => 'service',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => true,
                'is_active' => true,
            ],
            [
                'key' => 'general_inviter',
                'name' => 'Semeador de Convites',
                'description' => 'Conquista para incentivo saudável a convites e acompanhamento.',
                'type' => 'general',
                'category' => 'evangelism',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => true,
                'is_active' => true,
            ],
            [
                'key' => 'general_devotional_heart',
                'name' => 'Coração Devocional',
                'description' => 'Conquista privada para incentivo à vida devocional.',
                'type' => 'general',
                'category' => 'devotional',
                'visibility' => 'private_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => false,
                'is_active' => true,
            ],
            [
                'key' => 'general_communion_guardian',
                'name' => 'Guardião da Comunhão',
                'description' => 'Conquista para participação validada em comunhão e serviço.',
                'type' => 'general',
                'category' => 'communion',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => true,
                'is_active' => true,
            ],
            [
                'key' => 'youth_present_resgatado',
                'name' => 'Resgatado Presente',
                'description' => 'Conquista jovem para constância nos encontros dos Resgatados.',
                'type' => 'youth',
                'category' => 'presence',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => false,
                'is_active' => true,
            ],
            [
                'key' => 'youth_bible_ready',
                'name' => 'Bíblia na Mão',
                'description' => 'Conquista jovem para preparo bíblico nos encontros.',
                'type' => 'youth',
                'category' => 'bible',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => true,
                'is_active' => true,
            ],
            [
                'key' => 'youth_challenge_accepted',
                'name' => 'Desafio Aceito',
                'description' => 'Conquista jovem para participação em desafios bíblicos.',
                'type' => 'youth',
                'category' => 'bible_challenge',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => true,
                'is_active' => true,
            ],
            [
                'key' => 'youth_servant',
                'name' => 'Jovem Servo',
                'description' => 'Conquista jovem para serviço validado com responsabilidade.',
                'type' => 'youth',
                'category' => 'service',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => true,
                'is_active' => true,
            ],
            [
                'key' => 'youth_inviter',
                'name' => 'Amigo Convidado',
                'description' => 'Conquista jovem para convite e acolhimento de amigos.',
                'type' => 'youth',
                'category' => 'evangelism',
                'visibility' => 'public_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => true,
                'is_active' => true,
            ],
            [
                'key' => 'sensitive_financial_organization',
                'name' => 'Organização Financeira',
                'description' => 'Conquista sensível e inativa para eventual fluxo privado futuro.',
                'type' => 'financial',
                'category' => 'financial',
                'visibility' => 'private_to_user',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => true,
                'is_active' => false,
            ],
            [
                'key' => 'sensitive_pastoral_followup',
                'name' => 'Apoio Pastoral Acompanhado',
                'description' => 'Conquista sensível e inativa para acompanhamento pastoral protegido.',
                'type' => 'pastoral_sensitive',
                'category' => 'pastoral_care',
                'visibility' => 'leadership_only',
                'icon' => null,
                'color' => null,
                'criteria' => $criteria,
                'requires_validation' => true,
                'is_active' => false,
            ],
        ];

        foreach ($achievements as $achievement) {
            WalkingAchievement::updateOrCreate(
                ['key' => $achievement['key']],
                $achievement
            );
        }
    }
}
