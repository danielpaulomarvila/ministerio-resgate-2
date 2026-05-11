# DOCUMENTO MORADAS PORTUGAL

## Visão Geral

Este documento descreve o sistema de moradas do Ministério Resgate / Família Resgate, adaptado especificamente para a realidade administrativa de Portugal. O sistema de moradas foi projetado para suportar a estrutura hierárquica portuguesa de divisões administrativas.

## Estrutura Administrativa de Portugal

Portugal tem uma estrutura administrativa hierárquica:

```
País (Portugal)
  └─ Distrito (ex: Lisboa, Porto, Braga)
      └─ Concelho/Município (ex: Lisboa, Porto, Guimarães)
          └─ Freguesia (ex: São Nicolau, Cedofeita, Azurém)
              └─ Localidade (ex: Lisboa, Porto, Guimarães)
```

**Observações:**
- Portugal Continental tem 18 distritos
- Regiões Autónomas: Açores e Madeira (não têm distritos)
- Cada concelho tem uma ou mais freguesias
- Localidade pode ser uma cidade, vila, aldeia, etc.

## Tabela person_addresses

### Campos da Tabela

| Campo | Tipo | Descrição | Exemplo |
|-------|------|-----------|---------|
| `id` | bigint | Identificador único | 1 |
| `person_id` | bigint (FK) | Foreign key para people | 1 |
| `is_primary` | boolean | Indica se é a morada principal | true |
| `country_name` | string | País | Portugal |
| `district_name` | string | Distrito | Lisboa |
| `municipality_name` | string | Concelho/Município | Lisboa |
| `parish_name` | string | Freguesia | São Nicolau |
| `locality_name` | string | Localidade | Lisboa |
| `locality_manual` | string | Localidade manual (casos especiais) | - |
| `address_line` | string | Rua/Avenida | Rua Augusta |
| `door_number` | string | Número da porta | 123 |
| `floor_or_unit` | string | Andar/Fração | 1º Esq. |
| `address_complement` | string | Complemento | Lote 3 |
| `postal_code` | string | Código Postal (0000-000) | 1100-001 |
| `full_address` | text | Endereço completo concatenado | Rua Augusta, 123, 1º Esq., 1100-001 Lisboa |
| `created_at` | timestamp | Data de criação | 2026-05-09 |
| `updated_at` | timestamp | Data de atualização | 2026-05-09 |

### Índices

- `person_id`: Para buscar moradas de uma pessoa
- `is_primary`: Para buscar morada principal
- `postal_code`: Para busca por código postal
- `municipality_name`: Para busca por concelho

## Código Postal em Portugal

### Formato

O código postal em Portugal segue o formato: **0000-000**

- 4 dígitos (código de distribuição)
- Hífen
- 3 dígitos (código de localização)

### Estrutura

**4 Dígitos (Código de Distribuição):**
- Primeiro dígito: Região (1-9)
  - 1: Grande Lisboa
  - 2: Grande Porto
  - 3: Beira Interior / Beira Litoral
  - 4: Entre Douro e Minho
  - 5: Trás-os-Montes
  - 6: Beira Alta
  - 7: Beira Baixa
  - 8: Alentejo
  - 9: Algarve
- Próximos 3 dígitos: Área de distribuição postal

**3 Dígitos (Código de Localização):**
- Especifica a localidade ou rua dentro da área de distribuição

### Exemplos

| Código Postal | Localidade | Distrito |
|---------------|------------|---------|
| 1100-001 | Lisboa | Lisboa |
| 4000-001 | Porto | Porto |
| 4700-001 | Braga | Braga |
| 2000-001 | Santarém | Santarém |
| 8000-001 | Faro | Faro |

### Validação

O sistema valida o código postal com regex: `/^\d{4}-\d{3}$/`

## Andar e Fração

Em Portugal, é muito comum indicar o andar e a fração da habitação:

### Abreviações Comuns

| Abreviação | Significado |
|------------|-------------|
| 1º Esq. | 1º Andar Esquerdo |
| 1º Dto. | 1º Andar Direito |
| 2º Esq. | 2º Andar Esquerdo |
| 2º Dto. | 2º Andar Direito |
| R/C | Rés do Chão |
| R/C Esq. | Rés do Chão Esquerdo |
| R/C Dto. | Rés do Chão Direito |
| Cave | Cave |
| 1º Fr. | 1º Fração |
| A, B, C | Frações (A, B, C) |

### Exemplos

- "1º Esq." - Primeiro andar, porta esquerda
- "2º Dto." - Segundo andar, porta direita
- "R/C Esq." - Rés do chão, porta esquerda
- "3º Fr. D" - Terceiro andar, fração D
- "Cave" - Cave

## Morada Principal

### Regras

1. Cada pessoa deve ter uma morada principal (`is_primary = true`)
2. Uma pessoa pode ter múltiplas moradas secundárias
3. A morada principal é usada para:
   - Correspondência
   - Localização em mapas
   - Cálculo de distância
   - Relatórios

### Comportamento no Sistema

- Ao criar uma pessoa, a primeira morada é automaticamente marcada como principal
- Ao editar, o usuário pode alterar qual morada é principal
- O sistema garante que exista sempre uma morada principal por pessoa

## Exemplos de Endereços Completos

### Exemplo 1: Lisboa (Centro)

```
Rua Augusta, 123, 1º Esq.
1100-001 Lisboa
```

**No sistema:**
- `address_line`: Rua Augusta
- `door_number`: 123
- `floor_or_unit`: 1º Esq.
- `postal_code`: 1100-001
- `locality_name`: Lisboa
- `parish_name`: São Nicolau
- `municipality_name`: Lisboa
- `district_name`: Lisboa
- `country_name`: Portugal

### Exemplo 2: Porto (Centro)

```
Rua das Flores, 45, 2º Dto.
4000-001 Porto
```

**No sistema:**
- `address_line`: Rua das Flores
- `door_number`: 45
- `floor_or_unit`: 2º Dto.
- `postal_code`: 4000-001
- `locality_name`: Porto
- `parish_name`: Cedofeita
- `municipality_name`: Porto
- `district_name`: Porto
- `country_name`: Portugal

### Exemplo 3: Guimarães (Cidade Média)

```
Avenida da Liberdade, 78
4800-000 Guimarães
```

**No sistema:**
- `address_line`: Avenida da Liberdade
- `door_number`: 78
- `floor_or_unit`: (vazio)
- `postal_code`: 4800-000
- `locality_name`: Guimarães
- `parish_name`: Azurém
- `municipality_name`: Guimarães
- `district_name`: Braga
- `country_name`: Portugal

### Exemplo 4: Aldeia (Interior)

```
Rua Principal, 12
6300-000 Guarda
```

**No sistema:**
- `address_line`: Rua Principal
- `door_number`: 12
- `floor_or_unit`: (vazio)
- `postal_code`: 6300-000
- `locality_name`: Guarda
- `parish_name`: Sé
- `municipality_name`: Guarda
- `district_name`: Guarda
- `country_name`: Portugal

## Distritos de Portugal

### Portugal Continental (18 Distritos)

1. Aveiro
2. Beja
3. Braga
4. Bragança
5. Castelo Branco
6. Coimbra
7. Évora
8. Faro
9. Guarda
10. Leiria
11. Lisboa
12. Portalegre
13. Porto
14. Santarém
15. Setúbal
16. Viana do Castelo
17. Vila Real
18. Viseu

### Regiões Autónomas

- Açores (não tem distritos)
- Madeira (não tem distritos)

## Terminologia

| Termo | Tradução | Uso no Sistema |
|-------|----------|----------------|
| Address | Morada | `person_addresses` |
| Street | Rua | `address_line` |
| Avenue | Avenida | `address_line` |
| Door Number | Número da porta | `door_number` |
| Floor/Unit | Andar/Fração | `floor_or_unit` |
| Neighborhood | Freguesia | `parish_name` |
| City | Concelho/Município | `municipality_name` |
| State | Distrito | `district_name` |
| Zip Code | Código Postal | `postal_code` |
| Locality | Localidade | `locality_name` |

## Validações no Sistema

### Validações de Formato

- `postal_code`: Regex `/^\d{4}-\d{3}$/`
- `country_name`: String, max 100 caracteres
- `district_name`: String, max 100 caracteres
- `municipality_name`: String, max 100 caracteres
- `parish_name`: String, max 100 caracteres
- `locality_name`: String, max 100 caracteres
- `address_line`: String, max 255 caracteres
- `door_number`: String, max 50 caracteres
- `floor_or_unit`: String, max 50 caracteres
- `address_complement`: String, max 255 caracteres

### Validações de Negócio

- Uma pessoa deve ter pelo menos uma morada
- Apenas uma morada pode ser marcada como principal por pessoa
- Código postal deve seguir formato português

## Busca e Filtragem

### Por Código Postal

Buscar pessoas por código postal:
- Busca exata: "1100-001"
- Busca parcial: "1100" (todos os códigos que começam com 1100)

### Por Concelho

Buscar pessoas por concelho:
- Exemplo: "Lisboa", "Porto", "Guimarães"

### Por Distrito

Buscar pessoas por distrito:
- Exemplo: "Lisboa", "Porto", "Braga"

### Por Freguesia

Buscar pessoas por freguesia:
- Exemplo: "São Nicolau", "Cedofeita", "Azurém"

## Integração com API de Códigos Postais (Futuro)

### Possibilidade de Integração

O sistema pode ser integrado com APIs de códigos postais de Portugal para:
- Autocompletar código postal
- Validar código postal
- Preencher automaticamente concelho, freguesia e localidade
- Obter coordenadas GPS

### APIs Disponíveis

- CTT (Correios de Portugal)
- API de Códigos Postais de Portugal (open source)
- Google Maps Geocoding API

## Considerações Especiais

### Localidade Manual

O campo `locality_manual` permite inserir localidades que não estão em listas oficiais, como:
- Lugarejos muito pequenos
- Quintas
- Herdades
- Casais

### Casos Especiais

- **Moradas sem número**: Alguns lugares não têm numeração (ex: "Quinta da Fonte")
- **Moradas rurais**: Podem ter descrições especiais (ex: "Herdade do Vale")
- **Moradas de férias**: Segunda morada para férias/feriados
- **Moradas de trabalho**: Morada profissional separada da residencial

## Próximos Passos

1. Implementar integração com API de códigos postais
2. Implementar autocompletar de concelhos e freguesias
3. Implementar validação de código postal via API
4. Implementar geocodificação (coordenadas GPS)
5. Implementar mapa visual de moradas
6. Implementar cálculo de distância entre moradas
7. Implementar relatório de distribuição geográfica de membros

## Referências

- CTT - Correios de Portugal: https://www.ctt.pt
- Estrutura Administrativa de Portugal: https://www.portugal.gov.pt
- Código Postal: https://www.codigopostal.pt
