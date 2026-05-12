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

# Faz backup do banco MySQL do projeto.
mysqldump -u root -p ministerio_resgate > "backups/mysql/${MYSQL_BACKUP}"

# Faz backup local do arquivo .env, que contém configurações sensíveis.
cp .env "backups/env/${ENV_BACKUP}"

# Copia os backups recém-criados para o iCloud.
cp "backups/mysql/${MYSQL_BACKUP}" "$ICLOUD_DIR/mysql/"
cp "backups/env/${ENV_BACKUP}" "$ICLOUD_DIR/env/"

echo "Backup criado com sucesso:"
echo "- Local: backups/mysql e backups/env"
echo "- iCloud: ICLOUD - SISTEMA PLATAFORMA RESGATE - RESGATE 2.0"
