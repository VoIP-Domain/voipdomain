#!/usr/bin/env bash
# L3 Gearman smoke: worker visibility and optional router_reload_cache.

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck disable=SC1091
source "$SCRIPT_DIR/lib/common.sh"

load_env

echo "=== Gearman smoke: gearadmin status ==="
if command -v gearadmin >/dev/null 2>&1; then
  if gearadmin --server="${GEARMAN_HOST}:${GEARMAN_PORT}" --status 2>/dev/null | head -n 20; then
    pass "gearadmin --status OK"
  else
    fail "gearadmin could not reach ${GEARMAN_HOST}:${GEARMAN_PORT}"
  fi
else
  skip "gearadmin not installed"
fi

echo "=== Gearman smoke: router_reload_cache ==="
if [[ "${VD_RUN_INFRA_HOOKS:-}" != "1" ]]; then
  skip "router_reload_cache job (set VD_RUN_INFRA_HOOKS=1)"
  exit 0
fi

if command -v gearman >/dev/null 2>&1; then
  payload="{}"
  if [[ -n "${VD_SERVER_ID:-}" ]]; then
    payload="{\"ServerID\":$VD_SERVER_ID}"
  fi
  if printf '%s' "$payload" | gearman -f router_reload_cache -h "$GEARMAN_HOST" -p "$GEARMAN_PORT" 2>/dev/null; then
    pass "gearman router_reload_cache submitted"
  else
    fail "gearman router_reload_cache failed (is voipdomain-router running?)"
  fi
else
  skip "gearman CLI not installed — use smoke-api.sh with VD_RUN_INFRA_HOOKS=1"
fi

echo ""
pass "Gearman smoke tests completed"
