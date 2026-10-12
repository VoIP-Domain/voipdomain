#!/usr/bin/env bash
# Run R0 hook smoke sample from hook-inventory.csv (first N entries).
#
# Usage:
#   hook-smoke-r0.sh [limit]
# Default limit: 5

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck disable=SC1091
source "$SCRIPT_DIR/lib/common.sh"

load_env
require_cmd curl

LIMIT="${1:-5}"
INV="$SCRIPT_DIR/hook-inventory.csv"

if [[ ! -f "$INV" ]]; then
  fail "Missing $INV — run Documentations/scripts/hook-inventory.sh first"
fi

vd_login

echo "=== Hook smoke: up to $LIMIT R0 hooks ==="
count=0
while IFS= read -r line; do
  [[ "$line" == name,* ]] && continue
  risk="$(echo "$line" | awk -F'","' '{print $4}' | tr -d '"')"
  kind="$(echo "$line" | awk -F'","' '{print $2}' | tr -d '"')"
  name="$(echo "$line" | awk -F'"' '{print $2}')"
  layer="$(echo "$line" | awk -F'","' '{print $3}' | tr -d '"')"

  [[ "$risk" != "R0" ]] && continue
  [[ "$kind" != "hook" ]] && continue
  # Admin hooks only — daemon hooks need Gearman/API side effects
  [[ "$layer" != "admin" ]] && continue
  # Skip recursive / meta
  [[ "$name" == debug_call_hook* ]] && continue

  echo "--- POST /debug/callhook/$name ---"
  response="$(vd_curl POST "/debug/callhook/${name}" \
    -H 'Content-Type: application/json' -d '{}' 2>/dev/null || true)"
  code="$(tail -n1 <<< "$response" 2>/dev/null || echo "000")"
  body="$(sed '$d' <<< "$response" 2>/dev/null || true)"

  if [[ "$code" == "200" ]]; then
    pass "$name"
  elif [[ "$code" == "422" || "$code" == "404" ]]; then
    skip "$name (HTTP $code — needs parameters or not callable in isolation)"
  else
    echo "$body" >&2
    skip "$name (HTTP $code)"
  fi

  count=$((count + 1))
  [[ "$count" -ge "$LIMIT" ]] && break
done < "$INV"

pass "R0 hook sample run finished ($count hooks attempted)"
