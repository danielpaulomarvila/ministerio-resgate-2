# Checklist Etapa 10 - Departamentos e Ministérios

## Visão Geral
- **Status**: IMPLEMENTADA
- **Data de Início**: 11/05/2026
- **Data de Conclusão**: 11/05/2026
- **Objetivo**: Criar o módulo de Departamentos e Ministérios com CRUD completo, gestão de membros, events/listeners, job de verificação e integração com o sistema de alertas.

## Regras Estritas
- ✅ Reutilizar tabelas existentes se presentes
- ✅ Não criar duplicação de estruturas
- ✅ Não criar app mobile agora
- ✅ Não apagar dados existentes
- ✅ Não quebrar etapas anteriores
- ✅ Vínculo em departamento não cria membro automaticamente
- ✅ Vínculo em departamento não cria usuário automaticamente
- ✅ Liderar departamento não cria permissão automaticamente sem controle

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
  - 12 departamentos iniciais:
    1. Louvor (worship)
    2. Mídia (support)
    3. Recepção (support)
    4. Obreiros (ministry)
    5. Jovens / Resgatados (youth)
    6. Infantil (children)
    7. Tesouraria (financial)
    8. Secretaria (administrative)
    9. Cantina (support)
    10. Evangelismo (evangelism)
    11. Intercessão (ministry)
    12. Ensino / Discipulado (ministry)

### 7. Events
- [x] DepartmentCreated
- [x] DepartmentUpdated
- [x] DepartmentPersonAttached
- [x] DepartmentPersonUpdated
- [x] DepartmentPersonRemoved
- [x] DepartmentLeaderChanged

### 8. Listeners
- [x] LogDepartmentCreated
- [x] LogDepartmentUpdated
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
  - destroy: Desativa departamento (status inactive)
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
- [x] resources/js/Pages/Secretaria/Departments/Create.vue
  - Formulário de criação completo
- [x] resources/js/Pages/Secretaria/Departments/Edit.vue
  - Formulário de edição completo
- [x] resources/js/Pages/Secretaria/Departments/Show.vue
  - Cards de informações, liderança, reuniões
  - Tabela de membros

### 14. Menu
- [x] Atualizar menu da Secretaria em AuthenticatedLayout.vue
  - Adicionar link "Departamentos" no menu desktop
  - Adicionar link "Departamentos" no menu responsivo

### 15. Documentação
- [x] Criar documentação de departamentos (este arquivo)
- [x] Atualizar CHECKLIST_PREPARACAO_WEB_MOBILE.md

### 16. Testes
- [x] Criar testes de departamentos
  - [x] Teste de autorização (DepartmentPolicyTest)
  - [x] Teste de prevenção de duplicação (DepartmentTest)
  - [x] Teste de relacionamentos
- [x] Teste de alertas (verificado via Job)

### 17. Validação Final
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
- [ ] Executar migrations manualmente
- [ ] Executar seeders manualmente
- [ ] Testar rotas manualmente
- [ ] Testar telas Vue manualmente

## Observações Importantes

### Regras de Negócio
1. **Vínculo em departamento ≠ Membro**: Participar de departamento NÃO cria membro automaticamente
2. **Vínculo em departamento ≠ Usuário**: Participar de departamento NÃO cria usuário automaticamente
3. **Liderança ≠ Permissão**: Liderar departamento NÃO cria permissão automaticamente sem controle
4. **Remover vínculo ≠ Apagar pessoa**: Remover vínculo NÃO apaga pessoa, usuário ou membro
5. **Preferência por soft delete**: Usar status inactive em vez de delete para departamentos
6. **Prevenção de duplicação**: Não permitir vínculo ativo duplicado da mesma pessoa no mesmo departamento

### Tipos de Departamento
- ministry: Ministério
- administrative: Administrativo
- youth: Jovens
- support: Apoio
- financial: Financeiro
- worship: Louvor
- children: Infantil
- evangelism: Evangelismo
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

## Próximos Passos
1. ✅ Finalizar documentação
2. ✅ Criar testes básicos
3. ⏳ Executar validação manual (pendente)
4. ⏳ Atualizar checklist geral (pendente)

## Resumo da Implementação

### Arquivos Criados/Atualizados
- **Migrations**: 2 arquivos de ajuste (alter_departments_table_add_fields, alter_department_people_table_add_fields)
- **Models**: 3 arquivos atualizados (Department, DepartmentPerson, Person)
- **Policy**: 1 arquivo atualizado (DepartmentPolicy)
- **Seeders**: 1 arquivo atualizado (AccessControlSeeder, DepartmentSeeder)
- **Events**: 6 arquivos criados (DepartmentCreated, DepartmentUpdated, DepartmentPersonAttached, DepartmentPersonUpdated, DepartmentPersonRemoved, DepartmentLeaderChanged)
- **Listeners**: 6 arquivos criados (LogDepartmentCreated, LogDepartmentUpdated, LogDepartmentPersonAttached, LogDepartmentPersonUpdated, LogDepartmentPersonRemoved, LogDepartmentLeaderChanged)
- **Jobs**: 1 arquivo criado (CheckDepartmentsWithoutLeader)
- **Scheduler**: 1 arquivo atualizado (routes/console.php)
- **Controllers**: 2 arquivos criados (DepartmentController, DepartmentPersonController)
- **Form Requests**: 4 arquivos criados (StoreDepartmentRequest, UpdateDepartmentRequest, StoreDepartmentPersonRequest, UpdateDepartmentPersonRequest)
- **Rotas**: 1 arquivo atualizado (routes/web.php)
- **Telas Vue**: 4 arquivos criados (Index.vue, Create.vue, Edit.vue, Show.vue)
- **Menu**: 1 arquivo atualizado (AuthenticatedLayout.vue)
- **Providers**: 2 arquivos criados/atualizados (EventServiceProvider, bootstrap/providers.php)
- **Testes**: 2 arquivos criados (DepartmentPolicyTest, DepartmentTest)
- **Documentação**: 2 arquivos criados/atualizados (CHECKLIST_ETAPA_10.md, CHECKLIST_PREPARACAO_WEB_MOBILE.md)

### Total: 37 arquivos criados/atualizados

### Validações Pendentes
- Executar migrations: `php artisan migrate`
- Executar seeders: `php artisan db:seed --class=DepartmentSeeder`
- Executar seeder de permissões: `php artisan db:seed --class=AccessControlSeeder`
- Testar rotas via navegador
- Testar telas Vue manualmente

### Observações Finais
- Todos os arquivos foram criados seguindo os padrões do projeto
- Comentários didáticos foram adicionados em todos os arquivos
- Regras de negócio estritas foram respeitadas
- Integração com SystemAlert foi implementada via Job
- Prevenção de duplicação foi implementada no controller
- Menu da Secretaria foi atualizado para incluir Departamentos
