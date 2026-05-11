# Política de Privacidade - Rascunho Técnico
**Projeto:** Ministério Resgate / Família Resgate  
**Data:** 11 de Maio de 2026  
**Versão:** 1.0 (RASCUNHO TÉCNICO)

---

## AVISO IMPORTANTE

**ESTE DOCUMENTO É UM RASCUNHO TÉCNICO PARA FINS DE PLANEJAMENTO.**

Este documento NÃO é uma política de privacidade jurídica final. Antes de usar este documento oficialmente, é NECESSÁRIO revisão por advogado especializado em LGPD/privacidade de dados para garantir conformidade com a legislação vigente em Portugal/União Europeia (GDPR).

---

## 1. Introdução

Este documento descreve, em nível técnico, quais dados o sistema Ministério Resgate pode coletar, armazenar e processar, e como esses dados são protegidos.

---

## 2. Dados Coletados

### 2.1 Dados de Pessoas (Membros, Congregados, Visitantes)

O sistema pode armazenar os seguintes dados de pessoas:

**Dados Pessoais Básicos:**
- Nome completo
- Nome preferido
- Sobrenome
- Data de nascimento
- Gênero
- Estado civil
- Nível de educação
- Nacionalidade
- Naturalidade
- Profissão
- Ocupação

**Dados de Contato:**
- Telefone principal
- Telefone secundário
- WhatsApp
- E-mail
- Endereço (rua, número, código postal, cidade, país)

**Dados de Identificação (se fornecidos):**
- Número de documento de identificação
- Número de fiscal (NIF) - se aplicável
- Data de emissão do documento
- Local de emissão

**Dados Religiosos:**
- Status de batismo (sim/não)
- Data de batismo
- Data de conversão
- Quem convidou/indicou a pessoa
- Perfil de membro (se aplicável)

**Dados de Sistema:**
- Foto de perfil (se enviada)
- Notas de contato
- Notas gerais
- Data de criação do registro
- Data de atualização do registro

### 2.2 Dados de Família

O sistema pode armazenar:
- Nome da família
- Descrição da família
- Responsável principal
- Relação entre membros da família
- Notas sobre a família

### 2.3 Dados de Responsáveis/Tutoria (Menores)

Para menores de idade, o sistema pode armazenar:
- Identificação do menor
- Identificação do responsável
- Tipo de responsabilidade (pai, mãe, tutor, etc.)
- Status da responsabilidade
- Permissões especiais (ex: autorização de login para Júniores)
- Notas sobre a tutoria

### 2.4 Dados de Usuários do Sistema

Para acesso ao sistema web, o sistema armazena:
- Nome de usuário
- E-mail (usado como login)
- Senha (hashada, nunca em texto plano)
- Status da conta (ativo, suspenso, etc.)
- Perfis de acesso atribuídos
- Data de último acesso
- Histórico de atividades (logs)

### 2.5 Dados Financeiros (Futuro)

Em fase futura, o sistema pode armazenar:
- Dados de dízimos/ofertas
- Dados de pagamentos
- Histórico de transações

**NOTA:** Esta funcionalidade ainda não foi implementada.

---

## 3. Finalidade do Uso dos Dados

Os dados coletados são usados para:

### 3.1 Gestão Eclesiástica
- Manter registro de membros, congregados e visitantes
- Organizar famílias e responsáveis
- Gerenciar departamentos e ministérios
- Planejar eventos e atividades

### 3.2 Cuidado Pastoral
- Acompanhar membros e suas famílias
- Identificar necessidades especiais (ex: crianças próximas de transição de idade)
- Facilitar comunicação pastoral

### 3.3 Administração
- Gestão de acessos ao sistema
- Auditoria de atividades
- Relatórios estatísticos

### 3.4 Segurança
- Proteção de menores
- Verificação de elegibilidade de acesso
- Prevenção de acessos não autorizados

---

## 4. Quem Pode Acessar os Dados

### 4.1 Secretaria
A Secretaria tem acesso a:
- Todos os cadastros de pessoas
- Dados familiares
- Dados de responsáveis
- Histórico de atividades
- Alertas do sistema

### 4.2 Super-Administrador
O Super-Administrador tem acesso total ao sistema, incluindo:
- Gestão de usuários
- Gestão de perfis de acesso
- Configurações do sistema

### 4.3 Usuários Comuns (Membros)
Usuários comuns podem acessar:
- Seus próprios dados pessoais
- Seu próprio perfil de membro (se aplicável)
- Dados limitados de sua família (regra futura)

### 4.4 Líderes de Departamento (Futuro)
Líderes de departamento podem acessar:
- Dados de membros de seu departamento
- Informações relevantes para seu ministério

---

## 5. Dados de Menores

### 5.1 Proteção Especial
O sistema implementa proteção especial para menores de idade:
- Crianças menores de 11 anos não têm usuário próprio
- Júniores (11-13 anos) podem ter usuário, mas com supervisão
- Jovens (14-17 anos) podem ter usuário com limitações
- Dados de menores são tratados como sensíveis

### 5.2 Responsáveis
O sistema requer identificação de responsáveis para menores:
- Pelo menos um responsável deve estar registrado
- Responsável pode autorizar acesso de Júniores
- Alertas são gerados automaticamente para transições de idade

---

## 6. Pedido de Correção de Dados

### 6.1 Como Solicitar
Usuários podem solicitar correção de seus dados através de:
- Contato com a Secretaria
- Formulário de solicitação (futuro)
- Sistema web (recurso de edição próprio, se permitido)

### 6.2 Processo
- Secretaria verifica a solicitação
- Dados são atualizados se a solicitação for válida
- Alteração é registrada no log de auditoria

---

## 7. Pedido de Exclusão (Direito ao Esquecimento)

### 7.1 Quando é Possível
Usuários podem solicitar exclusão de seus dados quando:
- Não há obrigações legais de retenção
- Não há vínculos ativos que exijam os dados
- A exclusão não afeta integridade de outros registros

### 7.2 Limitações
Alguns dados não podem ser excluídos completamente:
- Logs de auditoria (por obrigação legal)
- Dados financeiros (por obrigação fiscal)
- Dados essenciais para integridade do sistema

### 7.3 Processo
- Usuário solicita exclusão à Secretaria
- Secretaria avalia viabilidade legal e técnica
- Se aprovado, dados são anonimizados ou excluídos
- Soft delete é usado quando possível (para preservar integridade)

---

## 8. Segurança

### 8.1 Medidas Técnicas
- Senhas hashadas (bcrypt)
- Autenticação por e-mail/senha
- Proteção CSRF
- Validação de dados
- Soft deletes para dados sensíveis
- Logs de auditoria
- Permissões granulares

### 8.2 Medidas Organizacionais
- Acesso restrito a dados sensíveis
- Treinamento de equipe
- Política de senhas
- Revisão periódica de acessos

### 8.3 Backups
- Backups regulares do banco de dados
- Backups de arquivos enviados
- Criptografia de backups em produção (recomendado)
- Retenção definida de backups

---

## 9. Logs

O sistema mantém logs de:
- Ações de usuários (criação, atualização, exclusão)
- Acessos ao sistema
- Alterações em dados sensíveis
- Tentativas de acesso não autorizado

Os logs são usados para:
- Auditoria
- Investigação de incidentes
- Melhoria de segurança

---

## 10. Compartilhamento de Dados

### 10.1 Regra Geral
O sistema NÃO compartilha dados com terceiros, exceto quando:
- Exigido por lei
- Necessário para prestação de serviço essencial
- Com consentimento explícito do titular

### 10.2 Terceiros
Atualmente, o sistema não compartilha dados com terceiros.

Em fase futura, pode ser necessário compartilhar com:
- Serviços de e-mail (para notificações)
- Serviços de armazenamento em nuvem (para backups)
- Serviços de processamento de pagamento (fase financeira)

---

## 11. Retenção de Dados

### 11.1 Período de Retenção
Dados são mantidos enquanto:
- A pessoa for membro ativo
- Houver obrigação legal de retenção
- Houver necessidade administrativa

### 11.2 Critérios de Exclusão
Dados podem ser excluídos ou anonimizados quando:
- A pessoa solicitar exclusão
- O período de retenção legal expirar
- Não houver mais necessidade administrativa

---

## 12. Transferência Internacional

Atualmente, o sistema é hospedado localmente em Portugal.

Se houver transferência internacional de dados no futuro (ex: uso de serviços de nuvem dos EUA), será necessário:
- Avaliar legalidade da transferência
- Implementar salvaguardas apropriadas
- Documentar consentimento do titular

---

## 13. Necessidade de Revisão Jurídica

### 13.1 Pontos que Revisão Jurídica
- Conformidade com LGPD (Lei Geral de Proteção de Dados - Brasil) se aplicável
- Conformidade com GDPR (União Europeia) se aplicável
- Conformidade com leis locais de Portugal
- Adequação de cláusulas de consentimento
- Adequação de políticas de retenção
- Adequação de direitos do titular

### 13.2 Recomendações
- Consultar advogado especializado em privacidade de dados
- Adaptar documento para linguagem jurídica apropriada
- Criar formulários de consentimento
- Criar política de cookies (se aplicável)
- Criar página de política de privacidade pública

---

## 14. Próximos Passos

1. Revisão jurídica do documento
2. Adaptação para linguagem jurídica
3. Criação de formulários de consentimento
4. Implementação de mecanismos de exercício de direitos
5. Publicação de política de privacidade pública
6. Treinamento de equipe

---

## 15. Revisão

| Data | Versão | Alterações | Autor |
|------|--------|------------|-------|
| 11/05/2026 | 1.0 | Rascunho técnico inicial | Cascade |

---

**IMPORTANTE:** Este documento é um rascunho técnico. Antes de uso oficial, é necessária revisão jurídica para garantir conformidade com a legislação aplicável.
