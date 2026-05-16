# Decisão Técnica das Migrations — Minha Caminhada

## 1. Objetivo da decisão técnica

Este documento define as decisões técnicas de banco antes da criação das migrations reais da **Minha Caminhada**.

A decisão parte dos documentos oficiais já produzidos para o módulo, especialmente:

- `05_PLANO_TECNICO_BACKEND_MINHA_CAMINHADA.md`;
- `06_AUDITORIA_BANCO_ATUAL_MINHA_CAMINHADA.md`;
- `02_MAPA_DE_PERMISSOES_MINHA_CAMINHADA.md`;
- `03_MAPA_DE_DADOS_REAIS_E_MOCKS_MINHA_CAMINHADA.md`.

Os objetivos desta decisão são:

- evitar duplicidade de tabelas e responsabilidades;
- reaproveitar estruturas existentes do projeto;
- separar o que pertence à Minha Caminhada do que já pertence a Família, Secretaria, Financeiro e Departamentos;
- preparar migrations seguras e coerentes;
- proteger dados jovens, familiares, financeiros e pastorais;
- garantir que o backend filtre payloads antes do Vue/Inertia;
- definir quais tabelas entram na primeira fase real;
- definir quais tabelas ficam proibidas ou adiadas.

Esta etapa é exclusivamente documental/técnica.

Não foram criadas migrations, models, controllers, services, policies, rotas, seeders ou alterações de banco.

## 2. Decisão sobre Resgatados/Jovens

### 2.1. Estruturas existentes

A auditoria do banco atual mostrou que já existem:

- `departments`;
- `department_people`;
- model `Department`;
- model `DepartmentPerson`.

O model `DepartmentPerson` já menciona o contexto dos Resgatados e categorias como:

- `junior`;
- `jovem`.

Também já existem campos úteis para liderança e gestão de vínculo:

- `role`;
- `category`;
- `status`;
- `is_leader`;
- `is_assistant`;
- `can_manage_department`.

### 2.2. Decisão técnica

A primeira fase da Minha Caminhada **não deve criar tabela `youth_teams` para representar o grupo Resgatados**.

A decisão é:

- reaproveitar `departments` como base de ministérios/departamentos;
- reaproveitar `department_people` para identificar quem pertence aos Resgatados;
- usar `category` em `department_people` para diferenciar `junior` e `jovem`;
- usar `role`, `is_leader`, `is_assistant` e `can_manage_department` para liderança jovem;
- fazer o futuro `WalkingPermissionService` consultar `departments` e `department_people` para descobrir se a pessoa tem trilho jovem.

### 2.3. Regra de implementação futura

A Minha Caminhada deve considerar que o trilho jovem é liberado a partir de vínculos reais com Resgatados/departamentos e não por mock, flag visual ou condição definida apenas no Vue.

A tabela `youth_teams` só deve ser considerada depois, se houver necessidade real de subdividir os Resgatados em equipes internas gamificadas.

## 3. Decisão sobre equipes jovens

### 3.1. Decisão técnica

A primeira fase real das migrations da Minha Caminhada **não deve criar**:

- `youth_teams`;
- `youth_team_members`.

Nesta primeira fase, equipes jovens ficam como pendência futura.

A Caminhada Jovem pode existir sem equipes jovens.

Pontos de equipe jovem não serão implementados inicialmente, apenas preparados conceitualmente na documentação e nas regras futuras de permissão.

### 3.2. Possíveis caminhos futuros

Se futuramente houver equipes internas dos Resgatados, será necessário decidir se elas serão:

1. subdepartamentos;
2. tabela própria `youth_teams`;
3. grupos internos vinculados ao department Resgatados.

### 3.3. Regra de proteção

Não criar tabelas de equipe antes de definir a experiência real dos jovens.

Também não criar colunas de pontuação coletiva na primeira fase, pois isso induziria uma experiência de equipe que ainda não foi validada no desenho real do ministério.

## 4. Decisão sobre histórico

### 4.1. Estrutura existente

Já existe:

- `activity_logs`.

A auditoria mostrou que `activity_logs` registra ações administrativas/técnicas, com campos de ação, módulo, descrição, valores antigos, valores novos, IP e user agent.

### 4.2. Decisão técnica

A primeira fase da Minha Caminhada **não deve usar `activity_logs` como histórico visível principal da caminhada**.

A decisão é:

- manter `activity_logs` como auditoria administrativa/técnica;
- criar futuramente `walking_history_events` para a timeline visível da caminhada;
- permitir que `walking_history_events` referencie sources reais por `source_type` e `source_id`;
- permitir que ações administrativas sobre histórico/pontos também sejam auditadas por `activity_logs` quando necessário.

### 4.3. Justificativa

A separação é necessária porque:

- `activity_logs` registra ações administrativas;
- histórico da caminhada é experiência do membro;
- misturar os dois pode expor dados internos;
- misturar os dois pode tornar a timeline pastoral confusa;
- histórico visível precisa de regras próprias de visibilidade por pessoa, família, trilho e permissão.

## 5. Decisão sobre presença/eventos

### 5.1. Estado atual

A auditoria encontrou rotas e intenções visuais como:

- `/familia-resgate/cultos/confirmar-presenca`;
- `/familia-resgate/cultos/registrar-biblia`.

Porém, não foram encontradas migrations dedicadas de presença, attendance ou eventos reais.

### 5.2. Decisão técnica

A primeira fase da Minha Caminhada **não deve criar tabela definitiva de presença dentro da Minha Caminhada**.

A decisão é:

- `walking_point_logs` deve ter `source_type` e `source_id` nullable;
- `source_type` e `source_id` devem permitir origem real futura;
- pontuação não deve nascer inventando presença;
- seeders e services futuros devem ser mínimos e não fingir dados reais.

### 5.3. Sources futuras possíveis

Quando o módulo real de presença/eventos existir, `walking_point_logs` poderá referenciar:

- `attendance`;
- `event_checkin`;
- `bible_registration`;
- `service_scale`;
- `devotional_entry`;
- `visitor_followup`.

### 5.4. Regra de proteção

Pontuação não deve nascer inventando presença.

Ela deve aguardar origem real ou registrar apenas eventos controlados, validados e claramente identificados.

## 6. Decisão sobre intercessão

### 6.1. Decisão técnica

Intercessão entra inicialmente apenas como categoria nas regras/conquistas, não como módulo completo.

A primeira fase **não deve criar tabela específica de intercessão dentro da Minha Caminhada**.

A decisão é:

- não criar ranking de intercessores;
- não expor atendimento/intercessão pessoal;
- permitir `category = intercession` em `walking_point_rules`;
- permitir pontos de intercessão em `walking_point_logs` somente quando houver fonte/validação segura;
- manter avaliação privada de atendimento como fase futura com desenho próprio.

### 6.2. Regras de segurança pastoral

Intercessão deve respeitar:

- sigilo;
- mínimo necessário;
- validação pastoral;
- ausência de ranking público;
- ausência de exposição de pedidos, crises, aconselhamentos ou pessoas atendidas.

## 7. Decisão sobre financeiro

### 7.1. Estruturas existentes

Já existem:

- `financial_transactions`;
- `credits`;
- `receipts`;
- `payment_proofs`;
- `financial_correction_requests`.

### 7.2. Decisão técnica

A Minha Caminhada **não deve criar tabelas financeiras**.

A decisão é:

- não criar financeiro paralelo;
- não criar conquistas financeiras públicas;
- não puxar dados financeiros para gamificação pública;
- não enviar dados financeiros no payload sem permissão explícita.

### 7.3. Regra para conquistas financeiras futuras

Se futuramente existir conquista financeira, ela deve ser:

- privada;
- discreta;
- sem linguagem espiritualizada;
- filtrada por policy;
- não enviada ao frontend sem permissão;
- separada de qualquer ranking, destaque público ou comparação entre pessoas.

## 8. Decisão sobre permissões

### 8.1. Estruturas existentes

Já existem:

- `access_profiles`;
- `permissions`;
- `access_profile_permission`;
- `access_profile_user`;
- métodos no model `User`, como `hasPermission` e `hasAccessProfile`.

### 8.2. Decisão técnica

A Minha Caminhada **não deve criar sistema paralelo de permissões**.

A decisão é criar futuramente `WalkingPermissionService` usando:

- `User`;
- `Person`;
- `AccessProfile`;
- `Permission`;
- `GuardianShip`;
- `DepartmentPerson`.

Policies da Minha Caminhada devem consultar permissões existentes e escopos reais.

Permissões específicas futuras, quando necessárias, devem ser criadas dentro da tabela `permissions`, sem sistema paralelo.

### 8.3. Exemplos de permissões futuras

Exemplos possíveis:

- `walking.view.general`;
- `walking.view.youth`;
- `walking.view.youth_team`;
- `walking.view.family_child`;
- `walking.view.sensitive`;
- `walking.manage.rules`;
- `walking.validate.points`;
- `walking.approve.highlights`;
- `walking.manage.mentor_templates`.

### 8.4. Regra de payload

O backend deve filtrar permissões antes do payload Inertia.

O Vue não deve receber dados jovens, familiares, financeiros, pastorais ou administrativos quando o usuário não tiver permissão.

## 9. Decisão sobre tabelas da primeira fase

### 9.1. Tabelas que devem ser criadas na primeira fase real

A primeira fase real das migrations da Minha Caminhada deve criar:

1. `walking_journeys`;
2. `walking_levels`;
3. `walking_point_rules`;
4. `walking_point_logs`;
5. `walking_achievements`;
6. `person_walking_achievements`;
7. `walking_highlights`;
8. `walking_mentor_response_templates`;
9. `walking_mentor_response_logs`;
10. `walking_history_events`.

### 9.2. Tabelas que não devem ser criadas ainda

A primeira fase não deve criar:

- `youth_teams`;
- `youth_team_members`;
- tabela de presença/eventos;
- tabela financeira;
- tabela própria de intercessão;
- tabela de avaliações privadas de intercessão.

## 10. Desenho final das tabelas da primeira fase

### 10.1. `walking_journeys`

Campos recomendados:

- `id`;
- `key` unique;
- `name`;
- `type` enum `general`, `youth`;
- `description` nullable;
- `is_active` boolean default `true`;
- timestamps.

Observação: não incluir `youth_team` no `type` nesta fase, já que equipes ficam para depois.

### 10.2. `walking_levels`

Campos recomendados:

- `id`;
- `walking_journey_id` foreign;
- `level_number` unsigned integer;
- `name`;
- `description` nullable;
- `required_points` unsigned integer default `0`;
- `icon` nullable;
- `color` nullable;
- `is_active` boolean default `true`;
- timestamps;
- unique `walking_journey_id` + `level_number`.

### 10.3. `walking_point_rules`

Campos recomendados:

- `id`;
- `walking_journey_id` nullable foreign;
- `key` unique;
- `name`;
- `description` nullable;
- `category`;
- `points` integer default `0`;
- `validation_type` enum `automatic`, `manual`, `leader_validation`, `secretary_validation`, `pastoral_validation`;
- `source_origin` nullable;
- `daily_limit` nullable;
- `weekly_limit` nullable;
- `monthly_limit` nullable;
- `counts_for_level` boolean default `true`;
- `counts_for_highlight` boolean default `true`;
- `is_fixed_system_rule` boolean default `false`;
- `can_edit_points` boolean default `true`;
- `is_active` boolean default `true`;
- timestamps.

Remover da primeira fase:

- `team_points`;
- `counts_for_team`.

### 10.4. `walking_point_logs`

Campos recomendados:

- `id`;
- `person_id` foreign;
- `user_id` nullable foreign;
- `walking_journey_id` foreign;
- `walking_point_rule_id` nullable foreign;
- `family_id` nullable foreign;
- `source_type` nullable;
- `source_id` nullable;
- `category`;
- `points` integer;
- `status` enum `pending`, `approved`, `rejected`, `cancelled`;
- `validation_type` nullable;
- `approved_by` nullable foreign users;
- `approved_at` nullable timestamp;
- `rejected_by` nullable foreign users;
- `rejected_at` nullable timestamp;
- `notes` nullable;
- `metadata` json nullable;
- timestamps.

Remover da primeira fase:

- `youth_team_id`;
- `team_points`.

### 10.5. `walking_achievements`

Campos recomendados:

- `id`;
- `key` unique;
- `name`;
- `description` nullable;
- `type` enum `general`, `youth`, `administrative`, `financial`, `pastoral_sensitive`;
- `category`;
- `visibility` enum `public_to_user`, `private_to_user`, `leadership_only`, `hidden`;
- `icon` nullable;
- `color` nullable;
- `criteria` json nullable;
- `requires_validation` boolean default `false`;
- `is_active` boolean default `true`;
- timestamps.

Observação: manter types sensíveis no catálogo, mas nunca enviar sem policy.

### 10.6. `person_walking_achievements`

Campos recomendados:

- `id`;
- `person_id` foreign;
- `walking_achievement_id` foreign;
- `walking_journey_id` nullable foreign;
- `status` enum `received`, `in_progress`, `locked`, `hidden`, `pending_validation`;
- `progress_current` nullable integer;
- `progress_target` nullable integer;
- `awarded_by` nullable foreign users;
- `awarded_at` nullable timestamp;
- `metadata` json nullable;
- timestamps;
- unique `person_id` + `walking_achievement_id` + `walking_journey_id`.

### 10.7. `walking_highlights`

Campos recomendados:

- `id`;
- `person_id` nullable foreign;
- `family_id` nullable foreign;
- `walking_journey_id` nullable foreign;
- `type` enum `general`, `youth`, `intercession`;
- `category`;
- `title`;
- `description` nullable;
- `period_type` enum `weekly`, `monthly`, `yearly`;
- `period_start` date;
- `period_end` date;
- `visibility` enum `public_to_user`, `private_to_user`, `leadership_only`, `hidden`;
- `generated_by` nullable foreign users;
- `approved_by` nullable foreign users;
- `approved_at` nullable timestamp;
- `is_visible` boolean default `false`;
- `metadata` json nullable;
- timestamps.

Remover da primeira fase:

- `youth_team_id`;
- `youth_team` type.

### 10.8. `walking_mentor_response_templates`

Campos recomendados:

- `id`;
- `analysis_type`;
- `key`;
- `title`;
- `body`;
- `tone` nullable;
- `journey_type` enum `general`, `youth`;
- `is_active` boolean default `true`;
- timestamps.

### 10.9. `walking_mentor_response_logs`

Campos recomendados:

- `id`;
- `person_id` foreign;
- `family_id` nullable foreign;
- `walking_journey_id` nullable foreign;
- `analysis_type`;
- `walking_mentor_response_template_id` nullable foreign;
- `response_variant` nullable;
- `context_summary` json nullable;
- `status` enum `displayed`, `ignored`, `completed`, `replaced`;
- `displayed_at` nullable timestamp;
- timestamps.

### 10.10. `walking_history_events`

Campos recomendados:

- `id`;
- `person_id` foreign;
- `walking_journey_id` foreign;
- `event_type`;
- `title`;
- `description` nullable;
- `points` nullable integer;
- `source_type` nullable;
- `source_id` nullable;
- `visibility` enum `public_to_user`, `private_to_user`, `leadership_only`, `hidden`;
- `metadata` json nullable;
- `occurred_at` timestamp;
- timestamps.

## 11. Foreign keys finais

### 11.1. Referências principais

Definições:

- `person_id` sempre referencia `people.id`;
- `user_id` referencia `users.id`;
- `approved_by` referencia `users.id`;
- `rejected_by` referencia `users.id`;
- `awarded_by` referencia `users.id`;
- `generated_by` referencia `users.id`;
- `family_id` referencia `families.id`;
- `walking_journey_id` referencia `walking_journeys.id`;
- `walking_point_rule_id` referencia `walking_point_rules.id`;
- `walking_achievement_id` referencia `walking_achievements.id`;
- `walking_mentor_response_template_id` referencia `walking_mentor_response_templates.id`.

### 11.2. Estratégia de delete

Usar `nullOnDelete` quando o registro histórico precisa permanecer.

Usar `cascadeOnDelete` apenas em tabelas dependentes que não devem sobreviver sem o pai, com cuidado.

Para logs, histórico e pontuação, evitar cascade destrutivo em `person_id`.

Recomendação: preferir `restrict` ou `nullOnDelete` quando fizer sentido, porque registros históricos não devem sumir facilmente.

## 12. Seeders mínimos futuros

### 12.1. `WalkingJourneySeeder`

Criar apenas jornadas base:

- `general`;
- `youth`.

### 12.2. `WalkingLevelSeeder`

Criar:

- 20 níveis gerais;
- 20 níveis jovens.

### 12.3. `WalkingPointRuleSeeder`

Criar:

- regras iniciais gerais;
- regras iniciais jovens;
- intercessão como regra futura, inativa ou controlada.

### 12.4. `WalkingAchievementSeeder`

Criar:

- conquistas gerais;
- conquistas jovens;
- conquistas sensíveis desativadas/ocultas quando necessário.

### 12.5. `WalkingMentorTemplateSeeder`

Criar:

- respostas pré-aprovadas gerais;
- respostas pré-aprovadas jovens.

### 12.6. Regra para seeders

Seeders não devem criar:

- dados de pessoa;
- pontos fake;
- conquistas fake para membros reais;
- presença fake;
- histórico fake de membros reais.

## 13. Ordem das migrations

Ordem futura recomendada:

1. `create_walking_journeys_table`;
2. `create_walking_levels_table`;
3. `create_walking_point_rules_table`;
4. `create_walking_point_logs_table`;
5. `create_walking_achievements_table`;
6. `create_person_walking_achievements_table`;
7. `create_walking_highlights_table`;
8. `create_walking_mentor_response_templates_table`;
9. `create_walking_mentor_response_logs_table`;
10. `create_walking_history_events_table`.

## 14. O que fica proibido na primeira implementação

Na primeira implementação fica proibido:

- criar `youth_teams` sem decisão futura;
- criar `youth_team_members` sem decisão futura;
- criar financeiro paralelo;
- criar responsáveis paralelos;
- criar presença fake como dado real;
- criar tabela própria de intercessão;
- criar tabela de avaliações privadas de intercessão;
- enviar dados jovens para membro comum;
- enviar dados sensíveis para frontend;
- criar ranking espiritual;
- criar ranking de intercessores;
- transformar conquistas financeiras em badge público;
- usar Vue como camada principal de segurança;
- duplicar sistema de permissões;
- criar pontos de equipe jovem na primeira fase;
- usar `activity_logs` como timeline visível sem separação técnica.

## 15. Próxima etapa após este documento

Depois deste documento, a próxima etapa segura será:

**ETAPA — CRIAR MIGRATIONS BASE DA MINHA CAMINHADA**

Essa etapa só deve ocorrer após aprovação deste documento.

A implementação futura deverá criar apenas as tabelas da primeira fase, sem services ainda, sem controllers funcionais e sem substituir mocks por dados reais antes das policies e services correspondentes.

## 16. Confirmação de escopo preservado

Nesta etapa documental:

- nenhuma migration foi criada;
- nenhuma migration foi alterada;
- nenhum model foi criado;
- nenhum model foi alterado;
- nenhum controller foi criado;
- nenhum controller foi alterado;
- nenhum service foi criado;
- nenhuma policy foi criada ou alterada;
- nenhuma rota foi alterada;
- nenhum arquivo Vue foi alterado;
- o banco não foi alterado;
- `migrate` não foi executado;
- `migrate:fresh` não foi executado;
- seeders não foram executados;
- commit não foi feito;
- push não foi feito.
