#!/bin/bash
if [ "$(ls *.php 2> /dev/null)" = "" ]; then
  if [ -d "${1}" ]; then
    pop="${1}"
    pushd "${1}" 1> /dev/null
  else
    echo "Error: Cannot find any PHP file to process. Run inside a module directory or"
    echo "       provide directory name as parameter."
    exit
  fi
fi
grep "__ ( \"" *.php 2> /dev/null | while read -r line; do
  while [ "${line}" != "${line#*__ ( \"}" ]; do
    line="${line#*__ ( \"}"
    i18n="${line%%\"*}"
    line="${line#*\"}"
    if [ -z "$(grep -F "i18n_add ( \"${i18n}\")" language.php 2> /dev/null)" ]; then
      if [ -z "$(grep -F "i18n_add ( \"${i18n}\")" ../interface/language.php 2> /dev/null)" ]; then
        echo "Missing: ${i18n}"
      else
        echo "OK (global): ${i18n}"
      fi
    else
      echo "OK (local): ${i18n}"
    fi
  done
done | sort -u
grep "i18n_add ( \"" language.php | grep -v "\", \"" 2> /dev/null | while read -r line; do
  line="${line#*i18n_add ( \"}"
  i18n="${line%%\"*}"
  if [ -z "$(grep -F "__ ( \"${i18n}\")" *.php 2> /dev/null; grep -F "_e ( \"${i18n}\")" *.php 2> /dev/null; grep -F "_n ( \"${i18n}\")" *.php 2> /dev/null; grep -F "_ne ( \"${i18n}\")" *.php 2> /dev/null)" ]; then
    echo "Unused translation: ${i18n}"
  fi
done
if [ -n "${pop}" ]; then
  popd 1> /dev/null
fi
