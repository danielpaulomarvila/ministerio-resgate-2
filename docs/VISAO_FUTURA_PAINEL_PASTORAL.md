# Visão Futura: Painel Pastoral

## Status

Este documento registra uma visão futura.
Nenhuma migration, tela, tabela ou controller foi implementado nesta etapa.
O conteúdo vem de pesquisa conceitual no repositório antigo/pesquisa e das regras atuais do projeto Resgate 2.0.

## Pesquisa no repositório antigo

Repositório pesquisado:

https://github.com/danielpaulomarvila/rederesgate-cancelado-pesquisa

A pesquisa foi feita somente como referência conceitual.
Nenhum código antigo foi copiado para o projeto atual.

Foram encontrados conceitos antigos sobre:

- Centro Pastoral
- Perfil Pastor
- Pedidos de oração
- Solicitações de conversa/aconselhamento
- Visitantes
- Lista de apresentação de visitantes
- Presença/chamada
- Pontuação
- Membro Destaque
- Perfis e permissões

## Regra principal

Pastor não é administrador técnico do sistema.

O pastor pode visualizar, acompanhar, verificar, encaminhar e cuidar de fluxos pastorais.
Mas o perfil Pastor não deve receber automaticamente permissões técnicas, administrativas ou estruturais do sistema.

## Separação de papéis

### Super Administrador

Perfil técnico com acesso total ao sistema.
Deve ser usado com cuidado por poucas pessoas.

Pode gerenciar:
- configurações críticas
- perfis
- permissões
- estrutura global
- recursos administrativos sensíveis

### Administrador do Sistema

Responsável pela administração geral e operacional do sistema.
Pode ser o secretário/administrador da igreja, se a igreja decidir.

Não deve ser confundido automaticamente com Pastor.

### Secretaria

Área privada responsável pela gestão administrativa da igreja.

Pode gerenciar:
- pessoas
- famílias
- responsáveis
- acessos
- solicitações
- alertas
- departamentos
- documentos internos
- aprovações

A Secretaria deve existir como conceito próprio, mesmo que a mesma pessoa também tenha perfil de administrador.

### Pastor

Deve ter visão pastoral, não controle técnico total.

Futuramente poderá:
- visualizar pedidos de oração
- acompanhar solicitações de conversa
- acompanhar visitantes
- receber lista de visitantes do culto
- encaminhar visitantes para dirigente do culto
- acompanhar presença/chamada
- encaminhar casos para obreiros, líderes, intercessão ou secretaria
- acompanhar follow-ups pastorais

### Obreiro

Pode atuar em fluxos operacionais do culto.

Futuramente poderá:
- registrar visitantes
- confirmar presença
- atuar em escala
- receber encaminhamentos pastorais
- apoiar recepção e acompanhamento

### Líder de Departamento

Pode gerenciar informações básicas do seu departamento.
Não recebe automaticamente permissão administrativa geral.

### Membro / Participante

Pode ter área própria no futuro.
Pode visualizar seus dados e solicitar alterações.
Não pode alterar dados definitivos sem aprovação da Secretaria.

## Fluxos futuros do Painel Pastoral

### 1. Pedidos de oração

O sistema antigo tinha conceitos como:
- pedidos de oração
- solicitação de acompanhamento pastoral
- casos de oração pastoral
- notas pastorais

No Resgate 2.0, isso deve ser um módulo futuro, com privacidade, permissões e logs.

Possíveis status:
- recebido
- em oração
- em acompanhamento
- encaminhado
- encerrado

### 2. Solicitação de conversa / aconselhamento

O sistema antigo indicava fluxos de follow-up pessoal e solicitação pastoral.

No futuro:
- uma pessoa poderá solicitar conversa
- o pastor poderá visualizar
- o pastor poderá encaminhar para outro responsável autorizado
- a Secretaria poderá acompanhar apenas o que for permitido
- detalhes sensíveis não devem ficar visíveis para qualquer usuário

### 3. Visitantes do culto

O sistema antigo tinha ideias de:
- cadastro de visitantes
- origem do visitante
- visitante indicado por membro
- visitante que deseja acompanhamento
- funil pastoral de visitantes
- visitante convertido em membro

No Resgate 2.0, visitantes devem ser módulo futuro próprio.

Possíveis origens:
- convite
- redes sociais
- passou em frente
- evento especial
- indicação de membro
- outros

### 4. Lista de apresentação de visitantes

O sistema antigo tinha o conceito de lista de apresentação pastoral.

No futuro:
- obreiro ou recepção registra visitantes
- pastor recebe a lista
- pastor pode encaminhar para o dirigente do culto
- dirigente pode apresentar visitantes no culto com organização

### 5. Presença / chamada do culto

O pastor hoje pode anotar presença no papel.
No futuro, o sistema deve permitir registrar presença por culto/evento.

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

### 6. Pontuação e Membro Destaque

O sistema antigo tinha conceitos de:
- sistema unificado de pontos
- pontos por visitante indicado
- pontos por Bíblia levada ao culto
- ajustes manuais com justificativa
- logs de alteração de pontos
- membro destaque do mês

No Resgate 2.0, isso NÃO deve ser implementado agora.

Será módulo futuro separado.

A pontuação poderá considerar:
- presença em culto
- levar Bíblia
- visitante confirmado
- evangelismo
- serviço
- leitura bíblica/devocional, se aprovado depois
- ajustes manuais com justificativa e auditoria

Cuidado:
A pontuação não deve gerar competição tóxica, humilhação ou exposição negativa.

### 7. Encaminhamentos pastorais

O pastor poderá futuramente encaminhar situações para:
- obreiro escalado
- líder de departamento
- intercessão
- secretaria
- dirigente do culto
- responsável familiar

Todo encaminhamento deve ter:
- responsável
- status
- data
- histórico
- ActivityLog
- privacidade

## Permissões futuras possíveis

O perfil Pastor poderá futuramente receber permissões como:

- pastoral.access
- pastoral.prayer_requests.view
- pastoral.prayer_requests.manage
- pastoral.conversation_requests.view
- pastoral.conversation_requests.manage
- pastoral.visitors.view
- pastoral.visitors.forward
- pastoral.attendance.view
- pastoral.attendance.record
- pastoral.followups.view
- pastoral.followups.forward

Mas o perfil Pastor NÃO deve receber automaticamente:

- permissions.manage
- access_profiles.manage
- system.settings.manage
- technical.admin
- database.manage
- users.manage_all

Se uma pessoa for pastor e também administrador, ela deve receber os dois perfis separadamente.

## Segurança e privacidade

Todos os fluxos pastorais futuros devem ter:

- permissões específicas
- Policies
- Events
- Listeners
- Jobs quando necessário
- ActivityLog
- SystemAlert quando necessário
- controle de visibilidade
- proteção de dados sensíveis

## O que NÃO implementar agora

Nesta fase, não implementar:

- painel pastoral
- pedidos de oração
- visitantes
- presença/chamada
- pontuação
- membro destaque
- gamificação
- migrations novas
- telas novas
- controllers novos
- permissões novas

Este documento é apenas visão futura.
