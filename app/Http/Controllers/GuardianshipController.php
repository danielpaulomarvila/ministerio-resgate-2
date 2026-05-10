<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuardianshipRequest;
use App\Http\Requests\UpdateGuardianshipRequest;
use App\Models\GuardianShip;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * Controller para gerenciar responsáveis legais e supervisores de menores
 * Módulo Secretaria - Etapa 3
 *
 * Este controller gerencia as responsabilidades, supervisões e autorizações
 * sobre menores de idade, incluindo responsável legal, financeiro, autorização
 * de login, aprovação de alterações, visualização financeira e dívidas da cantina.
 */
class GuardianshipController extends Controller
{
    /**
     * Listar todas as responsabilidades de guardianship
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $guardianships = GuardianShip::with(['minor', 'guardian'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($guardianship) {
                return [
                    'id' => $guardianship->id,
                    'minor' => [
                        'id' => $guardianship->minor->id,
                        'full_name' => $guardianship->minor->full_name,
                        'age' => $guardianship->minor->age,
                        'age_group_label' => $guardianship->minor->ageGroupLabel(),
                    ],
                    'guardian' => [
                        'id' => $guardianship->guardian->id,
                        'full_name' => $guardianship->guardian->full_name,
                    ],
                    'relationship_type' => $guardianship->relationship_type,
                    'is_legal_guardian' => $guardianship->is_legal_guardian,
                    'is_financial_responsible' => $guardianship->is_financial_responsible,
                    'can_authorize_login' => $guardianship->can_authorize_login,
                    'can_approve_changes' => $guardianship->can_approve_changes,
                    'can_view_financial' => $guardianship->can_view_financial,
                    'can_receive_canteen_debts' => $guardianship->can_receive_canteen_debts,
                    'status' => $guardianship->status,
                    'starts_at' => $guardianship->starts_at,
                    'ends_at' => $guardianship->ends_at,
                    'is_active' => $guardianship->isActive(),
                ];
            });

        return Inertia::render('Guardianships/Index', [
            'guardianships' => $guardianships,
        ]);
    }

    /**
     * Mostrar formulário para criar nova responsabilidade
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $people = Person::select('id', 'full_name', 'birth_date')
            ->orderBy('full_name')
            ->get()
            ->map(function ($person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'age' => $person->age,
                    'age_group_label' => $person->ageGroupLabel(),
                    'is_adult' => $person->isAdult(),
                ];
            });

        return Inertia::render('Guardianships/Create', [
            'people' => $people,
        ]);
    }

    /**
     * Salvar nova responsabilidade
     *
     * @param  StoreGuardianshipRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreGuardianshipRequest $request)
    {
        DB::beginTransaction();
        try {
            $guardianship = GuardianShip::create($request->validated());

            // Verificar se o responsável está na mesma família do menor
            $minorFamilies = $guardianship->minor->families;
            $guardianFamilies = $guardianship->guardian->families;
            
            $sharesFamily = $minorFamilies->pluck('id')->intersect($guardianFamilies->pluck('id'))->isNotEmpty();

            DB::commit();

            return redirect()
                ->route('guardianships.show', $guardianship->id)
                ->with('success', 'Responsabilidade criada com sucesso.' . ($sharesFamily ? '' : ' Aviso: Este responsável não está vinculado à mesma família do menor.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao criar responsabilidade: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalhes de uma responsabilidade
     *
     * @param  GuardianShip  $guardianship
     * @return \Inertia\Response
     */
    public function show(GuardianShip $guardianship)
    {
        $guardianship->load(['minor', 'guardian']);

        return Inertia::render('Guardianships/Show', [
            'guardianship' => [
                'id' => $guardianship->id,
                'minor' => [
                    'id' => $guardianship->minor->id,
                    'full_name' => $guardianship->minor->full_name,
                    'age' => $guardianship->minor->age,
                    'age_group_label' => $guardianship->minor->ageGroupLabel(),
                    'is_under_11' => $guardianship->minor->isUnder11YearsOld(),
                    'is_junior' => $guardianship->minor->isJunior(),
                    'is_young' => $guardianship->minor->isYoung(),
                ],
                'guardian' => [
                    'id' => $guardianship->guardian->id,
                    'full_name' => $guardianship->guardian->full_name,
                    'age' => $guardianship->guardian->age,
                ],
                'relationship_type' => $guardianship->relationship_type,
                'is_legal_guardian' => $guardianship->is_legal_guardian,
                'is_financial_responsible' => $guardianship->is_financial_responsible,
                'can_authorize_login' => $guardianship->can_authorize_login,
                'can_approve_changes' => $guardianship->can_approve_changes,
                'can_view_financial' => $guardianship->can_view_financial,
                'can_receive_canteen_debts' => $guardianship->can_receive_canteen_debts,
                'starts_at' => $guardianship->starts_at,
                'ends_at' => $guardianship->ends_at,
                'status' => $guardianship->status,
                'notes' => $guardianship->notes,
                'is_active' => $guardianship->isActive(),
                'created_at' => $guardianship->created_at,
                'updated_at' => $guardianship->updated_at,
            ],
        ]);
    }

    /**
     * Mostrar formulário para editar responsabilidade
     *
     * @param  GuardianShip  $guardianship
     * @return \Inertia\Response
     */
    public function edit(GuardianShip $guardianship)
    {
        $guardianship->load(['minor', 'guardian']);

        $people = Person::select('id', 'full_name', 'birth_date')
            ->orderBy('full_name')
            ->get()
            ->map(function ($person) {
                return [
                    'id' => $person->id,
                    'full_name' => $person->full_name,
                    'age' => $person->age,
                    'age_group_label' => $person->ageGroupLabel(),
                    'is_adult' => $person->isAdult(),
                ];
            });

        return Inertia::render('Guardianships/Edit', [
            'guardianship' => [
                'id' => $guardianship->id,
                'minor_person_id' => $guardianship->minor_person_id,
                'guardian_person_id' => $guardianship->guardian_person_id,
                'relationship_type' => $guardianship->relationship_type,
                'is_legal_guardian' => $guardianship->is_legal_guardian,
                'is_financial_responsible' => $guardianship->is_financial_responsible,
                'can_authorize_login' => $guardianship->can_authorize_login,
                'can_approve_changes' => $guardianship->can_approve_changes,
                'can_view_financial' => $guardianship->can_view_financial,
                'can_receive_canteen_debts' => $guardianship->can_receive_canteen_debts,
                'starts_at' => $guardianship->starts_at,
                'ends_at' => $guardianship->ends_at,
                'status' => $guardianship->status,
                'notes' => $guardianship->notes,
            ],
            'people' => $people,
        ]);
    }

    /**
     * Atualizar responsabilidade
     *
     * @param  UpdateGuardianshipRequest  $request
     * @param  GuardianShip  $guardianship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateGuardianshipRequest $request, GuardianShip $guardianship)
    {
        DB::beginTransaction();
        try {
            $guardianship->update($request->validated());

            DB::commit();

            return redirect()
                ->route('guardianships.show', $guardianship->id)
                ->with('success', 'Responsabilidade atualizada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar responsabilidade: ' . $e->getMessage());
        }
    }

    /**
     * Remover responsabilidade (soft delete)
     *
     * @param  GuardianShip  $guardianship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(GuardianShip $guardianship)
    {
        DB::beginTransaction();
        try {
            // Em vez de apagar, vamos marcar como ended com data atual
            $guardianship->update([
                'status' => 'ended',
                'ends_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('guardianships.index')
                ->with('success', 'Responsabilidade encerrada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao encerrar responsabilidade: ' . $e->getMessage());
        }
    }
}
