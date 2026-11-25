#!/usr/bin/env bash
# Subida do contêiner no Render (rede interna do Render, sem expor o banco):
#   1. schema próprio na instância gratuita de PostgreSQL e limpeza de sessões órfãs de subidas anteriores;
#   2. migrations pendentes (se falhar, o contêiner não sobe e o Render mantém a versão anterior);
#   3. perfis e contas de demonstração; conferência de integridade do estoque;
#   4. aplicação na porta que o Render informa;
#   5. só num banco vazio: a carga inicial (massa de produção), em segundo plano, numa transação única.
set -euo pipefail

if [ -z "${APP_KEY:-}" ]; then
  export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
fi

php artisan almox:provisionar-schema --no-interaction
php artisan migrate --force --no-interaction
ALMOX_SEM_MASSA=1 php artisan db:seed --force --no-interaction
php artisan almox:verificar-integridade --no-interaction
php artisan migrate:status --no-interaction
php artisan view:cache --no-interaction

php artisan serve --host=0.0.0.0 --port="${PORT:-10000}" --no-reload &
SERVIDOR=$!

if php artisan db:seed --class=MassaProducaoSeeder --force --no-interaction; then
  php artisan almox:verificar-integridade --no-interaction || true
else
  echo "Carga inicial falhou (a aplicação continua no ar; a próxima subida tenta de novo)." >&2
fi

wait "$SERVIDOR"
