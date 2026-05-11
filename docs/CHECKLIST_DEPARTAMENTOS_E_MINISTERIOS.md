# Checklist Departamentos e Ministérios

## Visão Geral
- **Status**: IMPLEMENTADA
- **Data de Início**: 11/05/2026
- **Data de Conclusão**: 11/05/2026
- **Objetivo**: Criar o módulo de Departamentos e Ministérios com CRUD completo, gestão de membros, events/listeners, job de verificação, integração com o sistema de alertas e exclusão segura com modal visual.

## Regras Estritas
- ✅ Reutilizar tabelas existentes se presentes
- ✅ Não criar duplicação de estruturas
- ✅ Não criar app mobile agora
- ✅ Não apagar dados existentes
- ✅ Não quebrar etapas anteriores
- ✅ Vínculo em departamento não cria membro automaticamente
- ✅ Vínculo em departamento não cria usuário automaticamente
- ✅ Liderar departamento não cria permissão automaticamente sem controle
- ✅ Exclusão usa modal visual, não confirm nativo do navegador
- ✅ Exclusão usa soft delete (deleted_at)
- ✅ Departamento do sistema não pode ser excluído
- ✅ Departamento com pessoas ativas não pode ser excluído
- ✅ Exclusão não apaga pessoas, usuários, membros ou member_profile
- ✅ Departamentos raiz/oficiais são is_system = true (14 departamentos)
- ✅ Departamentos criados pela interface nascem is_system = false
- ✅ Tipos, status e funções/roles aparecem em português na interface
- ✅ Role "member" aparece como "Participante da Equipe" (não "Membro")
- ✅ Identificadores/slugs continuam técnicos e não são traduzidos

## Tarefas

### 1. Verificar Estruturas Existentes
- [x] Verificar migrations de departments
- [x] Verificar migrations de department_people
- [x] Verificar models Department e DepartmentPerson

**Encontrado:**
- Migration `create_departments_table` já existe (2026_05_09_165337)
- Migration `create_department_people_table` já existe (2026_05_09_165340)
- Model Department já existe
- Model DepartmentPerson já existe

### 2. Migrations de Ajuste
- [x] Criar migration `alter_departments_table_add_fields.php`
  - Adiciona: department_type, parent_department_id, leader_person_id, assistant_leader_person_id, color, icon, meeting_day, meeting_time, location, sort_order, is_system, notes, created_by_user_id, updated_by_user_id
- [x] Criar migration `alter_department_people_table_add_fields.php`
  - Renomeia role_name para role
  - Adiciona: is_leader, is_assistant, can_manage_department, notes, created_by_user_id, updated_by_user_id

### 3. Models
- [x] Atualizar model Department
  - Adicionar campos do fillable e casts
  - Adicionar relacionamentos: parent, children, leader, assistantLeader, createdBy, updatedBy, activePeople, departmentPeople
  - Adicionar métodos auxiliares: isSystem, activeMembersCount, leadersLabel
  - Adicionar SoftDeletes trait
- [x] Atualizar model DepartmentPerson
  - Renomear role_name para role no fillable
  - Adicionar campos: is_leader, is_assistant, can_manage_department, notes, created_by_user_id, updated_by_user_id
  - Adicionar casts para booleanos
  - Adicionar relacionamentos: createdBy, updatedBy
  - Adicionar métodos: isLeader, isAssistant, canManage
- [x] Atualizar model Person
  - Atualizar relacionamento departments() com novos campos do pivot
  - Adicionar relacionamento activeDepartments()
  - Adicionar relacionamento departmentMemberships()

### 4. Policy
- [x] Criar/Atualizar DepartmentPolicy
  - viewAny: departments.view
  - view: departments.view ou departments.manage
  - create: departments.create
  - update: departments.update ou departments.manage
  - delete: departments.manage
  - restore: departments.manage
  - forceDelete: super-admin apenas
  - managePeople: departments.manage_people
  - assignLeader: departments.assign_leader

### 5. Permissões
- [x] Adicionar permissões no AccessControlSeeder
  - departments.view
  - departments.create
  - departments.update
  - departments.manage
  - departments.manage_people
  - departments.assign_leader
- [x] Atualizar perfis de acesso
  - Secretaria: departments.view, departments.create, departments.update, departments.manage_people
  - Líder-departamento: departments.view, departments.manage_people

### 6. Seeder
- [x] Atualizar DepartmentSeeder
  - Usar updateOrCreate para evitar duplicidade
  - 14 departamentos raiz/oficiais:
    1. Pastoral (ministry)
    2. Resgatados (ministry)
    3. Louvor (worship)
    4. Mídia (support)
    5. Recepção (support)
    6. Obreiros (ministry)
    7. Jovens / Resgatados (youth)
    8. Infantil (children)
    9. Tesouraria (financial)
    10. Secretaria (administrative)
    11. Cantina (support)
    12. Evangelismo (evangelism)
    13. Intercessão (ministry)
    14. Ensino / Discipulado (ministry)
  - Todos os departamentos raiz marcados com is_system = true
  - Departamentos criados pela interface nascem is_system = false

### 7. Events
- [x] DepartmentCreated
- [x] DepartmentUpdated
- [x] DepartmentDeleted
- [x] DepartmentPersonAttached
- [x] DepartmentPersonUpdated
- [x] DepartmentPersonRemoved
- [x] DepartmentLeaderChanged

### 8. Listeners
- [x] LogDepartmentCreated
- [x] LogDepartmentUpdated
- [x] LogDepartmentDeleted
- [x] LogDepartmentPersonAttached
- [x] LogDepartmentPersonUpdated
- [x] LogDepartmentPersonRemoved
- [x] LogDepartmentLeaderChanged

### 9. Job
- [x] Criar Job CheckDepartmentsWithoutLeader
  - Verifica departamentos ativos sem líder
  - Gera SystemAlert se não houver alerta pendente equivalente
- [x] Adicionar job ao scheduler (routes/console.php)
  - Executa diariamente às 04:00

### 10. Controllers
- [x] DepartmentController
  - index: Lista departamentos com stats
  - create: Formulário de criação
  - store: Cria departamento, dispara DepartmentCreated
  - show: Detalhes do departamento com membros
  - edit: Formulário de edição
  - update: Atualiza departamento, dispara DepartmentUpdated e DepartmentLeaderChanged
  - destroy: Exclui departamento (soft delete), bloqueia departamento do sistema e com pessoas ativas, dispara DepartmentDeleted
- [x] DepartmentPersonController
  - store: Adiciona pessoa ao departamento, dispara DepartmentPersonAttached
  - update: Atualiza vínculo, dispara DepartmentPersonUpdated
  - destroy: Inativa vínculo, dispara DepartmentPersonRemoved

### 11. Form Requests
- [x] StoreDepartmentRequest
- [x] UpdateDepartmentRequest
- [x] StoreDepartmentPersonRequest
- [x] UpdateDepartmentPersonRequest

### 12. Rotas
- [x] Adicionar rotas em routes/web.php
  - /secretaria/departamentos (index)
  - /secretaria/departamentos/criar (create)
  - /secretaria/departamentos (store)
  - /secretaria/departamentos/{department} (show)
  - /secretaria/departamentos/{department}/editar (edit)
  - /secretaria/departamentos/{department} (update)
  - /secretaria/departamentos/{department} (destroy)
  - /secretaria/departamentos/{department}/pessoas (store department person)
  - /secretaria/departamentos/{department}/pessoas/{departmentPerson} (update department person)
  - /secretaria/departamentos/{department}/pessoas/{departmentPerson} (destroy department person)

### 13. Telas Vue
- [x] resources/js/Pages/Secretaria/Departments/Index.vue
  - Cards de resumo (total, ativos, sem líder, membros)
  - Tabela de departamentos
  - Botão Excluir com modal visual
  - Botão Excluir não aparece para departamentos do sistema
- [x] resources/js/Pages/Secretaria/Departments/Create.vue
  - Formulário de criação completo
  - Botão Cancelar usa Link do Inertia
- [x] resources/js/Pages/Secretaria/Departments/Edit.vue
  - Formulário de edição completo
  - Botão Cancelar usa Link do Inertia
- [x] resources/js/Pages/Secretaria/Departments/Show.vue
  - Cards de informações, liderança, reuniões
  - Tabela de membros
  - Botão Excluir com modal visual
  - Botão Excluir não aparece para departamentos do sistema

### 14. Menu
- [x] Atualizar menu da Secretaria em AuthenticatedLayout.vue
  - Adicionar link "Departamentos" no menu desktop
  - Adicionar link "Departamentos" no menu responsivo

### 15. Componentes
- [x] Criar componente modal reutilizável ConfirmActionModal.vue
  - Modal visual bonito e centralizado
  - Não usa window.confirm/alert nativo
  - Fundo escurecido
  - Card centralizado
  - Título claro
  - Texto explicativo
  - Botão Cancelar
  - Botão de ação (Excluir Departamento)
  - Visual limpo e moderno
  - Coerente com o sistema

### 16. Exclusão Segura
- [x] Departamento usa soft delete (SoftDeletes trait)
- [x] Coluna deleted_at existe na tabela departments
- [x] Controller bloqueia exclusão de departamento do sistema
- [x] Controller bloqueia exclusão se houver pessoas ativas vinculadas
- [x] Controller dispara evento DepartmentDeleted
- [x] Listener LogDepartmentDeleted registra ActivityLog
- [x] Frontend usa modal visual, não window.confirm
- [x] Frontend esconde botão Excluir para departamentos do sistema
- [x] Exclusão não apaga pessoas, usuários, membros ou member_profile

### 17. Documentação
- [x] Criar docs/DOCUMENTO_DEPARTAMENTOS_E_MINISTERIOS.md
- [x] Criar docs/CHECKLIST_DEPARTAMENTOS_E_MINISTERIOS.md
- [x] Atualizar CHECKLIST_PREPARACAO_WEB_MOBILE.md
- [x] Remover documentação duplicada da raiz

### 18. Testes
- [x] Criar testes de departamentos
  - [x] Teste de autorização (DepartmentPolicyTest)
  - [x] Teste de prevenção de duplicação (DepartmentTest)
  - [x] Teste de relacionamentos
- [x] Teste de alertas (verificado via Job)

### 19. Validação Final
- [x] Criar migrations
- [x] Criar seeders
- [x] Criar controllers
- [x] Criar form requests
- [x] Criar rotas
- [x] Criar telas Vue
- [x] Verificar events/listeners
- [x] Verificar job/scheduler
- [x] Verificar integração com SystemAlert
- [x] Criar EventServiceProvider
- [x] Registrar EventServiceProvider
- [x] Criar componente modal reutilizável
- [x] Remover window.confirm/alert do módulo
- [x] Implementar exclusão segura com modal visual
- [x] Organizar documentação em docs/
- [x] Executar testes
- [x] Executar build
- [x] Commit das mudanças

## Observações Importantes

### Regras de Negócio
1. **Vínculo em departamento ≠ Membro**: Participar de departamento NÃO cria membro automaticamente
2. **Vínculo em departamento ≠ Usuário**: Participar de departamento NÃO cria usuário automaticamente
3. **Liderança ≠ Permissão**: Liderar departamento NÃO cria permissão automaticamente sem controle
4. **Remover vínculo ≠ Apagar pessoa**: Remover vínculo NÃO apaga pessoa, usuário ou membro
5. **Excluir departamento usa soft delete**: Usa soft delete (deleted_at), não exclusão física
6. **Departamento do sistema não pode ser excluído**: Departamentos marcados como is_system = true são protegidos
7. **Departamento com pessoas ativas não pode ser excluído**: Bloqueia exclusão se houver vínculos ativos
8. **Excluir departamento não apaga dados**: Não apaga pessoas, usuários, membros ou member_profile
9. **Prevenção de duplicação**: Não permitir vínculo ativo duplicado da mesma pessoa no mesmo departamento
10. **Exclusão usa modal visual**: Não usa window.confirm/alert nativo do navegador
11. **Modal é reutilizável**: Componente ConfirmActionModal.vue pode ser reutilizado em outras partes do sistema

### Tipos de Departamento
- ministry: Ministério
- administrative: Administrativo
- youth: Jovens
- support: Apoio
- financial: Financeiro
- worship: Louvor
- children: Infantil
- evangelismo: Evangelismo
- other: Outro

### Status de Departamento
- active: Ativo
- inactive: Inativo
- archived: Arquivado

### Status de Vínculo (department_people)
- active: Ativo
- inactive: Inativo
- paused: Pausado
- removed: Removido

### Permissões
- departments.view: Visualizar departamentos
- departments.create: Criar departamento
- departments.update: Editar departamento
- departments.manage: Gerenciar departamentos (full)
- departments.manage_people: Gerenciar pessoas de departamento
- departments.assign_leader: Atribuir líder de departamento

### Exclusão Segura
- Usa soft delete (deleted_at)
- Bloqueia departamento do sistema (is_system = true)
- Bloqueia departamento com pessoas ativas (status = active E ends_at IS NULL)
- Dispara evento DepartmentDeleted
- Registra ActivityLog através de LogDepartmentDeleted
- Usa modal visual no frontend
- Não apaga pessoas, usuários, membros ou member_profile

## Resumo da Implementação

### Arquivos Criados/Atualizados
- **Migrations**: 2 arquivos de ajuste (alter_departments_table_add_fields, alter_department_people_table_add_fields)
- **Models**: 3 arquivos atualizados (Department, DepartmentPerson, Person)
- **Policy**: 1 arquivo atualizado (DepartmentPolicy)
- **Seeders**: 1 arquivo atualizado (AccessControlSeeder, DepartmentSeeder)
- **Events**: 7 arquivos criados (DepartmentCreated, DepartmentUpdated, DepartmentDeleted, DepartmentPersonAttached, DepartmentPersonUpdated, DepartmentPersonRemoved, DepartmentLeaderChanged)
- **Listeners**: 7 arquivos criados (LogDepartmentCreated, LogDepartmentUpdated, LogDepartmentDeleted, LogDepartmentPersonAttached, LogDepartmentPersonUpdated, LogDepartmentPersonRemoved, LogDepartmentLeaderChanged)
- **Jobs**: 1 arquivo criado (CheckDepartmentsWithoutLeader)
- **Scheduler**: 1 arquivo atualizado (routes/console.php)
- **Controllers**: 2 arquivos criados (DepartmentController, DepartmentPersonController)
- **Form Requests**: 4 arquivos criados (StoreDepartmentRequest, UpdateDepartmentRequest, StoreDepartmentPersonRequest, UpdateDepartmentPersonRequest)
- **Rotas**: 1 arquivo atualizado (routes/web.php)
- **Telas Vue**: 4 arquivos criados (Index.vue, Create.vue, Edit.vue, Show.vue)
- **Componentes**: 1 arquivo criado (ConfirmActionModal.vue)
- **Menu**: 1 arquivo atualizado (AuthenticatedLayout.vue)
- **Providers**: 2 arquivos criados/atualizados (EventServiceProvider, bootstrap/providers.php)
- **Testes**: 2 arquivos criados (DepartmentPolicyTest, DepartmentTest)
- **Documentação**: 3 arquivos criados/atualizados (docs/DOCUMENTO_DEPARTAMENTOS_E_MINISTERIOS.md, docs/CHECKLIST_DEPARTAMENTOS_E_MINISTERIOS.md, CHECKLIST_PREPARACAO_WEB_MOBILE.md)

### Total: 39 arquivos criados/atualizados

### Observações Finais
- Todos os arquivos foram criados seguindo os padrões do projeto
- Comentários didáticos foram adicionados em todos os arquivos
- Regras de negócio estritas foram respeitadas
- Integração com SystemAlert foi implementada via Job
- Prevenção de duplicação foi implementada no controller
- Menu da Secretaria foi atualizado para incluir Departamentos
- Exclusão segura foi implementada com soft delete
- Modal visual substituiu window.confirm/alert nativo
- Documentação foi organizada em docs/ conforme padrão oficial
- Sem duplicidade de arquivos
- Sem criação automática de membro
- Sem criação automática de member_profile
- Mobile não iniciado
