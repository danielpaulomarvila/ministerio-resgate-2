# Visão Oficial do Produto — Resgate 2.0

## Status

Este documento registra a visão oficial de produto do Ministério Resgate / Família Resgate.

Ele deve orientar as próximas etapas do sistema.

Este documento não implementa código.
Não cria banco.
Não cria telas.
Não cria permissões.
Não altera módulos existentes.

## Auditoria geral aprovada

A auditoria geral do projeto foi aprovada até a Etapa 10.

O projeto está estável com:

- banco validado
- migrations executadas
- models revisados
- relacionamentos organizados
- rotas funcionando
- controllers validados
- policies criadas
- events/listeners/jobs configurados
- scheduler ativo
- queue database configurada
- Vue compilando
- testes passando
- build passando
- documentação principal existente
- nenhuma duplicidade encontrada

Pendências não críticas:

- organizar documentos antigos da raiz para a pasta docs/
- fazer push dos commits locais para o GitHub

Status:

✅ Liberado para próxima etapa após teste visual e organização opcional da documentação.

## Uso do sistema antigo

O sistema antigo/repositório de pesquisa deve servir apenas como referência conceitual.

Pode ser usado para entender:

- ideias antigas
- fluxos pensados
- módulos desejados
- caminhos funcionais
- regras de negócio
- onde queremos chegar

Não deve ser usado para copiar:

- código
- visual
- documentos
- telas
- arquitetura antiga sem análise
- soluções antigas que geraram confusão

O Resgate 2.0 deve nascer como uma plataforma nova.

## Identidade do Resgate 2.0

O sistema deve ser:

- espiritual sem ser antiquado
- premium sem ser pesado
- moderno sem ser confuso
- organizado sem ser frio
- completo sem ser poluído
- didático sem parecer infantil
- bonito sem sacrificar a funcionalidade
- seguro sem ser travado
- inteligente sem parecer complicado

O objetivo é criar uma plataforma eclesiástica que pareça feita com cuidado, excelência e propósito.

O Resgate 2.0 não deve ser apenas um sistema de cadastro.
Ele deve ser um ecossistema eclesiástico.

## Linguagem oficial

A interface, menus, textos, documentos e mensagens devem estar em português do Brasil.

Como a igreja está em Portugal, campos legais e de endereço devem respeitar Portugal quando necessário:

- NIF
- Cartão de Cidadão
- Título de Residência
- Passaporte
- Morada
- Código postal
- Distrito
- Concelho/Município
- Freguesia
- Localidade

Usar "Cadastro" como termo principal para usuários brasileiros.

Evitar termos técnicos na interface quando houver alternativa mais clara.

Exemplo:
- usar "Identificador" em vez de "slug"
- identificadores técnicos devem continuar sem acento, sem espaços e protegidos contra tradução automática

## Regra visual oficial

A interface deve evitar:

- scroll longo desnecessário
- caixa sobre caixa
- blocos desalinhados
- texto cortado
- card cortado
- botão fora do lugar
- excesso de informação
- tabelas esmagadas
- visual genérico
- aparência de sistema improvisado

Cada tela deve ser pensada como uma mesa bem organizada:
tudo no lugar certo, nada sobrando, nada escondido.

### Cores oficiais

- base escura: preto/cinza
- destaque principal: laranja-dourado
- azul: informação
- verde: sucesso
- vermelho: alerta

## Estratégia para telas grandes

Quando uma funcionalidade tiver muita informação, não empilhar tudo.

Usar:

- abas
- filtros
- modais
- drawers laterais
- passos guiados
- cartões resumidos
- seções recolhíveis
- assistentes em etapas
- paginação
- busca inteligente
- painéis compactos
- atalhos contextuais

A prioridade é clareza.

## Regras estruturais de pessoa, usuário e membro

Pessoa, usuário e membro são conceitos diferentes.

### Pessoa

É o cadastro base.
Pode ou não ter usuário.
Pode ou não ser membro.

### Usuário

É quem tem login no sistema.
Deve estar vinculado a uma pessoa.

Ter usuário não significa ser membro.

### Membro

Membro é somente quem é batizado.

Não criar membro automaticamente ao criar pessoa.
Não criar membro automaticamente ao criar usuário.

## Regras de idade e membresia

### Menores de 11 anos

- não podem ter usuário próprio
- não são membros
- ficam vinculados à família e responsáveis
- ações financeiras futuras, como cantina, devem ser vinculadas aos responsáveis
- o sistema deve gerar alerta quando estiverem próximos de completar 11 anos

### De 11 a antes de 14 anos

São Júniores dos Resgatados.

- podem ter usuário
- precisam de supervisão dos pais/responsáveis
- podem ser membros se forem batizados

### De 14 a antes de 18 anos

São Jovens dos Resgatados.

- podem ter usuário
- não precisam da mesma supervisão obrigatória dos Júniores
- podem ser membros se forem batizados

### Adultos

- podem ter usuário
- só são membros se forem batizados

## Formulário online futuro de cadastro

No futuro, o sistema deve permitir cadastro online.

A própria pessoa poderá preencher seus dados e informar:

- dados pessoais
- família
- cônjuge
- filhos
- responsáveis
- documentos
- contatos
- vínculo com a igreja
- quem convidou/indicou

Cadastros vindos do formulário online devem entrar como solicitações pendentes para validação da Secretaria.

A Secretaria deve aprovar antes que os dados virem definitivos.

## Área futura do membro/participante

No futuro, cada pessoa com usuário poderá ter uma área própria.

Ela poderá visualizar:

- seus dados cadastrais
- credenciais
- família
- vínculos
- departamentos
- solicitações
- financeiro próprio quando existir
- dízimos/ofertas quando permitido
- dívidas/créditos quando existir
- histórico de participação

A pessoa não deve editar dados definitivos diretamente.

Ela poderá solicitar alteração, e a Secretaria deverá aprovar.

## Secretaria

A Secretaria é área privada própria.

Responsável por:

- pessoas
- famílias
- responsáveis
- acessos
- solicitações
- alertas
- departamentos
- documentos internos
- aprovações
- organização cadastral
- histórico administrativo

Mesmo que o administrador também seja secretário, o sistema deve manter os conceitos separados.

## Administração Geral

Administração Geral é diferente da Secretaria.

Pode cuidar de:

- configurações do sistema
- perfis
- permissões
- estrutura geral
- módulos críticos
- auditoria
- operação ampla

O Administrador do Sistema não deve ser confundido automaticamente com Pastor.

## Pastor e Painel Pastoral futuro

Pastor não é administrador técnico do sistema.

O Pastor deve ter visão pastoral, não controle técnico total.

O Painel Pastoral será módulo futuro próprio.

O Pastor poderá atuar como:

- verificador
- analisador
- cuidador pastoral
- encaminhador
- acompanhador espiritual

Ele pode futuramente receber:

- pedidos de oração
- solicitações de conversa
- visitantes
- listas de apresentação
- alertas pastorais
- informações de presença
- casos de acompanhamento

Mas não deve receber automaticamente permissões técnicas totais.

Uma pessoa pode ter perfil de Pastor e também de Administrador, se a igreja decidir, mas os perfis devem ser separados.

## Pedidos de oração futuros

O sistema deve futuramente permitir pedidos de oração.

Possíveis recursos:

- pessoa envia pedido
- pode solicitar acompanhamento pastoral
- pastor visualiza
- intercessão pode receber encaminhamento
- status do pedido
- privacidade
- logs
- histórico de acompanhamento

Possíveis status:

- recebido
- em oração
- em acompanhamento
- encaminhado
- encerrado

## Solicitação de conversa / aconselhamento

No futuro, uma pessoa poderá solicitar conversa ou aconselhamento.

O fluxo deve respeitar privacidade.

Possibilidades:

- pessoa solicita conversa
- pastor recebe
- pastor encaminha para líder/obreiro autorizado, se necessário
- status de acompanhamento
- histórico restrito
- observações pastorais sensíveis protegidas

## Visitantes inteligentes

O sistema de visitantes será um diferencial.

Visitantes poderão ser registrados pela recepção, obreiro escalado, secretaria ou fluxo autorizado.

Informações possíveis:

- nome
- família/acompanhantes
- WhatsApp
- se já frequenta outra igreja
- se é membro de alguma igreja
- origem
- quem convidou
- se deseja acompanhamento
- observações permitidas

## Registro guiado de visitantes

O formulário de visitantes não deve mostrar todas as perguntas de uma vez.

Deve ser uma experiência guiada, bonita e leve.

Formato desejado:

- uma pergunta por vez
- transição lateral suave
- sensação de conversa
- interface limpa
- sem poluição
- sem formulário gigante
- sem assustar o visitante

Depois de concluir, o sistema deve voltar para o painel de origem e mostrar que o visitante foi cadastrado.

## Visitante vindo com família

Se o visitante vier com família, o sistema deve agrupar as pessoas.

Exemplo:

- pai
- mãe
- filhos
- acompanhantes

A família deve ir organizada para o pastor ou dirigente, para apresentação no culto.

Não deve chegar como lista bagunçada de nomes soltos.

## Origem do visitante

O sistema deve registrar como o visitante conheceu a igreja.

Possíveis origens:

- convite de membro
- redes sociais
- passou em frente
- evento especial
- familiar
- amigo
- outro

Se for por convite de membro, registrar o membro que convidou.

Esse membro poderá ganhar pontuação futura para Membro Destaque, conforme regras aprovadas.

## Lista de visitantes para apresentação no culto

A lista de visitantes deve ser organizada para o pastor ou dirigente.

Deve mostrar:

- nome do visitante
- família vinculada
- origem
- quem convidou
- se deseja acompanhamento
- observações permitidas

O pastor poderá encaminhar essa lista para o dirigente do culto.

## Sistema de obreiros escalados

O sistema de obreiros será um diferencial importante.

Regra principal:

Apenas o obreiro escalado para o culto deve receber a responsabilidade operacional daquele culto.

Se ele não puder atuar, poderá encaminhar a escala para outro obreiro.

Esse encaminhamento precisa:

- ser solicitado pelo obreiro original
- ser aceito pelo obreiro substituto
- ficar registrado
- ter autorização conforme regra definida
- manter histórico

## Presença e chamada do culto

O sistema deve futuramente permitir registrar presença por culto/evento.

A presença poderá ser confirmada por:

- pastor
- obreiro escalado
- recepção
- secretaria
- líder autorizado

A presença deve alimentar:

- histórico da pessoa
- relatórios da Secretaria
- possíveis pontos futuros, se aprovado depois

O pastor hoje pode anotar presença no papel.
O sistema deve futuramente transformar isso em um fluxo simples, bonito e prático.

## Sistema de pontuação futuro

O sistema de pontuação será um diferencial do Resgate 2.0.

Ele deve ser planejado com justiça, separação de contextos e auditoria.

Não deve incentivar vaidade, humilhação, competição tóxica ou exposição negativa.

A pontuação deve incentivar:

- presença
- constância
- serviço
- leitura bíblica
- devoção
- evangelismo
- participação
- responsabilidade
- comunhão
- crescimento espiritual

## Camadas de pontuação

A pontuação deve ser separada em camadas.

### 1. Pontuação geral da igreja

Pode envolver pessoas da igreja em geral.

Exemplos:

- presença em culto
- participação em eventos
- servir em escalas
- levar visitante confirmado
- leitura bíblica, se aprovado
- devocional, se aprovado

Essa camada mede caminhada geral, não competição.

### 2. Pontuação dos jovens / Resgatados

Voltada para os jovens e júniores do grupo Resgatados.

Pode incluir:

- presença no culto dos jovens
- participação em desafios bíblicos
- levar Bíblia
- responder estudos
- cumprir missões do grupo
- participar em equipes
- trazer visitantes
- streak de participação
- atividades do congresso

### 3. Pontuação por equipes dos jovens

Voltada para equipes dos jovens.

Pode incluir:

- presença coletiva
- desafios por equipe
- evangelismo em equipe
- participação coletiva
- respostas bíblicas
- atividades especiais

### 4. Pontuação para Membro Destaque

Voltada para reconhecimento equilibrado de membros.

Deve considerar ações justas para membros em geral.

Pode incluir:

- presença
- serviço
- visitante confirmado
- evangelismo
- leitura bíblica/devocional, se aprovado
- participação fiel
- ajuda prática
- constância

Não deve incluir ações que só jovens ou equipes conseguem fazer.

Se uma ação é exclusiva dos jovens, ela não deve pontuar para Membro Destaque, porque seria injusto.

### 5. Pontuação para Jovem Destaque

Separada do Membro Destaque.

Pode considerar ações específicas dos jovens, como:

- desafios do grupo
- participação em equipes
- estudos dos jovens
- missões dos jovens
- eventos dos Resgatados

## Regra de justiça da pontuação

Nem toda ação pontua para todos.

Uma mesma ação pode pontuar em uma, duas ou três camadas, mas com pesos diferentes.

Exemplo:

Presença no culto:
- pode pontuar caminhada geral
- pode pontuar Membro Destaque
- se for jovem, pode pontuar também Jovem Destaque
- se houver equipe, pode pontuar equipe com peso diferente

Levar visitante confirmado:
- pode pontuar evangelismo
- pode pontuar Membro Destaque
- se for jovem, pode pontuar Jovem Destaque
- se for desafio de equipe, pode pontuar equipe

Desafio exclusivo dos jovens:
- pontua Jovem Destaque
- pode pontuar equipe dos jovens
- não pontua Membro Destaque geral

Atividade exclusiva de equipe:
- pontua equipe
- pode pontuar jovem individual, se fizer sentido
- não pontua membro geral se membros adultos não têm como participar

## Validação de pontos

Pontos importantes devem ter comprovação.

Exemplos:

- presença confirmada
- visitante validado
- Bíblia comprovada por foto
- participação aprovada
- leitura bíblica registrada
- devocional respondido
- serviço confirmado por líder

O sistema deve registrar:

- quem validou
- quando validou
- qual regra gerou ponto
- se houve ajuste manual
- motivo do ajuste
- histórico de alteração

## Verificação de Bíblia por foto

O sistema deve futuramente permitir comprovar que a pessoa levou a Bíblia para a igreja.

Fluxo possível:

1. Pessoa tira foto da Bíblia.
2. Sistema registra a imagem.
3. Validação pode ser automática, manual ou por responsável.
4. Depois da validação, gera pontuação conforme a regra configurada.

Cuidados:

- não expor a imagem publicamente sem necessidade
- evitar fraude
- registrar data, culto e pessoa
- permitir auditoria
- não gerar constrangimento

## Aba de estudos bíblicos

O sistema deve futuramente ter uma área de estudos.

Essa área pode incluir:

- Bíblia integrada
- plano de leitura
- devocional guiado
- estudo por capítulo
- perguntas sobre o capítulo lido
- anotações pessoais
- respostas reflexivas
- histórico de leitura
- progresso bíblico
- pontuação por leitura, se aprovado

## Devocional guiado

O devocional deve poder se adaptar ao capítulo estudado.

Exemplo:

1. Pessoa lê um capítulo.
2. Sistema apresenta perguntas guiadas.
3. Pessoa responde.
4. Sistema gera reflexão/devocional com base no estudo.
5. Pessoa pode salvar anotação.
6. Pessoa pode publicar uma reflexão interna para edificar outros membros.

## Rede social interna edificante

Algumas respostas, reflexões ou testemunhos poderão ser publicados dentro do sistema.

Objetivo:

- edificar membros
- incentivar leitura
- fortalecer comunhão
- permitir testemunhos
- criar ambiente saudável

Recursos possíveis:

- publicar reflexão
- curtir
- comentar
- salvar
- denunciar conteúdo inadequado
- moderação pela secretaria/liderança

Cuidados:

- evitar vaidade espiritual
- evitar exposição sensível
- permitir moderação
- manter ambiente cristão e respeitoso

## Centro Financeiro / Tesouraria

O Centro Financeiro será um dos módulos principais.

Deve ser inspirado conceitualmente em boas experiências administrativas, mas sem copiar visual antigo ou sistemas externos.

Deve ter visão clara de:

- recebido hoje
- pago hoje
- a receber hoje
- a pagar hoje
- vencidos/atrasados
- saldo por contas
- composição do saldo
- resumo mensal
- resumo anual
- receitas
- despesas
- contas a receber
- contas a pagar
- categorias
- centros de custo
- contatos
- relatórios
- histórico
- orçamentos
- pesquisa de transações
- atalhos rápidos

Tudo adaptado ao visual oficial do Resgate 2.0.

## Repasse e Caixa para Depósito

Repasse e Caixa para Depósito são fluxos diferentes.

### Repasse

Repasse acontece quando um setor, como Cantina, entrega dinheiro físico à Tesouraria.

A Tesouraria deve:

- receber
- conferir
- confirmar
- registrar
- auditar

Só depois o valor entra como dinheiro físico sob responsabilidade da Tesouraria.

### Caixa para Depósito

Caixa para Depósito controla o dinheiro físico em mãos desde a semana ou desde o último depósito bancário.

Deve permitir:

- ver total físico em mãos
- selecionar valores para depósito integral ou parcial
- reencaminhar parte para pagamentos ou departamentos antes do depósito
- registrar diferença entre arrecadado e depositado
- exigir comprovante de depósito bancário
- rastrear quem ficou responsável pelo dinheiro
- auditar toda movimentação

## Cantina futura

A Cantina deve ser integrada ao ecossistema.

Pode envolver:

- vendas
- produtos
- estoque
- consumo por pessoa/família
- dívidas
- créditos
- repasse para Tesouraria
- menores de 11 vinculados aos responsáveis
- relatórios
- auditoria

Para menores de 11 anos, compras/dívidas devem ir para os pais ou responsáveis.

## Worship Central / Louvor

O sistema deve futuramente ter um módulo de louvor robusto.

Possibilidades:

- repertório
- músicas
- cifras
- tom original
- transposição
- capo
- escalas
- ministros
- músicos
- vocal
- histórico de ministrações
- referências
- arquivos
- ensaios
- comunicação com equipe
- organização de culto

Esse módulo deve ser bonito, musical, prático e sem poluição.

## Departamentos e Ministérios

Departamentos não criam membros automaticamente.
Participante de equipe não é necessariamente membro batizado.

Departamentos raiz/oficiais são protegidos.

Departamentos personalizados podem ser criados e excluídos com segurança, desde que não tenham pessoas ativas vinculadas.

Líder de departamento não recebe permissão administrativa geral automaticamente.

## Calendário inteligente

O sistema deve futuramente ter calendário inteligente.

Ele poderá integrar:

- cultos
- eventos
- ensaios
- escalas
- reuniões
- visitas
- congressos
- tarefas
- alertas
- lembretes
- disponibilidade
- responsabilidades

O calendário deve conversar com módulos como Secretaria, Departamentos, Obreiros, Louvor, Pastoral e Jovens.

## Editor de documentos estilo Word

O sistema deve futuramente ter uma área para criar e editar documentos/textos.

A experiência deve ser completa e confortável, semelhante a um editor de texto moderno.

Objetivo:

- criar atas
- declarações
- certificados
- comunicados
- estudos
- documentos internos
- cartas
- textos pastorais
- materiais de secretaria

O usuário não deve sentir que o documento fica desconfigurado ou perdido.

## Hub Família Resgate

O sistema deve ter uma experiência acolhedora para a Família Resgate.

A ideia de mensagem central:

"Bem-vindo ao centro da Família Resgate"

O Hub deve transmitir:

- acolhimento
- casa
- família
- comunhão
- organização
- pertencimento
- centro da igreja

Sem perder o visual premium e moderno.

## Site público da igreja

O sistema terá futuramente um site público.

Esse site será para:
- visitantes
- pessoas que ainda não têm login
- pessoas que não são membros
- pessoas que querem conhecer a igreja
- novos convertidos
- interessados
- pessoas que desejam pedir oração

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

O site público deve ser bonito, premium, espiritual e acolhedor.

Não deve parecer sistema administrativo.

### Navegação do site público

A navegação pública deve ter páginas separadas e URLs claras.

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

Não colocar tudo na mesma URL.

Ao clicar em um item do menu, a experiência visual pode ter transição lateral, como se a página passasse para o lado.

Mas tecnicamente deve haver rota/URL própria para cada seção.

### Regra de scroll do site público

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

### Caixinha de Palavras / Promessas

Conceito:
Antigamente a pessoa tirava um papelzinho com uma palavra bíblica, versículo, mensagem de fé, esperança ou encorajamento.

No Resgate 2.0, isso deve virar uma experiência digital bonita.

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

A experiência deve ser:
- bonita
- espiritual
- simples
- emocionante
- sem parecer brincadeira vazia
- sem parecer sorte espiritual irresponsável

### Galeria / Vitrine da igreja

O site público deve ter uma vitrine da igreja.

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

Não deve virar álbum infinito.

A página pública deve mostrar apenas:
- fotos mais bonitas
- fotos mais marcantes
- destaques selecionados

A galeria completa pode ser módulo futuro separado.

## Central de Oração

A igreja precisa ser uma igreja de oração.

Por isso, o site público deve ter uma entrada clara para a Central de Oração.

Botões principais:

1. Pedir oração
2. Acompanhar minha oração

Esses botões devem aparecer de forma bonita, espiritual e acolhedora.

Não devem parecer botões administrativos.

### Página pública de pedido de oração

A página de pedido de oração deve ser linda, espiritual, premium e acolhedora.

Visual desejado:
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

A página deve permitir:

1. Nome, opcional
2. WhatsApp, opcional
3. Pedido anônimo
4. Caixa de texto grande para escrever o pedido de oração
5. Texto longo
6. Possibilidade de escrever bastante
7. Botão de enviar pedido

Regra:
Nome e WhatsApp não são obrigatórios.

A pessoa pode pedir oração em anonimato.

A caixa de texto do pedido deve aceitar texto longo.

### Código do pedido de oração

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

### Acompanhar pedido de oração

A pessoa poderá acompanhar de duas formas:

1. Apenas com o código do pedido
2. Com o código do pedido e criação de uma conta simples para acompanhar melhor

Fluxo 1:
A pessoa informa o código e acessa a conversa/acompanhamento daquele pedido.

Fluxo 2:
A pessoa informa o código e pode criar uma conta simples para acompanhar a oração, receber mensagens e futuramente se aproximar da igreja.

### Conversa de acompanhamento tipo WhatsApp

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

### Botão "Quero conhecer a igreja"

Dentro da conversa de acompanhamento, pode existir futuramente um botão como:

"Quero conhecer melhor a igreja"
"Quero fazer parte da Família Resgate"
"Quero ser membro desta igreja"

Esse botão deve encaminhar a pessoa para um fluxo de cadastro/solicitação.

Não deve transformar a pessoa em membro automaticamente.

Deve criar uma solicitação ou cadastro pendente para a Secretaria avaliar.

### Pedidos de oração de membros logados

Membros, congregados ou usuários logados também podem pedir oração.

Eles podem pedir oração:
- identificados
- ou anonimamente

Mesmo sendo da igreja, o anonimato pode ser importante.

Motivo:
Algumas pessoas deixam de pedir ajuda por vergonha, medo ou exposição.

O sistema deve permitir cuidado espiritual com privacidade.

Se o membro pedir anonimato:
- o intercessor pode ver o conteúdo do pedido
- mas não deve ver a identidade, salvo regra pastoral/segurança muito específica
- deve haver proteção contra exposição indevida

Esse ponto precisa ser planejado com muito cuidado técnico e pastoral.

### Intercessores autorizados

Intercessores são pessoas autorizadas.

Podem ser:
- membros
- obreiros
- líderes
- pessoas maduras na fé
- pessoas convidadas pela Secretaria
- pessoas autorizadas pelo Pastor

A pessoa não vira intercessor automaticamente.

Ela precisa ser autorizada pela Secretaria ou pelo Pastor.

No perfil/acesso da pessoa, deve aparecer futuramente o sistema:

Central de Oração

Aparece somente para quem tiver permissão.

### Distribuição justa dos pedidos

Os pedidos de oração devem chegar aos intercessores de forma justa e equilibrada.

Regra desejada:
Não pode chegar 10 pedidos para um intercessor e 3 para outro sem motivo.

A distribuição deve ser parecida com rodízio.

Exemplo:
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

### Classificação do pedido pelo intercessor

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

### Anotações privadas do intercessor

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

### Encaminhar ao Pastor

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

### Relação entre Central de Oração e Centro Pastoral

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

### Relação entre Central de Oração e Secretaria

Secretaria não deve ver detalhes sensíveis de oração por padrão.

Mas pode administrar:
- quem tem permissão de intercessor
- cadastros
- solicitações administrativas
- vínculo de usuário, quando necessário
- configurações autorizadas

Conteúdo espiritual sensível deve ter proteção própria.

### Segurança e privacidade da Central de Oração

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

### Status futuros do pedido de oração

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

### Permissões futuras da Central de Oração

Possíveis permissões futuras:

intercession.access
intercession.requests.view
intercession.requests.respond
intercession.requests.classify
intercession.requests.forward_to_pastor
intercession.notes.create
intercession.notes.view
intercession.notes.manage
intercession.assignments.view
intercession.assignments.manage
intercession.public_requests.receive
intercession.anonymous_requests.handle

Pastor pode ter permissões para ver encaminhamentos pastorais:

pastoral.intercession_referrals.view
pastoral.intercession_referrals.manage

Secretaria/Administração podem ter permissões administrativas, sem acesso amplo ao conteúdo sensível por padrão:

intercession.settings.manage
intercession.intercessors.manage

## Login

O site público deve ter acesso ao login.

Login básico:
- e-mail
- senha

Depois de autenticar, a pessoa entra no Centro Família Resgate.

O login é a porta de entrada para o ecossistema interno.

## Centro Família Resgate

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

### Visão geral do Centro Família Resgate

A visão geral deve ter resumo compacto.

Não deve ter scroll longo.
Não deve ter página gigante.
Não deve ter cards demais empilhados.

A tela inicial deve mostrar os principais resumos:

1. Saudação personalizada
Exemplo:
"Bem-vindo, Daniel"

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

### Menu superior do usuário

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

### Sistemas internos autorizados

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

Exemplo:
Se Daniel for Administrador Geral e Secretário, ele pode ver todos os sistemas autorizados para esses perfis.

Um membro comum só vê as áreas permitidas para ele.

Um intercessor vê a Central de Oração.

Um obreiro vê o sistema de obreiros quando estiver autorizado.

Um pastor vê o Centro Pastoral.

Um líder de departamento vê o que tiver permissão.

### PIN de 4 dígitos para áreas sensíveis

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

### Permissões e ecossistema

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

### Regra visual do Centro Família Resgate

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

### Relação com a Secretaria

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

### Relação com o Administrador

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

### Relação com o Pastor

Pastor não é administrador técnico.

Pastor poderá ter sistema próprio futuramente:
- Centro Pastoral

O Centro Pastoral aparece para quem tiver permissão pastoral.

Ele não deve aparecer para todos.

### Relação com intercessores

Intercessor não é automaticamente Pastor.
Intercessor não é automaticamente Administrador.
Intercessor não é automaticamente Secretaria.

Intercessor é uma função autorizada.

Quem tiver permissão verá a Central de Oração no Centro Família Resgate.

### Relação com membros sem acesso

Nem toda pessoa cadastrada tem usuário.

Quem não tem login acessa apenas o site público.

Quem tem login acessa o Centro Família Resgate.

Quem é membro batizado pode ter recursos específicos, mas membro e usuário continuam conceitos separados.

## Futuro app / mobile

O projeto deve ser preparado para futuro app iOS/Android.

Mas não iniciar app agora.

Estratégia:

- consolidar base web/API
- preparar API com Sanctum futuramente
- considerar PWA quando fizer sentido
- só depois decidir Capacitor, app nativo ou outra abordagem

Não instalar Capacitor agora.
Não iniciar React Native agora.
Não criar app mobile agora.

## Segurança, logs e auditoria

Todo módulo importante deve considerar:

- Policies
- permissões
- Events
- Listeners
- Jobs quando necessário
- ActivityLog
- SystemAlert quando necessário
- rastreabilidade
- privacidade
- histórico de alterações
- aprovação da Secretaria quando aplicável

Nada sensível deve acontecer sem registro.

## O que torna o Resgate 2.0 diferente

O Resgate 2.0 não deve ser apenas um cadastro de membros.

Ele deve ser um ecossistema eclesiástico.

Diferenciais:

- gestão de pessoas
- famílias
- responsáveis
- acessos
- permissões
- departamentos
- secretaria
- pastoral futura
- obreiros escalados
- visitantes inteligentes
- estudos bíblicos
- devocional guiado
- pontuação justa
- membro destaque
- jovem destaque
- equipes dos jovens
- rede interna edificante
- louvor inteligente
- financeiro/tesouraria completo
- cantina integrada
- calendário inteligente
- editor de documentos
- logs e auditoria
- visual premium
- experiência espiritual e organizada
- futuro app/mobile preparado

## O que não implementar agora

Este documento é visão de produto.

Não implementar agora:

- pontuação
- obreiros escalados
- visitantes
- estudos bíblicos
- rede social interna
- devocional guiado
- Bíblia integrada
- foto da Bíblia
- painel pastoral
- membro destaque
- gamificação jovem
- Centro Financeiro completo
- Cantina
- Worship Central
- calendário inteligente
- editor estilo Word
- app mobile

Esses módulos devem ser planejados em etapas futuras.
