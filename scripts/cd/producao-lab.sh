#!/usr/bin/env bash
# CD da "produção do laboratório" (seções 2.3.1 passo 4, 2.4.1 e 2.4.2 do trabalho).
# Roda num agente Microsoft-hosted descartável: nenhum servidor, banco ou recurso do Azure é usado.
set -euo pipefail

PG="sudo -u postgres psql -v ON_ERROR_STOP=1 -qtA"
MIGRATOR_PW="migrator-$(openssl rand -hex 8)"   # senhas efêmeras, só existem neste job
APP_PW="app-$(openssl rand -hex 8)"
RESUMO="$(mktemp)"
anotar() { echo "$*" | tee -a "$RESUMO"; }
segundos() { date +%s.%N; }
dur() { awk -v a="$1" -v b="$2" 'BEGIN{printf "%.2f", b-a}'; }

como_migrator() { DB_USERNAME=lab_migrator DB_PASSWORD="$MIGRATOR_PW" "$@"; }
como_app()      { DB_USERNAME=lab_app      DB_PASSWORD="$APP_PW"      "$@"; }
psql_app()      { PGPASSWORD="$APP_PW" psql -h 127.0.0.1 -U lab_app -d lab_dba -v ON_ERROR_STOP=1 -qtA "$@"; }

echo "== 1. PostgreSQL e contas (conta de serviço x conta da aplicação)"
sudo systemctl start postgresql.service
pg_isready -h 127.0.0.1 -t 30
$PG <<SQL
CREATE ROLE lab_migrator LOGIN PASSWORD '$MIGRATOR_PW';
CREATE ROLE lab_app LOGIN PASSWORD '$APP_PW';
CREATE DATABASE lab_dba OWNER lab_migrator;
SQL
$PG -d lab_dba <<'SQL'
REVOKE ALL ON DATABASE lab_dba FROM PUBLIC;
GRANT CONNECT ON DATABASE lab_dba TO lab_app;
REVOKE CREATE ON SCHEMA public FROM PUBLIC;
GRANT USAGE ON SCHEMA public TO lab_app;
-- tudo o que a conta de migração criar fica acessível à aplicação só para DML
ALTER DEFAULT PRIVILEGES FOR ROLE lab_migrator IN SCHEMA public GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO lab_app;
ALTER DEFAULT PRIVILEGES FOR ROLE lab_migrator IN SCHEMA public GRANT USAGE, SELECT, UPDATE ON SEQUENCES TO lab_app;
SQL

echo "== 2. Aplicação (build de produção)"
composer install --no-dev --no-interaction --prefer-dist --no-progress --optimize-autoloader
cp .env.example .env
sed -i 's/^APP_ENV=.*/APP_ENV=production/; s/^APP_DEBUG=.*/APP_DEBUG=false/; s/^SESSION_DRIVER=.*/SESSION_DRIVER=array/; s/^CACHE_STORE=.*/CACHE_STORE=array/; s/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=sync/' .env
php artisan key:generate --force --no-interaction

echo "== 3. Produção no estado da versão anterior da main, com massa fictícia"
mapfile -t NOVAS < <(git diff --name-only --diff-filter=A HEAD~1 HEAD -- database/migrations 2>/dev/null | grep '\.php$' || true)
TMP="$(mktemp -d)"
for f in "${NOVAS[@]}"; do mkdir -p "$TMP/$(dirname "$f")"; mv "$f" "$TMP/$f"; done
como_migrator php artisan migrate --force --no-interaction
como_app php artisan db:seed --force --no-interaction
for f in "${NOVAS[@]}"; do mv "$TMP/$f" "$f"; done
ANTES=$(psql_app -c "SELECT count(*) FROM clientes")
echo "clientes antes da implantação: $ANTES"

echo "== 4. Backup imediatamente antes da mudança"
T=$(segundos)
sudo -u postgres pg_dump -Fc -d lab_dba -f /tmp/antes-da-mudanca.dump
T_BACKUP=$(dur "$T" "$(segundos)")

echo "== 5. Implantação: migrations novas com a conta de serviço"
como_migrator php artisan migrate:status --no-interaction | tail -n +1
T=$(segundos)
como_migrator php artisan migrate --force --no-interaction
T_MIGRATE=$(dur "$T" "$(segundos)")
como_migrator php artisan migrate:status --no-interaction

echo "== 6. Tarefas pós-implantação (conta da aplicação, só DML)"
COMANDOS="$(php artisan list --raw --no-interaction | awk '{print $1}')"
if grep -qx 'clientes:backfill-email-corporativo' <<<"$COMANDOS"; then
  como_app php artisan clientes:backfill-email-corporativo --no-interaction
fi
if grep -qx 'clientes:verificar-integridade' <<<"$COMANDOS"; then
  como_app php artisan clientes:verificar-integridade --no-interaction
fi
DEPOIS=$(psql_app -c "SELECT count(*) FROM clientes")
[ "$ANTES" = "$DEPOIS" ] || { echo "##vso[task.logissue type=error]Quantidade de clientes mudou: $ANTES -> $DEPOIS"; exit 1; }

echo "== 7. Menor privilégio: a conta da aplicação NÃO pode alterar o esquema"
for sql in "DROP TABLE clientes" "ALTER TABLE clientes ADD COLUMN invasor int" "CREATE TABLE invasora (id int)" "TRUNCATE clientes"; do
  if psql_app -c "$sql" >/dev/null 2>&1; then
    echo "##vso[task.logissue type=error]lab_app conseguiu executar: $sql"; exit 1
  fi
  echo "bloqueado para lab_app (ok): $sql"
done

echo "== 8. Ensaio de restauração (backup testado, seção 2.4.2)"
sudo -u postgres createdb lab_dba_restaurado
T=$(segundos)
sudo -u postgres pg_restore -d lab_dba_restaurado --no-owner /tmp/antes-da-mudanca.dump
T_RESTORE=$(dur "$T" "$(segundos)")
RESTAURADO=$($PG -d lab_dba_restaurado -c "SELECT count(*) FROM clientes")
[ "$RESTAURADO" = "$ANTES" ] || { echo "##vso[task.logissue type=error]Restauração divergente: $RESTAURADO de $ANTES"; exit 1; }

{
  echo "## Produção do laboratório"
  echo
  echo "| Item | Resultado |"
  echo "|---|---|"
  echo "| Commit implantado | \`$(git rev-parse --short HEAD)\` |"
  echo "| Migrations novas | ${#NOVAS[@]}: $(printf '%s ' "${NOVAS[@]/#database\/migrations\//}") |"
  echo "| Clientes (antes / depois) | $ANTES / $DEPOIS |"
  echo "| Backup antes da mudança | ${T_BACKUP}s |"
  echo "| Aplicação das migrations (conta lab_migrator) | ${T_MIGRATE}s |"
  echo "| Conta da aplicação (lab_app) tentando DDL | bloqueada (DROP, ALTER, CREATE, TRUNCATE) |"
  echo "| Ensaio de restauração do backup | ${T_RESTORE}s, $RESTAURADO registros conferidos |"
  echo
  echo "RTO medido no ensaio: ${T_RESTORE}s para $ANTES registros. RPO do ensaio: zero (backup imediatamente anterior à mudança)."
} > "$RESUMO"
cat "$RESUMO"
echo "##vso[task.uploadsummary]$RESUMO"
