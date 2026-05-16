# Histórico Diário do Projeto Ministério Resgate

## Dia 1 — 14/05/2026

- **Horário de início aproximado:** 01:10
- **Horário de finalização:** 02:34
- **Tempo aproximado de trabalho:** 1h25
- **Status do dia:** concluído sem commit automático

### Resumo do dia

Hoje foi finalizada a evolução visual e funcional da área da Família Resgate, com foco principal nas telas `/familia-resgate` e `/familia-resgate/meu-perfil`. O trabalho concentrou-se em harmonização visual, acabamento premium espiritual, organização dos blocos, legibilidade, responsividade, validação de rotas e fechamento técnico do dia.

### Telas e módulos trabalhados

- **Central da Família Resgate:** `/familia-resgate`
- **Meu Perfil:** `/familia-resgate/meu-perfil`
- **Sidebar compartilhada da Família Resgate:** `FamilySidebar.vue`
- **Rotas de suporte e placeholders oficiais:** rotas internas da Família Resgate e acessos privados
- **Assets visuais:** imagem central e ilustrações SVG do perfil/contatos

### Principais entregas

- **Meu Perfil concluído:** página revisada com blocos de identidade, caminhada, cartão Resgate, dados pessoais, contatos de emergência, estatísticas, atalhos e links importantes.
- **Harmonia visual refinada:** removidas restrições rígidas de altura, ampliados espaços internos e melhorada a leitura de textos pequenos.
- **Detalhes espirituais premium:** aplicados halos dourados, selos sutis, brilhos leves, cartões com textura visual e elementos espirituais discretos.
- **Acessos Privados no local correto:** mantidos apenas na página `/familia-resgate`, sem aparecer em `/familia-resgate/meu-perfil`.
- **Sidebar oficial consolidada:** usada como componente compartilhado nas páginas da Central da Família.
- **Rotas protegidas e placeholders:** rotas oficiais de perfil, caminhada, cartão, documentos, contatos e acessos privados validadas por `route:list`.

### Arquivos alterados ou adicionados

- **Alterado:** `resources/js/Pages/FamiliaResgate/Index.vue`
- **Adicionado:** `resources/js/Pages/FamiliaResgate/MeuPerfil.vue`
- **Adicionado:** `resources/js/Components/FamiliaResgate/FamilySidebar.vue`
- **Alterado:** `routes/web.php`
- **Alterado:** `public/REDE RESGATE/CENTRO DA FAMILIA/1:10- Centro Família.png`
- **Adicionado:** `public/images/familia-resgate/profile/daniel-paulo-marvila.svg`
- **Adicionado:** `public/images/familia-resgate/emergency-contacts/ana-paula-marvila.svg`
- **Adicionado:** `public/images/familia-resgate/emergency-contacts/carlos-marvila.svg`
- **Adicionado:** `public/images/familia-resgate/emergency-contacts/maria-marvila.svg`
- **Adicionado:** `docs/HISTORICO_DIARIO_PROJETO_MINISTERIO_RESGATE.md`

### Auditoria e limpeza controlada

- **Arquivos temporários:** não foram encontrados arquivos com nomes como `Novo`, `Final`, `Corrigido`, `Versao2`, `Teste`, `Temp`, `Copy` ou `Old` nas áreas auditadas.
- **Duplicidade visual:** não foi encontrada página duplicada do Meu Perfil nem sidebar alternativa concorrente.
- **Links inválidos:** não foram encontrados `href="#"` nem `javascript:void(0)` nas páginas auditadas.
- **Acessos privados:** confirmados somente em `/familia-resgate`.
- **Meu Perfil:** confirmado sem bloco de Acessos Privados e sem termos antigos auditados como preferências/bronze/prata/ouro/conquistas indevidas.
- **CSS suspeito:** ocorrências de `display:none` encontradas apenas em regras responsivas legítimas para mobile/sidebar e detalhes visuais.
- **Remoções:** nenhuma remoção foi feita, pois não houve lixo, duplicidade ou orphan comprovado com segurança.

### Rotas validadas

- **Principal:** `GET /familia-resgate`
- **Meu Perfil:** `GET /familia-resgate/meu-perfil`
- **Perfil e ações:** editar, alterar foto, alterar senha, notificações, privacidade, baixar dados, histórico, documentos, preferências, sobre mim e contatos de emergência.
- **Cartão Resgate:** visualização, download, PDF, PNG, QR Code e validação por token.
- **Minha Caminhada:** rota principal, nível, pontuação, presenças, conquistas, ranking e regras de pontos.
- **Acessos privados:** secretaria, centro pastoral, financeiro, intercessão, cantina e administração geral.

### Validações executadas

- **Build frontend:** `npm run build` executado com sucesso.
- **Testes Laravel:** `php artisan test` executado com sucesso, com 45 testes passando e 126 assertions.
- **Rotas Família Resgate:** `php artisan route:list --path=familia-resgate --except-vendor` validado; listagem confirmou 86 rotas.
- **Rotas de acesso privado:** `php artisan route:list --path=acesso --except-vendor` validado.
- **Whitespace/diff:** `git diff --check` executado sem erros.
- **Scripts disponíveis:** `package.json` possui apenas `build` e `dev`; não há script de lint configurado.

### Decisões importantes

- **Sem commit:** nenhum commit foi feito automaticamente.
- **Sem remoção arriscada:** arquivos oficiais, imagens usadas, rotas, placeholders e documentação existente foram preservados.
- **Mocks mantidos:** dados temporários visuais foram mantidos por ainda sustentarem a tela até integração real com banco/API.
- **Histórico criado:** este arquivo foi criado porque ainda não existia em `docs`.

### Pendências para próximo dia

- **Integração real:** substituir gradualmente mocks de perfil, caminhada, estatísticas, contatos e cartão por dados reais do backend.
- **Permissões reais:** conectar Acessos Privados com permissões reais do usuário, middleware, policies e logs.
- **Testes específicos:** criar testes direcionados para `/familia-resgate/meu-perfil` e rotas do Cartão Resgate.
- **Revisão visual em navegador:** fazer uma última inspeção manual em desktop e mobile após autenticação real.

### Próximo passo recomendado

- **Prioridade:** iniciar a integração do Meu Perfil com dados reais do usuário autenticado, mantendo o layout aprovado e substituindo mocks de forma incremental e segura.

### Nova etapa iniciada

- **Horário de início:** 10:17
- **Tela iniciada:** `/familia-resgate/meu-financeiro`
- **Nome interno:** Minha Vida Financeira
- **Objetivo:** criar a visão financeira pessoal e familiar do usuário logado, incluindo contribuições, pendências, cantina, recibos, eventos, correções e financeiro dos filhos menores de 18 anos.
- **Rota principal:** `/familia-resgate/meu-financeiro`
- **Regra visual:** reutilizar a sidebar/layout oficial da Central da Família, mudando apenas o conteúdo principal.
- **Observação técnica:** a tela será visual/mockada primeiro, com dados temporários organizados e preparada para integração futura com dados reais via backend/Inertia.
- **Status:** em desenvolvimento.

### Complemento da etapa Meu Financeiro

- **Horário:** 11:48
- **Arquivo criado:** `resources/js/Pages/FamiliaResgate/MeuFinanceiroArea.vue`
- **Objetivo:** preparar os fluxos internos que saem de `/familia-resgate/meu-financeiro` com páginas estruturadas, mantendo visual premium, sidebar oficial, breadcrumb, botão de retorno, mocks organizados e ações futuras claras.
- **Rotas internas preparadas:** histórico, recibos, comprovantes, enviar comprovante, solicitar correção, solicitações, cantina, eventos, filhos, detalhe do filho, pendências, créditos e fluxo visual de download de recibo.
- **Ajuste de rotas:** rotas internas do Meu Financeiro passaram a renderizar `FamiliaResgate/MeuFinanceiroArea` com props de área, pessoa ou recibo, deixando de usar placeholder genérico cru.
- **Status:** em desenvolvimento visual validado.
- **Pendência futura:** substituir mocks por backend real com validação de permissões, vínculos familiares, upload seguro, download autorizado e registros em `activity_logs`.

### Complemento — legibilidade e base funcional do Meu Financeiro

- **Horário:** 12:21–12:55
- **Objetivo:** corrigir contraste dos cards azuis/escuros, refinar a leitura das páginas internas e preparar a base real do módulo Meu Financeiro sem refazer telas aprovadas.
- **Legibilidade:** adicionadas regras específicas para `finance-blue-card`, mantendo títulos em branco forte, textos secundários em branco translúcido legível e valores em dourado acessível.
- **Cards corrigidos:** card “Seguro / Integração futura”, cards azuis de filhos, crédito disponível e faixa escura de crédito na tela principal.
- **Base real criada:** migrations mínimas para `financial_transactions`, `payment_proofs`, `receipts`, `financial_correction_requests` e `credits`.
- **Models criados:** `FinancialTransaction`, `PaymentProof`, `Receipt`, `FinancialCorrectionRequest` e `Credit`.
- **Segurança preparada:** criado `FinancialAccessService` para limitar visualização financeira ao próprio usuário, filhos menores vinculados por `guardianships` e responsáveis financeiros autorizados.
- **Policies preparadas:** `FinancialTransactionPolicy`, `PaymentProofPolicy`, `ReceiptPolicy` e `FinancialCorrectionRequestPolicy`.
- **Controller criado:** `Familia\FamilyFinancialController`, mantendo mocks como fallback visual explícito e enviando contexto `financialAccess` por Inertia.
- **Rotas ajustadas:** rotas de `/familia-resgate/meu-financeiro` e internas passaram a usar o controller financeiro.
- **Testes adicionados:** `tests/Feature/FamilyFinancialTest.php`, cobrindo rotas, autenticação, criação dos models e acesso financeiro do responsável ao menor.
- **Validações executadas:** `npm run build`, `php artisan route:list --path=familia-resgate/meu-financeiro --except-vendor --no-ansi`, `php artisan test`, `php artisan migrate:status`, `php artisan migrate`, novo `php artisan test`, `git diff --check` e buscas por links/contraste problemáticos.
- **Resultado:** build concluído, 14 rotas financeiras listadas, 50 testes passando com 144 assertions, migrations financeiras aplicadas no batch 14, `git diff --check` sem erros e sem links mortos no módulo financeiro.
- **Status:** concluído sem commit automático.
- **Pendências futuras:** substituir os mocks visuais por props reais do controller, implementar POST real de comprovantes/correções, download real de recibos com storage protegido e auditoria em `activity_logs`.

### Auditoria final — Meu Financeiro / Minha Vida Financeira

- **Horário:** 13:27–13:49
- **Objetivo:** auditar integralmente o módulo `/familia-resgate/meu-financeiro` antes de concluir a etapa visual/estrutural e passar para a próxima fase.
- **Tela principal verificada:** `resources/js/Pages/FamiliaResgate/MeuFinanceiro.vue`, com sidebar oficial, breadcrumb, título “Minha Vida Financeira”, badge de atualização, botão de correção, resumo financeiro, financeiro da família, pendências, cantina, eventos, recibos/comprovantes, histórico, solicitações/correções e crédito futuro.
- **Páginas internas verificadas:** histórico, recibos, comprovantes, enviar comprovante, solicitar correção, solicitações, cantina, eventos, filhos, detalhe de filho, pendências, créditos e download visual de recibo.
- **Rotas verificadas:** 14 rotas listadas por `php artisan route:list --path=familia-resgate/meu-financeiro --except-vendor --no-ansi`, todas apontando para `FamilyFinancialController` e componentes reais do Meu Financeiro, sem placeholder financeiro genérico.
- **Botões e fluxos:** links auditados sem `href="#"`, `to="#"` ou `javascript:void(0)`; ações navegam para rotas reais ou fluxo futuro claramente explicado.
- **Legibilidade:** cards azuis/escuros mantidos com texto branco legível e dourado acessível; card de correção financeira reforçado com fluxo claro de seleção de lançamento, motivo, pessoa, anexo opcional e descrição.
- **Mocks temporários:** dados permanecem organizados no topo dos componentes e identificados como protótipo visual, prontos para substituição futura por props reais via Inertia.
- **Base funcional auditada:** migrations, models, controller, service, policies e testes financeiros conferidos; estrutura real existe para `financial_transactions`, `payment_proofs`, `receipts`, `financial_correction_requests` e `credits`.
- **Ajustes feitos na auditoria:** removida duplicidade de comentário temporário na tela principal, substituído título genérico do formulário de comprovante e ampliada a cobertura do teste financeiro para todas as rotas do módulo.
- **Validações finais:** `npm run build` concluído, `php artisan test` com 50 testes passando e 162 assertions, `php artisan migrate:status` com migrations financeiras executadas no batch 14, `git diff --check` sem erros e busca por links mortos/placeholders financeiros sem problema real.
- **Status da etapa:** Meu Financeiro concluído como módulo visual/estrutural, com base real mínima preparada, sem commit automático.
- **Pendências da próxima fase:** alimentar telas com dados reais do controller, criar endpoints POST para comprovantes e correções, proteger upload/download via storage/policies, registrar `activity_logs`, integrar notificações e conectar fontes reais de cantina/eventos/inscrições.

### Nova etapa — Minha Caminhada

- **Horário:** 15:17–16:20
- **Objetivo:** iniciar e implementar a versão visual/estrutural oficial de `/familia-resgate/minha-caminhada`, com linguagem espiritual, premium, criativa e preparada para integração futura.
- **Arquivo criado:** `resources/js/Pages/FamiliaResgate/MinhaCaminhada.vue`.
- **Assets SVG criados/usados:** `mapa-caminhada-geral.svg`, `mapa-caminhada-jovem.svg` e `mentor-caminhada.svg` em `public/images/familia-resgate/minha-caminhada/`.
- **Sidebar oficial:** mantida intacta, usando `FamilySidebar` com `active-href="/familia-resgate/minha-caminhada"`.
- **Cenário membro normal:** preparado com uma caminhada geral, nível, pontos, progresso, próximo nível, badges, atividades, mentor IA e mapa geral.
- **Cenário jovem/resgatado:** preparado com duas jornadas separadas: Geral da Igreja e Jovem Resgatado, sem misturar pontos, níveis, posições ou badges.
- **Níveis gerais:** 20 níveis definidos, de Primeiros Passos até Legado Vivo.
- **Níveis jovens:** 20 níveis definidos, de Semente Resgatada até Chama que Permanece.
- **Mentor com IA:** criado como apoio pastoral e sugestivo, sem julgamento espiritual, com mensagem diferente para adulto e jovem.
- **Mapa ilustrado:** criado com imagem panorâmica, marcos de níveis, nível atual destacado, níveis futuros com opacidade e botão para rota futura `/familia-resgate/minha-caminhada/mapa`.
- **Rotas futuras preparadas:** histórico, conquistas, mapa, mentor, ranking, regras, jovem e geral, mantendo placeholder oficial estruturado nas telas ainda não implementadas.
- **Mocks temporários:** organizados no topo do componente com comentário explícito de protótipo visual e fontes futuras de backend.
- **Validações executadas até aqui:** `npm run build` passou após a página premium e `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` listou 14 rotas.
- **Status:** módulo Minha Caminhada implementado como primeira versão visual/estrutural, sem commit automático.
- **Pendências futuras:** substituir mocks por dados reais de pontuação, presenças, leitura bíblica, devocionais, serviço, visitantes, badges e insights de IA autorizados; criar endpoints reais, policies específicas e testes dedicados do módulo.

### Refinamento — Minha Caminhada

- **Horário:** 16:34–16:49
- **Objetivo:** refinar a experiência visual e espiritual de `/familia-resgate/minha-caminhada`, sem refazer a tela, sem alterar sidebar, sem backend novo e sem commit.
- **Mentor com IA:** ampliado com resumo da semana, área forte, próximo passo, sugestão espiritual, plano recomendado e painel “Leitura inteligente”.
- **Leitura inteligente:** adicionados indicadores contextuais para constância, Palavra, serviço, Resgatados, devocional e missões, variando conforme membro adulto ou jovem.
- **Separação Geral/Jovem:** reforçada com texto explicativo, pontos gerais e jovens explícitos, atividades com `pts gerais` ou `pts jovens` e barras individuais no resumo lateral.
- **Mapa da caminhada:** refinado com selo “Você está aqui”, título mais central, botões separados para mapa geral e mapa jovem, brilho dourado no nível atual, cadeado em níveis futuros e integração visual mais forte com o asset SVG.
- **Mentor e imagens:** imagens passaram a ter gradientes, borda dourada suave, brilho e camadas escuras para melhor leitura e integração visual.
- **Badges:** refinados com escudos mais premium, brilho dourado e etiqueta jovem quando aplicável.
- **Atividades recentes:** melhoradas como mini timeline com ícone circular, data, status e diferenciação entre pontos gerais e jovens.
- **Próximos passos:** transformados em recomendações categorizadas com ação, categoria e impacto esperado, reforçando sensação de IA e direção pastoral.
- **Destaques do mês:** recebeu nota de reconhecimento saudável de constância, serviço e crescimento.
- **Validação parcial:** `npm run build` executado e aprovado após os refinamentos visuais.
- **Validações finais:** `npm run build` concluído, `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` listou 14 rotas, `php artisan test` passou com 50 testes e 162 assertions, `git diff --check` passou sem erros e não foram encontrados `href="#"`, `to="#"` ou `javascript:void(0)` na página.
- **Status:** refinamento visual/experiencial concluído sem commit automático.

### Limpeza forçada e controlada — Minha Caminhada

- **Horário:** 18:44–18:55
- **Objetivo:** zerar o módulo visual `/familia-resgate/minha-caminhada` antes da reconstrução oficial do zero.
- **Motivo:** excesso de tentativas anteriores, travamentos em heredoc/quote, risco de lixo escondido, CSS morto, mocks duplicados e estruturas visuais parciais.
- **Arquivo substituído:** `resources/js/Pages/FamiliaResgate/MinhaCaminhada.vue` foi removido e recriado como placeholder mínimo, limpo e válido.
- **Conteúdo preservado:** sidebar oficial `FamilySidebar.vue`, rotas, outros módulos da Família Resgate, models, migrations, controllers, services, policies, autenticação, permissões, banco, storage e uploads.
- **Assets preservados:** `mapa-caminhada-geral.svg`, `mapa-caminhada-jovem.svg` e `mentor-caminhada.svg` permanecem em `public/images/familia-resgate/minha-caminhada/` para possível uso futuro.
- **Rotas verificadas:** a rota principal `/familia-resgate/minha-caminhada` continua renderizando `FamiliaResgate/MinhaCaminhada`; `route:list` mostrou 14 rotas do módulo.
- **Limpeza confirmada:** o arquivo final possui apenas 1 `<script setup>`, 1 `<template>`, 1 `<style scoped>` e 1 `</style>`, sem `isYouth`, `journeyAccess`, listas de níveis, mapa, mentor, badges, cards antigos ou links mortos.
- **Validações executadas:** `npm run build`, `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi`, `php artisan test` e `git diff --check`.
- **Resultado:** build aprovado, 14 rotas listadas, 50 testes passando com 162 assertions e `git diff --check` sem erros.
- **Status:** módulo Minha Caminhada limpo, com placeholder temporário funcionando e sem commit automático.
- **Próximo passo:** reconstruir do zero o cenário Jovem + Membro somente depois da aprovação para iniciar a nova implementação.

### Auditoria e fechamento — Minha Caminhada

- **Horário:** 01:06–01:18.
- **Objetivo:** auditar o estado atual do módulo `/familia-resgate/minha-caminhada` após tentativas visuais, sem implementar feature nova, sem reconstruir tela, sem backend, sem migrations, sem limpeza destrutiva e sem commit.
- **Git status inicial/final da auditoria:** `docs/HISTORICO_DIARIO_PROJETO_MINISTERIO_RESGATE.md` e `routes/web.php` modificados; `resources/js/Pages/FamiliaResgate/MinhaCaminhada.vue`, `resources/js/Pages/FamiliaResgate/MinhaCaminhadaConquistas.vue`, `public/images/familia-resgate/minha-caminhada/mapa-caminhada-cinematico.png` e `public/images/familia-resgate/minha-caminhada/mentor-caminhada.png` não rastreados.
- **Arquivo principal auditado:** `MinhaCaminhada.vue` existe, possui 1 `<script setup>`, 1 `</script>`, 1 `<template>`, 1 `</template>`, 1 `<style scoped>` e 1 `</style>`, com 841 linhas, sem heredoc/quote/restos de merge e sem links mortos encontrados.
- **Estado visual atual:** não é placeholder; é primeira versão visual/estrutural avançada com mocks temporários, jornada geral, jornada jovem, áreas, atividades, badges, mapa cinematográfico, mentor IA e overlay de conquista de nível.
- **Separação de jornadas:** `journeyAccess` existe; `isYouth` não aparece; `generalLevels` tem 20 níveis; `youthLevels` tem 20 níveis; pontos gerais e jovens estão separados nos mocks.
- **Arquivo adicional do módulo:** `MinhaCaminhadaConquistas.vue` existe como arquivo não rastreado e pertence ao módulo Minha Caminhada; possui 1 estrutura Vue válida e gera catálogo visual de conquistas com filtro por público, incluindo regra para esconder categorias jovens quando `hasYouthJourney` for falso.
- **Rotas auditadas:** `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` listou 14 rotas: principal, conquistas, destaques/mensal, geral, historico, jovem, mapa, mentor, nivel, pontuacao, presencas, ranking, regras e regras-de-pontos.
- **Estado das rotas:** rota principal renderiza `FamiliaResgate/MinhaCaminhada`; rota `conquistas` renderiza `FamiliaResgate/MinhaCaminhadaConquistas`; demais subrotas permanecem em placeholder oficial via `FamiliaResgate/Placeholder`.
- **Possíveis duplicidades de rotas:** `pontuacao` e `regras-de-pontos` podem ser reorganizadas futuramente; `nivel` pode sobrepor parte da tela principal; não foi removida nenhuma rota.
- **Links e botões:** não foram encontrados `href="#"`, `to="#"` ou `javascript:void(0)` em `MinhaCaminhada.vue` ou `MinhaCaminhadaConquistas.vue`; botões internos do mapa usam funções locais e links navegam para rotas do módulo.
- **Assets auditados:** a pasta `public/images/familia-resgate/minha-caminhada/` contém `mapa-caminhada-cinematico.png` e `mentor-caminhada.png`, ambos não vazios; há `.DS_Store` visível na listagem local, mas não aparece no Git status detalhado.
- **Divergência de assets:** histórico anterior cita SVGs `mapa-caminhada-geral.svg`, `mapa-caminhada-jovem.svg` e `mentor-caminhada.svg`, mas a pasta atual contém PNGs cinematográficos usados pelo Vue; deve ser decidido depois se SVGs antigos devem ser recriados, restaurados ou substituídos oficialmente pelos PNGs.
- **Arquivos paralelos indevidos:** busca por `MinhaCaminhadaNova`, `MinhaCaminhadaFinal`, `MinhaCaminhadaCorrigida`, `MinhaCaminhadaV2`, `MinhaCaminhadaTemp`, `MinhaCaminhadaOld`, `MinhaCaminhadaBackup` e `MinhaCaminhadaTeste` não encontrou resultados.
- **Buscas por lixo:** no arquivo principal não foram encontrados `isYouth`, links mortos, `Pontuação saudável`, `Ouro`, `Prata`, `Bronze`, `Diamante`, `Level`, `Versao2`, `Novo`, `Final`, `Corrigido`, `Temp`, `Copy`, `Old`, heredoc/quote quebrado ou marcadores de conflito.
- **Aparições esperadas:** `journeyAccess`, `generalLevels`, `youthLevels`, `mapa-caminhada-cinematico.png` e `mentor-caminhada.png` aparecem em `MinhaCaminhada.vue`; `mapa-caminhada` e `mentor-caminhada` também aparecem no histórico.
- **Módulos protegidos:** `Index.vue`, `MeuPerfil.vue`, `MeuFinanceiro.vue` e `FamilySidebar.vue` não aparecem alterados no Git status específico; sidebar oficial preservada.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 14 rotas; `php artisan test` passou com 50 testes e 162 assertions; `git diff --check` passou sem saída.
- **Pontos de atenção para próxima etapa:** decidir se `MinhaCaminhadaConquistas.vue` deve permanecer como parte oficial agora ou aguardar refinamento; corrigir divergência documental/assets SVG versus PNG; substituir mocks por props/backend no futuro; revisar autorização real de jornada jovem, pois o arquivo principal ainda usa `journeyAccess.hasYouthJourney: true` como mock.
- **Decisão de fechamento:** projeto está compilando e testando, sem sinais de arquivo corrompido; seguro para parar hoje, desde que nada seja commitado antes de revisar os itens não rastreados.
- **Commit:** nenhum commit realizado.

### Fechamento limpo do dia — Minha Caminhada

- **Horário:** 01:24–01:35.
- **Objetivo:** encerrar o dia com limpeza segura e organização do módulo `/familia-resgate/minha-caminhada`, sem continuar implementação visual, sem refinar telas, sem apagar arquivos do módulo, sem alterar sidebar, sem mexer em backend e sem commit.
- **Status da Minha Caminhada:** não foi concluída hoje; `resources/js/Pages/FamiliaResgate/MinhaCaminhada.vue` permanece como primeira versão visual/estrutural em andamento, com mocks temporários e pendente de revisão futura.
- **Decisão oficial sobre Conquistas:** `resources/js/Pages/FamiliaResgate/MinhaCaminhadaConquistas.vue` foi preservado e passa a ser tratado como página oficial futura da área `/familia-resgate/minha-caminhada/conquistas`, ainda pendente de revisão visual, refinamento e oficialização final antes de qualquer commit.
- **Ideia oficial da página Conquistas:** a página deve evoluir para uma galeria de marcos espirituais da caminhada da pessoa, reunindo badges gerais, badges jovens quando houver acesso, níveis conquistados, marcos espirituais desbloqueados, conquistas em progresso, conquistas bloqueadas, conquistas concedidas pela liderança/administração, histórico de desbloqueios, filtros por categoria, regras de desbloqueio e ligação com o Mapa da Caminhada, os 20 níveis gerais e os 20 níveis jovens.
- **Regra futura de acesso:** membro/congregado vê conquistas gerais; jovem/resgatado vê conquistas gerais e jovens; conquistas jovens só aparecem com `hasYouthJourney`; conquistas administrativas podem aparecer separadas; conquistas bloqueadas devem ser exibidas sem humilhação, comparação tóxica ou mistura de pontos/rankings entre geral e jovem.
- **Verificação de `MinhaCaminhadaConquistas.vue`:** arquivo existe, possui 1 `<script setup>`, 1 `</script>`, 1 `<template>`, 1 `</template>`, 1 `<style scoped>` e 1 `</style>`; não foram encontrados `href="#"`, `to="#"`, `javascript:void(0)`, conflitos Git, heredoc ou restos de quote.
- **Regra jovem em Conquistas:** o arquivo contém lógica com `hasYouthJourney`, mantendo a intenção de esconder categorias jovens quando o usuário não tiver acesso jovem.
- **Limpeza realizada:** removido apenas `public/images/familia-resgate/minha-caminhada/.DS_Store`, confirmado como lixo seguro; nenhum PNG, rota, página Vue, sidebar ou outro módulo foi removido.
- **`.gitignore`:** verificado e já contém `.DS_Store`; nenhuma alteração foi necessária no `.gitignore`.
- **Assets preservados:** `public/images/familia-resgate/minha-caminhada/mapa-caminhada-cinematico.png` e `public/images/familia-resgate/minha-caminhada/mentor-caminhada.png` foram mantidos como assets atuais oficiais provisórios do módulo.
- **SVGs antigos:** `mapa-caminhada-geral.svg`, `mapa-caminhada-jovem.svg` e `mentor-caminhada.svg` não foram recriados hoje; a decisão final sobre PNG versus SVG fica para a próxima etapa visual.
- **Rotas preservadas:** `/familia-resgate/minha-caminhada/conquistas` foi mantida renderizando `FamiliaResgate/MinhaCaminhadaConquistas`; as demais rotas da Minha Caminhada também foram preservadas.
- **Arquivos protegidos:** `FamilySidebar.vue`, `Index.vue`, `MeuPerfil.vue`, `MeuFinanceiro.vue`, controllers, models, migrations, services, policies, `.env`, storage, uploads, banco de dados, autenticação e permissões não foram alterados nesta limpeza.
- **Validações executadas:** `npm run build`, `php artisan test`, `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi`, `git diff --check` e `git status --short --untracked-files=all`.
- **Pendências para próxima etapa:** finalizar visual do Mapa da Caminhada; transformar os marcos do mapa em pequenas igrejas/conquistas; preparar páginas personalizadas dos 20 níveis; revisar visual da página Conquistas; decidir oficialmente PNG vs SVG; preparar variação Apenas Membro; substituir `journeyAccess` mockado por dados reais do backend; garantir que membros comuns não vejam conquistas jovens; revisar rotas potencialmente sobrepostas `pontuacao`, `regras-de-pontos` e `nivel`; só fazer commit quando a etapa visual estiver aprovada.
- **Commit:** nenhum commit realizado.

## Dia 15 — 15/05/2026 — Retomada do módulo Minha Caminhada

**Horário de início:** 09:32
**Status:** em andamento, sem commit.

### O que começamos hoje

Hoje retomamos o módulo **Minha Caminhada** após o fechamento limpo anterior.

Antes de implementar, foi feita uma verificação rápida de retomada para confirmar que o projeto continuava seguro:

- `npm run build` passou;
- as rotas de `/familia-resgate/minha-caminhada` foram listadas com sucesso, com 14 rotas preservadas na retomada inicial;
- `MinhaCaminhada.vue` existe;
- `MinhaCaminhadaConquistas.vue` existe e permanece como página oficial futura;
- os assets PNG do módulo existem;
- `.DS_Store` não voltou para a pasta de imagens;
- nenhum commit foi feito.

### Etapa iniciada

Iniciamos a etapa do **Mapa da Caminhada**, com a decisão de transformar os 20 níveis em **igrejas/marcos de conquista**, em vez de círculos genéricos.

A ideia definida é que cada nível da caminhada seja representado como uma pequena igreja/marco espiritual no caminho. Ao alcançar os pontos necessários, o membro desbloqueia aquela conquista, e futuramente cada marco terá uma página personalizada do nível.

### Direção técnica/visual definida

- Substituir os antigos círculos/orbs do mapa por igrejas/marcos visuais;
- manter todos os 20 níveis gerais navegáveis no mapa;
- usar trilha horizontal com setas;
- preparar cada marco com rota futura;
- criar rota dinâmica futura `/familia-resgate/minha-caminhada/niveis/{level}`;
- não criar as 20 páginas agora;
- não criar backend/migrations nesta etapa;
- não mexer na sidebar;
- não mexer nos outros módulos.

### Pendência imediata

Continuar a implementação visual do mapa, finalizar limpeza do CSS antigo de orbs/bolinhas, validar build/rotas/diff e depois avaliar o print do mapa antes de avançar.

**Commit:** nenhum commit realizado.

### Etapa 2A — Página dinâmica dos níveis da caminhada

- **Horário:** 13:38–13:52 aprox.
- **Objetivo:** criar a página oficial dinâmica para os 20 níveis/igrejas da caminhada, sem criar 20 arquivos, sem criar controller, sem migration, sem backend novo, sem mexer na sidebar, sem revisar Conquistas e sem alterar rotas antigas/sobrepostas.
- **Arquivo criado:** `resources/js/Pages/FamiliaResgate/MinhaCaminhadaNivel.vue`.
- **Dados temporários:** adicionados no topo do novo componente com comentário explícito de protótipo visual, contendo os 20 níveis gerais de `Primeiros Passos` até `Legado Vivo`, com número, nome, slug, status, pontos, descrição, versículo, próximo nível, badges, atividades e rota.
- **Rota ajustada:** `/familia-resgate/minha-caminhada/niveis/{level}` passou a renderizar `FamiliaResgate/MinhaCaminhadaNivel`, mantendo a constraint `[1-9]|1[0-9]|20`.
- **Comportamento dos marcos:** as igrejas/marcos do mapa em `MinhaCaminhada.vue` deixaram de usar `preventDefault` e agora navegam sempre para `/familia-resgate/minha-caminhada/niveis/{level}`.
- **Modal de celebração:** preservado e separado do clique principal das igrejas; permanece acionado pelos botões `Celebrar conquista` dos cards principais.
- **Sidebar:** preservada, usando `FamilySidebar` com `active-href="/familia-resgate/minha-caminhada"`.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 15 rotas; `git diff --check` passou após remoção de trailing whitespace no histórico; busca por padrões proibidos não encontrou ocorrências no módulo; `git status --short --untracked-files=all` foi conferido.
- **Commit:** nenhum commit realizado.

### Etapa 2A.2 — Página real do Mapa da Caminhada

- **Horário:** 15:24–15:36 aprox.
- **Objetivo:** fazer o botão `Voltar ao mapa` da página dinâmica dos níveis abrir um destino real em `/familia-resgate/minha-caminhada/mapa`, sem deixar a rota como placeholder cru.
- **Arquivo criado:** `resources/js/Pages/FamiliaResgate/MinhaCaminhadaArea.vue`.
- **Prop preparada:** a página recebe `area`, com implementação atual somente para `area: "mapa"`.
- **Rota ajustada:** `/familia-resgate/minha-caminhada/mapa` passou a renderizar `FamiliaResgate/MinhaCaminhadaArea` com `area => mapa`.
- **Limpeza de rota:** a entrada `minha-caminhada/mapa` foi removida do array genérico de placeholders para evitar rota duplicada/lixo.
- **Botão Voltar ao mapa:** preservado em `MinhaCaminhadaNivel.vue`, apontando para `${baseRoute}/mapa`.
- **Página do mapa:** criada com sidebar oficial, breadcrumb, título, subtítulo, card cinematográfico do mapa, 20 igrejas/marcos navegáveis, estados visuais, legenda, ações para Minha Caminhada e Conquistas e aviso de integração futura.
- **Comportamento dos marcos:** cada igreja/marco aponta para `/familia-resgate/minha-caminhada/niveis/{level}`, sem `href="#"`, sem `preventDefault` e sem modal.
- **Sidebar:** preservada, usando `FamilySidebar` com `active-href="/familia-resgate/minha-caminhada"`.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 15 rotas; `git diff --check` passou; busca focada por padrões proibidos em `MinhaCaminhada*.vue` não encontrou ocorrências; `git status --short --untracked-files=all` foi conferido.
- **Observação:** a busca ampla em `FamiliaResgate/*.vue` encontrou apenas falso positivo em `MeuPerfil.vue` por causa da tag HTML legítima `</blockquote>`, fora do escopo da etapa.
- **Commit:** nenhum commit realizado.

### Etapa 2A.3 — Separação dos níveis por jornada geral/jovem

- **Horário:** 15:55–16:08 aprox.
- **Objetivo:** separar a navegação dos níveis da `Caminhada Geral da Igreja` e da `Caminhada Jovem Resgatado`, sem apagar a rota antiga genérica.
- **Rotas criadas:** `/familia-resgate/minha-caminhada/geral/niveis/{level}` e `/familia-resgate/minha-caminhada/jovem/niveis/{level}`, ambas renderizando `FamiliaResgate/MinhaCaminhadaNivel` com props `journey` e `level`.
- **Rota de compatibilidade:** `/familia-resgate/minha-caminhada/niveis/{level}` foi mantida e agora renderiza a página com `journey => geral`, como compatibilidade temporária até auditoria final futura.
- **Página de nível:** `MinhaCaminhadaNivel.vue` passou a receber `journey`, escolher entre `generalLevels` e `youthLevels`, aplicar breadcrumb/título/textos/badges/atividades de acordo com a jornada e fazer o botão `Próximo nível` apontar para `/geral/niveis/{next}` ou `/jovem/niveis/{next}`.
- **Mapa principal:** `MinhaCaminhada.vue` passou a gerar links dos marcos para `/geral/niveis/{level}` quando o mapa ativo é geral e `/jovem/niveis/{level}` quando o mapa ativo é jovem.
- **Mapa completo:** `MinhaCaminhadaArea.vue` passou a usar `/geral/niveis/{level}` nos marcos do mapa completo, deixando a rota genérica apenas como compatibilidade.
- **Regra futura:** registrado em rota/componente que membro comum deve acessar somente caminhada geral, jovem/resgatado deve acessar geral + jovem, e a rota jovem deverá ser protegida futuramente no backend/policy, pois frontend não é segurança final.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 17 rotas, incluindo as rotas geral, jovem e genérica de níveis; `git diff --check` passou; busca por padrões proibidos no módulo não encontrou ocorrências; `git status --short --untracked-files=all` foi conferido.
- **Commit:** nenhum commit realizado.

### Etapa 2A.4 — Separação do mapa geral e mapa jovem

- **Horário:** 16:16–16:30 aprox.
- **Objetivo:** separar também a página do mapa por jornada e corrigir o botão dos níveis de `Voltar ao mapa` para `Ir ao mapa`.
- **Rotas criadas:** `/familia-resgate/minha-caminhada/geral/mapa` e `/familia-resgate/minha-caminhada/jovem/mapa`, ambas renderizando `FamiliaResgate/MinhaCaminhadaArea` com props `area => mapa` e `journey => geral/jovem`.
- **Rota de compatibilidade:** `/familia-resgate/minha-caminhada/mapa` foi mantida como rota legada temporária de compatibilidade geral, renderizando `journey => geral`.
- **Botão dos níveis:** em `MinhaCaminhadaNivel.vue`, o botão `Voltar ao mapa` foi renomeado para `Ir ao mapa` e agora aponta dinamicamente para `/geral/mapa` ou `/jovem/mapa`, conforme a jornada atual.
- **Página do mapa:** `MinhaCaminhadaArea.vue` passou a receber `journey`, escolher entre níveis gerais e jovens, mudar breadcrumb/título/subtítulo/resumo conforme jornada e gerar links dos marcos para `/geral/niveis/{level}` ou `/jovem/niveis/{level}`.
- **Tela principal:** o botão `Ver meu mapa da caminhada` em `MinhaCaminhada.vue` passou a apontar para `/geral/mapa` ou `/jovem/mapa`, conforme o mapa ativo.
- **Visual:** o mapa jovem mantém a identidade azul noturno + dourado e recebeu diferenciação discreta em laranja-fogo.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/geral/mapa`, `/jovem/mapa` e `/mapa`; `git diff --check` passou; busca por padrões proibidos no módulo não encontrou ocorrências; conferência textual confirmou links dinâmicos por jornada.
- **Commit:** nenhum commit realizado.

### Mini auditoria — Separação geral/jovem da Minha Caminhada

- **Horário:** 16:37–16:43 aprox.
- **Escopo:** verificação final da separação de níveis e mapas por jornada antes de avançar para novas etapas, sem implementação nova e sem alteração visual.
- **Rotas conferidas:** `/familia-resgate/minha-caminhada/geral/niveis/6`, `/familia-resgate/minha-caminhada/jovem/niveis/6`, `/familia-resgate/minha-caminhada/niveis/6`, `/familia-resgate/minha-caminhada/geral/mapa`, `/familia-resgate/minha-caminhada/jovem/mapa` e `/familia-resgate/minha-caminhada/mapa`.
- **Níveis confirmados:** `geral/niveis/6` usa `Caminho do Discípulo`; `jovem/niveis/6` usa `Escudeiro da Palavra`; a rota antiga `niveis/6` permanece como compatibilidade geral e usa `Caminho do Discípulo`.
- **Mapas confirmados:** `geral/mapa` usa níveis gerais; `jovem/mapa` usa níveis jovens; a rota antiga `mapa` permanece como compatibilidade geral.
- **Botões confirmados:** `Ir ao mapa` respeita `/geral/mapa` ou `/jovem/mapa`; `Próximo nível` respeita `/geral/niveis/{level}` ou `/jovem/niveis/{level}`; `Ver conquistas` continua apontando para `/conquistas`; `Voltar para Minha Caminhada` continua apontando para `/familia-resgate/minha-caminhada`.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo.
- **Status:** alterações rastreadas em `docs/HISTORICO_DIARIO_PROJETO_MINISTERIO_RESGATE.md` e `routes/web.php`; arquivos não rastreados do módulo Minha Caminhada e imagens do módulo permanecem pendentes para revisão futura.
- **Commit:** nenhum commit realizado.

### Etapa de integração — Meu Nível Atual

- **Horário:** 17:06–17:20 aprox.
- **Decisão:** manter `/familia-resgate/minha-caminhada/nivel` como rota oficial agregadora, sem apagar a rota antiga usada pela Central da Família.
- **Motivo:** o card `Nível Atual` da Central da Família já aponta para `/familia-resgate/minha-caminhada/nivel`, então a rota virou destino seguro e real em vez de placeholder.
- **Rota ajustada:** `/familia-resgate/minha-caminhada/nivel` passou a renderizar `FamiliaResgate/MinhaCaminhadaArea` com props `area => nivel` e `journey => auto`.
- **Limpeza de rota:** a entrada `minha-caminhada/nivel` foi removida somente do array genérico de placeholders para evitar duplicidade.
- **Página preparada:** `MinhaCaminhadaArea.vue` passou a renderizar a página `Meu Nível Atual` quando recebe `area: "nivel"`, preservando o mapa quando recebe `area: "mapa"`.
- **Separação visual:** a página mostra `Caminhada Geral da Igreja` e, quando o mock `hasYouthJourney` está verdadeiro, também mostra `Caminhada Jovem Resgatado`, com aviso de que os pontos gerais e jovens são separados.
- **Botões:** cada jornada aponta para seu nível atual em `/geral/niveis/{level}` ou `/jovem/niveis/{level}` e para seu mapa em `/geral/mapa` ou `/jovem/mapa`; ações extras voltam para `Minha Caminhada` e `Conquistas`.
- **Regra futura:** registrado no mock que usuário comum vê somente caminhada geral, jovem/resgatado vê geral + jovem, o backend futuro deve enviar `hasYouthJourney` e níveis atuais, e frontend não é segurança final.
- **Escopo preservado:** sem criação de `/geral` e `/jovem`, sem mexer em sidebar, Meu Perfil, Meu Financeiro, Conquistas, backend, migrations, controllers, pontuação, regras, presenças, ranking, mentor ou histórico interno.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas.
- **Status:** `/nivel` renderiza `MinhaCaminhadaArea` com `area: "nivel"`; o card `Nível Atual` da Central continua apontando para `/nivel`; rotas de níveis e mapas por jornada permanecem preservadas.
- **Commit:** nenhum commit realizado.

### Etapa 2B.1 — Minha Caminhada Geral e Jovem como páginas reais

- **Horário:** 17:25–17:42 aprox.
- **Objetivo:** transformar `/familia-resgate/minha-caminhada/geral` e `/familia-resgate/minha-caminhada/jovem` de placeholders genéricos em páginas reais da `Minha Caminhada`, usando o componente existente `MinhaCaminhadaArea.vue`.
- **Rotas ajustadas:** `/familia-resgate/minha-caminhada/geral` passou a renderizar `FamiliaResgate/MinhaCaminhadaArea` com props `area => geral` e `journey => geral`; `/familia-resgate/minha-caminhada/jovem` passou a renderizar o mesmo componente com props `area => jovem` e `journey => jovem`.
- **Limpeza de rota:** as entradas `minha-caminhada/geral` e `minha-caminhada/jovem` foram removidas somente do array genérico de placeholders para evitar duplicidade.
- **Página geral:** criada a experiência `Caminhada Geral da Igreja`, com resumo de nível, pontos, progresso, próximo nível, posição geral, badges gerais, áreas de crescimento, atividades recentes, conquistas, próximos passos e botões para nível atual, mapa geral, histórico, conquistas, Minha Caminhada e regras.
- **Página jovem:** criada a experiência `Caminhada Jovem dos Resgatados`, com identidade azul noturno + dourado e diferenciação discreta em laranja-fogo, pontos jovens separados, áreas jovens, atividades recentes, badges jovens, próximos passos e aviso de separação da caminhada geral.
- **Componente preservado:** `MinhaCaminhadaArea.vue` manteve as renderizações existentes de `area: "mapa"` e `area: "nivel"` e adicionou a branch reutilizável para `area: "geral"` e `area: "jovem"`.
- **Regra futura:** reforçado no mock que usuário comum deve acessar somente caminhada geral, jovem/resgatado acessa geral + jovem, e a rota jovem deve ser protegida futuramente por backend/policy, pois frontend não é segurança final.
- **Escopo preservado:** sem alterações em sidebar, Meu Perfil, Meu Financeiro, Conquistas, backend, migrations, controllers, pontuação real, políticas, banco de dados ou commits.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/geral` e `/jovem`; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas.
- **Commit:** nenhum commit realizado.

### Refinamento visual — Detalhes da Caminhada Geral e Jovem

- **Horário:** 18:05–18:23 aprox.
- **Objetivo:** reduzir repetição entre `/familia-resgate/minha-caminhada`, `/familia-resgate/minha-caminhada/geral` e `/familia-resgate/minha-caminhada/jovem`, fazendo `/geral` e `/jovem` parecerem páginas de detalhe reais.
- **Arquivo alterado:** somente `resources/js/Pages/FamiliaResgate/MinhaCaminhadaArea.vue` no componente funcional, além deste histórico.
- **Página geral:** `/geral` passou a usar título `Detalhes da Caminhada Geral`, hero compacto, 4 cards pequenos de resumo, bloco `Foco da semana`, análise compacta por áreas, atividades recentes, conquistas gerais e barra de atalhos para nível geral, mapa geral, conquistas e retorno.
- **Página jovem:** `/jovem` passou a usar título `Detalhes da Caminhada Jovem`, chip `Resgatados`, bloco `Desafio da semana`, áreas jovens compactas, missões recentes, conquistas jovens, aviso de separação de pontos e atalhos para nível jovem, mapa jovem, conquistas e retorno.
- **Redução de repetição:** removido o card gigante de jornada logo abaixo do hero nas páginas de detalhe; o resumo foi convertido em cards pequenos e as seções foram organizadas como análise/atividade/atalhos.
- **Diferenciação visual:** caminhada geral mantém tom sereno em azul noturno + dourado; caminhada jovem mantém azul noturno + dourado com laranja-fogo discreto em foco, progresso e ações principais.
- **Escopo preservado:** sem mexer em sidebar, rotas, backend, migrations, controllers, Conquistas, Meu Perfil, Meu Financeiro, `area: "mapa"` ou `area: "nivel"`.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas.
- **Commit:** nenhum commit realizado.

### Etapa 2B.2 — Histórico da Caminhada como página real

- **Horário:** 18:28–18:48 aprox.
- **Objetivo:** transformar `/familia-resgate/minha-caminhada/historico` de placeholder em página real da `Minha Caminhada`, usando o arquivo oficial já existente `MinhaCaminhadaArea.vue`.
- **Rota ajustada:** `/familia-resgate/minha-caminhada/historico` passou a renderizar `FamiliaResgate/MinhaCaminhadaArea` com props `area => historico` e `journey => all`.
- **Limpeza de rota:** removida somente a entrada `minha-caminhada/historico` do array genérico de placeholders para evitar duplicidade.
- **Página criada:** adicionada branch `area: "historico"` com hero compacto, breadcrumb `Central da Família > Minha Caminhada > Histórico`, chip `Histórico completo`, resumo rápido, filtros visuais e linha do tempo.
- **Filtros preparados:** `Todos`, `Caminhada Geral`, `Caminhada Jovem`, `Presença`, `Palavra`, `Devocional`, `Serviço`, `Evangelismo` e `Conquistas`, com estado local simples para seleção visual e filtragem dos mocks.
- **Timeline visual:** criada linha do tempo compacta com eventos mockados de caminhada geral, caminhada jovem, presença, Palavra, devocional, serviço, evangelismo, conquistas e ajuste administrativo.
- **Card de apoio:** incluído bloco `Como o histórico funciona`, explicando registros automáticos, enviados por líderes ou validados pela Secretaria/Administração, com pontos confirmados, pontos em validação e conquistas desbloqueadas.
- **Botões reais:** adicionados atalhos para `/familia-resgate/minha-caminhada`, `/geral`, `/jovem` e `/conquistas`.
- **Regra futura:** registrado no mock que backend futuro deve enviar histórico real; registros podem vir de presença, leitura, devocional, serviço, visitantes, jovens, conquistas e ajustes administrativos; jovens/resgatados possuem histórico jovem separado do histórico geral; frontend não é segurança final.
- **Escopo preservado:** sem mexer em sidebar, Meu Perfil, Meu Financeiro, Conquistas, backend, migrations, controllers, `/geral`, `/jovem`, `/mapa`, `/nivel`, `/niveis`, `/mentor`, `/ranking`, `/regras`, `/pontuacao`, `/regras-de-pontos`, `/presencas` ou `/destaques/mensal`.
- **Pendências futuras:** trocar mocks por dados reais vindos do backend, aplicar permissões/policies, conectar validações administrativas e histórico real de eventos.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/historico`; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas.
- **Commit:** nenhum commit realizado.

### Etapa 2B.3 — Mentor da Caminhada como página real

- **Horário:** 18:50–19:08 aprox.
- **Objetivo:** transformar `/familia-resgate/minha-caminhada/mentor` de placeholder em página real da `Minha Caminhada`, usando o arquivo oficial já existente `MinhaCaminhadaArea.vue`.
- **Rota ajustada:** `/familia-resgate/minha-caminhada/mentor` passou a renderizar `FamiliaResgate/MinhaCaminhadaArea` com props `area => mentor` e `journey => all`.
- **Limpeza de rota:** removida somente a entrada `minha-caminhada/mentor` do array genérico de placeholders para evitar duplicidade.
- **Página criada:** adicionada branch `area: "mentor"` com hero compacto, breadcrumb `Central da Família > Minha Caminhada > Mentor`, título `Mentor da Caminhada`, subtítulo de apoio cuidadoso e chip `Apoio inteligente`.
- **Leitura da semana:** criada análise mockada informando boa constância em presença e serviço, com próximo passo em Palavra e devocional, além dos campos `Área forte`, `Área a fortalecer`, `Ritmo da semana` e `Próximo marco`.
- **Plano personalizado:** incluídos 4 passos sugeridos com categoria, ação, impacto e status visual: ler 1 capítulo por dia, registrar 3 devocionais, participar do próximo culto e servir em uma escala ou apoiar um ministério.
- **Geral/Jovem:** adicionados cards separados para Caminhada Geral e Caminhada Jovem, com foco sugerido em Palavra/constância e desafio bíblico/presença nos Resgatados; o card jovem respeita `hasYouthJourney` mockado.
- **Perguntas guiadas:** adicionada área visual preparada para IA futura com sugestões de perguntas sobre constância, áreas de atenção, leitura bíblica e próximos passos.
- **Limites pastorais:** incluído card `Cuidado importante`, reforçando que o Mentor da Caminhada é apoio baseado em registros e não substitui liderança pastoral, aconselhamento, discipulado ou acompanhamento espiritual pessoal.
- **Botões reais:** adicionados atalhos para `/familia-resgate/minha-caminhada`, `/historico`, `/regras` e `/conquistas`.
- **Regra futura:** registrado no mock que backend futuro deve enviar insights reais, IA futura deve usar somente dados autorizados, não substitui pastor/liderança, deve considerar histórico geral e jovem separadamente, e frontend não é segurança final.
- **Escopo preservado:** sem mexer em sidebar, Meu Perfil, Meu Financeiro, Conquistas, backend, migrations, controllers, `/geral`, `/jovem`, `/historico`, `/mapa`, `/nivel`, `/niveis`, `/ranking`, `/regras`, `/pontuacao`, `/regras-de-pontos`, `/presencas` ou `/destaques/mensal`.
- **Pendências futuras:** substituir mocks por insights reais autorizados, conectar dados reais da caminhada, aplicar permissões/policies, criar endpoint próprio apenas em fase futura e revisar cuidadosamente limites pastorais antes de qualquer IA real.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/mentor`; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas; `git diff --check` passou.
- **Commit:** nenhum commit realizado.

### Microajuste — Mentor por regras pastorais

- **Horário:** 19:19–19:27 aprox.
- **Objetivo:** ajustar a comunicação do `/familia-resgate/minha-caminhada/mentor` para deixar claro que a primeira versão real do Mentor da Caminhada não dependerá de IA externa paga, mas de inteligência gratuita por regras pastorais.
- **Textos ajustados:** substituídos termos genéricos de IA por `apoio inteligente`, `orientação por regras pastorais`, `leitura da caminhada`, `plano sugerido` e `mentor inteligente por regras`.
- **Card adicionado:** incluído o bloco `Como este mentor funciona`, explicando que o mentor será preparado para analisar registros reais como presença, Palavra, devocional, serviço, comunhão, evangelismo e conquistas.
- **Decisão futura registrada:** orientações serão geradas por regras pastorais seguras e respostas pré-aprovadas, com histórico futuro para evitar repetição por pessoa e família.
- **Cuidado pastoral reforçado:** mantido o aviso de que o Mentor não substitui pastor, liderança, discipulado, aconselhamento pastoral ou acompanhamento espiritual humano, e não trata crises graves sozinho.
- **Comentário técnico curto:** registrado próximo aos mocks que o backend futuro deve começar como inteligência gratuita por regras, usando tipos de análise, respostas pré-aprovadas e histórico de respostas; IA externa real poderá ser estudada somente em fase futura.
- **Escopo preservado:** sem backend, migrations, controllers, IA real, rotas, outras páginas, funcionalidade real nova ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas.
- **Commit:** nenhum commit realizado.

### Etapa 2B.4 — Regras da Caminhada como página real

- **Horário:** 19:34–19:52 aprox.
- **Objetivo:** transformar `/familia-resgate/minha-caminhada/regras` de placeholder em página real da `Minha Caminhada`, usando o arquivo oficial já existente `MinhaCaminhadaArea.vue`.
- **Rota ajustada:** `/familia-resgate/minha-caminhada/regras` passou a renderizar `FamiliaResgate/MinhaCaminhadaArea` com props `area => regras` e `journey => all`.
- **Limpeza de rota:** removida somente a entrada `minha-caminhada/regras` do array genérico de placeholders para evitar duplicidade, mantendo `/ranking`, `/pontuacao`, `/regras-de-pontos`, `/presencas` e `/destaques/mensal` para etapas futuras.
- **Página criada:** adicionada branch `area: "regras"` com hero compacto, breadcrumb `Central da Família > Minha Caminhada > Regras`, título `Regras da Caminhada`, subtítulo explicativo e chip `Caminhada saudável`.
- **Princípio da caminhada:** incluído card principal reforçando que a caminhada não mede espiritualidade nem valor diante de Deus, mas incentiva constância, participação, serviço, Palavra, comunhão e crescimento saudável.
- **Pilares pastorais:** adicionados os pilares `Encorajar, não comparar`, `Acompanhar, não expor` e `Crescer, não competir`.
- **Pontuação explicada:** criada seção `Como os pontos funcionam` com cards para Presença, Palavra, Devocional, Serviço, Comunhão, Evangelismo e Formação, em tom didático e não competitivo.
- **Jornada geral e jovem:** adicionados cards explicando a Caminhada Geral da Igreja e a Caminhada Jovem dos Resgatados, com pontos e níveis separados e sem mistura entre jornadas.
- **Níveis, marcos e conquistas:** incluídos blocos explicando que níveis são marcos simbólicos, o mapa mostra igrejinhas/conquistas, atingir nível não torna ninguém melhor que outro e conquistas devem encorajar, não envergonhar.
- **Validação dos registros:** incluída seção explicando registros automáticos, enviados por líderes ou confirmados pela Secretaria/Administração, com status `Confirmado`, `Em validação`, `Ajustado`, `Cancelado` e `Conquista desbloqueada`.
- **Limites pastorais:** criado card `O que a caminhada não é`, reforçando que não é ranking de espiritualidade, não substitui discipulado/cuidado pastoral, não mede valor diante de Deus, não deve humilhar/comparar pessoas e não transforma serviço em competição.
- **Botões reais:** adicionados atalhos para Minha Caminhada, Histórico, Mentor, Conquistas, Mapa Geral e Mapa Jovem, todos apontando para rotas reais.
- **Regra futura:** registrado no mock que backend futuro deve definir regras reais de pontuação no servidor, auditáveis, com pontos gerais e jovens separados, validações protegidas por permissões/policies, frontend não é segurança final e caminhada não mede espiritualidade.
- **Escopo preservado:** sem mexer em sidebar, Meu Perfil, Meu Financeiro, Conquistas, `/geral`, `/jovem`, `/historico`, `/mentor`, `/mapa`, `/nivel`, `/niveis/{level}`, backend, migrations, controllers, arquivos novos ou commit.
- **Pendências futuras:** substituir mocks por regras reais vindas do backend, definir pontuações auditáveis, permissões/policies de validação, histórico administrativo de ajustes e integração real com presença, Palavra, devocional, serviço, comunhão, evangelismo, formação e conquistas.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/regras`; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas; `git diff --check` passou.
- **Commit:** nenhum commit realizado.

### Microajuste visual — Regras da Caminhada

- **Horário:** 20:03–20:12 aprox.
- **Objetivo:** refinar visualmente `/familia-resgate/minha-caminhada/regras` sem alterar rotas, backend, migrations, controllers, arquivos novos ou outras páginas.
- **Composição:** mantido o hero aprovado e compactada a área `area: "regras"` em `MinhaCaminhadaArea.vue`, reduzindo a sensação de apostila e deixando a página mais premium e direta.
- **Princípio da Caminhada:** preservado o conteúdo do card principal, com pilares visualmente mais integrados em uma grade compacta.
- **Áreas acompanhadas:** compactados os cards de Presença, Palavra, Devocional, Serviço, Comunhão, Evangelismo e Formação, com menos padding, ícones menores, textos menores legíveis e altura mais uniforme.
- **Distribuição inferior:** `Validação dos registros` e `O que a caminhada não é` foram organizados em grade lado a lado no desktop, reduzindo scroll e mantendo visibilidade.
- **Tom pastoral preservado:** mantidos os textos aprovados, a separação entre Caminhada Geral e Caminhada Jovem, os limites pastorais, os botões reais e a identidade visual azul noturno, marfim, dourado e laranja-fogo discreto.
- **Responsividade:** ajustada a nova composição para voltar a uma coluna em telas menores.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/regras`; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas.
- **Commit:** nenhum commit realizado.

### Ajuste conceitual — Regras gerais e jovens separadas

- **Horário:** 20:08–20:18 aprox.
- **Objetivo:** corrigir a lógica de conteúdo da página `/familia-resgate/minha-caminhada/regras` para não misturar critérios da Caminhada Geral com critérios da Caminhada Jovem.
- **Princípio preservado:** mantida a explicação de que a caminhada não mede espiritualidade nem valor diante de Deus, servindo para incentivar constância, participação, serviço, Palavra, comunhão e crescimento saudável.
- **Caminhada Geral:** criada seção própria com texto, critérios gerais, pontos gerais, níveis gerais, conquistas gerais e aviso de que não depende dos desafios dos Resgatados.
- **Caminhada Jovem:** criada seção própria para os Resgatados, com critérios jovens, pontos jovens, níveis jovens, conquistas jovens e aviso de que não mistura pontos com a Caminhada Geral.
- **Jornadas separadas:** adicionada faixa explicando que um jovem pode participar das duas jornadas, mas cada uma possui critérios, níveis, marcos e conquistas próprios.
- **Evitar confusão:** removida a leitura visual de uma lista única de pontos para todo mundo; os critérios agora aparecem dentro das respectivas jornadas.
- **Futuro backend:** registrado no comentário que a página de regras deve respeitar o perfil do usuário: adultos/membros veem Geral em destaque; jovens/resgatados veem Geral + Jovem; responsáveis podem ver regras jovens dos filhos; Secretaria/liderança pode visualizar todas as regras.
- **Escopo preservado:** sem rotas, backend, migrations, controllers, outras páginas, arquivos novos ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/regras`; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas.
- **Commit:** nenhum commit realizado.

### Microajuste de harmonia visual — Regras da Caminhada

- **Horário:** 20:15–20:24 aprox.
- **Objetivo:** corrigir a harmonia visual da parte final de `/familia-resgate/minha-caminhada/regras`, sem alterar rotas, backend, migrations, controllers, arquivos novos ou outras páginas.
- **Card ajustado:** refinado o card `O que a caminhada não é` para evitar título espremido, reduzir peso visual, melhorar distribuição da lista e manter presença forte sem parecer alarmista.
- **Área inferior:** equilibrados os cards `Validação dos registros` e `O que a caminhada não é` com alturas mais próximas, padding mais confortável, gradientes suaves e respiro melhor antes dos botões finais.
- **Botões finais:** preservadas as rotas reais e melhorado o espaçamento visual para não ficarem colados aos cards.
- **Separação preservada:** mantidas as seções separadas de Caminhada Geral e Caminhada Jovem, pontos gerais e jovens separados e a faixa `Jornadas separadas`.
- **Escopo preservado:** sem rotas, backend, migrations, controllers, outras páginas, arquivos novos ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/regras`; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas.
- **Commit:** nenhum commit realizado.

### Melhoria visual — Regras da Caminhada como guia visual

- **Horário:** 20:22–20:34 aprox.
- **Objetivo:** reduzir a carga textual de `/familia-resgate/minha-caminhada/regras` e transformar a página em um guia visual mais dinâmico, sem alterar lógica, rotas, backend, migrations, controllers, arquivos novos ou outras páginas.
- **Texto reduzido:** parágrafos longos foram convertidos em frases curtas, cards compactos, badges e blocos com ícones discretos.
- **Comparativo Geral x Jovem:** as seções de Caminhada Geral da Igreja e Caminhada Jovem dos Resgatados foram mantidas separadas e ficaram mais visuais, com ícones, badges de pontos/níveis/conquistas e cards compactos de critérios.
- **Jornadas separadas:** a faixa foi preservada e ganhou duas trilhas visuais: `Caminhada Geral → pontos gerais` e `Caminhada Jovem → pontos jovens`.
- **Orientação visual:** `Níveis e marcos`, `Conquistas` e `Validação` foram convertidos em três cards de orientação com ícones, frases curtas, botões reais e status compactos.
- **Cuidado pastoral:** `O que a caminhada não é` foi reorganizado em duas colunas, `Não é` e `É para`, mantendo limites pastorais sem peso de regulamento.
- **Separação preservada:** mantidos pontos gerais e jovens separados, critérios próprios por jornada e mensagem pastoral de que a caminhada não mede espiritualidade nem valor diante de Deus.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/regras`; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas.
- **Commit:** nenhum commit realizado.

### Etapa 2B.5 — Destaques da Caminhada

- **Horário:** 20:32–20:50 aprox.
- **Objetivo:** transformar `/familia-resgate/minha-caminhada/ranking` em página real de `Destaques da Caminhada`, mantendo a rota `/ranking` por compatibilidade técnica, mas removendo leitura de competição espiritual.
- **Rota ajustada:** `routes/web.php` passou a renderizar `FamiliaResgate/MinhaCaminhadaArea` com props `area='ranking'` e `journey='all'`.
- **Placeholder removido:** removida somente a entrada `minha-caminhada/ranking` do array genérico de placeholders, preservando `/pontuacao`, `/regras-de-pontos`, `/presencas` e `/destaques/mensal` para etapas futuras.
- **Página real:** `MinhaCaminhadaArea.vue` ganhou branch `isRankingArea` com hero compacto, breadcrumb `Central da Família > Minha Caminhada > Destaques`, título visual `Destaques da Caminhada`, subtítulo pastoral e chip `Reconhecimento saudável`.
- **Cuidado contra competição:** o conteúdo evita pódio, posições, linguagem de 1º/2º/3º e ranking frio; a mensagem central reforça que os destaques não medem espiritualidade nem valor diante de Deus.
- **Filtros visuais:** adicionados filtros locais para `Todos`, `Caminhada Geral`, `Resgatados`, `Serviço`, `Palavra`, `Presença`, `Evangelismo` e `Conquistas`, preparados para backend futuro.
- **Destaques do mês:** criados cards de reconhecimento com selos saudáveis, nome, motivo, jornada e categoria, sem exposição agressiva.
- **Visão geral saudável:** adicionados cards compactos de pessoas reconhecidas, selos entregues, áreas acompanhadas e destaques jovens.
- **Áreas de reconhecimento:** adicionados cards visuais para Presença, Palavra, Devocional, Serviço, Comunhão, Evangelismo, Formação e Resgatados.
- **Separação jovem:** criado bloco `Destaques dos Resgatados`, com badges jovens mockados e aviso de que reconhecimentos jovens não se misturam com os destaques gerais.
- **Cuidado pastoral:** criado card `Reconhecimento com cuidado` com limites explícitos contra ranking espiritual, exposição, vergonha, substituição do cuidado pastoral, disputa e comparação entre famílias/pessoas.
- **Botões reais:** atalhos finais apontam para Minha Caminhada, Regras, Histórico e Conquistas.
- **Futuro backend:** registrado comentário de que destaques devem ser auditáveis, respeitar permissões, separar jovens/resgatados, permitir acompanhamento por responsáveis, validação por Secretaria/liderança e nunca medir espiritualidade.
- **Escopo preservado:** sem sidebar, Meu Perfil, Meu Financeiro, Conquistas, páginas já aprovadas, backend, migrations, controllers, arquivo novo ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/ranking`, `/regras`, `/mentor`, `/historico`, `/geral`, `/jovem`, `/mapa`, `/nivel` e `/conquistas`; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas; status escopado conferido.
- **Pendências futuras:** integrar dados reais, regras auditáveis, permissões por perfil, validação administrativa e cálculo seguro dos reconhecimentos.
- **Commit:** nenhum commit realizado.

### Ajuste obrigatório — Conexão e separação dos Destaques da Caminhada

- **Horário:** 21:25–21:45 aprox.
- **Objetivo:** conectar a tela principal `/familia-resgate/minha-caminhada` à página real `/familia-resgate/minha-caminhada/ranking` e alinhar `Destaques da Caminhada` com a base de estudo sobre pontos gerais, pontos jovens e pontos de equipe jovem.
- **Base consultada:** conferidos `docs/base-estudo-resgate/03_MINHA_CAMINHADA_PONTUACAO_DESTAQUES_E_INTERCESSAO.md` e `docs/base-estudo-resgate/05_CHECKLIST_CONFERENCIA_ANTES_DE_NOVAS_ETAPAS.md`, sem editar esses documentos.
- **Tela principal conectada:** o card `Destaques do mês` em `MinhaCaminhada.vue` mantém link real para `/familia-resgate/minha-caminhada/ranking` e trocou a chamada visual de `Ver ranking` para `Ver destaques`.
- **Troca conceitual:** reforçada a linguagem de Destaques, Reconhecimento, Selos, Constância, Serviço, Crescimento, Resgatados, Equipes e Cuidado, evitando competição espiritual.
- **Separação obrigatória:** a página `/ranking` deixou de depender de lista única misturada e agora organiza os reconhecimentos em `Destaques Gerais da Igreja`, `Destaques Jovens dos Resgatados` e `Equipes dos Resgatados`.
- **Destaques gerais:** exibem somente cards da Caminhada Geral e critérios gerais: Presença, Palavra, Devocional, Serviço, Comunhão, Evangelismo, Formação e Intercessão quando aplicável.
- **Destaques jovens:** exibem somente cards dos Resgatados e critérios jovens: Presença nos Resgatados, Bíblia na mão, Desafios bíblicos, Serviço jovem, Comunhão jovem, Evangelismo jovem e Missões.
- **Equipes jovens:** criado bloco compacto `Equipes dos Resgatados` preparado para futuro, com selos coletivos como Missão em equipe, Desafio coletivo, Acolhimento jovem e Serviço coletivo, deixando claro que pontos de equipe não se misturam com pontos individuais.
- **Filtros visuais:** filtros atualizados para `Todos`, `Gerais`, `Resgatados`, `Equipes`, `Presença`, `Palavra`, `Serviço`, `Evangelismo` e `Conquistas`; o filtro `Todos` mostra seções separadas, sem misturar jovens e membros comuns em lista única.
- **Intercessão futura:** adicionada como categoria geral futura, com comentário de backend sobre pontuação fixa mensal, pontuação por atendimento, avaliação privada convertida em no máximo 3 pontos e proteção contra popularidade espiritual.
- **Futuro backend:** comentário ajustado para registrar que regras de pontuação virão da Administração e definirão jornada, categoria, pontos gerais, pontos jovens, pontos de equipe jovem, validação, limites e se contam para destaques.
- **Cuidado pastoral:** card `Reconhecimento com cuidado` passou a explicitar que não compara jovens com adultos e não mistura pontos individuais com pontos de equipe.
- **Escopo preservado:** sem sidebar, backend, migrations, controllers, rotas, arquivos novos, páginas aprovadas desfeitas ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/ranking`, `/regras`, `/mentor`, `/historico`, `/geral`, `/jovem`, `/mapa`, `/nivel` e `/conquistas`; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git e padrões `heredoc/quote/dquote/bquote` não encontrou ocorrências no escopo do módulo/rotas; status escopado conferido.
- **Commit:** nenhum commit realizado.

### Ajuste final premium — Destaques com visibilidade realista

- **Horário:** 21:46–22:05 aprox.
- **Objetivo:** transformar `/familia-resgate/minha-caminhada/ranking` em uma visualização padrão de membro comum, preparada para permissões futuras, sem expor destaques jovens ou equipes dos Resgatados para quem não tem vínculo com esse módulo.
- **Acesso premium:** adicionada na tela principal `/familia-resgate/minha-caminhada` uma chamada compacta `Reconhecimento saudável`, com texto pastoral e botão real `Ver destaques da caminhada` apontando para `/familia-resgate/minha-caminhada/ranking`.
- **Link pequeno preservado:** o link menor do card `Destaques do mês` continua apontando para `/ranking` e mantém o texto `Ver destaques`.
- **Contexto temporário:** criado `viewerContext` em `MinhaCaminhadaArea.vue` simulando `profileType: member`, com `canSeeGeneralHighlights: true`, `canSeeYouthHighlights: false` e `canSeeYouthTeams: false`, preparado para backend/policies futuras.
- **Visibilidade padrão:** membro comum vê somente `Destaques Gerais da Igreja`, critérios gerais, áreas gerais de reconhecimento, `Reconhecimento com cuidado` e atalhos reais para Minha Caminhada, Regras, Histórico e Conquistas.
- **Ocultação jovem:** `Destaques Jovens dos Resgatados`, nomes jovens, critérios jovens, badges jovens e bloco explicativo dos Resgatados não são renderizados para membro comum.
- **Ocultação de equipes:** `Equipes dos Resgatados`, filtro `Equipes` e cards de equipe jovem não são renderizados para membro comum.
- **Filtros inteligentes:** filtros visíveis para membro comum ficaram limitados a `Todos`, `Gerais`, `Presença`, `Palavra`, `Serviço`, `Evangelismo` e `Conquistas`; `Resgatados` aparece apenas quando `canSeeYouthHighlights` for verdadeiro e `Equipes` apenas quando `canSeeYouthTeams` for verdadeiro.
- **Sem lista única:** mantida a separação técnica entre destaques gerais, jovens e equipes, sem restaurar lista única misturada.
- **Preparação futura:** jovem/resgatado poderá ver Geral + Jovem; participante/líder de equipe jovem poderá ver Equipes; responsáveis autorizados verão apenas filhos vinculados; secretaria/administração/pastor/liderança poderão ver tudo conforme policy.
- **Intercessão:** mantida apenas como área geral futura, com avaliação privada e cuidado pastoral; nenhum destaque fake de intercessor, nome de intercessor ou ranking de intercessores foi criado.
- **Visual premium:** reforçados hover suave, micro elevação, chips/filtros com estado ativo claro, sombras leves, bordas elegantes e destaque azul noturno no cuidado pastoral, sem animação contínua ou linguagem competitiva.
- **Limpeza:** removida dependência da lista única antiga, mantidos apenas mocks futuros condicionados por permissão, sem botões mortos, sem filtros jovens/equipes no DOM do membro comum e sem texto visual `Ver ranking`.
- **Escopo preservado:** sem sidebar, backend, migrations, controllers, rotas, arquivos novos, páginas aprovadas desfeitas ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/ranking`, `/regras`, `/mentor`, `/historico`, `/geral`, `/jovem`, `/mapa`, `/nivel` e `/conquistas`; `git diff --check` passou; busca por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git, padrões `heredoc/quote/dquote/bquote` e `Ver ranking` não encontrou ocorrências no escopo `MinhaCaminhada.vue`, `MinhaCaminhadaArea.vue` e `routes/web.php`; busca por lista única antiga `rankingHighlights`/`visibleRankingHighlights` também não encontrou resultados; status escopado conferido.
- **Commit:** nenhum commit realizado.

### Etapa estrutural — Documentação oficial da Minha Caminhada

- **Horário:** 22:10–22:35 aprox.
- **Objetivo:** criar documentação estrutural oficial para consolidar arquitetura, permissões, dados reais/mocks e checklist de aprovação da Minha Caminhada antes de novos ajustes visuais isolados.
- **Motivo:** evitar remendos, duplicidade, mocks soltos, regras espalhadas, páginas com permissões inconsistentes e exposição indevida de dados jovens, familiares, administrativos ou sensíveis.
- **Documentos criados:** `docs/modulos/minha-caminhada/01_ESTRUTURA_OFICIAL_MINHA_CAMINHADA.md`, `02_MAPA_DE_PERMISSOES_MINHA_CAMINHADA.md`, `03_MAPA_DE_DADOS_REAIS_E_MOCKS_MINHA_CAMINHADA.md` e `04_CHECKLIST_VISUAL_E_FUNCIONAL_MINHA_CAMINHADA.md`.
- **Estrutura oficial registrada:** objetivo pastoral do módulo, trilhos oficiais, separação obrigatória entre Caminhada Geral, Caminhada Jovem, Equipes dos Resgatados, Intercessão futura e Administração de regras de pontuação.
- **Mapa de permissões registrado:** membro comum, jovem/resgatado, participante de equipe jovem, responsável, líder jovem, secretaria/administração e pastor/liderança pastoral, com tabela por página.
- **Mapa de dados registrado:** mocks atuais e origem real futura para página principal, Destaques, Conquistas, Regras, Mentor, Histórico, Nível e Mapa, incluindo props Inertia esperadas, services prováveis, policies necessárias, fallback seguro e prioridade.
- **Checklist criado:** validações visuais, rotas, permissões, dados, pontuação, limpeza e requisitos antes de uso real.
- **Observações dos prints atuais:** `/ranking` ficou correto para membro comum; `/regras` ainda precisa adaptar visibilidade por perfil; `/mentor` ainda precisa adaptar visibilidade por perfil; `/conquistas` está ampla/genérica e precisa reorganização por tipo/permissão; a página principal ainda mostra jornada jovem no contexto comum e precisa controle por permissão futura.
- **Pendências oficiais registradas:** adaptar `/regras` por permissão; adaptar `/mentor` por permissão; reestruturar `/conquistas`; controlar jornada jovem na página principal conforme perfil; executar futura auditoria de mocks/dados reais.
- **Escopo preservado:** documentação apenas; sem alteração de código Vue, rotas, backend, migrations, controllers ou services.
- **Validações executadas:** `git diff --check` passou; conferência dos quatro documentos criados confirmou arquivos existentes e não vazios; status escopado conferido.
- **Commit:** nenhum commit realizado.

### Etapa — Página principal com jornada jovem controlada por perfil

- **Horário:** 22:22–22:50 aprox.
- **Objetivo:** ajustar `/familia-resgate/minha-caminhada` para simular membro comum por padrão, seguindo a documentação estrutural oficial da Minha Caminhada.
- **Contexto visual criado:** adicionado `viewerContext` temporário preparado para backend/policies, com `profileType: member`, `canSeeGeneralJourney: true`, `canSeeYouthJourney: false` e `canSeeYouthTeams: false`.
- **Comentário futuro:** registrado que o contexto deve vir das permissões reais, com membro comum vendo apenas Caminhada Geral, jovem/resgatado vendo Geral + Jovem, equipes jovens apenas no módulo jovem/para autorizados, responsáveis vendo filhos vinculados e administração/liderança conforme policy.
- **Resumo superior:** o bloco deixou de apresentar `Duas jornadas, pontos separados` para membro comum e passou a mostrar `Sua caminhada geral`, com texto focado em pontos gerais, nível atual e próximos passos na igreja.
- **Cards de jornada:** membro comum renderiza apenas `Caminhada Geral da Igreja`; o card jovem fica condicionado por `canSeeYouthJourney`.
- **Resumo lateral:** o título passa a `Resumo da Caminhada` para membro comum e exibe apenas Geral da Igreja, pontos gerais, nível geral e link para mapa geral.
- **Mapa:** membro comum vê apenas `Mapa geral`; botão/trilho do mapa jovem fica condicionado por `canSeeYouthJourney`, e a rota lateral usa fallback seguro para `/geral/mapa`.
- **Atividades recentes:** itens do trilho jovem foram removidos do estado comum; membro comum vê apenas presença, leitura bíblica, serviço, devocional e visitante acompanhado.
- **Conquistas/badges:** página principal passa a exibir apenas badges gerais no estado comum, incluindo Presença Fiel, Palavra Viva, Servo Disponível, Comunhão, Formação e Evangelismo; badges jovens ficam condicionados por permissão futura.
- **Mentor lateral:** deixou de mencionar jovem para membro comum e passou a usar mentor geral com constância em presença/serviço, devocional pessoal e Salmo 119:105.
- **Destaques do mês:** deixaram de exibir destaques jovens no estado comum e passaram a mostrar destaques gerais de constância e Palavra; link `Ver destaques` para `/ranking` foi preservado.
- **Próximos passos:** removido item jovem/Resgatados do estado comum; membro comum vê Palavra, Devocional, Serviço e Comunhão.
- **Limpeza:** removido `journeyAccess` antigo, convertidos dados visíveis para computeds baseados em `viewerContext`, eliminado texto antigo `Mentor IA` na página principal e renomeada variável interna para evitar padrão proibido `isYouth`.
- **Preparação futura:** mocks jovens continuam existindo apenas como preparação futura controlada por `canSeeYouthJourney`, sem renderizar para membro comum.
- **Pendências seguintes:** adaptar `/regras` por permissão; adaptar `/mentor` por permissão; reestruturar `/conquistas`; futura auditoria de mocks/dados reais.
- **Escopo preservado:** sem sidebar, backend, migrations, controllers, rotas, arquivos novos ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/ranking`, `/regras`, `/mentor` e `/conquistas`; `git diff --check` passou; buscas em `MinhaCaminhada.vue` por padrões proibidos e textos jovens antigos do contexto comum não retornaram resultados; status escopado conferido.
- **Commit:** nenhum commit realizado.

### Etapa — Regras da Caminhada por perfil e permissão

- **Horário:** 22:50–23:10 aprox.
- **Objetivo:** ajustar `/familia-resgate/minha-caminhada/regras` para simular membro comum por padrão, seguindo a mesma lógica já aplicada na página principal e em `/ranking`.
- **Contexto reaproveitado:** o `viewerContext` existente em `MinhaCaminhadaArea.vue` foi ampliado com `canSeeGeneralJourney: true` e `canSeeYouthJourney: false`, sem criar contexto duplicado.
- **Comentário futuro:** consolidado para registrar que o contexto deve vir de permissões reais, com membro comum vendo Caminhada Geral, jovem/resgatado vendo Geral + Jovem, equipes jovens apenas no módulo jovem/para autorizados, responsáveis vendo filhos vinculados e administração/liderança conforme policy.
- **Regras gerais:** membro comum vê apenas o card `Caminhada Geral da Igreja`, critérios gerais e chips de pontos, níveis e conquistas gerais, incluindo Intercessão quando aplicável.
- **Ocultação jovem:** o card grande `Caminhada Jovem dos Resgatados`, critérios jovens detalhados, pontos jovens, níveis jovens, conquistas jovens e mapa do trilho jovem não renderizam para membro comum.
- **Menção discreta:** criada nota `Trilho jovem próprio`, informando que os Resgatados possuem regras específicas no módulo jovem, sem parecer jornada ativa do membro comum.
- **Jornadas separadas:** texto adaptado para membro comum: Caminhada Geral possui pontos, níveis e conquistas próprias; trilhos jovens/equipes dos Resgatados pertencem ao módulo jovem e aparecem apenas para perfis autorizados.
- **Marcos/conquistas/validação:** textos ajustados para Caminhada Geral; `Mapa geral` permanece visível, `Mapa do trilho jovem` fica condicionado por `canSeeYouthJourney`, e validação cita Secretaria ou liderança.
- **Botões finais:** preservados atalhos reais para Minha Caminhada, Histórico, Mentor e Conquistas, com adição de `Ver mapa geral` apontando para `/familia-resgate/minha-caminhada/geral/mapa`.
- **Visual premium:** a grade de regras foi ajustada para manter card geral maior e nota jovem discreta, com borda tracejada e sem deixar a página pobre ao ocultar o card jovem completo.
- **Preparação futura:** os dados jovens continuam no arquivo como preparação futura, mas são controlados por `canSeeYouthJourney` e não renderizam para membro comum.
- **Pendências seguintes:** adaptar `/mentor` por permissão; reestruturar `/conquistas`; futura auditoria de mocks/dados reais.
- **Escopo preservado:** sem sidebar, backend, migrations, controllers, rotas, arquivos novos ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas; `git diff --check` passou; busca por padrões proibidos em `MinhaCaminhadaArea.vue` não encontrou resultados; busca por textos jovens encontrou apenas mocks/preparação futura condicionada por permissões e outras branches já existentes, não renderizadas para membro comum em `/regras`; status escopado conferido.
- **Observação:** uma tentativa inicial com `php artisan route-list` falhou por nome incorreto do comando; a validação oficial foi repetida corretamente com `route:list` e passou.
- **Commit:** nenhum commit realizado.

### Etapa — Mentor da Caminhada por perfil e permissão

- **Horário:** 23:10–23:30 aprox.
- **Objetivo:** ajustar `/familia-resgate/minha-caminhada/mentor` para simular membro comum por padrão, exibindo orientação apenas da Caminhada Geral.
- **Contexto reaproveitado:** o `viewerContext` existente em `MinhaCaminhadaArea.vue` foi mantido como fonte única, com `profileType: member`, `canSeeGeneralJourney: true`, `canSeeYouthJourney: false` e `canSeeYouthTeams: false`.
- **Comentário futuro:** reforçado que o contexto deve vir de permissões reais e que o Mentor não substitui pastor, liderança, discipulado ou aconselhamento pastoral.
- **Hero:** o chip foi ajustado para `Apoio pastoral`, e o subtítulo passou a falar de orientação simples e pastoral baseada na caminhada geral, registros e próximos passos.
- **Leitura da semana:** ajustada para membro comum, com constância em presença/serviço, fortalecimento de devocional e Palavra, ritmo estável e próximo marco `Semente da Palavra`.
- **Como funciona:** texto ajustado para explicar que o Mentor usa registros da caminhada geral, considera presença, Palavra, devocional, serviço, comunhão, evangelismo e conquistas, opera por regras pastorais e respostas pré-aprovadas, sem IA externa real nesta primeira fase.
- **Plano personalizado:** mantido geral, com leitura bíblica diária, registro de devocionais, disponibilidade em escala/serviço e participação em comunhão da igreja.
- **Cards de jornada:** membro comum vê apenas `Caminhada Geral`; o card `Caminhada Jovem` permanece como preparação futura, mas condicionado por `viewerContext.canSeeYouthJourney`.
- **Perguntas guiadas:** ajustadas para perguntas gerais sobre constância, caminhada geral, leitura bíblica, próximo passo e serviço com equilíbrio.
- **Cuidado pastoral:** fortalecido com lista de limites: não substitui pastor/liderança/discipulado/aconselhamento, não diagnostica emoções, não trata crises graves sozinho, não julga espiritualidade, não acusa falta de fé, não promete resultado espiritual e não expõe comparações.
- **Ocultação jovem:** membro comum não recebe orientação jovem, desafio jovem, devocional jovem, presença nos Resgatados, pontos jovens, mapa jovem ou equipe jovem no `/mentor`.
- **Pendências seguintes:** reestruturar `/conquistas`; futura auditoria de mocks/dados reais.
- **Escopo preservado:** sem sidebar, backend, migrations, controllers, rotas, arquivos novos ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas; `git diff --check` passou; busca por padrões proibidos em `MinhaCaminhadaArea.vue` não encontrou resultados; busca por textos jovens encontrou apenas mocks/preparação futura condicionada por permissões e outras branches já existentes, com o card jovem do mentor condicionado por `canSeeYouthJourney`; status escopado conferido via `git ls-files`.
- **Commit:** nenhum commit realizado.

### Etapa — Conquistas da Caminhada por perfil e permissão

- **Horário:** 23:30–23:58 aprox.
- **Objetivo:** reestruturar `/familia-resgate/minha-caminhada/conquistas` para o perfil padrão de membro comum, seguindo a documentação estrutural oficial da Minha Caminhada e o modelo de permissões já aplicado em página principal, Destaques, Regras e Mentor.
- **Arquivo alterado:** `resources/js/Pages/FamiliaResgate/MinhaCaminhadaConquistas.vue`.
- **Contexto temporário:** criado `viewerContext` local com `profileType: member`, `canSeeGeneralAchievements: true` e permissões restritas desligadas para trilhas complementares, equipes, administração, financeiro e registros sensíveis.
- **Preparação futura:** registrados comentários técnicos para backend/policies futuras, deixando claro que o contexto deve vir das permissões reais e que o frontend não deve receber conquistas restritas quando o perfil não tiver permissão.
- **Estados organizados:** os mocks passaram a usar tipos e estados explícitos, incluindo `general`, `youth`, `youth_team`, `administrative`, `financial`, `pastoral_sensitive`, `received`, `in_progress`, `pending_validation`, `locked` e `hidden`.
- **Visibilidade de membro comum:** a tela renderiza somente conquistas gerais pessoais recebidas, em progresso, em validação ou próximas; itens restritos permanecem no mock apenas como preparação futura e não aparecem para membro comum.
- **Estrutura visual criada:** a página passou a ter hero premium, resumo superior, filtros simples, seção de conquistas recebidas, seção em progresso, categorias gerais, explicação de funcionamento, cuidado pastoral e atalhos reais.
- **Categorias gerais:** organizadas Presença, Palavra, Devocional, Serviço, Comunhão, Evangelismo, Formação e Intercessão futura com sigilo e validação pastoral.
- **Filtros por permissão:** membro comum vê apenas `Todas`, `Recebidas`, `Em progresso`, `Próximas` e `Categorias`; filtros restritos só entram no DOM se o `viewerContext` permitir.
- **Cuidado pastoral:** reforçado que conquistas não são troféus de espiritualidade, comparação, disputa de serviço ou prova de valor diante de Deus; servem para lembrar constância, celebrar marcos e incentivar próximos passos.
- **Botões reais:** atalhos finais apontam para Minha Caminhada, Histórico, Regras, Mentor e Destaques, sem `href="#"`, `to="#"` ou `javascript:void(0)`.
- **Limpeza:** a versão anterior ampla/genérica foi substituída por uma estrutura limpa, sem lista misturada, sem exposição visual de áreas restritas para membro comum e sem textos inadequados de competição ou pontuação tóxica.
- **Escopo preservado:** sem sidebar, backend, migrations, controllers, rotas, arquivos novos, alteração de file structure ou commit.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas, incluindo `/conquistas`; `git diff --check` passou; buscas por `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git, padrões `heredoc/quote/dquote/bquote` e textos sensíveis no escopo de `MinhaCaminhada*.vue`, `routes` e `MinhaCaminhadaConquistas.vue` não encontraram problemas após correção da expressão de busca.
- **Commit:** nenhum commit realizado.

### Auditoria final visual e estrutural — Minha Caminhada completa

- **Horário:** 23:58–00:18 aprox.
- **Objetivo:** auditar visual e estruturalmente a Minha Caminhada completa, sem backend, sem migrations, sem controllers, sem rotas novas, sem sidebar, sem arquivos novos de implementação, sem redesenho e sem commit.
- **Arquivos auditados:** `MinhaCaminhada.vue`, `MinhaCaminhadaArea.vue`, `MinhaCaminhadaConquistas.vue`, `MinhaCaminhadaNivel.vue`, `routes/web.php`, os quatro documentos em `docs/modulos/minha-caminhada/` e este histórico diário.
- **Páginas auditadas:** `/familia-resgate/minha-caminhada`, `/ranking`, `/regras`, `/mentor`, `/conquistas`, `/historico`, `/nivel`, mapas geral/jovem e níveis geral/jovem.
- **Correção pequena em `/nivel`:** removido o mock local `currentLevelMock.hasYouthJourney` e a página `Meu Nível Atual` passou a usar `viewerContext.canSeeYouthJourney`, evitando renderizar card, aviso e botão de mapa jovem para membro comum.
- **Correção pequena em `/historico`:** filtros, cards de resumo, stats, eventos e atalho para caminhada jovem passaram a respeitar `viewerContext.canSeeYouthJourney`; membro comum vê histórico geral, filtro geral e atalhos gerais.
- **Documentação ajustada:** `01_ESTRUTURA_OFICIAL_MINHA_CAMINHADA.md` e `03_MAPA_DE_DADOS_REAIS_E_MOCKS_MINHA_CAMINHADA.md` foram atualizados para refletir que página principal, Regras, Mentor e Conquistas já foram ajustados por perfil no protótipo visual.
- **Página principal:** confirmada com membro comum vendo Caminhada Geral, mapa geral, atividades gerais, badges gerais, mentor geral, destaques gerais, próximos passos gerais e card `Reconhecimento saudável` apontando para `/ranking`.
- **Destaques:** confirmada visualização de membro comum com Destaques Gerais, filtros sem Resgatados/Equipes, sem lista única misturada e sem linguagem visual de ranking espiritual.
- **Regras:** confirmada visualização geral para membro comum, com nota discreta sobre trilhos autorizados, botões reais e mapa jovem condicionado por permissão.
- **Mentor:** confirmado como apoio pastoral por regras, leitura geral, plano geral, perguntas gerais, cuidado pastoral forte e sem promessa de IA externa real.
- **Conquistas:** confirmada visualização de conquistas gerais pessoais, recebidas/em progresso/categorias gerais, com conquistas restritas ocultas por padrão e filtros coerentes para membro comum.
- **Níveis e mapas:** confirmadas rotas de nível geral e mapa geral; rotas jovens permanecem preparadas, mas as telas de membro comum não empurram o usuário para mapa jovem.
- **Rotas e botões:** rotas principais existem e botões auditados apontam para destinos reais, sem `href="#"`, `to="#"` ou `javascript:void(0)`.
- **Buscas obrigatórias:** não foram encontrados padrões proibidos `href="#"`, `to="#"`, `javascript:void(0)`, `isYouth`, `Pontuação saudável`, conflitos Git, `heredoc`, `quote>`, `dquote>`, `bquote>`, `Ver ranking` ou `ranking espiritual` nos arquivos auditados de código/rotas.
- **Textos jovens/sensíveis:** ocorrências restantes de Resgatados, pontos jovens, mapa jovem, Bíblia na Mão, Chama Jovem, Desafio Cumprido e equipes aparecem em mocks/preparação futura e branches condicionadas por `viewerContext`; após a correção, não renderizam para membro comum nas telas principais auditadas.
- **Conferência documental:** os quatro documentos estruturais existem, são não vazios e foram conferidos; trechos desatualizados sobre pendências já resolvidas foram ajustados.
- **Validações executadas:** `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` passou e listou 19 rotas; `git diff --check` passou antes da atualização final do histórico; conferência `test -s` dos quatro documentos passou; status escopado funcionou.
- **Status escopado:** `docs/HISTORICO_DIARIO_PROJETO_MINISTERIO_RESGATE.md` e `routes/web.php` seguem modificados; `docs/modulos/minha-caminhada/` e os arquivos `MinhaCaminhada*.vue` seguem não rastreados por serem parte da etapa visual em andamento.
- **Pendências oficiais restantes:** auditoria futura de mocks/dados reais, integração com backend/services/policies, substituição gradual de mocks por props reais, permissões reais no servidor e auditoria de payload Inertia antes de uso real.
- **Commit:** nenhum commit realizado.

### Etapa — Plano técnico de backend da Minha Caminhada

- **Horário:** 00:13–00:35 aprox.
- **Objetivo:** criar documento técnico para orientar a implementação futura do backend real da Minha Caminhada após a conclusão, auditoria, commits e push da etapa visual/preparatória.
- **Arquivo criado:** `docs/modulos/minha-caminhada/05_PLANO_TECNICO_BACKEND_MINHA_CAMINHADA.md`.
- **Documentos consultados:** estrutura oficial, mapa de permissões, mapa de dados reais/mocks, checklist visual/funcional e base de estudo sobre pontuação, destaques, intercessão, mocks e conferência antes de novas etapas.
- **Conteúdo registrado:** visão geral pastoral, trilhos oficiais, princípio de segurança, perfis e permissões, tabelas sugeridas, models, relacionamentos, services, policies, props Inertia por página, dados que nunca devem ser enviados sem permissão, fluxos de pontuação, conquistas, destaques, mentor e histórico, testes necessários, ordem segura de implementação e checklist antes de implementar.
- **Escopo preservado:** nenhum código funcional foi implementado; sem migrations, models, controllers, services, policies, rotas, Vue ou alterações de banco.
- **Pendências oficiais:** revisar banco atual antes de migrations, evitar duplicidade com estruturas existentes, criar migrations depois, criar policies, criar services, substituir mocks gradualmente por props reais, criar testes e auditar payload Inertia por perfil antes de uso real.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Auditoria do banco atual da Minha Caminhada

- **Horário:** 00:35–00:55 aprox.
- **Objetivo:** registrar oficialmente a auditoria do banco/projeto atual antes de criar migrations da Minha Caminhada, evitando duplicidade e identificando estruturas reaproveitáveis e lacunas reais.
- **Arquivo criado:** `docs/modulos/minha-caminhada/06_AUDITORIA_BANCO_ATUAL_MINHA_CAMINHADA.md`.
- **Escopo da auditoria:** somente leitura, sem migrations, sem alteração de banco, sem alteração de código/backend, sem Vue, sem rotas, sem seeders, sem commit e sem push.
- **Migrations auditadas:** registradas as migrations atuais de usuários, pessoas, famílias, responsáveis, departamentos, logs, alertas, perfis/permissões e financeiro, todas com status `Ran` no `migrate:status`.
- **Models encontrados:** documentados `Person`, `User`, `Family`, `FamilyMember`, `GuardianShip`, `Department`, `DepartmentPerson`, `ActivityLog`, `SystemAlert`, models financeiros, perfis e permissões.
- **Achados principais:** `Person` é a base principal da Minha Caminhada; `families`, `family_members` e `guardianships` devem ser reaproveitados para família/responsáveis; `departments` e `department_people` parecem ser a base atual mais próxima para Jovens/Resgatados; estruturas financeiras já existem e não devem ser duplicadas; `activity_logs` serve para auditoria administrativa, mas não deve ser tratado automaticamente como histórico visível da caminhada.
- **Permissões:** `access_profiles`, `permissions` e métodos do `User` já existem; o futuro `WalkingPermissionService` deve usar essa base e evitar sistema paralelo de permissões.
- **Rotas/API:** rotas `familia-resgate` foram auditadas parcialmente; Minha Caminhada ainda usa rotas Inertia/closures em `routes/web.php`; `routes/api.php` mostrou apenas `/api/health`; ainda não existe API real da Minha Caminhada.
- **Tabelas registradas:** documentadas as tabelas do schema `ministerio_resgate`, com observação de que `Schema::getTableListing()` também retornou tabelas de outros schemas como `Exercicios` e `Exercicios_b_2`.
- **Riscos identificados:** duplicar jovens/equipes, duplicar responsáveis, duplicar financeiro, confundir `activity_logs` com histórico visível, criar pontuação sem origem real de presença/eventos e criar permissões paralelas.
- **Recomendação técnica:** ainda não é seguro criar migrations até decidir como Resgatados estão cadastrados em `departments`, se equipes jovens serão derivadas de `departments/department_people` ou tabelas novas, se `walking_history_events` será separado ou integrado com `activity_logs`, qual será a origem real de presença/eventos, quais foreign keys serão usadas e como `WalkingPermissionService` usará perfis/permissões existentes.
- **Limitações registradas:** alguns comandos travaram por I/O, `route:list` de API não completou, algumas saídas foram truncadas e nem todas as migrations/controllers/policies foram lidas integralmente; o documento foi produzido com leitura seletiva e comandos leves/escopados.
- **Implementação:** nada foi implementado; esta etapa foi exclusivamente documental.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Decisão técnica das migrations da Minha Caminhada

- **Horário:** 01:05–01:25 aprox.
- **Objetivo:** registrar a decisão técnica final para orientar as futuras migrations base da Minha Caminhada, antes de qualquer criação de migration real.
- **Arquivo criado:** `docs/modulos/minha-caminhada/07_DECISAO_TECNICA_MIGRATIONS_MINHA_CAMINHADA.md`.
- **Documentos consultados:** plano técnico de backend, auditoria do banco atual, mapa de permissões e mapa de dados reais/mocks da Minha Caminhada.
- **Decisão Resgatados/Jovens:** reaproveitar `departments` e `department_people` como base atual para identificar vínculos com Resgatados, usando `category` para `junior` e `jovem` e campos de liderança como `role`, `is_leader`, `is_assistant` e `can_manage_department`.
- **Decisão equipes jovens:** não criar `youth_teams` nem `youth_team_members` na primeira fase; equipes jovens ficam como pendência futura até a experiência real dos jovens ser definida.
- **Decisão histórico:** criar futuramente `walking_history_events` separado de `activity_logs`; `activity_logs` permanece como auditoria administrativa/técnica, não como timeline visível principal da caminhada.
- **Decisão presença/eventos:** não criar tabela própria de presença/eventos dentro da Minha Caminhada nesta fase; `walking_point_logs` deverá ter `source_type` e `source_id` nullable para aceitar origens reais futuras.
- **Decisão intercessão:** tratar intercessão inicialmente apenas como categoria em regras/conquistas, sem tabela própria, sem ranking de intercessores e sem exposição de atendimentos pessoais.
- **Decisão financeiro:** não criar tabelas financeiras nem conquistas financeiras públicas; qualquer uso futuro deverá ser privado, discreto, com policy forte e sem gamificação pública.
- **Decisão permissões:** não criar sistema paralelo de permissões; o futuro `WalkingPermissionService` deverá usar `User`, `Person`, `AccessProfile`, `Permission`, `GuardianShip` e `DepartmentPerson`.
- **Tabelas da primeira fase definidas:** `walking_journeys`, `walking_levels`, `walking_point_rules`, `walking_point_logs`, `walking_achievements`, `person_walking_achievements`, `walking_highlights`, `walking_mentor_response_templates`, `walking_mentor_response_logs` e `walking_history_events`.
- **Tabelas adiadas/proibidas na primeira fase:** `youth_teams`, `youth_team_members`, presença/eventos, financeiro, intercessão própria e avaliações privadas de intercessão.
- **Campos e foreign keys:** documento definiu campos recomendados para cada tabela, referências para `people`, `users`, `families` e tabelas `walking_*`, além de preferência por preservar logs/históricos sem cascades destrutivos.
- **Ordem futura das migrations:** definida sequência de criação começando por `walking_journeys` e encerrando com `walking_history_events`.
- **Escopo preservado:** sem migrations criadas, sem alteração de banco, sem models, controllers, services, policies, rotas, Vue, seeders, commit ou push.

### Etapa — Migrations base da Minha Caminhada criadas

- **Horário:** 01:17–01:45 aprox.
- **Objetivo:** criar somente as migrations base da primeira fase da Minha Caminhada, conforme a decisão técnica oficial registrada no documento `07_DECISAO_TECNICA_MIGRATIONS_MINHA_CAMINHADA.md`.
- **Status inicial:** `git status --short` retornou limpo antes da criação; busca por migrations parecidas com `walking`, `journey`, `level`, `point`, `achievement`, `highlight`, `mentor`, `history`, `caminhada`, `conquista`, `pontuacao` e `destaque` não encontrou arquivos existentes.
- **Migrations criadas:** `create_walking_journeys_table`, `create_walking_levels_table`, `create_walking_point_rules_table`, `create_walking_point_logs_table`, `create_walking_achievements_table`, `create_person_walking_achievements_table`, `create_walking_highlights_table`, `create_walking_mentor_response_templates_table`, `create_walking_mentor_response_logs_table` e `create_walking_history_events_table`.
- **Ordem ajustada:** os timestamps dos arquivos recém-criados foram organizados para preservar a ordem oficial das dependências, evitando que logs fossem executados antes de rules/templates.
- **Escopo das tabelas:** jornadas, níveis, regras de pontos, logs de pontos, catálogo de conquistas, conquistas por pessoa, destaques, templates do mentor, logs do mentor e histórico visível da caminhada.
- **Tabelas não criadas:** não foram criadas `youth_teams`, `youth_team_members`, tabela de presença/eventos, tabela financeira, tabela própria de intercessão ou tabela de avaliações privadas de intercessão.
- **Cuidados técnicos:** migrations incluem comentários didáticos por bloco, índices principais, uniques definidos no documento 07, foreign keys para `people`, `users`, `families` e tabelas `walking_*`, e uso de `restrictOnDelete`/`nullOnDelete` conforme preservação histórica.
- **Ajuste preventivo:** a FK `walking_mentor_response_template_id` em `walking_mentor_response_logs` recebeu nome curto para evitar limite de identificador em bancos como MySQL.
- **Validações executadas:** lint PHP em todas as migrations `walking` passou com `No syntax errors detected`; listagem confirmou exatamente 10 migrations `walking`; busca por tabelas/colunas proibidas (`youth_teams`, `youth_team_members`, `youth_team_id`, `team_points`, `counts_for_team`, presença, attendance, financeiro e intercessão própria) não encontrou resultados; `git diff --check` passou; `php artisan migrate:status --no-ansi` mostrou as 10 migrations novas como `Pending`.
- **Importante:** as migrations foram criadas, mas ainda não foram executadas; nenhuma alteração de banco foi aplicada.
- **Escopo preservado:** não foram criados models, controllers, services, policies, seeders, rotas ou arquivos Vue; mocks não foram substituídos; nenhum dado real foi alterado.
- **Pendência próxima:** revisar as migrations criadas e só depois rodar `migrate` mediante aprovação explícita.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Revisão das migrations base da Minha Caminhada antes do migrate

- **Horário:** 01:29–01:42 aprox.
- **Objetivo:** revisar as 10 migrations base da Minha Caminhada antes de qualquer execução no banco, conferindo ordem, nomes, campos, enums, foreign keys, indexes, uniques, `down()`, comportamento de delete, constraints longas, ausência de tabelas proibidas e status `Pending`.
- **Migrations revisadas:** `walking_journeys`, `walking_levels`, `walking_point_rules`, `walking_point_logs`, `walking_achievements`, `person_walking_achievements`, `walking_highlights`, `walking_mentor_response_templates`, `walking_mentor_response_logs` e `walking_history_events`.
- **Ordem confirmada:** as migrations estão ordenadas de `walking_journeys` até `walking_history_events`, preservando dependências entre jornadas, regras, logs, conquistas, mentor e histórico.
- **Campos confirmados:** os campos definidos seguem o documento 07, incluindo enums oficiais, flags de ativação, `metadata` JSON, timestamps, `source_type/source_id` nullable, períodos de destaque e visibilidade por policy futura.
- **Foreign keys confirmadas:** `person_id` aponta para `people`, usuários de ação apontam para `users`, `family_id` aponta para `families`, e chaves `walking_*` apontam para as tabelas da Minha Caminhada; registros históricos/pontuação usam `restrictOnDelete` ou `nullOnDelete`, sem cascade destrutivo perigoso.
- **Correção técnica realizada:** em `person_walking_achievements`, o unique com `walking_journey_id` nullable foi ajustado para usar a coluna gerada `walking_journey_unique_id`, preservando unicidade em MySQL quando a jornada for nula.
- **Constraint longa conferida:** `walking_mentor_response_template_id` em `walking_mentor_response_logs` usa foreign key nomeada `wmrl_template_fk`, evitando limite de identificador em MySQL.
- **Tabelas/colunas proibidas:** busca por `youth_teams`, `youth_team_members`, `team_points`, `counts_for_team`, `attendance`, `presence`, `intercession_evaluation` e `financial_` não retornou ocorrências nas migrations `walking`.
- **Validações executadas:** lint PHP em todas as migrations `walking` passou; `git diff --check` passou; `php artisan migrate:status --no-ansi` mostrou as 10 migrations da Minha Caminhada como `Pending`.
- **Importante:** `migrate` não foi executado; nenhuma tabela foi criada no banco nesta etapa.
- **Escopo preservado:** não foram criados models, controllers, services, policies, seeders, rotas ou arquivos Vue; nenhum dado real foi alterado.
- **Pendência próxima:** se aprovado, rodar `php artisan migrate` em etapa separada e explícita.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Execução das migrations base da Minha Caminhada

- **Horário:** 01:42–01:50 aprox.
- **Objetivo:** executar `php artisan migrate` após a revisão das migrations base da Minha Caminhada.
- **Primeira execução:** `php artisan migrate` aplicou com sucesso as cinco primeiras migrations (`walking_journeys`, `walking_levels`, `walking_point_rules`, `walking_point_logs` e `walking_achievements`), mas falhou em `person_walking_achievements`.
- **Erro encontrado:** MySQL retornou `SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint` ao tentar criar a FK de `walking_journey_id` com `on delete set null` em uma tabela que também usava coluna gerada baseada em `walking_journey_id`.
- **Diagnóstico:** `migrate:status` mostrou as cinco primeiras migrations como `Ran` e as demais como `Pending`; a tabela `person_walking_achievements` havia sido criada parcialmente pelo MySQL, mas ainda sem migration registrada.
- **Conferência da tabela parcial:** a tabela parcial `person_walking_achievements` estava vazia, com `0` registros.
- **Correção técnica autorizada:** a migration `create_person_walking_achievements_table` foi ajustada para usar `restrictOnDelete()` em `walking_journey_id`, evitando o erro de FK com coluna gerada no MySQL.
- **Ação corretiva autorizada:** foi removida somente a tabela parcial e vazia `person_walking_achievements`; nenhuma outra tabela foi dropada.
- **Reexecução:** após remover a tabela parcial vazia, foi executado apenas `php artisan migrate`, aplicando com sucesso as cinco migrations restantes (`person_walking_achievements`, `walking_highlights`, `walking_mentor_response_templates`, `walking_mentor_response_logs` e `walking_history_events`).
- **Resultado final:** `php artisan migrate:status --no-ansi` mostrou as 10 migrations da Minha Caminhada como `Ran`; as cinco primeiras ficaram no batch 15 e as cinco restantes no batch 16.
- **Tabelas conferidas:** consulta `SHOW TABLES LIKE '%walking%'` confirmou 10 tabelas: `person_walking_achievements`, `walking_achievements`, `walking_highlights`, `walking_history_events`, `walking_journeys`, `walking_levels`, `walking_mentor_response_logs`, `walking_mentor_response_templates`, `walking_point_logs` e `walking_point_rules`.
- **Escopo preservado:** não foram executados `migrate:fresh`, `migrate:refresh`, `db:wipe` ou seeders; não foram criados models, controllers, services, policies, seeders, rotas ou Vue.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Models e relacionamentos da Minha Caminhada

- **Horário:** 01:47–02:00 aprox.
- **Objetivo:** criar somente os Models Eloquent da Minha Caminhada e seus relacionamentos básicos, sem controllers, services, policies, seeders, rotas, Vue ou novas migrations.
- **Status inicial:** `git status --short` retornou limpo; busca em `app/Models` por nomes relacionados a `Walking`, `Achievement`, `Mentor`, `Highlight`, `Journey`, `Point` e `History` não encontrou Models existentes antes da criação.
- **Models criados:** `WalkingJourney`, `WalkingLevel`, `WalkingPointRule`, `WalkingPointLog`, `WalkingAchievement`, `PersonWalkingAchievement`, `WalkingHighlight`, `WalkingMentorResponseTemplate`, `WalkingMentorResponseLog` e `WalkingHistoryEvent`.
- **Implementação dos Models:** foram adicionados `fillable`, `casts`, comentários didáticos e relacionamentos Eloquent básicos conforme as tabelas da primeira fase da Minha Caminhada.
- **Relacionamentos em `Person`:** adicionados `walkingPointLogs`, `walkingAchievements`, `walkingHighlights`, `walkingMentorResponseLogs` e `walkingHistoryEvents`.
- **Relacionamentos em `User`:** adicionados `createdWalkingPointLogs`, `approvedWalkingPointLogs`, `rejectedWalkingPointLogs`, `awardedWalkingAchievements`, `generatedWalkingHighlights` e `approvedWalkingHighlights`.
- **Relacionamentos em `Family`:** adicionados `walkingPointLogs`, `walkingHighlights` e `walkingMentorResponseLogs`.
- **Validações executadas:** lint PHP passou para os 10 Models novos e para `Person`, `User` e `Family`; testes `class_exists` via Tinker retornaram `true` para `WalkingJourney`, `WalkingPointLog`, `WalkingAchievement`, `WalkingMentorResponseLog` e `WalkingHistoryEvent`; `php artisan migrate:status --no-ansi` confirmou as 10 migrations da Minha Caminhada como `Ran`; `git diff --check` passou.
- **Escopo preservado:** não foram criadas migrations, controllers, services, policies, seeders, rotas ou arquivos Vue; mocks não foram substituídos; nenhum comando `migrate`, `migrate:fresh`, `db:wipe` ou seeder foi executado nesta etapa.
- **Pendência próxima:** criar seeders mínimos ou policies da Minha Caminhada em etapa separada, conforme aprovação.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Seeders mínimos da Minha Caminhada

- **Horário:** 02:20–02:35 aprox.
- **Objetivo:** criar seeders mínimos e idempotentes para dados-base/catálogo da Minha Caminhada, sem criar dados pessoais, pontos reais, conquistas atribuídas, destaques, histórico fake ou logs do mentor.
- **Status inicial:** `git status --short` retornou limpo; busca por seeders relacionados a `Walking`, `Caminhada`, `Journey`, `Level`, `Point`, `Achievement` e `Mentor` não encontrou seeders existentes antes da criação.
- **Seeders criados:** `WalkingJourneySeeder`, `WalkingLevelSeeder`, `WalkingPointRuleSeeder`, `WalkingAchievementSeeder` e `WalkingMentorResponseTemplateSeeder`.
- **DatabaseSeeder:** atualizado no padrão existente de `$this->call([...])`, registrando os seeders da Minha Caminhada na ordem `WalkingJourneySeeder`, `WalkingLevelSeeder`, `WalkingPointRuleSeeder`, `WalkingAchievementSeeder` e `WalkingMentorResponseTemplateSeeder`.
- **Execução realizada:** não foi executado `php artisan db:seed` geral; foram executados somente os seeders específicos da Minha Caminhada, um por vez e na ordem correta.
- **Dados-base criados:** 2 jornadas oficiais (`general` e `youth`), 40 níveis, 12 regras de pontuação, 13 conquistas de catálogo e 22 templates pré-aprovados do mentor.
- **Segurança de dados:** as tabelas `walking_point_logs`, `person_walking_achievements`, `walking_highlights`, `walking_mentor_response_logs` e `walking_history_events` permaneceram com `0` registros.
- **Validações executadas:** lint PHP passou para os 5 seeders novos e para `DatabaseSeeder`; testes `class_exists` via Tinker retornaram `true` para todos os seeders novos; `php artisan migrate:status --no-ansi` confirmou as 10 migrations da Minha Caminhada como `Ran`; `git diff --check` passou.
- **Escopo preservado:** não foram criadas migrations, controllers, services, policies, rotas ou arquivos Vue; não foram executados `migrate`, `migrate:fresh`, `db:wipe` ou seeders gerais; mocks não foram substituídos.
- **Pendência próxima:** criar policies ou services da Minha Caminhada em etapa separada, conforme aprovação.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Policies e permissões base da Minha Caminhada

- **Horário:** 02:44–03:05 aprox.
- **Objetivo:** criar a base inicial de autorização backend da Minha Caminhada antes de controllers/services de negócio e antes da substituição de mocks por dados reais.
- **Estrutura reaproveitada:** confirmado uso do sistema existente `AccessProfile`/`Permission`, com permissões por `slug`, perfis por `access_profile_user` e verificação via `User::hasPermission()`; não foi criado sistema paralelo de permissões.
- **Seeder de permissões criado:** `WalkingPermissionSeeder`, idempotente com `updateOrCreate` por `slug`, usando os campos reais `uuid`, `name`, `slug`, `group`, `description`, `is_system`, `is_active` e `sort_order`.
- **Permissões walking criadas:** `walking.view.general`, `walking.view.youth`, `walking.view.family_child`, `walking.view.sensitive`, `walking.manage.rules`, `walking.validate.points`, `walking.approve.highlights`, `walking.manage.mentor_templates`, `walking.view.leadership_dashboard`, `walking.view.youth_leadership` e `walking.view.pastoral`.
- **DatabaseSeeder:** registrado `WalkingPermissionSeeder` no padrão existente de `$this->call([...])`; a execução realizada foi apenas `php artisan db:seed --class=WalkingPermissionSeeder`, sem rodar `db:seed` geral.
- **Service de autorização criado:** `app/Services/MinhaCaminhada/WalkingAuthorizationService.php`, focado somente em autorização, usando `User`, `Person`, `GuardianShip`, `DepartmentPerson` e permissões existentes; não inclui regra de pontuação, dashboard ou leitura de dados.
- **Policies criadas:** `WalkingJourneyPolicy`, `WalkingPointLogPolicy`, `WalkingAchievementPolicy`, `PersonWalkingAchievementPolicy`, `WalkingHighlightPolicy`, `WalkingMentorResponseTemplatePolicy`, `WalkingMentorResponseLogPolicy` e `WalkingHistoryEventPolicy`.
- **Registro de policies:** não foi criado nem alterado provider; o projeto não possui `AuthServiceProvider` e segue discovery automático/nomeação convencional de policies do Laravel.
- **Validações executadas:** lint PHP passou para o service, as 8 policies, `WalkingPermissionSeeder` e `DatabaseSeeder`; testes `class_exists` retornaram `true` para o service, policies e seeder; o seeder criou 11 permissões `walking.%`; `php artisan migrate:status --no-ansi` confirmou as 10 migrations da Minha Caminhada como `Ran`; `git diff --check` passou.
- **Escopo preservado:** não foram criadas migrations, controllers, services de pontuação/dashboard, rotas ou arquivos Vue; não foram executados `migrate`, `migrate:fresh`, `db:wipe` ou seeders gerais; não foram criados dados fake para pessoas reais.
- **Pendência próxima:** criar services de leitura segura da Minha Caminhada em etapa separada, respeitando as policies e o `WalkingAuthorizationService`.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Services de leitura segura da Minha Caminhada

- **Horário:** 03:00–03:15 aprox.
- **Objetivo:** criar services de leitura segura para preparar a futura troca de mocks por dados reais, sem controllers, rotas, Vue ou alterações de banco.
- **Estado inicial:** `git status --short` retornou limpo; busca em `app/Services` confirmou que existia apenas `WalkingAuthorizationService.php` relacionado à Minha Caminhada.
- **Services criados:** `WalkingLevelService`, `WalkingProgressService`, `WalkingAchievementReadService`, `WalkingMentorReadService` e `WalkingDashboardReadService`.
- **Autorização reaproveitada:** os services usam `WalkingAuthorizationService` para impedir leitura de dados de outra pessoa, dados jovens ou dados sensíveis sem permissão.
- **Comportamento seguro:** os services retornam arrays simples preparados para futura Inertia, não retornam Models crus, não criam/alteram/deletam registros, não inventam pontos, conquistas, histórico ou destaques e usam vazio/null/false quando não autorizado.
- **Validações executadas:** lint PHP passou para os 5 services novos e para `WalkingAuthorizationService`; testes `class_exists` retornaram `true` para os 5 services novos; consultas somente leitura confirmaram catálogos preservados (`WalkingJourney=2`, `WalkingLevel=40`, `WalkingPointRule=12`, `WalkingAchievement=13`, `WalkingMentorResponseTemplate=22`) e tabelas operacionais vazias (`WalkingPointLog=0`, `PersonWalkingAchievement=0`, `WalkingHighlight=0`, `WalkingMentorResponseLog=0`, `WalkingHistoryEvent=0`); `php artisan migrate:status --no-ansi` confirmou as 10 migrations da Minha Caminhada como `Ran`; `git diff --check` passou.
- **Escopo preservado:** não foram criadas migrations, controllers, rotas, arquivos Vue, jobs ou seeders; não foram executados `migrate`, `migrate:fresh`, `db:wipe` ou seeders; mocks não foram substituídos.
- **Pendência próxima:** criar controllers/Inertia props ou testes unitários para os services de leitura segura, conforme decisão.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Testes dos services da Minha Caminhada

- **Horário:** 03:17–03:35 aprox.
- **Objetivo:** criar testes automatizados para os services de leitura segura da Minha Caminhada sem depender de telas, sem criar controllers/rotas/Vue e sem alterar banco real.
- **Padrão de testes identificado:** projeto usa PHPUnit/Pest via `php artisan test`, `tests/Feature`, `tests/Unit`, `RefreshDatabase` e SQLite em memória no `phpunit.xml` (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`).
- **Arquivo criado:** `tests/Feature/MinhaCaminhada/WalkingServicesTest.php`.
- **Services cobertos:** `WalkingLevelService`, `WalkingProgressService`, `WalkingAchievementReadService`, `WalkingMentorReadService`, `WalkingDashboardReadService` e validações indiretas com `WalkingAuthorizationService`.
- **Helpers internos do teste:** `seedWalkingBase`, `createPersonAndUser`, `giveWalkingPermission`, `getJourney`, `createApprovedPointLog` e `createPersonAchievement`.
- **Cenários cobertos:** cálculo de níveis, bloqueio sem pessoa vinculada, bloqueio de outra pessoa sem autorização, progresso próprio com pontos aprovados, logs recentes sem `metadata`, catálogo de conquistas filtrando sensíveis, conquistas pessoais filtrando `hidden`, Mentor sem criar log, bloqueio de Mentor para pessoa não autorizada, dashboard seguro próprio, bloqueio de dashboard de outra pessoa e garantia de que services de leitura não criam registros operacionais automaticamente.
- **Correções realizadas:** nenhuma correção em services/models foi necessária; os testes passaram com os services existentes.
- **Resultado do teste específico:** `php artisan test tests/Feature/MinhaCaminhada/WalkingServicesTest.php` passou com 13 testes e 46 assertions.
- **Resultado da suíte geral:** `php artisan test` passou com 63 testes e 208 assertions.
- **Validações adicionais:** `php -l tests/Feature/MinhaCaminhada/WalkingServicesTest.php` passou; `php artisan test --list-tests` listou os testes, incluindo os 13 novos da Minha Caminhada; `php artisan migrate:status --no-ansi` confirmou as 10 migrations da Minha Caminhada como `Ran`; `git diff --check` passou.
- **Escopo preservado:** não foram criadas migrations, controllers, rotas, arquivos Vue, mocks, factories ou seeders; os dados criados pelos testes ficam no SQLite em memória do ambiente de teste; não foi executado `migrate`, `migrate:fresh` ou `db:wipe` no banco real/local principal.
- **Pendência próxima:** criar controllers/Inertia props ou continuar ampliando testes/policies, conforme decisão.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Controller e props reais da visão geral da Minha Caminhada

- **Horário:** 16:05–16:31 aprox.
- **Objetivo:** iniciar a integração real da visão geral da Minha Caminhada com o backend, substituindo mocks da página principal por props seguras de Inertia e estados vazios controlados.
- **Controller criado:** `app/Http/Controllers/Familia/MinhaCaminhadaController.php`.
- **Rota ajustada:** `/familia-resgate/minha-caminhada` passou a usar `MinhaCaminhadaController@index`, preservando as demais rotas internas existentes.
- **Services reaproveitados:** `WalkingDashboardReadService` e `WalkingAuthorizationService`.
- **Prop real criada:** `walkingDashboard`, com `usesRealData`, `generatedAt`, `viewerContext`, caminhada geral e caminhada jovem somente quando autorizada.
- **Segurança aplicada:** o controller usa dados já filtrados pelos services de leitura segura, não retorna Models crus, não envia `metadata` sensível e só marca uma jornada como visível quando o dashboard e o progresso estão autorizados.
- **Página principal ajustada:** `resources/js/Pages/FamiliaResgate/MinhaCaminhada.vue` passou a calcular jornadas, mapa, áreas, atividades recentes, conquistas, Mentor, destaques e próximos passos a partir de `walkingDashboard`.
- **Mocks removidos da visão geral:** removidos dados fixos de nomes, pontos, atividades, conquistas, destaques e próximos passos da página principal.
- **Estados vazios seguros:** adicionados estados para jornada indisponível, áreas sem cálculo, atividades sem registros aprovados, conquistas vazias, destaques ausentes e próximos passos ainda não calculados.
- **Teste criado:** `tests/Feature/MinhaCaminhada/MinhaCaminhadaControllerTest.php`, cobrindo entrega de props reais seguras e usuário autenticado sem `person_id`.
- **Validações executadas:** `php -l` passou no controller e no teste; `php artisan test tests/Feature/MinhaCaminhada` passou com 15 testes e 98 assertions; `php artisan test` passou com 65 testes e 260 assertions; `npm run build` passou; `php artisan route:list --path=familia-resgate/minha-caminhada --except-vendor --no-ansi` listou 19 rotas; `git diff --check` passou.
- **Busca de mocks antigos:** busca focada em `MinhaCaminhada.vue` não encontrou `href="#"`, `to="#"`, `javascript:void(0)`, nomes fictícios antigos, pontos fixos antigos, `generalMentorInsight` ou `youthMentorInsight`.
- **Escopo preservado:** não foram criadas migrations, seeders, dados fake, controllers paralelos, services paralelos ou telas fora da Minha Caminhada.
- **Pendência próxima:** testar visualmente no navegador com usuário autenticado e auditar o payload Inertia final antes de commit/push.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.

### Etapa — Ajustes pós-auditoria da visão geral da Minha Caminhada

- **Horário:** 16:53 aprox.
- **Objetivo:** corrigir somente os pontos levantados na auditoria visual/funcional da visão geral, sem avançar para abas internas e sem alterar backend estrutural.
- **Arquivo ajustado:** `resources/js/Pages/FamiliaResgate/MinhaCaminhada.vue`.
- **Botão de celebração:** o botão “Celebrar conquista” passou a aparecer somente quando houver motivo real derivado dos dados recebidos, como pontos reais, logs recentes, conquistas ou destaques visíveis; não aparece apenas porque a jornada carregou.
- **Texto de atualização:** o texto fixo “Atualizado agora há pouco” foi substituído por label baseado em `walkingDashboard.generatedAt`/`generated_at`, com fallback neutro “Resumo gerado a partir dos dados disponíveis”.
- **Reconhecimento vazio:** o card de reconhecimento passou a exibir estado discreto quando não há destaques reais visíveis, sem parecer reconhecimento ativo.
- **Mentor vazio:** o texto de fallback do Mentor passou a indicar que ainda não há leitura personalizada, que são necessários registros aprovados suficientes e que a orientação não substitui acompanhamento pastoral ou discipulado.
- **Escopo preservado:** não foram criadas migrations, seeders, controllers, rotas, dados fake ou arquivos novos; não foram alteradas abas internas da Minha Caminhada nem módulos Central da Família, Meu Perfil ou Meu Financeiro.
- **Commit:** nenhum commit realizado nesta etapa.
- **Push:** nenhum push realizado nesta etapa.
