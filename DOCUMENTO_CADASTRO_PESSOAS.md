# DOCUMENTO CADASTRO PESSOAS

## Visão Geral

Este documento descreve o sistema de cadastro de pessoas do Ministério Resgate / Família Resgate, adaptado para a realidade de Portugal. O módulo de cadastro de pessoas foi refatorado para separar documentos e moradas em tabelas próprias, deixando a tabela principal mais limpa e organizada.

## Estrutura do Banco de Dados

### Tabela people (Tabela Principal)

Contém os dados pessoais essenciais de cada pessoa:

**Campos Pessoais:**
- `full_name`: Nome completo (obrigatório)
- `preferred_name`: Nome como prefere ser chamado
- `last_name`: Apelido/Sobrenome
- `birth_date`: Data de nascimento (usada para cálculo de idade)
- `gender`: Gênero (male, female, other)
- `marital_status`: Estado civil (single, married, divorced, widowed, separated)
- `education_level`: Nível de escolaridade (elementary, high_school, college, postgraduate, other)
- `nationality`: Nacionalidade (ex: Portuguesa)
- `birthplace`: Naturalidade (ex: Lisboa)
- `profession`: Profissão
- `occupation`: Ocupação

**Campos de Contacto:**
- `email`: E-mail único
- `primary_phone`: Telemóvel principal
- `secondary_phone`: Telemóvel secundário
- `whatsapp`: WhatsApp
- `contact_notes`: Notas de contacto

**Campos Adicionais:**
- `photo_path`: Caminho da foto no storage
- `is_baptized`: Indica se foi batizado
- `baptism_date`: Data do batismo
- `conversion_date`: Data de conversão
- `invited_by_person_id`: Quem convidou/influenciou/indicou (foreign key para people)
- `person_status`: Status da pessoa (active, inactive, visitor, congregant, discipling, new_convert, regularization)
- `general_notes`: Observações gerais

### Tabela person_documents (Documentos)

Contém os documentos de identificação adaptados para Portugal:

**Campos:**
- `person_id`: Foreign key para people (unique)
- `nif`: NIF (Número de Identificação Fiscal) - documento fiscal principal em Portugal
- `citizen_card_number`: Cartão de Cidadão
- `passport_number`: Passaporte
- `residence_permit_number`: Título de Residência
- `other_document`: Outro documento
- `document_notes`: Notas sobre documentos

**Observações:**
- NIF é o documento principal em Portugal para fins fiscais
- Cartão de Cidadão substituiu o antigo Bilhete de Identidade
- Título de Residência é necessário para cidadãos não-UE
- NIF deve ser único no sistema

### Tabela person_addresses (Moradas)

Contém os dados de morada adaptados para Portugal:

**Campos:**
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

**Observações:**
- Uma pessoa pode ter múltiplas moradas
- Código postal em Portugal segue formato 0000-000 (4 dígitos + hífen + 3 dígitos)
- Estrutura administrativa: País > Distrito > Concelho > Freguesia > Localidade
- Andar/Fração é comum em Portugal (ex: 1º Esq., 3º Dto.)

## Validações

### StorePersonRequest (Criação)

**Validações Person:**
- `person.full_name`: obrigatório, string, max 255
- `person.email`: nullable, email, único na tabela people
- `person.primary_phone`: nullable, string, max 50
- `person.birth_date`: nullable, date
- `person.person_status`: obrigatório, in: [active, inactive, visitor, congregant, discipling, new_convert, regularization]
- `person.is_baptized`: obrigatório, boolean

**Validações Document:**
- `document.nif`: nullable, string, max 50, único na tabela person_documents
- `document.citizen_card_number`: nullable, string, max 100
- `document.passport_number`: nullable, string, max 100
- `document.residence_permit_number`: nullable, string, max 100

**Validações Address:**
- `address.postal_code`: nullable, regex: `/^\d{4}-\d{3}$/` (formato 0000-000)
- `address.country_name`: nullable, string, max 100
- `address.municipality_name`: nullable, string, max 100

### UpdatePersonRequest (Atualização)

Mesmas validações do StorePersonRequest, com uma diferença importante:
- `document.nif`: único na tabela person_documents, ignorando o próprio registro da pessoa
- `person.email`: único na tabela people, ignorando o próprio registro da pessoa

## Relacionamentos Eloquent

### Person
- `hasOne(PersonDocument)`: Uma pessoa tem um registro de documentos
- `hasMany(PersonAddress)`: Uma pessoa pode ter múltiplas moradas
- `hasOne(PersonAddress as primaryAddress)`: Morada principal da pessoa

### PersonDocument
- `belongsTo(Person)`: Um documento pertence a uma pessoa

### PersonAddress
- `belongsTo(Person)`: Uma morada pertence a uma pessoa

## Controller (PersonController)

O PersonController foi atualizado para usar transações de banco e garantir integridade dos dados:

### store()
- Inicia transação de banco
- Cria Person
- Cria PersonDocument vinculado à Person
- Cria PersonAddress vinculado à Person (com is_primary = true)
- Commit da transação em caso de sucesso
- Rollback em caso de erro

### update()
- Inicia transação de banco
- Atualiza Person
- Atualiza PersonDocument (se existir) ou cria novo
- Atualiza PersonAddress principal (se existir) ou cria nova
- Commit da transação em caso de sucesso
- Rollback em caso de erro

### show()
- Carrega Person com:
  - document (PersonDocument)
  - primaryAddress (PersonAddress com is_primary = true)
- Calcula idade
- Passa dados para a página Vue

## Páginas Vue

### Index.vue
Lista todas as pessoas com:
- Nome completo
- Idade
- Telemóvel principal
- Email
- NIF (de person.document)
- Concelho (de person.primaryAddress.municipality_name)
- Status
- Batizado

### Create.vue
Formulário de criação organizado em seções:
- A) Dados Pessoais
- B) Contactos
- C) Documentos
- D) Morada
- E) Vida Cristã/Igreja
- F) Observações

Estrutura do form usa objetos separados:
```javascript
form.person = { ... }
form.document = { ... }
form.address = { ... }
```

### Edit.vue
Similar ao Create.vue, mas:
- Carrega dados existentes da pessoa
- Preenche form.person, form.document e form.address
- Usa método PUT para atualizar

### Show.vue
Exibe dados organizados em seções:
- A) Dados Pessoais
- B) Contactos
- C) Documentos
- D) Morada
- E) Vida Cristã/Igreja
- F) Observações

Mostra dados de:
- person (tabela people)
- person.document (tabela person_documents)
- person.primaryAddress (tabela person_addresses)

## Terminologia Portuguesa

O sistema usa terminologia portuguesa de Portugal:

| Termo | Descrição |
|-------|-----------|
| Telemóvel | Telefone móvel |
| Contactos | Meios de comunicação |
| Morada | Endereço residencial |
| Freguesia | Divisão administrativa local |
| Concelho/Município | Divisão administrativa regional |
| Distrito | Divisão administrativa superior |
| Código Postal | Código de endereçamento postal |
| NIF | Número de Identificação Fiscal |

## Regras de Negócio

### NIF
- NIF é o documento fiscal principal em Portugal
- Deve ser único no sistema
- Formato: 9 dígitos numéricos
- Opcional para visitantes, mas obrigatório para membros

### Código Postal
- Formato: 0000-000 (4 dígitos + hífen + 3 dígitos)
- Exemplo: 1000-001 (Lisboa)
- Exemplo: 4000-001 (Porto)

### Morada Principal
- Cada pessoa deve ter uma morada principal (is_primary = true)
- Uma pessoa pode ter múltiplas moradas secundárias
- Morada principal é usada para correspondência e localização

### Idade e Membresia
- Menores de 11 anos: não podem ter usuário
- 11-13 anos (Júnior): podem ter usuário com supervisão
- 14-17 anos (Jovem): podem ter usuário independente
- 18+ anos (Adulto): podem ter usuário independente
- Membro: somente quem é batizado

## Avisos de Elegibilidade

O sistema mostra avisos automáticos nas páginas de cadastro:

### Usuário
- Menores de 11 anos: "Menores de 11 anos não podem ter usuário."
- Júnior/Jovem: "Pode ter usuário, mas requer supervisão dos pais/responsáveis."
- Adulto: "Pode ter usuário independente de membresia."

### Membro
- Não batizado: "Não pode ser membro pois não é batizado."
- Batizado: "Pode ser membro se for batizado."

### Departamento Resgatados
- Júnior (11-13 anos): "Júnior (11-13 anos): Pode participar do departamento Resgatados."
- Jovem (14-17 anos): "Jovem (14-17 anos): Pode participar do departamento Resgatados."

### Alerta de 11 Anos
- Menor de 11 anos: "Menor de 11 anos: Quando completar 11 anos, poderá ter usuário e participar do departamento Resgatados."

## Próximos Passos

1. Implementar busca avançada por NIF
2. Implementar busca por código postal
3. Implementar busca por concelho
4. Implementar gestão de múltiplas moradas
5. Implementar histórico de alterações de documentos
6. Implementar upload de foto
7. Implementar autocomplete para "quem convidou"

## Considerações de Segurança

- NIF é dado sensível e deve ser tratado como tal
- Documentos não devem ser expostos em logs
- Transações de banco garantem integridade
- Validações no backend e no frontend
