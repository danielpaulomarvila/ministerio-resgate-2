# Mapa de Permissões da Minha Caminhada

## 1. Princípio geral

A Minha Caminhada deve ser orientada por permissões reais no backend. A interface pode ocultar seções, mas segurança final deve vir de policy, controller, service e consulta filtrada.

Nenhuma página deve assumir que o usuário pode ver dados jovens, dados de equipe, dados de filhos, dados sensíveis ou relatórios apenas porque a rota existe.

## 2. Perfis oficiais

### 2.1 Membro comum

Pode ver:

- **Caminhada Geral.**
- **Destaques Gerais.**
- **Conquistas gerais.**
- **Histórico geral próprio.**
- **Mentor geral.**
- **Regras gerais.**

Não pode ver:

- **Destaques Jovens.**
- **Equipes dos Resgatados.**
- **Dados de outros jovens.**
- **Rankings/destaques jovens.**
- **Informações internas de equipe jovem.**
- **Dados de filhos se não houver vínculo de responsável autorizado.**
- **Relatórios administrativos.**

### 2.2 Jovem/Resgatado

Pode ver:

- **Caminhada Geral.**
- **Caminhada Jovem.**
- **Destaques Jovens.**
- **Conquistas jovens.**
- **Histórico jovem próprio.**
- **Mentor com trilho jovem.**
- **Módulo jovem quando liberado.**

Não deve ver:

- **Dados de outros jovens sem permissão.**
- **Informações internas de equipes das quais não participa.**
- **Validações administrativas.**
- **Dados sensíveis de liderança.**

### 2.3 Participante de equipe jovem

Pode ver:

- **Informações da própria equipe.**
- **Pontos coletivos da própria equipe.**
- **Missões de equipe.**
- **Destaques coletivos conforme regra futura.**
- **Histórico coletivo autorizado da própria equipe.**

Não deve ver:

- **Equipes de terceiros sem autorização.**
- **Dados pessoais de outros jovens além do necessário.**
- **Relatórios administrativos.**

### 2.4 Responsável/pai/mãe

Pode ver:

- **Caminhada dos filhos vinculados.**
- **Informações jovens dos filhos conforme idade/permissão.**
- **Histórico permitido dos filhos.**
- **Conquistas permitidas dos filhos.**
- **Mentor/acompanhamento familiar quando autorizado.**

Não pode ver:

- **Todos os jovens.**
- **Equipes inteiras sem vínculo.**
- **Dados sensíveis não liberados por policy.**
- **Informações pastorais privadas.**

### 2.5 Líder jovem

Pode ver:

- **Jovens/resgatados sob sua liderança.**
- **Equipes.**
- **Validações jovens.**
- **Destaques jovens.**
- **Relatórios jovens.**
- **Pontos coletivos de equipes.**

Deve ter limites:

- **Ver apenas escopo de liderança autorizado.**
- **Não acessar dados pastorais sensíveis sem policy.**
- **Não acessar dados financeiros sem permissão própria.**

### 2.6 Secretaria/Administração

Pode ver:

- **Tudo necessário para cadastro.**
- **Tudo necessário para validação.**
- **Tudo necessário para auditoria.**
- **Regras de pontuação.**
- **Registros pendentes.**
- **Logs de pontos e conquistas.**
- **Dados de trilhos gerais, jovens e equipes conforme necessidade administrativa.**

Responsabilidades:

- **Administrar regras de pontuação.**
- **Validar registros.**
- **Garantir integridade dos dados.**
- **Manter rastreabilidade.**

### 2.7 Pastor/Liderança pastoral

Pode ver:

- **Informações necessárias para cuidado pastoral.**
- **Dados sensíveis conforme policy.**
- **Mentor/acompanhamento com cuidado.**
- **Indicadores de constância e risco pastoral quando aprovados.**

Deve respeitar:

- **Privacidade.**
- **Mínimo necessário.**
- **Não exposição pública.**
- **Separação entre acompanhamento pastoral e competição.**

## 3. Tabela Página x Perfil

Legenda:

- **Sim:** pode acessar/ver conteúdo principal.
- **Parcial:** pode ver apenas conteúdo filtrado por vínculo, trilho ou policy.
- **Não:** não deve ver.
- **Admin:** acesso administrativo/auditoria conforme policy.

| Página | Membro comum | Jovem/Resgatado | Responsável | Líder jovem | Secretaria/Admin | Pastor/Liderança |
|---|---:|---:|---:|---:|---:|---:|
| `/familia-resgate/minha-caminhada` | Sim, geral | Sim, geral + jovem | Parcial, filhos | Parcial | Admin | Parcial |
| `/familia-resgate/minha-caminhada/nivel` | Sim, próprio geral | Sim, próprio geral + jovem | Parcial, filhos | Parcial | Admin | Parcial |
| `/familia-resgate/minha-caminhada/geral` | Sim | Sim | Parcial, filhos se aplicável | Parcial | Admin | Parcial |
| `/familia-resgate/minha-caminhada/jovem` | Não | Sim | Parcial, filhos | Sim, escopo jovem | Admin | Parcial |
| `/familia-resgate/minha-caminhada/mapa` | Sim, geral | Sim, geral + jovem | Parcial, filhos | Parcial | Admin | Parcial |
| `/familia-resgate/minha-caminhada/historico` | Sim, próprio geral | Sim, próprio geral + jovem | Parcial, filhos | Parcial, escopo jovem | Admin | Parcial |
| `/familia-resgate/minha-caminhada/mentor` | Sim, geral | Sim, geral + jovem | Parcial, filhos | Parcial, acompanhamento | Admin | Parcial, cuidado pastoral |
| `/familia-resgate/minha-caminhada/regras` | Sim, gerais | Sim, gerais + jovens | Parcial | Sim, jovens/equipes | Admin | Sim, conforme policy |
| `/familia-resgate/minha-caminhada/ranking` | Sim, destaques gerais | Sim, geral + jovem | Parcial, filhos se aplicável | Sim, jovens/equipes | Admin | Parcial/Sim conforme policy |
| `/familia-resgate/minha-caminhada/conquistas` | Sim, gerais próprias | Sim, gerais + jovens próprias | Parcial, filhos | Parcial, escopo jovem | Admin | Parcial |

## 4. Regras por trilho

### Caminhada Geral

- **Membro comum:** vê própria caminhada geral.
- **Jovem:** vê também caminhada geral.
- **Responsável:** vê dados gerais dos filhos apenas quando houver vínculo e autorização.
- **Liderança/Admin:** vê conforme necessidade e policy.

### Caminhada Jovem

- **Membro comum:** não vê.
- **Jovem/Resgatado:** vê dados próprios.
- **Responsável:** vê filhos vinculados.
- **Líder jovem:** vê escopo autorizado.
- **Admin/Pastor:** vê conforme policy.

### Equipes dos Resgatados

- **Membro comum:** não vê.
- **Participante:** vê própria equipe.
- **Líder jovem:** vê equipes sob sua liderança.
- **Responsável:** pode ver equipe do filho se policy permitir.
- **Admin/Pastor:** vê conforme necessidade e policy.

### Intercessão

- **Membro comum:** pode ver categoria geral futura quando aplicável, sem dados sensíveis.
- **Intercessor:** pode ver registros próprios autorizados.
- **Pessoa atendida:** avaliação deve ser privada.
- **Liderança/Admin/Pastor:** vê conforme cuidado pastoral, auditoria e policy.

## 5. Regras de segurança

- **Frontend não é segurança final.**
- **Policies devem filtrar dados antes de enviar para Inertia.**
- **Dados jovens devem considerar idade, responsável e vínculo.**
- **Dados sensíveis devem usar mínimo necessário.**
- **Destaques públicos devem evitar exposição indevida.**
- **Histórico deve ser sempre filtrado por pessoa, vínculo ou permissão.**
- **Administração deve ter trilha de auditoria para alterações de regras e validações.**
