#!/usr/bin/env bash
# L0 static checks: PHP syntax on Daemons and Administrative Interface.

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# shellcheck disable=SC1091
source "$SCRIPT_DIR/lib/common.sh"

load_env

require_cmd php
require_cmd find

ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"
FAIL=0

echo "=== Static: PHP syntax (Daemons) ==="
while IFS= read -r -d '' file; do
  if ! php -l "$file" >/dev/null 2>&1; then
    echo "[FAIL] $file"
    php -l "$file" || true
    FAIL=1
  fi
done < <(find "$ROOT/Daemons" -name '*.php' -print0)

echo "=== Static: PHP syntax (Administrative Interface, excluding vendors) ==="
while IFS= read -r -d '' file; do
  if ! php -l "$file" >/dev/null 2>&1; then
    echo "[FAIL] $file"
    php -l "$file" || true
    FAIL=1
  fi
done < <(find "$ROOT/Administrative Interface" -name '*.php' \
  ! -path '*/vendors/*' -print0)

if [[ "$FAIL" -ne 0 ]]; then
  fail "PHP syntax errors found"
fi

pass "All scanned PHP files OK"

echo "=== Static: required spool directory ==="
if [[ -d /var/spool/voipdomain/file-transfer ]]; then
  pass "/var/spool/voipdomain/file-transfer exists"
else
  skip "file-transfer spool missing (install tmpfiles or create manually in lab)"
fi

echo "=== Static: hook inventory CSV ==="
INV="$ROOT/Documentations/testing/hook-inventory.csv"
if [[ -f "$INV" ]]; then
  lines="$(tail -n +2 "$INV" | wc -l | tr -d ' ')"
  pass "hook-inventory.csv present ($lines entries)"
else
  skip "Run Documentations/scripts/hook-inventory.sh to generate inventory"
fi

exit 0
