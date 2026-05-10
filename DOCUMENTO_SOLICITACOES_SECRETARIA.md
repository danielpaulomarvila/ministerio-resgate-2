# Documento: Solicitações e Revisões da Secretaria

## Objetivo

Criar um módulo interno para a Secretaria registrar, acompanhar e tratar solicitações/revisões antes de alterar dados oficiais.

Este módulo será a base futura para:
- Pedido de alteração cadastral vindo do membro
- Cadastro online pendente
- Revisão de dados pessoais
- Revisão de família
- Revisão de responsável
- Revisão de transição dos 11 anos
- Revisão de alerta
- Pedidos internos da Secretaria

Nesta etapa, tudo é interno da Secretaria.

---

## Conceito Chave

**A solicitação não é o dado oficial.**

Exemplo:
- Uma pessoa pede para trocar telefone
- O sistema registra uma solicitação
- A Secretaria analisa
- Se aprovar, a Secretaria aplica ou confirma a alteração
- Depois marca como concluída

A solicitação deve ter histórico, status, prioridade, responsável e observações.

---

## Tabela: secretary_requests

### Campos principais

- `id` - Chave primária
- `uuid` - UUID único (nullable, unique)
- `type` - Tipo da solicitação
- `status` - Status da solicitação (default: pending)
- `priority` - Prioridade (default: normal)
- `title` - Título da solicitação
- `description` - Descrição detalhada (nullable)
- `requester_user_id` - ID do usuário solicitante (nullable, foreign key)
- `requester_person_id` - ID da pessoa solicitante (nullable, foreign key)
- `target_type` - Tipo do alvo polimórfico (nullable)
- `target_id` - ID do alvo polimórfico (nullable)
- `related_alert_id` - ID do alerta relacionado (nullable, foreign key)
- `assigned_to_user_id` - ID do usuário responsável pela análise (nullable, foreign key)
- `current_snapshot` - Estado atual conhecido (JSON, nullable)
- `requested_changes` - Mudanças solicitadas (JSON, nullable)
- `internal_notes` - Observações internas (text, nullable)
- `decision_notes` - Decisão/observação final (text, nullable)
- `submitted_at` - Data de envio (timestamp, nullable)
- `reviewed_at` - Data de revisão (timestamp, nullable)
- `reviewed_by_user_id` - ID do usuário que revisou (nullable, foreign key)
- `approved_at` - Data de aprovação (timestamp, nullable)
- `approved_by_user_id` - ID do usuário que aprovou (nullable, foreign key)
- `rejected_at` - Data de rejeição (timestamp, nullable)
- `rejected_by_user_id` - ID do usuário que rejeitou (nullable, foreign key)
- `completed_at` - Data de conclusão (timestamp, nullable)
- `completed_by_user_id` - ID do usuário que concluiu (nullable, foreign key)
- `due_at` - Prazo (timestamp, nullable)
- `metadata` - Metadados adicionais (JSON, nullable)
- `created_at` - Data de criação
- `updated_at` - Data de atualização

### Índices

- `type`
- `status`
- `priority`
- `due_at`
- `requester_person_id`
- `related_alert_id`
- `assigned_to_user_id`

---

## Tipos de Solicitação

### type

- `registration_review` - Revisão de cadastro
- `personal_data_change` - Alteração de dados pessoais
- `family_link_review` - Revisão de vínculo familiar
- `guardianship_review` - Revisão de responsável
- `child_transition_review` - Revisão de transição dos 11 anos
- `alert_resolution_review` - Revisão de alerta
- `manual_secretary_request` - Solicitação manual da Secretaria

### Interface em Português

- Revisão de cadastro
- Alteração de dados pessoais
- Revisão de vínculo familiar
- Revisão de responsável
- Revisão de transição dos 11 anos
- Revisão de alerta
- Solicitação manual da Secretaria

---

## Status

### status

- `pending` - Pendente
- `in_review` - Em análise
- `approved` - Aprovada
- `rejected` - Rejeitada
- `completed` - Concluída
- `cancelled` - Cancelada

### Interface

- Pendente
- Em análise
- Aprovada
- Rejeitada
- Concluída
- Cancelada

### Regras de Fluxo

- `pending`: Criada, aguardando análise
- `in_review`: Alguém da Secretaria está analisando
- `approved`: Aprovada, mas ainda pode não ter sido aplicada
- `rejected`: Rejeitada com motivo
- `completed`: Processo concluído
- `cancelled`: Cancelada sem prosseguir

### Transições Permitidas

- `pending` → `in_review` (Marcar em análise)
- `pending` → `approved` (Aprovar)
- `pending` → `rejected` (Rejeitar)
- `pending` → `cancelled` (Cancelar)
- `in_review` → `approved` (Aprovar)
- `in_review` → `rejected` (Rejeitar)
- `in_review` → `cancelled` (Cancelar)
- `in_review` → `completed` (Concluir)
- `approved` → `completed` (Concluir)

### Transições Proibidas

- `completed` → `pending` (Não pode voltar para pendente)
- `rejected` → `approved` (Nova solicitação necessária)
- `cancelled` → `completed` (Nova solicitação necessária)

---

## Prioridade

### priority

- `low` - Baixa
- `normal` - Normal
- `high` - Alta
- `urgent` - Urgente

### Interface

- Baixa
- Normal
- Alta
- Urgente

---

## Model: SecretaryRequest

### Caminho

`app/Models/SecretaryRequest.php`

### Relacionamentos

- `requesterUser` - Pertence a User
- `requesterPerson` - Pertence a Person
- `assignedToUser` - Pertence a User
- `reviewedByUser` - Pertence a User
- `approvedByUser` - Pertence a User
- `rejectedByUser` - Pertence a User
- `completedByUser` - Pertence a User
- `relatedAlert` - Pertence a SystemAlert

### Métodos Úteis

- `isPending()` - Verifica se está pendente
- `isInReview()` - Verifica se está em análise
- `isApproved()` - Verifica se foi aprovada
- `isRejected()` - Verifica se foi rejeitada
- `isCompleted()` - Verifica se foi concluída
- `isCancelled()` - Verifica se foi cancelada
- `canBeEdited()` - Verifica se pode ser editada (não concluída, rejeitada ou cancelada)
- `isOverdue()` - Verifica se está atrasada

### Métodos de Estado

- `markInReview(int $userId, ?string $notes = null)` - Marca como em análise
- `approve(int $userId, string $notes)` - Aprova solicitação
- `reject(int $userId, string $notes)` - Rejeita solicitação
- `complete(int $userId, string $notes)` - Conclui solicitação
- `cancel(int $userId, string $notes)` - Cancela solicitação

### Casts

- `current_snapshot` - array
- `requested_changes` - array
- `metadata` - array
- `submitted_at` - datetime
- `reviewed_at` - datetime
- `approved_at` - datetime
- `rejected_at` - datetime
- `completed_at` - datetime
- `due_at` - datetime

---

## Controller: SecretaryRequestController

### Caminho

`app/Http/Controllers/SecretaryRequestController.php`

### Métodos

- `index()` - Lista todas as solicitações com filtros
- `create()` - Mostra formulário de criação
- `store()` - Salva nova solicitação
- `show(SecretaryRequest $secretaryRequest)` - Mostra detalhes de uma solicitação
- `edit(SecretaryRequest $secretaryRequest)` - Mostra formulário de edição
- `update(Request $request, SecretaryRequest $secretaryRequest)` - Atualiza solicitação
- `markInReview(Request $request, SecretaryRequest $secretaryRequest)` - Marca como em análise
- `approve(Request $request, SecretaryRequest $secretaryRequest)` - Aprova solicitação
- `reject(Request $request, SecretaryRequest $secretaryRequest)` - Rejeita solicitação
- `complete(Request $request, SecretaryRequest $secretaryRequest)` - Conclui solicitação
- `cancel(Request $request, SecretaryRequest $secretaryRequest)` - Cancela solicitação

### Validações

- `type`: obrigatório, dentro dos tipos permitidos
- `title`: obrigatório, max 255 caracteres
- `description`: opcional
- `priority`: obrigatório, dentro das prioridades permitidas
- `requester_person_id`: opcional, deve existir em people
- `related_alert_id`: opcional, deve existir em system_alerts
- `assigned_to_user_id`: opcional, deve existir em users
- `due_at`: opcional, data válida futura
- `internal_notes`: opcional
- `decision_notes`: obrigatório ao aprovar, rejeitar, concluir ou cancelar

### Filtros na Index

- Status: all, pending, in_review, approved, rejected, completed, cancelled
- Tipo: all, registration_review, personal_data_change, family_link_review, guardianship_review, child_transition_review, alert_resolution_review, manual_secretary_request
- Prioridade: all, low, normal, high, urgent

### Contagens na Index

- pending
- in_review
- approved
- completed
- urgent

---

## Requests de Validação

### StoreSecretaryRequestRequest

- `type`: required, in: tipos permitidos
- `priority`: required, in: low, normal, high, urgent
- `title`: required, string, max:255
- `description`: nullable, string
- `requester_person_id`: nullable, exists:people,id
- `related_alert_id`: nullable, exists:system_alerts,id
- `assigned_to_user_id`: nullable, exists:users,id
- `due_at`: nullable, date, after:now
- `internal_notes`: nullable, string

### UpdateSecretaryRequestRequest

- `title`: required, string, max:255
- `description`: nullable, string
- `priority`: required, in: low, normal, high, urgent
- `requester_person_id`: nullable, exists:people,id
- `related_alert_id`: nullable, exists:system_alerts,id
- `assigned_to_user_id`: nullable, exists:users,id
- `due_at`: nullable, date, after:now
- `internal_notes`: nullable, string

---

## Rotas

### Caminho

`routes/web.php`

### Rotas Protegidas por Auth

- `GET /secretaria/solicitacoes` - secretaria.requests.index
- `GET /secretaria/solicitacoes/criar` - secretaria.requests.create
- `POST /secretaria/solicitacoes` - secretaria.requests.store
- `GET /secretaria/solicitacoes/{secretaryRequest}` - secretaria.requests.show
- `GET /secretaria/solicitacoes/{secretaryRequest}/editar` - secretaria.requests.edit
- `PUT/PATCH /secretaria/solicitacoes/{secretaryRequest}` - secretaria.requests.update
- `PATCH /secretaria/solicitacoes/{secretaryRequest}/em-analise` - secretaria.requests.mark-in-review
- `PATCH /secretaria/solicitacoes/{secretaryRequest}/aprovar` - secretaria.requests.approve
- `PATCH /secretaria/solicitacoes/{secretaryRequest}/rejeitar` - secretaria.requests.reject
- `PATCH /secretaria/solicitacoes/{secretaryRequest}/concluir` - secretaria.requests.complete
- `PATCH /secretaria/solicitacoes/{secretaryRequest}/cancelar` - secretaria.requests.cancel

---

## Páginas Vue

### Index.vue

**Caminho:** `resources/js/Pages/Secretaria/Requests/Index.vue`

**Funcionalidades:**
- Título: "Solicitações da Secretaria"
- Descrição: "Área para acompanhar pedidos, revisões e decisões antes de alterar dados oficiais."
- Cards com contagens: Pendentes, Em análise, Aprovadas, Concluídas, Urgentes
- Filtros por status, tipo e prioridade
- Tabela responsiva sem corte de texto
- Botão "Nova Solicitação"
- Botão "Ver" para cada solicitação
- Botão "Editar" quando permitido

### Create.vue

**Caminho:** `resources/js/Pages/Secretaria/Requests/Create.vue`

**Funcionalidades:**
- Formulário para criar solicitação interna
- Campos: tipo, prioridade, título, descrição, pessoa relacionada, alerta relacionado, responsável, prazo, observações internas
- Texto de ajuda baseado no tipo selecionado
- Se vier alert_id na query string, preenche automaticamente:
  - Tipo: alert_resolution_review
  - Título e descrição a partir do alerta
  - related_alert_id
- Botão "Criar Solicitação"
- Botão "Voltar"

### Show.vue

**Caminho:** `resources/js/Pages/Secretaria/Requests/Show.vue`

**Funcionalidades:**
- Mostra título, status, prioridade, tipo, descrição
- Mostra pessoa relacionada (se houver)
- Mostra alerta relacionado (se houver)
- Mostra responsável pela análise (se houver)
- Mostra prazo (se houver)
- Mostra histórico de datas: criado em, enviado em, revisado em, aprovado em, rejeitado em, concluído em
- Mostra current_snapshot (se existir)
- Mostra requested_changes (se existir)
- Mostra observações internas
- Mostra decisão/observação final
- Ações disponíveis:
  - Marcar em análise (se pending)
  - Aprovar (se pending ou in_review)
  - Rejeitar (se pending ou in_review)
  - Concluir (se approved ou in_review)
  - Cancelar (se pending ou in_review)
- Cada ação exige observação/justificativa
- Botão "Voltar"

### Edit.vue

**Caminho:** `resources/js/Pages/Secretaria/Requests/Edit.vue`

**Funcionalidades:**
- Formulário para editar dados básicos
- Pode editar: título, descrição, prioridade, pessoa relacionada, alerta relacionado, responsável, prazo, observações internas
- Não permite editar se concluída, rejeitada ou cancelada
- Botão "Salvar Alterações"
- Botão "Cancelar"

---

## Menu

### Link Adicionado

- "Solicitações" no menu autenticado principal
- "Solicitações" no menu responsivo

### Links Mantidos

- Dashboard
- Secretaria
- Alertas
- Pessoas
- Famílias
- Responsáveis

---

## Integração com Painel da Secretaria

### Atualizações no Controller

**SecretaryDashboardController:**
- Adicionou contagem de solicitações pendentes
- Adicionou contagem de solicitações urgentes
- Adicionou contagem de solicitações em análise

### Atualizações no Dashboard

**Dashboard.vue:**
- Adicionou cards de solicitações:
  - "Solicitações Pendentes"
  - "Solicitações em Análise"
  - "Solicitações Urgentes"
- Cada card tem link para página de solicitações
- Cores dinâmicas baseadas nos contadores

---

## Integração com Alertas

### Botão na Tela de Resolução do Alerta

**Resolve.vue:**
- Adicionado botão "Criar solicitação de revisão"
- Navega para: `secretaria.requests.create` com `alert_id` como query parameter
- Botão posicionado no header do alerta

### Comportamento no Create

**Create.vue:**
- Se vier `alert_id` na query string:
  - Busca o alerta com pessoa relacionada
  - Preenche tipo como `alert_resolution_review`
  - Preenche título e descrição a partir do alerta
  - Vincula `related_alert_id`
  - Preenche `requester_person_id` se houver pessoa relacionada

---

## Regras de Segurança

### Não Altera Dados Oficiais Automaticamente

Exemplo:
- Se uma solicitação pede troca de telefone, não atualizar `people.primary_phone` automaticamente
- A Secretaria deve editar o cadastro no módulo correto e depois concluir a solicitação

### Snapshots

- `current_snapshot`: Estado atual conhecido do registro
- `requested_changes`: Mudanças solicitadas pelo solicitante
- Não há editor avançado de JSON na tela nesta etapa

---

## Integração Futura

### Área do Membro

- Pedido de alteração cadastral vindo do membro
- Cadastro online pendente
- Revisão de dados pessoais

### Cadastro Online

- Formulário público para cadastro
- Cria solicitação automaticamente
- Secretaria analisa antes de aprovar

---

## Diferença entre Alerta e Solicitação

### Alerta

- Gerado automaticamente pelo sistema
- Baseado em regras e verificações
- Indica problema que precisa atenção
- Exige ação corretiva
- Tem verificação automática de resolução

### Solicitação

- Criado manualmente ou via formulário
- Baseado em pedido ou necessidade
- Indica pedido de alteração ou revisão
- Exige análise e decisão
- Não altera dados automaticamente
- Pode vir de membros no futuro

---

## Fluxo de Resolução de Alerta vs Solicitação

### Alerta

1. Sistema detecta problema
2. Cria alerta automaticamente
3. Secretaria vê alerta
4. Clica em "Tratar"
5. Abre tela de resolução
6. Corrige problema no módulo correto
7. Clica em "Verificar resolução"
8. Sistema verifica se problema foi resolvido
9. Se resolvido, marca como "resolved"

### Solicitação

1. Secretaria ou membro cria solicitação
2. Solicitação fica como "pending"
3. Secretaria marca como "in_review" (opcional)
4. Secretaria aprova, rejeita, cancela ou conclui
5. Se aprovada, Secretaria aplica alteração no módulo correto
6. Secretaria marca como "completed"
7. Solicitação fica como histórico

---

## Validação

### Comandos

```bash
php artisan optimize:clear
php artisan route:list --path=secretaria/solicitacoes
php artisan route:list --path=secretaria/alertas
php artisan route:list --path=secretaria
php artisan test
npm run build
git status
```

### Verificar Duplicidades

```bash
find app resources database routes -iname "*Novo*" -o -iname "*Final*" -o -iname "*Corrigido*" -o -iname "*V2*" -o -iname "*Backup*" -o -iname "*Copy*"
```

---

## Teste Manual no Navegador

### Testar Solicitações

1. Abrir: `http://127.0.0.1:8000/secretaria/solicitacoes`
2. Clicar em "Nova Solicitação"
3. Preencher formulário
4. Salvar
5. Ver detalhes
6. Marcar em análise
7. Aprovar com observação
8. Concluir com observação
9. Criar outra solicitação
10. Rejeitar com observação
11. Criar outra solicitação
12. Cancelar com observação
13. Testar filtros por status, tipo e prioridade
14. Confirmar que solicitação concluída não permite edição

### Testar Integração com Alertas

1. Abrir um alerta
2. Clicar em "Criar solicitação de revisão"
3. Confirmar que a tela de criação já vem com dados do alerta
4. Salvar
5. Ver se a solicitação ficou vinculada ao alerta

### Testar Painel

1. Abrir: `http://127.0.0.1:8000/secretaria`
2. Confirmar que cards de solicitações aparecem
3. Confirmar que números fazem sentido
4. Confirmar que link para solicitações funciona

---

## Entrega Final

### Migration

- ✅ Migration criada: `2026_05_10_135436_create_secretary_requests_table.php`

### Model

- ✅ Model criado: `app/Models/SecretaryRequest.php`

### Controller

- ✅ Controller criado: `app/Http/Controllers/SecretaryRequestController.php`

### Requests

- ✅ StoreSecretaryRequestRequest criado
- ✅ UpdateSecretaryRequestRequest criado

### Rotas

- ✅ Rotas criadas em `routes/web.php`

### Menu

- ✅ Link "Solicitações" adicionado ao menu principal
- ✅ Link "Solicitações" adicionado ao menu responsivo

### Páginas Vue

- ✅ Index.vue criado
- ✅ Create.vue criado
- ✅ Show.vue criado
- ✅ Edit.vue criado

### Dashboard

- ✅ SecretaryDashboardController atualizado com contagens de solicitações
- ✅ Dashboard.vue atualizado com cards de solicitações

### Integração com Alertas

- ✅ Botão "Criar solicitação de revisão" adicionado em Resolve.vue
- ✅ Create.vue preenche dados automaticamente quando vem de alerta

### Documentação

- ✅ DOCUMENTO_SOLICITACOES_SECRETARIA.md criado

### Regras de Segurança

- ✅ Não altera dados oficiais automaticamente
- ✅ Solicitação não é o dado oficial
- ✅ Secretaria deve aplicar alteração manualmente

### Fluxo de Status

- ✅ Fluxo implementado corretamente
- ✅ Transições permitidas e proibidas definidas
- ✅ decision_notes obrigatório em ações de estado

---

## Resumo

A ETAPA 6 implementou o módulo de Solicitações e Revisões da Secretaria, permitindo que a Secretaria registre, acompanhe e trate solicitações antes de alterar dados oficiais. O módulo inclui:

- Tabela `secretary_requests` com todos os campos necessários
- Model `SecretaryRequest` com relacionamentos e métodos de estado
- Controller `SecretaryRequestController` com todos os métodos CRUD e de fluxo
- Requests de validação para criação e atualização
- Rotas protegidas por autenticação
- Menu atualizado com link para solicitações
- Páginas Vue completas (Index, Create, Show, Edit)
- Integração com dashboard com cards de contagem
- Integração com alertas para criar solicitações de revisão
- Documentação completa

O módulo segue as regras de segurança: não altera dados oficiais automaticamente, exige decisão manual da Secretaria, e mantém histórico completo de todas as ações.
