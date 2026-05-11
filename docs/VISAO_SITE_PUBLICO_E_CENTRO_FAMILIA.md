# Visão do Site Público e Centro Família Resgate

## Status

Este documento registra a visão detalhada do site público da igreja e do Centro Família Resgate.

Ele complementa o documento oficial VISAO_PRODUTO_RESGATE_2.md.

Este documento não implementa código.
Não cria banco.
Não cria telas.
Não cria permissões.
Não altera módulos existentes.

## Visão 1 — Site Público da Igreja

O sistema terá futuramente um site público.

### Público-alvo

Esse site será para:
- visitantes
- pessoas que ainda não têm login
- pessoas que não são membros
- pessoas que querem conhecer a igreja
- novos convertidos
- interessados
- pessoas que desejam pedir oração

### Propósito

O site público será a vitrine da igreja.

Ele deve mostrar:
- quem somos
- onde estamos
- horários de culto
- ministérios principais
- fotos marcantes
- acontecimentos importantes
- mensagens de fé
- caixinha de palavras/promessas
- botão para login
- botão para pedir oração
- botão para acompanhar oração
- botão para contato/visita, futuramente
- identidade espiritual da igreja

### Identidade visual

O site público deve ser:
- bonito
- premium
- espiritual
- acolhedor

Não deve parecer sistema administrativo.

## Navegação do Site Público

A navegação pública deve ter páginas separadas e URLs claras.

### URLs planejadas

Exemplos:

/home
/quem-somos
/onde-estamos
/caixinha-de-palavras
/fotos
/cultos
/pedir-oracao
/acompanhar-oracao
/contato
/login

### Regra de roteamento

Não colocar tudo na mesma URL.

Ao clicar em um item do menu, a experiência visual pode ter transição lateral, como se a página passasse para o lado.

Mas tecnicamente deve haver rota/URL própria para cada seção.

Exemplo:
Clicar em "Quem Somos" deve ir para /quem-somos
Clicar em "Onde Estamos" deve ir para /onde-estamos
Clicar em "Caixinha de Palavras" deve ir para /caixinha-de-palavras
Clicar em "Pedir oração" deve ir para /pedir-oracao
Clicar em "Acompanhar minha oração" deve ir para /acompanhar-oracao

A transição visual deve ser suave, moderna e lateral, mas sem confundir as rotas.

## Regra de Scroll do Site Público

O site público pode ter no máximo uma rolagem leve.

Preferência:
- uma tela principal
- no máximo 1 ou 2 scrolls

Evitar:
- página gigante
- conteúdo empilhado sem fim
- blocos demais
- caixas sobre caixas
- texto cortado
- cards cortados
- visual poluído

Se houver muito conteúdo, separar em páginas próprias.

## Caixinha de Palavras / Promessas

### Conceito

Antigamente a pessoa tirava um papelzinho com uma palavra bíblica, versículo, mensagem de fé, esperança ou encorajamento.

No Resgate 2.0, isso deve virar uma experiência digital bonita.

### Conteúdo planejado

A Caixinha de Palavras deve ter:
- aproximadamente 200 a 250 versículos ou frases de encorajamento
- mensagens bíblicas
- palavras de fé
- palavras de esperança
- palavras de coragem
- palavras de consolo
- palavras para visitantes
- palavras para membros
- palavras para momentos difíceis

### Experiência desejada

A experiência deve ser:
- bonita
- espiritual
- simples
- emocionante
- sem parecer brincadeira vazia
- sem parecer sorte espiritual irresponsável

A pessoa pode clicar em algo como "Receber uma palavra" e o sistema mostra uma palavra de forma visualmente bonita.

### Funcionalidades futuras

Futuramente pode ter:
- compartilhar
- salvar
- enviar para alguém
- histórico
- moderação das mensagens
- cadastro das mensagens pela Secretaria/Pastoral autorizada

## Galeria / Vitrine da Igreja

O site público deve ter uma vitrine da igreja.

### Conteúdo da vitrine

Mostrar:
- fotos principais
- momentos marcantes
- cultos especiais
- eventos
- congressos
- batismos
- comunhão
- ministérios
- ações sociais, se houver
- encontros de jovens
- atividades especiais

### Regra de seleção

Não deve virar álbum infinito.

A página pública deve mostrar apenas:
- fotos mais bonitas
- fotos mais marcantes
- destaques selecionados

A galeria completa pode ser módulo futuro separado.

## Central de Oração no Site Público

A igreja precisa ser uma igreja de oração.

Por isso, o site público deve ter uma entrada clara para a Central de Oração.

### Botões principais

1. Pedir oração
2. Acompanhar minha oração

Esses botões devem aparecer de forma bonita, espiritual e acolhedora.

Não devem parecer botões administrativos.

## Página Pública de Pedido de Oração

### Visual desejado

A página de pedido de oração deve ser linda, espiritual, premium e acolhedora.

Elementos visuais:
- painel bonito de oração
- tirinhas, papéis, folhas ou cartões visuais
- versículos sobre oração
- frases de fé
- frases de esperança
- artes suaves
- ambiente de cuidado
- sem poluição
- sem parecer formulário frio
- sem parecer sistema administrativo

### Campos do formulário

A página deve permitir:
1. Nome, opcional
2. WhatsApp, opcional
3. Pedido anônimo
4. Caixa de texto grande para escrever o pedido de oração
5. Texto longo
6. Possibilidade de escrever bastante
7. Botão de enviar pedido

### Regras de preenchimento

Nome e WhatsApp não são obrigatórios.
A pessoa pode pedir oração em anonimato.
A caixa de texto do pedido deve aceitar texto longo.
Não limitar de forma pequena.

## Código do Pedido de Oração

Ao enviar um pedido de oração, o sistema gera um código único.
Esse código deve ser mostrado para a pessoa guardar.
A pessoa poderá usar esse código para acompanhar o pedido depois.

O sistema deve explicar claramente:
"Guarde este código. Com ele você poderá acompanhar seu pedido de oração."

Esse código deve ser:
- seguro
- difícil de adivinhar
- sem expor dados sensíveis
- vinculado ao pedido

## Acompanhar Pedido de Oração

### Fluxo 1 — Apenas com o código

A pessoa informa o código e acessa a conversa/acompanhamento daquele pedido.

### Fluxo 2 — Código e conta

A pessoa informa o código e pode criar uma conta simples para acompanhar a oração, receber mensagens e futuramente se aproximar da igreja.

Essa conta pode futuramente se tornar um cadastro mais completo, caso a pessoa queira ser membro/participante da igreja.

## Conversa de Acompanhamento Tipo WhatsApp

O acompanhamento da oração deve ter experiência viva, parecida com uma conversa.

A conversa deve ser:
- humana
- simples
- organizada
- estilo chat
- parecida com WhatsApp
- com mensagens entre pessoa e intercessor autorizado
- com histórico
- com status
- com privacidade

A pessoa pode responder.
O intercessor pode responder.
O acompanhamento deve ser vivo, não apenas um formulário morto.

## Botão "Quero Conhecer a Igreja"

Dentro da conversa de acompanhamento, pode existir futuramente um botão como:
"Quero conhecer melhor a igreja"
"Quero fazer parte da Família Resgate"
"Quero ser membro desta igreja"

Esse botão deve encaminhar a pessoa para um fluxo de cadastro/solicitação.
Não deve transformar a pessoa em membro automaticamente.
Deve criar uma solicitação ou cadastro pendente para a Secretaria avaliar.

## Pedidos de Oração de Membros Logados

Membros, congregados ou usuários logados também podem pedir oração.
Eles podem pedir oração identificados ou anonimamente.

Mesmo sendo da igreja, o anonimato pode ser importante.
Algumas pessoas deixam de pedir ajuda por vergonha, medo ou exposição.
O sistema deve permitir cuidado espiritual com privacidade.

Se o membro pedir anonimato:
- o intercessor pode ver o conteúdo do pedido
- mas não deve ver a identidade, salvo regra pastoral/segurança muito específica
- deve haver proteção contra exposição indevida

Esse ponto precisa ser planejado com muito cuidado técnico e pastoral.

## Intercessores Autorizados

Intercessores são pessoas autorizadas.
Podem ser membros, obreiros, líderes, pessoas maduras na fé, pessoas convidadas pela Secretaria ou pessoas autorizadas pelo Pastor.

A pessoa não vira intercessor automaticamente.
Ela precisa ser autorizada pela Secretaria ou pelo Pastor.

No perfil/acesso da pessoa, deve aparecer futuramente o sistema "Central de Oração".
Aparece somente para quem tiver permissão.

## Distribuição Justa dos Pedidos

Os pedidos de oração devem chegar aos intercessores de forma justa e equilibrada.

Regra desejada:
Não pode chegar 10 pedidos para um intercessor e 3 para outro sem motivo.

A distribuição deve ser parecida com rodízio:
- Pedido 1 vai para intercessor A
- Pedido 2 vai para intercessor B
- Pedido 3 vai para intercessor C
- Quando todos receberem, volta para A

Também considerar:
- intercessor ativo/disponível
- limite de pedidos em aberto
- urgência
- encaminhamentos
- especialidade/cuidado, se existir no futuro
- ausência ou indisponibilidade

O sistema deve evitar sobrecarga e manter justiça.

## Classificação do Pedido pelo Intercessor

O intercessor deve poder classificar o pedido.

Exemplos:
- pedido normal
- pedido urgente
- oração grave
- acompanhamento contínuo
- encaminhar ao pastor
- encerrado

O sistema deve permitir marcar urgência com cuidado.
Não deve banalizar urgência.
Não deve expor publicamente casos graves.

## Anotações Privadas do Intercessor

Ao lado da conversa, o intercessor deve ter uma área de anotações privadas.

Essa área serve para registrar:
- o que percebeu
- pontos importantes da conversa
- datas relevantes
- cuidados necessários
- próximos passos
- observações espirituais e pastorais
- histórico do caso

Essas anotações devem ficar guardadas para consulta futura.
Devem ser privadas e protegidas.
Não devem ser vistas pela pessoa que fez o pedido.
Devem ter regras de acesso claras.

## Encaminhar ao Pastor

O intercessor deve poder encaminhar um pedido ou conversa ao Pastor.

Motivos:
- caso grave
- necessidade de aconselhamento
- situação delicada
- visão pastoral necessária
- acompanhamento pessoal
- risco espiritual/emocional
- pedido de conversa com o pastor

O encaminhamento deve:
- registrar motivo
- avisar o pastor
- manter histórico
- preservar privacidade
- indicar quem encaminhou
- indicar data/hora
- manter status

O Pastor pode aceitar, acompanhar ou reencaminhar conforme o fluxo futuro.

## Relação entre Central de Oração e Centro Pastoral

Central de Oração e Centro Pastoral são sistemas diferentes, mas conectados.

Central de Oração:
- recebe pedidos de oração
- acompanha conversas de oração
- distribui para intercessores
- permite anotações e encaminhamentos

Centro Pastoral:
- recebe casos encaminhados
- cuida de aconselhamento pastoral
- acompanha visitantes
- acompanha solicitações pastorais
- tem visão pastoral mais ampla

Nem todo pedido de oração precisa ir para o Pastor.
Somente casos encaminhados ou classificados como necessários.

## Relação entre Central de Oração e Secretaria

Secretaria não deve ver detalhes sensíveis de oração por padrão.

Mas pode administrar:
- quem tem permissão de intercessor
- cadastros
- solicitações administrativas
- vínculo de usuário, quando necessário
- configurações autorizadas

Conteúdo espiritual sensível deve ter proteção própria.

## Segurança e Privacidade da Central de Oração

A Central de Oração precisa de alto cuidado com privacidade.

Regras:
- pedidos anônimos devem proteger identidade
- dados pessoais opcionais
- WhatsApp opcional
- conversa protegida
- intercessores autorizados
- logs de acesso
- logs de encaminhamento
- status do pedido
- histórico seguro
- notas privadas protegidas
- nenhum dado sensível exposto sem permissão

Cuidados importantes:
- não prometer sigilo absoluto se houver risco de vida ou situação grave
- prever encaminhamento pastoral
- prever encaminhamento responsável em situações críticas
- registrar acessos sensíveis

## Status Futuros do Pedido de Oração

Possíveis status:
- recebido
- aguardando intercessor
- em oração
- em conversa
- em acompanhamento
- encaminhado ao pastor
- respondido
- encerrado
- arquivado

## Permissões Futuras da Central de Oração

Possíveis permissões futuras:
- intercession.access
- intercession.requests.view
- intercession.requests.respond
- intercession.requests.classify
- intercession.requests.forward_to_pastor
- intercession.notes.create
- intercession.notes.view
- intercession.notes.manage
- intercession.assignments.view
- intercession.assignments.manage
- intercession.public_requests.receive
- intercession.anonymous_requests.handle

Pastor pode ter permissões para ver encaminhamentos pastorais:
- pastoral.intercession_referrals.view
- pastoral.intercession_referrals.manage

Secretaria/Administração podem ter permissões administrativas, sem acesso amplo ao conteúdo sensível por padrão:
- intercession.settings.manage
- intercession.intercessors.manage

## Visão 2 — Login

O site público deve ter acesso ao login.

Login básico:
- e-mail
- senha

Depois de autenticar, a pessoa entra no Centro Família Resgate.

O login é a porta de entrada para o ecossistema interno.

## Visão 3 — Centro Família Resgate

Depois do login, o usuário entra no Centro Família Resgate.

O Centro Família Resgate é a página principal da pessoa logada.

Não é a Secretaria.
Não é a Administração.
Não é o Pastor.
Não é o Financeiro.

É o centro de entrada do usuário dentro do ecossistema.

Mensagem/conceito:
"Bem-vindo ao centro da Família Resgate"

O Centro deve transmitir:
- casa
- família
- acolhimento
- pertencimento
- organização
- comunhão
- espiritualidade
- clareza

## Visão Geral do Centro Família Resgate

A visão geral deve ter resumo compacto.

Não deve ter scroll longo.
Não deve ter página gigante.
Não deve ter cards demais empilhados.

A tela inicial deve mostrar os principais resumos:

1. Saudação personalizada
Exemplo: "Bem-vindo, Daniel"

2. Aniversários do dia
Mostrar membros/pessoas aniversariantes do dia.

3. Membro destaque
Mostrar destaque atual, quando o módulo existir.

4. Avisos principais
Avisos importantes da igreja.

5. Próximos eventos
Eventos/cultos/reuniões próximos.

6. Mural de fotos
Pequeno resumo visual com fotos recentes ou marcantes.

7. Mural de artes
Artes dos eventos da semana.

8. Atalhos principais
Botões para áreas que a pessoa pode acessar.

9. Alertas pessoais
Exemplo:
- solicitação pendente
- escala pendente
- aviso importante
- pedido de atualização cadastral

10. Palavra do dia
Pode puxar uma palavra/versículo da Caixinha de Palavras.

11. Oração
Atalho para pedir oração ou acompanhar pedidos de oração, conforme contexto.

A tela deve ser compacta e organizada.

Se houver muito conteúdo, usar:
- carrossel compacto
- abas
- modal
- drawer lateral
- cards resumidos
- botão "ver mais"

Não empilhar tudo na mesma página.

## Menu Superior do Usuário

No topo direito deve aparecer:
- nome do usuário
- foto/avatar
- acesso rápido ao perfil

Ao clicar ou passar o mouse, abre um menu/dropdown compacto.

Esse menu pode mostrar:
- meu perfil
- meus dados
- minhas solicitações
- sistemas disponíveis
- sair

O menu não pode ser grande demais.
Não pode quebrar layout.
Não pode ficar cortado.
Não pode invadir a tela.

## Sistemas Internos Autorizados

Dentro do Centro Família Resgate, cada pessoa deve ver apenas os sistemas que tem autorização para acessar.

Sistemas internos futuros/principais:

1. Secretaria
2. Administração Geral
3. Financeiro / Tesouraria
4. Centro Pastoral
5. Cantina
6. Central de Oração
7. Obreiros / Escalas
8. Louvor / Worship Central
9. Jovens / Resgatados
10. Estudos Bíblicos
11. Departamentos
12. Oportunidades
13. Mídia
14. Área do Membro
15. Visitantes, quando aplicável

Regra:
Cada sistema aparece conforme permissões/perfis do usuário.

Exemplos:
- Se Daniel for Administrador Geral e Secretário, ele pode ver todos os sistemas autorizados para esses perfis
- Um membro comum só vê as áreas permitidas para ele
- Um intercessor vê a Central de Oração
- Um obreiro vê o sistema de obreiros quando estiver autorizado
- Um pastor vê o Centro Pastoral
- Um líder de departamento vê o que tiver permissão

## PIN de 4 Dígitos para Áreas Sensíveis

Cada sistema interno sensível pode exigir PIN de 4 números.

A primeira vez que a pessoa autorizada tentar acessar uma área protegida, o sistema pede:
"Crie seu PIN de 4 números"

Depois, nas próximas vezes, o sistema pede o PIN criado.

O PIN deve servir como segunda proteção para áreas internas/sensíveis.

Regras futuras:
- PIN deve ter 4 dígitos
- PIN pertence ao usuário
- PIN deve ser armazenado com segurança, nunca em texto puro
- usuário pode redefinir PIN por fluxo seguro
- tentativas erradas devem ser limitadas
- acesso sensível deve gerar log
- áreas críticas podem exigir PIN mesmo com login ativo

Não implementar PIN agora.
Apenas documentar e planejar.

## Permissões e Ecossistema

Tudo deve conversar com o sistema de permissões já existente.

O ecossistema deve funcionar assim:
- Login autentica a pessoa
- Permissões definem quais sistemas aparecem
- PIN protege áreas sensíveis
- ActivityLog registra ações importantes
- SystemAlert avisa quando necessário
- Policies protegem o backend
- frontend nunca deve ser a única proteção

Não confiar apenas em esconder botão na tela.
Toda área protegida precisa de backend protegido.

## Regra Visual do Centro Família Resgate

Visual:
- premium
- espiritual
- moderno
- escuro/cinza
- detalhes laranja-dourado
- limpo
- organizado
- acolhedor

Evitar:
- dashboard poluído
- muitos cards grandes
- scroll longo
- caixas em cima de caixas
- textos cortados
- blocos desalinhados
- visual velho
- tabelas desnecessárias
- excesso de informação

A tela principal deve parecer uma central bonita, não uma planilha.

## Relação com a Secretaria

A Secretaria é um sistema interno separado.

O Centro Família Resgate pode mostrar atalhos para a Secretaria apenas para quem tem permissão.

Secretaria continua responsável por:
- cadastros
- famílias
- responsáveis
- acessos
- alertas
- solicitações
- departamentos
- aprovações

Mas o usuário comum não deve cair direto na Secretaria.
Ele deve cair no Centro Família Resgate.

## Relação com o Administrador

Administrador Geral pode ver mais sistemas.

Mas o Centro Família Resgate continua sendo a entrada principal.

Administração Geral será uma área interna própria.

Não misturar:
- Centro Família Resgate
- Secretaria
- Administração
- Centro Pastoral
- Central de Oração

Cada um tem seu papel.

## Relação com o Pastor

Pastor não é administrador técnico.

Pastor poderá ter sistema próprio futuramente:
- Centro Pastoral

O Centro Pastoral aparece para quem tiver permissão pastoral.
Ele não deve aparecer para todos.

## Relação com Intercessores

Intercessor não é automaticamente Pastor.
Intercessor não é automaticamente Administrador.
Intercessor não é automaticamente Secretaria.

Intercessor é uma função autorizada.

Quem tiver permissão verá a Central de Oração no Centro Família Resgate.

## Relação com Membros Sem Acesso

Nem toda pessoa cadastrada tem usuário.

Quem não tem login acessa apenas o site público.

Quem tem login acessa o Centro Família Resgate.

Quem é membro batizado pode ter recursos específicos, mas membro e usuário continuam conceitos separados.

## O Que Não Implementar Agora

Não implementar agora:
- site público
- transição lateral de páginas
- caixinha de palavras
- banco de 250 versículos
- galeria pública
- novo login visual
- Centro Família Resgate
- dropdown do usuário
- seleção de sistemas internos
- PIN de 4 dígitos
- dashboard do membro
- mural de fotos
- mural de artes
- Central de Oração
- pedido público de oração
- chat de acompanhamento
- distribuição de pedidos entre intercessores
- cadastro online completo
- sistema complexo de endereço/morada do sistema antigo

Isso é visão e planejamento para orientar a próxima etapa.

## Referência ao Cadastro

Para detalhes sobre cadastro pela Secretaria e cadastro online, incluindo análise do sistema antigo como referência conceitual, ver a seção "Cadastro pela Secretaria e Cadastro Online" em docs/VISAO_PRODUTO_RESGATE_2.md.

Essa seção documenta:
- Boas partes do sistema antigo (conceitual)
- Partes que devem ser descartadas
- Regra para Resgate 2.0 (não copiar código, usar como referência)
- Endereço/Morada redesenhado e simplificado
- Experiência visual do cadastro por etapas

## Planejamento da Próxima Etapa

Sugerir que a próxima etapa funcional seja:

### ETAPA 11 — Arquitetura visual e experiência base do Centro Família Resgate

Objetivo da Etapa 11:
Planejar e começar a estrutura visual da entrada logada do ecossistema, sem ainda implementar todos os módulos futuros.

Mas antes de implementar, deve ser entregue um plano técnico com:
- rotas previstas
- componentes previstos
- regras de permissão
- estrutura visual
- fluxo de login até Centro Família Resgate
- diferença entre Centro Família Resgate, Secretaria, Administração, Centro Pastoral e Central de Oração
- riscos
- o que implementar agora
- o que deixar para depois
