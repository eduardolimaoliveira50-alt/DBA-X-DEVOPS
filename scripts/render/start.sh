#!/usr/bin/env bash
# Subida do contêiner no Render. No plano gratuito o serviço "dorme" sem visitas e esta subida roda de novo a
# cada acesso depois disso: antes de abrir a porta, só o indispensável.
#   1. schema próprio na instância gratuita de PostgreSQL e limpeza de sessões órfãs de subidas anteriores;
#   2. migrations pendentes (se falhar, o contêiner não sobe e o Render mantém a versão anterior);
#   3. aplicação na porta que o Render informa;
#   4. em segundo plano: perfis e contas de demonstração, carga inicial (só no banco vazio) e integridade.
set -euo pipefail

if [ -z "${APP_KEY:-}" ]; then
  export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
fi

php artisan almox:provisionar-schema --no-interaction
php artisan migrate --force --no-interaction

php artisan serve --host=0.0.0.0 --port="${PORT:-10000}" --no-reload &
SERVIDOR=$!

(
  ALMOX_SEM_MASSA=1 php artisan db:seed --force --no-interaction \
    && php artisan db:seed --class=MassaProducaoSeeder --force --no-interaction \
    && php artisan almox:verificar-integridade --no-interaction
) || echo "Tarefas de segundo plano falharam (a aplicação continua no ar; a próxima subida tenta de novo)." >&2

wait "$SERVIDOR"
