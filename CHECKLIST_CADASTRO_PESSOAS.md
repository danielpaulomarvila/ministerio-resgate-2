# CHECKLIST CADASTRO PESSOAS

## Visão Geral

Este documento serve como checklist para validar que o módulo de cadastro de pessoas foi implementado corretamente, adaptado para a realidade de Portugal.

## Checklist de Implementação

### ✅ Migrations

- [x] Migration `create_person_documents_table`
  - [x] Campo person_id (foreign key para people)
  - [x] Campo nif (string, max 50, unique)
  - [x] Campo citizen_card_number (string, max 100)
  - [x] Campo passport_number (string, max 100)
  - [x] Campo residence_permit_number (string, max 100)
  - [x] Campo other_document (string, max 100)
  - [x] Campo document_notes (text)
  - [x] Índice person_id
  - [x] Índice unique nif
  - [x] Timestamps (created_at, updated_at)

- [x] Migration `create_person_addresses_table`
  - [x] Campo person_id (foreign key para people)
  - [x] Campo is_primary (boolean, default false)
  - [x] Campo country_name (string, max 100)
  - [x] Campo district_name (string, max 100)
  - [x] Campo municipality_name (string, max 100)
  - [x] Campo parish_name (string, max 100)
  - [x] Campo locality_name (string, max 100)
  - [x] Campo locality_manual (string, max 100)
  - [x] Campo address_line (string, max 255)
  - [x] Campo door_number (string, max 50)
  - [x] Campo floor_or_unit (string, max 50)
  - [x] Campo address_complement (string, max 255)
  - [x] Campo postal_code (string, max 10)
  - [x] Campo full_address (text)
  - [x] Índice person_id
  - [x] Índice is_primary
  - [x] Índice postal_code
  - [x] Índice municipality_name
  - [x] Timestamps (created_at, updated_at)

- [x] Migration `migrate_people_data_to_person_documents_and_addresses`
  - [x] Migração de nif/document_number para person_documents.nif
  - [x] Migração de secondary_document para person_documents.other_document
  - [x] Migração de address para person_addresses.address_line
  - [x] Migração de address_number para person_addresses.door_number
  - [x] Migração de address_complement para person_addresses.address_complement
  - [x] Migração de neighborhood para person_addresses.parish_name
  - [x] Migração de postal_code para person_addresses.postal_code
  - [x] Migração de city para person_addresses.municipality_name
  - [x] Migração de state para person_addresses.district_name
  - [x] Migração de country para person_addresses.country_name
  - [x] Criação de full_address concatenado
  - [x] Marcação de is_primary = true para primeira morada

- [x] Migration `restructure_people_table_for_portugal`
  - [x] Adição de last_name (string, max 255)
  - [x] Adição de nationality (string, max 100)
  - [x] Adição de birthplace (string, max 100)
  - [x] Adição de profession (string, max 100)
  - [x] Adição de occupation (string, max 100)
  - [x] Renomeação de phone para primary_phone
  - [x] Adição de whatsapp (string, max 50)
  - [x] Adição de contact_notes (text)
  - [x] Renomeação de notes para general_notes
  - [x] Remoção de nif
  - [x] Remoção de secondary_document
  - [x] Remoção de address
  - [x] Remoção de address_number
  - [x] Remoção de address_complement
  - [x] Remoção de neighborhood
  - [x] Remoção de postal_code
  - [x] Remoção de city
  - [x] Remoção de state
  - [x] Remoção de country

### ✅ Models

- [x] Model `PersonDocument`
  - [x] Relacionamento belongsTo Person
  - [x] Fillable configurado com todos os campos
  - [x] Timestamps habilitados

- [x] Model `PersonAddress`
  - [x] Relacionamento belongsTo Person
  - [x] Fillable configurado com todos os campos
  - [x] Timestamps habilitados
  - [x] Scope primary() para morada principal

- [x] Model `Person` atualizado
  - [x] Relacionamento hasOne PersonDocument (document)
  - [x] Relacionamento hasMany PersonAddress (addresses)
  - [x] Relacionamento hasOne PersonAddress (primaryAddress)
  - [x] Fillable atualizado com novos campos
  - [x] Casts atualizado com novos campos

### ✅ Controller

- [x] `PersonController` atualizado
  - [x] Método index(): Carrega com document e primaryAddress
  - [x] Método create(): Retorna view vazia
  - [x] Método store(): Usa transação, cria person, document e address
  - [x] Método show(): Carrega com document e primaryAddress
  - [x] Método edit(): Carrega com document e primaryAddress
  - [x] Método update(): Usa transação, atualiza person, document e address
  - [x] Método destroy(): Deleta person (cascade com document e address)

### ✅ Requests (Validação)

- [x] `StorePersonRequest` atualizado
  - [x] Validação person.full_name (required, string, max 255)
  - [x] Validação person.email (nullable, email, unique)
  - [x] Validação person.primary_phone (nullable, string, max 50)
  - [x] Validação person.birth_date (nullable, date)
  - [x] Validação person.person_status (required, in: [...])
  - [x] Validação person.is_baptized (required, boolean)
  - [x] Validação document.nif (nullable, string, max 50, unique)
  - [x] Validação document.citizen_card_number (nullable, string, max 100)
  - [x] Validação document.passport_number (nullable, string, max 100)
  - [x] Validação document.residence_permit_number (nullable, string, max 100)
  - [x] Validação address.postal_code (nullable, regex: /^\d{4}-\d{3}$/)
  - [x] Validação address.country_name (nullable, string, max 100)
  - [x] Validação address.municipality_name (nullable, string, max 100)
  - [x] Mensagens de erro em português

- [x] `UpdatePersonRequest` atualizado
  - [x] Mesmas validações do StorePersonRequest
  - [x] Validação document.nif unique ignorando o próprio registro
  - [x] Validação person.email unique ignorando o próprio registro
  - [x] Mensagens de erro em português

### ✅ Páginas Vue

- [x] `People/Index.vue`
  - [x] Coluna Nome Completo
  - [x] Coluna Idade
  - [x] Coluna Telemóvel Principal
  - [x] Coluna Email
  - [x] Coluna NIF (de person.document)
  - [x] Coluna Concelho (de person.primaryAddress.municipality_name)
  - [x] Coluna Status
  - [x] Coluna Batizado
  - [x] Terminologia portuguesa

- [x] `People/Create.vue`
  - [x] Seção A) Dados Pessoais
    - [x] full_name, preferred_name, last_name
    - [x] birth_date, gender, marital_status, education_level
    - [x] nationality, birthplace, profession, occupation
  - [x] Seção B) Contactos
    - [x] email, primary_phone, secondary_phone, whatsapp, contact_notes
  - [x] Seção C) Documentos
    - [x] nif, citizen_card_number, passport_number, residence_permit_number
    - [x] other_document, document_notes
  - [x] Seção D) Morada
    - [x] country_name (default: Portugal)
    - [x] district_name, municipality_name, parish_name
    - [x] locality_name, locality_manual
    - [x] address_line, door_number, floor_or_unit, address_complement
    - [x] postal_code (validação regex)
  - [x] Seção E) Vida Cristã/Igreja
    - [x] is_baptized, baptism_date, conversion_date
    - [x] invited_by_person_id, person_status
  - [x] Seção F) Observações
    - [x] general_notes
  - [x] Estrutura do form: person, document, address
  - [x] v-model correto para estrutura aninhada
  - [x] Mensagens de erro para campos aninhados
  - [x] Terminologia portuguesa

- [x] `People/Edit.vue`
  - [x] Mesmas seções do Create.vue
  - [x] Carregamento de dados existentes (person.document, person.primaryAddress)
  - [x] Estrutura do form: person, document, address
  - [x] v-model correto para estrutura aninhada
  - [x] Mensagens de erro para campos aninhados
  - [x] Terminologia portuguesa

- [x] `People/Show.vue`
  - [x] Seção A) Dados Pessoais
    - [x] full_name, preferred_name, last_name
    - [x] age, birth_date, gender, marital_status, education_level
    - [x] nationality, birthplace, profession, occupation
  - [x] Seção B) Contactos
    - [x] email, primary_phone, secondary_phone, whatsapp, contact_notes
  - [x] Seção C) Documentos
    - [x] nif, citizen_card_number, passport_number, residence_permit_number
    - [x] other_document, document_notes
  - [x] Seção D) Morada
    - [x] country_name, district_name, municipality_name, parish_name
    - [x] locality_name, locality_manual
    - [x] address_line, door_number, floor_or_unit, address_complement
    - [x] postal_code
  - [x] Seção E) Vida Cristã/Igreja
    - [x] is_baptized, baptism_date, conversion_date
    - [x] invited_by_person_id, person_status
  - [x] Seção F) Observações
    - [x] general_notes
  - [x] Exibição de person.document
  - [x] Exibição de person.primaryAddress
  - [x] Terminologia portuguesa

### ✅ Terminologia Portuguesa

- [x] "Telefone" → "Telemóvel"
- [x] "Contatos" → "Contactos"
- [x] "Endereço" → "Morada"
- [x] "Bairro" → "Freguesia"
- [x] "Cidade" → "Concelho/Município"
- [x] "Estado" → "Distrito"
- [x] "CEP" → "Código Postal"
- [x] "CPF" → "NIF"

### ✅ Validações

- [x] NIF único no sistema
- [x] Email único no sistema
- [x] Código postal formato 0000-000 (regex)
- [x] Campos obrigatórios marcados corretamente
- [x] Mensagens de erro em português

### ✅ Documentação

- [x] `DOCUMENTO_CADASTRO_PESSOAS.md` criado
  - [x] Estrutura do banco de dados
  - [x] Validações
  - [x] Relacionamentos
  - [x] Controller
  - [x] Páginas Vue
  - [x] Terminologia portuguesa
  - [x] Regras de negócio

- [x] `DOCUMENTO_MORADAS_PORTUGAL.md` criado
  - [x] Estrutura administrativa de Portugal
  - [x] Campos da tabela person_addresses
  - [x] Código postal em Portugal
  - [x] Andar e fração
  - [x] Exemplos de endereços
  - [x] Distritos de Portugal
  - [x] Terminologia

### ✅ Documentação Atualizada

- [x] `DOCUMENTO_BANCO_DADOS_INICIAL.md` atualizado
  - [x] Tabela people atualizada (sem documentos e moradas)
  - [x] Tabela person_documents adicionada
  - [x] Tabela person_addresses adicionada
  - [x] Relacionamentos Eloquent atualizados
  - [x] Números de tabelas atualizados

- [x] `DOCUMENTO_ARQUITETURA_INICIAL.md` atualizado
  - [x] Fase 2.1 atualizada com nova estrutura
  - [x] Detalhes das tabelas person_documents e person_addresses
  - [x] Terminologia portuguesa documentada

- [x] `CHECKLIST_INICIAL.md` atualizado
  - [x] Seção People CRUD atualizada com nova estrutura
  - [x] Detalhes das migrations criadas
  - [x] Detalhes dos models criados
  - [x] Detalhes das páginas Vue atualizadas

## Validação Funcional

### ✅ Criação de Pessoa

- [x] Formulário de criação carrega corretamente
- [x] Validações funcionam corretamente
- [x] Pessoa é criada na tabela people
- [x] Documento é criado na tabela person_documents
- [x] Morada é criada na tabela person_addresses
- [x] Morada é marcada como principal (is_primary = true)
- [x] Transação de banco funciona (rollback em caso de erro)

### ✅ Edição de Pessoa

- [x] Formulário de edição carrega dados existentes
- [x] Dados de person são carregados
- [x] Dados de person.document são carregados
- [x] Dados de person.primaryAddress são carregados
- [x] Validações funcionam corretamente
- [x] Pessoa é atualizada na tabela people
- [x] Documento é atualizado na tabela person_documents
- [x] Morada é atualizada na tabela person_addresses
- [x] Transação de banco funciona (rollback em caso de erro)

### ✅ Visualização de Pessoa

- [x] Página de visualização carrega dados
- [x] Dados de person são exibidos
- [x] Dados de person.document são exibidos
- [x] Dados de person.primaryAddress são exibidos
- [x] Terminologia portuguesa está correta
- [x] Avisos de elegibilidade são exibidos

### ✅ Listagem de Pessoas

- [x] Lista de pessoas carrega corretamente
- [x] Colunas estão corretas
- [x] NIF é exibido (de person.document)
- [x] Concelho é exibido (de person.primaryAddress.municipality_name)
- [x] Terminologia portuguesa está correta

## Validação de Banco de Dados

### ✅ Tabela person_documents

- [x] Tabela foi criada
- [x] Foreign key person_id está configurada
- [x] Índice person_id está configurado
- [x] Índice unique nif está configurado
- [x] Dados são migrados corretamente

### ✅ Tabela person_addresses

- [x] Tabela foi criada
- [x] Foreign key person_id está configurada
- [x] Índice person_id está configurado
- [x] Índice is_primary está configurado
- [x] Índice postal_code está configurado
- [x] Índice municipality_name está configurado
- [x] Dados são migrados corretamente

### ✅ Tabela people

- [x] Campos novos foram adicionados
- [x] Campos antigos foram removidos
- [x] Campos foram renomeados corretamente
- [x] Dados permanecem consistentes

## Validação de Regras de Negócio

### ✅ NIF

- [x] NIF é validado como único
- [x] NIF pode ser opcional (nullable)
- [x] NIF é exibido na listagem
- [x] NIF é exibido na visualização

### ✅ Código Postal

- [x] Código postal é validado com regex
- [x] Formato 0000-000 é exigido
- [x] Mensagem de erro é clara

### ✅ Morada Principal

- [x] Primeira morada é marcada como principal
- [x] Morada principal é carregada no show/edit
- [x] Apenas uma morada pode ser principal por pessoa

### ✅ Idade e Membresia

- [x] Idade é calculada corretamente
- [x] Avisos de elegibilidade funcionam
- [x] Regras de usuário por idade funcionam
- [x] Regras de membro (batizado) funcionam

## Validação de Segurança

### ✅ Transações de Banco

- [x] store() usa transação
- [x] update() usa transação
- [x] Rollback funciona em caso de erro
- [x] Integridade dos dados é garantida

### ✅ Validações

- [x] Validações no backend (Requests)
- [x] Validações no frontend (Vue)
- [x] Mensagens de erro são claras
- [x] Dados sensíveis não são expostos

## Status Final

### ✅ Concluído

- Migrations criadas e executadas
- Models criados e configurados
- Controller atualizado com transações
- Requests atualizados com validações
- Páginas Vue atualizadas com nova estrutura
- Terminologia portuguesa implementada
- Documentação criada e atualizada
- Validações funcionando corretamente
- Regras de negócio implementadas

### 📋 Próximos Passos

1. Implementar busca avançada por NIF
2. Implementar busca por código postal
3. Implementar busca por concelho
4. Implementar gestão de múltiplas moradas
5. Implementar integração com API de códigos postais
6. Implementar upload de foto
7. Implementar autocomplete para "quem convidou"
8. Implementar relatório de distribuição geográfica

## Observações

- **Estrutura separada**: Documentos e moradas em tabelas próprias
- **Terminologia portuguesa**: Adotada em todo o sistema
- **Validações robustas**: Backend e frontend
- **Transações de banco**: Garantem integridade
- **Documentação completa**: Inclui DOCUMENTO_CADASTRO_PESSOAS.md e DOCUMENTO_MORADAS_PORTUGAL.md
