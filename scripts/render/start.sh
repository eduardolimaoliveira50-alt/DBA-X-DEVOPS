#!/usr/bin/env bash
# Subida do contêiner no Render (rede interna do Render, sem expor o banco):
#   1. schema próprio na instância gratuita de PostgreSQL (provisionamento idempotente);
#   2. migrations pendentes (se falhar, o contêiner não sobe e o Render mantém a versão anterior);
#   3. contas de demonstração e, só no banco vazio, a massa de produção;
#   4. conferência de integridade do estoque;
#   5. aplicação na porta que o Render informa.
set -euo pipefail

if [ -z "${APP_KEY:-}" ]; then
  export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
fi

php artisan almox:provisionar-schema --no-interaction
php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction
php artisan almox:verificar-integridade --no-interaction
php artisan migrate:status --no-interaction
php artisan config:cache --no-interaction
php artisan view:cache --no-interaction

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}" --no-reload
