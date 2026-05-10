# DOCUMENTO_ALERTAS_SECRETARIA

## Alertas Internos da Secretaria
**Etapa 5 do Projeto Ministério Resgate / Família Resgate**

Este documento descreve o sistema de alertas internos da Secretaria para acompanhamento de pendências e situações que requerem atenção.

---

## Visão Geral

O sistema de alertas internos da Secretaria é uma ferramenta para transformar as atenções do painel em itens acompanháveis. A Secretaria pode ver, acompanhar e resolver pendências através de alertas que são gerados automaticamente com base nas regras do sistema.

**Importante:** Esta etapa cria apenas alertas internos da Secretaria. Não há notificação externa (e-mail, WhatsApp, push) nesta etapa.

---

## Tipos de Alertas

### 1. Criança Próxima dos 11 Anos
- **Type:** `child_turning_11`
- **Severity:** `low` (informativo)
- **Status:** `pending`
- **Descrição:** Crianças menores de 11 anos que completarão 11 anos nos próximos 60 dias
- **Ação sugerida:** Revise o cadastro para possível transição para Júnior
- **Regra dos 11 anos:** Ao completar 11 anos, a Secretaria deve revisar o cadastro. A responsabilidade legal NÃO acaba automaticamente, mas a regra do sistema muda (fase Júnior com possível usuário supervisionado)

### 2. Menor sem Responsável Ativo
- **Type:** `minor_without_guardian`
- **Severity:** `critical` (perigo)
- **Status:** `pending`
- **Descrição:** Pessoas menores de 18 anos sem responsável legal ativo
- **Ação sugerida:** Vincular um responsável legal
- **Urgência:** Alta - menores sem responsável precisam de atenção imediata

### 3. Pessoa sem Família
- **Type:** `person_without_family`
- **Severity:** `medium` (atenção)
- **Status:** `pending`
- **Descrição:** Pessoas que não possuem vínculo familiar ativo
- **Ação sugerida:** Vincular a uma família existente
- **Urgência:** Média - importante para organização

### 4. Cadastro Incompleto
- **Type:** `incomplete_registration`
- **Severity:** `medium` (atenção)
- **Status:** `pending`
- **Descrição:** Pessoas com cadastro incompleto
  - Sem data de nascimento
  - Sem telefone principal
  - Sem email e sem telefone
- **Ação sugerida:** Completar o cadastro com informações faltantes
- **Urgência:** Média - importante para contato e cálculo de idade

**Nota:** NIF e email não são obrigatórios para todos. Não tratamos falta de NIF como erro grave nesta etapa.

### 5. Responsabilidade com Data de Fim Próxima
- **Type:** `guardianship_ending_soon`
- **Severity:** `medium` (atenção)
- **Status:** `pending`
- **Descrição:** Responsabilidades ativas com data de fim nos próximos 30 dias
- **Ação sugerida:** Revise se deve ser estendida ou encerrada
- **Urgência:** Média - planejamento necessário

### 6. Responsabilidade Vencida
- **Type:** `guardianship_expired`
- **Severity:** `critical` (perigo)
- **Status:** `pending`
- **Descrição:** Responsabilidades ativas com data de fim menor que hoje
- **Ação sugerida:** Revise se deve ser estendida ou encerrada
- **Urgência:** Alta - responsabilidade vencida precisa de atenção

---

## Severidade

### Low (Informativo)
- **Cor:** Azul
- **Uso:** Alertas informativos que não exigem ação imediata
- **Exemplo:** Criança completando 11 anos em breve

### Medium (Atenção)
- **Cor:** Amarelo
- **Uso:** Alertas que requerem atenção mas não são urgentes
- **Exemplo:** Pessoa sem família, cadastro incompleto

### High (Alto)
- **Cor:** Vermelho
- **Uso:** Alertas importantes que devem ser tratados
- **Exemplo:** Responsabilidade com data próxima

### Critical (Crítico)
- **Cor:** Vermelho escuro
- **Uso:** Alertas urgentes que requerem ação imediata
- **Exemplo:** Menor sem responsável, responsabilidade vencida

---

## Status

### Pending (Aberto)
- **Mapeado da tabela existente:** `pending`
- **Descrição:** Alerta aberto, aguardando ação da Secretaria
- **Ações possíveis:** Tratar (abre tela de resolução), Ignorar

### In Progress (Em andamento)
- **Mapeado da tabela existente:** `in_progress`
- **Descrição:** Alerta em tratamento pela Secretaria
- **Ações possíveis:** Continuar tratamento, Verificar resolução, Ignorar

### Resolved (Resolvido)
- **Mapeado da tabela existente:** `resolved`
- **Descrição:** Alerta resolvido pela Secretaria após validação real
- **Campos preenchidos:** `resolved_at`, `resolved_by_user_id`, `resolution_notes`
- **Nota:** Só pode ser marcado como resolvido após o problema real ser corrigido e validado pelo sistema

### Dismissed (Ignorado)
- **Mapeado da tabela existente:** `dismissed`
- **Descrição:** Alerta ignorado pela Secretaria
- **Campos preenchidos:** `resolved_at`, `resolved_by_user_id`, `resolution_notes`
- **Nota:** Ignorar exige motivo obrigatório (resolution_notes)

---

## Regra de Unicidade

Para evitar duplicação de alertas abertos, o sistema usa a seguinte regra:

**Unicidade lógica:** `type + related_person_id + status pending`

Se já existe um alerta aberto do mesmo tipo para a mesma pessoa, o sistema:
- Atualiza o alerta existente (title, message, severity)
- Não cria um novo alerta

**Exemplo:**
- Se existe alerta `child_turning_11` para pessoa ID 5 com status `pending`
- Ao regenerar alertas, o sistema atualiza esse alerta em vez de criar outro

---

## Tabela SystemAlerts

A tabela `system_alerts` já existia no projeto. Foi ajustada para adicionar o campo `resolution_notes`.

### Campos principais:
- `id`: Identificador único
- `type`: Tipo do alerta (string)
- `title`: Título do alerta
- `message`: Mensagem detalhada
- `related_person_id`: Pessoa relacionada (nullable)
- `related_family_id`: Família relacionada (nullable)
- `severity`: Severidade (enum: low, medium, high, critical)
- `status`: Status (enum: pending, in_progress, resolved, dismissed)
- `due_date`: Data limite para resolução (nullable)
- `resolved_at`: Data de resolução (nullable)
- `resolved_by_user_id`: Usuário que resolveu (nullable)
- `resolution_notes`: Observações de resolução (text, nullable) - **Adicionado na Etapa 5**
- `created_at`: Data de criação (usado como detected_at)
- `updated_at`: Data de atualização

### Índices:
- `type`
- `status`
- `severity`
- `due_date`
- `related_person_id`
- `related_family_id`

---

## Model SystemAlert

### Métodos principais:
- `isPending()`: Verifica se o alerta está aberto (status pending)
- `isInProgress()`: Verifica se o alerta está em andamento
- `isResolved()`: Verifica se o alerta foi resolvido
- `isIgnored()`: Verifica se o alerta foi ignorado (status dismissed)
- `markAsResolved(int $userId, ?string $notes = null)`: Marca o alerta como resolvido
- `markAsIgnored(int $userId, ?string $notes = null)`: Marca o alerta como ignorado

### Relacionamentos:
- `relatedPerson`: BelongsTo Person
- `relatedFamily`: BelongsTo Family
- `resolvedBy`: BelongsTo User

---

## Service SecretaryAlertService

O service `SecretaryAlertService` é responsável por gerar alertas automaticamente com base nas regras do sistema.

### Métodos:
- `regenerateAlerts()`: Regera todos os alertas com base nas regras atuais
- `removeDuplicatePendingAlerts()`: Remove alertas pending duplicados mantendo o mais recente
- `createOrUpdatePendingAlert(array $data)`: Cria ou atualiza alerta pending sem duplicar
- `isAlertActuallyResolved(SystemAlert $alert): array`: Verifica se um alerta foi realmente resolvido
- `generateChildTurning11Alerts()`: Gera alertas para crianças próximas dos 11 anos
- `generateMinorWithoutGuardianAlerts()`: Gera alertas para menores sem responsável
- `generatePersonWithoutFamilyAlerts()`: Gera alertas para pessoas sem família
- `generateIncompleteRegistrationAlerts()`: Gera alertas para cadastros incompletos
- `generateGuardianshipEndingSoonAlerts()`: Gera alertas para responsabilidades terminando
- `generateGuardianshipExpiredAlerts()`: Gera alertas para responsabilidades vencidas

### Regras de geração:
- Não duplica alertas abertos iguais
- Atualiza alertas existentes se necessário
- Usa dados reais do banco (sem seeders fake)
- Trata dados nulos de forma segura
- Agrupa campos faltantes em um único alerta por pessoa para cadastro incompleto

---

## Fluxo de Resolução de Alertas

O sistema de alertas exige que o problema real seja corrigido antes de marcar o alerta como resolvido.

### Fluxo Correto

1. **Usuário clica em "Tratar"** na lista de alertas
2. **Sistema abre tela de resolução** do alerta
3. **Usuário corrige o problema real** no cadastro correspondente usando as ações contextuais
4. **Usuário volta à tela de resolução** e clica em "Verificar resolução"
5. **Sistema valida se o problema foi corrigido** através de verificação real no banco
6. **Se corrigido:** Marca como resolved com resolution_notes obrigatório
7. **Se não corrigido:** Mantém em pending/in_progress com mensagem explicativa

### Status de Resolução

- **Pending (Aberto):** Alerta ainda não foi tratado
- **In Progress (Em andamento):** Alerta está sendo tratado
- **Resolved (Resolvido):** Problema foi corrigido e validado pelo sistema
- **Dismissed (Ignorado):** Secretaria decidiu conscientemente não tratar (exige motivo)

### Regras de Verificação por Tipo

#### incomplete_registration
- Só resolve se os campos faltantes foram preenchidos
- Se faltava telefone: primary_phone deve estar preenchido
- Se faltava email: email deve estar preenchido OU primary_phone deve estar preenchido
- Se faltava data de nascimento: birth_date deve estar preenchido

#### person_without_family
- Só resolve se a pessoa tiver vínculo familiar ativo
- Verifica: family_members.left_at = null

#### minor_without_guardian
- Só resolve se o menor tiver guardianship ativo
- Verifica: guardianships.status = 'active' para o menor

#### child_turning_11
- Este alerta é de revisão manual
- Ao clicar em verificar resolução, considera como confirmado
- Não cria usuário automaticamente
- Não cria membro automaticamente
- Não move para Resgatados automaticamente

#### guardianship_ending_soon
- Só resolve se:
  - A responsabilidade foi prorrogada para data > 30 dias
  - OU a responsabilidade foi encerrada (status não é mais active)
  - OU ends_at foi atualizado para data futura válida
- Se ainda está ativo e ends_at nos próximos 30 dias: não resolve

#### guardianship_expired
- Só resolve se:
  - status não é mais active
  - OU ends_at foi atualizado para data futura válida
  - OU responsabilidade foi encerrada formalmente
- Se status active e ends_at continua vencido: não resolve

---

## Páginas Vue

### Alerts/Index.vue
- Lista todos os alertas com filtros por status, tipo e severidade
- Mostra resumo por status (abertos, resolvidos, ignorados)
- Mostra resumo por severidade (informativos, atenção, urgentes)
- Botão "Tratar" abre tela de resolução (não resolve diretamente)
- Botão "Ignorar" exige motivo
- Tabela responsiva sem corte de texto

### Alerts/Show.vue
- Mostra detalhes do alerta
- Exibe pessoa e família relacionadas
- Mostra informações de resolução (se resolvido)

### Alerts/Resolve.vue
- Tela de resolução/tratamento do alerta
- Mostra instruções claras sobre o fluxo
- Exibe ações contextuais baseadas no tipo de alerta
- Botão "Marcar em andamento" (opcional)
- Botão "Verificar resolução" (obrigatório para resolver)
- Botão "Ignorar" (exige motivo obrigatório)
- Campo resolution_notes obrigatório para verificar resolução e ignorar

---

## Integração com Painel da Secretaria

O painel da Secretaria foi atualizado para mostrar:
- Card "Alertas Abertos" com contagem de alertas pendentes
- Card "Alertas Urgentes" com contagem de alertas críticos
- Link "Ver alertas" para acessar a página de alertas

### Atualizações:
- `SecretaryDashboardController`: Adicionou contagem de alertas abertos e urgentes
- `Dashboard.vue`: Adicionou card de alertas com link para página de alertas

---

## Regras Importantes

### Não Apagar Alerta ao Resolver
- Resolver um alerta NÃO o apaga do histórico
- O alerta permanece no banco com status `resolved`
- Isso permite auditoria e acompanhamento

### Resolver vs Ignorar
- **Resolver:** Usado quando a situação foi tratada
- **Ignorar:** Usado quando a situação não requer ação ou foi tratada de outra forma
- Ambos preenchem `resolved_at`, `resolved_by_user_id` e `resolution_notes`

### Histórico Preservado
- Alertas resolvidos permanecem no histórico
- Alertas ignorados permanecem no histórico
- Não há exclusão de alertas

---

## Não Implementado Nesta Etapa

- ❌ Sistema completo de notificações externas (e-mail, WhatsApp, push)
- ❌ Alertas automáticos em tempo real
- ❌ Tarefas recorrentes avançadas
- ❌ IA para análise de alertas
- ❌ Notificações por e-mail
- ❌ Notificações push
- ❌ WhatsApp automático
- ❌ Sistema complexo de priorização

---

## Próximos Passos Futuros

1. **Sistema de Notificações Externas**
   - E-mail automático para alertas críticos
   - WhatsApp para alertas urgentes
   - Push notifications para usuários

2. **Alertas Automáticos em Tempo Real**
   - Eventos quando uma pessoa é cadastrada
   - Eventos quando uma responsabilidade é criada
   - Eventos quando uma data limite é atingida

3. **Tarefas Recorrentes**
   - Agendar verificação diária de alertas
   - Agendar verificação semanal de pendências
   - Sistema de follow-up automático

4. **IA e Análise**
   - Análise de padrões em alertas
   - Sugestões automáticas de resolução
   - Priorização inteligente

---

## Status da Implementação

- ✅ Migration para adicionar `resolution_notes` criada e executada
- ✅ Model SystemAlert ajustado com métodos adicionais
- ✅ Service SecretaryAlertService criado com geração de alertas
- ✅ Controller SecretaryAlertController criado com CRUD básico
- ✅ Rotas criadas (index, show, resolve, ignore, regenerate)
- ✅ Menu atualizado com link "Alertas"
- ✅ Página Alerts/Index.vue criada
- ✅ Página Alerts/Show.vue criada
- ✅ Dashboard.vue atualizado com card de alertas
- ✅ SecretaryDashboardController atualizado com dados de alertas
- ✅ Regra de unicidade implementada
- ✅ Histórico preservado
- ✅ Acesso seguro para dados nulos

---

## Comandos Úteis

### Regenerar alertas manualmente:
```bash
php artisan tinker
>>> $service = new \App\Services\Secretaria\SecretaryAlertService();
>>> $service->regenerateAlerts();
```

### Ver alertas no banco:
```sql
SELECT * FROM system_alerts WHERE status = 'pending' ORDER BY created_at DESC;
```

### Ver alertas por tipo:
```sql
SELECT type, COUNT(*) as count FROM system_alerts GROUP BY type;
```

### Ver alertas por severidade:
```sql
SELECT severity, COUNT(*) as count FROM system_alerts GROUP BY severity;
```
