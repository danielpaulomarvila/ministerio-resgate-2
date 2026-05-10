<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Family;
use App\Models\GuardianShip;
use App\Models\FamilyMember;
use App\Models\SystemAlert;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * SecretaryDashboardController
 *
 * Controller responsável pelo painel inicial da Secretaria.
 *
 * Este painel mostra informações úteis e alertas básicos sobre:
 * - pessoas
 * - famílias
 * - responsáveis
 * - crianças menores de 11 anos
 * - Júniores
 * - Jovens
 * - adultos
 * - pessoas sem família
 * - menores sem responsável ativo
 * - crianças próximas de completar 11 anos
 * - cadastros possivelmente incompletos
 *
 * Etapa 4 do Projeto Ministério Resgate / Família Resgate
 */
class SecretaryDashboardController extends Controller
{
    /**
     * Mostrar o painel inicial da Secretaria
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // 1. Total de pessoas
        $totalPeople = Person::count();

        // 2. Total de famílias
        $totalFamilies = Family::count();

        // 3. Total de responsáveis ativos
        $totalActiveGuardianships = GuardianShip::where('status', 'active')->count();

        // 4. Crianças menores de 11 anos
        $childrenUnder11 = Person::whereNotNull('birth_date')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 11')
            ->count();

        // 5. Júniores (11 até antes de 14)
        $juniors = Person::whereNotNull('birth_date')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 11')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 14')
            ->count();

        // 6. Jovens (14 até antes de 18)
        $youngs = Person::whereNotNull('birth_date')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 14')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18')
            ->count();

        // 7. Adultos (18 anos ou mais)
        $adults = Person::whereNotNull('birth_date')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 18')
            ->count();

        // 8. Pessoas sem família (sem vínculo ativo em family_members)
        $peopleWithoutFamily = Person::whereDoesntHave('families')
            ->count();

        // 9. Menores sem responsável ativo (menores de 18 anos sem guardianship ativo)
        $minorsWithoutGuardian = Person::whereNotNull('birth_date')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18')
            ->whereDoesntHave('guardianshipsAsMinor', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        // 10. Crianças próximas dos 11 anos (completarão 11 anos nos próximos 60 dias)
        $childrenNear11 = Person::whereNotNull('birth_date')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 11')
            ->whereRaw('DATE_ADD(birth_date, INTERVAL 11 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 60 DAY)')
            ->select('id', 'full_name', 'birth_date')
            ->get()
            ->map(function ($person) {
                $age = $person->age;
                $turns11At = $person->birth_date?->addYears(11)?->format('Y-m-d');
                
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'age' => $age,
                    'turns_11_at' => $turns11At,
                ];
            });

        // 11. Pessoas com cadastro incompleto
        // Sem data de nascimento
        $peopleWithoutBirthDate = Person::whereNull('birth_date')->count();
        
        // Sem telefone principal
        $peopleWithoutPhone = Person::whereNull('primary_phone')->count();
        
        // Sem email e sem telefone
        $peopleWithoutEmailOrPhone = Person::whereNull('email')
            ->whereNull('primary_phone')
            ->count();

        // 12. Pessoas recentemente cadastradas (últimas 5)
        $recentPeople = Person::latest()
            ->take(5)
            ->select('id', 'full_name', 'created_at')
            ->get()
            ->map(function ($person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'created_at' => $person->created_at?->format('d/m/Y H:i'),
                ];
            });

        // 13. Famílias recentemente criadas (últimas 5)
        $recentFamilies = Family::latest()
            ->take(5)
            ->select('id', 'name', 'created_at')
            ->get()
            ->map(function ($family) {
                return [
                    'id' => $family->id,
                    'name' => $family->name,
                    'created_at' => $family->created_at?->format('d/m/Y H:i'),
                ];
            });

        // 14. Responsabilidades recentes (últimas 5)
        $recentGuardianships = GuardianShip::with(['minor', 'guardian'])
            ->latest()
            ->take(5)
            ->select('id', 'minor_person_id', 'guardian_person_id', 'created_at')
            ->get()
            ->map(function ($guardianship) {
                return [
                    'id' => $guardianship->id,
                    'minor_name' => $guardianship->minor?->full_name ?? 'Menor não informado',
                    'guardian_name' => $guardianship->guardian?->full_name ?? 'Responsável não informado',
                    'created_at' => $guardianship->created_at?->format('d/m/Y H:i'),
                ];
            });

        // 15. Alertas abertos e urgentes (Etapa 5)
        $openAlerts = SystemAlert::where('status', 'pending')->count();
        $urgentAlerts = SystemAlert::where('status', 'pending')
            ->whereIn('severity', ['high', 'critical'])
            ->count();

        return Inertia::render('Secretaria/Dashboard', [
            // Totais
            'total_people' => $totalPeople,
            'total_families' => $totalFamilies,
            'total_active_guardianships' => $totalActiveGuardianships,
            
            // Faixa etária
            'children_under_11' => $childrenUnder11,
            'juniors' => $juniors,
            'youngs' => $youngs,
            'adults' => $adults,
            
            // Atenções
            'people_without_family' => $peopleWithoutFamily,
            'minors_without_guardian' => $minorsWithoutGuardian,
            'children_near_11' => $childrenNear11,
            'people_without_birth_date' => $peopleWithoutBirthDate,
            'people_without_phone' => $peopleWithoutPhone,
            'people_without_email_or_phone' => $peopleWithoutEmailOrPhone,
            
            // Listas recentes
            'recent_people' => $recentPeople,
            'recent_families' => $recentFamilies,
            'recent_guardianships' => $recentGuardianships,
            
            // Alertas (Etapa 5)
            'open_alerts' => $openAlerts,
            'urgent_alerts' => $urgentAlerts,
        ]);
    }
}
