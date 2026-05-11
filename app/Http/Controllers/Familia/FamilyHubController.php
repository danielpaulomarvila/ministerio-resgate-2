<?php

namespace App\Http\Controllers\Familia;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Person;
use App\Models\SystemAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FamilyHubController extends Controller
{
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $person = $user->person ?? null;

        // Saudação personalizada
        $greetingName = $person->preferred_name ?? $person->full_name ?? $user->name ?? 'Família Resgate';

        // Aniversariantes do dia
        $today = now();
        $birthdayPeople = Person::query()
            ->whereNotNull('birth_date')
            ->whereMonth('birth_date', $today->month)
            ->whereDay('birth_date', $today->day)
            ->where('person_status', '!=', 'inactive')
            ->limit(5)
            ->get(['id', 'full_name', 'preferred_name', 'birth_date']);

        $birthdayCount = Person::query()
            ->whereNotNull('birth_date')
            ->whereMonth('birth_date', $today->month)
            ->whereDay('birth_date', $today->day)
            ->where('person_status', '!=', 'inactive')
            ->count();

        // Atalhos de sistemas existentes
        $shortcuts = $this->getAvailableShortcuts($user);

        // Alertas pessoais simples (se houver dados seguros)
        $personalAlertsCount = 0;
        if ($person) {
            // Verificar se há alertas relacionados ao usuário/pessoa
            // Por enquanto, manter simples e seguro
            $personalAlertsCount = 0;
        }

        return Inertia::render('Familia/Index', [
            'greetingName' => $greetingName,
            'birthdayPeople' => $birthdayPeople->map(fn ($person) => [
                'id' => $person->id,
                'name' => $person->preferred_name ?? $person->full_name,
                'birth_date' => optional($person->birth_date)->format('d/m'),
            ]),
            'birthdayCount' => $birthdayCount,
            'shortcuts' => $shortcuts,
            'personalAlertsCount' => $personalAlertsCount,
        ]);
    }

    private function getAvailableShortcuts($user): array
    {
        $shortcuts = [];

        // Secretaria
        if ($user->can('secretaria.view') || $user->can('people.view')) {
            $shortcuts[] = [
                'title' => 'Secretaria',
                'description' => 'Cadastros, famílias, solicitações e organização administrativa.',
                'route' => route('secretaria.dashboard'),
                'visible' => true,
            ];
        }

        // Departamentos
        if ($user->can('departments.view')) {
            $shortcuts[] = [
                'title' => 'Departamentos',
                'description' => 'Ministérios, equipes e vínculos.',
                'route' => route('secretaria.departments.index'),
                'visible' => true,
            ];
        }

        // Acessos
        if ($user->can('accesses.view') || $user->can('access_profiles.view')) {
            $shortcuts[] = [
                'title' => 'Acessos',
                'description' => 'Gestão de usuários e permissões.',
                'route' => route('secretaria.access.index'),
                'visible' => true,
            ];
        }

        return $shortcuts;
    }
}
