<?php

namespace App\Http\Controllers;

use App\Events\FamilyCreated;
use App\Events\FamilyMemberAttached;
use App\Events\FamilyMemberDetached;
use App\Events\FamilyUpdated;
use App\Http\Requests\StoreFamilyRequest;
use App\Http\Requests\StoreFamilyMemberRequest;
use App\Http\Requests\UpdateFamilyRequest;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller para gerenciar famílias no sistema
 * Módulo Secretaria - Fase 2.2
 * 
 * Este controller lida com o CRUD básico de famílias,
 * permitindo que a Secretaria cadastre e gerencie
 * famílias e seus membros.
 * 
 * Etapa 9: Adicionados disparos de Events para auditoria
 * Etapa 9: Adicionada autorização via FamilyPolicy
 */
class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Autorização via FamilyPolicy (Etapa 9)
        $this->authorize('viewAny', Family::class);
        
        $families = Family::with('responsible')
            ->withCount('members')
            ->orderBy('name')
            ->get();

        return inertia('Families/Index', [
            'families' => $families,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $people = Person::orderBy('full_name')->get(['id', 'full_name']);

        return inertia('Families/Create', [
            'people' => $people,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFamilyRequest $request)
    {
        // Autorização via FamilyPolicy (Etapa 9)
        $this->authorize('create', Family::class);
        
        DB::beginTransaction();
        try {
            $family = Family::create($request->validated());

            // Se informou responsável principal, vincular automaticamente como membro
            if ($request->validated('responsible_person_id')) {
                FamilyMember::create([
                    'family_id' => $family->id,
                    'person_id' => $request->validated('responsible_person_id'),
                    'role' => 'other',
                    'is_responsible' => true,
                    'joined_at' => now(),
                ]);
            }

            DB::commit();

            // Disparar evento para registrar log e notificações (Etapa 9)
            event(new FamilyCreated($family, Auth::id()));

            return redirect()->route('families.show', $family)
                ->with('success', 'Família criada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao criar família: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Family $family)
    {
        // Autorização via FamilyPolicy (Etapa 9)
        $this->authorize('view', $family);
        
        $family->load('responsible');
        $family->load(['members' => function ($query) {
            $query->withPivot('role', 'is_responsible', 'joined_at', 'left_at', 'notes')
                ->wherePivotNull('left_at')
                ->orderBy('pivot_is_responsible', 'desc')
                ->orderBy('full_name');
        }]);

        // Calcular faixa etária de cada membro
        $membersWithAge = $family->members->map(function ($person) {
            $age = $person->birth_date ? $person->birth_date->age : null;
            $ageCategory = $this->getAgeCategory($age);

            return array_merge($person->toArray(), [
                'age' => $age,
                'age_category' => $ageCategory,
            ]);
        });

        $people = Person::orderBy('full_name')->get(['id', 'full_name', 'birth_date']);

        return inertia('Families/Show', [
            'family' => $family,
            'members' => $membersWithAge,
            'people' => $people,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Family $family)
    {
        $family->load('responsible');
        $people = Person::orderBy('full_name')->get(['id', 'full_name']);

        return inertia('Families/Edit', [
            'family' => $family,
            'people' => $people,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFamilyRequest $request, Family $family)
    {
        // Autorização via FamilyPolicy (Etapa 9)
        $this->authorize('update', $family);
        
        DB::beginTransaction();
        try {
            $family->update($request->validated());

            // Se responsável principal mudou e não está na família, vincular
            if ($request->validated('responsible_person_id')) {
                $existingMember = FamilyMember::where('family_id', $family->id)
                    ->where('person_id', $request->validated('responsible_person_id'))
                    ->whereNull('left_at')
                    ->first();

                if (!$existingMember) {
                    FamilyMember::create([
                        'family_id' => $family->id,
                        'person_id' => $request->validated('responsible_person_id'),
                        'role' => 'other',
                        'is_responsible' => true,
                        'joined_at' => now(),
                    ]);
                }
            }

            DB::commit();

            // Disparar evento para registrar log e notificações (Etapa 9)
            event(new FamilyUpdated($family, Auth::id()));

            return redirect()->route('families.show', $family)
                ->with('success', 'Família atualizada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar família: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Family $family)
    {
        // Autorização via FamilyPolicy (Etapa 9)
        $this->authorize('delete', $family);
        
        try {
            $family->delete();

            return redirect()->route('families.index')
                ->with('success', 'Família removida com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover família: ' . $e->getMessage());
        }
    }

    /**
     * Attach a person to the family.
     */
    public function attachPerson(StoreFamilyMemberRequest $request, Family $family)
    {
        // Autorização via FamilyPolicy (Etapa 9)
        $this->authorize('update', $family);
        
        DB::beginTransaction();
        try {
            // Verificar se pessoa já está na família ativa
            $existingMember = FamilyMember::where('family_id', $family->id)
                ->where('person_id', $request->validated('person_id'))
                ->whereNull('left_at')
                ->first();

            if ($existingMember) {
                return back()->with('error', 'Esta pessoa já está vinculada à família.');
            }

            $person = Person::find($request->validated('person_id'));
            
            FamilyMember::create(array_merge($request->validated(), [
                'family_id' => $family->id,
                'joined_at' => $request->validated('joined_at') ?? now(),
            ]));

            DB::commit();

            // Disparar evento para registrar log e notificações (Etapa 9)
            event(new FamilyMemberAttached($person, $family, $request->validated('role'), Auth::id()));

            return redirect()->route('families.show', $family)
                ->with('success', 'Pessoa vinculada à família com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao vincular pessoa: ' . $e->getMessage());
        }
    }

    /**
     * Update a family member.
     */
    public function updateMember(Request $request, Family $family, FamilyMember $member)
    {
        // Autorização via FamilyPolicy (Etapa 9)
        $this->authorize('update', $family);
        
        try {
            $member->update($request->validate([
                'role' => 'required|in:father,mother,son,daughter,spouse,guardian,relative,other',
                'is_responsible' => 'boolean',
                'notes' => 'nullable|string',
            ]));

            return redirect()->route('families.show', $family)
                ->with('success', 'Vínculo atualizado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar vínculo: ' . $e->getMessage());
        }
    }

    /**
     * Detach a person from the family (soft detach with left_at).
     */
    public function detachPerson(Family $family, FamilyMember $member)
    {
        // Autorização via FamilyPolicy (Etapa 9)
        $this->authorize('update', $family);
        
        try {
            $person = $member->person;
            
            $member->update(['left_at' => now()]);

            // Disparar evento para registrar log e notificações (Etapa 9)
            event(new FamilyMemberDetached($person, $family, Auth::id()));

            return redirect()->route('families.show', $family)
                ->with('success', 'Pessoa removida da família com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover pessoa: ' . $e->getMessage());
        }
    }

    /**
     * Calculate age category based on age.
     */
    private function getAgeCategory(?int $age): string
    {
        if ($age === null) {
            return 'Desconhecido';
        }

        if ($age < 11) {
            return 'Criança menor de 11 anos';
        }

        if ($age >= 11 && $age < 14) {
            return 'Júnior';
        }

        if ($age >= 14 && $age < 18) {
            return 'Jovem';
        }

        return 'Adulto';
    }
}
