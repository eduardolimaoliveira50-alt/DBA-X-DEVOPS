#!/usr/bin/env bash
# =============================================================================
# Provisionamento do banco: a parte que é do DBA, feita UMA vez por ambiente.
# Depois disso, todo o esquema (tabelas, índices, restrições) vem das migrations do Laravel.
#
#   - almox_migrator: dono do banco; só o pipeline usa (aplica as migrations).
#   - almox_app:      a aplicação; só DML (SELECT/INSERT/UPDATE/DELETE). Não cria, altera nem apaga tabela.
#   - privilégios padrão: tudo que o migrator criar no futuro já nasce liberado para o app (DML).
#
# uso (como superusuário do PostgreSQL):  sudo -u postgres bash scripts/dba/criar-banco.sh <banco>
# senhas: ALMOX_MIGRATOR_PASSWORD e ALMOX_APP_PASSWORD (no CI, valores descartáveis do agente).
# =============================================================================
set -euo pipefail

BANCO="${1:?informe o nome do banco}"
MIGRATOR_PASS="${ALMOX_MIGRATOR_PASSWORD:?}"
APP_PASS="${ALMOX_APP_PASSWORD:?}"

psql -v ON_ERROR_STOP=1 -q <<SQL
DO \$\$
BEGIN
  IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'almox_migrator') THEN
    CREATE ROLE almox_migrator LOGIN PASSWORD '${MIGRATOR_PASS}';
  END IF;
  IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'almox_app') THEN
    CREATE ROLE almox_app LOGIN PASSWORD '${APP_PASS}';
  END IF;
END
\$\$;
SQL

if ! psql -tAc "SELECT 1 FROM pg_database WHERE datname = '${BANCO}'" | grep -q 1; then
  psql -v ON_ERROR_STOP=1 -q -c "CREATE DATABASE ${BANCO} OWNER almox_migrator"
fi

psql -v ON_ERROR_STOP=1 -q -d "${BANCO}" <<SQL
REVOKE ALL ON DATABASE ${BANCO} FROM PUBLIC;
GRANT CONNECT ON DATABASE ${BANCO} TO almox_app;
GRANT USAGE ON SCHEMA public TO almox_app;
ALTER DEFAULT PRIVILEGES FOR ROLE almox_migrator IN SCHEMA public
  GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO almox_app;
ALTER DEFAULT PRIVILEGES FOR ROLE almox_migrator IN SCHEMA public
  GRANT USAGE, SELECT ON SEQUENCES TO almox_app;
SQL

echo "Banco ${BANCO} pronto: dono almox_migrator (migrations), almox_app só com DML."
