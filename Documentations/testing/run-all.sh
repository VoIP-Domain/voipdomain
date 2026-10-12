#!/usr/bin/env bash
# Run all automated smoke tests in order.

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

echo "VoIP Domain — automated smoke test run"
echo "======================================="
echo ""

bash "$ROOT/Documentations/scripts/hook-inventory.sh" "$SCRIPT_DIR/hook-inventory.csv"
echo ""

bash "$SCRIPT_DIR/smoke-static.sh"
echo ""

bash "$SCRIPT_DIR/smoke-api.sh"
echo ""

bash "$SCRIPT_DIR/smoke-gearman.sh"
echo ""

if [[ "${VD_RUN_R0_SAMPLE:-}" == "1" ]]; then
  bash "$SCRIPT_DIR/hook-smoke-r0.sh" "${VD_R0_SAMPLE_LIMIT:-5}"
  echo ""
fi

echo "All smoke stages finished."
