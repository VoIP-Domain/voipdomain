#!/bin/bash

#    ___ ___       ___ _______     ______                        __
#   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
#   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
#   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
#   |:  1   |     |:  |:  |       |:  1    /
#    \:.. ./      |::.|::.|       |::.. . /
#     `---'       `---`---'       `------'
#
# Copyright (C) 2016-2021 Ernani José Camargo Azevedo
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <https://www.gnu.org/licenses/>.

# VoIP Domain Asterisk configurator script. This script will configure Asterisk
# server to include VoIP Domain configuration files.
#
# @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
# @version    1.0
# @package    VoIP Domain
# @subpackage Core
# @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
# @license    https://www.gnu.org/licenses/gpl-3.0.en.html

# Print script header:
echo -e "\e[1mVoIP Domain Asterisk Configurator\e[0m v1.0 - by Ernani Azevedo"
echo

# Check minimum BASH version:
if [ "$(printf "%s\n" "4.2.0" "$(echo ${BASH_VERSION%-*} | cut -d'(' -f1)" | sort -V | head -n 1)" != "4.2.0" ]; then
  echo -e "\e[1mError\e[0m: This script requires BASH minimum version 4.2.0."
  exit 2
fi

# Check basic applications dependency:
command -v sed 1>/dev/null 2>&1
if [ $? -ne 0 ]; then
  echo -e "\e[1mError\e[0m: Cannot find 'sed' application. This script needs it to work."
  exit 2
fi
command -v grep 1>/dev/null 2>&1
if [ $? -ne 0 ]; then
  echo -e "\e[1mError\e[0m: Cannot find 'grep' application. This script needs it to work."
  exit 2
fi

# Default variables:
serverid=""
serverip="$(hostname --all-ip-addresses | cut -d' ' -f1):5060"
asteriskpath="/etc/asterisk/"
dialtime=24
transtime=12
fastagi="agi://127.0.0.1:4573"

# Check for parameters:
while [ -n "${1}" ]; do
  case "${1}" in
    -h|--help)
      echo "This script will configure Asterisk server files to include VoIP Domain"
      echo "configurations."
      echo
      echo "Usage: ${0##*/} <SERVER_ID>"
      echo "  <SERVER_ID>: You have to provide the VoIP Domain server ID to install."
      echo "  -h | --help: Show this help page."
      echo "  -d | --directory: The path to the Asterisk configuration directory."
      echo "                    Default: /etc/asterisk/"
      echo "  -i | --localip: The local IP and port of Asterisk server. Default: ${serverip}"
      echo "  -t | --dialtime: Number of seconds that server will allow ringing. Default: 24"
      echo "  -b | --transtime: Number of seconds the server waits before ringing"
      echo "                    transfer targets. Default: 12"
      echo "  -f | --fastagi: The FastAGI server IP and port. Default: 127.0.0.1:4573"
      exit 1
      ;;
    -d|--directory)
      shift
      asteriskpath="${1}"
      if [ "${asteriskpath: -1}" -ne "/" ]; then
        asteriskpath="${asteriskpath}/"
      fi
      shift
      ;;
    -i|--localip)
      shift
      localip="${1}"
      shift
      ;;
    -t|--dialtime)
      shift
      dialtime="${1}"
      shift
      ;;
    -b|--transtime)
      shift
      transtime="${1}"
      shift
      ;;
    -f|--fastagi)
      shift
      fastagi="${1}"
      shift
      ;;
    *)
      if [ -n "${serverid}" ]; then
        echo -e "\e[1mError\e[0m: Invalid parameter '${1}'."
        exit 3
      fi
      serverid="${1}"
      shift
      ;;
  esac
done

if [ -z "${serverid}" ]; then
  echo -e "\e[1mError\e[0m: Missing server ID number. You must provide it as a parameter."
  exit 3
fi

echo "Installation parameters:"
echo -e "\e[1mServer ID\e[0m: ${serverid}"
echo -e "\e[1mServer IP:Port\e[0m: ${serverip}"
echo -e "\e[1mAsterisk configuration path\e[0m: ${asteriskpath}"
echo -e "\e[1mDial time\e[0m: ${dialtime}"
echo -e "\e[1mTransfer delay time\e[0m: ${transtime}"
echo -e "\e[1mFastAGI IP:Port\e[0m: ${fastagi}"
echo
echo -n "Proceed with installation (y/yes/n/no)? "
read confirm
if [ "${confirm}" != "y" -a "${confirm}" != "yes" ]; then
  echo -e "\e[1mAborted\e[0m: Confirmation failed."
  exit 3
fi
echo

# Configure 'dialplan-globals.conf':
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'dialplan-globals.conf'..."
if [ ! -e "${asteriskpath}voipdomain/configs/dialplan-globals.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'dialplan-globals.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
sed -i -e "s/^SERVER=.*\$/SERVER=${serverid}/" "${asteriskpath}voipdomain/configs/dialplan-globals.conf"
sed -i -e "s/^SERVERADDR=.*\$/SERVERADDR=${serverip}/" "${asteriskpath}voipdomain/configs/dialplan-globals.conf"
sed -i -e "s/^DIALTIME=.*\$/DIALTIME=${dialtime}/" "${asteriskpath}voipdomain/configs/dialplan-globals.conf"
sed -i -e "s/^TRANSTIME=.*\$/TRANSTIME=${transtime}/" "${asteriskpath}voipdomain/configs/dialplan-globals.conf"
sed -i -e "s/^AGIADDR=.*\$/AGIADDR=${fastagi}/" "${asteriskpath}voipdomain/configs/dialplan-globals.conf"
echo -e "\r\e[2K\e[1m[\e[0m\e[92m\xE2\x9C\x94\e[0m\e[1m]\e[0m Configuring 'dialplan-globals.conf'... DONE"

ensure_include() {
  local cfgfile="${1}"
  local incline="${2}"
  if grep -qF "${incline}" "${cfgfile}"; then
    echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring '${cfgfile##*/}'... SKIPPED, ALREADY CONFIGURED."
    return 0
  fi
  echo "${incline}" >> "${cfgfile}"
  echo -e "\r\e[2K\e[1m[\e[0m\e[92m\xE2\x9C\x94\e[0m\e[1m]\e[0m Configuring '${cfgfile##*/}'... DONE"
  return 0
}

# Configure 'agents.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'agents.conf'..."
if [ ! -e "${asteriskpath}agents.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'agents.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/agents.conf" "${asteriskpath}agents.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'agents.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}agents.conf" "#include voipdomain/bootstrap/agents.conf"
fi

# Configure 'extensions.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'extensions.conf'..."
if [ ! -e "${asteriskpath}extensions.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'extensions.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/dialplan.conf" "${asteriskpath}extensions.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'extensions.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}extensions.conf" "#include voipdomain/bootstrap/dialplan.conf"
fi

# Configure 'musiconhold.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'musiconhold.conf'..."
if [ ! -e "${asteriskpath}musiconhold.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'musiconhold.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/musiconhold.conf" "${asteriskpath}musiconhold.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'musiconhold.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}musiconhold.conf" "#include voipdomain/bootstrap/musiconhold.conf"
fi

# Configure 'pjsip.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'pjsip.conf'..."
if [ ! -e "${asteriskpath}pjsip.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'pjsip.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/pjsip.conf" "${asteriskpath}pjsip.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'pjsip.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}pjsip.conf" "#include voipdomain/bootstrap/pjsip.conf"
fi

# Configure 'pjsip_notify.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'pjsip_notify.conf'..."
if [ ! -e "${asteriskpath}pjsip_notify.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'pjsip_notify.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/pjsip_notify.conf" "${asteriskpath}pjsip_notify.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'pjsip_notify.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}pjsip_notify.conf" "#include voipdomain/bootstrap/pjsip_notify.conf"
fi

# Configure 'queues.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'queues.conf'..."
if [ ! -e "${asteriskpath}queues.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'queues.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/queues.conf" "${asteriskpath}queues.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'queues.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}queues.conf" "#include voipdomain/bootstrap/queues.conf"
fi

# Configure 'confbridge.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'confbridge.conf'..."
if [ ! -e "${asteriskpath}confbridge.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'confbridge.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/confbridge.conf" "${asteriskpath}confbridge.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'confbridge.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}confbridge.conf" "#include voipdomain/bootstrap/confbridge.conf"
fi

# Configure 'voicemail.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'voicemail.conf'..."
if [ ! -e "${asteriskpath}voicemail.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'voicemail.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/voicemail.conf" "${asteriskpath}voicemail.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'voicemail.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}voicemail.conf" "#include voipdomain/bootstrap/voicemail.conf"
fi

# Configure 'manager.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'manager.conf'..."
if [ ! -e "${asteriskpath}manager.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'manager.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
# Enable AMI if disabled
if grep -q "^enabled = no" "${asteriskpath}manager.conf" || grep -q "^;enabled = yes" "${asteriskpath}manager.conf"; then
  sed -i "s/^enabled = no/enabled = yes/" "${asteriskpath}manager.conf"
  sed -i "s/^;enabled = yes/enabled = yes/" "${asteriskpath}manager.conf"
fi
# Ensure bindaddr is set to localhost
if ! grep -q "^bindaddr = 127.0.0.1" "${asteriskpath}manager.conf"; then
  sed -i "s/^bindaddr = .*/bindaddr = 127.0.0.1/" "${asteriskpath}manager.conf"
  sed -i "s/^;bindaddr = .*/bindaddr = 127.0.0.1/" "${asteriskpath}manager.conf"
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/manager.conf" "${asteriskpath}manager.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'manager.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}manager.conf" "#include voipdomain/bootstrap/manager.conf"
fi

# Configure 'indications.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'indications.conf'..."
if [ ! -e "${asteriskpath}indications.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'indications.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/indications.conf" "${asteriskpath}indications.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'indications.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}indications.conf" "#include voipdomain/bootstrap/indications.conf"
fi

# Configure 'cdr.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'cdr.conf'..."
if [ ! -e "${asteriskpath}cdr.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'cdr.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/cdr.conf" "${asteriskpath}cdr.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'cdr.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}cdr.conf" "#include voipdomain/bootstrap/cdr.conf"
fi

# Configure 'cdr_manager.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'cdr_manager.conf'..."
if [ ! -e "${asteriskpath}cdr_manager.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'cdr_manager.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/cdr_manager.conf" "${asteriskpath}cdr_manager.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'cdr_manager.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}cdr_manager.conf" "#include voipdomain/bootstrap/cdr_manager.conf"
fi

# Configure 'cdr_sqlite3_custom.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'cdr_sqlite3_custom.conf'..."
if [ ! -e "${asteriskpath}cdr_sqlite3_custom.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'cdr_sqlite3_custom.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/cdr_sqlite3_custom.conf" "${asteriskpath}cdr_sqlite3_custom.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'cdr_sqlite3_custom.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}cdr_sqlite3_custom.conf" "#include voipdomain/bootstrap/cdr_sqlite3_custom.conf"
fi

# Configure 'cel.conf' include:
echo -n -e "\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0m\e[1m]\e[0m Configuring 'cel.conf'..."
if [ ! -e "${asteriskpath}cel.conf" ]; then
  echo -e "\r\e[2K\e[1m[\e[0m\e[91m\xE2\x9C\x95\e[0m\e[1m]\e[0m Configuring 'cel.conf'... FAILED, FILE NOT FOUND!"
  exit 4
fi
if [ -n "$(grep "^#include voipdomain/bootstrap/cel.conf" "${asteriskpath}cel.conf")" ]; then
  echo -e "\r\e[2K\e[1m[\e[33m\xE2\x98\x85\e[0;1m]\e[0m Configuring 'cel.conf'... SKIPPED, ALREADY CONFIGURED."
else
  ensure_include "${asteriskpath}cel.conf" "#include voipdomain/bootstrap/cel.conf"
fi

echo
echo "DONE!"
echo
echo "NOTE: Bootstrap configuration files (dialplan, pjsip, queues, etc.) are now"
echo "      dynamically generated by the VoIP Domain client daemon via hooks."
echo "      The daemon will generate them on first startup or server rebuild."
echo "      To complete AMI setup, create the user configuration file:"
echo "      ${asteriskpath}voipdomain/configs/manager-voipdomain.conf"
echo "      with the appropriate username and secret for the monitor daemon."
