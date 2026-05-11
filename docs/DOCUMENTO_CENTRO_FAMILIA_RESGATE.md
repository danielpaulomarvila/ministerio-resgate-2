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
- Tema claro premium (bg-[#F8F6F3] - bege claro)
- Menu lateral fixo à esquerda com fundo azul escuro (gradiente de #1e3a5f para #0f2744)
- Sidebar com logo "R" dourado, título "Família Resgate" e subtítulo "Centro da igreja"
- Itens principais do sidebar: Centro Família, Secretaria, Departamentos, Acessos, Perfis de Acesso (aparecem conforme permissões)
- Seção "Futuros sistemas" com: Central de Oração, Palavra do Dia, Financeiro, Cantina, Centro Pastoral (todos com badge "Em breve")
- Footer do sidebar com ilustração de família e frase: "Somos uma família que ama, acolhe e transforma vidas."
- Header superior claro com breadcrumb "🏠 Centro Família"
- Ícones de notificação e mensagem (visuais apenas)
- Avatar do usuário com inicial, nome e saudação "Bem-vindo(a)!"
- Dropdown com "Meu perfil" e "Sair"
- Sidebar responsiva: recolhível em mobile com overlay, fixo em desktop
- Layout premium, acolhedor, familiar e espiritual

### 4. Página Vue Principal

**Arquivo:** `resources/js/Pages/Familia/Index.vue`

**Seções:**
1. Hero Section com gradiente bege claro, título "Bem-vindo ao centro da Família Resgate! 👋", saudação personalizada, frase "🧡 Sua casa dentro do ecossistema Resgate", versículo "Eu e a minha casa serviremos ao Senhor. — Josué 24:15"
2. Card de aniversariantes do dia (com emoji 🎂, contador circular, lista de nomes com emoji 🎉)
3. Card de sistemas disponíveis (com emoji 🚀, mini-cards coloridos para cada sistema: Secretaria 👨‍💻, Departamentos 🏛️, Acessos 🛡️, Perfis de Acesso 🪪)
4. Card de avisos e pendências (com emoji 🔔, check verde quando sem avisos)
5. Card futuro de Central de Oração (com emoji 🙏, badge "Em breve")
6. Card futuro de Palavra do Dia (com emoji 📖, badge "Em breve")
7. Card futuro de Mais sistemas (com emoji ✨, badge "Em breve")
8. Footer com versículo e frase "Bem-vindo à sua casa espiritual! 🏠"

**Visual:**
- Fundo bege claro (#F8F6F3)
- Cards brancos com bordas cinza suaves e sombras
- Ícones/emojis coloridos para cada seção
- Badges "Em breve" em âmbar para sistemas futuros
- Gradientes sutis nos cards de sistemas
- Responsivo: grid de 3 colunas em desktop, 2 em tablet, 1 em mobile
- Textos legíveis com bom contraste
- Aparência premium, acolhedora, familiar e espiritual

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

## Ajuste Visual Após Pesquisa no Sistema Antigo

Após pesquisa no sistema antigo e aprovação de nova referência visual, foi decidido mudar completamente o visual do Centro Família Resgate. Foram feitos os seguintes ajustes:

**Decisão Visual Oficial:**
- Mudança de tema escuro para tema claro premium
- Fundo bege claro (#F8F6F3) em vez de cinza escuro
- Menu lateral fixo à esquerda com fundo azul escuro (gradiente de #1e3a5f para #0f2744)
- Cards brancos com bordas sutis e sombras
- Ícones/emojis coloridos para cada seção
- Aparência premium, acolhedora, familiar e espiritual

**Ajustes no Layout (FamilyHubLayout.vue):**
- Fundo alterado de `bg-gray-950` para `bg-[#F8F6F3]` (bege claro)
- Adicionado sidebar fixo à esquerda com 280px de largura
- Sidebar com fundo azul escuro premium (gradiente)
- Logo "R" dourado no topo do sidebar
- Título "Família Resgate" e subtítulo "Centro da igreja"
- Itens principais: Centro Família, Secretaria, Departamentos, Acessos, Perfis de Acesso (conforme permissões)
- Seção "Futuros sistemas" com: Central de Oração, Palavra do Dia, Financeiro, Cantina, Centro Pastoral (todos com badge "Em breve")
- Footer do sidebar com ilustração de família e frase
- Header superior claro com breadcrumb "🏠 Centro Família"
- Ícones de notificação e mensagem (visuais apenas)
- Avatar do usuário com inicial, nome e saudação
- Sidebar responsiva: recolhível em mobile com overlay, fixo em desktop
- Header com backdrop blur e borda cinza suave

**Ajustes na Página (Index.vue):**
- Hero Section recriado com gradiente bege claro (#FFF8F0 a #F5EBE0)
- Título: "Bem-vindo ao centro da Família Resgate! 👋"
- Saudação personalizada: "Olá, {nome}. Que bom ter você aqui."
- Frase: "🧡 Sua casa dentro do ecossistema Resgate."
- Versículo: "Eu e a minha casa serviremos ao Senhor. — Josué 24:15"
- Ilustrações com emojis no fundo do hero (✝️, 👨‍👩‍👧‍👦, 🏠)
- Cards recriados com:
  - Fundo branco com bordas cinza sutis
  - Ícones/emojis grandes e coloridos
  - Contadores circulares
  - Gradientes sutis nos mini-cards de sistemas
  - Cantos arredondados (rounded-2xl)
- Card de aniversariantes: emoji 🎂, lista com 🎉, contador pink
- Card de sistemas: emoji 🚀, mini-cards coloridos (Secretaria 👨‍💻 azul, Departamentos 🏛️ roxo, Acessos 🛡️ verde, Perfis de Acesso 🪪 âmbar)
- Card de avisos: emoji 🔔, check verde ✅
- Cards futuros (Oração 🙏, Palavra do Dia 📖, Mais sistemas ✨) com badges "Em breve" e opacidade reduzida
- Footer com versículo e frase "Bem-vindo à sua casa espiritual! 🏠"
- Responsivo: grid de 3 colunas em desktop, 2 em tablet, 1 em mobile
- Textos legíveis com bom contraste

**Correção no Controller:**
- Mantido uso de `$user->hasPermission()` para verificar permissões corretamente
- Atalhos mantidos: Secretaria, Departamentos, Acessos, Perfis de Acesso

## Visual

**Identidade visual seguida:**
- Tema claro premium (bg-[#F8F6F3] - bege claro)
- Menu lateral azul escuro (gradiente de #1e3a5f para #0f2744)
- Cards brancos com bordas cinza sutis
- Dourado/laranja (amber-400 a amber-600) como destaque principal
- Azul para informação e sidebar
- Verde para sucesso
- Roxo para departamentos
- Pink para aniversariantes
- Âmbar para perfis
- Ícones/emojis coloridos para cada seção
- Badges "Em breve" em âmbar
- Visual premium, acolhedor, familiar, espiritual, moderno, elegante, limpo
- Hero Section com gradiente bege claro
- Ilustrações com emojis
- Textos legíveis com bom contraste

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
- Fundo branco puro demais
- Visual padrão Laravel
- Logo padrão Laravel
- Tema escuro nesta tela
- Texto sobre fundo sem contraste

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
