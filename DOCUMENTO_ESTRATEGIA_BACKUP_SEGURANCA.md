# Estratégia de Backup e Segurança de Dados
**Projeto:** Ministério Resgate / Família Resgate  
**Data:** 11 de Maio de 2026  
**Versão:** 1.0

---

## 1. Visão Geral

Este documento define a estratégia de backup e segurança de dados para o sistema Ministério Resgate. O objetivo é garantir a integridade, disponibilidade e confidencialidade dos dados armazenados.

---

## 2. Tipos de Dados

### 2.1 Banco de Dados MySQL
- Tabelas principais: users, people, families, family_members, guardianships
- Tabelas de sistema: access_profiles, permissions, activity_logs, system_alerts
- Tabelas de documentos: person_documents, person_addresses
- Tabelas de membresia: member_profiles, secretary_requests

### 2.2 Storage / Uploads
- Fotos de perfil
- Documentos digitalizados
- Outros arquivos enviados pelos usuários

### 2.3 Arquivos de Configuração
- .env (NUNCA deve ser incluído em backup público)
- Configurações do Laravel

---

## 3. Estratégia de Backup

### 3.1 Backup do Banco de Dados

#### Ambiente Local (Desenvolvimento)
- **Frequência:** Diário (automático)
- **Retenção:** 7 dias
- **Método:** `mysqldump` ou `php artisan db:backup`
- **Local:** `storage/backups/database/`

**Comando recomendado:**
```bash
# Usando mysqldump
mysqldump -u [usuario] -p[senha] [banco] > backup_$(date +%Y%m%d).sql

# Ou usando artisan (se instalar pacote de backup)
php artisan backup:run --only-db
```

#### Ambiente de Produção
- **Frequência:** 
  - Backup completo: Diário
  - Backup incremental: A cada 6 horas
- **Retenção:** 
  - Diários: 30 dias
  - Semanais: 12 semanas
  - Mensais: 12 meses
- **Método:** mysqldump + compressão
- **Local:** Servidor externo ou serviço de backup (ex: AWS S3, Backblaze)

**Recomendação:** Considerar uso de pacote `spatie/laravel-backup` para automação.

### 3.2 Backup de Storage / Uploads

#### Ambiente Local
- **Frequência:** Semanal
- **Retenção:** 4 semanas
- **Método:** rsync ou cópia direta
- **Local:** `storage/backups/uploads/`

**Comando recomendado:**
```bash
rsync -avz storage/app/public/ storage/backups/uploads/$(date +%Y%m%d)/
```

#### Ambiente de Produção
- **Frequência:** Diário
- **Retenção:** 30 dias
- **Método:** rsync para servidor externo ou serviço de cloud storage
- **Local:** Servidor externo ou cloud storage (AWS S3, Backblaze B2)

### 3.3 Backup de Configuração

#### Regra Importante
- **NUNCA** incluir `.env` em backup público
- **NUNCA** commitar `.env` no Git
- Manter `.env.example` atualizado com variáveis necessárias (sem valores reais)

#### Ambiente Local
- **Frequência:** Manual (antes de mudanças importantes)
- **Retenção:** Versões anteriores
- **Método:** Cópia manual com nome datado
- **Local:** `storage/backups/config/`

#### Ambiente de Produção
- **Frequência:** Manual (antes de deploy ou mudanças importantes)
- **Retenção:** 5 versões anteriores
- **Método:** Cópia segura em local isolado
- **Local:** Servidor de gestão de segredos (ex: AWS Secrets Manager, HashiCorp Vault)

---

## 4. Restauração

### 4.1 Procedimento de Restauração do Banco

1. **Parar a aplicação** (para evitar inconsistências)
2. **Fazer backup do estado atual** (caso precise reverter)
3. **Restaurar o backup desejado:**
```bash
mysql -u [usuario] -p[senha] [banco] < backup_20240511.sql
```
4. **Verificar integridade dos dados**
5. **Reiniciar a aplicação**
6. **Testar funcionalidades críticas**

### 4.2 Procedimento de Restauração de Storage

1. **Identificar o backup correto**
2. **Restaurar arquivos:**
```bash
rsync -avz storage/backups/uploads/20240511/ storage/app/public/
```
3. **Verificar permissões de arquivos**
4. **Limpar cache se necessário:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan storage:link
```

---

## 5. Cuidados com Dados Pessoais (LGPD)

### 5.1 Dados Sensíveis
O sistema armazena dados pessoais que exigem proteção especial:
- Dados de menores de idade
- Dados de contato (telefone, email, WhatsApp)
- Endereços residenciais
- Documentos de identificação
- Dados de família e responsáveis

### 5.2 Proteção de Backups
- Backups devem ser criptografados em produção
- Backups devem ser armazenados em local seguro
- Acesso a backups deve ser restrito e auditado
- Backups antigos devem ser descartados de forma segura

### 5.3 Retenção de Dados
- Definir política de retenção de dados (ex: 7 anos para dados financeiros)
- Implementar direito ao esquecimento quando solicitado
- Documentar consentimento de uso de dados

---

## 6. Onde NÃO Salvar Senhas

### 6.1 Nunca Salvar Em:
- Código fonte (Git)
- Logs de aplicação
- Arquivos de configuração versionados
- Documentos compartilhados
- Chat ou e-mail

### 6.2 Onde Salvar:
- `.env` (local, não versionado)
- Serviços de gestão de segredos (produção)
- Password managers (pessoal)
- Variáveis de ambiente do servidor (produção)

---

## 7. Cuidados com .env

### 7.1 Regras de Segurança
- **NUNCA** commitar `.env` no Git
- **NUNCA** compartilhar `.env` por e-mail ou chat
- **NUNCA** incluir `.env` em backup público
- Manter `.env.example` atualizado (sem valores reais)

### 7.2 Variáveis Críticas
- `APP_KEY` - Chave de criptografia da aplicação
- `DB_PASSWORD` - Senha do banco de dados
- `MAIL_PASSWORD` - Senha de e-mail (se usado)
- `AWS_ACCESS_KEY_ID` - Chaves da AWS (se usado)
- `AWS_SECRET_ACCESS_KEY` - Chaves da AWS (se usado)

### 7.3 Rotação de Chaves
- `APP_KEY` deve ser gerada automaticamente pelo Laravel
- Senhas de banco devem ser alteradas periodicamente
- Chaves de API devem ter expiração e rotação

---

## 8. Checklist Antes de Deploy

### 8.1 Backup
- [ ] Backup completo do banco de dados
- [ ] Backup de storage/uploads
- [ ] Backup da configuração atual (.env)
- [ ] Verificar integridade dos backups

### 8.2 Segurança
- [ ] Verificar se `.env` não está no Git
- [ ] Verificar se `.env.example` está atualizado
- [ ] Verificar permissões de arquivos
- [ ] Verificar configuração de CORS (se aplicável)
- [ ] Verificar se debug está desativado (`APP_DEBUG=false`)

### 8.3 Testes
- [ ] Rodar testes automatizados
- [ ] Testar funcionalidades críticas manualmente
- [ ] Verificar logs de erro
- [ ] Testar restore de backup (em ambiente de staging)

### 8.4 Monitoramento
- [ ] Configurar monitoramento de erros
- [ ] Configurar alertas de sistema
- [ ] Verificar espaço em disco
- [ ] Verificar configuração de logs

---

## 9. Recomendações Futuras

### 9.1 Automação
- Implementar backup automatizado com `spatie/laravel-backup`
- Configurar scheduler para backups automáticos
- Implementar notificação de backup falhado
- Implementar monitoramento de integridade de backup

### 9.2 Infraestrutura
- Considerar uso de serviços de backup gerenciados (ex: AWS Backup)
- Implementar replicação de banco para alta disponibilidade
- Implementar CDN para uploads (ex: AWS CloudFront)
- Implementar criptografia em repouso para backups

### 9.3 Compliance
- Revisar política de retenção com advogado
- Implementar logs de acesso a dados sensíveis
- Implementar auditoria de alterações
- Documentar consentimento de uso de dados

---

## 10. Contatos e Responsabilidades

### 10.1 Responsável Técnico
- Nome: [A definir]
- Função: Arquiteto Técnico / Desenvolvedor
- Responsabilidades: Executar backups, monitorar integridade

### 10.2 Responsável pela Secretaria
- Nome: [A definir]
- Função: Secretaria / Administração
- Responsabilidades: Aprovar mudanças, verificar integridade

---

## 11. Revisão

| Data | Versão | Alterações | Autor |
|------|--------|------------|-------|
| 11/05/2026 | 1.0 | Documento inicial | Cascade |

---

**IMPORTANTE:** Este documento é um guia técnico inicial. A estratégia de backup deve ser revisada e ajustada conforme as necessidades do projeto e ambiente de produção.
