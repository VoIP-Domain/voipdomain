#!/bin/bash
grep "__ ( \"" *.php | while read line; do
  while [ "${line}" != "${line#*__ ( \"}" ]; do
    line="${line#*__ ( \"}"
    i18n="${line%%\"*}"
    line="${line#*\"}"
    if [ -z "$(grep "i18n_add ( \"${i18n}\")" language.php)" ]; then
      if [ -z "$(grep "i18n_add ( \"${i18n}\")" ../interface/language.php)" ]; then
        echo "Missing: ${i18n}"
      else
        echo "OK (global): ${i18n}"
      fi
    else
      echo "OK (local): ${i18n}"
    fi
  done
done | sort -u
grep "^i18n_add ( \"" language.php | while read line; do
  line="${line#*i18n_add ( \"}"
  i18n="${line%%\");*}"
  if [ "${i18n}" != "${i18n%%\", \"*}" ]; then
    continue
  fi
  if [ -n "${i18n}" ]; then
    if [ -z "$(grep "^i18n_add ( \"${i18n}\", \"" language.php)" ]; then
      echo "English only translation: ${i18n}"
    fi
    if [ -z "$(grep "__ ( \"${i18n}\")" *.php)" ]; then
      echo "Unused translation: ${i18n}"
    fi
  fi
done | sort -u
