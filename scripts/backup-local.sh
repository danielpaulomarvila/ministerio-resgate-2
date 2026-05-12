#!/bin/bash

# Caminho base: garante que o script roda sempre a partir da raiz do projeto.
cd "$(dirname "$0")/.." || exit 1

# Cria as pastas de backup, caso ainda não existam.
mkdir -p backups/mysql
mkdir -p backups/env

# Gera data e hora para nomear os arquivos sem sobrescrever backups antigos.
DATA=$(date +%Y-%m-%d_%H-%M-%S)

# Faz backup do banco MySQL do projeto.
mysqldump -u root -p ministerio_resgate > "backups/mysql/ministerio_resgate_${DATA}.sql"

# Faz backup local do arquivo .env, que contém configurações sensíveis.
cp .env "backups/env/.env_${DATA}.backup"

echo "Backup local criado com sucesso em backups/mysql e backups/env."
