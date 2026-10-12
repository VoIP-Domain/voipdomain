#!/usr/bin/env bash
# Call a single hook via the debug API (lab only).
#
# Usage:
#   hook-call.sh HOOK_NAME [JSON_PAYLOAD]
#   hook-call.sh ranges_add_validate '{"Number":""}'
#
# Requires env.local (see env.example) with credentials or VD_TOKEN.

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck disable=SC1091
source "$SCRIPT_DIR/lib/common.sh"

load_env
require_cmd curl

HOOK="${1:-}"
PAYLOAD="${2:-{}}"

if [[ -z "$HOOK" ]]; then
  echo "Usage: $(basename "$0") HOOK_NAME [JSON_PAYLOAD]" >&2
  exit 1
fi

# Warn on destructive hooks unless explicitly confirmed
case "$HOOK" in
  *_wipe|*_rebuild|servers_rebuild|firmware_*_wipe|cleanup_*)
    if [[ "${VD_I_KNOW_THIS_IS_DESTRUCTIVE:-}" != "1" ]]; then
      echo "Refusing to call destructive hook '$HOOK' without VD_I_KNOW_THIS_IS_DESTRUCTIVE=1" >&2
      exit 2
    fi
    ;;
esac

vd_login

echo "Calling hook: $HOOK"
echo "Payload: $PAYLOAD"
echo "---"

response="$(vd_curl POST "/debug/callhook/${HOOK}" \
  -H 'Content-Type: application/json' \
  -d "$PAYLOAD")"
code="$(tail -n1 <<< "$response")"
body="$(sed '$d' <<< "$response")"

echo "$body"
echo "---"
echo "HTTP $code"

exit 0
