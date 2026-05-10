# Checklist: Solicitações e Revisões da Secretaria

## ETAPA 6 do Projeto Ministério Resgate / Família Resgate

Este checklist guia a implementação do módulo de Solicitações e Revisões da Secretaria.

---

## Banco de Dados

### Migration

- [x] Criar migration `create_secretary_requests_table`
- [x] Adicionar campo `id`
- [x] Adicionar campo `uuid` (nullable, unique)
- [x] Adicionar campo `type`
- [x] Adicionar campo `status` (default: pending)
- [x] Adicionar campo `priority` (default: normal)
- [x] Adicionar campo `title`
- [x] Adicionar campo `description` (nullable)
- [x] Adicionar campo `requester_user_id` (nullable, foreign key para users)
- [x] Adicionar campo `requester_person_id` (nullable, foreign key para people)
- [x] Adicionar campo `target_type` (nullable)
- [x] Adicionar campo `target_id` (nullable)
- [x] Adicionar campo `related_alert_id` (nullable, foreign key para system_alerts)
- [x] Adicionar campo `assigned_to_user_id` (nullable, foreign key para users)
- [x] Adicionar campo `current_snapshot` (JSON, nullable)
- [x] Adicionar campo `requested_changes` (JSON, nullable)
- [x] Adicionar campo `internal_notes` (text, nullable)
- [x] Adicionar campo `decision_notes` (text, nullable)
- [x] Adicionar campo `submitted_at` (timestamp, nullable)
- [x] Adicionar campo `reviewed_at` (timestamp, nullable)
- [x] Adicionar campo `reviewed_by_user_id` (nullable, foreign key para users)
- [x] Adicionar campo `approved_at` (timestamp, nullable)
- [x] Adicionar campo `approved_by_user_id` (nullable, foreign key para users)
- [x] Adicionar campo `rejected_at` (timestamp, nullable)
- [x] Adicionar campo `rejected_by_user_id` (nullable, foreign key para users)
- [x] Adicionar campo `completed_at` (timestamp, nullable)
- [x] Adicionar campo `completed_by_user_id` (nullable, foreign key para users)
- [x] Adicionar campo `due_at` (timestamp, nullable)
- [x] Adicionar campo `metadata` (JSON, nullable)
- [x] Adicionar campos `created_at` e `updated_at`
- [x] Adicionar índices: type, status, priority, due_at, requester_person_id, related_alert_id, assigned_to_user_id
- [x] Adicionar foreign keys com nullOnDelete

---

## Model

### SecretaryRequest

- [x] Criar model `app/Models/SecretaryRequest.php`
- [x] Adicionar `$fillable` com todos os campos editáveis
- [x] Adicionar `$casts` para JSON (current_snapshot, requested_changes, metadata)
- [x] Adicionar `$casts` para datetime (submitted_at, reviewed_at, approved_at, rejected_at, completed_at, due_at)
- [x] Criar relacionamento `requesterUser()` pertence a User
- [x] Criar relacionamento `requesterPerson()` pertence a Person
- [x] Criar relacionamento `assignedToUser()` pertence a User
- [x] Criar relacionamento `reviewedByUser()` pertence a User
- [x] Criar relacionamento `approvedByUser()` pertence a User
- [x] Criar relacionamento `rejectedByUser()` pertence a User
- [x] Criar relacionamento `completedByUser()` pertence a User
- [x] Criar relacionamento `relatedAlert()` pertence a SystemAlert
- [x] Criar método `isPending()`
- [x] Criar método `isInReview()`
- [x] Criar método `isApproved()`
- [x] Criar método `isRejected()`
- [x] Criar método `isCompleted()`
- [x] Criar método `isCancelled()`
- [x] Criar método `canBeEdited()`
- [x] Criar método `isOverdue()`
- [x] Criar método `markInReview(int $userId, ?string $notes = null)`
- [x] Criar método `approve(int $userId, string $notes)`
- [x] Criar método `reject(int $userId, string $notes)`
- [x] Criar método `complete(int $userId, string $notes)`
- [x] Criar método `cancel(int $userId, string $notes)`
- [x] Adicionar comentários didáticos nos blocos principais

---

## Controller

### SecretaryRequestController

- [x] Criar controller `app/Http/Controllers/SecretaryRequestController.php`
- [x] Criar método `index()` com filtros por status, tipo e prioridade
- [x] Criar método `create()` com suporte para alert_id na query string
- [x] Criar método `store()` com validação
- [x] Criar método `show()` com todos os relacionamentos carregados
- [x] Criar método `edit()` com verificação de canBeEdited()
- [x] Criar método `update()` com verificação de canBeEdited()
- [x] Criar método `markInReview()` com verificação de status
- [x] Criar método `approve()` com verificação de status e decision_notes obrigatório
- [x] Criar método `reject()` com verificação de status e decision_notes obrigatório
- [x] Criar método `complete()` com verificação de status e decision_notes obrigatório
- [x] Criar método `cancel()` com verificação de status e decision_notes obrigatório
- [x] Adicionar comentários didáticos nos blocos principais

---

## Requests de Validação

### StoreSecretaryRequestRequest

- [x] Criar request `app/Http/Requests/StoreSecretaryRequestRequest.php`
- [x] Definir `authorize()` retornando `auth()->check()`
- [x] Definir `rules()` com validações para type, priority, title, description, requester_person_id, related_alert_id, assigned_to_user_id, due_at, internal_notes
- [x] Definir `messages()` com mensagens em português
- [x] Validar type: required, in: tipos permitidos
- [x] Validar priority: required, in: low, normal, high, urgent
- [x] Validar title: required, string, max:255
- [x] Validar requester_person_id: nullable, exists:people,id
- [x] Validar related_alert_id: nullable, exists:system_alerts,id
- [x] Validar assigned_to_user_id: nullable, exists:users,id
- [x] Validar due_at: nullable, date, after:now

### UpdateSecretaryRequestRequest

- [x] Criar request `app/Http/Requests/UpdateSecretaryRequestRequest.php`
- [x] Definir `authorize()` retornando `auth()->check()`
- [x] Definir `rules()` com validações para title, description, priority, requester_person_id, related_alert_id, assigned_to_user_id, due_at, internal_notes
- [x] Definir `messages()` com mensagens em português
- [x] Validar title: required, string, max:255
- [x] Validar priority: required, in: low, normal, high, urgent
- [x] Validar requester_person_id: nullable, exists:people,id
- [x] Validar related_alert_id: nullable, exists:system_alerts,id
- [x] Validar assigned_to_user_id: nullable, exists:users,id
- [x] Validar due_at: nullable, date, after:now

---

## Rotas

- [x] Adicionar import de `SecretaryRequestController` em `routes/web.php`
- [x] Criar grupo de rotas `secretaria/solicitacoes` com name `secretaria.requests.`
- [x] Criar rota `GET /secretaria/solicitacoes` → index
- [x] Criar rota `GET /secretaria/solicitacoes/criar` → create
- [x] Criar rota `POST /secretaria/solicitacoes` → store
- [x] Criar rota `GET /secretaria/solicitacoes/{secretaryRequest}` → show
- [x] Criar rota `GET /secretaria/solicitacoes/{secretaryRequest}/editar` → edit
- [x] Criar rota `PUT/PATCH /secretaria/solicitacoes/{secretaryRequest}` → update
- [x] Criar rota `PATCH /secretaria/solicitacoes/{secretaryRequest}/em-analise` → mark-in-review
- [x] Criar rota `PATCH /secretaria/solicitacoes/{secretaryRequest}/aprovar` → approve
- [x] Criar rota `PATCH /secretaria/solicitacoes/{secretaryRequest}/rejeitar` → reject
- [x] Criar rota `PATCH /secretaria/solicitacoes/{secretaryRequest}/concluir` → complete
- [x] Criar rota `PATCH /secretaria/solicitacoes/{secretaryRequest}/cancelar` → cancel
- [x] Adicionar comentários explicativos nas rotas

---

## Menu

### Menu Principal

- [x] Adicionar link "Solicitações" em `resources/js/Layouts/AuthenticatedLayout.vue`
- [x] Posicionar após "Alertas"
- [x] Usar `route('secretaria.requests.index')`
- [x] Usar `route().current('secretaria.requests.*')` para active

### Menu Responsivo

- [x] Adicionar link "Solicitações" no menu responsivo
- [x] Posicionar após "Alertas"
- [x] Usar `route('secretaria.requests.index')`
- [x] Usar `route().current('secretaria.requests.*')` para active

---

## Páginas Vue

### Index.vue

- [x] Criar página `resources/js/Pages/Secretaria/Requests/Index.vue`
- [x] Adicionar título "Solicitações da Secretaria"
- [x] Adicionar descrição "Área para acompanhar pedidos, revisões e decisões antes de alterar dados oficiais."
- [x] Criar cards com contagens: Pendentes, Em análise, Aprovadas, Concluídas, Urgentes
- [x] Criar filtros por status, tipo e prioridade
- [x] Criar tabela responsiva sem corte de texto (remover whitespace-nowrap, adicionar min-width)
- [x] Adicionar colunas: título, tipo, status, prioridade, data, ações
- [x] Adicionar botão "Nova Solicitação"
- [x] Adicionar botão "Ver" para cada solicitação
- [x] Adicionar botão "Editar" quando permitido
- [x] Implementar helpers: getStatusColor, getStatusLabel, getPriorityColor, getPriorityLabel, getTypeLabel

### Create.vue

- [x] Criar página `resources/js/Pages/Secretaria/Requests/Create.vue`
- [x] Adicionar título "Nova Solicitação"
- [x] Criar formulário com campos: tipo, prioridade, título, descrição, pessoa relacionada, alerta relacionado, responsável, prazo, observações internas
- [x] Adicionar texto de ajuda baseado no tipo selecionado
- [x] Se vier alert_id na query string:
  - [x] Buscar alerta com pessoa relacionada
  - [x] Preencher tipo como alert_resolution_review
  - [x] Preencher título e descrição a partir do alerta
  - [x] Vincular related_alert_id
  - [x] Preencher requester_person_id se houver pessoa relacionada
- [x] Adicionar botão "Criar Solicitação"
- [x] Adicionar botão "Voltar"
- [x] Adicionar mensagem "Esta solicitação não altera dados oficiais automaticamente"

### Show.vue

- [x] Criar página `resources/js/Pages/Secretaria/Requests/Show.vue`
- [x] Adicionar título "Detalhes da Solicitação"
- [x] Mostrar título, status, prioridade, tipo, descrição
- [x] Mostrar pessoa relacionada (se houver)
- [x] Mostrar alerta relacionado (se houver)
- [x] Mostrar responsável pela análise (se houver)
- [x] Mostrar prazo (se houver)
- [x] Mostrar histórico de datas: criado em, enviado em, revisado em, aprovado em, rejeitado em, concluído em
- [x] Mostrar observações internas (se houver)
- [x] Mostrar decisão/observação final (se houver)
- [x] Criar ações:
  - [x] Marcar em análise (se pending)
  - [x] Aprovar (se pending ou in_review)
  - [x] Rejeitar (se pending ou in_review)
  - [x] Concluir (se approved ou in_review)
  - [x] Cancelar (se pending ou in_review)
- [x] Cada ação exige observação/justificativa (decision_notes obrigatório)
- [x] Adicionar botão "Voltar"
- [x] Implementar helpers: getStatusColor, getStatusLabel, getPriorityColor, getPriorityLabel, getTypeLabel

### Edit.vue

- [x] Criar página `resources/js/Pages/Secretaria/Requests/Edit.vue`
- [x] Adicionar título "Editar Solicitação"
- [x] Criar formulário com campos editáveis: título, descrição, prioridade, pessoa relacionada, alerta relacionado, responsável, prazo, observações internas
- [x] Verificar canBeEdited() antes de mostrar formulário
- [x] Não permitir editar se concluída, rejeitada ou cancelada
- [x] Adicionar botão "Salvar Alterações"
- [x] Adicionar botão "Cancelar"
- [x] Adicionar botão "Voltar"

---

## Dashboard

### SecretaryDashboardController

- [x] Adicionar import de `SecretaryRequest`
- [x] Adicionar contagem de solicitações pendentes
- [x] Adicionar contagem de solicitações urgentes
- [x] Adicionar contagem de solicitações em análise
- [x] Passar dados para a view: pending_requests, urgent_requests, in_review_requests

### Dashboard.vue

- [x] Adicionar props: pending_requests, urgent_requests, in_review_requests
- [x] Criar cards de solicitações:
  - [x] "Solicitações Pendentes"
  - [x] "Solicitações em Análise"
  - [x] "Solicitações Urgentes"
- [x] Cada card tem link para página de solicitações
- [x] Cores dinâmicas baseadas nos contadores

---

## Integração com Alertas

### Resolve.vue

- [x] Adicionar botão "Criar solicitação de revisão"
- [x] Navegar para `secretaria.requests.create` com `alert_id` como query parameter
- [x] Posicionar botão no header do alerta

### Create.vue

- [x] Se vier `alert_id` na query string:
  - [x] Buscar alerta com pessoa relacionada
  - [x] Preencher tipo como `alert_resolution_review`
  - [x] Preencher título e descrição a partir do alerta
  - [x] Vincular `related_alert_id`
  - [x] Preencher `requester_person_id` se houver pessoa relacionada

---

## Documentação

### DOCUMENTO_SOLICITACOES_SECRETARIA.md

- [x] Criar documento completo sobre solicitações e revisões
- [x] Documentar objetivo do módulo
- [x] Documentar conceito chave (solicitação não é dado oficial)
- [x] Documentar tabela secretary_requests com todos os campos
- [x] Documentar tipos de solicitação
- [x] Documentar status e fluxo
- [x] Documentar prioridades
- [x] Documentar model SecretaryRequest
- [x] Documentar controller SecretaryRequestController
- [x] Documentar requests de validação
- [x] Documentar rotas
- [x] Documentar páginas Vue
- [x] Documentar menu
- [x] Documentar integração com dashboard
- [x] Documentar integração com alertas
- [x] Documentar regras de segurança
- [x] Documentar integração futura
- [x] Documentar diferença entre alerta e solicitação

### CHECKLIST_SOLICITACOES_SECRETARIA.md

- [x] Criar checklist de implementação
- [x] Listar todas as tarefas de banco de dados
- [x] Listar todas as tarefas de model
- [x] Listar todas as tarefas de controller
- [x] Listar todas as tarefas de requests
- [x] Listar todas as tarefas de rotas
- [x] Listar todas as tarefas de menu
- [x] Listar todas as tarefas de páginas Vue
- [x] Listar todas as tarefas de dashboard
- [x] Listar todas as tarefas de integração com alertas
- [x] Listar todas as tarefas de documentação

---

## Validação

- [x] Rodar `php artisan optimize:clear`
- [x] Rodar `php artisan route:list --path=secretaria/solicitacoes`
- [x] Rodar `php artisan route:list --path=secretaria/alertas`
- [x] Rodar `php artisan route:list --path=secretaria`
- [x] Rodar `php artisan test`
- [x] Rodar `npm run build`
- [x] Rodar `git status`
- [x] Verificar duplicidades: `find app resources database routes -iname "*Novo*" -o -iname "*Final*" -o -iname "*Corrigido*" -o -iname "*V2*" -o -iname "*Backup*" -o -iname "*Copy*"`

---

## Teste Manual no Navegador

### Testar Solicitações

- [ ] Abrir: `http://127.0.0.1:8000/secretaria/solicitacoes`
- [ ] Clicar em "Nova Solicitação"
- [ ] Preencher formulário
- [ ] Salvar
- [ ] Ver detalhes
- [ ] Marcar em análise
- [ ] Aprovar com observação
- [ ] Concluir com observação
- [ ] Criar outra solicitação
- [ ] Rejeitar com observação
- [ ] Criar outra solicitação
- [ ] Cancelar com observação
- [ ] Testar filtros por status, tipo e prioridade
- [ ] Confirmar que solicitação concluída não permite edição

### Testar Integração com Alertas

- [ ] Abrir um alerta
- [ ] Clicar em "Criar solicitação de revisão"
- [ ] Confirmar que a tela de criação já vem com dados do alerta
- [ ] Salvar
- [ ] Ver se a solicitação ficou vinculada ao alerta

### Testar Painel

- [ ] Abrir: `http://127.0.0.1:8000/secretaria`
- [ ] Confirmar que cards de solicitações aparecem
- [ ] Confirmar que números fazem sentido
- [ ] Confirmar que link para solicitações funciona

---

## Atualizar Documentos Existentes

- [ ] Atualizar DOCUMENTO_SECRETARIA.md com seção sobre Etapa 6
- [ ] Atualizar CHECKLIST_SECRETARIA.md com seção sobre Etapa 6
- [ ] Atualizar DOCUMENTO_ALERTAS_SECRETARIA.md com integração com solicitações
- [ ] Atualizar CHECKLIST_ALERTAS_SECRETARIA.md com integração com solicitações
- [ ] Atualizar DOCUMENTO_BANCO_DADOS_INICIAL.md com seção sobre Etapa 6
- [ ] Atualizar DOCUMENTO_ARQUITETURA_INICIAL.md com seção sobre Etapa 6
- [ ] Atualizar CHECKLIST_INICIAL.md com seção sobre Etapa 6

---

## Commit

- [ ] Fazer commit com mensagem: "feat: criar solicitações e revisões da secretaria"
- [ ] Verificar git status está limpo

---

## Resumo

Este checklist garante que todos os aspectos do módulo de Solicitações e Revisões da Secretaria foram implementados corretamente, seguindo as regras de segurança e as melhores práticas do projeto.
