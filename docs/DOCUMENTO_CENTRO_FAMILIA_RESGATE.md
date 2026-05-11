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
- `getAvailableShortcuts($user): array` - Monta lista de atalhos permitidos usando `hasPermission()`

### 3. Layout

**Arquivo:** `resources/js/Layouts/FamilyHubLayout.vue`

**Características:**
- Tema escuro premium (bg-gray-950)
- Header com identidade Resgate (logo com "R" em gradiente laranja)
- Nome do sistema: "Família Resgate" com subtítulo "Centro da igreja"
- Avatar do usuário com inicial
- Dropdown com "Meu perfil" e "Sair"
- Layout limpo e organizado

### 4. Página Vue Principal

**Arquivo:** `resources/js/Pages/Familia/Index.vue`

**Seções:**
1. Hero Section: "Centro Família Resgate" com saudação personalizada
2. Card de aniversariantes do dia (com ícone e visual premium)
3. Card de sistemas disponíveis (com ícone e visual premium)
4. Card de avisos e pendências (com ícone e visual premium)
5. Card futuro de oração (com badge "Em breve" e ícone espiritual)
6. Card futuro de palavra do dia (com badge "Em breve" e ícone espiritual)

**Visual:**
- Fundo escuro (bg-gray-950)
- Cards com bordas sutis e hover effects
- Ícones coloridos (laranja para aniversariantes, azul para sistemas, verde para avisos, roxo para oração, âmbar para palavra)
- Badges "Em breve" em laranja para sistemas futuros
- Gradiente no hero section

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
- Backend protege atalhos com verificação de permissões usando `hasPermission()`
- Página funciona mesmo se usuário não tiver `person_id`
- Layout simples evita scroll longo
- Componentes mantidos simples para evitar complexidade

## Ajuste Visual Após Teste Manual

Após teste manual inicial, foi identificado que o visual não estava no padrão oficial do Resgate 2.0. Foram feitos os seguintes ajustes:

**Ajustes no Layout:**
- Fundo alterado de `bg-gray-900` para `bg-gray-950` (mais escuro e premium)
- Logo padrão Laravel removido
- Adicionada identidade visual Resgate: logo com "R" em gradiente laranja
- Nome do sistema: "Família Resgate" com subtítulo "Centro da igreja"
- Avatar do usuário com inicial em círculo laranja
- Header com backdrop blur para efeito premium

**Ajustes na Página:**
- Criado Hero Section com gradiente e título forte "Centro Família Resgate"
- Saudação personalizada destacada
- Cards melhorados com:
  - Ícones coloridos para cada seção
  - Bordas sutis e hover effects
  - Fundo semitransparente (`bg-gray-900/50`)
  - Cantos arredondados (`rounded-2xl`)
- Badges "Em breve" em laranja para sistemas futuros
- Ícones espirituais para Oração e Palavra do dia

**Correção no Controller:**
- Alterado de `$user->can()` para `$user->hasPermission()` para verificar permissões corretamente
- Adicionado atalho para "Perfis de Acesso" quando usuário tem permissão `permissions.view`

## Visual

**Identidade visual seguida:**
- Tema escuro premium (bg-gray-950)
- Preto/cinza como base
- Laranja-dourado (amber-500) como destaque principal
- Azul para informação
- Verde para sucesso
- Roxo para espiritual
- Visual premium, espiritual, moderno, elegante, limpo
- Hero Section com gradiente
- Ícones coloridos
- Badges "Em breve" elegantes

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
- Fundo branco dominante
- Visual padrão Laravel
- Logo padrão Laravel

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
