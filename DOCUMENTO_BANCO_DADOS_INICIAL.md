# DOCUMENTO BANCO DE DADOS INICIAL

## Visão Geral

Este documento descreve a estrutura inicial do banco de dados do sistema Ministério Resgate / Família Resgate. O banco foi projetado para ser a fundação de uma plataforma completa de gestão de igreja, preparada para crescer e escalar.

## Motivo de Separar People, Users e Member Profiles

### People (Pessoas)
- **O que é**: Tabela principal de pessoas. Qualquer pessoa cadastrada no sistema.
- **Pode ser**: Criança, adolescente, adulto, visitante, congregado, participante, membro, responsável familiar, líder, pastor, tesoureiro, secretário, voluntário.
- **Regra**: Pessoa pode existir sem usuário e sem ser membro.
- **Exemplo**: Uma criança de 5 anos pode estar cadastrada como pessoa, mas não tem usuário e não é membro.

### Users (Usuários)
- **O que é**: Pessoas que podem acessar o sistema com login.
- **Regra**: Um usuário pertence a uma pessoa, mas nem toda pessoa tem usuário.
- **Restrição**: Menores de 11 anos não podem ter usuário.
- **Exemplo**: Um adulto congregado pode ter usuário para acessar a área do membro, mas não tem perfil de membro porque não foi batizado.

### Member Profiles (Perfis de Membro)
- **O que é**: Tabela para membresia oficial da igreja.
- **Regra**: Só pode existir perfil de membro para pessoa batizada.
- **Restrição**: Pessoa não batizada não pode ser membro.
- **Exemplo**: Um adulto não batizado pode ter usuário, mas não tem member_profile. Somente quem é batizado pode ter perfil de membro.

## Tabelas do Banco de Dados

### 1. people
Tabela principal de pessoas.

**Campos principais:**
- `id`: Identificador único
- `full_name`: Nome completo da pessoa
- `preferred_name`: Nome como prefere ser chamada
- `last_name`: Apelido/Sobrenome
- `birth_date`: Data de nascimento para cálculo de idade
- `gender`: Gênero (male, female, other)
- `marital_status`: Estado civil (single, married, divorced, widowed, separated)
- `education_level`: Nível de escolaridade (elementary, high_school, college, postgraduate, other)
- `nationality`: Nacionalidade
- `birthplace`: Naturalidade
- `profession`: Profissão
- `occupation`: Ocupação
- `email`: E-mail único
- `primary_phone`: Telemóvel principal de contato
- `secondary_phone`: Telemóvel secundário
- `whatsapp`: WhatsApp
- `contact_notes`: Notas de contacto
- `photo_path`: Caminho da foto no storage
- `is_baptized`: Indica se foi batizado
- `baptism_date`: Data do batismo
- `conversion_date`: Data de conversão
- `invited_by_person_id`: Foreign key para people (quem convidou/influenciou/indicou)
- `person_status`: Status da pessoa (active, inactive, visitor, congregant, discipling, new_convert, regularization)
- `general_notes`: Anotações importantes
- `created_at`, `updated_at`, `deleted_at`: Timestamps e soft delete

**Índices:**
- `full_name`: Para busca por nome
- `birth_date`: Para cálculo de idade
- `person_status`: Para filtrar por status
- `email`: Para busca por email (único)
- `invited_by_person_id`: Para buscar quem foi convidado por uma pessoa

**Relacionamentos:**
- `invited_by_person_id` → people (foreign key, self-referential)
- `invitedPeople`: Uma pessoa pode ter convidado várias pessoas (hasMany)
- `document`: Uma pessoa tem um documento (hasOne - PersonDocument)
- `addresses`: Uma pessoa tem múltiplas moradas (hasMany - PersonAddress)
- `primaryAddress`: Morada principal (hasOne - PersonAddress com is_primary = true)

### 2. users
Tabela de usuários que acessam o sistema.

**Campos principais:**
- `id`: Identificador único
- `person_id`: Foreign key para people (nullable)
- `name`: Nome do usuário
- `email`: E-mail único
- `password`: Senha hash
- `status`: Status do usuário (active, inactive, suspended)
- `last_login_at`: Último login
- `created_at`, `updated_at`: Timestamps

**Relacionamentos:**
- `person_id` → people (foreign key)

**Índices:**
- `person_id`: Para vincular à pessoa
- `status`: Para filtrar usuários ativos
- `last_login_at`: Para rastrear acessos

### 3. person_documents
Tabela para documentos de identificação de pessoas (adaptada para Portugal).

**Campos principais:**
- `id`: Identificador único
- `person_id`: Foreign key para people (unique)
- `nif`: NIF (Número de Identificação Fiscal) - documento fiscal principal em Portugal
- `citizen_card_number`: Cartão de Cidadão
- `passport_number`: Passaporte
- `residence_permit_number`: Título de Residência
- `other_document`: Outro documento
- `document_notes`: Notas sobre documentos
- `created_at`, `updated_at`: Timestamps

**Relacionamentos:**
- `person_id` → people (foreign key)

**Índices:**
- `person_id`: Para vincular à pessoa
- `nif`: Para busca por NIF (único)

### 4. person_addresses
Tabela para moradas de pessoas (adaptada para Portugal).

**Campos principais:**
- `id`: Identificador único
- `person_id`: Foreign key para people
- `is_primary`: Indica se é a morada principal
- `country_name`: País (ex: Portugal)
- `district_name`: Distrito
- `municipality_name`: Concelho/Município
- `parish_name`: Freguesia
- `locality_name`: Localidade
- `locality_manual`: Localidade manual (para casos especiais)
- `address_line`: Rua/Avenida
- `door_number`: Número da porta
- `floor_or_unit`: Andar/Fração
- `address_complement`: Complemento
- `postal_code`: Código Postal (formato 0000-000)
- `full_address`: Endereço completo concatenado
- `created_at`, `updated_at`: Timestamps

**Relacionamentos:**
- `person_id` → people (foreign key)

**Índices:**
- `person_id`: Para buscar moradas de uma pessoa
- `is_primary`: Para buscar morada principal
- `postal_code`: Para busca por código postal
- `municipality_name`: Para busca por concelho

### 5. member_profiles
Tabela para membresia oficial.

**Campos principais:**
- `id`: Identificador único
- `person_id`: Foreign key para people (unique)
- `membership_number`: Número de carteira de membro (unique)
- `membership_date`: Data de entrada como membro
- `membership_status`: Status da membresia (active, inactive, suspended, transferred)
- `approved_by_user_id`: Usuário que aprovou (foreign key para users)
- `notes`: Anotações sobre a membresia
- `created_at`, `updated_at`, `deleted_at`: Timestamps e soft delete

**Relacionamentos:**
- `person_id` → people (foreign key)
- `approved_by_user_id` → users (foreign key)

**Índices:**
- `membership_number`: Para buscar por número de carteira
- `membership_status`: Para filtrar membros ativos
- `membership_date`: Para ordenar por data de entrada

### 6. families
Tabela de famílias (ajustada na Etapa 2).

**Campos principais:**
- `id`: Identificador único
- `name`: Nome da família (ex: Família Silva)
- `description`: Descrição da família
- `responsible_person_id`: Responsável principal (foreign key para people, nullable)
- `status`: Status da família (active, inactive)
- `notes`: Observações sobre a família
- `created_at`, `updated_at`, `deleted_at`: Timestamps e soft delete

**Relacionamentos:**
- `responsible_person_id` → people (foreign key)
- `belongsToMany(Person)` através de `family_members`

**Índices:**
- `name`: Para buscar por nome da família
- `status`: Para filtrar famílias ativas

**Observações da Etapa 2:**
- Campo renomeado de `main_responsible_person_id` para `responsible_person_id`
- Campo `description` adicionado
- Campos `address` e `phone` removidos (morada pertence à pessoa em `person_addresses`)
- Não guardar morada diretamente na família nesta etapa

### 7. family_members
Tabela de vínculo entre pessoas e famílias (pivot table, ajustada na Etapa 2).

**Campos principais:**
- `id`: Identificador único
- `family_id`: Foreign key para families
- `person_id`: Foreign key para people
- `role`: Papel familiar (father, mother, son, daughter, spouse, guardian, relative, other)
- `is_responsible`: Indica se é responsável familiar (boolean)
- `joined_at`: Data de entrada na família
- `left_at`: Data de saída da família (nullable, para histórico)
- `notes`: Observações sobre o vínculo (nullable)
- `created_at`, `updated_at`: Timestamps

**Relacionamentos:**
- `family_id` → families (foreign key)
- `person_id` → people (foreign key)

**Índices:**
- `family_id`: Para buscar membros de uma família
- `person_id`: Para buscar famílias de uma pessoa
- `role`: Para filtrar por papel familiar
- Unique: `[family_id, person_id, ends_at]` para evitar duplicidade de vínculo ativo

**Observações da Etapa 2:**
- Campo renomeado de `relationship_type` para `role`
- Campo renomeado de `is_main_responsible` para `is_responsible`
- Campo renomeado de `starts_at` para `joined_at`
- Campo renomeado de `ends_at` para `left_at`
- Campo `notes` adicionado
- Enum de `role` atualizado para incluir `relative`
- `is_responsible` indica responsável familiar, NÃO substitui guardianships (responsável legal)

### 8. guardianships
Tabela para responsáveis por menores de idade (ajustada na Etapa 3).

**Campos principais:**
- `id`: Identificador único
- `minor_person_id`: Pessoa menor de idade (foreign key para people)
- `guardian_person_id`: Responsável (foreign key para people)
- `relationship_type`: Tipo de relacionamento (father, mother, grandfather, grandmother, uncle, aunt, brother, sister, legal_guardian, tutor, other)
- `is_legal_guardian`: Responsável legal (boolean)
- `is_financial_responsible`: Responsável financeiro (para cantina, etc.) (boolean)
- `can_approve_changes`: Pode aprovar alterações no cadastro (boolean)
- `can_view_financial`: Pode ver dados financeiros (boolean)
- `can_authorize_login`: Pode autorizar login do menor (boolean) - adicionado na Etapa 3
- `can_receive_canteen_debts`: Recebe dívidas futuras da cantina (boolean) - adicionado na Etapa 3
- `starts_at`: Data de início do vínculo
- `ends_at`: Data de fim do vínculo (nullable, para histórico)
- `status`: Status da responsabilidade (active, inactive, ended)
- `notes`: Observações sobre o vínculo (nullable) - adicionado na Etapa 3
- `created_at`, `updated_at`: Timestamps

**Relacionamentos:**
- `minor_person_id` → people (foreign key)
- `guardian_person_id` → people (foreign key)

**Índices:**
- `minor_person_id`: Para buscar responsáveis de um menor
- `guardian_person_id`: Para buscar menores de um responsável
- `status`: Para filtrar por status
- Unique: `[minor_person_id, guardian_person_id, relationship_type, ends_at]` para evitar duplicidade de vínculo ativo

**Observações da Etapa 3:**
- Campo `can_authorize_login` adicionado para autorizar criação de usuário para Júnior
- Campo `can_receive_canteen_debts` adicionado para definir quem recebe cobranças de compras futuras
- Campo `notes` adicionado para observações sobre o vínculo
- Enum de `relationship_type` atualizado para incluir grandfather, grandmother, uncle, aunt, brother, sister, tutor
- Enum de `status` atualizado para incluir ended
- Diferença importante: guardianships ≠ family_members (ver DOCUMENTO_RESPONSAVEIS.md)

### 9. departments
Tabela de departamentos da igreja.

**Campos principais:**
- `id`: Identificador único
- `name`: Nome do departamento
- `slug`: Slug único para URL e referências
- `description`: Descrição do departamento
- `status`: Status do departamento (active, inactive)
- `created_at`, `updated_at`, `deleted_at`: Timestamps e soft delete

**Índices:**
- `name`: Para buscar por nome
- `slug`: Para referências URL
- `status`: Para filtrar departamentos ativos

**Departamentos iniciais:**
- Secretaria
- Tesouraria
- Cantina
- Louvor
- Mídia
- Resgatados
- Intercessão
- Recepção
- Pastoral

### 10. department_people
Tabela para vínculo entre pessoas e departamentos (pivot table).

**Campos principais:**
- `id`: Identificador único
- `department_id`: Foreign key para departments
- `person_id`: Foreign key para people
- `role_name`: Função (líder, auxiliar, integrante, vocal, músico, operador, tesoureiro, etc.)
- `category`: Categoria usada no departamento Resgatados (junior ou jovem)
- `starts_at`: Data de início no departamento
- `ends_at`: Data de saída do departamento (nullable, para histórico)
- `status`: Status do vínculo (active, inactive)
- `created_at`, `updated_at`: Timestamps

**Relacionamentos:**
- `department_id` → departments (foreign key)
- `person_id` → people (foreign key)

**Índices:**
- `department_id`: Para buscar pessoas de um departamento
- `person_id`: Para buscar departamentos de uma pessoa
- `role_name`: Para filtrar por função
- `category`: Para filtrar por categoria (júnior/jovem)
- `status`: Para filtrar vínculos ativos
- Unique: `[department_id, person_id, ends_at]` para evitar duplicidade

### 11. system_alerts
Tabela para alertas internos do sistema.

**Campos principais:**
- `id`: Identificador único
- `type`: Tipo do alerta (child_turning_11, missing_guardian, pending_member_update, etc.)
- `title`: Título do alerta
- `message`: Mensagem detalhada
- `related_person_id`: Pessoa relacionada (foreign key para people, nullable)
- `related_family_id`: Família relacionada (foreign key para families, nullable)
- `severity`: Severidade (low, medium, high, critical)
- `status`: Status do alerta (pending, in_progress, resolved, dismissed)
- `due_date`: Data limite para resolução
- `resolved_at`: Data de resolução
- `resolved_by_user_id`: Usuário que resolveu (foreign key para users, nullable)
- `created_at`, `updated_at`: Timestamps

**Relacionamentos:**
- `related_person_id` → people (foreign key)
- `related_family_id` → families (foreign key)
- `resolved_by_user_id` → users (foreign key)

**Índices:**
- `type`: Para filtrar por tipo de alerta
- `status`: Para filtrar alertas pendentes
- `severity`: Para priorizar alertas críticos
- `due_date`: Para ordenar por data limite
- `related_person_id`: Para buscar alertas de uma pessoa
- `related_family_id`: Para buscar alertas de uma família

### 12. activity_logs
Tabela para auditoria e histórico de ações.

**Campos principais:**
- `id`: Identificador único
- `user_id`: Usuário que realizou a ação (foreign key para users, nullable)
- `action`: Ação realizada (create, update, delete, login, etc.)
- `module`: Módulo afetado (people, families, members, etc.)
- `description`: Descrição detalhada da ação
- `old_values`: Valores antes da alteração (JSON)
- `new_values`: Valores depois da alteração (JSON)
- `ip_address`: Endereço IP do usuário
- `user_agent`: User agent (navegador/dispositivo)
- `created_at`, `updated_at`: Timestamps

**Relacionamentos:**
- `user_id` → users (foreign key)

**Índices:**
- `user_id`: Para buscar ações de um usuário
- `action`: Para filtrar por tipo de ação
- `module`: Para filtrar por módulo
- `created_at`: Para ordenar por data

## Regras de Idade

### Crianças menores de 11 anos
- **Não têm usuário**
- **Não são membros**
- **Ficam vinculadas à família**
- **Tudo passa pelos pais ou responsáveis**
- **Compras futuras na cantina devem ser cobradas no financeiro dos pais/responsáveis**
- **Podem aparecer no cadastro familiar**
- **Não possuem login próprio**

### De 11 até antes de 14 anos
- **Categoria**: Júnior
- **Grupo/Departamento**: Resgatados
- **Pode ter usuário**
- **Precisa de supervisão dos pais/responsáveis**
- **Pode ser membro se for batizado**
- **Continua vinculado à família**

### De 14 até antes de 18 anos
- **Categoria**: Jovem
- **Grupo/Departamento**: Resgatados
- **Tem usuário**
- **Não precisa de supervisão obrigatória dos pais/responsáveis**
- **Pode ser membro se for batizado**
- **Continua vinculado à família**

### Adultos com 18 anos ou mais
- **Podem ter usuário**
- **Só são membros se forem batizados**
- **Se não forem batizados, são congregados, participantes ou usuários sem membresia**

## Regra de Membresia

**Membro é somente quem é batizado.**

Esta é uma regra absoluta do sistema. Não existe membresia sem batismo, independente da idade.

## Regra de Usuários

- **Menores de 11 anos não podem ter usuário**
- **De 11 a 17 anos podem ter usuário, com supervisão dos pais**
- **Adultos podem ter usuário independente de membresia**
- **Um usuário pertence a uma pessoa**
- **Nem toda pessoa tem usuário**

## Regra de Responsáveis

A tabela `guardianships` define:
- **Responsável legal**: Pessoa com autoridade legal sobre o menor
- **Responsável financeiro**: Pessoa que paga pelas compras do menor (cantina, etc.)
- **Quem aprova alterações**: Pessoa que pode aprovar mudanças no cadastro do menor
- **Quem pode ver dados financeiros**: Pessoa com acesso a informações financeiras do menor

## Regra dos Júniores

- **Faixa etária**: 11 até antes de 14 anos
- **Departamento**: Resgatados
- **Category em department_people**: "junior"
- **Pode ter usuário**
- **Precisa de supervisão dos pais/responsáveis**
- **Pode ser membro se for batizado**
- **Continua vinculado à família**

## Regra dos Jovens

- **Faixa etária**: 14 até antes de 18 anos
- **Departamento**: Resgatados
- **Category em department_people**: "jovem"
- **Tem usuário**
- **Não precisa de supervisão obrigatória**
- **Pode ser membro se for batizado**
- **Continua vinculado à família**

## Alerta Automático dos 11 Anos

Quando uma criança menor de 11 anos estiver próxima de completar 11 anos, o sistema deve gerar alerta para a Secretaria.

**O alerta deve avisar que a criança está prestes a sair da fase de:**
- "Criança vinculada à família"

**E entrar na fase de:**
- "Júnior dos Resgatados"

**A Secretaria deverá:**
- Revisar cadastro
- Confirmar dados pessoais
- Confirmar responsáveis
- Verificar batismo/membresia quando aplicável
- Preparar criação de usuário
- Definir permissões iniciais
- Manter vínculo familiar
- Marcar supervisão dos pais/responsáveis

**Sugestão futura:**
- Gerar alertas 60 dias antes
- Gerar alertas 30 dias antes
- Gerar alerta no dia do aniversário de 11 anos

**Tipo de alerta em system_alerts:**
- `type`: "child_turning_11"
- `severity`: "medium"
- `related_person_id`: ID da criança

## Cuidados para Futuro Financeiro

### Compras de Crianças Menores de 11 Anos
- **Devem ser cobradas no financeiro dos pais/responsáveis financeiros**
- **Usar tabela guardianships para identificar responsável financeiro**
- **Campo `is_financial_responsible` indica quem paga**

### Repasse e Caixa para Depósito
- **Repasse**: Quando a Cantina ou outro setor entrega dinheiro físico para a Tesouraria
- **Caixa para Depósito**: Controle do dinheiro físico acumulado até ser depositado no banco
- **Permite depósito total, depósito parcial, uso de parte do dinheiro para pagamentos**
- **Requer comprovante obrigatório, auditoria e rastreamento**

## Relacionamentos Eloquent

### Person
- `hasOne(User)`: Uma pessoa pode ter um usuário
- `hasOne(MemberProfile)`: Uma pessoa pode ter um perfil de membro
- `hasOne(PersonDocument)`: Uma pessoa tem um registro de documentos
- `hasMany(PersonAddress)`: Uma pessoa pode ter múltiplas moradas
- `hasOne(PersonAddress as primaryAddress)`: Morada principal da pessoa
- `belongsToMany(Family)`: Uma pessoa pode pertencer a famílias
- `belongsToMany(Department)`: Uma pessoa pode estar em departamentos
- `hasMany(GuardianShip as minor)`: Uma pessoa (menor) pode ter responsáveis
- `hasMany(GuardianShip as guardian)`: Uma pessoa pode ser responsável por menores
- `hasMany(SystemAlert)`: Uma pessoa pode ter alertas do sistema

### User
- `belongsTo(Person)`: Um usuário pertence a uma pessoa
- `hasMany(ActivityLog)`: Um usuário pode ter múltiplos logs de atividade
- `hasMany(MemberProfile as approvedBy)`: Um usuário pode aprovar perfis de membro
- `hasMany(SystemAlert as resolvedBy)`: Um usuário pode resolver alertas

### MemberProfile
- `belongsTo(Person)`: Um perfil de membro pertence a uma pessoa
- `belongsTo(User as approvedBy)`: Um perfil de membro é aprovado por um usuário

### PersonDocument
- `belongsTo(Person)`: Um documento pertence a uma pessoa

### PersonAddress
- `belongsTo(Person)`: Uma morada pertence a uma pessoa

### Family
- `belongsTo(Person as mainResponsible)`: Uma família tem um responsável principal
- `belongsToMany(Person)`: Uma família pode ter várias pessoas vinculadas
- `hasMany(SystemAlert)`: Uma família pode ter alertas do sistema

### GuardianShip
- `belongsTo(Person as minor)`: Uma responsabilidade pertence a um menor
- `belongsTo(Person as guardian)`: Uma responsabilidade pertence a um responsável

### Department
- `belongsToMany(Person)`: Um departamento pode ter várias pessoas vinculadas

### SystemAlert
- `belongsTo(Person as relatedPerson)`: Um alerta pode estar relacionado a uma pessoa
- `belongsTo(Family as relatedFamily)`: Um alerta pode estar relacionado a uma família
- `belongsTo(User as resolvedBy)`: Um alerta é resolvido por um usuário

### ActivityLog
- `belongsTo(User)`: Um log de atividade pertence a um usuário

## Próximos Passos

1. ✅ Implementar módulo de Secretaria para gerenciar cadastros e aprovações (Etapa 4 - Painel inicial da Secretaria)
2. Implementar módulo Financeiro com suporte a responsáveis financeiros
3. Implementar módulo Cantina com cobrança em responsáveis de menores
4. Implementar sistema de alertas automáticos para crianças completando 11 anos
5. Implementar sistema de permissões por papel/função (Spatie Laravel Permission)
6. Implementar área do membro para acesso via usuário
7. Preparar arquitetura para futuro app iOS/Android (API Sanctum, PWA, Capacitor)

---

## Etapa 4 - Painel Inicial da Secretaria

### Controller

- **SecretaryDashboardController** - Controller responsável pelo painel inicial da Secretaria
  - Método `index()` - Busca dados reais do banco usando models existentes
  - Calcula indicadores sobre pessoas, famílias, responsáveis e faixa etária
  - Identifica atenções (pessoas sem família, menores sem responsável, cadastros incompletos)
  - Mostra listas recentes (pessoas, famílias, responsabilidades)
  - Acesso seguro para dados vazios

### Rota

- **GET /secretaria** - Rota protegida por autenticação
  - Nome da rota: `secretaria.dashboard`
  - Middleware: `auth`

### Página Vue

- **resources/js/Pages/Secretaria/Dashboard.vue** - Página do painel da Secretaria
  - Visual limpo e responsivo
  - Cards principais (Pessoas, Famílias, Responsáveis Ativos, Menores sem Responsável)
  - Cards por faixa etária (Crianças <11, Júniores 11-13, Jovens 14-17, Adultos 18+)
  - Atenções da Secretaria (Pessoas sem família, Sem data de nascimento, Sem telefone)
  - Crianças próximas dos 11 anos (lista com nome, idade e data de aniversário)
  - Listas recentes (Pessoas, Famílias, Responsabilidades)
  - Atalhos rápidos para cadastro e visualização

### Indicadores Calculados

1. Total de pessoas
2. Total de famílias
3. Total de responsáveis ativos
4. Crianças menores de 11 anos
5. Júniores (11-13 anos)
6. Jovens (14-17 anos)
7. Adultos (18+ anos)
8. Pessoas sem família
9. Menores sem responsável ativo
10. Crianças próximas dos 11 anos (próximos 60 dias)
11. Pessoas sem data de nascimento
12. Pessoas sem telefone
13. Pessoas sem email e sem telefone
14. Pessoas recentemente cadastradas (últimas 5)
15. Famílias recentemente criadas (últimas 5)
16. Responsabilidades recentes (últimas 5)

### Regra dos 11 Anos

O painel respeita e reforça a regra implementada na Etapa 3:
- Responsabilidade legal NÃO acaba automaticamente aos 11 anos
- Crianças menores de 11 anos não podem ter usuário próprio
- Ao completar 11 anos, a Secretaria deve revisar o cadastro
- O painel mostra aviso para crianças próximas de completar 11 anos

### Campo Quem Indicou / Convidou

O campo `invited_by_person_id` está preservado no banco para uso futuro:
- Não criou pontuação nesta etapa
- Não criou ranking nesta etapa
- Futuramente poderá alimentar frutos de evangelismo, acompanhamento de visitantes, reconhecimento, pontuação, Membro Destaque

### Não Implementado Nesta Etapa

- Sistema completo de alertas
- Aprovação de cadastro
- Notificações reais
- Gráficos complexos
- Relatórios exportáveis
- Usuário automático aos 11 anos
- Membro automático
- Departamento Resgatados automático
- Pontuação/gamificação de evangelismo
- Ranking de quem indicou pessoas

---

## Etapa 5 - Alertas Internos da Secretaria

### Tabela system_alerts

A tabela `system_alerts` já existia no projeto. Foi ajustada para suportar o sistema de alertas internos da Secretaria.

**Migration criada:**
- `2026_05_10_124945_add_resolution_notes_to_system_alerts_table.php`
- Adicionou campo `resolution_notes` (text, nullable) para guardar observações de resolução

**Estrutura da tabela:**
- `id`: Identificador único
- `type`: Tipo do alerta (string) - ex: child_turning_11, minor_without_guardian
- `title`: Título do alerta (string)
- `message`: Mensagem detalhada (text)
- `related_person_id`: Pessoa relacionada (nullable, foreign key para people)
- `related_family_id`: Família relacionada (nullable, foreign key para families)
- `severity`: Severidade (enum: low, medium, high, critical)
- `status`: Status (enum: pending, in_progress, resolved, dismissed)
- `due_date`: Data limite para resolução (date, nullable)
- `resolved_at`: Data de resolução (timestamp, nullable)
- `resolved_by_user_id`: Usuário que resolveu (nullable, foreign key para users)
- `resolution_notes`: Observações de resolução (text, nullable) - **Adicionado na Etapa 5**
- `created_at`: Data de criação (usado como detected_at)
- `updated_at`: Data de atualização

**Índices:**
- `type`
- `status`
- `severity`
- `due_date`
- `related_person_id`
- `related_family_id`

### Tipos de Alertas Implementados

1. **child_turning_11**: Crianças que completarão 11 anos nos próximos 60 dias
2. **minor_without_guardian**: Menores de 18 anos sem responsável legal ativo
3. **person_without_family**: Pessoas sem vínculo familiar ativo
4. **incomplete_registration**: Pessoas com cadastro incompleto (sem birth_date, primary_phone, ou ambos)
5. **guardianship_ending_soon**: Responsabilidades ativas com ends_at nos próximos 30 dias
6. **guardianship_expired**: Responsabilidades ativas com ends_at menor que hoje

### Regras de Dados

**Unicidade lógica:**
- type + related_person_id + status pending
- Se já existe alerta aberto igual, atualiza em vez de criar novo

**Histórico preservado:**
- Resolver NÃO apaga o alerta
- Alertas resolvidos permanecem com status resolved
- Alertas ignorados permanecem com status dismissed

**Dados reais:**
- Service usa dados reais do banco
- Não usa seeders fake
- Trata dados nulos de forma segura

### Model SystemAlert

**Métodos adicionados:**
- `isOpen()`: Verifica se status é pending
- `isIgnored()`: Verifica se status é dismissed
- `markAsResolved(int $userId, ?string $notes = null)`: Marca como resolvido com observações
- `markAsIgnored(int $userId, ?string $notes = null)`: Marca como ignorado com observações

**Relacionamentos:**
- `relatedPerson`: BelongsTo Person
- `relatedFamily`: BelongsTo Family
- `resolvedBy`: BelongsTo User

### Service SecretaryAlertService

**Local:** `app/Services/Secretaria/SecretaryAlertService.php`

**Métodos:**
- `regenerateAlerts()`: Regera todos os alertas com base nas regras atuais
- `generateChildTurning11Alerts()`: Gera alertas para crianças próximas dos 11 anos
- `generateMinorWithoutGuardianAlerts()`: Gera alertas para menores sem responsável
- `generatePersonWithoutFamilyAlerts()`: Gera alertas para pessoas sem família
- `generateIncompleteRegistrationAlerts()`: Gera alertas para cadastros incompletos
- `generateGuardianshipEndingSoonAlerts()`: Gera alertas para responsabilidades terminando
- `generateGuardianshipExpiredAlerts()`: Gera alertas para responsabilidades vencidas

---

## Etapa 6 - Solicitações e Revisões da Secretaria

### Tabela: secretary_requests

**Campos principais:**
- `id`: Chave primária
- `uuid`: UUID único (nullable, unique)
- `type`: Tipo da solicitação
- `status`: Status da solicitação (default: pending)
- `priority`: Prioridade (default: normal)
- `title`: Título da solicitação
- `description`: Descrição detalhada (nullable)
- `requester_user_id`: ID do usuário solicitante (nullable, foreign key para users)
- `requester_person_id`: ID da pessoa solicitante (nullable, foreign key para people)
- `target_type`: Tipo do alvo polimórfico (nullable)
- `target_id`: ID do alvo polimórfico (nullable)
- `related_alert_id`: ID do alerta relacionado (nullable, foreign key para system_alerts)
- `assigned_to_user_id`: ID do usuário responsável pela análise (nullable, foreign key para users)
- `current_snapshot`: Estado atual conhecido (JSON, nullable)
- `requested_changes`: Mudanças solicitadas (JSON, nullable)
- `internal_notes`: Observações internas (text, nullable)
- `decision_notes`: Decisão/observação final (text, nullable)
- `submitted_at`: Data de envio (timestamp, nullable)
- `reviewed_at`: Data de revisão (timestamp, nullable)
- `reviewed_by_user_id`: ID do usuário que revisou (nullable, foreign key para users)
- `approved_at`: Data de aprovação (timestamp, nullable)
- `approved_by_user_id`: ID do usuário que aprovou (nullable, foreign key para users)
- `rejected_at`: Data de rejeição (timestamp, nullable)
- `rejected_by_user_id`: ID do usuário que rejeitou (nullable, foreign key para users)
- `completed_at`: Data de conclusão (timestamp, nullable)
- `completed_by_user_id`: ID do usuário que concluiu (nullable, foreign key para users)
- `due_at`: Prazo (timestamp, nullable)
- `metadata`: Metadados adicionais (JSON, nullable)
- `created_at`: Data de criação
- `updated_at`: Data de atualização

**Índices:**
- `type`
- `status`
- `priority`
- `due_at`
- `requester_person_id`
- `related_alert_id`
- `assigned_to_user_id`

### Tipos de Solicitação

- `registration_review`: Revisão de cadastro
- `personal_data_change`: Alteração de dados pessoais
- `family_link_review`: Revisão de vínculo familiar
- `guardianship_review`: Revisão de responsável
- `child_transition_review`: Revisão de transição dos 11 anos
- `alert_resolution_review`: Revisão de alerta
- `manual_secretary_request`: Solicitação manual da Secretaria

### Status de Solicitação

- `pending`: Pendente
- `in_review`: Em análise
- `approved`: Aprovada
- `rejected`: Rejeitada
- `completed`: Concluída
- `cancelled`: Cancelada

### Prioridade

- `low`: Baixa
- `normal`: Normal
- `high`: Alta
- `urgent`: Urgente

### Model SecretaryRequest

**Relacionamentos:**
- `requesterUser`: BelongsTo User
- `requesterPerson`: BelongsTo Person
- `assignedToUser`: BelongsTo User
- `reviewedByUser`: BelongsTo User
- `approvedByUser`: BelongsTo User
- `rejectedByUser`: BelongsTo User
- `completedByUser`: BelongsTo User
- `relatedAlert`: BelongsTo SystemAlert

**Métodos de estado:**
- `isPending()`: Verifica se está pendente
- `isInReview()`: Verifica se está em análise
- `isApproved()`: Verifica se foi aprovada
- `isRejected()`: Verifica se foi rejeitada
- `isCompleted()`: Verifica se foi concluída
- `isCancelled()`: Verifica se foi cancelada
- `canBeEdited()`: Verifica se pode ser editada
- `isOverdue()`: Verifica se está atrasada

**Métodos de ação:**
- `markInReview(int $userId, ?string $notes = null)`: Marca como em análise
- `approve(int $userId, string $notes)`: Aprova solicitação
- `reject(int $userId, string $notes)`: Rejeita solicitação
- `complete(int $userId, string $notes)`: Conclui solicitação
- `cancel(int $userId, string $notes)`: Cancela solicitação

### Não Implementado Nesta Etapa

- Sistema completo de notificações externas (e-mail, WhatsApp, push)
- Alertas automáticos em tempo real
- Tarefas recorrentes avançadas
- IA para análise de alertas
