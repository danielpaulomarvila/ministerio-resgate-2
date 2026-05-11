<?php

namespace App\Http\Controllers\Secretaria;

use App\Events\DepartmentPersonAttached;
use App\Events\DepartmentPersonRemoved;
use App\Events\DepartmentPersonUpdated;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * Controller para gerenciar vínculos entre pessoas e departamentos
 * 
 * IMPORTANTE:
 * - Vínculo em departamento não cria membro automaticamente
 * - Vínculo em departamento não cria usuário automaticamente
 * - Ser líder de departamento não cria permissão automaticamente sem controle
 * - Remover vínculo não apaga pessoa, usuário ou membro
 */
class DepartmentPersonController extends Controller
{
    /**
     * Armazena um novo vínculo pessoa-departamento
     */
    public function store(Request $request, Department $department)
    {
        $this->authorize('managePeople', $department);

        $validated = $request->validate([
            'person_id' => 'required|exists:people,id',
            'role' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'starts_at' => 'nullable|date',
            'is_leader' => 'boolean',
            'is_assistant' => 'boolean',
            'can_manage_department' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Verificar se já existe vínculo ativo da mesma pessoa no mesmo departamento
        $existing = DepartmentPerson::where('department_id', $department->id)
            ->where('person_id', $validated['person_id'])
            ->where('status', 'active')
            ->whereNull('ends_at')
            ->first();

        if ($existing) {
            return back()->with('error', 'Esta pessoa já possui um vínculo ativo neste departamento.');
        }

        $departmentPerson = DepartmentPerson::create([
            ...$validated,
            'department_id' => $department->id,
            'status' => 'active',
            'created_by_user_id' => Auth::id(),
        ]);

        $person = Person::find($validated['person_id']);

        event(new DepartmentPersonAttached($department, $person, $departmentPerson, Auth::id()));

        return redirect()->route('secretaria.departments.show', $department)
            ->with('success', 'Pessoa adicionada ao departamento com sucesso.');
    }

    /**
     * Atualiza um vínculo pessoa-departamento
     */
    public function update(Request $request, Department $department, DepartmentPerson $departmentPerson)
    {
        $this->authorize('managePeople', $department);

        $validated = $request->validate([
            'role' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'is_leader' => 'boolean',
            'is_assistant' => 'boolean',
            'can_manage_department' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $oldData = $departmentPerson->toArray();

        $departmentPerson->update([
            ...$validated,
            'updated_by_user_id' => Auth::id(),
        ]);

        $person = $departmentPerson->person;

        event(new DepartmentPersonUpdated($department, $person, $departmentPerson, Auth::id(), $oldData));

        return redirect()->route('secretaria.departments.show', $department)
            ->with('success', 'Vínculo atualizado com sucesso.');
    }

    /**
     * Remove/inativa um vínculo pessoa-departamento
     */
    public function destroy(Department $department, DepartmentPerson $departmentPerson)
    {
        $this->authorize('managePeople', $department);

        // Inativar em vez de apagar para manter histórico
        $departmentPerson->update([
            'status' => 'inactive',
            'ends_at' => now(),
            'updated_by_user_id' => Auth::id(),
        ]);

        $person = $departmentPerson->person;

        event(new DepartmentPersonRemoved($department, $person, $departmentPerson, Auth::id()));

        return redirect()->route('secretaria.departments.show', $department)
            ->with('success', 'Pessoa removida do departamento com sucesso.');
    }
}
