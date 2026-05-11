# Checklist Acessos da Secretaria - Etapa 7

## Banco de Dados

- [ ] Migration `alter_users_table_add_access_fields` criada
- [ ] Migration contém `must_change_password` (boolean, default false)
- [ ] Migration contém `access_granted_at` (nullable timestamp)
- [ ] Migration contém `access_revoked_at` (nullable timestamp)
- [ ] Migration contém `access_revoked_reason` (nullable string)
- [ ] Migration contém `access_notes` (nullable text)
- [ ] Migration contém índices para os novos campos
- [ ] Migration `down()` remove índices e colunas corretamente

## Model User

- [ ] Fillable inclui novos campos de acesso
- [ ] Casts inclui `must_change_password` como boolean
- [ ] Casts inclui `access_granted_at` como datetime
- [ ] Casts inclui `access_revoked_at` como datetime
- [ ] Método `isSuspended()` implementado
- [ ] Método `requiresPasswordChange()` implementado
- [ ] Método `hasPerson()` implementado
- [ ] Relacionamento `person()` existe

## Model Person

- [ ] Método `requiresGuardianForUser()` implementado
- [ ] Método `hasActiveGuardianAuthorizedForLogin()` implementado
- [ ] Método `age()` implementado (auxiliar)
- [ ] Método `ageGroup()` implementado (auxiliar)
- [ ] Relacionamento `user()` existe

## Service: UserAccessEligibilityService

- [ ] Arquivo criado em `app/Services/Secretaria/UserAccessEligibilityService.php`
- [ ] Método `check()` implementado
- [ ] Valida data de nascimento ausente
- [ ] Valida menor de 11 anos
- [ ] Valida Júnior sem responsável autorizado
- [ ] Valida Júnior com responsável autorizado
- [ ] Valida Jovem (14-17)
- [ ] Valida Adulto (18+)
- [ ] Retorna array com estrutura correta
- [ ] Método `hasActiveUser()` implementado

## Controller: SecretaryUserAccessController

- [ ] Arquivo criado em `app/Http/Controllers/SecretaryUserAccessController.php`
- [ ] Injeta UserAccessEligibilityService no construtor
- [ ] Método `index()` retorna usuários com paginação
- [ ] Método `index()` retorna estatísticas corretas
- [ ] Método `create()` renderiza tela de criação
- [ ] Método `store()` valida pessoa já tem usuário
- [ ] Método `store()` valida elegibilidade
- [ ] Método `store()` gera senha temporária se não informada
- [ ] Método `store()` define `must_change_password = true`
- [ ] Método `store()` define `access_granted_at`
- [ ] Método `show()` carrega dados do usuário e pessoa
- [ ] Método `show()` verifica violação de idade
- [ ] Método `show()` carrega responsáveis ativos se for Júnior
- [ ] Método `edit()` renderiza tela de edição
- [ ] Método `update()` atualiza dados permitidos
- [ ] Método `suspend()` exige motivo
- [ ] Método `suspend()` define status, datas e motivo
- [ ] Método `reactivate()` revalida elegibilidade
- [ ] Método `reactivate()` limpa dados de suspensão
- [ ] Método `eligibility()` retorna JSON

## Requests de Validação

### StoreSecretaryUserAccessRequest
- [ ] Arquivo criado
- [ ] Regra `person_id` required, exists:people
- [ ] Regra `name` required, string, max:255
- [ ] Regra `email` required, email, unique:users,email
- [ ] Regra `password` nullable, string, min:8
- [ ] Regra `access_notes` nullable, string
- [ ] Mensagens de erro personalizadas

### UpdateSecretaryUserAccessRequest
- [ ] Arquivo criado
- [ ] Regra `name` required, string, max:255
- [ ] Regra `email` required, email, unique:users,email,{user}
- [ ] Regra `access_notes` nullable, string
- [ ] Mensagens de erro personalizadas

## Rotas

- [ ] Import `SecretaryUserAccessController` adicionado
- [ ] Grupo de rotas `/secretaria/acessos` criado
- [ ] Rota `elegibilidade/{person}` antes da rota dinâmica
- [ ] Rota `index` (GET)
- [ ] Rota `create` (GET)
- [ ] Rota `store` (POST)
- [ ] Rota `show` (GET)
- [ ] Rota `edit` (GET)
- [ ] Rota `update` (PUT)
- [ ] Rota `suspend` (PATCH)
- [ ] Rota `reactivate` (PATCH)
- [ ] Todas as rotas dentro de `auth` middleware
- [ ] Nomes de rotas seguem padrão `secretaria.access.*`

## Menu

- [ ] Link "Acessos" adicionado ao menu principal em `AuthenticatedLayout.vue`
- [ ] Link usa `route('secretaria.access.index')`
- [ ] Link usa `route().current('secretaria.access.*')` para active
- [ ] Link "Acessos" adicionado ao menu responsivo

## Páginas Vue

### Index.vue
- [ ] Arquivo criado em `resources/js/Pages/Secretaria/Access/Index.vue`
- [ ] Título: "Acessos do Sistema"
- [ ] Descrição correta
- [ ] Cards de estatísticas renderizados
- [ ] Tabela de usuários com colunas corretas
- [ ] Badge de status colorido
- [ ] Paginação renderizada
- [ ] Link para criar novo acesso
- [ ] Ações: ver, editar, suspender/reativar

### Create.vue
- [ ] Arquivo criado em `resources/js/Pages/Secretaria/Access/Create.vue`
- [ ] Autocomplete para pessoa vinculado
- [ ] Painel de elegibilidade mostra ao selecionar
- [ ] Painel de elegibilidade bloqueia botão se não elegível
- [ ] Nome preenchido automaticamente
- [ ] Email preenchido automaticamente
- [ ] Campo senha temporária opcional
- [ ] Campo observações
- [ ] Botão criar desativado sem pessoa
- [ ] Botão criar desativado se não elegível

### Show.vue
- [ ] Arquivo criado em `resources/js/Pages/Secretaria/Access/Show.vue`
- [ ] Alerta de violação de idade se aplicável
- [ ] Seção dados do usuário
- [ ] Seção pessoa vinculada
- [ ] Seção responsáveis ativos (se Júnior)
- [ ] Formato de datas correto (DD/MM/YYYY HH:mm)
- [ ] Modal de suspensão com motivo obrigatório
- [ ] Modal de reativação com observação opcional
- [ ] Botões de ação funcionais

### Edit.vue
- [ ] Arquivo criado em `resources/js/Pages/Secretaria/Access/Edit.vue`
- [ ] Campo nome editável
- [ ] Campo email editável
- [ ] Campo observações editável
- [ ] Pessoa vinculada apenas leitura
- [ ] Mensagem explicativa sobre não editar pessoa

## Dashboard

- [ ] Import `User` adicionado ao controller
- [ ] Estatística `active_users` calculada
- [ ] Estatística `suspended_users` calculada
- [ ] Estatística `people_without_user` calculada
- [ ] Estatística `juniors_with_access` calculada
- [ ] Props adicionadas ao Dashboard.vue
- [ ] Cards de acesso renderizados no Dashboard.vue
- [ ] Links para tela de acessos funcionais

## Segurança

- [ ] Rotas de acesso protegidas por auth
- [ ] Rotas de busca de pessoas protegidas por auth
- [ ] Rotas de busca de usuários protegidas por auth
- [ ] Senha nunca exibida na tela de detalhes
- [ ] Senha temporária só aparece no momento de criação
- [ ] Validação de elegibilidade no backend
- [ ] Validação de elegibilidade no frontend
- [ ] Não cria usuário automaticamente ao cadastrar pessoa
- [ ] Não cria membro automaticamente ao criar usuário

## Testes Funcionais

### Criar usuário para adulto
- [ ] Selecionar pessoa adulta
- [ ] Confirmar elegibilidade permitida
- [ ] Preencher dados
- [ ] Criar usuário
- [ ] Confirmar senha temporária aparece
- [ ] Confirmar usuário criado

### Bloquear usuário menor de 11 anos
- [ ] Selecionar pessoa menor de 11
- [ ] Confirmar bloqueio com mensagem clara
- [ ] Botão criar desativado

### Bloquear Júnior sem responsável autorizado
- [ ] Selecionar Júnior sem responsável
- [ ] Confirmar bloqueio com mensagem clara
- [ ] Botão criar desativado

### Criar usuário para Júnior com responsável
- [ ] Selecionar Júnior com responsável autorizado
- [ ] Confirmar permissão
- [ ] Criar usuário
- [ ] Confirmar que não cria membro

### Suspender acesso
- [ ] Acessar detalhes de usuário ativo
- [ ] Clicar suspender
- [ ] Informar motivo
- [ ] Confirmar suspensão

### Reativar acesso
- [ ] Acessar detalhes de usuário suspenso
- [ ] Clicar reativar
- [ ] Confirmar reativação

### Editar usuário
- [ ] Acessar edição
- [ ] Alterar dados
- [ ] Salvar
- [ ] Confirmar alterações

### Ver dashboard
- [ ] Acessar dashboard da Secretaria
- [ ] Confirmar cards de acesso atualizados

## Validação

- [ ] `php artisan optimize:clear` executado
- [ ] `php artisan route:list --path=secretaria/acessos` mostra rotas
- [ ] `php artisan route:list --path=people` mostra rotas
- [ ] `php artisan route:list --path=users` mostra rotas
- [ ] `php artisan route:list --path=secretaria` mostra rotas
- [ ] `php artisan test` executa testes
- [ ] `npm run build` compila frontend
- [ ] Verificar arquivos duplicados não encontrados

## Documentação

- [ ] DOCUMENTO_ACESSOS_SECRETARIA.md criado
- [ ] CHECKLIST_ACESSOS_SECRETARIA.md criado
- [ ] DOCUMENTO_SECRETARIA.md atualizado
- [ ] CHECKLIST_SECRETARIA.md atualizado
- [ ] DOCUMENTO_SOLICITACOES_SECRETARIA.md atualizado
- [ ] CHECKLIST_SOLICITACOES_SECRETARIA.md atualizado
- [ ] DOCUMENTO_BANCO_DADOS_INICIAL.md atualizado
- [ ] DOCUMENTO_ARQUITETURA_INICIAL.md atualizado
- [ ] CHECKLIST_INICIAL.md atualizado

## Commit

- [ ] Migration rodada no banco
- [ ] Arquivos novos adicionados ao git
- [ ] Arquivos modificados adicionados ao git
- [ ] Commit com mensagem clara: "Etapa 7: Módulo de Acessos do Sistema"
- [ ] Branch criada se necessário
- [ ] Push realizado se necessário
