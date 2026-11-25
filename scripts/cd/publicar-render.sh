#!/usr/bin/env bash
# Publicação pública do laboratório: leva o commit aprovado no PR até a URL do Render.
#
#   1. espelha o commit no GitHub público (o Render lê o código de lá; ninguém desenvolve no GitHub);
#   2. pede ao Render, pela API, a implantação DESSE commit e acompanha o build e a subida;
#   3. abre a URL publicada e só termina com sucesso se o painel mostrar o mesmo commit.
#
# Na subida, o contêiner aplica as migrations pendentes no PostgreSQL do Render (scripts/render/start.sh).
#
# CUSTO ZERO: repositório público no GitHub + Render no plano free (web e PostgreSQL). Nenhum recurso
# do Azure. Os tokens ficam como variáveis SECRETAS do pipeline (gratuito) e nunca vão para o log.
set -euo pipefail

: "${GITHUB_TOKEN:?variável secreta GITHUB_TOKEN não configurada no pipeline}"
: "${RENDER_API_KEY:?variável secreta RENDER_API_KEY não configurada no pipeline}"
: "${GITHUB_REPO:?}" "${RENDER_SERVICE_ID:?}" "${RENDER_URL:?}"

API=https://api.render.com/v1
COMMIT=$(git rev-parse HEAD)
CURTO=${COMMIT:0:7}
INICIO=$(date +%s)
echo "Commit aprovado: $COMMIT"

render() { curl -fsS -H "Authorization: Bearer $RENDER_API_KEY" -H 'Accept: application/json' "$@"; }

echo "##[section]1/3 Espelho no GitHub (${GITHUB_REPO})"
AUTH=$(printf 'x-access-token:%s' "$GITHUB_TOKEN" | base64 -w0)
# O primeiro -c zera cabeçalhos herdados do checkout do Azure; o segundo autentica só no GitHub.
git -c http.extraHeader= -c "http.extraHeader=Authorization: Basic ${AUTH}" \
  push --force "https://github.com/${GITHUB_REPO}.git" "${COMMIT}:refs/heads/main"

echo "##[section]2/3 Implantação no Render do commit ${CURTO}"
DEPLOY=$(render -X POST -H 'Content-Type: application/json' \
  -d "{\"commitId\":\"${COMMIT}\"}" "$API/services/${RENDER_SERVICE_ID}/deploys" | jq -r .id)
echo "Deploy ${DEPLOY} solicitado"

STATUS=""
for _ in $(seq 1 80); do   # até 20 min (build do Docker no plano free é lento)
  NOVO=$(render "$API/services/${RENDER_SERVICE_ID}/deploys/${DEPLOY}" | jq -r .status)
  if [ "$NOVO" != "$STATUS" ]; then
    echo "$(date +%H:%M:%S) ${NOVO}"
    STATUS=$NOVO
  fi
  case "$STATUS" in
    live) break ;;
    build_failed|update_failed|pre_deploy_failed|canceled|deactivated)
      echo "##vso[task.logissue type=error]Render recusou a implantação (${STATUS}). Logs: https://dashboard.render.com/web/${RENDER_SERVICE_ID}/deploys/${DEPLOY}"
      exit 1 ;;
  esac
  sleep 15
done
if [ "$STATUS" != "live" ]; then
  echo "##vso[task.logissue type=error]Tempo esgotado esperando o Render (último estado: ${STATUS})."
  exit 1
fi

echo "##[section]3/3 Conferência da URL publicada"
OK=false
for _ in $(seq 1 12); do
  if PAGINA=$(curl -fsS --max-time 60 "$RENDER_URL/") && grep -q "<code>${CURTO}</code>" <<<"$PAGINA"; then
    OK=true
    break
  fi
  sleep 10
done
if [ "$OK" != true ]; then
  echo "##vso[task.logissue type=error]${RENDER_URL} não está servindo o commit ${CURTO}."
  exit 1
fi
FASE=$(grep -o 'data-fase="[^"]*"' <<<"$PAGINA" | head -1 | cut -d'"' -f2 || true)
DURACAO=$(( $(date +%s) - INICIO ))
echo "Publicado: ${RENDER_URL} (commit ${CURTO}, fase ${FASE:-?}) em ${DURACAO}s"

RESUMO="$(mktemp -d)/publicacao.md"
cat > "$RESUMO" <<EOF
## Publicação pública (Render, plano gratuito)

| Item | Valor |
|---|---|
| URL | ${RENDER_URL} |
| Commit publicado | \`${CURTO}\` (o mesmo aprovado no PR) |
| Fase do Expand-Contract | ${FASE:-não identificada} |
| Tempo do espelho até a URL no ar | ${DURACAO}s |
EOF
echo "##vso[task.uploadsummary]${RESUMO}"
