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
Tabela para responsáveis por menores de idade.

**Campos principais:**
- `id`: Identificador único
- `minor_person_id`: Pessoa menor de idade (foreign key para people)
- `guardian_person_id`: Responsável (foreign key para people)
- `relationship_type`: Tipo de relacionamento (father, mother, legal_guardian, other)
- `is_legal_guardian`: Responsável legal
- `is_financial_responsible`: Responsável financeiro (para cantina, etc.)
- `can_approve_changes`: Pode aprovar alterações no cadastro
- `can_view_financial`: Pode ver dados financeiros
- `starts_at`: Início da responsabilidade
- `ends_at`: Fim da responsabilidade (nullable, para histórico)
- `status`: Status da responsabilidade (active, inactive)
- `created_at`, `updated_at`: Timestamps

**Relacionamentos:**
- `minor_person_id` → people (foreign key)
- `guardian_person_id` → people (foreign key)

**Índices:**
- `minor_person_id`: Para buscar responsáveis de um menor
- `guardian_person_id`: Para buscar menores sob responsabilidade
- `status`: Para filtrar responsabilidades ativas
- Unique: `[minor_person_id, guardian_person_id, ends_at]` para evitar duplicidade

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

1. Implementar módulo de Secretaria para gerenciar cadastros e aprovações
2. Implementar módulo Financeiro com suporte a responsáveis financeiros
3. Implementar módulo Cantina com cobrança em responsáveis de menores
4. Implementar sistema de alertas automáticos para crianças completando 11 anos
5. Implementar sistema de permissões por papel/função (Spatie Laravel Permission)
6. Implementar área do membro para acesso via usuário
7. Preparar arquitetura para futuro app iOS/Android (API Sanctum, PWA, Capacitor)
