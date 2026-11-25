#!/usr/bin/env bash
# =============================================================================
# ENSAIO EM STAGING: aplica as migrations novas num banco com o FORMATO DA PRODUÇÃO.
#
# Os testes rodam num banco vazio, e banco vazio aceita qualquer migration. Os problemas reais aparecem
# com os dados de produção: código repetido que impede um UNIQUE, linha antiga com NULL que impede um
# NOT NULL, tabela grande que fica travada enquanto um índice é criado. Por isso, a cada PR:
#
#   1. o DBA provisiona o banco de staging (scripts/dba/criar-banco.sh) - mesmo script da produção;
#   2. o banco é levado ao estado da branch de destino (versão em produção) com a massa de produção
#      (MassaProducaoSeeder: ~300 mil movimentações, códigos repetidos, fornecedores sem CNPJ);
#   3. o SQL pendente (migrate --pretend) passa pela análise de bloqueio com o TAMANHO REAL das tabelas;
#   4. as migrations novas são aplicadas de verdade, cronometradas;
#   5. a integridade do estoque é conferida com a conta da aplicação (só DML).
#   (modo main) backup antes da mudança e restauração cronometrada depois (RTO medido).
#
# Falhou? O log diz o motivo e o que o DBA precisa decidir. O PR fica bloqueado até um novo push verde.
#
# uso: bash scripts/ci/ensaio-staging.sh <ref-base> [pr|main]
# =============================================================================
set -uo pipefail

BASE="${1:?ref base}"
MODO="${2:-pr}"
RAIZ="$(pwd)"
TMP="$(mktemp -d)"
RESUMO="$TMP/ensaio-staging.md"
LIMITE="${ALMOX_LIMITE_TABELA_GRANDE:-100000}"
BANCO=almox_staging
: "${ALMOX_MIGRATOR_PASSWORD:?}" "${ALMOX_APP_PASSWORD:?}"

agora() { date +%s.%N; }
dur() { awk -v a="$1" -v b="$2" 'BEGIN{printf "%.1f", b-a}'; }
md() { echo "$*" >> "$RESUMO"; }
falhar() {
  echo "##vso[task.logissue type=error]$1"
  md ""; md "### ❌ $1"
  publicar_resumo; exit 1
}
publicar_resumo() { [ -n "${AGENT_TEMPDIRECTORY:-}" ] && cp "$RESUMO" "$AGENT_TEMPDIRECTORY/" && echo "##vso[task.uploadsummary]$AGENT_TEMPDIRECTORY/ensaio-staging.md"; }

# conexões: migrations com a conta de serviço; a aplicação com a conta só de DML
ambiente() {
  export APP_ENV=staging APP_KEY="base64:$(head -c 32 /dev/urandom | base64)" APP_DEBUG=false \
         CACHE_STORE=array SESSION_DRIVER=array QUEUE_CONNECTION=sync LOG_CHANNEL=stderr \
         DB_CONNECTION=pgsql DB_HOST=127.0.0.1 DB_PORT=5432 DB_DATABASE=$BANCO
}
como_migrator() { DB_USERNAME=almox_migrator DB_PASSWORD="$ALMOX_MIGRATOR_PASSWORD" "$@"; }
como_app()      { DB_USERNAME=almox_app DB_PASSWORD="$ALMOX_APP_PASSWORD" "$@"; }
psql_staging()  { PGPASSWORD="$ALMOX_MIGRATOR_PASSWORD" psql -h 127.0.0.1 -U almox_migrator -d $BANCO -v ON_ERROR_STOP=1 -qtA "$@"; }
ambiente

md "## Ensaio em staging (formato da produção)"
md ""
md "Base: \`$BASE\` · modo: **$MODO**"
md ""

echo "== 1. DBA: provisionamento do banco de staging (uma vez por ambiente)"
sudo -u postgres env ALMOX_MIGRATOR_PASSWORD="$ALMOX_MIGRATOR_PASSWORD" ALMOX_APP_PASSWORD="$ALMOX_APP_PASSWORD" \
  bash "$RAIZ/scripts/dba/criar-banco.sh" $BANCO || falhar "Provisionamento do banco de staging falhou."

echo "== 2. Staging no estado de $BASE (versão em produção) com a massa de produção"
git worktree add --detach "$TMP/base" "$BASE" >/dev/null 2>&1 || falhar "Não foi possível obter $BASE."
(cd "$TMP/base" && composer install --no-dev --no-interaction --prefer-dist --no-progress -q) || falhar "composer install da versão base falhou."
T=$(agora)
(cd "$TMP/base" && como_migrator php artisan migrate --force --no-interaction) || falhar "As migrations da versão em produção ($BASE) não aplicam num banco novo."
(cd "$TMP/base" && como_migrator php artisan db:seed --force --no-interaction) || falhar "A carga da massa de produção falhou."
T_CARGA=$(dur "$T" "$(agora)")
psql_staging -F ' ' -c "SELECT relname, reltuples::bigint FROM pg_class WHERE relkind = 'r' AND relnamespace = 'public'::regnamespace ORDER BY reltuples DESC" > "$TMP/tamanhos.txt"
echo "Tamanho das tabelas (pg_class.reltuples):"; cat "$TMP/tamanhos.txt"
md "**Massa de produção carregada em ${T_CARGA}s:** $(awk '$1 ~ /^(movimentacoes|produtos|fornecedores)$/ {printf "%s %s · ", $1, $2}' "$TMP/tamanhos.txt" | sed 's/ · $//')"
md ""

echo "== 3. Migrations pendentes e SQL que vai rodar"
como_migrator php artisan migrate:status --no-interaction | tee "$TMP/status.txt"
PENDENTES=$(grep -c 'Pending' "$TMP/status.txt" || true)
if [ "$PENDENTES" -eq 0 ]; then
  md "- Nenhuma migration nova: nada a ensaiar no esquema."
else
  md "**Migrations novas ($PENDENTES):**"; md ""
  grep 'Pending' "$TMP/status.txt" | awk '{print "- `"$1"`"}' >> "$RESUMO"; md ""
fi

BLOQUEIO_RISCO=0
if [ "$PENDENTES" -gt 0 ]; then
  como_migrator php artisan migrate --pretend --force --no-interaction > "$TMP/pretend.txt" 2>&1
  sed -n 's/^[[:space:]]*⇂[[:space:]]*//p' "$TMP/pretend.txt" > "$TMP/pendente.sql"
  echo "--- SQL pendente:"; cat "$TMP/pendente.sql"
  md "<details><summary>SQL pendente (migrate --pretend)</summary>"; md ""; md '```sql'; cat "$TMP/pendente.sql" >> "$RESUMO"; md '```'; md "</details>"; md ""

  echo "== 4. Análise de bloqueio com o tamanho real das tabelas"
  php "$RAIZ/scripts/ci/riscos-de-bloqueio.php" "$TMP/pendente.sql" "$TMP/tamanhos.txt" "$LIMITE" "$RESUMO" || BLOQUEIO_RISCO=1

  echo "== Squawk (linter de migrations PostgreSQL; relatório)"
  sed 's/[[:space:]]*$/;/' "$TMP/pendente.sql" > "$TMP/squawk.sql"
  timeout 120 npx --yes squawk-cli "$TMP/squawk.sql" || echo "(Squawk apontou ressalvas acima)"
fi

if [ "$MODO" = "main" ]; then
  echo "== Backup imediatamente antes da mudança"
  T=$(agora); sudo -u postgres pg_dump -Fc -d $BANCO -f "$TMP/antes.dump" || falhar "Backup falhou."
  T_BACKUP=$(dur "$T" "$(agora)")
fi

echo "== 5. Aplica as migrations novas (conta de serviço almox_migrator)"
T=$(agora)
como_migrator php artisan migrate --force --no-interaction > "$TMP/migrate.txt" 2>&1
RC=$?
T_MIGRATE=$(dur "$T" "$(agora)")
cat "$TMP/migrate.txt"

if [ $RC -ne 0 ]; then
  ESTADO=$(grep -o 'SQLSTATE\[[0-9A-Z]*\]' "$TMP/migrate.txt" | head -1 | tr -cd '0-9A-Z' | sed 's/^SQLSTATE//')
  case "$ESTADO" in
    23505) DIAG="dados DUPLICADOS na produção impedem a restrição de unicidade. O DBA decide como sanear (consolidar, renomear ou descartar) antes de criar o índice único." ;;
    23502) DIAG="há linhas ANTIGAS com valor nulo na coluna que passaria a ser obrigatória. Não dá para inventar o dado: o DBA define a estratégia (restrição NOT VALID para os novos + saneamento dos antigos)." ;;
    23503) DIAG="há referências órfãs na produção que impedem a chave estrangeira." ;;
    23514) DIAG="há linhas que violam a regra CHECK nova." ;;
    25001) DIAG="CREATE INDEX CONCURRENTLY não roda dentro de transação: a migration precisa de public \$withinTransaction = false." ;;
    *) DIAG="veja o erro do PostgreSQL no log desta etapa." ;;
  esac
  ERRO=$(grep -m1 -o 'SQLSTATE.*' "$TMP/migrate.txt" | cut -c1-300)
  md "**Resultado da migração:** falhou em ${T_MIGRATE}s"; md ""; md '```'; md "$ERRO"; md '```'
  falhar "Ensaio em staging: a migration funciona no banco vazio dos testes, mas FALHA com os dados de produção (SQLSTATE $ESTADO): $DIAG Intervenção do DBA necessária."
fi
md "**Migrations aplicadas em ${T_MIGRATE}s** (o tempo que as tabelas envolvidas ficaram com lock)."; md ""

echo "== 6. Integridade do estoque, com a conta da aplicação (só DML)"
como_app php artisan almox:verificar-integridade --no-interaction || falhar "Integridade do estoque violada depois das migrations."
md "- ✅ Integridade: saldo de cada produto = soma das movimentações (conferido com a conta \`almox_app\`)."

if PGPASSWORD="$ALMOX_APP_PASSWORD" psql -h 127.0.0.1 -U almox_app -d $BANCO -c "DROP TABLE movimentacoes" >/dev/null 2>&1; then
  falhar "A conta da aplicação conseguiu apagar uma tabela: privilégios errados."
fi
md "- ✅ Menor privilégio: \`almox_app\` não consegue DROP/ALTER (só DML)."

if [ "$MODO" = "main" ]; then
  echo "== Restauração cronometrada do backup (ensaio do plano de recuperação)"
  sudo -u postgres psql -qc "CREATE DATABASE almox_restore OWNER almox_migrator"
  T=$(agora); sudo -u postgres pg_restore -d almox_restore --no-owner --role=almox_migrator "$TMP/antes.dump" || falhar "Restauração do backup falhou."
  T_RESTORE=$(dur "$T" "$(agora)")
  ORIG=$(sudo -u postgres psql -qtA -d almox_restore -c "SELECT count(*) FROM movimentacoes")
  md "- ✅ Backup antes da mudança em ${T_BACKUP}s; restauração completa em **${T_RESTORE}s** ($ORIG movimentações recuperadas) = RTO medido."
fi

if [ $BLOQUEIO_RISCO -ne 0 ]; then
  falhar "Ensaio em staging: a migration aplica, mas travaria uma tabela grande em produção (ver Análise de bloqueio). Intervenção do DBA necessária."
fi

md ""; md "### ✅ Ensaio aprovado: as migrations aplicam sobre os dados de produção, sem travar tabela grande."
publicar_resumo
exit 0
