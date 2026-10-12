#!/usr/bin/env bash
# L1/L2 API smoke tests: login, debug ping, API map, sample R0/R1 hook calls.

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck disable=SC1091
source "$SCRIPT_DIR/lib/common.sh"

load_env
require_cmd curl

echo "=== API smoke: authenticate ==="
vd_login

echo "=== API smoke: GET /debug/ping ==="
response="$(vd_curl GET /debug/ping)"
code="$(tail -n1 <<< "$response")"
body="$(sed '$d' <<< "$response")"
assert_http "debug_ping" "200" "$code" "$body"
if [[ "$body" != *"Pong"* ]]; then
  fail "debug_ping body does not contain 'Pong': $body"
fi

echo "=== API smoke: GET /debug/api/map ==="
response="$(vd_curl GET /debug/api/map)"
code="$(tail -n1 <<< "$response")"
body="$(sed '$d' <<< "$response")"
assert_http "debug_api_map" "200" "$code" "$body"
if [[ "$body" != "{"* ]]; then
  fail "debug_api_map did not return JSON object"
fi

echo "=== API smoke: POST /debug/callhook/debug_ping (R0) ==="
response="$(vd_curl POST /debug/callhook/debug_ping \
  -H 'Content-Type: application/json' -d '{}')"
code="$(tail -n1 <<< "$response")"
body="$(sed '$d' <<< "$response")"
assert_http "callhook debug_ping" "200" "$code" "$body"

echo "=== API smoke: POST /debug/callhook/ranges_add_validate (R1, expect 422) ==="
response="$(vd_curl POST /debug/callhook/ranges_add_validate \
  -H 'Content-Type: application/json' -d '{}')"
code="$(tail -n1 <<< "$response")"
body="$(sed '$d' <<< "$response")"
if [[ "$code" == "422" ]]; then
  pass "ranges_add_validate returned 422 for empty payload"
else
  echo "$body" >&2
  fail "ranges_add_validate — expected HTTP 422, got $code"
fi

if [[ -n "${VD_RUN_INFRA_HOOKS:-}" ]]; then
  echo "=== API smoke: POST /debug/callhook/router_reload_cache (R4, opt-in) ==="
  payload="{}"
  if [[ -n "${VD_SERVER_ID:-}" ]]; then
    payload="{\"ServerID\":\"$VD_SERVER_ID\"}"
  fi
  response="$(vd_curl POST /debug/callhook/router_reload_cache \
    -H 'Content-Type: application/json' -d "$payload")"
  code="$(tail -n1 <<< "$response")"
  body="$(sed '$d' <<< "$response")"
  assert_http "router_reload_cache" "200" "$code" "$body"
else
  skip "router_reload_cache (set VD_RUN_INFRA_HOOKS=1 to enable)"
fi

echo ""
pass "API smoke tests completed"
