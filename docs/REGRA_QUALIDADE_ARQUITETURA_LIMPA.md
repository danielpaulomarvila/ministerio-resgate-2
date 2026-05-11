# Regra de Qualidade e Arquitetura Limpa do Resgate 2.0

## Status

Este documento registra a regra definitiva para todas as próximas etapas do Resgate 2.0.

Antes de implementar qualquer sistema novo, módulo novo ou tela nova, é obrigatório seguir esta regra.

## Princípio Fundamental

O projeto não pode virar sistema bagunçado.

Não pode ter:
- nó
- lixo
- duplicidade
- gambiarra
- arquivos paralelos
- módulo criado pela metade
- tela bonita sem backend seguro
- backend sem permissão
- permissão só no frontend
- dado sensível exposto
- código antigo copiado
- módulo novo quebrando módulo anterior

## 1. Responsabilidade Clara

Cada novo sistema deve nascer com responsabilidade clara.

### O que esse sistema faz
- Definir o propósito principal
- Definir o escopo de atuação
- Definir os objetivos específicos

### O que ele não faz
- Definir limites claros
- Definir o que está fora do escopo
- Evitar responsabilidade duplicada

### Com quais sistemas conversa
- Definir integrações necessárias
- Definir dependências
- Definir comunicação entre sistemas

### Quais limites ele tem
- Definir restrições técnicas
- Definir restrições de negócio
- Definir restrições de segurança

## 2. Nomes Padronizados

Todos os nomes devem ser claros e padronizados.

### Rotas claras
- URLs sem ambiguidade
- Nomes que indicam o recurso
- Estrutura consistente

### Controllers claros
- Nome que indica a responsabilidade
- Métodos com nomes descritivos
- Separar por contexto quando necessário

### Models claros
- Nome que representa a entidade
- Relacionamentos bem definidos
- Métodos com nomes descritivos

### Pages Vue claras
- Nome que indica a página
- Estrutura de pastas organizada
- Componentes com nomes descritivos

### Documentos claros
- Nome que indica o conteúdo
- Estrutura organizada
- Sem ambiguidade

### Nomes proibidos
Nada de:
- Novo
- Final
- Corrigido
- V2
- Backup
- Copy

## 3. Banco de Dados Planejado Antes

Antes de criar qualquer tabela, planejar completamente.

### Tabelas necessárias
- Identificar todas as tabelas
- Definir propósito de cada uma
- Evitar duplicação de conceito

### Relacionamentos
- Definir relacionamentos entre tabelas
- Definir cardinalidade
- Definir chaves estrangeiras

### Índices
- Identificar campos que precisam de índice
- Justificar cada índice
- Evitar índices desnecessários

### Soft delete
- Aplicar quando fizer sentido
- Justificar uso
- Documentar regras de exclusão

### Auditoria
- Aplicar quando necessário
- Definir campos de auditoria
- Documentar o que é auditado

### Dados sensíveis protegidos
- Identificar dados sensíveis
- Definir proteção necessária
- Criptografar quando apropriado
- Nunca expor em log ou resposta

### Nada de tabela duplicada
- Um conceito = uma tabela
- Evitar redundância
- Normalizar quando fizer sentido

## 4. Permissões e Segurança Desde o Começo

Segurança não é algo para adicionar depois.

### Policies
- Criar Policy para cada model sensível
- Definir métodos de autorização
- Testar permissões

### AccessProfile/Permission
- Definir perfis de acesso
- Definir permissões específicas
- Vincular perfis a usuários

### Backend protegido
- Nunca confiar apenas no frontend
- Validar permissões no controller
- Usar Policies nas rotas

### Frontend apenas como camada visual
- Frontend não deve ser a única proteção
- Esconder botões é UX, não segurança
- Backend deve sempre validar

### ActivityLog
- Registrar ações importantes
- Registrar quem fez
- Registrar quando fez
- Registrar o que foi alterado

### SystemAlert
- Usar para alertas críticos
- Usar para notificações importantes
- Definir quem pode criar

### Events/Listeners/Jobs
- Usar para processos assíncronos
- Usar para decoupling
- Documentar o fluxo

## 5. Frontend Limpo e Premium

A interface deve seguir a regra visual oficial.

### Sem scroll longo
- Tela compacta
- Informação resumida
- Usar "ver mais" quando necessário

### Sem caixa em cima de caixa
- Layout limpo
- Espaçamento adequado
- Hierarquia visual clara

### Sem texto cortado
- Espaço suficiente
- Texto completo visível
- Sem truncamento desnecessário

### Sem botão desalinhado
- Grid consistente
- Alinhamento correto
- Espaçamento uniforme

### Sem informação demais na mesma tela
- Informação relevante
- Focar no essencial
- Separar em abas ou modais

### Sem visual antigo
- Design moderno
- Cores oficiais do projeto
- Tipografia consistente

### Sem copiar sistema velho
- Design original
- Experiência nova
- Não copiar layout antigo

### Padrões de organização
Quando houver muita informação, usar:
- Abas
- Modais
- Drawers laterais
- Cards compactos
- Passos guiados
- Filtros
- Busca inteligente
- Paginação
- Seções recolhíveis

## 6. Integração Sem Confusão

Cada sistema tem seu papel claro.

### Centro Família Resgate
- Entrada logada
- Portal central do usuário
- Acolhimento e organização

### Secretaria
- Área administrativa privada
- Cadastros, famílias, responsáveis
- Acessos, alertas, solicitações
- Departamentos, aprovações

### Administração Geral
- Controle geral/técnico
- Configurações do sistema
- Perfis, permissões
- Auditoria, operação ampla

### Financeiro/Tesouraria
- Sistema financeiro
- Receitas, despesas
- Contas a receber/pagar
- Relatórios financeiros

### Centro Pastoral
- Cuidado pastoral
- Aconselhamento
- Acompanhamento espiritual
- Visitantes

### Central de Oração
- Oração e intercessão
- Pedidos de oração
- Intercessores autorizados
- Acompanhamento de oração

### Cantina
- Venda/estoque/repasse
- Produtos
- Dívidas e créditos
- Repasse para Tesouraria

### Obreiros/Escalas
- Operação do culto
- Escalas de obreiros
- Registro de visitantes
- Troca de escala

### Louvor/Worship
- Música, repertório
- Escalas de louvor
- Ensaios
- Cifras e músicas

### Jovens/Resgatados
- Jovens, equipes
- Missões
- Devocionais
- Eventos específicos

### Estudos Bíblicos
- Leitura bíblica
- Devocional guiado
- Edificação
- Progresso bíblico

### Regra de integração
Cada sistema pode se integrar, mas não pode invadir a responsabilidade do outro.
Não misturar conceitos.
Não duplicar funcionalidade.
Definir claramente onde termina um e começa o outro.

## 7. Documentação Obrigatória

Toda etapa nova precisa atualizar a documentação.

### docs/VISAO_PRODUTO_RESGATE_2.md
- Atualizar se houver nova visão
- Registrar conceitos novos
- Atualizar quando mudar direção

### Documento próprio do módulo
- Criar se for módulo novo
- Definir responsabilidade
- Definir integrações
- Definir regras de negócio

### Checklist próprio do módulo
- Criar checklist específico
- Listar funcionalidades
- Listar validações
- Listar testes necessários

### docs/README.md
- Atualizar se criar documento novo
- Adicionar na seção apropriada
- Manter índice organizado

## 8. Validação Obrigatória

Antes de aprovar qualquer etapa, validar tudo.

### php artisan optimize:clear
- Limpar cache
- Garantir que não há cache antigo
- Verificar que tudo está atualizado

### php artisan test
- Rodar todos os testes
- Garantir que tudo passa
- Criar testes para funcionalidades novas

### npm run build
- Compilar assets
- Verificar que não há erro de build
- Garantir que frontend compila

### git status
- Verificar arquivos modificados
- Verificar se não há arquivos indesejados
- Garantir que está pronto para commit

### Verificar duplicidades
- Procurar arquivos com Novo, Final, Corrigido, V2, Backup, Copy
- Remover duplicidades
- Garantir nomes padronizados

### Testar páginas principais no navegador
- Abrir páginas principais
- Verificar que não há erro
- Testar fluxo principal
- Verificar console

### Verificar console
- Quando houver tela nova
- Verificar se há erro JavaScript
- Verificar se há warning
- Garantir console limpo

## 9. Regra de Aprovação

Uma etapa só será aprovada quando:

### Código funcionando
- Tudo implementado
- Sem bugs conhecidos
- Fluxos completos

### Tela abrir sem branco
- Página carrega
- Visual correto
- Sem erro de renderização

### Backend protegido
- Policies aplicadas
- Permissões validadas
- Dados sensíveis protegidos

### Testes passarem
- Todos os testes passam
- Testes novos criados
- Cobertura adequada

### Build passar
- npm run build sem erro
- Assets compilados
- Sem warning crítico

### Documentação atualizada
- Visão atualizada se necessário
- Documento do módulo criado
- Checklist criado
- README atualizado

### Não houver duplicidade
- Nomes padronizados
- Sem arquivos duplicados
- Sem conceitos duplicados

### Usuário validar visualmente
- Fluxo principal testado
- Visual aprovado
- Experiência validada

## Aplicação

Esta regra se aplica a todas as próximas etapas do Resgate 2.0.

Não há exceção.
Qualquer violação desta regra deve ser corrigida antes da aprovação.

Esta regra é parte integrante da qualidade e arquitetura do projeto.
