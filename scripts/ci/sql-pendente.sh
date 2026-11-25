#!/usr/bin/env bash
# Mostra o SQL que as migrations NOVAS vão executar, contra um banco no estado anterior à mudança
# (seção 2.3.4: "--pretend permite inspecionar o SQL pendente em uma base no estado anterior").
#
# 1. tira temporariamente as migrations novas da pasta;
# 2. migra o banco de testes até o estado da branch de destino;
# 3. devolve as migrations e roda `migrate --pretend` (só imprime, não executa);
# 4. passa o SQL pelo Squawk (linter de migrations PostgreSQL), apenas como relatório.
#
# --pretend NÃO comprova segurança nem substitui a execução real: os passos seguintes do pipeline
# aplicam as migrations de verdade e rodam os testes.
#
# uso: scripts/ci/sql-pendente.sh <ref-base>
set -euo pipefail
BASE="${1:-origin/main}"
mapfile -t NOVAS < <(git diff --name-only --diff-filter=A "$BASE"...HEAD -- database/migrations | grep '\.php$' || true)

if [ ${#NOVAS[@]} -eq 0 ]; then
  echo "Nenhuma migration nova em relação a $BASE."
  exit 0
fi

TMP="$(mktemp -d)"
for f in "${NOVAS[@]}"; do mkdir -p "$TMP/$(dirname "$f")"; mv "$f" "$TMP/$f"; done
trap 'for f in "${NOVAS[@]}"; do [ -f "$TMP/$f" ] && mv "$TMP/$f" "$f"; done' EXIT

echo "== banco de testes no estado de $BASE"
php artisan migrate --env=testing --force --no-interaction

for f in "${NOVAS[@]}"; do mv "$TMP/$f" "$f"; done

echo
echo "== SQL pendente (migrate --pretend)"
php artisan migrate --env=testing --pretend --no-interaction | tee "$TMP/pretend.txt"

# Linhas de SQL do --pretend começam com "⇂" no Laravel 11+.
sed -n 's/^[[:space:]]*⇂[[:space:]]*//p' "$TMP/pretend.txt" | sed 's/[[:space:]]*$/;/' > "$TMP/pendente.sql"
echo
echo "== Squawk (relatório; não bloqueia: o bloqueio é do classificar-migrations.php)"
if [ -s "$TMP/pendente.sql" ]; then
  npx --yes squawk-cli "$TMP/pendente.sql" || echo "(Squawk apontou ressalvas acima; avaliar na revisão)"
else
  echo "(sem SQL para analisar)"
fi
