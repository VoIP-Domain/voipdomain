#!/bin/bash
for each in *; do
  if [ -d "${each}" ]; then
    if [ -n "$(./checklanguage.sh "${each}" | grep "^Missing")" ]; then
      echo "Missing at ${each}."
    fi
  fi
done
