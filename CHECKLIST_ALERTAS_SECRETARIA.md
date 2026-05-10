# CHECKLIST_ALERTAS_SECRETARIA

## Checklist de Implementação - Alertas Internos da Secretaria
**Etapa 5 do Projeto Ministério Resgate / Família Resgate**

---

## Banco de Dados

### Tabela SystemAlerts
- [x] Verificar se tabela system_alerts já existe
- [x] Criar migration para adicionar campo resolution_notes
- [x] Executar migration
- [x] Verificar estrutura da tabela

---

## Model

### SystemAlert
- [x] Adicionar resolution_notes ao fillable
- [x] Adicionar método isOpen()
- [x] Adicionar método isIgnored()
- [x] Atualizar método markAsResolved() para incluir resolution_notes
- [x] Adicionar método markAsIgnored()
- [x] Verificar relacionamentos (relatedPerson, relatedFamily, resolvedBy)

---

## Service

### SecretaryAlertService
- [x] Criar service em app/Services/Secretaria/SecretaryAlertService.php
- [x] Implementar método regenerateAlerts()
- [x] Implementar método generateChildTurning11Alerts()
  - [x] Buscar crianças menores de 11 anos que completarão 11 nos próximos 60 dias
  - [x] Verificar se já existe alerta aberto para evitar duplicação
  - [x] Criar ou atualizar alerta
- [x] Implementar método generateMinorWithoutGuardianAlerts()
  - [x] Buscar menores de 18 anos sem responsável ativo
  - [x] Verificar se já existe alerta aberto para evitar duplicação
  - [x] Criar ou atualizar alerta
- [x] Implementar método generatePersonWithoutFamilyAlerts()
  - [x] Buscar pessoas sem vínculo familiar
  - [x] Verificar se já existe alerta aberto para evitar duplicação
  - [x] Criar ou atualizar alerta
- [x] Implementar método generateIncompleteRegistrationAlerts()
  - [x] Buscar pessoas sem data de nascimento
  - [x] Buscar pessoas sem telefone principal
  - [x] Buscar pessoas sem email e sem telefone
  - [x] Verificar se já existe alerta aberto para evitar duplicação
  - [x] Criar ou atualizar alerta
- [x] Implementar método generateGuardianshipEndingSoonAlerts()
  - [x] Buscar responsabilidades ativas com ends_at nos próximos 30 dias
  - [x] Verificar se já existe alerta aberto para evitar duplicação
  - [x] Criar ou atualizar alerta
- [x] Implementar método generateGuardianshipExpiredAlerts()
  - [x] Buscar responsabilidades ativas com ends_at menor que hoje
  - [x] Verificar se já existe alerta aberto para evitar duplicação
  - [x] Criar ou atualizar alerta
- [x] Usar dados reais (sem seeders fake)
- [x] Tratar dados nulos de forma segura

---

## Controller

### SecretaryAlertController
- [x] Criar controller em app/Http/Controllers/SecretaryAlertController.php
- [x] Injetar SecretaryAlertService no construtor
- [x] Implementar método index()
  - [x] Buscar alertas com relacionamentos
  - [x] Aplicar filtros por status
  - [x] Aplicar filtros por tipo
  - [x] Aplicar filtros por severidade
  - [x] Calcular contagens por status
  - [x] Calcular contagens por severidade
  - [x] Retornar dados formatados para Vue
- [x] Implementar método show()
  - [x] Buscar alerta com relacionamentos
  - [x] Retornar dados formatados para Vue
- [x] Implementar método resolve()
  - [x] Validar resolution_notes
  - [x] Chamar markAsResolved() com userId e notes
  - [x] Redirecionar com mensagem de sucesso
- [x] Implementar método ignore()
  - [x] Validar resolution_notes
  - [x] Chamar markAsIgnored() com userId e notes
  - [x] Redirecionar com mensagem de sucesso
- [x] Implementar método regenerate()
  - [x] Chamar regenerateAlerts() do service
  - [x] Redirecionar com mensagem de sucesso

---

## Rotas

### Rotas de Alertas
- [x] Adicionar import do SecretaryAlertController em routes/web.php
- [x] Criar rota GET /secretaria/alertas (secretaria.alerts.index)
- [x] Criar rota GET /secretaria/alertas/{systemAlert} (secretaria.alerts.show)
- [x] Criar rota PATCH /secretaria/alertas/{systemAlert}/resolver (secretaria.alerts.resolve)
- [x] Criar rota PATCH /secretaria/alertas/{systemAlert}/ignorar (secretaria.alerts.ignore)
- [x] Criar rota POST /secretaria/alertas/regenerar (secretaria.alerts.regenerate)
- [x] Proteger rotas com middleware auth
- [x] Não quebrar rota existente /secretaria

---

## Menu

### Menu Autenticado
- [x] Adicionar link "Alertas" no menu desktop
- [x] Adicionar link "Alertas" no menu responsivo
- [x] Não remover links existentes (Dashboard, Secretaria, Pessoas, Famílias, Responsáveis)
- [x] Usar rota secretaria.alerts.index

---

## Páginas Vue

### Alerts/Index.vue
- [x] Criar página em resources/js/Pages/Secretaria/Alerts/Index.vue
- [x] Receber props: alerts, counts, filters
- [x] Mostrar resumo por status (abertos, resolvidos, ignorados)
- [x] Mostrar resumo por severidade (informativos, atenção, urgentes)
- [x] Implementar filtros por status
- [x] Implementar filtros por tipo
- [x] Implementar filtros por severidade
- [x] Mostrar lista de alertas em tabela
- [x] Implementar botão "Ver" para detalhes
- [x] Implementar botão "Resolver" para alertas abertos
- [x] Implementar botão "Ignorar" para alertas abertos
- [x] Implementar botão "Regenerar Alertas"
- [x] Usar Inertia para navegação
- [x] Usar useForm para ações
- [x] Tratar dados nulos de forma segura

### Alerts/Show.vue
- [x] Criar página em resources/js/Pages/Secretaria/Alerts/Show.vue
- [x] Receber props: alert
- [x] Mostrar detalhes completos do alerta
- [x] Mostrar informações (tipo, severidade, status, datas)
- [x] Mostrar pessoa relacionada se existir
- [x] Mostrar família relacionada se existir
- [x] Mostrar observações de resolução se existirem
- [x] Implementar botão "Resolver" para alertas abertos
- [x] Implementar botão "Ignorar" para alertas abertos
- [x] Adicionar campo de observações para resolução
- [x] Usar Inertia para navegação
- [x] Usar useForm para ações
- [x] Não deixar tela branca se dados forem nulos

---

## Integração com Painel da Secretaria

### Dashboard.vue
- [x] Adicionar props: open_alerts, urgent_alerts
- [x] Adicionar card "Alertas Abertos"
- [x] Adicionar card "Alertas Urgentes"
- [x] Adicionar link "Ver alertas" para secretaria.alerts.index
- [x] Não transformar dashboard em tela gigante

### SecretaryDashboardController
- [x] Adicionar import do model SystemAlert
- [x] Adicionar contagem de alertas abertos (status pending)
- [x] Adicionar contagem de alertas urgentes (status pending, severity high/critical)
- [x] Passar dados para Vue

---

## Documentação

### Documentos Criados
- [x] Criar DOCUMENTO_ALERTAS_SECRETARIA.md
  - [x] Documentar visão geral
  - [x] Documentar tipos de alertas
  - [x] Documentar severidades
  - [x] Documentar status
  - [x] Documentar regra de unicidade
  - [x] Documentar tabela system_alerts
  - [x] Documentar model SystemAlert
  - [x] Documentar service SecretaryAlertService
  - [x] Documentar controller SecretaryAlertController
  - [x] Documentar páginas Vue
  - [x] Documentar integração com painel
  - [x] Documentar regras importantes
  - [x] Documentar o que não foi implementado
  - [x] Documentar próximos passos futuros
- [x] Criar CHECKLIST_ALERTAS_SECRETARIA.md

### Documentos Atualizados
- [ ] Atualizar DOCUMENTO_SECRETARIA.md
- [ ] Atualizar CHECKLIST_SECRETARIA.md
- [ ] Atualizar DOCUMENTO_BANCO_DADOS_INICIAL.md
- [ ] Atualizar DOCUMENTO_ARQUITETURA_INICIAL.md
- [ ] Atualizar CHECKLIST_INICIAL.md

---

## Validação de Dados

### Acesso Seguro
- [x] Não quebrar se não houver alertas
- [x] Não quebrar se related_person for null
- [x] Não quebrar se related_family for null
- [x] Não quebrar se resolved_by for null
- [x] Não deixar tela branca se dados forem nulos

### Tratamento de Nulos
- [x] Usar optional chaining em PHP
- [x] Usar conditional rendering em Vue
- [x] Fornecer valores padrão
- [x] Verificar antes de acessar propriedades

---

## Regras de Negócio

### Tipos de Alertas Implementados
- [x] child_turning_11 (Criança completando 11 anos)
- [x] minor_without_guardian (Menor sem responsável)
- [x] person_without_family (Pessoa sem família)
- [x] incomplete_registration (Cadastro incompleto)
- [x] guardianship_ending_soon (Responsabilidade terminando)
- [x] guardianship_expired (Responsabilidade vencida)

### Severidade
- [x] low (Informativo)
- [x] medium (Atenção)
- [x] high (Alto)
- [x] critical (Crítico)

### Status
- [x] pending (Aberto)
- [x] resolved (Resolvido)
- [x] dismissed (Ignorado)

### Regra dos 11 Anos
- [x] Alerta informativo para crianças próximas dos 11 anos
- [x] Responsabilidade NÃO acaba automaticamente
- [x] Revisão necessária ao completar 11 anos

### invited_by_person_id
- [x] Preservado para evangelismo/pontuação futura
- [x] Não implementar pontuação nesta etapa
- [x] Não implementar ranking nesta etapa

---

## Não Implementado

### Módulos Proibidos
- [x] Não criar módulo financeiro
- [x] Não criar módulo cantina
- [x] Não criar módulo louvor
- [x] Não criar módulo jovens visual
- [x] Não criar cadastro online
- [x] Não criar pontuação
- [x] Não criar gamificação
- [x] Não criar app mobile

### Funcionalidades Proibidas
- [x] Não criar notificação automática complexa
- [x] Não criar envio por e-mail
- [x] Não criar push notifications
- [x] Não criar WhatsApp automático
- [x] Não criar tarefas recorrentes avançadas
- [x] Não criar IA

### Regras de Dados
- [x] Não usar seeders fake
- [x] Não criar dados fictícios
- [x] Usar dados reais do banco

---

## Validações e Comandos

### Comandos a Executar
- [ ] php artisan optimize:clear
- [ ] php artisan route:list --path=secretaria
- [ ] php artisan route:list --path=secretaria/alertas
- [ ] php artisan test
- [ ] npm run build
- [ ] git status

### Verificação de Duplicidade
- [ ] find app resources database routes -iname "*Novo*" -o -iname "*Final*" -o -iname "*Corrigido*" -o -iname "*V2*" -o -iname "*Backup*" -o -iname "*Copy*"

---

## Teste Manual no Navegador

### Página de Alertas
- [ ] Acessar http://127.0.0.1:8000/secretaria/alertas
- [ ] Verificar se página abre sem erro
- [ ] Verificar se mostra alertas abertos
- [ ] Verificar se botão regenerar funciona
- [ ] Verificar se botão resolver funciona
- [ ] Verificar se botão ignorar funciona
- [ ] Verificar se show abre
- [ ] Verificar se filtros funcionam
- [ ] Verificar se não duplica alerta ao regenerar mais de uma vez

### Painel da Secretaria
- [ ] Acessar http://127.0.0.1:8000/secretaria
- [ ] Verificar se painel ainda abre
- [ ] Verificar se cards de alertas aparecem
- [ ] Verificar se link para alertas funciona

---

## Integração com Solicitações (Etapa 6)

### Botão "Criar solicitação de revisão"
- [ ] Botão adicionado em Resolve.vue
- [ ] Botão posicionado no header do alerta
- [ ] Navega para secretaria.requests.create com alert_id
- [ ] Create.vue preenche dados automaticamente quando vem de alerta

### Documentação Adicional
- [ ] DOCUMENTO_SOLICITACOES_SECRETARIA.md criado
- [ ] CHECKLIST_SOLICITACOES_SECRETARIA.md criado

---

## Commit

### Commit
- [ ] git add .
- [ ] git commit -m "feat: criar alertas internos da secretaria"

---

## Status Final

- [ ] Todos os itens do checklist concluídos
- [ ] Validações passaram
- [ ] Teste manual passou
- [ ] Commit feito
