# Plano Técnico de Backend da Minha Caminhada

## 1. Visão geral

A Minha Caminhada será o módulo de acompanhamento saudável da vida na igreja dentro da Família Resgate. O backend real deve transformar os protótipos visuais atuais em dados oficiais, auditáveis e filtrados por permissão, preservando o cuidado pastoral e a separação entre trilhos.

A Minha Caminhada deve:

- **Acompanhar constância:** presença, Palavra, devocional, serviço, comunhão, evangelismo, formação e conquistas.
- **Incentivar crescimento saudável:** orientar próximos passos sem culpa, exposição ou comparação tóxica.
- **Reconhecer serviço e participação:** valorizar marcos reais da caminhada com linguagem pastoral.
- **Organizar dados por trilho:** geral, jovem, equipe jovem e categorias futuras autorizadas.
- **Proteger dados sensíveis:** jovens, familiares, financeiros, administrativos, intercessão e cuidado pastoral.

A Minha Caminhada nunca deve:

- **Medir espiritualidade.**
- **Medir valor diante de Deus.**
- **Criar competição espiritual.**
- **Substituir discipulado.**
- **Substituir pastor, liderança ou cuidado humano.**
- **Expor pessoas, famílias, jovens ou dados sensíveis.**

Os pontos devem ser usados apenas como ferramenta de organização, incentivo, acompanhamento, validação e histórico. Nenhuma regra deve comunicar superioridade espiritual, ranking de fé ou valor pessoal.

## 2. Trilhos oficiais

### 2.1 Caminhada Geral da Igreja

Trilho principal para membro comum, congregado autorizado, jovem/resgatado também autorizado na caminhada geral e demais perfis conforme policy.

Inclui:

- **Pontos gerais.**
- **Níveis gerais.**
- **Conquistas gerais.**
- **Histórico geral.**
- **Mentor geral.**
- **Destaques gerais.**
- **Mapa geral.**
- **Regras gerais.**

Categorias prováveis:

- **Presença.**
- **Palavra.**
- **Devocional.**
- **Serviço.**
- **Comunhão.**
- **Evangelismo.**
- **Formação.**
- **Intercessão futura, quando aprovada e protegida.**

### 2.2 Caminhada Jovem dos Resgatados

Trilho específico para jovens/resgatados e perfis autorizados, sem exposição para membro comum.

Inclui:

- **Pontos jovens.**
- **Níveis jovens.**
- **Conquistas jovens.**
- **Histórico jovem.**
- **Mentor jovem.**
- **Destaques jovens.**
- **Mapa jovem.**
- **Regras jovens.**

Categorias prováveis:

- **Presença nos Resgatados.**
- **Bíblia na mão.**
- **Desafios bíblicos.**
- **Serviço jovem.**
- **Comunhão jovem.**
- **Evangelismo jovem.**
- **Missões.**

### 2.3 Equipes dos Resgatados

Trilho coletivo vinculado ao módulo jovem. Não deve ser misturado com pontuação individual geral nem com pontuação jovem pessoal sem regra explícita.

Inclui:

- **Pontos de equipe jovem.**
- **Missões coletivas.**
- **Desafios coletivos.**
- **Histórico coletivo autorizado.**
- **Destaques de equipe quando aprovados.**

Visibilidade:

- **Jovem participante:** própria equipe e dados autorizados.
- **Líder jovem:** equipes sob escopo autorizado.
- **Responsável:** equipe do filho apenas se policy permitir.
- **Admin/secretaria/liderança:** conforme função, necessidade e auditoria.
- **Membro comum:** não recebe payload de equipe jovem.

### 2.4 Intercessão

Categoria futura geral, com sigilo e validação pastoral. Deve ser tratada como área sensível e nunca como ranking público.

Regras centrais:

- **Sem exposição pública de intercessores.**
- **Sem ranking de intercessores.**
- **Sem exposição de pedidos, crises, aconselhamentos ou pessoas atendidas.**
- **Avaliação privada, limitada e auditável, se implementada.**
- **Pontuação eventual deve ter limites, validação e mínimo necessário.**
- **Liderança pastoral deve revisar qualquer uso sensível.**

## 3. Princípio de segurança

Regra central: **o backend deve filtrar dados antes de enviar ao Vue/Inertia**.

O frontend pode esconder componentes, mas isso não é segurança final. Controller, service, policy e query devem impedir que dados proibidos cheguem ao payload.

O frontend nunca deve receber, sem permissão:

- **Dados jovens para membro comum.**
- **Dados de equipe jovem.**
- **Dados de outros jovens.**
- **Dados de filhos não vinculados.**
- **Dados financeiros.**
- **Dados de conquistas financeiras.**
- **Dados sensíveis pastorais.**
- **Dados de outros membros.**
- **Dados internos administrativos.**
- **Dados de validação interna.**
- **Detalhes de intercessão pessoal.**
- **Notas pastorais.**
- **Relatórios amplos sem escopo.**

Toda página Inertia deve receber um `viewerContext` gerado pelo backend e payload já filtrado. Não deve existir payload completo para depois esconder no Vue.

## 4. Perfis e permissões

### 4.1 Membro comum

Pode ver:

- **Caminhada Geral própria.**
- **Pontos gerais próprios.**
- **Nível geral próprio.**
- **Conquistas gerais próprias.**
- **Histórico geral próprio.**
- **Mentor geral.**
- **Destaques gerais permitidos.**
- **Mapa geral.**
- **Regras gerais.**

Payload esperado:

- **viewerContext geral.**
- **generalJourney.**
- **generalAchievements.**
- **generalHistory.**
- **generalMentor.**
- **generalHighlights.**
- **generalLevel.**
- **generalMap.**

Não recebe:

- **youthJourney.**
- **youthLevel.**
- **youthHistory.**
- **youthAchievements.**
- **youthHighlights.**
- **youthTeam.**
- **financialAchievements.**
- **sensitivePastoralData.**
- **dados de filhos sem vínculo autorizado.**

### 4.2 Jovem/resgatado

Pode ver:

- **Caminhada Geral própria.**
- **Caminhada Jovem própria.**
- **Pontos gerais e jovens separados.**
- **Níveis gerais e jovens separados.**
- **Conquistas gerais e jovens próprias.**
- **Histórico geral e jovem próprio.**
- **Mentor geral e jovem.**
- **Destaques jovens autorizados.**

Payload esperado:

- **viewerContext com `canSeeYouthJourney`.**
- **generalJourney.**
- **youthJourney.**
- **generalLevel.**
- **youthLevel.**
- **generalAchievements.**
- **youthAchievements.**
- **generalHistory.**
- **youthHistory.**
- **mentor geral/jovem filtrado.**

Restrições:

- **Não vê dados de outros jovens sem autorização.**
- **Não vê equipes de terceiros.**
- **Não vê validações administrativas.**
- **Não vê dados pastorais sensíveis.**

### 4.3 Participante de equipe jovem

Pode ver:

- **Dados autorizados da própria equipe.**
- **Pontos coletivos da própria equipe.**
- **Missões coletivas.**
- **Histórico coletivo autorizado.**
- **Destaques coletivos quando aprovados.**

Payload esperado:

- **viewerContext com `canSeeYouthTeams`.**
- **ownYouthTeam.**
- **teamProgress.**
- **teamMissions.**
- **teamHighlights autorizados.**

Restrições:

- **Não vê equipes de terceiros sem permissão.**
- **Não recebe dados pessoais desnecessários de outros jovens.**
- **Não vê relatórios administrativos.**

### 4.4 Responsável/pai/mãe

Pode ver:

- **Dados de filhos vinculados.**
- **Caminhada geral dos filhos conforme policy.**
- **Caminhada jovem dos filhos conforme idade, vínculo e policy.**
- **Histórico permitido dos filhos.**
- **Conquistas permitidas dos filhos.**
- **Acompanhamento familiar autorizado.**

Payload esperado:

- **viewerContext com vínculo familiar.**
- **linkedChildren.**
- **childJourneySummaries filtrados.**
- **childHistory autorizado.**
- **childAchievements autorizadas.**

Restrições:

- **Não vê todos os jovens.**
- **Não vê filhos sem vínculo.**
- **Não vê informações pastorais privadas não liberadas.**
- **Não vê equipes inteiras sem vínculo/autorização.**

### 4.5 Líder jovem

Pode ver:

- **Jovens sob escopo autorizado.**
- **Equipes sob liderança autorizada.**
- **Validações jovens.**
- **Pontos jovens pendentes.**
- **Destaques jovens.**
- **Pontos coletivos de equipes.**

Payload esperado:

- **viewerContext de liderança.**
- **authorizedYouthScope.**
- **pendingYouthValidations.**
- **teamSummaries.**
- **youthHighlights conforme escopo.**

Restrições:

- **Não acessa dados fora do escopo.**
- **Não acessa dados financeiros sem permissão própria.**
- **Não acessa dados pastorais sensíveis sem policy específica.**

### 4.6 Secretaria/administração

Pode ver:

- **Dados necessários para cadastro, validação, auditoria e integridade.**
- **Regras de pontuação.**
- **Logs pendentes.**
- **Logs de pontos e conquistas.**
- **Trilhos gerais, jovens e equipes conforme necessidade administrativa.**

Payload esperado:

- **viewerContext administrativo.**
- **validationQueues.**
- **ruleManagementData.**
- **auditSummaries.**
- **scopedMemberData conforme permissão.**

Restrições:

- **Acesso amplo deve ser explícito.**
- **Toda alteração deve ser auditável.**
- **Dados sensíveis devem respeitar mínimo necessário.**

### 4.7 Pastor/liderança pastoral

Pode ver:

- **Informações necessárias para cuidado pastoral.**
- **Indicadores de constância e acompanhamento quando aprovados.**
- **Dados sensíveis somente quando necessário e autorizado.**
- **Mentor/acompanhamento com cuidado.**

Payload esperado:

- **viewerContext pastoral.**
- **careSummaries com mínimo necessário.**
- **pastoralFlags autorizadas.**
- **sem exposição pública.**

Restrições:

- **Privacidade.**
- **Mínimo necessário.**
- **Sem competição.**
- **Sem uso de dados sensíveis fora do cuidado.**

## 5. Tabelas sugeridas

As tabelas abaixo são propostas para implementação futura. Antes de qualquer migration, o banco atual deve ser revisado para evitar duplicidade com módulos existentes de pessoas, famílias, responsáveis, jovens, equipes, presença, finanças, intercessão ou permissões.

### 5.1 `walking_journeys`

- **id**
- **key**
- **name**
- **type:** `general`, `youth`, `youth_team`
- **description**
- **is_active**
- **created_at**
- **updated_at**

### 5.2 `walking_levels`

- **id**
- **journey_id**
- **level_number**
- **name**
- **description**
- **required_points**
- **icon**
- **color**
- **is_active**
- **created_at**
- **updated_at**

### 5.3 `walking_point_rules`

- **id**
- **journey_id nullable**
- **key**
- **name**
- **description**
- **category**
- **points**
- **team_points nullable**
- **validation_type:** `automatic`, `manual`, `leader_validation`, `secretary_validation`, `pastoral_validation`
- **source_origin**
- **daily_limit nullable**
- **weekly_limit nullable**
- **monthly_limit nullable**
- **counts_for_level boolean**
- **counts_for_highlight boolean**
- **counts_for_team boolean**
- **is_fixed_system_rule boolean**
- **can_edit_points boolean**
- **is_active**
- **created_at**
- **updated_at**

### 5.4 `walking_point_logs`

- **id**
- **person_id**
- **user_id nullable**
- **journey_id**
- **point_rule_id nullable**
- **family_id nullable**
- **youth_team_id nullable**
- **source_type nullable**
- **source_id nullable**
- **category**
- **points**
- **team_points nullable**
- **status:** `pending`, `approved`, `rejected`, `cancelled`
- **validation_type**
- **approved_by nullable**
- **approved_at nullable**
- **rejected_by nullable**
- **rejected_at nullable**
- **notes nullable**
- **metadata json nullable**
- **created_at**
- **updated_at**

### 5.5 `walking_achievements`

- **id**
- **key**
- **name**
- **description**
- **type:** `general`, `youth`, `youth_team`, `administrative`, `financial`, `pastoral_sensitive`
- **category**
- **visibility:** `public_to_user`, `private_to_user`, `leadership_only`, `hidden`
- **icon**
- **color**
- **criteria json nullable**
- **requires_validation boolean**
- **is_active**
- **created_at**
- **updated_at**

### 5.6 `person_walking_achievements`

- **id**
- **person_id**
- **achievement_id**
- **journey_id nullable**
- **status:** `received`, `in_progress`, `locked`, `hidden`, `pending_validation`
- **progress_current nullable**
- **progress_target nullable**
- **awarded_by nullable**
- **awarded_at nullable**
- **metadata json nullable**
- **created_at**
- **updated_at**

### 5.7 `walking_highlights`

- **id**
- **person_id nullable**
- **family_id nullable**
- **journey_id nullable**
- **youth_team_id nullable**
- **type:** `general`, `youth`, `youth_team`, `intercession`
- **category**
- **title**
- **description**
- **period_type:** `weekly`, `monthly`, `yearly`
- **period_start**
- **period_end**
- **visibility**
- **generated_by nullable**
- **approved_by nullable**
- **approved_at nullable**
- **is_visible**
- **metadata json nullable**
- **created_at**
- **updated_at**

### 5.8 `walking_mentor_response_templates`

- **id**
- **analysis_type**
- **key**
- **title**
- **body**
- **tone**
- **journey_type:** `general`, `youth`
- **is_active**
- **created_at**
- **updated_at**

### 5.9 `walking_mentor_response_logs`

- **id**
- **person_id**
- **family_id nullable**
- **journey_id nullable**
- **analysis_type**
- **response_template_id nullable**
- **response_variant**
- **context_summary json nullable**
- **status:** `displayed`, `ignored`, `completed`, `replaced`
- **displayed_at**
- **created_at**
- **updated_at**

### 5.10 `walking_history_events`

- **id**
- **person_id**
- **journey_id**
- **event_type**
- **title**
- **description**
- **points nullable**
- **source_type nullable**
- **source_id nullable**
- **visibility**
- **metadata json nullable**
- **occurred_at**
- **created_at**
- **updated_at**

### 5.11 `youth_teams`

Criar somente se não existir tabela equivalente em outro módulo.

- **id**
- **name**
- **description**
- **is_active**
- **created_at**
- **updated_at**

### 5.12 `youth_team_members`

Criar somente se não existir tabela equivalente em outro módulo.

- **id**
- **youth_team_id**
- **person_id**
- **role**
- **joined_at**
- **left_at nullable**
- **is_active**
- **created_at**
- **updated_at**

### 5.13 Observação sobre reutilização

Se já existirem tabelas equivalentes no projeto, reaproveitar a estrutura atual e não duplicar. Em especial, revisar antes:

- **people.**
- **users.**
- **families.**
- **family_members.**
- **guardianships.**
- **permissions/access profiles.**
- **presence/event records.**
- **financial records.**
- **prayer/intercession records.**
- **qualquer estrutura jovem já existente.**

## 6. Models e relacionamentos

### 6.1 Models propostos

- **WalkingJourney.**
- **WalkingLevel.**
- **WalkingPointRule.**
- **WalkingPointLog.**
- **WalkingAchievement.**
- **PersonWalkingAchievement.**
- **WalkingHighlight.**
- **WalkingMentorResponseTemplate.**
- **WalkingMentorResponseLog.**
- **WalkingHistoryEvent.**
- **YouthTeam, se necessário.**
- **YouthTeamMember, se necessário.**

### 6.2 `Person`

Relacionamentos esperados:

- **hasMany `WalkingPointLog`.**
- **hasMany `PersonWalkingAchievement`.**
- **hasMany `WalkingHighlight`.**
- **hasMany `WalkingMentorResponseLog`.**
- **belongsToMany `YouthTeam`.**

### 6.3 `WalkingJourney`

Relacionamentos esperados:

- **hasMany `WalkingLevel`.**
- **hasMany `WalkingPointRule`.**
- **hasMany `WalkingPointLog`.**
- **hasMany `WalkingHistoryEvent`.**

### 6.4 `WalkingPointRule`

Relacionamentos esperados:

- **belongsTo `WalkingJourney`.**
- **hasMany `WalkingPointLog`.**

### 6.5 `WalkingPointLog`

Relacionamentos esperados:

- **belongsTo `Person`.**
- **belongsTo `User` nullable.**
- **belongsTo `WalkingJourney`.**
- **belongsTo `WalkingPointRule` nullable.**
- **belongsTo `YouthTeam` nullable.**

### 6.6 `WalkingAchievement`

Relacionamentos esperados:

- **hasMany `PersonWalkingAchievement`.**

### 6.7 `PersonWalkingAchievement`

Relacionamentos esperados:

- **belongsTo `Person`.**
- **belongsTo `WalkingAchievement`.**
- **belongsTo `WalkingJourney` nullable.**

### 6.8 `WalkingHighlight`

Relacionamentos esperados:

- **belongsTo `Person` nullable.**
- **belongsTo `Family` nullable.**
- **belongsTo `WalkingJourney` nullable.**
- **belongsTo `YouthTeam` nullable.**

### 6.9 Mentor logs

`WalkingMentorResponseLog` deve:

- **belongsTo `Person`.**
- **belongsTo `Family` nullable.**
- **belongsTo `WalkingJourney` nullable.**
- **belongsTo `WalkingMentorResponseTemplate` nullable.**

## 7. Services futuros

### 7.1 `WalkingPermissionService`

Responsabilidades:

- **Decidir `viewerContext`.**
- **Decidir payload permitido por perfil.**
- **Determinar se pode ver trilho jovem.**
- **Determinar se pode ver equipe jovem.**
- **Determinar se pode ver dados sensíveis.**
- **Resolver escopo de responsável, liderança, secretaria e pastoral.**
- **Impedir que dado proibido chegue ao Vue/Inertia.**

### 7.2 `WalkingDashboardService`

Responsabilidades:

- **Montar dados da página principal.**
- **Combinar jornada, progresso, atividades, badges, mentor, destaques e próximos passos.**
- **Usar apenas dados liberados pelo serviço de permissão.**
- **Fornecer fallback seguro quando não houver registros.**

### 7.3 `WalkingLevelService`

Responsabilidades:

- **Calcular nível atual.**
- **Calcular próximo nível.**
- **Calcular progresso percentual.**
- **Separar nível geral e jovem.**
- **Montar dados de mapa por trilho.**

### 7.4 `WalkingPointService`

Responsabilidades:

- **Aplicar pontos.**
- **Validar regra ativa.**
- **Validar limites diário, semanal e mensal.**
- **Evitar duplicidade por `source_type` e `source_id`.**
- **Separar geral, jovem e equipe.**
- **Definir status `pending` ou `approved`.**
- **Registrar auditoria.**

### 7.5 `WalkingAchievementService`

Responsabilidades:

- **Calcular conquistas recebidas.**
- **Calcular progresso de conquistas.**
- **Definir `locked`, `hidden` e `pending_validation`.**
- **Separar conquistas gerais, jovens, equipe, administrativas, financeiras e sensíveis.**
- **Nunca enviar conquistas restritas sem permissão.**

### 7.6 `WalkingHighlightService`

Responsabilidades:

- **Gerar destaques saudáveis.**
- **Separar geral, jovem e equipe.**
- **Evitar ranking espiritual.**
- **Respeitar visibilidade por perfil.**
- **Exigir aprovação quando necessário.**
- **Bloquear intercessão sensível em listagens públicas.**

### 7.7 `WalkingMentorService`

Responsabilidades:

- **Analisar padrões da caminhada com regras.**
- **Escolher resposta pré-aprovada.**
- **Gerar variações seguras.**
- **Evitar repetição por pessoa/família.**
- **Gravar log de exibição.**
- **Não substituir liderança pastoral.**
- **Não diagnosticar, acusar ou prometer resultado espiritual.**

### 7.8 `WalkingHistoryService`

Responsabilidades:

- **Montar timeline filtrada por perfil.**
- **Combinar eventos de pontos, conquistas, validações e marcos.**
- **Omitir ou resumir dados sensíveis.**
- **Filtrar por pessoa, filho vinculado, equipe ou escopo autorizado.**

### 7.9 `WalkingInertiaPayloadService`

Responsabilidades:

- **Centralizar payload por página.**
- **Garantir que dado proibido não chegue ao Vue.**
- **Padronizar `viewerContext`.**
- **Remover chaves proibidas por perfil.**
- **Facilitar testes de payload por página.**

## 8. Policies futuras

### 8.1 Policies necessárias

- **WalkingJourneyPolicy.**
- **WalkingPointLogPolicy.**
- **WalkingAchievementPolicy.**
- **WalkingHighlightPolicy.**
- **WalkingMentorPolicy.**
- **WalkingHistoryPolicy.**
- **YouthTeamPolicy.**

### 8.2 Métodos possíveis

Cada policy pode conter, conforme necessidade:

- **viewAny.**
- **view.**
- **create.**
- **update.**
- **validate.**
- **approve.**
- **reject.**
- **viewYouth.**
- **viewYouthTeam.**
- **viewSensitive.**
- **viewFamilyChild.**

### 8.3 Regras centrais

- **Responsável só vê dados de filho vinculado.**
- **Jovem só vê próprios dados jovens, salvo autorização explícita.**
- **Membro comum só vê próprios dados gerais.**
- **Liderança/admin vê conforme função, escopo e necessidade.**
- **Pastoral vê dados sensíveis apenas quando necessário e autorizado.**
- **Financeiro nunca deve ser exposto por conquista sem policy específica.**
- **Intercessão deve usar mínimo necessário e não gerar ranking público.**

## 9. Props Inertia por página

### 9.1 Página principal

Props futuras:

- **viewerContext.**
- **generalJourney.**
- **youthJourney nullable.**
- **summary.**
- **activities.**
- **badges.**
- **mentorPreview.**
- **highlightsPreview.**
- **nextSteps.**
- **routes.**

### 9.2 Ranking/Destaques

Props futuras:

- **viewerContext.**
- **filters.**
- **generalHighlights.**
- **youthHighlights nullable.**
- **youthTeamHighlights nullable.**
- **intercessionInfo nullable.**
- **careNotes.**

### 9.3 Regras

Props futuras:

- **viewerContext.**
- **generalRules.**
- **youthRules nullable.**
- **teamRules nullable.**
- **validationRules.**
- **careNotes.**

### 9.4 Mentor

Props futuras:

- **viewerContext.**
- **mentorReading.**
- **mentorPlan.**
- **journeyCards.**
- **guidedQuestions.**
- **careLimits.**
- **responseHistoryPreview nullable.**

### 9.5 Conquistas

Props futuras:

- **viewerContext.**
- **achievementSummary.**
- **filters.**
- **receivedAchievements.**
- **inProgressAchievements.**
- **lockedAchievements.**
- **categories.**
- **careNotes.**

### 9.6 Histórico

Props futuras:

- **viewerContext.**
- **filters.**
- **summary.**
- **events.**

### 9.7 Nível

Props futuras:

- **viewerContext.**
- **generalLevel.**
- **youthLevel nullable.**
- **nextLevel.**
- **mapRoutes.**

### 9.8 Mapas

Props futuras:

- **viewerContext.**
- **journey.**
- **levels.**
- **currentLevel.**
- **progress.**

## 10. Dados que nunca devem ser enviados sem permissão

Nunca enviar sem permissão explícita:

- **Dados jovens para membro comum.**
- **Dados de equipe jovem.**
- **Dados de outros jovens.**
- **Dados de filhos sem vínculo.**
- **Dados financeiros.**
- **Dívidas.**
- **Histórico sensível.**
- **Aconselhamento.**
- **Intercessão pessoal.**
- **Avaliações privadas.**
- **Notas pastorais.**
- **Dados administrativos internos.**
- **Dados de validação interna.**
- **Ranking de intercessores.**
- **Comparações entre pessoas.**
- **Relatórios amplos sem escopo.**
- **Metadados que revelem pessoas, famílias ou situações sensíveis.**

## 11. Fluxo de pontuação

Fluxo futuro recomendado:

1. **Evento acontece.**
2. **Service identifica a regra aplicável.**
3. **Verifica se a regra está ativa.**
4. **Verifica limites diários, semanais e mensais.**
5. **Verifica duplicidade por `source_type` e `source_id`.**
6. **Define trilho:** `general`, `youth` ou `youth_team`.
7. **Define status:** `approved` ou `pending`.
8. **Registra `walking_point_log`.**
9. **Atualiza progresso de nível.**
10. **Atualiza progresso de conquista.**
11. **Registra evento de histórico.**
12. **Pode gerar destaque futuramente.**
13. **Mantém tudo auditável.**

Regras adicionais:

- **Pontos gerais não se misturam com pontos jovens.**
- **Pontos jovens não se misturam com pontos de equipe.**
- **Ponto coletivo não vira ponto individual automaticamente.**
- **Validação manual deve registrar responsável e horário.**
- **Rejeição deve ser auditável e, quando exibida ao usuário, pastoral e sem exposição.**

## 12. Fluxo de conquistas

Tipos de conquistas:

- **Automáticas:** calculadas por regra e logs aprovados.
- **Por validação:** dependem de liderança, secretaria, responsável de curso ou pastoral.
- **Concedidas:** atribuídas manualmente por perfil autorizado.
- **Ocultas:** não aparecem até cumprir critérios ou policy permitir.
- **Privadas:** visíveis somente ao próprio usuário ou escopo autorizado.
- **Sensíveis:** ocultas por padrão, com mínimo necessário.
- **Financeiras:** nunca públicas e dependentes de policy específica.
- **Jovens:** visíveis somente para jovem/resgatado e perfis autorizados.
- **Equipe jovem:** visíveis somente no escopo da equipe autorizada.

Fluxo recomendado:

1. **Pontuação ou evento oficial é aprovado.**
2. **Achievement service recalcula critérios.**
3. **Define status da conquista.**
4. **Se requer validação, cria estado `pending_validation`.**
5. **Se aprovada, registra `awarded_by` e `awarded_at`.**
6. **Registra evento de histórico quando visível.**
7. **Filtra payload conforme policy.**

## 13. Fluxo de destaques

Destaques não são ranking espiritual. Devem reconhecer constância, serviço, Palavra, comunhão e crescimento saudável sem comunicar superioridade.

Regras:

- **Gerados por período:** semanal, mensal ou anual.
- **Baseados em logs aprovados e critérios saudáveis.**
- **Podem exigir aprovação de liderança/secretaria.**
- **Devem respeitar visibilidade.**
- **Não expõem jovens para todos.**
- **Não expõem equipes fora do escopo.**
- **Não expõem intercessão sensível.**
- **Não comparam valor espiritual.**
- **Não criam pódio agressivo.**
- **Não usam dados negativos.**

Fluxo recomendado:

1. **Serviço consulta logs aprovados do período.**
2. **Agrupa por trilho, categoria e escopo.**
3. **Remove dados sensíveis e não autorizados.**
4. **Gera candidatos a destaque.**
5. **Aplica regras de cuidado pastoral.**
6. **Envia para aprovação quando necessário.**
7. **Publica somente destaques visíveis para o perfil.**

## 14. Fluxo do mentor

A primeira versão do mentor deve ser **sem IA externa**. Deve usar regras, respostas pré-aprovadas e dados resumidos permitidos.

O mentor deve:

- **Analisar padrões de constância, progresso e próximos passos.**
- **Escolher respostas por regras.**
- **Usar templates pastorais aprovados.**
- **Variar respostas para evitar repetição.**
- **Gravar histórico de exibição.**
- **Respeitar trilho geral/jovem/família.**
- **Exibir limites de cuidado.**

O mentor nunca deve:

- **Substituir pastor.**
- **Substituir liderança.**
- **Diagnosticar.**
- **Acusar falta de fé.**
- **Prometer resultado espiritual.**
- **Atuar sozinho em crises graves.**
- **Expor dados sensíveis.**

Fluxo recomendado:

1. **Service recebe pessoa/família/jornada autorizada.**
2. **Busca resumo filtrado da caminhada.**
3. **Identifica padrão por regra.**
4. **Escolhe template ativo.**
5. **Verifica histórico para evitar repetição.**
6. **Gera resposta segura.**
7. **Grava `walking_mentor_response_log`.**
8. **Exibe orientação pastoral e limites.**

## 15. Testes necessários

### 15.1 Unit tests

- **Cálculo de pontos.**
- **Limites de regras.**
- **Prevenção de duplicidade por source.**
- **Cálculo de nível.**
- **Progresso para próximo nível.**
- **Conquistas automáticas.**
- **Conquistas por validação.**
- **Mentor por regras.**
- **Destaques saudáveis.**
- **Separação geral/jovem/equipe.**

### 15.2 Feature tests

- **Membro comum não recebe youth payload.**
- **Jovem recebe youth payload autorizado.**
- **Responsável vê apenas filhos vinculados.**
- **Admin vê apenas o permitido por função/escopo.**
- **Financeiro/sensível não aparece para perfil comum.**
- **Logs são criados.**
- **Pending validation funciona.**
- **Hidden achievements não aparecem.**
- **Rotas jovens não expõem payload para membro comum.**

### 15.3 Policy tests

- **view general.**
- **view youth.**
- **view team.**
- **view child.**
- **view sensitive.**
- **approve/reject.**
- **validate point log.**
- **view mentor scope.**

### 15.4 Inertia tests

- **Props corretas por página.**
- **Payload não contém chaves proibidas para membro comum.**
- **`youthJourney` ausente ou `null` sem permissão.**
- **`youthTeamHighlights` ausente ou `null` sem permissão.**
- **Conquistas financeiras/sensíveis ausentes para perfil comum.**
- **Histórico contém somente eventos permitidos.**

## 16. Ordem segura de implementação futura

Ordem recomendada:

1. **Revisar banco atual para evitar duplicidade.**
2. **Mapear tabelas já existentes de pessoas, famílias, responsáveis, jovens, presença, financeiro e intercessão.**
3. **Criar migrations base dos trilhos, níveis, regras e logs.**
4. **Criar models e relacionamentos.**
5. **Criar seeders mínimos de jornadas, níveis e regras.**
6. **Criar policies.**
7. **Criar `WalkingPermissionService`.**
8. **Criar services de payload/Inertia.**
9. **Substituir mocks da página principal.**
10. **Substituir mocks de nível/mapa.**
11. **Substituir mocks de histórico.**
12. **Substituir mocks de conquistas.**
13. **Substituir mocks de destaques.**
14. **Substituir mocks de mentor.**
15. **Criar testes unitários.**
16. **Criar testes feature/policy/Inertia.**
17. **Executar auditoria de payload por perfil.**
18. **Executar auditoria visual após dados reais.**
19. **Só então liberar para uso real.**

## 17. Checklist antes de implementar

Antes de qualquer etapa funcional:

- [ ] **Banco atual conferido.**
- [ ] **Nomes de tabelas validados.**
- [ ] **Sem duplicar módulo jovem.**
- [ ] **Sem duplicar estruturas de família/responsável.**
- [ ] **Sem duplicar estruturas financeiras.**
- [ ] **Sem misturar geral/jovem/equipe.**
- [ ] **Policies planejadas.**
- [ ] **Dados sensíveis protegidos.**
- [ ] **Payload por perfil definido.**
- [ ] **Fallbacks seguros definidos.**
- [ ] **Testes planejados.**
- [ ] **Documentação atualizada.**
- [ ] **Commit separado por etapa.**
- [ ] **Sem `git add .` em etapas sensíveis.**
- [ ] **Sem push antes de validações.**

## 18. Pendências oficiais

- **Revisar banco atual.**
- **Confirmar estruturas existentes de jovens/equipes.**
- **Confirmar estruturas existentes de intercessão.**
- **Confirmar estruturas existentes de presença/eventos.**
- **Definir migrations somente após revisão.**
- **Definir policies reais.**
- **Definir services reais.**
- **Substituir mocks por props reais gradualmente.**
- **Criar testes.**
- **Auditar payload Inertia antes de uso real.**
