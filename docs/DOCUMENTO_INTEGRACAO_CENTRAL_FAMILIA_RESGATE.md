# Documento de Integração da Central da Família Resgate

## 1. Objetivo

Este documento registra como a tela `/familia-resgate` funciona como hub central do ecossistema Ministério Resgate / Família Resgate.

A tela já foi finalizada visualmente, auditada, validada e enviada ao GitHub. Este documento não altera código, rotas nem layout. Ele serve como mapa técnico para orientar as próximas implementações, conectando os blocos atuais da Central da Família aos módulos futuros, tabelas prováveis, permissões, fluxos e cuidados de privacidade.

## 2. Status atual da Central da Família

A tela `/familia-resgate` está preparada como visão geral oficial da área interna do usuário. Ela possui rotas, botões, placeholders e blocos visuais organizados.

Nesta fase, muitos dados ainda são mockados. Isso é intencional. A tela está preparada para receber dados reais futuramente, mas a ligação com banco, regras de negócio e fluxos operacionais será feita módulo por módulo.

## 3. Diferença entre preparado e conectado

### Preparado

Significa que a tela já possui:
- rota própria;
- botão com destino definido;
- placeholder quando a área ainda não está implementada;
- bloco visual aprovado;
- dados temporários organizados;
- estrutura pronta para futura integração.

### Conectado

Significa que a área já possui:
- dados reais vindos do banco;
- permissões aplicadas;
- regras de negócio funcionando;
- ações reais executadas;
- logs e auditoria quando necessário;
- validações;
- fluxos completos entre módulos.

A Central da Família está preparada para o ecossistema, mas nem todos os blocos estão conectados aos dados reais.

## 4. Blocos atuais da tela `/familia-resgate`

| Bloco | Rota atual | Dados mockados atuais | Fonte futura provável | Módulo responsável | Permissões necessárias | Ações futuras | Riscos/cuidados |
|---|---|---|---|---|---|---|---|
| Perfil do usuário | `/familia-resgate/meu-perfil` | Nome, foto/inicial, família e ministérios exibidos no card | `users`, `people`, `member_profiles`, `families`, `family_members`, `department_people` | Secretaria / Perfil do Membro | Próprio usuário; Secretaria para manutenção; Administração para auditoria | Editar dados, foto, preferências e vínculos | Dados pessoais, foto, vínculo familiar incorreto |
| Próximo culto | `/familia-resgate/calendario/proximo-culto` | Sexta, 20:00, Culto da Família, Sede Central | `calendar_events`, `attendance_records`, `announcements` | Administração Geral / Agenda | Membro autenticado; gestão por Administração | Exibir próximo culto real, local e confirmação | Evento cancelado, horário incorreto, lotação |
| Confirmar presença | `/familia-resgate/cultos/confirmar-presenca` | Botão de confirmação visual | `attendance_records`, `calendar_events`, `point_events` | Agenda / Caminhada | Usuário autenticado; validação antifraude | Confirmar presença e gerar registro/pontuação | Duplicidade, confirmação indevida, auditoria |
| Minha Caminhada | `/familia-resgate/minha-caminhada` | Nível, XP, ranking, conquistas, pontos | `points`, `point_rules`, `point_events`, `attendance_records`, `bible_readings` | Caminhada Espiritual | Próprio usuário; liderança valida destaques | Ranking, regras, conquistas, pontuação real | Competição tóxica, pontuação injusta, exposição |
| Centro da Sabedoria | `/familia-resgate/centro-sabedoria` | Leitura bíblica, plano anual, devocional e atalhos | `bible_readings`, `devotionals`, `point_events` | Centro da Sabedoria | Usuário autenticado | Planos, devocionais, perguntas, histórico | Privacidade devocional, dados incompletos |
| Agenda Rápida | Rotas de calendário e eventos | Área foi substituída na visão geral, mas rotas seguem preparadas | `calendar_events`, `announcements`, `notifications` | Administração Geral / Agenda | Usuário autenticado; gestão por Administração | Reaproveitar em tela própria se necessário | Não duplicar card antigo na visão geral |
| Destaques da Família | `/familia-resgate/minha-caminhada/ranking` e `/familia-resgate/minha-caminhada/destaques/mensal` | Daniel Paulo, pontos, top 5%, badges, leitura do mês | `points`, `point_events`, `point_rules`, `bible_readings`, `attendance_records` | Caminhada / Liderança | Público interno; validação por liderança | Destaques saudáveis mensais e leitura bíblica | Vaidade, comparação humilhante, ranking sensível |
| Aniversariantes da Família | `/familia-resgate/aniversariantes` | Marília Paulo, idade, lista mensal, envio de carinho | `people`, `families`, `family_members`, `privacy_settings` futura | Família / Secretaria | Mostrar apenas quem permitiu; responsáveis para menores | Aniversários do dia/mês e mensagem de carinho | Idade opcional, menores, privacidade |
| Fotos Recentes | `/familia-resgate/galeria` | SVGs locais temporários, 12 novas fotos | `media_items`, `event_media`, `uploads` aprovados | Galeria / Comunicação | Ver conforme permissão; gestão por autorizados | Galeria real, álbuns, eventos, autorização de imagem | Direito de imagem, menores, conteúdo inadequado |
| Destaques da Semana | `/familia-resgate/destaques-semana` | Flyers locais de culto, domingo e jovens | `weekly_highlights`, `announcements`, `calendar_events`, `event_banners` | Administração Geral | Administração publica; membros visualizam | Publicar flyers e comunicados aprovados | Arte errada, data incorreta, aprovação pendente |
| Minha Família compacto | `/familia-resgate/minha-familia` | 5 membros, avatares e pendência cadastral | `families`, `family_members`, `people`, `guardianships` | Família / Secretaria | Próprio núcleo familiar; Secretaria para revisão | Membros, solicitações de alteração, vínculos | Exposição familiar, guarda/responsáveis |
| Grupos e Ministérios compacto | `/familia-resgate/grupos-ministerios` | Resgatados/Jovens, Louvor, 92%, próximo encontro | `departments`, `department_people`, `groups`, `group_members`, `ministries`, `ministry_members` | Grupos e Ministérios | Usuário vê seus vínculos; líderes gerem equipe | Meus grupos, ministérios, jovens, louvor | Vínculos desatualizados, escala sensível |
| Acessos Rápidos | Várias rotas da Central | Bíblia, devocional, rede, calendário, documentos, avisos | Depende do destino | Hub da Central | Usuário autenticado | Atalhos contextuais e ordenação por uso | Links quebrados, duplicidade, permissão |
| Acessos Privados | `/acesso/secretaria`, `/acesso/financeiro`, etc. | Portões de acesso visual | `area_pins`, `access_logs`, permissões/papéis | Áreas restritas | Perfil autorizado, PIN quando necessário | Gate seguro, logs, auditoria | Acesso indevido, PIN fraco, log ausente |
| Menu lateral | Rotas `/familia-resgate/*` | Navegação oficial da Central | Rotas, permissões, preferências futuras | Plataforma / UX | Usuário autenticado; itens por permissão futuramente | Menu adaptativo por perfil | Exibir área sem permissão, rota ausente |

## 5. Tabelas futuras prováveis

As tabelas abaixo representam a base provável de integração. Este documento não cria migrations; apenas registra a direção técnica.

| Tabela | Uso provável na Central da Família |
|---|---|
| `users` | Autenticação, conta, e-mail, senha e vínculo principal do usuário. |
| `people` | Dados pessoais, nome, nascimento, telefone, foto e cadastro de pessoa. |
| `member_profiles` | Perfil ministerial, preferências, status de membro e metadados espirituais. |
| `families` | Núcleos familiares cadastrados. |
| `family_members` | Vínculo entre pessoas e famílias. |
| `guardianships` | Responsáveis legais, menores e autorizações familiares. |
| `departments` | Departamentos oficiais da igreja. |
| `department_people` | Participação de pessoas em departamentos. |
| `groups` | Grupos de comunhão, células ou grupos por faixa/tema. |
| `group_members` | Membros vinculados aos grupos. |
| `ministries` | Ministérios oficiais, como Louvor, Intercessão, Jovens. |
| `ministry_members` | Pessoas vinculadas a ministérios. |
| `points` | Saldo ou resumo de pontuação espiritual/gamificada. |
| `point_rules` | Regras de pontuação. |
| `point_events` | Eventos que geram pontos. |
| `attendance_records` | Presenças em cultos, eventos e encontros. |
| `bible_readings` | Leituras bíblicas registradas. |
| `devotionals` | Devocionais, leituras e conteúdos diários. |
| `prayer_requests` | Pedidos de oração. |
| `prayer_followups` | Acompanhamentos, respostas e intercessões. |
| `media_items` | Fotos, vídeos, artes e arquivos da galeria. |
| `calendar_events` | Cultos, eventos, reuniões e datas oficiais. |
| `announcements` | Avisos e comunicados oficiais. |
| `weekly_highlights` | Destaques/flyers aprovados da semana. |
| `financial_transactions` | Histórico financeiro pessoal permitido. |
| `canteen_debts` | Débitos ou consumos da cantina, quando aplicável. |
| `notifications` | Notificações internas e lembretes. |
| `access_logs` | Registro de acesso às áreas sensíveis. |
| `area_pins` | PINs de acesso por área privada. |
| `activity_logs` | Auditoria geral de ações relevantes. |

## 6. Fluxos futuros por módulo

### Usuário, pessoa, família e permissões

Fluxo recomendado:

1. Usuário autentica em `users`.
2. Sistema resolve o vínculo com `people`.
3. Sistema identifica `families` e `family_members`.
4. Sistema verifica `guardianships` quando houver menores.
5. Policies definem quais blocos e ações o usuário pode acessar.

### Confirmar presença

Fluxo recomendado:

1. Usuário acessa `/familia-resgate/cultos/confirmar-presenca`.
2. Sistema identifica próximo evento em `calendar_events`.
3. Sistema cria ou atualiza `attendance_records`.
4. Regra de pontuação em `point_rules` gera `point_events`, quando aplicável.
5. A tela Minha Caminhada reflete o progresso.

### Leitura bíblica e devocional

Fluxo recomendado:

1. Usuário abre Bíblia, devocional ou plano.
2. Leitura é registrada em `bible_readings`.
3. Devocional vem de `devotionals`.
4. Progresso pode gerar `point_events`.
5. Dados podem alimentar Rede de Fé, acompanhamento pastoral e destaques.

### Destaques da Família

Fluxo recomendado:

1. Sistema calcula progresso em `points` e `point_events`.
2. Regras são carregadas de `point_rules`.
3. Liderança valida destaques sensíveis.
4. Ranking é exibido sem expor dados indevidos.
5. Destaques devem reforçar frutos espirituais, não competição vaidosa.

### Fotos Recentes

Fluxo recomendado:

1. Equipe autorizada envia fotos para `media_items`.
2. Sistema registra evento, autor, data e permissões de imagem.
3. Fotos aprovadas aparecem em `/familia-resgate/galeria`.
4. Bloco Fotos Recentes mostra os últimos itens autorizados.

### Destaques da Semana

Fluxo recomendado:

1. Administração Geral cria destaque em `weekly_highlights`.
2. Pode vincular a `calendar_events` e `announcements`.
3. Destaque passa por aprovação.
4. Central da Família exibe flyer/comunicado vigente.

### Aniversariantes

Fluxo recomendado:

1. Sistema consulta `people.birth_date`.
2. Aplica preferências de privacidade.
3. Verifica família e autorização para menores.
4. Exibe aniversariantes do dia/mês.
5. Permite envio de carinho, se permitido.

### Acessos Privados

Fluxo recomendado:

1. Usuário solicita acesso a uma área privada.
2. Sistema verifica permissões/papéis.
3. Se necessário, valida PIN em `area_pins`.
4. Registra acesso em `access_logs` e ação em `activity_logs`.
5. Redireciona para área protegida.

## 7. Permissões, privacidade e segurança

A Central da Família lida com dados sensíveis. Toda conexão futura deve respeitar policies, papéis, logs e preferências de privacidade.

### Dados familiares

- Não expor membros de uma família sem vínculo autorizado.
- Permitir revisão de vínculo pela Secretaria.
- Registrar alterações sensíveis em `activity_logs`.

### Menores de idade

- Consultar `guardianships` antes de exibir dados de crianças/adolescentes.
- Evitar idade explícita quando não houver autorização.
- Permitir controle por responsáveis legais.

### Aniversários

- Idade deve ser opcional.
- Exibir aniversário apenas com permissão.
- Para menores, exigir autorização do responsável.

### Imagens e galeria

- Fotos devem ter autorização de imagem.
- Fotos de menores exigem cuidado extra.
- Itens devem ser aprovados antes de aparecer na Central.

### Pedidos de oração

- `prayer_requests` e `prayer_followups` devem ser tratados como dados pastorais sensíveis.
- Exibir apenas para o solicitante e equipes autorizadas.
- Registrar acompanhamento sem expor detalhes indevidos.

### Financeiro pessoal

- `financial_transactions` e pendências financeiras só devem aparecer ao próprio usuário ou área autorizada.
- Nunca exibir valores pessoais para terceiros.
- Auditoria é obrigatória em consultas administrativas.

### Cantina

- `canteen_debts` deve ter acesso restrito ao próprio usuário, responsáveis ou gestão autorizada.
- Evitar exposição pública de débitos.

### Acessos privados

- Áreas privadas devem combinar permissão por papel/função com PIN quando necessário.
- `area_pins` não deve armazenar PIN em texto puro.
- Acessos devem ser registrados em `access_logs`.

### Transições visuais e performance

- Cards da Central devem aparecer imediatamente.
- Não usar reveal on scroll, fade-in atrasado, `IntersectionObserver` para exibição ou `transition-delay` em cards.
- Manter apenas hover leve e transições curtas.

## 8. Relação com áreas do ecossistema

### Secretaria

Responsável por cadastro, pessoas, vínculos familiares, dados civis, revisão de alterações e consistência dos registros. Alimenta Perfil do Usuário, Minha Família, aniversariantes e vínculos ministeriais.

### Administração Geral

Responsável por governança, destaques oficiais, agenda institucional, comunicados, permissões superiores e auditoria geral. Alimenta Destaques da Semana, Agenda, Acessos Privados e comunicação oficial.

### Centro Pastoral

Responsável por cuidado espiritual, acompanhamento pastoral, pedidos de oração sensíveis e suporte familiar. Integra-se a Orações, Rede de Fé, Caminhada e cuidado de famílias.

### Centro Financeiro

Responsável por dados financeiros autorizados, contribuições, recibos, pendências e relatórios. Alimenta Meu Financeiro e documentos financeiros, sempre com privacidade reforçada.

### Cantina

Responsável por consumos, débitos, pagamentos e controle interno de cantina. Pode alimentar pendências do usuário, quando autorizado.

### Intercessão

Responsável por pedidos de oração, acompanhamento, intercessores e respostas. Deve operar com privacidade, confidencialidade e permissões específicas.

### Galeria

Responsável por fotos, álbuns, eventos, autorização de imagem e mídia aprovada. Alimenta Fotos Recentes e páginas de galeria.

### Centro da Sabedoria

Responsável por Bíblia, devocionais, planos de leitura, perguntas e progresso bíblico. Alimenta Centro da Sabedoria, pontuação e destaque em leitura.

### Rede de Fé

Responsável por conexões espirituais, discipulado, acompanhamento e crescimento em comunidade. Pode consumir dados de caminhada, leitura e participação, sempre com consentimento e cuidado.

### Grupos e Ministérios

Responsável por grupos, departamentos, ministérios, participação, encontros e escalas. Alimenta o card compacto e páginas detalhadas de grupos/ministérios.

## 9. Ordem recomendada de implementação

1. Meu Perfil
2. Minha Família
3. Minha Caminhada / Pontuação
4. Centro da Sabedoria
5. Galeria / Fotos Recentes
6. Destaques da Semana / Administração Geral
7. Aniversariantes
8. Orações
9. Grupos e Ministérios
10. Meu Financeiro
11. Acessos Privados completos

## 10. Checklist de conexão futura

- [ ] Remover mocks somente quando dados reais estiverem disponíveis.
- [ ] Manter rotas próprias e explícitas.
- [ ] Não usar links `#`.
- [ ] Aplicar policies e permissões por usuário/papel.
- [ ] Registrar logs em áreas sensíveis.
- [ ] Validar privacidade antes de exibir família, idade, fotos e pedidos.
- [ ] Não duplicar componentes ou criar versões paralelas da Central.
- [ ] Não quebrar o visual aprovado.
- [ ] Conectar módulo por módulo.
- [ ] Auditar rotas, build e testes após cada etapa.
- [ ] Garantir que cards apareçam imediatamente, sem reveal/fade/scroll delay.
- [ ] Manter hover suave e transições curtas.

## 11. Pendências futuras

- Definir models, migrations e relações reais para os módulos ainda mockados.
- Criar controllers/services para alimentar a Central via Inertia props.
- Criar policies específicas para família, mídia, financeiro, intercessão e áreas privadas.
- Implementar preferências de privacidade para aniversário, idade, imagem e dados familiares.
- Implementar fluxo real de confirmação de presença.
- Implementar pontuação real com regras auditáveis.
- Implementar gestão de destaques semanais pela Administração Geral.
- Implementar galeria real com aprovação e autorização de imagem.
- Implementar aniversariantes com respeito a menores e responsáveis.
- Implementar áreas privadas completas com PIN seguro, logs e auditoria.
- Criar testes automatizados para cada módulo conectado.

## 12. Observação final

Este documento orienta a integração futura da Central da Família Resgate. Ele não substitui a especificação detalhada de cada módulo, mas serve como mapa central para manter coerência entre visual, rotas, dados, permissões, privacidade e governança do ecossistema.

