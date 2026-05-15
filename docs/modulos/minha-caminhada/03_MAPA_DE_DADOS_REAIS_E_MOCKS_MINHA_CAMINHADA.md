# Mapa de Dados Reais e Mocks da Minha Caminhada

## 1. Objetivo

Este documento mapeia os mocks atuais da Minha Caminhada e define a origem real futura dos dados. Ele deve ser usado antes de transformar qualquer tela visual em funcionalidade real.

Nenhum mock deve ser tratado como dado real. Toda substituição deve passar por controller, service, policy, fallback seguro e teste com perfis diferentes.

## 2. Página principal Minha Caminhada

### Rota

`/familia-resgate/minha-caminhada`

### Dados fake atuais

- **Pontos gerais.**
- **Pontos jovens.**
- **Nível geral.**
- **Nível jovem.**
- **Áreas de progresso.**
- **Atividades recentes.**
- **Conquistas/badges.**
- **Mentor.**
- **Destaques.**
- **Próximos passos.**
- **Jornadas geral e jovem.**
- **Mapa resumido.**

### Origem real futura

- **walking point logs:** registros de pontos por categoria/trilho.
- **walking levels:** níveis e marcos oficiais.
- **achievements:** catálogo oficial de conquistas.
- **member achievements:** conquistas concedidas.
- **member profile:** pessoa autenticada e vínculos familiares.
- **youth membership:** vínculo jovem/resgatado.
- **mentor rules:** regras pastorais do mentor.
- **permissions:** contexto de acesso por perfil.

### Props Inertia esperadas

- **viewerContext:** perfil, permissões e vínculos.
- **generalJourney:** resumo da Caminhada Geral.
- **youthJourney:** resumo jovem quando autorizado.
- **progressAreas:** progresso por área visível.
- **recentActivities:** atividades filtradas por usuário/permissão.
- **achievements:** conquistas visíveis.
- **mentorInsight:** leitura segura do mentor.
- **highlights:** destaques permitidos.
- **nextSteps:** próximos passos calculados.

### Service/backend provável

- **MinhaCaminhadaController.**
- **WalkingDashboardService.**
- **WalkingPermissionService.**
- **WalkingPointService.**
- **AchievementService.**
- **MentorRulesService.**

### Policy necessária

- **viewWalkingDashboard.**
- **viewGeneralJourney.**
- **viewYouthJourney.**
- **viewFamilyMemberJourney.**
- **viewWalkingHighlights.**

### Fallback seguro

- **Sem dados:** mostrar estado vazio encorajador.
- **Sem permissão jovem:** ocultar jornada jovem completamente.
- **Sem pontos:** mostrar progresso zero sem inventar histórico.
- **Sem mentor:** mostrar orientação geral, não personalizada.

### Prioridade

Alta. É a porta de entrada do módulo e precisa refletir permissões reais.

## 3. Ranking/Destaques

### Rota

`/familia-resgate/minha-caminhada/ranking`

### Dados fake atuais

- **Destaques gerais.**
- **Critérios gerais.**
- **Áreas de reconhecimento.**
- **Intercessão futura.**
- **Mocks jovens e equipes existem, mas ficam condicionados por contexto de visibilidade.**

### Origem real futura

- **point_logs:** registros validados de pontuação.
- **highlight service:** cálculo/seleção de destaques saudáveis.
- **point rules:** regras oficiais administráveis.
- **policies:** visibilidade por perfil, vínculo e trilho.
- **audit logs:** rastreabilidade de validações e destaques.

### Props Inertia esperadas

- **viewerContext:** permissões de geral, jovem e equipes.
- **filters:** filtros permitidos para o perfil.
- **generalHighlights:** destaques gerais visíveis.
- **youthHighlights:** destaques jovens apenas quando autorizado.
- **teamHighlights:** destaques de equipe apenas quando autorizado.
- **recognitionAreas:** áreas gerais permitidas.
- **careGuidelines:** textos de cuidado pastoral.

### Service/backend provável

- **WalkingHighlightService.**
- **WalkingPointRuleService.**
- **WalkingPermissionService.**
- **YouthTeamHighlightService.**

### Policy necessária

- **viewGeneralHighlights.**
- **viewYouthHighlights.**
- **viewYouthTeamHighlights.**
- **viewIntercessionRecognition.**

### Fallback seguro

- **Membro comum:** mostrar somente destaques gerais.
- **Sem destaques:** mostrar mensagem de reconhecimento em construção.
- **Sem permissão jovem/equipe:** não enviar dados jovens/equipe na prop.
- **Intercessão:** mostrar apenas categoria geral futura, sem nomes nem dados sensíveis.

### Prioridade

Alta. Página já foi ajustada para membro comum, mas precisa backend/policies para uso real.

## 4. Conquistas

### Rota

`/familia-resgate/minha-caminhada/conquistas`

### Dados fake atuais

- **Conquistas gerais recebidas.**
- **Conquistas gerais em progresso.**
- **Categorias gerais.**
- **Mocks restritos de preparação futura, ocultos para membro comum.**
- **Estados explícitos de conquista, como recebida, em progresso, em validação, próxima e oculta.**

### Origem real futura

- **achievements:** catálogo oficial.
- **member_achievements:** conquistas concedidas por pessoa.
- **achievement_rules:** regras de concessão.
- **permissions:** visibilidade por perfil/trilho.
- **visibility level:** público, privado, pastoral, administrativo ou sensível.
- **financial records:** somente quando estritamente necessário e autorizado.

### Props Inertia esperadas

- **viewerContext:** perfil e permissões.
- **achievementGroups:** grupos por tipo e trilho.
- **earnedAchievements:** conquistas concedidas e visíveis.
- **availableAchievements:** conquistas disponíveis para o perfil.
- **hiddenSensitiveCount:** opcional para administração, sem expor detalhes ao membro.
- **filters:** filtros permitidos.

### Service/backend provável

- **AchievementCatalogService.**
- **MemberAchievementService.**
- **AchievementVisibilityService.**
- **WalkingPermissionService.**

### Policy necessária

- **viewAchievement.**
- **viewYouthAchievement.**
- **viewAdministrativeAchievement.**
- **viewFinancialAchievement.**
- **viewSensitiveAchievement.**

### Fallback seguro

- **Membro comum:** conquistas gerais próprias.
- **Jovem autorizado:** conquistas gerais + jovens próprias.
- **Responsável:** conquistas dos filhos vinculados conforme policy.
- **Admin:** catálogo completo conforme necessidade.
- **Financeiro/sensível:** ocultar por padrão.

### Estado atual de `/conquistas`

A página foi reestruturada para membro comum e agora organiza:

- **Conquistas gerais.**
- **Conquistas recebidas.**
- **Conquistas em progresso, em validação ou próximas.**
- **Categorias gerais.**
- **Conquistas restritas ocultas por padrão e preparadas para policy futura.**

### Prioridade

Alta. A página visual foi ajustada, mas ainda depende de backend/policies para garantir que dados restritos não sejam enviados ao frontend.

## 5. Regras

### Rota

`/familia-resgate/minha-caminhada/regras`

### Dados fake atuais

- **Critérios gerais.**
- **Critérios jovens.**
- **Explicações de pontuação.**
- **Textos de cuidado.**
- **Separações conceituais.**

### Origem real futura

- **Regras de pontuação administráveis.**
- **Profile permissions.**
- **Rule categories.**
- **Validation requirements.**
- **Limits and caps.**
- **Visibility settings.**

### Props Inertia esperadas

- **viewerContext:** perfil e permissões.
- **generalRules:** regras gerais.
- **youthRules:** regras jovens quando autorizado.
- **teamRules:** regras de equipe quando autorizado.
- **intercessionRules:** regras futuras conforme permissão.
- **ruleWarnings:** avisos pastorais.

### Service/backend provável

- **WalkingPointRuleService.**
- **WalkingRuleVisibilityService.**
- **WalkingPermissionService.**

### Policy necessária

- **viewGeneralRules.**
- **viewYouthRules.**
- **viewTeamRules.**
- **manageWalkingRules.**

### Fallback seguro

- **Membro comum:** regras gerais.
- **Jovem:** regras gerais + jovens.
- **Equipe jovem:** regras de equipe se autorizado.
- **Admin:** tudo para gestão.

### Estado atual

`/regras` já adapta visibilidade no protótipo visual:

- **Membro comum vê geral.**
- **Jovem poderá ver geral + jovem quando autorizado.**
- **Admin verá tudo somente quando backend/policies existirem.**

### Prioridade

Alta. Regras visíveis precisam bater com regras reais administráveis.

## 6. Mentor

### Rota

`/familia-resgate/minha-caminhada/mentor`

### Dados fake atuais

- **Leitura da caminhada.**
- **Plano sugerido.**
- **Caminhada jovem.**
- **Perguntas guiadas.**
- **Textos de cuidado pastoral.**

### Origem real futura

- **mentor rules:** regras pastorais gratuitas.
- **response templates:** respostas pré-aprovadas.
- **mentor response history:** histórico para evitar repetição.
- **permissions:** acesso por perfil, vínculo e escopo.
- **walking logs:** dados resumidos e seguros da caminhada.
- **achievement and progress summaries:** apenas dados permitidos.

### Props Inertia esperadas

- **viewerContext:** perfil, vínculos e permissões.
- **mentorSummary:** leitura segura.
- **suggestedPlan:** plano sugerido por regras.
- **generalTrackInsight:** trilho geral.
- **youthTrackInsight:** trilho jovem quando autorizado.
- **familyInsights:** filhos vinculados quando autorizado.
- **careLimits:** avisos de limite pastoral.

### Service/backend provável

- **MentorRulesService.**
- **MentorResponseTemplateService.**
- **MentorResponseHistoryService.**
- **WalkingSummaryService.**
- **WalkingPermissionService.**

### Policy necessária

- **viewMentor.**
- **viewYouthMentor.**
- **viewFamilyMentor.**
- **viewPastoralMentorOverview.**

### Fallback seguro

- **Membro comum:** mentor geral.
- **Jovem:** mentor geral + jovem.
- **Responsável:** filhos vinculados.
- **Liderança:** visão de acompanhamento, sem expor indevidamente.
- **Sem dados:** plano geral encorajador, sem inventar progresso.

### Adaptação obrigatória

`/mentor` deve adaptar:

- **Membro comum:** mentor geral.
- **Jovem:** mentor geral + jovem.
- **Responsável:** filhos vinculados.
- **Liderança:** visão de acompanhamento, sem exposição indevida.

### Prioridade

Alta. Mentor usa linguagem sensível e não deve expor dados nem substituir cuidado pastoral humano.

## 7. Histórico

### Rota

`/familia-resgate/minha-caminhada/historico`

### Dados fake atuais

- **Eventos de caminhada.**
- **Pontuações simuladas.**
- **Filtros por trilho.**
- **Registros de conquistas.**

### Origem real futura

- **walking_point_logs.**
- **achievement logs.**
- **presence records.**
- **service records.**
- **study/devotional records.**
- **validation logs.**

### Props Inertia esperadas

- **viewerContext.**
- **historyEvents.**
- **filters.**
- **summary.**
- **pagination.**

### Service/backend provável

- **WalkingHistoryService.**
- **WalkingPermissionService.**
- **WalkingPointLogService.**

### Policy necessária

- **viewOwnWalkingHistory.**
- **viewYouthWalkingHistory.**
- **viewFamilyWalkingHistory.**
- **viewAdministrativeWalkingHistory.**

### Fallback seguro

- **Sem eventos:** estado vazio.
- **Sem permissão:** não enviar eventos não autorizados.
- **Dados sensíveis:** omitir ou resumir.

### Prioridade

Média/Alta. Histórico real é base para auditoria de pontos e conquistas.

## 8. Nível e mapa

### Rotas

- `/familia-resgate/minha-caminhada/nivel`
- `/familia-resgate/minha-caminhada/mapa`
- `/familia-resgate/minha-caminhada/geral`
- `/familia-resgate/minha-caminhada/jovem`

### Dados fake atuais

- **Níveis gerais.**
- **Níveis jovens.**
- **Mapa visual.**
- **Marcos atuais/futuros/concluídos.**
- **Badges de nível.**

### Origem real futura

- **walking_levels.**
- **walking_level_progress.**
- **journey definitions.**
- **point logs.**
- **permissions.**

### Props Inertia esperadas

- **viewerContext.**
- **journey.**
- **levels.**
- **currentLevel.**
- **progressToNext.**
- **mapMilestones.**

### Service/backend provável

- **WalkingLevelService.**
- **WalkingMapService.**
- **WalkingPermissionService.**

### Policy necessária

- **viewGeneralMap.**
- **viewYouthMap.**
- **viewCurrentLevel.**
- **viewFamilyLevel.**

### Fallback seguro

- **Sem progresso:** nível inicial.
- **Sem permissão jovem:** ocultar mapa jovem.
- **Rota antiga:** manter compatibilidade sem confundir trilhos.

### Prioridade

Média/Alta. O mapa é central visualmente e precisa refletir trilho correto.

## 9. Regras gerais para substituição de mocks

Antes de trocar mock por dado real:

- **Definir tabela/model/service real.**
- **Definir policy.**
- **Definir props Inertia.**
- **Definir fallback seguro.**
- **Testar membro comum.**
- **Testar jovem/resgatado.**
- **Testar responsável.**
- **Testar líder jovem.**
- **Testar admin/secretaria.**
- **Testar pastor/liderança quando aplicável.**
- **Verificar que dados jovens/equipe não são enviados quando não autorizados.**
- **Verificar que dados sensíveis não aparecem em payload, não apenas no DOM.**
