<?php

namespace App\Http\Controllers\Secretaria;

use App\Events\DepartmentCreated;
use App\Events\DepartmentDeleted;
use App\Events\DepartmentLeaderChanged;
use App\Events\DepartmentUpdated;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentPerson;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * Controller para gerenciar Departamentos
 * 
 * IMPORTANTE:
 * - Departamento não cria membro automaticamente
 * - Participar de departamento não cria usuário automaticamente
 * - Liderar departamento não cria permissão automaticamente sem controle
 */
class DepartmentController extends Controller
{
    /**
     * Lista todos os departamentos
     */
    public function index()
    {
        $this->authorize('viewAny', Department::class);

        $departments = Department::with(['leader', 'assistantLeader', 'parent'])
            ->withCount('activeDepartmentPeople')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($department) {
                return [
                    'id' => $department->id,
                    'name' => $department->name,
                    'slug' => $department->slug,
                    'description' => $department->description,
                    'department_type' => $department->department_type,
                    'status' => $department->status,
                    'parent_department_id' => $department->parent_department_id,
                    'parent_department_name' => $department->parent?->name,
                    'leader_person_id' => $department->leader_person_id,
                    'leader_name' => $department->leader?->full_name,
                    'assistant_leader_person_id' => $department->assistant_leader_person_id,
                    'assistant_leader_name' => $department->assistantLeader?->full_name,
                    'color' => $department->color,
                    'icon' => $department->icon,
                    'meeting_day' => $department->meeting_day,
                    'meeting_time' => $department->meeting_time?->format('H:i'),
                    'location' => $department->location,
                    'sort_order' => $department->sort_order,
                    'is_system' => $department->is_system,
                    'active_members_count' => $department->active_members_count,
                    'created_at' => $department->created_at?->format('Y-m-d H:i:s'),
                ];
            });

        $stats = [
            'total_departments' => Department::count(),
            'active_departments' => Department::where('status', 'active')->count(),
            'departments_without_leader' => Department::where('status', 'active')
                ->whereNull('leader_person_id')
                ->count(),
            'total_members' => DepartmentPerson::where('status', 'active')->whereNull('ends_at')->count(),
            'administrative_departments' => Department::where('department_type', 'administrative')->where('status', 'active')->count(),
            'ministry_departments' => Department::where('department_type', 'ministry')->where('status', 'active')->count(),
        ];

        return Inertia::render('Secretaria/Departments/Index', [
            'departments' => $departments,
            'stats' => $stats,
        ]);
    }

    /**
     * Mostra o formulário para criar um departamento
     */
    public function create()
    {
        $this->authorize('create', Department::class);

        $parentDepartments = Department::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $people = Person::orderBy('full_name')->get(['id', 'full_name']);

        return Inertia::render('Secretaria/Departments/Create', [
            'parentDepartments' => $parentDepartments,
            'people' => $people,
        ]);
    }

    /**
     * Armazena um novo departamento
     */
    public function store(Request $request)
    {
        $this->authorize('create', Department::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:departments,slug',
            'description' => 'nullable|string',
            'department_type' => 'required|in:ministry,administrative,youth,support,financial,worship,children,evangelism,other',
            'status' => 'required|in:active,inactive,archived',
            'parent_department_id' => 'nullable|exists:departments,id',
            'leader_person_id' => 'nullable|exists:people,id',
            'assistant_leader_person_id' => 'nullable|exists:people,id',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'meeting_day' => 'nullable|string|max:50',
            'meeting_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_system' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $department = Department::create([
            ...$validated,
            'created_by_user_id' => Auth::id(),
        ]);

        event(new DepartmentCreated($department, Auth::id()));

        return redirect()->route('secretaria.departments.show', $department)
            ->with('success', 'Departamento criado com sucesso.');
    }

    /**
     * Mostra os detalhes de um departamento
     */
    public function show(Department $department)
    {
        $this->authorize('view', $department);

        $department->load(['leader', 'assistantLeader', 'parent', 'children', 'activePeople']);

        $departmentData = [
            'id' => $department->id,
            'name' => $department->name,
            'slug' => $department->slug,
            'description' => $department->description,
            'department_type' => $department->department_type,
            'status' => $department->status,
            'parent_department_id' => $department->parent_department_id,
            'parent_department_name' => $department->parent?->name,
            'leader_person_id' => $department->leader_person_id,
            'leader_name' => $department->leader?->full_name,
            'assistant_leader_person_id' => $department->assistant_leader_person_id,
            'assistant_leader_name' => $department->assistantLeader?->full_name,
            'color' => $department->color,
            'icon' => $department->icon,
            'meeting_day' => $department->meeting_day,
            'meeting_time' => $department->meeting_time?->format('H:i'),
            'location' => $department->location,
            'sort_order' => $department->sort_order,
            'is_system' => $department->is_system,
            'notes' => $department->notes,
            'active_members_count' => $department->active_members_count,
            'created_at' => $department->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $department->updated_at?->format('Y-m-d H:i:s'),
        ];

        $members = $department->activeDepartmentPeople()
            ->with('person')
            ->orderBy('is_leader', 'desc')
            ->orderBy('is_assistant', 'desc')
            ->get()
            ->map(function ($dp) {
                return [
                    'id' => $dp->id,
                    'person_id' => $dp->person_id,
                    'person_name' => $dp->person->full_name,
                    'role' => $dp->role,
                    'category' => $dp->category,
                    'is_leader' => $dp->is_leader,
                    'is_assistant' => $dp->is_assistant,
                    'can_manage_department' => $dp->can_manage_department,
                    'starts_at' => $dp->starts_at?->format('Y-m-d'),
                    'status' => $dp->status,
                ];
            });

        return Inertia::render('Secretaria/Departments/Show', [
            'department' => $departmentData,
            'members' => $members,
        ]);
    }

    /**
     * Mostra o formulário para editar um departamento
     */
    public function edit(Department $department)
    {
        $this->authorize('update', $department);

        $parentDepartments = Department::where('status', 'active')
            ->where('id', '!=', $department->id)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $people = Person::orderBy('full_name')->get(['id', 'full_name']);

        return Inertia::render('Secretaria/Departments/Edit', [
            'department' => [
                'id' => $department->id,
                'name' => $department->name,
                'slug' => $department->slug,
                'description' => $department->description,
                'department_type' => $department->department_type,
                'status' => $department->status,
                'parent_department_id' => $department->parent_department_id,
                'leader_person_id' => $department->leader_person_id,
                'assistant_leader_person_id' => $department->assistant_leader_person_id,
                'color' => $department->color,
                'icon' => $department->icon,
                'meeting_day' => $department->meeting_day,
                'meeting_time' => $department->meeting_time?->format('H:i'),
                'location' => $department->location,
                'sort_order' => $department->sort_order,
                'is_system' => $department->is_system,
                'notes' => $department->notes,
            ],
            'parentDepartments' => $parentDepartments,
            'people' => $people,
        ]);
    }

    /**
     * Atualiza um departamento
     */
    public function update(Request $request, Department $department)
    {
        $this->authorize('update', $department);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:departments,slug,' . $department->id,
            'description' => 'nullable|string',
            'department_type' => 'required|in:ministry,administrative,youth,support,financial,worship,children,evangelism,other',
            'status' => 'required|in:active,inactive,archived',
            'parent_department_id' => 'nullable|exists:departments,id',
            'leader_person_id' => 'nullable|exists:people,id',
            'assistant_leader_person_id' => 'nullable|exists:people,id',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'meeting_day' => 'nullable|string|max:50',
            'meeting_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_system' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $oldLeader = $department->leader;
        $oldData = $department->toArray();

        $department->update([
            ...$validated,
            'updated_by_user_id' => Auth::id(),
        ]);

        event(new DepartmentUpdated($department, Auth::id(), $oldData));

        // Verificar se o líder foi alterado
        if ($oldLeader?->id !== $department->leader_person_id) {
            $newLeader = $department->leader;
            event(new DepartmentLeaderChanged($department, $oldLeader, $newLeader, Auth::id()));
        }

        return redirect()->route('secretaria.departments.show', $department)
            ->with('success', 'Departamento atualizado com sucesso.');
    }

    /**
     * Exclui um departamento (soft delete)
     * 
     * IMPORTANTE:
     * - Departamento do sistema não pode ser excluído
     * - Departamento com pessoas ativas vinculadas não pode ser excluído
     * - Usa soft delete (não apaga dados do banco)
     * - Não apaga pessoas, usuários, membros ou member_profile
     * - Gera ActivityLog através de evento DepartmentDeleted
     */
    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        // Bloquear exclusão de departamento do sistema
        if ($department->is_system) {
            return back()->with('error', 'Este é um departamento do sistema e não pode ser excluído.');
        }

        // Verificar se há pessoas ativas vinculadas
        $hasActivePeople = $department->departmentPeople()
            ->where('status', 'active')
            ->whereNull('ends_at')
            ->exists();

        if ($hasActivePeople) {
            return back()->with('error', 'Este departamento possui pessoas ativas vinculadas. Remova ou inative os vínculos antes de excluir.');
        }

        // Registrar dados antes da exclusão para log/evento
        $departmentName = $department->name;
        $userId = Auth::id();

        // Usar transação para evitar ação parcial quebrada
        DB::transaction(function () use ($department, $userId) {
            // Soft delete (não apaga dados do banco, apenas marca como deleted_at)
            $department->delete();

            // Disparar evento para log
            event(new DepartmentDeleted($department, $userId));
        });

        return redirect()
            ->route('secretaria.departments.index')
            ->with('success', "Departamento {$departmentName} excluído com segurança.");
    }
}
