# Documento: Centro Família Resgate

## Visão Geral

Este documento registra a implementação da Etapa 11.1 — Base Visual do Centro Família Resgate.

O Centro Família Resgate é a entrada principal da pessoa logada no ecossistema.

## Objetivo da Etapa 11.1

Criar a base visual e funcional inicial do Centro Família Resgate.

## O Que Foi Implementado

### 1. Rota

**Rota:** `/familia`

**Nome da rota:** `familia.index`

**Middleware:** `auth`

**Arquivo:** `routes/web.php`

### 2. Controller

**Arquivo:** `app/Http/Controllers/Familia/FamilyHubController.php`

**Responsabilidades:**
- Carregar usuário autenticado
- Carregar pessoa vinculada ao usuário
- Carregar aniversariantes do dia
- Montar atalhos reais de sistemas existentes conforme permissões
- Retornar página Inertia

**Métodos:**
- `index(Request $request): Response` - Carrega dados e renderiza página
- `getAvailableShortcuts($user): array` - Monta lista de atalhos permitidos

### 3. Layout

**Arquivo:** `resources/js/Layouts/FamilyHubLayout.vue`

**Características:**
- Tema escuro (bg-gray-900)
- Header com logo e menu do usuário
- Dropdown com "Meu perfil" e "Sair"
- Layout simples e limpo

### 4. Página Vue Principal

**Arquivo:** `resources/js/Pages/Familia/Index.vue`

**Seções:**
1. Saudação principal: "Bem-vindo ao centro da Família Resgate"
2. Saudação personalizada: "Olá, {nome}. Que bom ter você aqui."
3. Card de aniversariantes do dia
4. Card de sistemas disponíveis
5. Card de avisos e pendências
6. Card futuro de oração (visão futura)
7. Card futuro de palavra do dia (visão futura)

## Dados Reais Usados

### 1. Usuário Logado

- `auth()->user()`

### 2. Pessoa Vinculada ao Usuário

- `$user->person`

### 3. Saudação Personalizada

- `preferred_name` se existir
- Senão `full_name`
- Senão `name` do usuário
- Senão "Família Resgate"

### 4. Aniversariantes do Dia

- Consulta em `Person` com `birth_date`
- Filtra por dia e mês atual
- Limita a 5 registros
- Mostra contador se houver mais

### 5. Atalhos de Sistemas Existentes

Apenas atalhos para módulos já existentes e funcionais:

- Secretaria (se usuário tiver `secretaria.view` ou `people.view`)
- Departamentos (se usuário tiver `departments.view`)
- Acessos (se usuário tiver `accesses.view` ou `access_profiles.view`)

## Permissões Usadas

**Nenhuma permissão nova foi criada nesta etapa.**

Usamos permissões existentes do sistema:
- `secretaria.view`
- `people.view`
- `departments.view`
- `accesses.view`
- `access_profiles.view`

## Atalhos Implementados

### Secretaria

- **Título:** Secretaria
- **Descrição:** Cadastros, famílias, solicitações e organização administrativa.
- **Rota:** `secretaria.dashboard`
- **Condição:** Usuário tem `secretaria.view` ou `people.view`

### Departamentos

- **Título:** Departamentos
- **Descrição:** Ministérios, equipes e vínculos.
- **Rota:** `secretaria.departments.index`
- **Condição:** Usuário tem `departments.view`

### Acessos

- **Título:** Acessos
- **Descrição:** Gestão de usuários e permissões.
- **Rota:** `secretaria.access.index`
- **Condição:** Usuário tem `accesses.view` ou `access_profiles.view`

## Limites da Etapa

**Não foi implementado nesta etapa:**
- ✅ Não criou migration
- ✅ Não alterou banco
- ✅ Não criou permissões novas
- ✅ Não criou PIN
- ✅ Não criou Central de Oração
- ✅ Não criou Centro Pastoral
- ✅ Não criou Financeiro
- ✅ Não criou Cantina
- ✅ Não criou Site Público
- ✅ Não criou Mural de Fotos
- ✅ Não criou Mural de Artes
- ✅ Não criou Caixinha de Palavras
- ✅ Não criou Membro Destaque
- ✅ Não alterou redirecionamento do login
- ✅ Não fez push

## Riscos

**Riscos mitigados:**
- Backend protege atalhos com verificação de permissões
- Página funciona mesmo se usuário não tiver `person_id`
- Layout simples evita scroll longo
- Componentes mantidos simples para evitar complexidade

## Visual

**Identidade visual seguida:**
- Tema escuro (bg-gray-900)
- Preto/cinza como base
- Laranja-dourado (amber-500) como destaque
- Visual premium, espiritual, moderno, elegante, limpo

**Evitado:**
- Scroll longo
- Dashboard gigante
- Cards demais
- Caixas em cima de caixas
- Textos cortados
- Cards cortados
- Botões desalinhados
- Tabelas desnecessárias
- Informação demais na mesma tela

## Testes Manuais Sugeridos

1. Usuário autenticado acessa `/familia` com sucesso
2. Usuário não autenticado é redirecionado para login
3. Página carrega mesmo se usuário não tiver `person_id`
4. Atalhos não aparecem sem permissão
5. Atalhos aparecem com permissão
6. Botão Sair funciona
7. Não há scroll longo
8. Não há texto cortado
9. Não há card quebrado

## Próximos Passos

Futuras etapas podem incluir:
- Alterar redirecionamento do login para `/familia`
- Criar componentes Vue separados para melhor organização
- Implementar Central de Oração
- Implementar Centro Pastoral
- Implementar Financeiro
- Implementar PIN de 4 dígitos
- Implementar Mural de Fotos
- Implementar Mural de Artes
