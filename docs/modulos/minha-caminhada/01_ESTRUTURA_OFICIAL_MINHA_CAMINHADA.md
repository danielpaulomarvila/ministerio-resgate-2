# Estrutura Oficial da Minha Caminhada

## 1. Objetivo do módulo

A Minha Caminhada é o módulo de acompanhamento saudável da jornada cristã dentro da Família Resgate. Seu objetivo é apoiar crescimento, constância e cuidado pastoral sem transformar fé em competição.

O módulo deve:

- **Acompanhar crescimento saudável:** registrar passos de presença, Palavra, serviço, comunhão, evangelismo, formação e conquistas.
- **Incentivar constância:** valorizar pequenos passos repetidos com fidelidade.
- **Reconhecer serviço:** destacar envolvimento prático na vida da igreja, ministérios e ações de cuidado.
- **Reconhecer Palavra:** incentivar leitura bíblica, estudos, devocionais e formação cristã.
- **Reconhecer presença:** acompanhar participação em cultos, encontros, classes e atividades registradas.
- **Reconhecer comunhão:** valorizar integração com a família da fé e participação comunitária.
- **Reconhecer evangelismo:** registrar cuidado com visitantes, convites, missões e alcance saudável.
- **Reconhecer formação:** acompanhar trilhas, discipulado, cursos e amadurecimento.

O módulo nunca deve:

- **Medir espiritualidade.**
- **Definir valor diante de Deus.**
- **Comparar pessoas de forma pública ou tóxica.**
- **Expor dados sensíveis.**
- **Misturar trilhos diferentes de pontuação.**
- **Criar competição espiritual.**

## 2. Trilhos oficiais

### Caminhada Geral da Igreja

Trilho principal para membros em geral. Deve contemplar presença, Palavra, serviço, comunhão, evangelismo, formação, conquistas gerais e categorias futuras aprovadas pela liderança.

### Caminhada Jovem dos Resgatados

Trilho específico para jovens/resgatados. Pode incluir presença nos Resgatados, desafios bíblicos, Bíblia na mão, serviço jovem, comunhão jovem, evangelismo jovem, missões e conquistas jovens.

### Equipes dos Resgatados

Trilho coletivo dentro do módulo jovem. Deve registrar pontuação e reconhecimentos de equipes jovens, sem transformar pontuação coletiva em pontuação individual.

### Intercessão como categoria geral futura

Intercessão deve ser tratada como categoria geral futura, com muito cuidado pastoral. Pode ter pontuação fixa mensal e pontuação por atendimento, mas avaliações devem ser privadas, limitadas e auditáveis.

Intercessão não deve expor pessoas atendidas, pedidos sensíveis, aconselhamentos, crises, detalhes pastorais ou qualquer informação confidencial.

### Administração de regras de pontuação

As regras de pontuação devem ser administráveis no futuro, com origem oficial na Administração/Secretaria/Liderança autorizada. Cada regra deve definir categoria, trilho, pontos, limites, validação, visibilidade e se conta para destaques.

## 3. Separação obrigatória

A separação entre trilhos é regra estrutural do módulo.

- **Pontos gerais não se misturam com pontos jovens.**
- **Pontos jovens não se misturam com pontos de equipe.**
- **Pontos de equipe jovem não viram pontuação individual automaticamente.**
- **Membro comum não vê conteúdo jovem/equipe.**
- **Jovem pode ter Caminhada Geral + Caminhada Jovem.**
- **Equipe jovem pertence ao módulo jovem.**
- **Responsável vê dados jovens somente dos filhos vinculados.**
- **Liderança/Admin vê conforme policy e necessidade de cuidado, validação ou auditoria.**

A interface deve respeitar essas separações desde o visual, mesmo quando ainda usa mock.

## 4. Páginas existentes

### `/familia-resgate/minha-caminhada`

- **Objetivo:** página principal do módulo, apresentando visão resumida da caminhada, jornadas disponíveis, progresso, mapa, mentor, destaques e próximos passos.
- **Público:** membros, jovens/resgatados e futuramente responsáveis/liderança conforme permissões.
- **Visualmente pronta:** sim, como protótipo visual avançado.
- **Ainda usa mock:** sim.
- **Pendências:** substituir pontos, níveis, atividades, mentor, destaques e conquistas por dados reais; manter auditoria de payload quando backend real existir.
- **Riscos:** se o backend futuro não filtrar por policy, poderá expor jornada jovem para membro comum; manter mocks parecendo dados reais.

### `/familia-resgate/minha-caminhada/nivel`

- **Objetivo:** mostrar o nível atual da caminhada e próximos passos do membro.
- **Público:** membro autenticado, jovem/resgatado e perfis autorizados.
- **Visualmente pronta:** sim, como protótipo visual.
- **Ainda usa mock:** sim.
- **Pendências:** conectar nível atual real, progresso, pontos e próximos marcos.
- **Riscos:** mostrar nível incorreto se não houver policy/dados reais; confundir trilho geral com jovem.

### `/familia-resgate/minha-caminhada/geral`

- **Objetivo:** área detalhada da Caminhada Geral da Igreja.
- **Público:** membro comum, jovem/resgatado, responsável e liderança conforme policy.
- **Visualmente pronta:** sim, como protótipo visual.
- **Ainda usa mock:** sim.
- **Pendências:** conectar progresso geral real, registros oficiais e permissões.
- **Riscos:** dados gerais ficarem duplicados em relação à página principal ou histórico.

### `/familia-resgate/minha-caminhada/jovem`

- **Objetivo:** área detalhada da Caminhada Jovem dos Resgatados.
- **Público:** jovens/resgatados, líderes jovens, responsáveis autorizados, secretaria/admin e pastor/liderança conforme policy.
- **Visualmente pronta:** sim, como protótipo visual.
- **Ainda usa mock:** sim.
- **Pendências:** proteger por perfil; conectar youth membership, atividades jovens, desafios e conquistas jovens.
- **Riscos:** membro comum acessar conteúdo jovem; responsável ver jovens não vinculados; expor dados de menores.

### `/familia-resgate/minha-caminhada/mapa`

- **Objetivo:** exibir mapa completo dos marcos/níveis da caminhada.
- **Público:** membro autenticado e perfis autorizados conforme trilho.
- **Visualmente pronta:** sim, como protótipo visual.
- **Ainda usa mock:** sim.
- **Pendências:** controlar mapa geral/jovem por permissão e dados reais.
- **Riscos:** rotas antigas e novas confundirem trilhos; mapa jovem aparecer para quem não deve.

### `/familia-resgate/minha-caminhada/historico`

- **Objetivo:** listar registros da caminhada, pontos, conquistas e eventos relevantes.
- **Público:** membro para seus próprios dados; jovem para seus dados; responsável para filhos vinculados; liderança/admin conforme policy.
- **Visualmente pronta:** sim, como protótipo visual.
- **Ainda usa mock:** sim.
- **Pendências:** conectar point logs, registros oficiais, validações e filtros por perfil.
- **Riscos:** histórico revelar dados de terceiros, jovens ou dados sensíveis sem autorização.

### `/familia-resgate/minha-caminhada/mentor`

- **Objetivo:** fornecer leitura cuidadosa da caminhada e plano sugerido por regras pastorais.
- **Público:** membro, jovem/resgatado, responsável e liderança conforme permissões.
- **Visualmente pronta:** sim, como protótipo visual.
- **Ainda usa mock:** sim.
- **Pendências:** conectar regras pastorais reais, histórico de respostas e variações por responsável/liderança conforme policy.
- **Riscos:** se a policy futura falhar, pode expor trilho jovem a membro comum; dar tom de IA absoluta; substituir cuidado pastoral humano.

### `/familia-resgate/minha-caminhada/regras`

- **Objetivo:** explicar como pontuação, níveis, conquistas, destaques e cuidado funcionam.
- **Público:** membro comum, jovem/resgatado, responsável, liderança/admin conforme policy.
- **Visualmente pronta:** sim, como protótipo visual.
- **Ainda usa mock:** sim.
- **Pendências:** conectar regras reais administráveis; manter membro comum com regras gerais e perfis autorizados com trilhos adicionais conforme policy.
- **Riscos:** se a policy futura falhar, pode expor regras jovens/equipes para membro comum; deixar regras divergentes da Administração futura.

### `/familia-resgate/minha-caminhada/ranking`

- **Objetivo:** exibir Destaques da Caminhada com reconhecimento saudável, sem competição espiritual.
- **Público:** membro comum para destaques gerais; jovem/resgatado para geral + jovem; equipe/líder/admin conforme policy.
- **Visualmente pronta:** sim, com ajuste correto para membro comum.
- **Ainda usa mock:** sim.
- **Pendências:** conectar highlights reais, policies e regras oficiais; adaptar dados por perfil real vindo do backend.
- **Riscos:** se futuras permissões forem implementadas apenas no frontend, dados jovens/equipe podem ser expostos indevidamente.

### `/familia-resgate/minha-caminhada/conquistas`

- **Objetivo:** exibir conquistas/badges conquistados e disponíveis conforme trilho e permissão.
- **Público:** membro comum, jovem/resgatado, responsável, liderança/admin conforme policy.
- **Visualmente pronta:** sim, como protótipo visual ajustado para membro comum.
- **Ainda usa mock:** sim.
- **Pendências:** conectar catálogo real, conquistas recebidas e permissões por backend/policies.
- **Riscos:** se o backend futuro não filtrar por policy, conquistas administrativas/financeiras/sensíveis podem ficar visíveis sem regra clara; confundir conquista concedida com conquista disponível.

## 5. Observações vindas dos prints atuais

- **`/ranking` ficou correto para membro comum:** a página mostra destaques gerais e oculta conteúdo jovem/equipe por contexto de visibilidade.
- **`/regras` foi ajustada por perfil:** membro comum vê regras gerais e apenas nota discreta sobre trilhos autorizados.
- **`/mentor` foi ajustado por perfil:** membro comum recebe mentor geral, apoio pastoral e orientação por regras sem IA externa real.
- **`/conquistas` foi reestruturada:** membro comum vê conquistas gerais pessoais, com itens restritos condicionados/ocultos.
- **Página principal foi ajustada por perfil:** jornada jovem, mapa jovem, atividades jovens e próximos passos jovens ficam condicionados por `viewerContext.canSeeYouthJourney`.

## 6. Regra de manutenção

Antes de qualquer nova etapa visual ou funcional na Minha Caminhada, este documento deve ser consultado para evitar remendos, duplicidade, mocks soltos, regras espalhadas e permissões inconsistentes.
