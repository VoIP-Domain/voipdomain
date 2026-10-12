#!/usr/bin/env bash
# Generate a CSV inventory of VoIP Domain hooks and filters with risk classification.
#
# Usage:
#   hook-inventory.sh [output.csv]
#
# Re-running merges manual columns (tested, test_date, notes) from the existing file.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
OUT="${1:-$ROOT/Documentations/testing/hook-inventory.csv}"
TMP="$(mktemp)"
MERGE="$(mktemp)"
trap 'rm -f "$TMP" "$MERGE"' EXIT

SCAN_DIRS=(
  "$ROOT/Administrative Interface/modules"
  "$ROOT/Daemons"
  "$ROOT/Plugins"
)

classify_name() {
  local kind="$1"
  local name="$2"

  if [[ "$kind" == "filter" ]]; then
    echo "R1"
    return
  fi

  case "$name" in
    *_wipe|*_rebuild|firmware_*_wipe|servers_rebuild|cleanup_*|*_cleanup)
      echo "R3"
      return
      ;;
    file_transfer_*|plugin_db_*|plugin_daemon_*|router_reload*|notifications_reload|\
notification_create|notification_change|notification_destroy|systemd_*|\
channels_*|queues_*|peers_*|gearman_*|notify_*|VD_NOTIFY_*)
      echo "R4"
      return
      ;;
    *_validate|*_sanitize)
      echo "R1"
      return
      ;;
    *_search|*_view|debug_*|ping|*_map|*_explain|dump_*|*_ping|*_list)
      echo "R0"
      return
      ;;
    *_add|*_change|*_remove|*_edit|*_destroy|*_create|*_toggle|*_migrate|\
*_apply|*_install|*_upgrade|*_enable|*_disable|*_import|*_export|*_deploy|*_undeploy)
      echo "R2"
      return
      ;;
  esac

  echo "R2"
}

detect_layer() {
  local file="$1"
  case "$file" in
    *"/Daemons/client-modules/"*) echo "client" ;;
    *"/Daemons/router-modules/"*|*"/Daemons/router.php") echo "router" ;;
    *"/Daemons/monitor-modules/"*|*"/Daemons/monitor.php") echo "monitor" ;;
    *"/Daemons/fastagi-modules/"*) echo "fastagi" ;;
    *"/Daemons/"*) echo "daemon" ;;
    *"/Plugins/"*) echo "plugin" ;;
    *"/Administrative Interface/"*) echo "admin" ;;
    *) echo "unknown" ;;
  esac
}

relpath() {
  local file="$1"
  echo "${file#"$ROOT"/}"
}

load_merge_keys() {
  if [[ ! -f "$OUT" ]]; then
    return
  fi
  tail -n +2 "$OUT" | while IFS=',' read -r name kind _layer _risk tested test_date notes _source; do
    name="${name//\"/}"
    kind="${kind//\"/}"
    tested="${tested//\"/}"
    test_date="${test_date//\"/}"
    notes="${notes//\"/}"
    if [[ -n "$tested" || -n "$test_date" || -n "$notes" ]]; then
      printf '%s|%s|%s|%s|%s\n' "$name" "$kind" "$tested" "$test_date" "$notes"
    fi
  done > "$MERGE"
}

lookup_merge() {
  local name="$1"
  local kind="$2"
  local key="${name}|${kind}"
  local line
  line="$(grep -F "$key|" "$MERGE" 2>/dev/null | head -n1 || true)"
  if [[ -z "$line" ]]; then
    echo ",,"
    return
  fi
  local tested test_date notes
  IFS='|' read -r _ _ tested test_date notes <<< "$line"
  tested="${tested//,/;}"
  test_date="${test_date//,/;}"
  notes="${notes//,/;}"
  echo ",$tested,$test_date,$notes"
}

scan_registrations() {
  local pattern="$1"
  local kind="$2"

  rg --no-heading -n "$pattern" \
    --glob '*.php' \
    "${SCAN_DIRS[@]}" 2>/dev/null \
    | while IFS=: read -r file line content; do
        local name
        name="$(sed -n 's/.*"\([^"]*\)".*/\1/p' <<< "$content" | head -n1)"
        [[ -z "$name" ]] && continue
        printf '%s\t%s\t%s\t%s\n' "$name" "$kind" "$file" "$line"
      done
}

load_merge_keys

{
  echo 'name,kind,layer,risk,tested,test_date,notes,source_file'

  {
    scan_registrations 'framework_add_hook[[:space:]]*\([[:space:]]*"' hook
    scan_registrations 'framework_add_filter[[:space:]]*\([[:space:]]*"' filter
  } | sort -u -t $'\t' -k1,1 -k2,2 \
    | while IFS=$'\t' read -r name kind file line; do
        layer="$(detect_layer "$file")"
        risk="$(classify_name "$kind" "$name")"
        merge="$(lookup_merge "$name" "$kind")"
        source="$(relpath "$file"):${line}"
        # shellcheck disable=SC2086
        echo "\"$name\",\"$kind\",\"$layer\",\"$risk\"${merge},\"$source\""
      done
} > "$TMP"

mv "$TMP" "$OUT"

HOOKS="$(grep -c '","hook",' "$OUT" || true)"
FILTERS="$(grep -c '","filter",' "$OUT" || true)"
TOTAL="$(tail -n +2 "$OUT" | wc -l | tr -d ' ')"

echo "Wrote $OUT"
echo "  hooks:   $HOOKS"
echo "  filters: $FILTERS"
echo "  total:   $TOTAL"
echo ""
echo "Risk summary (hooks only):"
grep '","hook",' "$OUT" | cut -d, -f4 | tr -d '"' | sort | uniq -c | sort -k2 || true
