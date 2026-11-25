#!/usr/bin/env bash
# Inicialização no Render: aplica as migrations pendentes, cria a massa fictícia (só se a tabela
# estiver vazia) e sobe a aplicação na porta que o Render informa.
set -euo pipefail

# Sem APP_KEY configurada, gera uma efêmera (o laboratório não guarda nada cifrado).
if [ -z "${APP_KEY:-}" ]; then
  export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
fi

php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction
php artisan migrate:status --no-interaction

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}" --no-reload
