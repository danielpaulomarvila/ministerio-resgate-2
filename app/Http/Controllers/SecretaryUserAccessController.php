<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use App\Services\Secretaria\UserAccessEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

/**
 * Controller para gerenciar acessos de usuários pela Secretaria
 * 
 * Permite criar, editar, suspender e reativar acessos ao sistema
 * Respeita as regras de idade e responsáveis
 */
class SecretaryUserAccessController extends Controller
{
    protected UserAccessEligibilityService $eligibilityService;

    public function __construct(UserAccessEligibilityService $eligibilityService)
    {
        $this->eligibilityService = $eligibilityService;
    }

    /**
     * Lista todos os usuários do sistema
     */
    public function index()
    {
        $users = User::with('person')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'must_change_password' => (bool) $user->must_change_password,
                'access_granted_at' => $user->access_granted_at ? $user->access_granted_at->format('Y-m-d H:i:s') : null,
                'access_revoked_at' => $user->access_revoked_at ? $user->access_revoked_at->format('Y-m-d H:i:s') : null,
                'access_revoked_reason' => $user->access_revoked_reason,
                'access_notes' => $user->access_notes,
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : null,
                'updated_at' => $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : null,
                'person' => $user->person ? [
                    'id' => $user->person->id,
                    'full_name' => $user->person->full_name,
                    'birth_date' => $user->person->birth_date ? $user->person->birth_date->format('Y-m-d') : null,
                    'email' => $user->person->email,
                    'primary_phone' => $user->person->primary_phone,
                    'age' => $user->person->age ?? null,
                    'age_group' => $user->person->ageGroupLabel(),
                    'is_baptized' => (bool) $user->person->is_baptized,
                    'can_be_member' => (bool) $user->person->canBeMember(),
                ] : null,
            ]);

        $stats = [
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'people_without_user' => Person::whereDoesntHave('user')->count(),
            'juniors_with_access' => User::where('status', 'active')
                ->whereHas('person', function ($query) {
                    $query->where('birth_date', '>=', now()->subYears(13)->toDateString())
                        ->where('birth_date', '<=', now()->subYears(11)->toDateString());
                })
                ->count(),
            'pending_password_change' => User::where('must_change_password', true)->count(),
        ];

        return Inertia::render('Secretaria/Access/Index', [
            'users' => $users,
            'stats' => $stats,
        ]);
    }

    /**
     * Mostra formulário para criar novo usuário
     */
    public function create()
    {
        return Inertia::render('Secretaria/Access/Create');
    }

    /**
     * Armazena novo usuário
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8',
            'access_notes' => 'nullable|string',
        ]);

        $person = Person::findOrFail($validated['person_id']);

        // Verificar se pessoa já tem usuário ativo
        if ($this->eligibilityService->hasActiveUser($person)) {
            return back()->with('error', 'Esta pessoa já possui usuário vinculado.');
        }

        // Verificar elegibilidade
        $eligibility = $this->eligibilityService->check($person);
        if (!$eligibility['allowed']) {
            return back()->with('error', $eligibility['reason']);
        }

        // Gerar senha temporária se não informada
        $temporaryPassword = $validated['password'] ?? Str::random(16);

        $user = User::create([
            'person_id' => $person->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($temporaryPassword),
            'status' => 'active',
            'must_change_password' => true,
            'access_granted_at' => now(),
            'access_notes' => $validated['access_notes'] ?? null,
        ]);

        return redirect()->route('secretaria.access.show', $user)
            ->with('success', 'Usuário criado com senha temporária. Oriente a pessoa a trocar a senha no primeiro acesso.')
            ->with('temporary_password', $temporaryPassword);
    }

    /**
     * Mostra detalhes de um usuário
     */
    public function show(User $user)
    {
        $user->load('person');

        // Verificar se há violação de regra (menor de 11 anos com usuário)
        $hasAgeViolation = false;
        if ($user->person && $user->person->isUnder11YearsOld()) {
            $hasAgeViolation = true;
        }

        // Carregar responsáveis ativos se for Júnior
        $activeGuardians = [];
        if ($user->person && $user->person->isJunior()) {
            $activeGuardians = $user->person->guardianshipsAsMinor()
                ->where('status', 'active')
                ->where('can_authorize_login', true)
                ->with('guardian')
                ->get()
                ->map(function ($guardianship) {
                    return [
                        'id' => $guardianship->guardian->id,
                        'name' => $guardianship->guardian->full_name,
                        'can_authorize_login' => $guardianship->can_authorize_login,
                    ];
                });
        }

        // Preparar dados do usuário como array para evitar problemas de serialização
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'must_change_password' => (bool) $user->must_change_password,
            'access_granted_at' => $user->access_granted_at ? $user->access_granted_at->format('Y-m-d H:i:s') : null,
            'access_revoked_at' => $user->access_revoked_at ? $user->access_revoked_at->format('Y-m-d H:i:s') : null,
            'access_revoked_reason' => $user->access_revoked_reason,
            'access_notes' => $user->access_notes,
            'created_at' => $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : null,
            'person' => $user->person ? [
                'id' => $user->person->id,
                'full_name' => $user->person->full_name,
                'birth_date' => $user->person->birth_date ? $user->person->birth_date->format('Y-m-d') : null,
                'email' => $user->person->email,
                'primary_phone' => $user->person->primary_phone,
                'age' => $user->person->age ?? null,
                'is_baptized' => (bool) $user->person->is_baptized,
                'can_be_member' => (bool) $user->person->can_be_member,
            ] : null,
        ];

        return Inertia::render('Secretaria/Access/Show', [
            'user' => $userData,
            'has_age_violation' => $hasAgeViolation,
            'active_guardians' => $activeGuardians,
        ]);
    }

    /**
     * Mostra formulário para editar usuário
     */
    public function edit(User $user)
    {
        $user->load('person');

        return Inertia::render('Secretaria/Access/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Atualiza usuário
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'access_notes' => 'nullable|string',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'access_notes' => $validated['access_notes'] ?? $user->access_notes,
        ]);

        return redirect()->route('secretaria.access.show', $user)
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Suspende acesso de usuário
     */
    public function suspend(Request $request, User $user)
    {
        $validated = $request->validate([
            'access_revoked_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'status' => 'suspended',
            'access_revoked_at' => now(),
            'access_revoked_reason' => $validated['access_revoked_reason'],
        ]);

        return redirect()->route('secretaria.access.show', $user)
            ->with('success', 'Acesso suspenso com sucesso.');
    }

    /**
     * Reativa acesso de usuário
     */
    public function reactivate(Request $request, User $user)
    {
        // Revalidar elegibilidade se usuário for de menor
        if ($user->person) {
            $eligibility = $this->eligibilityService->check($user->person);
            if (!$eligibility['allowed']) {
                return redirect()->route('secretaria.access.show', $user)
                    ->with('error', 'Não é possível reativar: ' . $eligibility['reason']);
            }
        }

        $validated = $request->validate([
            'access_notes' => 'nullable|string',
        ]);

        $user->update([
            'status' => 'active',
            'access_revoked_at' => null,
            'access_revoked_reason' => null,
            'access_granted_at' => now(),
            'access_notes' => $validated['access_notes'] ?? $user->access_notes,
        ]);

        return redirect()->route('secretaria.access.show', $user)
            ->with('success', 'Acesso reativado com sucesso.');
    }

    /**
     * Verifica elegibilidade de uma pessoa para ter acesso
     */
    public function eligibility(Person $person)
    {
        $eligibility = $this->eligibilityService->check($person);
        $hasActiveUser = $this->eligibilityService->hasActiveUser($person);

        return response()->json([
            'eligibility' => $eligibility,
            'has_active_user' => $hasActiveUser,
        ]);
    }
}
