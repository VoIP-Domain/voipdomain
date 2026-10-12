#!/usr/bin/env python3
#
# VoIP Domain — one-shot patch for hardcoded timezone map blocks in WebUI modules.
#
# Usage:
#   patch-timezone-webui.py
#
# Replaces static <area> tags inside timezone imagemaps with a call to
# voipdomain_timezonepicker_map_markup (). Select lists were patched separately.
#
# Copyright (C) 2016-2026 Ernani José Camargo Azevedo
# SPDX-License-Identifier: GPL-3.0-or-later

from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
FILES = [
    ROOT / "Administrative Interface/modules/profiles/webui.php",
    ROOT / "Administrative Interface/modules/tenants/webui.php",
    ROOT / "Administrative Interface/modules/config/webui.php",
]

MAP_REPLACEMENT = "  $output .= voipdomain_timezonepicker_map_markup ();\n"


def patch_maps(path):
    lines = path.read_text(encoding="utf-8").splitlines(keepends=True)
    out = []
    i = 0
    maps = 0

    while i < len(lines):
        line = lines[i]

        if '<map name=\\"' in line and "_timezone_map" in line:
            out.append(line)
            i += 1
            while i < len(lines) and "</map>" not in lines[i]:
                i += 1
            out.append(MAP_REPLACEMENT)
            if i < len(lines):
                out.append(lines[i])
                i += 1
            maps += 1
            continue

        out.append(line)
        i += 1

    path.write_text("".join(out), encoding="utf-8")
    return maps


def main():
    for path in FILES:
        maps = patch_maps(path)
        print("%s: %d map(s)" % (path.relative_to(ROOT), maps))


if __name__ == "__main__":
    main()
