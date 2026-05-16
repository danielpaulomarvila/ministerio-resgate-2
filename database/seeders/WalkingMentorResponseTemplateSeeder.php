<?php

namespace Database\Seeders;

use App\Models\WalkingMentorResponseTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalkingMentorResponseTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'analysis_type' => 'presence_strong',
                'key' => 'general_presence_strong_constancy',
                'title' => 'Constância que edifica',
                'body' => 'Você tem demonstrado constância na caminhada. Continue valorizando os pequenos passos, porque crescimento saudável também nasce da fidelidade no simples.',
                'tone' => 'pastoral',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'presence_strong',
                'key' => 'general_presence_strong_communion',
                'title' => 'Presença com propósito',
                'body' => 'Sua presença tem valor na comunhão da igreja. Siga caminhando com humildade, mantendo o coração disponível para aprender e servir.',
                'tone' => 'acolhedor',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'devotional_low',
                'key' => 'general_devotional_low_small_steps',
                'title' => 'Recomece pelo simples',
                'body' => 'Se a rotina devocional ficou mais difícil, recomece com passos pequenos e possíveis. Um tempo breve com a Palavra já pode ajudar a reorganizar o coração.',
                'tone' => 'prático',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'devotional_low',
                'key' => 'general_devotional_low_without_weight',
                'title' => 'Sem peso, com direção',
                'body' => 'A caminhada devocional pode ser retomada sem culpa pesada. Escolha um trecho bíblico, ore com simplicidade e avance com constância.',
                'tone' => 'bíblico',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'service_strong',
                'key' => 'general_service_strong_available',
                'title' => 'Serviço com alegria',
                'body' => 'Seu envolvimento no serviço demonstra disponibilidade. Continue servindo com equilíbrio, lembrando que o cuidado com o coração também faz parte da caminhada.',
                'tone' => 'pastoral',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'service_strong',
                'key' => 'general_service_strong_balance',
                'title' => 'Disponibilidade equilibrada',
                'body' => 'Servir é uma bênção quando caminha junto com descanso, comunhão e escuta. Mantenha a disposição, sem transformar serviço em peso.',
                'tone' => 'prático',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'balanced_growth',
                'key' => 'general_balanced_growth_gratitude',
                'title' => 'Crescimento em várias áreas',
                'body' => 'Sua caminhada mostra sinais de equilíbrio entre presença, serviço e crescimento pessoal. Continue avançando com gratidão e simplicidade.',
                'tone' => 'acolhedor',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'balanced_growth',
                'key' => 'general_balanced_growth_continue',
                'title' => 'Continue cultivando',
                'body' => 'O crescimento saudável costuma aparecer no conjunto da caminhada. Continue cultivando presença, Palavra, comunhão e serviço no seu ritmo.',
                'tone' => 'bíblico',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'rhythm_drop',
                'key' => 'general_rhythm_drop_restart',
                'title' => 'Um novo passo é possível',
                'body' => 'Se o ritmo diminuiu, isso não precisa definir sua caminhada. Retome com um passo simples nesta semana e, se precisar, converse com uma liderança de confiança.',
                'tone' => 'acolhedor',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'rhythm_drop',
                'key' => 'general_rhythm_drop_gentle',
                'title' => 'Retomada com cuidado',
                'body' => 'Toda caminhada tem fases. Olhe para o próximo passo com cuidado, sem cobrança pesada, e busque apoio pastoral quando sentir necessidade.',
                'tone' => 'pastoral',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'new_level_reached',
                'key' => 'general_new_level_reached_grace',
                'title' => 'Mais uma etapa vencida',
                'body' => 'Você alcançou uma nova etapa da caminhada. Receba esse marco com gratidão e continue crescendo com humildade, sem comparação com outras pessoas.',
                'tone' => 'bíblico',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'new_level_reached',
                'key' => 'general_new_level_reached_next_step',
                'title' => 'Novo nível, novo cuidado',
                'body' => 'Este novo nível marca progresso, mas também convida a seguir com constância. Continue cuidando do coração e dos próximos passos.',
                'tone' => 'pastoral',
                'journey_type' => 'general',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'devotional_low',
                'key' => 'youth_devotional_low_simple',
                'title' => 'Comece pequeno',
                'body' => 'Se ficou difícil manter o devocional, comece pequeno: um texto bíblico, uma oração simples e um passo real já ajudam na caminhada.',
                'tone' => 'prático',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'devotional_low',
                'key' => 'youth_devotional_low_routine',
                'title' => 'Um tempo com Deus na rotina',
                'body' => 'Procure um horário possível na sua rotina para ler a Bíblia sem pressa e sem peso. Constância cresce melhor quando começa de forma simples.',
                'tone' => 'acolhedor',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'balanced_growth',
                'key' => 'youth_balanced_growth_steps',
                'title' => 'Crescendo com equilíbrio',
                'body' => 'Sua caminhada jovem mostra bons passos em comunhão, aprendizado e responsabilidade. Continue avançando sem comparação e com o coração ensinável.',
                'tone' => 'pastoral',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'balanced_growth',
                'key' => 'youth_balanced_growth_resgatados',
                'title' => 'Caminhada saudável nos Resgatados',
                'body' => 'Participar, aprender e servir são partes importantes da caminhada. Continue crescendo com alegria e respeito ao seu tempo.',
                'tone' => 'bíblico',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'rhythm_drop',
                'key' => 'youth_rhythm_drop_return',
                'title' => 'Você pode retomar',
                'body' => 'Se sua participação diminuiu, retome com calma. Um encontro, uma conversa com a liderança e um passo de cada vez já podem ajudar.',
                'tone' => 'acolhedor',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'rhythm_drop',
                'key' => 'youth_rhythm_drop_without_pressure',
                'title' => 'Sem pressão pesada',
                'body' => 'A caminhada jovem não precisa ser vivida com culpa. Recomece pelo próximo passo possível e busque apoio de uma liderança de confiança.',
                'tone' => 'prático',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'new_level_reached',
                'key' => 'youth_new_level_reached_encouragement',
                'title' => 'Nova etapa alcançada',
                'body' => 'Você chegou a uma nova etapa da Caminhada Jovem. Celebre com gratidão e siga aprendendo, servindo e crescendo com humildade.',
                'tone' => 'pastoral',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'new_level_reached',
                'key' => 'youth_new_level_reached_next',
                'title' => 'Continue avançando',
                'body' => 'Este marco mostra progresso na sua caminhada. Continue firme nos pequenos compromissos e valorize o crescimento no dia a dia.',
                'tone' => 'acolhedor',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'youth_engaged',
                'key' => 'youth_engaged_participation',
                'title' => 'Participação com propósito',
                'body' => 'Sua participação nos Resgatados é importante. Continue presente, aprendendo com a Palavra e contribuindo com respeito e alegria.',
                'tone' => 'bíblico',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
            [
                'analysis_type' => 'youth_engaged',
                'key' => 'youth_engaged_service',
                'title' => 'Jovem disponível',
                'body' => 'É bonito ver disposição para participar e servir. Siga caminhando com equilíbrio, ouvindo a liderança e cuidando do coração.',
                'tone' => 'pastoral',
                'journey_type' => 'youth',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            WalkingMentorResponseTemplate::updateOrCreate(
                [
                    'analysis_type' => $template['analysis_type'],
                    'key' => $template['key'],
                    'journey_type' => $template['journey_type'],
                ],
                $template
            );
        }
    }
}
