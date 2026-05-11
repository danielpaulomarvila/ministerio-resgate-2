# Documento: Departamentos e Ministérios

## Visão Geral

Este módulo gerencia os departamentos e ministérios da igreja, incluindo sua criação, edição, visualização e exclusão segura.

## Características Principais

### Soft Delete

- Departamentos usam soft delete (deleted_at) em vez de exclusão física
- Dados não são apagados do banco, apenas marcados como excluídos
- Permite restauração se necessário

### Proteção de Departamentos do Sistema

- Departamentos marcados como `is_system = true` não podem ser excluídos
- Mensagem de erro clara: "Este é um departamento do sistema e não pode ser excluído."
- Departamentos do sistema incluem: Louvor, Mídia, Recepção, Obreiros, Jovens/Resgatados, Infantil, Tesouraria, Secretaria, Cantina, Evangelismo, Intercessão, Ensino/Discipulado

### Proteção de Departamentos com Pessoas Ativas

- Departamentos com pessoas ativas vinculadas não podem ser excluídos
- Critério: `department_people.status = active` E `ends_at IS NULL`
- Mensagem de erro: "Este departamento possui pessoas ativas vinculadas. Remova ou inative os vínculos antes de excluir."

### Regras Importantes

- **Departamento não cria membro automaticamente**
- **Participar de departamento não cria usuário automaticamente**
- **Liderar departamento não cria permissão automaticamente sem controle**
- **Excluir departamento não exclui pessoa, usuário, membro ou member_profile**
- **A exclusão deve gerar ActivityLog através de evento DepartmentDeleted**

## Campos do Model Department

- `id` - Identificador único
- `name` - Nome do departamento
- `slug` - Identificador único (URL-friendly)
- `description` - Descrição do departamento
- `department_type` - Tipo (ministry, administrative, youth, support, financial, worship, children, evangelism, other)
- `status` - Status (active, inactive, archived)
- `parent_department_id` - Departamento pai (hierarquia)
- `leader_person_id` - Pessoa líder
- `assistant_leader_person_id` - Pessoa auxiliar
- `color` - Cor para identificação visual
- `icon` - Ícone para identificação visual
- `meeting_day` - Dia de reunião
- `meeting_time` - Horário de reunião
- `location` - Local de reunião
- `sort_order` - Ordem de exibição
- `is_system` - Se é departamento do sistema (protegido)
- `notes` - Observações
- `deleted_at` - Soft delete timestamp

## Relacionamentos

- `leader()` - BelongsTo para Person (líder)
- `assistantLeader()` - BelongsTo para Person (auxiliar)
- `parent()` - BelongsTo para Department (departamento pai)
- `children()` - HasMany para Department (departamentos filhos)
- `departmentPeople()` - HasMany para DepartmentPerson (vínculos de pessoas)
- `people()` - BelongsToMany para Person (pessoas vinculadas)
- `activityLogs()` - MorphMany para ActivityLog (logs de alterações)

## Autorização (Policy)

### Permissões

- `departments.view` - Ver departamentos
- `departments.create` - Criar departamentos
- `departments.update` - Atualizar departamentos
- `departments.manage` - Gerir departamentos (inclui delete)
- `departments.manage_people` - Gerir pessoas de departamento
- `departments.assign_leader` - Atribuir líder

### Métodos da Policy

- `viewAny(User $user)` - Ver lista de departamentos
- `view(User $user, Department $department)` - Ver departamento específico
- `create(User $user)` - Criar departamento
- `update(User $user, Department $department)` - Atualizar departamento
- `delete(User $user, Department $department)` - Excluir departamento
- `restore(User $user, Department $department)` - Restaurar departamento excluído
- `forceDelete(User $user, Department $department)` - Excluir permanentemente (apenas super-admin)
- `managePeople(User $user, Department $department)` - Gerir pessoas do departamento
- `assignLeader(User $user, Department $department)` - Atribuir líder

## Eventos e Listeners

### Events

- `DepartmentCreated` - Departamento criado
- `DepartmentUpdated` - Departamento atualizado
- `DepartmentDeleted` - Departamento excluído (soft delete)
- `DepartmentLeaderChanged` - Líder do departamento alterado
- `DepartmentPersonAttached` - Pessoa adicionada ao departamento
- `DepartmentPersonUpdated` - Vínculo de pessoa atualizado
- `DepartmentPersonRemoved` - Pessoa removida/inativada do departamento

### Listeners

- `LogDepartmentCreated` - Registra log de criação
- `LogDepartmentUpdated` - Registra log de atualização
- `LogDepartmentDeleted` - Registra log de exclusão
- `LogDepartmentLeaderChanged` - Registra log de mudança de líder
- `LogDepartmentPersonAttached` - Registra log de adição de pessoa
- `LogDepartmentPersonUpdated` - Registra log de atualização de vínculo
- `LogDepartmentPersonRemoved` - Registra log de remoção de pessoa

## ActivityLog

### Ações Registradas

- `department.created` - Departamento criado
- `department.updated` - Departamento atualizado
- `department.deleted` - Departamento excluído (soft delete)
- `department.leader_changed` - Líder alterado
- `department.person_attached` - Pessoa adicionada ao departamento
- `department.person_updated` - Vínculo de pessoa atualizado
- `department.person_removed` - Pessoa removida do departamento

### Campos do Log

- `user_id` - Usuário que realizou a ação
- `action` - Ação realizada
- `description` - Descrição da ação
- `model_type` - Tipo do modelo (Department::class)
- `model_id` - ID do departamento
- `old_values` - Valores anteriores (para update/delete)
- `new_values` - Novos valores (para create/update)

## Rotas

- `GET /secretaria/departamentos` - Listagem (secretaria.departments.index)
- `GET /secretaria/departamentos/criar` - Formulário de criação (secretaria.departments.create)
- `POST /secretaria/departamentos` - Salvar criação (secretaria.departments.store)
- `GET /secretaria/departamentos/{department}` - Detalhes (secretaria.departments.show)
- `GET /secretaria/departamentos/{department}/editar` - Formulário de edição (secretaria.departments.edit)
- `PUT/PATCH /secretaria/departamentos/{department}` - Salvar edição (secretaria.departments.update)
- `DELETE /secretaria/departamentos/{department}` - Excluir (secretaria.departments.destroy)

## Frontend

### Páginas Vue

- `Index.vue` - Listagem de departamentos
- `Create.vue` - Formulário de criação
- `Show.vue` - Detalhes do departamento
- `Edit.vue` - Formulário de edição

### Botões de Ação

#### Index.vue
- Criar Departamento - Navega para criação
- Ver - Navega para detalhes
- Editar - Navega para edição
- Excluir - Exclui departamento (soft delete, não aparece para departamentos do sistema)

#### Show.vue
- Editar - Navega para edição
- Excluir - Exclui departamento (soft delete, não aparece para departamentos do sistema)
- Voltar - Volta para listagem

#### Create.vue
- Cancelar - Volta para listagem
- Salvar - Salva criação

#### Edit.vue
- Cancelar - Volta para detalhes
- Salvar - Salva edição

## Validações

### Validações de Backend

- `name` - Obrigatório
- `slug` - Obrigatório, único
- `department_type` - Obrigatório
- `status` - Obrigatório

### Validações de Exclusão

- Departamento não pode ser do sistema (`is_system = false`)
- Departamento não pode ter pessoas ativas vinculadas
- Usuário deve ter permissão `departments.manage` ou ser super-admin

## Segurança

### Soft Delete

- Usa `SoftDeletes` trait no model
- Coluna `deleted_at` na tabela
- Não apaga dados fisicamente
- Permite restauração

### Proteção de Dados

- Não apaga pessoas ao excluir departamento
- Não apaga usuários ao excluir departamento
- Não apaga membros ao excluir departamento
- Não apaga member_profile ao excluir departamento
- Vínculos históricos são preservados

### Auditoria

- Todas as ações são registradas em ActivityLog
- Eventos são disparados para cada ação
- Listeners registram logs detalhados
- Usuário responsável é sempre registrado

## Migrações

### Tabela departments

```php
Schema::create('departments', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->enum('department_type', ['ministry', 'administrative', 'youth', 'support', 'financial', 'worship', 'children', 'evangelism', 'other'])->default('ministry');
    $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
    $table->foreignId('parent_department_id')->nullable()->constrained('departments')->onDelete('cascade');
    $table->foreignId('leader_person_id')->nullable()->constrained('people')->onDelete('set null');
    $table->foreignId('assistant_leader_person_id')->nullable()->constrained('people')->onDelete('set null');
    $table->string('color')->nullable();
    $table->string('icon')->nullable();
    $table->string('meeting_day')->nullable();
    $table->time('meeting_time')->nullable();
    $table->string('location')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_system')->default(false);
    $table->text('notes')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->index('slug');
    $table->index('status');
    $table->index('department_type');
    $table->index('parent_department_id');
    $table->index('is_system');
});
```

### Tabela department_people

```php
Schema::create('department_people', function (Blueprint $table) {
    $table->id();
    $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
    $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
    $table->string('role_name')->nullable();
    $table->string('category')->nullable();
    $table->date('starts_at')->default(now());
    $table->date('ends_at')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    
    $table->index('department_id');
    $table->index('person_id');
    $table->index('role_name');
    $table->index('category');
    $table->index('status');
    $table->unique(['department_id', 'person_id', 'ends_at']);
});
```

## Seeding

### DepartmentSeeder

Cria departamentos do sistema com `is_system = true`:
- Louvor
- Mídia
- Recepção
- Obreiros
- Jovens/Resgatados
- Infantil
- Tesouraria
- Secretaria
- Cantina
- Evangelismo
- Intercessão
- Ensino/Discipulado

## Testes

### Testes de Policy

- Verificar se super-admin pode excluir qualquer departamento
- Verificar se usuário com permissão pode excluir departamento não-sistema
- Verificar se usuário sem permissão não pode excluir

### Testes de Controller

- Verificar se departamento do sistema não pode ser excluído
- Verificar se departamento com pessoas ativas não pode ser excluído
- Verificar se soft delete é usado
- Verificar se evento é disparado
- Verificar se ActivityLog é criado

### Testes de Frontend

- Verificar se botão Excluir não aparece para departamentos do sistema
- Verificar se confirmação é exibida antes de excluir
- Verificar se redireciona corretamente após exclusão
- Verificar se mensagem de sucesso é exibida
