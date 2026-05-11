# Documento Acessos da Secretaria

## Visão Geral

Este documento descreve o módulo de Gestão de Acessos ao Sistema da Secretaria, criado na Etapa 7 do projeto Ministério Resgate / Família Resgate.

## Objetivo

Permitir que a Secretaria gerencie quem pode ter acesso ao sistema, diferenciando claramente entre:
- **Pessoa**: registro cadastral
- **Usuário**: login no sistema
- **Membro**: pessoa batizada

## Diferenças Fundamentais

### Pessoa
- Registro cadastral no sistema
- Pode ou não ter usuário
- Pode ou não ser membro
- Não significa que tem acesso ao sistema

### Usuário
- Login no sistema
- Sempre vinculado a uma pessoa por `person_id`
- Não significa que a pessoa é membro
- Pode ser criado manualmente pela Secretaria

### Membro
- Somente pessoas batizadas podem ser membros
- Deve ter `member_profile`
- Não é criado automaticamente ao criar usuário
- Não é criado automaticamente ao cadastrar pessoa

## Regras por Idade

### Menor de 11 anos
- **Não pode ter usuário próprio**
- Tudo passa pelos pais/responsáveis
- Mensagem de bloqueio: "Crianças menores de 11 anos não podem ter usuário próprio. O acesso deve ser feito pelos responsáveis."

### Júnior (11 até antes de 14 anos)
- Pode ter usuário supervisionado
- Precisa ter responsável ativo em `guardianships`
- Idealmente precisa de responsável com `can_authorize_login = true`
- Mensagem de bloqueio: "Júniores podem ter usuário supervisionado, mas precisam de responsável ativo autorizado."
- Mensagem de permissão: "Permitido com supervisão: usuário será vinculado a responsável."

### Jovem (14 até antes de 18 anos)
- Pode ter usuário
- Pode continuar tendo responsável, mas não precisa da mesma supervisão obrigatória do Júnior
- Só será membro se for batizado
- Mensagem: "Permitido: jovem pode ter usuário. Membro somente se batizado."

### Adulto (18 anos ou mais)
- Pode ter usuário
- Só será membro se for batizado
- Mensagem: "Permitido: adulto pode ter usuário. Membro somente se batizado."

### Sem data de nascimento
- Não criar usuário automaticamente
- Exigir revisão da Secretaria
- Mensagem: "Informe a data de nascimento antes de criar acesso, para validar as regras de idade."

## Banco de Dados

### Tabela `users`

Campos existentes:
- `id`
- `name`
- `email`
- `email_verified_at`
- `password`
- `remember_token`
- `person_id` (nullable, FK para `people`) - adicionado em migration anterior
- `last_login_at` (nullable) - adicionado em migration anterior
- `status` (enum: 'active', 'inactive', 'suspended', default 'active') - adicionado em migration anterior

Campos adicionados na Etapa 7:
- `must_change_password` (boolean, default false)
- `access_granted_at` (nullable timestamp)
- `access_revoked_at` (nullable timestamp)
- `access_revoked_reason` (nullable string)
- `access_notes` (nullable text)

## Model User

### Relacionamentos
- `person()`: belongsTo(Person::class) - Um usuário pertence a uma pessoa

### Métodos
- `isActive()`: Verifica se o usuário está ativo (status === 'active')
- `isSuspended()`: Verifica se o usuário está suspenso (status === 'suspended')
- `requiresPasswordChange()`: Verifica se o usuário precisa trocar senha (must_change_password)
- `hasPerson()`: Verifica se o usuário está vinculado a uma pessoa

## Model Person

### Relacionamentos
- `user()`: hasOne(User::class) - Uma pessoa pode ter um usuário

### Métodos existentes
- `getAgeAttribute()`: Calcula a idade atual
- `isUnder11YearsOld()`: Verifica se é menor de 11 anos
- `isJunior()`: Verifica se está na faixa de Júnior (11-13 anos)
- `isYoung()`: Verifica se está na faixa de Jovem (14-17 anos)
- `isAdult()`: Verifica se é adulto (18+ anos)
- `ageGroupLabel()`: Retorna rótulo da categoria de idade
- `canHaveUser()`: Verifica se a pessoa pode ter usuário
- `canBeMember()`: Verifica se a pessoa pode ser membro (é batizada)

### Métodos adicionados na Etapa 7
- `requiresGuardianForUser()`: Verifica se precisa de responsável para ter usuário
- `hasActiveGuardianAuthorizedForLogin()`: Verifica se tem responsável ativo autorizado para login
- `age()`: Retorna a idade (método auxiliar)
- `ageGroup()`: Retorna o grupo de idade (método auxiliar)

## Service: UserAccessEligibilityService

### Localização
`app/Services/Secretaria/UserAccessEligibilityService.php`

### Método: `check(Person $person): array`

Retorna array com:
```php
[
    'allowed' => true/false,
    'reason' => 'mensagem clara',
    'age' => 12,
    'age_group' => 'Júnior',
    'requires_guardian' => true/false,
    'has_authorized_guardian' => true/false,
]
```

### Lógica de Validação
1. Se não tiver `birth_date`: `allowed = false`
2. Se menor de 11 anos: `allowed = false`
3. Se Júnior sem responsável ativo/autorizado: `allowed = false`
4. Se Júnior com responsável autorizado: `allowed = true`
5. Se Jovem: `allowed = true`
6. Se Adulto: `allowed = true`

### Método: `hasActiveUser(Person $person): bool`

Verifica se a pessoa já possui usuário ativo.

## Controller: SecretaryUserAccessController

### Localização
`app/Http/Controllers/SecretaryUserAccessController.php`

### Métodos

#### `index()`
Lista todos os usuários do sistema com paginação.
Retorna estatísticas:
- active: Usuários ativos
- suspended: Usuários suspensos
- people_without_user: Pessoas sem usuário
- juniors_with_access: Júniores com acesso
- pending_password_change: Acessos aguardando troca de senha

#### `create()`
Mostra formulário para criar novo usuário.

#### `store(Request $request)`
Cria novo usuário.
Validações:
- `person_id`: required, exists em people
- `name`: required, max 255
- `email`: required, email, unique em users
- `password`: nullable, min 8 (se vazio, gera senha temporária)
- `access_notes`: nullable, string

Lógica:
1. Verifica se pessoa já tem usuário ativo
2. Verifica elegibilidade usando `UserAccessEligibilityService`
3. Gera senha temporária se não informada
4. Cria usuário com `must_change_password = true`
5. Retorna para tela de detalhes com senha temporária em flash message

#### `show(User $user)`
Mostra detalhes de um usuário.
Carrega:
- Dados do usuário
- Dados da pessoa vinculada
- Idade/fase
- Status do acesso
- `must_change_password`
- `access_granted_at`
- `access_revoked_at`
- Motivo da suspensão
- Observações
- Responsáveis ativos (se for Júnior)
- Alerta de violação de regra (se menor de 11 anos)

#### `edit(User $user)`
Mostra formulário para editar usuário.
Não permite editar `person_id` livremente.

#### `update(Request $request, User $user)`
Atualiza usuário.
Validações:
- `name`: required, max 255
- `email`: required, email, unique ignorando o usuário atual
- `access_notes`: nullable, string

#### `suspend(Request $request, User $user)`
Suspende acesso de usuário.
Validações:
- `access_revoked_reason`: required, max 500

Lógica:
- Define `status = 'suspended'`
- Define `access_revoked_at = now()`
- Define `access_revoked_reason`

#### `reactivate(Request $request, User $user)`
Reativa acesso de usuário.
Validações:
- `access_notes`: nullable

Lógica:
- Revalida elegibilidade se usuário for de menor
- Define `status = 'active'`
- Limpa `access_revoked_at` e `access_revoked_reason`
- Define `access_granted_at = now()`

#### `eligibility(Person $person)`
Endpoint JSON para verificar elegibilidade de uma pessoa.
Retorna elegibilidade e se já tem usuário ativo.

## Requests de Validação

### StoreSecretaryUserAccessRequest
Regras:
- `person_id`: required, exists:people
- `name`: required, string, max:255
- `email`: required, email, unique:users,email
- `password`: nullable, string, min:8
- `access_notes`: nullable, string

### UpdateSecretaryUserAccessRequest
Regras:
- `name`: required, string, max:255
- `email`: required, email, unique:users,email,{user}
- `access_notes`: nullable, string

## Rotas

Todas as rotas estão protegidas por `auth` middleware.

```
GET /secretaria/acessos/elegibilidade/{person}
name: secretaria.access.eligibility

GET /secretaria/acessos
name: secretaria.access.index

GET /secretaria/acessos/criar
name: secretaria.access.create

POST /secretaria/acessos
name: secretaria.access.store

GET /secretaria/acessos/{user}
name: secretaria.access.show

GET /secretaria/acessos/{user}/editar
name: secretaria.access.edit

PUT /secretaria/acessos/{user}
name: secretaria.access.update

PATCH /secretaria/acessos/{user}/suspender
name: secretaria.access.suspend

PATCH /secretaria/acessos/{user}/reativar
name: secretaria.access.reactivate
```

**Importante**: A rota de elegibilidade vem antes da rota dinâmica `{user}` para não conflitar.

## Páginas Vue

### Index.vue
Tela: "Acessos do Sistema"
Descrição: "Gerencie quem pode entrar no sistema sem confundir pessoa, usuário e membro."

Cards:
- Usuários ativos
- Usuários suspensos
- Pessoas sem usuário
- Júniores com acesso
- Acessos aguardando troca de senha

Lista:
- Nome do usuário
- Email
- Pessoa vinculada
- Idade/fase da pessoa
- Status
- Troca de senha pendente
- Criado em
- Ações: ver, editar, suspender/reativar

### Create.vue
Formulário para criar usuário.

Campos:
1. Pessoa vinculada (autocomplete por nome)
2. Nome do usuário (preenchido automaticamente)
3. Email (preenchido automaticamente)
4. Senha temporária (gerar automaticamente ou informar manualmente)
5. Observações de acesso

Painel de elegibilidade:
- Mostra ao selecionar pessoa
- Bloqueia botão criar se não elegível

### Show.vue
Mostra detalhes do usuário.

Seções:
- Dados do usuário (nome, email, status, troca de senha pendente, datas)
- Pessoa vinculada (nome, email, idade, grupo, batizado, pode ser membro)
- Responsáveis ativos (se for Júnior)
- Alerta de violação de regra (se menor de 11 anos)
- Ações (editar, suspender, reativar)

Modais:
- Suspender: exige motivo
- Reativar: observação opcional

### Edit.vue
Formulário para editar usuário.

Campos editáveis:
- Nome
- Email
- Observações

Campo não editável:
- Pessoa vinculada (apenas leitura para manter integridade)

## Menu

Adicionado link "Acessos" ao menu autenticado em `AuthenticatedLayout.vue`.

## Dashboard

Atualizado `SecretaryDashboardController` e `Dashboard.vue` para incluir cards de estatísticas:
- Usuários ativos
- Usuários suspensos
- Pessoas sem usuário
- Júniores com acesso supervisionado

## Segurança

### Proteção de Rotas
- Todas as rotas de acesso estão protegidas por `auth` middleware
- Rotas de busca de pessoas e usuários também estão protegidas

### Senha
- Senha temporária gerada automaticamente com `Str::random(16)`
- Senha sempre armazenada com `Hash::make`
- Senha temporária mostrada apenas no momento de criação (flash message)
- `must_change_password = true` para forçar troca no primeiro acesso

### Validação de Elegibilidade
- Menor de 11 anos: bloqueado
- Sem data de nascimento: bloqueado
- Júnior sem responsável autorizado: bloqueado
- Pessoa já tem usuário ativo: bloqueado

### Não Criação Automática
- Não cria usuário automaticamente ao cadastrar pessoa
- Não cria membro automaticamente ao criar usuário
- Não cria member_profile automaticamente

## Integrações

### Com Solicitações
Futuramente pode haver solicitação para criação de acesso.
Nesta etapa: não cria fluxo automático, apenas documentação.

### Com Alertas
Ideias futuras:
- Usuário criado para menor de 11 anos
- Júnior com acesso sem responsável
- Usuário sem pessoa vinculada
Nesta etapa: apenas documentação.

## Validação

Comandos a rodar:
```bash
php artisan optimize:clear
php artisan route:list --path=secretaria/acessos
php artisan route:list --path=people
php artisan route:list --path=users
php artisan route:list --path=secretaria
php artisan test
npm run build
```

Verificar duplicidades:
```bash
find app resources database routes -iname "*Novo*" -o -iname "*Final*" -o -iname "*Corrigido*" -o -iname "*V2*" -o -iname "*Backup*" -o -iname "*Copy*"
```

## Teste Manual no Navegador

### Criar usuário para adulto
1. Acessar `/secretaria/acessos/criar`
2. Selecionar pessoa adulta
3. Confirmar elegibilidade permitida
4. Preencher dados
5. Criar usuário
6. Confirmar que senha temporária aparece
7. Confirmar que usuário foi criado

### Tentar criar usuário para criança menor de 11 anos
1. Acessar `/secretaria/acessos/criar`
2. Selecionar pessoa menor de 11 anos
3. Confirmar que bloqueia com mensagem clara
4. Botão criar deve estar desativado

### Tentar criar usuário para Júnior sem responsável autorizado
1. Acessar `/secretaria/acessos/criar`
2. Selecionar Júnior sem responsável ativo/autorizado
3. Confirmar que bloqueia com mensagem clara
4. Botão criar deve estar desativado

### Criar usuário para Júnior com responsável autorizado
1. Acessar `/secretaria/acessos/criar`
2. Selecionar Júnior com responsável ativo autorizado
3. Confirmar que permite
4. Criar usuário
5. Confirmar que não cria membro automaticamente

### Suspender acesso
1. Acessar detalhes de usuário ativo
2. Clicar em Suspender
3. Informar motivo
4. Confirmar suspensão

### Reativar acesso
1. Acessar detalhes de usuário suspenso
2. Clicar em Reativar
3. Confirmar reativação

### Editar usuário
1. Acessar edição de usuário
2. Alterar nome/email
3. Salvar
4. Confirmar alterações

### Ver dashboard
1. Acessar `/secretaria`
2. Confirmar cards de acesso atualizados

## Regras Importantes

### Não Criação Automática
- Não cria usuário automaticamente ao cadastrar pessoa
- Não cria membro automaticamente ao criar usuário
- Não altera member_profiles automaticamente

### Não Duplicidade
- Não permite criar usuário para pessoa que já possui usuário ativo
- Mensagem: "Esta pessoa já possui usuário vinculado."

### Não Exposição de Dados
- Rotas de busca protegidas por auth
- Senha não mostrada em detalhes
- Senha temporária mostrada apenas no momento de criação

## Conclusão

Este módulo permite que a Secretaria gerencie acessos ao sistema de forma segura e controlada, respeitando as regras de idade e responsáveis, sem confundir pessoa, usuário e membro.
