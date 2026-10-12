#!/usr/bin/env bash
# Shared helpers for VoIP Domain lab smoke scripts.

set -euo pipefail

VD_SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
VD_ROOT="$(cd "$VD_SCRIPT_DIR/../.." && pwd)"

load_env() {
  if [[ -f "$VD_SCRIPT_DIR/env.local" ]]; then
    # shellcheck disable=SC1091
    source "$VD_SCRIPT_DIR/env.local"
  elif [[ -f "$VD_SCRIPT_DIR/env.example" ]]; then
    # shellcheck disable=SC1091
    source "$VD_SCRIPT_DIR/env.example"
  fi

  : "${VD_BASE_URL:=http://localhost}"
  : "${VD_COOKIE_JAR:=/tmp/voipdomain-test-cookies.txt}"
  : "${GEARMAN_HOST:=127.0.0.1}"
  : "${GEARMAN_PORT:=4730}"
}

pass() { echo "[PASS] $*"; }
fail() { echo "[FAIL] $*" >&2; exit 1; }
skip() { echo "[SKIP] $*"; }

require_cmd() {
  local cmd="$1"
  command -v "$cmd" >/dev/null 2>&1 || fail "Required command not found: $cmd"
}

vd_curl() {
  local method="$1"
  local path="$2"
  shift 2
  local url="${VD_BASE_URL%/}/api${path}"
  local args=(-sS -w "\n%{http_code}" -X "$method" "$url")

  if [[ -n "${VD_TOKEN:-}" ]]; then
    args+=(-H "Authorization: Bearer $VD_TOKEN")
  elif [[ -f "$VD_COOKIE_JAR" ]]; then
    args+=(-b "$VD_COOKIE_JAR" -c "$VD_COOKIE_JAR")
  fi

  args+=("$@")
  curl "${args[@]}"
}

vd_login() {
  if [[ -n "${VD_TOKEN:-}" ]]; then
    skip "VD_TOKEN set — skipping session login"
    return
  fi

  require_cmd curl
  : "${VD_USERNAME:?Set VD_USERNAME or VD_TOKEN}"
  : "${VD_PASSWORD:?Set VD_PASSWORD or VD_TOKEN}"

  local payload
  if [[ -n "${VD_DOMAIN:-}" ]]; then
    payload="$(printf '{"Username":"%s","Password":"%s","Domain":"%s"}' \
      "$VD_USERNAME" "$VD_PASSWORD" "$VD_DOMAIN")"
  else
    payload="$(printf '{"Username":"%s","Password":"%s"}' \
      "$VD_USERNAME" "$VD_PASSWORD")"
  fi

  local response code body
  response="$(vd_curl POST /session -H 'Content-Type: application/json' -d "$payload")"
  code="$(tail -n1 <<< "$response")"
  body="$(sed '$d' <<< "$response")"

  if [[ "$code" != "201" && "$code" != "200" ]]; then
    echo "$body" >&2
    fail "Login failed (HTTP $code)"
  fi
  pass "Session login (HTTP $code)"
}

assert_http() {
  local label="$1"
  local expected="$2"
  local actual="$3"
  local body="$4"

  if [[ "$actual" != "$expected" ]]; then
    echo "$body" >&2
    fail "$label — expected HTTP $expected, got $actual"
  fi
  pass "$label (HTTP $actual)"
}
