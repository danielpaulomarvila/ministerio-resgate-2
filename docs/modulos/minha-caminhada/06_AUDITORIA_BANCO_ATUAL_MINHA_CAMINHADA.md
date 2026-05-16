# Auditoria do Banco Atual — Minha Caminhada

## 1. Objetivo da auditoria

Esta auditoria registra o estado atual do banco de dados e da estrutura backend do projeto antes da criação de migrations específicas para o módulo **Minha Caminhada**.

Os objetivos principais são:

- Auditar o banco/projeto atual antes de criar migrations da Minha Caminhada.
- Evitar duplicidade de tabelas, models, controllers, policies e regras já existentes.
- Identificar o que deve ser reaproveitado da estrutura atual.
- Identificar lacunas reais que ainda precisam de implementação futura.
- Apoiar a decisão técnica sobre o desenho final das migrations da Minha Caminhada.

## 2. Escopo da auditoria

Esta etapa foi realizada em modo somente leitura.

Não foram realizados:

- migrations;
- alterações de banco;
- alterações de código;
- criação ou alteração de models;
- criação ou alteração de controllers;
- criação ou alteração de services;
- criação ou alteração de policies;
- alterações em Vue;
- alterações em rotas;
- seeders;
- commit;
- push.

As únicas ações documentais desta etapa são a criação deste relatório e o registro correspondente no histórico diário do projeto.

## 3. Migrations encontradas

Foram encontradas as seguintes migrations em `database/migrations`:

```text
0001_01_01_000000_create_users_table.php
0001_01_01_000001_create_cache_table.php
0001_01_01_000002_create_jobs_table.php
2026_05_09_165327_create_people_table.php
2026_05_09_165336_alter_users_table_add_person_id.php
2026_05_09_165337_create_departments_table.php
2026_05_09_165337_create_families_table.php
2026_05_09_165337_create_family_members_table.php
2026_05_09_165337_create_member_profiles_table.php
2026_05_09_165338_create_guardianships_table.php
2026_05_09_165339_create_activity_logs_table.php
2026_05_09_165339_create_system_alerts_table.php
2026_05_09_165340_create_department_people_table.php
2026_05_09_200824_create_person_documents_table.php
2026_05_09_200901_create_person_addresses_table.php
2026_05_10_104608_adjust_families_and_family_members_structure.php
2026_05_10_120000_adjust_guardianships_structure.php
2026_05_10_124945_add_resolution_notes_to_system_alerts_table.php
2026_05_10_135436_create_secretary_requests_table.php
2026_05_10_150022_add_cancelled_fields_to_secretary_requests_table.php
2026_05_10_160818_alter_users_table_add_access_fields.php
2026_05_10_203817_create_access_profiles_table.php
2026_05_10_203859_create_permissions_table.php
2026_05_10_203947_create_access_profile_permission_table.php
2026_05_10_204028_create_access_profile_user_table.php
2026_05_11_093941_alter_departments_table_add_fields.php
2026_05_11_103000_alter_departments_table_add_fields.php
2026_05_11_103100_alter_department_people_table_add_fields.php
2026_05_14_123700_create_financial_transactions_table.php
2026_05_14_123710_create_payment_proofs_table.php
2026_05_14_123720_create_receipts_table.php
2026_05_14_123730_create_financial_correction_requests_table.php
2026_05_14_123740_create_credits_table.php
```

### 3.1. Áreas cobertas pelas migrations atuais

- **Pessoas e usuários:** `people`, `users`, `member_profiles`, `person_documents`, `person_addresses`.
- **Famílias e responsáveis:** `families`, `family_members`, `guardianships`.
- **Departamentos e vínculos ministeriais:** `departments`, `department_people`.
- **Auditoria e alertas:** `activity_logs`, `system_alerts`.
- **Financeiro:** `financial_transactions`, `payment_proofs`, `receipts`, `financial_correction_requests`, `credits`.
- **Perfis e permissões:** `access_profiles`, `permissions`, `access_profile_permission`, `access_profile_user`.

### 3.2. Lacunas aparentes nas migrations

Não foram encontradas migrations específicas para:

- `walking_journeys`;
- `walking_levels`;
- `walking_point_rules`;
- `walking_point_logs`;
- `walking_achievements`;
- `person_walking_achievements`;
- `walking_highlights`;
- `walking_mentor_response_templates`;
- `walking_mentor_response_logs`;
- `walking_history_events`;
- `youth_teams`;
- `youth_team_members`.

Também não foram identificadas migrations dedicadas a conquistas, badges, pontuação, presença, intercessão ou histórico próprio da Minha Caminhada.

## 4. Models encontrados

Foram encontrados os seguintes models em `app/Models`:

```text
AccessProfile
ActivityLog
Credit
Department
DepartmentPerson
Family
FamilyMember
FinancialCorrectionRequest
FinancialTransaction
GuardianShip
MemberProfile
PaymentProof
Permission
Person
PersonAddress
PersonDocument
Receipt
SecretaryRequest
SystemAlert
User
```

## 5. Achados principais por área

### 5.1. Pessoas

O model `Person` é a base principal para a Minha Caminhada.

Ele já possui relações com:

- usuário de login;
- perfil de membro;
- família;
- departamentos;
- responsáveis;
- financeiro;
- alertas do sistema;
- documentos;
- moradas.

Conclusão: qualquer dado individual da Minha Caminhada deve partir de `Person`, evitando criar uma nova entidade paralela de pessoa ou participante.

### 5.2. Famílias e responsáveis

As estruturas `families`, `family_members` e `guardianships` já existem e devem ser reaproveitadas.

Achados principais:

- `families` representa núcleos familiares.
- `family_members` representa vínculos entre pessoas e famílias.
- `guardianships` representa responsabilidade formal sobre menores.
- `GuardianShip` contém permissões específicas como responsável legal, responsável financeiro, autorização de login, visualização financeira e recebimento de débitos de cantina.

Conclusão: não criar tabela paralela de responsáveis para a Minha Caminhada. `GuardianShip` é essencial para pais/responsáveis verem filhos vinculados e deve ser a base das regras futuras de visibilidade.

### 5.3. Jovens e Resgatados

As estruturas `departments` e `department_people` parecem ser a base atual mais próxima para Jovens/Resgatados.

Achados principais:

- `DepartmentPerson` menciona categorias `junior` e `jovem` para o departamento Resgatados.
- `department_people` já possui campos como `role`, `category`, `status`, `is_leader`, `is_assistant` e `can_manage_department`.
- O vínculo em departamento não cria membro ou usuário automaticamente.

Conclusão: não criar `youth_teams` ou `youth_team_members` antes de decidir se equipes jovens serão tabelas próprias ou derivadas de `departments` e `department_people`.

### 5.4. Financeiro

As estruturas financeiras já existem.

Foram identificados:

- `financial_transactions`;
- `credits`;
- `receipts`;
- `payment_proofs`;
- `financial_correction_requests`.

Conclusão: não criar financeiro paralelo para a Minha Caminhada. Se houver conquistas financeiras futuras, elas devem ser privadas, mínimas, derivadas de regras bem definidas e protegidas por policy forte.

### 5.5. Logs e histórico

A tabela/model `activity_logs` existe para auditoria administrativa.

Achados principais:

- `ActivityLog` registra ações de usuários, módulo, descrição, valores antigos, valores novos, IP e user agent.
- Essa estrutura é adequada para auditoria administrativa.
- Não deve ser usada diretamente como histórico visível da caminhada sem uma decisão técnica explícita.

Conclusão: `walking_history_events` ainda parece necessário, ou precisa de integração clara com `activity_logs`, separando log administrativo de histórico pastoral/visível ao membro.

### 5.6. Permissões

Já existem estruturas de perfis e permissões:

- `access_profiles`;
- `permissions`;
- `access_profile_permission`;
- `access_profile_user`;
- métodos de autorização no model `User`.

O model `User` já possui métodos como `hasAccessProfile`, `hasPermission` e `isSuperAdmin`.

Conclusão: o futuro `WalkingPermissionService` deve usar essa base e não criar um sistema paralelo de permissões.

## 6. Controllers, Requests e Policies auditados

### 6.1. Controllers relevantes

Foram identificados como relevantes para a Minha Caminhada:

- `PersonController`;
- `FamilyController`;
- `GuardianshipController`;
- `Secretaria/DepartmentController`;
- `Secretaria/DepartmentPersonController`;
- `Familia/FamilyFinancialController`;
- `Secretaria/UserAccessProfileController`;
- `Secretaria/AccessProfileController`.

Não foi identificado controller backend específico da Minha Caminhada nesta auditoria.

### 6.2. Requests relevantes

Foram identificados como relevantes:

- `StorePersonRequest` / `UpdatePersonRequest`;
- `StoreFamilyRequest` / `UpdateFamilyRequest`;
- `StoreFamilyMemberRequest`;
- `StoreGuardianshipRequest` / `UpdateGuardianshipRequest`;
- `StoreDepartmentRequest` / `UpdateDepartmentRequest`;
- `StoreDepartmentPersonRequest` / `UpdateDepartmentPersonRequest`;
- `StoreSecretaryUserAccessRequest` / `UpdateSecretaryUserAccessRequest`.

Não foram identificados requests específicos para pontuação, conquistas, destaques, mentor ou histórico da Minha Caminhada.

### 6.3. Policies relevantes

Foram identificadas como relevantes:

- `PersonPolicy`;
- `FamilyPolicy`;
- `FamilyMemberPolicy`;
- `GuardianshipPolicy`;
- `DepartmentPolicy`;
- `FinancialTransactionPolicy`;
- `ActivityLogPolicy`;
- `AccessProfilePolicy`.

Lacuna identificada: ainda não existem policies específicas da Minha Caminhada, como policies para jornada, pontos, conquistas, destaques, mentor, histórico ou equipes jovens.

## 7. Rotas auditadas

As rotas `familia-resgate` foram auditadas parcialmente por `route:list` escopado e leitura seletiva de `routes/web.php`.

Achados principais:

- A Minha Caminhada ainda usa rotas Inertia/closures em `routes/web.php`.
- As rotas atuais renderizam páginas Vue/props visuais, sem controller backend real da Minha Caminhada.
- Ainda não existe API real da Minha Caminhada.
- O comando `route:list` para API travou por I/O.
- A leitura direta de `routes/api.php` mostrou apenas `/api/health` como rota ativa de API.

Rotas atuais relevantes da Minha Caminhada incluem:

- `/familia-resgate/minha-caminhada`;
- `/familia-resgate/minha-caminhada/conquistas`;
- `/familia-resgate/minha-caminhada/nivel`;
- `/familia-resgate/minha-caminhada/geral`;
- `/familia-resgate/minha-caminhada/jovem`;
- `/familia-resgate/minha-caminhada/historico`;
- `/familia-resgate/minha-caminhada/mentor`;
- `/familia-resgate/minha-caminhada/regras`;
- `/familia-resgate/minha-caminhada/ranking`;
- `/familia-resgate/minha-caminhada/mapa`;
- `/familia-resgate/minha-caminhada/geral/mapa`;
- `/familia-resgate/minha-caminhada/jovem/mapa`;
- `/familia-resgate/minha-caminhada/geral/niveis/{level}`;
- `/familia-resgate/minha-caminhada/jovem/niveis/{level}`;
- `/familia-resgate/minha-caminhada/niveis/{level}`;
- `/familia-resgate/minha-caminhada/destaques/mensal`;
- `/familia-resgate/minha-caminhada/pontuacao`;
- `/familia-resgate/minha-caminhada/presencas`;
- `/familia-resgate/minha-caminhada/regras-de-pontos`.

## 8. Banco e tabelas

Foram identificadas as seguintes tabelas no schema `ministerio_resgate`:

```text
access_profile_permission
access_profile_user
access_profiles
activity_logs
cache
cache_locks
credits
department_people
departments
failed_jobs
families
family_members
financial_correction_requests
financial_transactions
guardianships
job_batches
jobs
member_profiles
migrations
password_reset_tokens
payment_proofs
people
permissions
person_addresses
person_documents
receipts
secretary_requests
sessions
system_alerts
users
```

Observação: o comando `Schema::getTableListing()` também retornou tabelas de outros schemas, como `Exercicios` e `Exercicios_b_2`. Próximas auditorias devem filtrar explicitamente o schema principal quando necessário.

## 9. Resultado do migrate:status

O comando `php artisan migrate:status --no-ansi` foi executado em modo seguro.

Resultado:

- todas as migrations listadas estavam com status `Ran`;
- nenhuma divergência foi identificada entre as migrations listadas e o status de execução;
- nenhuma migration foi criada, alterada ou executada nesta etapa.

## 10. Comparação com o plano técnico 05

| Item planejado | Já existe? | Reaproveitar? | Criar novo? | Observações |
|---|---:|---:|---:|---|
| `walking_journeys` | Não | Não | Sim | Criar após confirmar desenho final dos trilhos. |
| `walking_levels` | Não | Não | Sim | Criar para níveis reais da caminhada geral e jovem. |
| `walking_point_rules` | Não | Não | Sim | Criar regras administráveis de pontuação. |
| `walking_point_logs` | Não | Parcial | Sim | Criar log próprio; pode consumir fontes já existentes futuramente. |
| `walking_achievements` | Não | Não | Sim | Criar catálogo de conquistas. |
| `person_walking_achievements` | Não | Não | Sim | Criar vínculo entre pessoa e conquista. |
| `walking_highlights` | Não | Não | Sim | Criar estrutura própria para destaques saudáveis. |
| `walking_mentor_response_templates` | Não | Não | Sim | Criar templates/respostas por regra para mentor. |
| `walking_mentor_response_logs` | Não | Parcial | Sim | Criar log próprio do mentor; `activity_logs` não substitui integralmente. |
| `walking_history_events` | Não | Parcial | Avaliar | Avaliar integração com `activity_logs`, mantendo separação entre histórico visível e auditoria administrativa. |
| `youth_teams` | Não explícito | Avaliar | Avaliar | Avaliar se será derivado de `departments` ou tabela própria. |
| `youth_team_members` | Não explícito | Avaliar | Avaliar | Avaliar se será derivado de `department_people` ou tabela própria. |
| `people` | Sim | Sim | Não | Base principal de pessoa. |
| `users` | Sim | Sim | Não | Base de login e permissões. |
| `member_profiles` | Sim | Sim | Não | Base de perfil de membro. |
| `families` | Sim | Sim | Não | Base familiar existente. |
| `family_members` | Sim | Sim | Não | Vínculo família-pessoa existente. |
| `guardianships` | Sim | Sim | Não | Base formal para responsáveis por menores. |
| `departments` | Sim | Sim | Não | Base provável para Resgatados/ministérios. |
| `department_people` | Sim | Sim | Não | Vínculo pessoa-departamento, incluindo categorias jovem/júnior. |
| `activity_logs` | Sim | Parcial | Não | Reaproveitar para auditoria administrativa, não como histórico visível sem decisão. |
| `system_alerts` | Sim | Parcial | Não | Reaproveitar para alertas, não para pontuação/histórico. |
| Financeiro | Sim | Sim | Não | Não duplicar `financial_transactions`, `credits`, `receipts`, `payment_proofs` e correções. |

## 11. Riscos de duplicidade

### 11.1. Jovens e equipes

Há risco de duplicar jovens/equipes caso sejam criadas tabelas `youth_teams` e `youth_team_members` sem decidir antes se `departments` e `department_people` já cumprem esse papel.

### 11.2. Responsáveis

Há risco de duplicar responsáveis caso seja criada tabela própria de pais/responsáveis para a Minha Caminhada. A estrutura `guardianships` já existe e deve ser a base da regra.

### 11.3. Financeiro

Há risco de duplicar financeiro caso a Minha Caminhada crie registros paralelos para pagamentos, créditos, cantina, recibos ou pendências. O módulo financeiro já possui estrutura própria.

### 11.4. Logs e histórico

Há risco de confundir `activity_logs` com histórico visível da caminhada. `activity_logs` é auditoria administrativa; histórico pastoral/visível ao membro exige decisão técnica separada.

### 11.5. Pontuação sem origem real

Há risco de criar pontuação sem origem real de presença/eventos. Antes das migrations, é necessário decidir quais eventos geram pontos e de onde virão essas informações.

### 11.6. Permissões paralelas

Há risco de criar permissões paralelas se a Minha Caminhada não reaproveitar `access_profiles`, `permissions` e os métodos de autorização existentes em `User`.

## 12. Recomendação técnica final

Ainda não criar migrations da Minha Caminhada até decidir:

- como Resgatados estão cadastrados em `departments`;
- se equipes jovens serão `departments`, `department_people` ou tabelas novas;
- se `walking_history_events` será separado ou integrado com `activity_logs`;
- qual será a origem real de presença/eventos;
- quais foreign keys finais serão usadas;
- como `WalkingPermissionService` usará `access_profiles` e `permissions`.

Conclusão: ainda não é totalmente seguro partir para migrations. A próxima etapa deve ser uma decisão técnica sobre o desenho final das migrations da Minha Caminhada com base nesta auditoria, evitando duplicidade com estruturas já existentes.

## 13. Limitações da auditoria

Esta auditoria foi suficiente para mapear a estrutura principal, mas possui limitações:

- alguns comandos travaram por I/O;
- `route:list` de API não completou;
- algumas saídas foram truncadas pela ferramenta;
- nem todas as migrations foram lidas integralmente;
- nem todos os controllers foram lidos integralmente;
- nem todas as policies foram lidas integralmente;
- nem todos os requests foram lidos integralmente;
- o relatório foi produzido com leitura seletiva e comandos leves/escopados.

Essas limitações devem ser consideradas antes de qualquer implementação de migrations.

## 14. Confirmação de escopo preservado

Durante a auditoria e o registro documental:

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
