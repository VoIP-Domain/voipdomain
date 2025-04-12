#!/bin/bash

#    ___ ___       ___ _______     ______                        __
#   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
#   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
#   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
#   |:  1   |     |:  |:  |       |:  1    /
#    \:.. ./      |::.|::.|       |::.. . /
#     `---'       `---`---'       `------'
#
# Copyright (C) 2016-2025 Ernani José Camargo Azevedo
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

# VoIP Domain server install script. This script was automatically created by the
# administrative interface to provision the server #{{{ID}}} on a RHEL server.
#
# @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
# @version    1.0
# @package    VoIP Domain
# @subpackage Core
# @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
# @license    https://www.gnu.org/licenses/gpl-3.0.en.html

#
# Default install variables
#
MARIADB_USER="root"
MARIADB_PASS=""
MARIADB_DB="vdclient"
MARIADB_UUSER="vdclient"
MARIADB_UPASS="$(< /dev/urandom tr -dc _A-Z-a-z-0-9 | head -c${1:-16};echo)"

#
# Installer script welcome banner
#
echo -e "\033[1;37mVoIP Domain server installer\033[1;0m v1.0 - by Ernani Azevedo <azevedo@voipdomain.io>"
echo

#
# Process parameters
#
while [ -n "$1" ]; do
  case "${1}" in
    --username|--user|-u)
      shift
      MARIADB_USER="${1}"
      shift
      ;;
    --password|--pass|-p)
      shift
      MARIADB_PASS="${1}"
      shift
      ;;
    --database|--db|-d)
      shift
      MARIADB_DB="${1}"
      shift
      ;;
    --vdusername|--vduser|-vu)
      shift
      MARIADB_UUSER="${1}"
      shift
      ;;
    --vdpassword|--vdpass|-vp)
      shift
      MARIADB_PASS="${1}"
      shift
      ;;
    --help|-h)
      echo "If no parameter was given, the installer will set username to \"root\" and"
      echo "password to \"\" (empty, no password), which is the default MariaDB installation"
      echo "credentials. If no MariaDB database was detected, it will be installed."
      echo "The installer will create a non-root username and password to the system."
      echo
      echo "Available parameters:"
      echo "  --username|--user|-u USERNAME: MariaDB username (default: root)"
      echo "  --password|--pass|-p PASSWORD: MariaDB password (default: *EMPTY*)"
      echo "  --database|--db|-d: MariaDB database (default: vdclient)"
      echo "  --vdusername|--vduser|-vu USERNAME: MariaDB non-root username (default: vdclient)"
      echo "  --vdpassword|--vdpass|-vp PASSWORD: MariaDB non-root password (default: *RANDOM*)"
      exit
      ;;
    *)
      echo -e "\e[1;91mError\e[0m: Unknown parameter \"${1}\"."
      exit 1
      ;;
  esac
done

#
# Check if it's a RHEL (release 7, 8, and 9 supported):
#

if [ ! -f /etc/os-release ]; then
  echo -e "\e[1;91mError\e[0m: Cannot find \"/etc/os-release\"!"
  exit 1
fi
. /etc/os-release
if [ "${ID}" != "rhel" ]; then
  echo -e "\e[1;91mError\e[0m: This script runs only at RHEL server!"
  exit 1
fi
if [ "${VERSION_ID}" != "7" -a "${VERSION_ID}" != "8" -a "${VERSION_ID}" != "9" ]; then
  echo -e "\e[1;91mError\e[0m: This script runs only at RHEL release 7, 8 or 9!"
  exit 1
fi

#
# Confirm installation
#
echo -n -e "You're about to install VoIP Domain client #{{{ID}}} daemons on this server. Type \"\e[1mYes\e[0m\" (case sensitive) to accept and start installation: "
read start
echo
if [ "${start}" != "Yes" ]; then
  echo "Installation ABORTED!"
  exit 2
fi
echo "Starting installation."
echo

#
# Add repository
#
if [ ! -f /etc/yum.repos.d/VoIPDomain.repo ]; then
  echo -n "Adding VoIP Domain package repository... "
  wget -q -O /etc/yum.repos.d/VoIPDomain.repo http://rhel.voipdomain.io/VoIPDomain.repo
  echo -e "\e[1mOK!\e[0m"
  echo -n "Installing repository key... "
  rpm --import http://rhel.voipdomain.io/azevedo@voipdomain.io.key
  echo -e "\e[1mOK!\e[0m"
fi

#
# Check for database
#
echo -n "Checking for MariaDB... "
if [ -z "$(rpm -qv mariadb-server)" ]; then
  echo -n "Not found, installing... "
  yum install -y mariadb-server 1>/dev/null 2>&1
  systemctl enable mariadb.service 1>/dev/null 2>&1
  systemctl start mariadb.service 1>/dev/null 2>&1
  MARIADB_USER="root"
  MARIADB_PASS=""
else
  echo -n "Installed, checking credentials... "
  systemctl enable mariadb.service 1>/dev/null 2>&1
  systemctl start mariadb.service 1>/dev/null 2>&1
  mysql --user="${MARIADB_USER}" --password="${MARIADB_PASS}" -e "SHOW DATABASES;" 1>/dev/null 2>&1
  if [ $? != 0 ]; then
    echo -e "\e[1;91mError\e[0m: Cannot log into MariaDB server with user \"${MARIADB_USER}\" and password \"${MARIADB_PASS}\"!"
    echo "TIP: Use -u USER and -p PASS parameters on this script to set username and password to access MariaDB server."
    exit 3
  fi
fi
if [ -n "$(mysql --user="${MARIADB_USER}" --password="${MARIADB_PASS}" -e "USE \`${MARIADB_DB}\`;" 2>/dev/null)" ]; then
  echo -e "\e[1;91mError\e[0m: Database \"${MARIADB_DB}\" already exist! Remove before run this script."
  exit 3
fi
echo -e "\e[1mOK!\e[0m"

#
# Install basic packages
#
echo -n "Installing asterisk related packages... "
yum install -y asterisk pcapsipdump sngrep 1>/dev/null 2>&1
echo -e "\e[1mOK!\e[0m"

#
# Install VoIP Domain packages
#
echo -n "Installing VoIP Domain packages... "
yum install -y voipdomain-daemons voipdomain-client voipdomain-fastagi 1>/dev/null 2>&1
echo -e "\e[1mOK!\e[0m"

#
# Initialize MariaDB database
#
echo -n "Creating database and importing strucutre... "
mysqladmin --user="${MARIADB_USER}" --password="${MARIADB_PASS}" create "${MARIADB_DB}"
mysql --user="${MARIADB_USER}" --password="${MARIADB_PASS}" "${MARIADB_DB}" < /usr/share/doc/voipdomain-client-*/voipdomain-client.sqldump 1>/dev/null 2>&1
mysql --user="${MARIADB_USER}" --password="${MARIADB_PASS}" -e "GRANT SELECT, INSERT, UPDATE, DELETE ON \`${MARIADB_DB}\`.* TO '${MARIADB_UUSER}'@'localhost' IDENTIFIED BY '${MARIADB_UPASS}'" 1>/dev/null 2>&1
echo -e "\e[1mOK!\e[0m"

#
# Configure VoIP Domain files
#
echo -n "Configuring system... "
sed -i -e "s/^serverid = .*\$/serverid = {{{ID}}}/g" /etc/voipdomain/client.conf 1>/dev/null 2>&1
sed -i -e "s/^serverpass = .*\$/serverpass = {{{SERVERPASS}}}/g" /etc/voipdomain/client.conf 1>/dev/null 2>&1
sed -i -e "s/^lpurl = .*\$/lpurl = {{{URL}}}lp/g" /etc/voipdomain/client.conf 1>/dev/null 2>&1
sed -i -e "s/^returl = .*\$/returl = {{{URL}}}sys\/return/g" /etc/voipdomain/client.conf 1>/dev/null 2>&1
sed -i -e "s/^baseurl = .*\$/baseurl = {{{URL}}}/g" /etc/voipdomain/client.conf 1>/dev/null 2>&1
sed -i -e "s/^username = .*\$/username = ${MARIADB_UUSER}/g" /etc/voipdomain/fastagi.conf 1>/dev/null 2>&1
sed -i -e "s/^password = .*\$/password = ${MARIADB_UPASS}/g" /etc/voipdomain/fastagi.conf 1>/dev/null 2>&1
sed -i -e "s/^database = .*\$/database = ${MARIADB_DB}/g" /etc/voipdomain/fastagi.conf 1>/dev/null 2>&1
cat > /etc/voipdomain/client-certificate.key << __EOF__
{{{PRIVATEKEY}}}__EOF__
chmod 600 /etc/voipdomain/client-certificate.key
cat > /etc/voipdomain/client-certificate.pub << __EOF__
{{{PUBLICKEY}}}__EOF__
echo -e "\e[1mOK!\e[0m"

#
# Configure daemons system files and initialization status
#
echo -n "Starting daemons... "
for service in asterisk voipdomain fastagi; do
  systemctl enable ${service}.service 1>/dev/null 2>&1
  systemctl start ${service}.service 1>/dev/null 2>&1
  echo -n "${service} "
done
echo -e "\e[1mOK!\e[0m"

#
# Finished!
#
echo
echo -e "\e[1mDone!\e[0m"
echo
echo "VoIP Domain was sucessfully installed on this system."
