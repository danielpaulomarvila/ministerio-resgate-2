# Etapa 8: Permissões e Perfis de Acesso

## Visão Geral
Esta etapa implementa um sistema customizado de controle de acesso baseado em perfis e permissões, sem uso de pacotes externos como Spatie Permission.

## Estrutura do Sistema

### Tabelas do Banco de Dados

#### `access_profiles`
Define os perfis de acesso do sistema (ex: Secretaria, Tesouraria, Membro).

Campos:
- `id` (PK)
- `uuid` (unique)
- `name` (nome do perfil)
- `slug` (identificador único)
- `description` (descrição opcional)
- `is_system` (perfil do sistema não pode ser deletado)
- `is_active` (status do perfil)
- `sort_order` (ordem de exibição)
- `timestamps`

#### `permissions`
Define as permissões granulares do sistema (ex: people.view, accesses.suspend).

Campos:
- `id` (PK)
- `uuid` (unique)
- `name` (nome da permissão)
- `slug` (identificador único)
- `group` (grupo para organização)
- `description` (descrição opcional)
- `is_system` (permissão do sistema não pode ser deletada)
- `is_active` (status da permissão)
- `sort_order` (ordem de exibição)
- `timestamps`

#### `access_profile_permission` (Pivot)
Vincula perfis a permissões (many-to-many).

Campos:
- `id` (PK)
- `access_profile_id` (FK)
- `permission_id` (FK)
- `timestamps`
- Unique: (access_profile_id, permission_id)

#### `access_profile_user` (Pivot)
Vincula usuários a perfis (many-to-many) com rastreio.

Campos:
- `id` (PK)
- `user_id` (FK)
- `access_profile_id` (FK)
- `assigned_by_user_id` (FK - quem atribuiu)
- `assigned_at` (quando foi atribuído)
- `notes` (notas sobre a atribuição)
- `timestamps`
- Unique: (user_id, access_profile_id)

## Models

### `App\Models\AccessProfile`
- Relacionamento `permissions()` (BelongsToMany)
- Relacionamento `users()` (BelongsToMany)
- Método `hasPermission($slug)` - verifica se perfil tem permissão
- Método `isSystemProfile()` - verifica se é perfil do sistema
- Escopos: `active()`, `system()`, `custom()`

### `App\Models\Permission`
- Relacionamento `accessProfiles()` (BelongsToMany)
- Método `isSystemPermission()` - verifica se é permissão do sistema
- Escopos: `active()`, `byGroup()`, `ordered()`, `system()`, `custom()`

### `App\Models\User` (Atualizado)
- Relacionamento `accessProfiles()` (BelongsToMany)
- Método `permissions()` - obtém todas as permissões através dos perfis
- Método `hasAccessProfile($slug)` - verifica se usuário tem perfil
- Método `hasPermission($slug)` - verifica se usuário tem permissão (super-admin tem todas)
- Método `isSuperAdmin()` - verifica se usuário é super-admin

## Controllers

### `App\Http\Controllers\Secretaria\AccessProfileController`
CRUD completo para gerenciar perfis de acesso:
- `index()` - lista todos os perfis com stats
- `create()` - formulário para criar perfil
- `store()` - armazena novo perfil
- `show()` - mostra detalhes do perfil
- `edit()` - formulário para editar perfil
- `update()` - atualiza perfil
- `destroy()` - desativa perfil (não deleta perfis do sistema)

### `App\Http\Controllers\Secretaria\UserAccessProfileController`
Gerencia perfis de usuários:
- `edit()` - formulário para editar perfis de um usuário
- `update()` - atualiza perfis de um usuário

## Middleware

### `App\Http\Middleware\EnsureUserHasPermission`
Verifica se o usuário tem uma permissão específica.
- Uso: `->middleware('permission:people.view')`
- Super-admins têm todas as permissões automaticamente
- Retorna 403 se não tiver permissão

## Comando Artisan

### `access:grant-super-admin {email}`
Concede acesso de Super Administrador a um usuário pelo email.
- Uso: `php artisan access:grant-super-admin daniel.teste@example.com`
- Não duplica se usuário já tiver o perfil
- Registra que foi atribuído pelo sistema

## Rotas

### Perfis de Acesso
- `GET /secretaria/perfis-acesso` - `secretaria.access-profiles.index`
- `GET /secretaria/perfis-acesso/criar` - `secretaria.access-profiles.create`
- `POST /secretaria/perfis-acesso` - `secretaria.access-profiles.store`
- `GET /secretaria/perfis-acesso/{accessProfile}` - `secretaria.access-profiles.show`
- `GET /secretaria/perfis-acesso/{accessProfile}/editar` - `secretaria.access-profiles.edit`
- `PUT /secretaria/perfis-acesso/{accessProfile}` - `secretaria.access-profiles.update`
- `DELETE /secretaria/perfis-acesso/{accessProfile}` - `secretaria.access-profiles.destroy`

### Perfis de Usuário
- `GET /secretaria/acessos/{user}/perfis` - `secretaria.acessos.perfis.edit`
- `PUT /secretaria/acessos/{user}/perfis` - `secretaria.acessos.perfis.update`

## Vue Pages

### `resources/js/Pages/Secretaria/AccessProfiles/Index.vue`
Listagem de todos os perfis com cards de resumo (total, ativos, sistema, personalizados).

### `resources/js/Pages/Secretaria/AccessProfiles/Create.vue`
Formulário para criar novo perfil com seleção de permissões por grupo.

### `resources/js/Pages/Secretaria/AccessProfiles/Edit.vue`
Formulário para editar perfil existente com seleção de permissões.

### `resources/js/Pages/Secretaria/AccessProfiles/Show.vue`
Detalhes do perfil com lista de permissões agrupadas e usuários com o perfil.

### `resources/js/Pages/Secretaria/Access/UserProfiles.vue`
Gerencia perfis de um usuário específico com checkboxes para selecionar perfis.

## Integração com Módulo de Acessos

### Botão "Perfis" na Listagem
Adicionado em `resources/js/Pages/Secretaria/Access/Index.vue` na tabela de ações.

### Botão "Gerenciar Perfis" na Tela de Detalhes
Adicionado em `resources/js/Pages/Secretaria/Access/Show.vue` na seção de ações.

### Menu "Perfis de Acesso"
Adicionado em `resources/js/Layouts/AuthenticatedLayout.vue` após o menu "Acessos".

## Perfil Super-Admin

O perfil `super-admin` tem todas as permissões do sistema. Usuários com este perfil:
- Têm acesso irrestrito ao sistema
- Não são bloqueados pelo middleware de permissões
- Podem gerenciar todos os outros perfis e permissões

## Como Usar

### 1. Conceder Super-Admin a um Usuário
```bash
php artisan access:grant-super-admin email@exemplo.com
```

### 2. Criar um Novo Perfil
1. Acesse "Perfis de Acesso" no menu
2. Clique em "Criar Perfil"
3. Preencha nome, slug, descrição
4. Selecione as permissões desejadas
5. Clique em "Criar Perfil"

### 3. Atribuir Perfis a um Usuário
1. Vá para "Acessos" no menu
2. Clique em "Perfis" na listagem ou "Gerenciar Perfis" nos detalhes
3. Selecione os perfis desejados
4. Clique em "Salvar Perfis"

### 4. Proteger uma Rota com Middleware
```php
Route::middleware(['auth', 'permission:people.view'])->group(function () {
    // rotas protegidas
});
```

## Perfil Super-Admin

O perfil `super-admin` tem todas as permissões do sistema. Usuários com este perfil:
- Têm acesso irrestrito ao sistema
- Não são bloqueados pelo middleware de permissões
- Podem gerenciar todos os outros perfis e permissões

## Perfis Iniciais

O seeder `AccessControlSeeder` cria os seguintes perfis iniciais:

1. **Super Administrador** - Todas as permissões
2. **Pastor/Administração** - Quase todas as permissões (exceto gerenciar permissões)
3. **Secretaria** - Cadastros, alertas, solicitações, acessos
4. **Tesouraria** - Visualização de cadastros e financeiro
5. **Líder de Departamento** - Visualização de pessoas e departamentos
6. **Membro** - Área de membros
7. **Responsável Familiar** - Área familiar, filhos, solicitações
8. **Jovem** - Áreas de membros e jovens
9. **Júnior Supervisionado** - Acesso limitado às áreas de membros e jovens

## Permissões Iniciais

O seeder cria permissões organizadas em grupos:
- **secretaria**: secretaria.view
- **pessoas**: people.view, people.create, people.update, people.delete
- **familias**: families.view, families.create, families.update
- **responsaveis**: guardianships.view, guardianships.manage
- **alertas**: alerts.view, alerts.manage
- **solicitacoes**: requests.view, requests.manage
- **acessos**: accesses.view, accesses.create, accesses.update, accesses.suspend, accesses.reactivate
- **sistema**: permissions.view, permissions.manage
- **membros**: member.area.view
- **jovens**: youth.area.view, youth.area.view_limited
- **familia**: family.area.view, family.children.view, family.requests.create
- **departamentos**: departments.view, departments.manage_basic
- **financeiro**: financeiro.view, financeiro.manage

## Regras Importantes

1. **Perfis do Sistema** (`is_system = true`) não podem ser deletados, apenas desativados
2. **Permissões do Sistema** (`is_system = true`) não podem ser deletadas
3. **Super-admin** tem todas as permissões automaticamente
4. **Validação** é feita inline nos controllers (sem Form Requests separados)
5. **Rastreio** de atribuição de perfis é feito na tabela pivot `access_profile_user`

## Testes Realizados

- Migrations executadas com sucesso
- Seeder executado com sucesso
- Perfil super-admin concedido ao usuário atual (daniel.teste@example.com)
- Sistema não bloqueou o usuário atual
- Rotas criadas e funcionando
- Vue pages criadas
- Integração com módulo de acessos realizada

## Próximos Passos

1. Aplicar middleware de permissões às rotas existentes de forma gradual
2. Criar testes unitários para models e controllers
3. Implementar logging de mudanças de perfil
4. Considerar criação de perfis personalizados por usuários com permissão `permissions.manage`
