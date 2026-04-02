#!/usr/bin/env bash
set -euo pipefail

COMPOSE_FILE="${COMPOSE_FILE:-compose.yaml}"
PHP_SERVICE="${PHP_SERVICE:-phpi}"

usage() {
  cat <<'EOF'
Uso:
  ./scripts/upgrade.sh <versao>
  ./scripts/upgrade.sh <versao> --allow-dirty

Exemplos:
  ./scripts/upgrade.sh v1.2.0
  ./scripts/upgrade.sh v1.2.1 --allow-dirty

O que o script faz:
  1) Valida pré-requisitos (git/docker)
  2) Busca tags remotas
  3) Cria branch local upgrade/<versao> a partir da tag
  4) Sobe/atualiza containers
  5) Instala dependências
  6) Executa migrations
  7) Compila assets (asset-map:compile)
  8) Limpa cache
EOF
}

if [[ "${1:-}" == "" || "${1:-}" == "-h" || "${1:-}" == "--help" ]]; then
  usage
  exit 0
fi

TARGET_VERSION="$1"
ALLOW_DIRTY="${2:-}"
UPGRADE_BRANCH="upgrade/${TARGET_VERSION}"

require_cmd() {
  local cmd="$1"
  if ! command -v "$cmd" >/dev/null 2>&1; then
    echo "Erro: comando '$cmd' não encontrado." >&2
    exit 1
  fi
}

require_cmd git
require_cmd docker

if ! docker compose version >/dev/null 2>&1; then
  echo "Erro: 'docker compose' não está disponível. Atualize Docker Desktop/Compose v2." >&2
  exit 1
fi

if [[ ! -d .git ]]; then
  echo "Erro: execute este script na raiz do repositório Git." >&2
  exit 1
fi

if [[ "$ALLOW_DIRTY" != "--allow-dirty" ]]; then
  if [[ -n "$(git status --porcelain)" ]]; then
    echo "Erro: há alterações locais não commitadas." >&2
    echo "Faça commit/stash ou rode novamente com --allow-dirty." >&2
    exit 1
  fi
fi

echo ">>> Buscando tags remotas..."
git fetch --tags origin

if ! git rev-parse -q --verify "refs/tags/${TARGET_VERSION}" >/dev/null; then
  echo "Erro: tag '${TARGET_VERSION}' não encontrada." >&2
  exit 1
fi

if git show-ref --verify --quiet "refs/heads/${UPGRADE_BRANCH}"; then
  echo "Erro: branch '${UPGRADE_BRANCH}' já existe." >&2
  echo "Use outra versão ou delete a branch local existente." >&2
  exit 1
fi

echo ">>> Criando branch ${UPGRADE_BRANCH} a partir da tag ${TARGET_VERSION}..."
git checkout -b "${UPGRADE_BRANCH}" "tags/${TARGET_VERSION}"

compose() {
  docker compose -f "$COMPOSE_FILE" "$@"
}

phpi() {
  compose exec "$PHP_SERVICE" "$@"
}

echo ">>> Subindo/atualizando containers..."
compose up -d --build

echo ">>> Instalando dependências..."
phpi composer install --no-interaction

echo ">>> Executando migrations..."
phpi php bin/console doctrine:migrations:migrate --no-interaction

echo ">>> Compilando assets..."
phpi php bin/console asset-map:compile

echo ">>> Limpando cache..."
phpi php bin/console cache:clear

echo
echo "Upgrade concluído para ${TARGET_VERSION}."
echo "Branch atual: ${UPGRADE_BRANCH}"
