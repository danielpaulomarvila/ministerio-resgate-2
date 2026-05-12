#!/bin/bash

# Caminho base: garante que o script roda sempre a partir da raiz do projeto.
cd "$(dirname "$0")/.." || exit 1

# Pasta do iCloud onde os backups também serão guardados.
ICLOUD_DIR="$HOME/Library/Mobile Documents/com~apple~CloudDocs/ICLOUD - SISTEMA PLATAFORMA RESGATE - RESGATE 2.0"

# Cria as pastas de backup local, caso ainda não existam.
mkdir -p backups/mysql
mkdir -p backups/env

# Cria as pastas de backup no iCloud, caso ainda não existam.
mkdir -p "$ICLOUD_DIR/mysql"
mkdir -p "$ICLOUD_DIR/env"

# Gera data e hora para nomear os arquivos sem sobrescrever backups antigos.
DATA=$(date +%Y-%m-%d_%H-%M-%S)

# Define os nomes dos arquivos de backup.
MYSQL_BACKUP="ministerio_resgate_${DATA}.sql"
ENV_BACKUP=".env_${DATA}.backup"

# ============================================================================
# LER CONFIGURAÇÕES DO BANCO A PARTIR DO .env
# ============================================================================
# Lê as variáveis do arquivo .env de forma segura
if [ ! -f .env ]; then
    echo "❌ ERRO: Arquivo .env não encontrado!"
    exit 1
fi

# Extrai configurações do banco do .env
DB_HOST=$(grep "^DB_HOST=" .env | cut -d '=' -f2-)
DB_PORT=$(grep "^DB_PORT=" .env | cut -d '=' -f2-)
DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2-)
DB_USERNAME=$(grep "^DB_USERNAME=" .env | cut -d '=' -f2-)
DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2-)

# Valida se todas as configurações necessárias foram encontradas
if [ -z "$DB_HOST" ] || [ -z "$DB_PORT" ] || [ -z "$DB_DATABASE" ] || [ -z "$DB_USERNAME" ] || [ -z "$DB_PASSWORD" ]; then
    echo "❌ ERRO: Configurações de banco não encontradas no .env!"
    echo "   Verifique se DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME e DB_PASSWORD estão definidos."
    exit 1
fi

# ============================================================================
# CRIAR ARQUIVO TEMPORÁRIO DE CREDENCIAIS
# ============================================================================
# Usa arquivo temporário com permissões restritas para evitar exposição da senha
CRED_FILE=$(mktemp)
chmod 600 "$CRED_FILE"

# Escreve as credenciais no arquivo temporário no formato esperado pelo mysqldump
cat > "$CRED_FILE" <<EOF
[client]
host=$DB_HOST
port=$DB_PORT
user=$DB_USERNAME
password=$DB_PASSWORD
EOF

# ============================================================================
# FAZER BACKUP DO BANCO MYSQL
# ============================================================================
# Usa --defaults-extra-file para ler credenciais do arquivo temporário
# Isso evita expor a senha no histórico de comandos e no terminal
echo "📦 Iniciando backup do MySQL..."
mysqldump --defaults-extra-file="$CRED_FILE" "$DB_DATABASE" > "backups/mysql/${MYSQL_BACKUP}" 2>&1
MYSQL_EXIT_CODE=$?

# Remove o arquivo temporário de credenciais imediatamente após uso
rm -f "$CRED_FILE"

# Verifica se o mysqldump foi bem-sucedido
if [ $MYSQL_EXIT_CODE -ne 0 ]; then
    echo "❌ ERRO: Falha ao fazer backup do MySQL (código: $MYSQL_EXIT_CODE)"
    echo "   Verifique as configurações do banco no .env."
    exit 1
fi

# Verifica se o arquivo SQL foi criado e tem tamanho > 0
if [ ! -f "backups/mysql/${MYSQL_BACKUP}" ]; then
    echo "❌ ERRO: Arquivo SQL não foi criado."
    exit 1
fi

SQL_SIZE=$(stat -f%z "backups/mysql/${MYSQL_BACKUP}" 2>/dev/null || stat -c%s "backups/mysql/${MYSQL_BACKUP}" 2>/dev/null)
if [ -z "$SQL_SIZE" ] || [ "$SQL_SIZE" -eq 0 ]; then
    echo "❌ ERRO: Arquivo SQL está vazio (0 bytes)."
    rm -f "backups/mysql/${MYSQL_BACKUP}"
    exit 1
fi

echo "✅ Backup MySQL criado: ${MYSQL_BACKUP} (${SQL_SIZE} bytes)"

# ============================================================================
# FAZER BACKUP DO ARQUIVO .env
# ============================================================================
echo "📦 Iniciando backup do .env..."
cp .env "backups/env/${ENV_BACKUP}"

if [ ! -f "backups/env/${ENV_BACKUP}" ]; then
    echo "❌ ERRO: Falha ao copiar arquivo .env."
    exit 1
fi

echo "✅ Backup .env criado: ${ENV_BACKUP}"

# ============================================================================
# COPIAR BACKUPS PARA ICLOUD
# ============================================================================
# Só copia para o iCloud se ambos os backups locais foram bem-sucedidos
echo "☁️  Copiando backups para o iCloud..."

cp "backups/mysql/${MYSQL_BACKUP}" "$ICLOUD_DIR/mysql/"
if [ $? -ne 0 ]; then
    echo "❌ ERRO: Falha ao copiar backup MySQL para o iCloud."
    exit 1
fi

cp "backups/env/${ENV_BACKUP}" "$ICLOUD_DIR/env/"
if [ $? -ne 0 ]; then
    echo "❌ ERRO: Falha ao copiar backup .env para o iCloud."
    exit 1
fi

# ============================================================================
# RELATÓRIO DE SUCESSO
# ============================================================================
echo ""
echo "✅ Backup criado com sucesso:"
echo "📁 Local:"
echo "   - MySQL: backups/mysql/${MYSQL_BACKUP}"
echo "   - .env: backups/env/${ENV_BACKUP}"
echo "☁️  iCloud:"
echo "   - MySQL: ICLOUD - SISTEMA PLATAFORMA RESGATE - RESGATE 2.0/mysql/"
echo "   - .env: ICLOUD - SISTEMA PLATAFORMA RESGATE - RESGATE 2.0/env/"
echo ""
echo "📊 Tamanho do backup MySQL: ${SQL_SIZE} bytes"
