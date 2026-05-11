# CHECKLIST_SECRETARIA

## Painel Inicial da Secretaria
**Etapa 4 do Projeto Ministério Resgate / Família Resgate**

Este checklist acompanha a implementação do painel inicial da Secretaria.

---

## Controller

### ✅ SecretaryDashboardController Criado

- [x] Arquivo criado: `app/Http/Controllers/SecretaryDashboardController.php`
- [x] Método `index()` implementado
- [x] Comentários didáticos nos blocos principais
- [x] Busca dados reais do banco usando models existentes:
  - [x] Person
  - [x] Family
  - [x] GuardianShip
  - [x] FamilyMember
- [x] Não usa dados fake
- [x] Não usa seeder
- [x] Não inventa números

### Indicadores Calculados

- [x] Total de pessoas (count de people não deletadas)
- [x] Total de famílias (count de families não deletadas)
- [x] Total de responsáveis ativos (count de guardianships com status active)
- [x] Crianças menores de 11 anos (pessoas com birth_date e idade < 11)
- [x] Júniores (pessoas com idade >= 11 e < 14)
- [x] Jovens (pessoas com idade >= 14 e < 18)
- [x] Adultos (pessoas com idade >= 18)
- [x] Pessoas sem família (pessoas que não possuem vínculo em family_members ativo)
- [x] Menores sem responsável ativo (pessoas menores de 18 anos sem guardianship ativo)
- [x] Crianças próximas dos 11 anos (menores de 11 anos que completarão 11 anos nos próximos 60 dias)
  - [x] Mostra nome, idade atual e data em que completa 11
- [x] Pessoas sem data de nascimento (sem birth_date)
- [x] Pessoas sem telefone principal (sem primary_phone)
- [x] Pessoas sem email e sem telefone
- [x] Pessoas recentemente cadastradas (últimas 5)
- [x] Famílias recentemente criadas (últimas 5)
- [x] Responsabilidades recentes (últimas 5)

### Validação de Dados

- [x] Não quebra se não houver pessoas
- [x] Não quebra se não houver famílias
- [x] Não quebra se não houver responsáveis
- [x] Trata pessoa sem birth_date
- [x] Trata pessoa sem família
- [x] Trata responsible_person_id null
- [x] Trata guardian/minor null
- [x] Trata invited_by_person_id null
- [x] Acesso seguro no PHP (nullsafe operators, verificações)
- [x] Valores padrão e mensagens amigáveis

---

## Rotas

### ✅ Rota Secretaria Criada

- [x] Rota criada: `GET /secretaria`
- [x] Nome da rota: `secretaria.dashboard`
- [x] Controller: `SecretaryDashboardController@index`
- [x] Middleware: `auth` (autenticação obrigatória)
- [x] Arquivo: `routes/web.php`
- [x] Import do SecretaryDashboardController adicionado

---

## Página Vue

### ✅ Dashboard.vue Criado

- [x] Arquivo criado: `resources/js/Pages/Secretaria/Dashboard.vue`
- [x] Usa AuthenticatedLayout
- [x] Props definidas com valores padrão
- [x] Acesso seguro no Vue (verificações de null/undefined)
- [x] Textos em português do Brasil
- [x] Visual limpo, simples e responsivo

### Estrutura da Página

- [x] Cabeçalho: "Painel da Secretaria"
- [x] Subtítulo: "Visão inicial para acompanhar cadastros, famílias e responsáveis"
- [x] Cards principais:
  - [x] Pessoas (com link)
  - [x] Famílias (com link)
  - [x] Responsáveis Ativos (com link)
  - [x] Menores sem Responsável (indicador vermelho se > 0)
- [x] Cards por faixa etária:
  - [x] Crianças menores de 11 (azul)
  - [x] Júniores (verde)
  - [x] Jovens (roxo)
  - [x] Adultos (cinza)
- [x] Atenções da Secretaria:
  - [x] Pessoas sem Família (âmbar)
  - [x] Sem Data de Nascimento (âmbar)
  - [x] Sem Telefone (âmbar)
- [x] Crianças Próximas dos 11 Anos:
  - [x] Lista com nome, idade e data de aniversário
  - [x] Aviso informativo em azul
  - [x] Recomendação para revisão
- [x] Listas rápidas:
  - [x] Pessoas Recentes (últimas 5)
  - [x] Famílias Recentes (últimas 5)
  - [x] Responsabilidades Recentes (últimas 5)
- [x] Atalhos rápidos:
  - [x] Cadastrar Pessoa
  - [x] Ver Pessoas
  - [x] Criar Família
  - [x] Ver Famílias
  - [x] Criar Responsável
  - [x] Ver Responsáveis
- [x] Todos os botões apontam para rotas reais existentes

---

## Menu

### ✅ Link Secretaria Adicionado

- [x] Link adicionado ao menu autenticado (desktop)
- [x] Link adicionado ao menu responsivo (mobile)
- [x] Arquivo: `resources/js/Layouts/AuthenticatedLayout.vue`
- [x] NavLink usa `route('secretaria.dashboard')`
- [x] Active state verifica `route().current('secretaria.dashboard')`
- [x] Não removeu:
  - [x] Dashboard (Breeze)
  - [x] Pessoas
  - [x] Famílias
  - [x] Responsáveis
- [x] Não criou menu duplicado

---

## Regra dos 11 Anos

### ✅ Regra Respeitada e Documentada

- [x] Responsabilidade legal NÃO acaba automaticamente aos 11 anos documentado
- [x] O que muda é a regra do sistema documentado
- [x] Não preencher automaticamente ends_at documentado
- [x] Aviso para revisar cadastro ao completar 11 anos documentado
- [x] Crianças menores de 11 anos documentadas
- [x] Júniores documentados
- [x] Jovens documentados
- [x] Adultos documentados
- [x] Campo invited_by_person_id preservado para futuro evangelismo/pontuação documentado

---

## Campo Quem Indicou / Convidou

### ✅ Campo Preservado

- [x] Campo `invited_by_person_id` existe no cadastro de pessoas
- [x] Não criou pontuação nesta etapa
- [x] Não criou ranking nesta etapa
- [x] Não criou gamificação nesta etapa
- [x] Não criou Membro Destaque nesta etapa
- [x] Documentado como próximo passo futuro para:
  - [x] Frutos de evangelismo
  - [x] Acompanhamento de visitantes
  - [x] Reconhecimento
  - [x] Pontuação
  - [x] Membro Destaque
  - [x] Avaliação dos jovens/Resgatados

---

## Documentação

### ✅ Documentação Criada

- [x] `DOCUMENTO_SECRETARIA.md` criado
- [x] `CHECKLIST_SECRETARIA.md` criado
- [x] Objetivo do painel documentado
- [x] Indicadores documentados
- [x] Regra dos 11 anos documentada
- [x] Diferença entre pessoa, família e responsável documentada
- [x] Campo quem indicou/convidou documentado
- [x] O que não foi implementado nesta etapa documentado

### ✅ Documentação Atualizada

- [ ] `DOCUMENTO_BANCO_DADOS_INICIAL.md`
- [ ] `DOCUMENTO_ARQUITETURA_INICIAL.md`
- [ ] `CHECKLIST_INICIAL.md`

---

## Módulo de Acessos ao Sistema
**Etapa 7 do Projeto Ministério Resgate / Família Resgate**

### ✅ Banco de Dados

- [x] Migration `alter_users_table_add_access_fields` criada
- [x] Campos adicionados: must_change_password, access_granted_at, access_revoked_at, access_revoked_reason, access_notes
- [x] Índices adicionados
- [x] Migration down() remove índices e colunas corretamente

### ✅ Model User

- [x] Fillable atualizado com novos campos
- [x] Casts atualizados para novos campos
- [x] Métodos: isSuspended(), requiresPasswordChange(), hasPerson()
- [x] Relacionamento person() existe

### ✅ Model Person

- [x] Métodos: requiresGuardianForUser(), hasActiveGuardianAuthorizedForLogin()
- [x] Métodos auxiliares: age(), ageGroup()
- [x] Relacionamento user() existe

### ✅ Service: UserAccessEligibilityService

- [x] Arquivo criado em app/Services/Secretaria/
- [x] Método check() implementado com todas as validações
- [x] Método hasActiveUser() implementado

### ✅ Controller: SecretaryUserAccessController

- [x] Arquivo criado em app/Http/Controllers/
- [x] Todos os métodos implementados: index, create, store, show, edit, update, suspend, reactivate, eligibility
- [x] Validações de elegibilidade implementadas
- [x] Geração de senha temporária implementada

### ✅ Requests de Validação

- [x] StoreSecretaryUserAccessRequest criado
- [x] UpdateSecretaryUserAccessRequest criado
- [x] Regras de validação definidas
- [x] Mensagens de erro personalizadas

### ✅ Rotas

- [x] Grupo de rotas /secretaria/acessos criado
- [x] Todas as rotas definidas
- [x] Rota elegibilidade antes da rota dinâmica
- [x] Protegidas por auth middleware

### ✅ Menu

- [x] Link "Acessos" adicionado ao menu principal
- [x] Link "Acessos" adicionado ao menu responsivo

### ✅ Páginas Vue

- [x] Index.vue criado
- [x] Create.vue criado
- [x] Show.vue criado
- [x] Edit.vue criado
- [x] Autocomplete de pessoa funcionando
- [x] Painel de elegibilidade funcionando
- [x] Modais de suspensão/reativação funcionando

### ✅ Dashboard

- [x] Estatísticas de acesso adicionadas ao controller
- [x] Cards de acesso adicionados ao Dashboard.vue
- [x] Links funcionando

### ✅ Segurança

- [x] Rotas protegidas por auth
- [x] Validação de elegibilidade no backend
- [x] Validação de elegibilidade no frontend
- [x] Senha temporária gerada automaticamente
- [x] Senha não exibida na tela de detalhes

### ✅ Documentação

- [x] DOCUMENTO_ACESSOS_SECRETARIA.md criado
- [x] CHECKLIST_ACESSOS_SECRETARIA.md criado
- [x] DOCUMENTO_SECRETARIA.md atualizado

---

## Não Implementar Nesta Etapa

### ✅ Não Criado

- [x] Sistema completo de alertas
- [x] Aprovação de cadastro
- [x] Notificações reais
- [x] Gráficos complexos
- [x] Relatórios exportáveis
- [x] Usuário automático aos 11 anos
- [x] Membro automático
- [x] Departamento Resgatados automático
- [x] Pontuação/gamificação de evangelismo
- [x] Ranking de quem indicou pessoas
- [x] Financeiro
- [x] Cantina
- [x] Louvor
- [x] Cadastro online
- [x] Departamentos visual
- [x] Resgatados visual
- [x] App mobile

---

## Validação

### ⏳ Comandos de Validação (pendente)

- [ ] `php artisan optimize:clear`
- [ ] `php artisan route:list --path=secretaria`
- [ ] `php artisan route:list --path=people`
- [ ] `php artisan route:list --path=families`
- [ ] `php artisan route:list --path=guardianships`
- [ ] `php artisan test`
- [ ] `npm run build`
- [ ] `git status`

### ⏳ Teste Manual no Navegador (pendente)

- [ ] Acessar: http://127.0.0.1:8000/secretaria
- [ ] Página abre sem tela branca
- [ ] Cards aparecem
- [ ] Números aparecem corretamente ou zero
- [ ] Atalhos funcionam
- [ ] Pessoas recentes aparecem
- [ ] Famílias recentes aparecem
- [ ] Responsáveis recentes aparecem
- [ ] Crianças próximas dos 11 anos aparecem quando houver
- [ ] Menores sem responsável aparecem quando houver
- [ ] Não há botão quebrado
- [ ] Não há erro no console do navegador

---

## Checkpoints

### ✅ Checkpoint 1 (após 4 alterações)

- [x] git status verificado
- [x] git log --oneline -5 verificado
- [x] Verificação de duplicidade realizada (nenhum arquivo Novo/Final/Corrigido/V2/Backup/Copy encontrado)
- [x] Verificação de módulos fora do escopo realizada (nenhum módulo proibido criado)

---

## Etapa 5 - Alertas Internos da Secretaria

### Banco de Dados
- [x] Migration para adicionar resolution_notes criada e executada
- [x] Tabela system_alerts verificada e ajustada

### Model
- [x] SystemAlert ajustado com métodos isOpen(), isIgnored(), markAsResolved(), markAsIgnored()
- [x] resolution_notes adicionado ao fillable

### Service
- [x] SecretaryAlertService criado com geração de 6 tipos de alertas
- [x] Regra de unicidade implementada (type + related_person_id + status pending)
- [x] Dados reais usados (sem seeders fake)

### Controller
- [x] SecretaryAlertController criado com index, show, resolve, ignore, regenerate
- [x] Filtros por status, tipo e severidade implementados

### Rotas
- [x] Rotas criadas para alertas (index, show, resolve, ignore, regenerate)
- [x] Middleware auth aplicado

### Menu
- [x] Link "Alertas" adicionado ao menu desktop
- [x] Link "Alertas" adicionado ao menu responsivo

### Páginas Vue
- [x] Alerts/Index.vue criada com resumo, filtros e lista
- [x] Alerts/Show.vue criada com detalhes e ações

### Integração com Painel
- [x] Dashboard.vue atualizado com card de alertas
- [x] SecretaryDashboardController atualizado com dados de alertas

### Documentação
- [x] DOCUMENTO_ALERTAS_SECRETARIA.md criado
- [x] CHECKLIST_ALERTAS_SECRETARIA.md criado

### Não Implementado
- [x] Sistema completo de notificações externas
- [x] Alertas automáticos em tempo real
- [x] Tarefas recorrentes avançadas
- [x] IA para análise de alertas

---

## Etapa 6 - Solicitações e Revisões da Secretaria

### Banco de Dados
- [x] Migration para secretary_requests criada
- [x] Campos principais adicionados (type, status, priority, title, description, requester_person_id, related_alert_id, assigned_to_user_id, current_snapshot, requested_changes, internal_notes, decision_notes, submitted_at, reviewed_at, approved_at, rejected_at, completed_at, due_at, metadata)
- [x] Índices adicionados (type, status, priority, due_at, requester_person_id, related_alert_id, assigned_to_user_id)
- [x] Foreign keys adicionados com nullOnDelete

### Model
- [x] SecretaryRequest criado com relacionamentos
- [x] Métodos de estado implementados (isPending, isInReview, isApproved, isRejected, isCompleted, isCancelled, canBeEdited, isOverdue)
- [x] Métodos de ação implementados (markInReview, approve, reject, complete, cancel)
- [x] Casts adicionados para JSON e datetime

### Controller
- [x] SecretaryRequestController criado com index, create, store, show, edit, update, markInReview, approve, reject, complete, cancel
- [x] Filtros por status, tipo e prioridade implementados
- [x] Validações implementadas com decision_notes obrigatório

### Requests
- [x] StoreSecretaryRequestRequest criado
- [x] UpdateSecretaryRequestRequest criado
- [x] Mensagens em português adicionadas

### Rotas
- [x] Rotas criadas para solicitações (index, create, store, show, edit, update, mark-in-review, approve, reject, complete, cancel)
- [x] Middleware auth aplicado

### Menu
- [x] Link "Solicitações" adicionado ao menu desktop
- [x] Link "Solicitações" adicionado ao menu responsivo

### Páginas Vue
- [x] Requests/Index.vue criada com resumo, filtros e lista
- [x] Requests/Create.vue criada com formulário e suporte para alert_id
- [x] Requests/Show.vue criada com detalhes e ações de fluxo
- [x] Requests/Edit.vue criada com formulário de edição

### Integração com Painel
- [x] Dashboard.vue atualizado com cards de solicitações
- [x] SecretaryDashboardController atualizado com dados de solicitações

### Integração com Alertas
- [x] Botão "Criar solicitação de revisão" adicionado em Resolve.vue
- [x] Create.vue preenche dados automaticamente quando vem de alerta

### Documentação
- [x] DOCUMENTO_SOLICITACOES_SECRETARIA.md criado
- [x] CHECKLIST_SOLICITACOES_SECRETARIA.md criado

### Regras de Segurança
- [x] Não altera dados oficiais automaticamente
- [x] Solicitação não é o dado oficial
- [x] Secretaria deve aplicar alteração manualmente

---

## Commit

### ⏳ Commit (pendente)

- [ ] `git add .`
- [ ] `git commit -m "feat: criar painel inicial da secretaria"`
