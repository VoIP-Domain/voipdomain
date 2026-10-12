#!/bin/bash

#    ___ ___       ___ _______     ______                        __
#   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
#   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
#   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
#   |:  1   |     |:  |:  |       |:  1    /
#    \:.. ./      |::.|::.|       |::.. . /
#     `---'       `---`---'       `------'
#
# Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
# @version    0.3
# @package    VoIP Domain
# @subpackage Core
# @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
# @license    https://www.gnu.org/licenses/gpl-3.0.en.html

#
# Color definitions
#
RED='\e[1;91m'
GREEN='\e[1;32m'
YELLOW='\e[1;33m'
BLUE='\e[1;34m'
BOLD='\e[1m'
RESET='\e[0m'

#
# Output functions
#
msg_info()  { echo -e "${BLUE}[INFO]${RESET} $1"; }
msg_ok()    { echo -e "${GREEN}[OK]${RESET} $1"; }
msg_warn()  { echo -e "${YELLOW}[WARN]${RESET} $1"; }
msg_error() { echo -e "${RED}[ERROR]${RESET} $1"; }
msg_step()  { echo -ne "${BOLD}==>${RESET} $1... "; }
msg_done()  { echo -e "${GREEN}OK${RESET}"; }
msg_fail()  { echo -e "${RED}FAILED${RESET}"; }
msg_skip()  { echo -e "${YELLOW}SKIPPED${RESET}"; }

#
# Check if running as root
#
if [[ $EUID -ne 0 ]]; then
  msg_error "This script must be run as root!"
  echo "Try: sudo $0 $*"
  exit 1
fi

#
# Generate random password (pipefail-safe)
#
generate_password() {
  local length="${1:-16}"
  head -c 100 /dev/urandom | tr -dc 'A-Za-z0-9_' | head -c "${length}" || true
}

#
# Default install variables
#
MARIADB_USER="root"
MARIADB_PASS=""
MARIADB_DB="vdclient"
MARIADB_UUSER="vdclient"
MARIADB_UPASS="$(generate_password 16)"
AMI_USER="voipdomain"
AMI_PASS="$(generate_password 16)"
ASSUME_YES=false
SKIP_PKG_INSTALL=false
SERVER_COUNTRY="US"

#
# Enable strict mode after variable initialization
#
set -euo pipefail

#
# Package lists
#
declare -a PKGS_REQUIRED=(
  "asterisk"
  "mariadb-server"
  "voipdomain-daemons-common"
  "voipdomain-client"
  "voipdomain-fastagi"
  "voipdomain-monitor"
)
declare -a PKGS_OPTIONAL=(
  "pcapsipdump"
  "sngrep"
)

#
# Asterisk configuration mappings (asterisk_config -> voipdomain_bootstrap)
#
declare -A ASTERISK_BOOTSTRAPS=(
  ["pjsip.conf"]="voipdomain/bootstrap/pjsip.conf"
  ["extensions.conf"]="voipdomain/bootstrap/dialplan.conf"
  ["manager.conf"]="voipdomain/bootstrap/manager.conf"
  ["queues.conf"]="voipdomain/bootstrap/queues.conf"
  ["voicemail.conf"]="voipdomain/bootstrap/voicemail.conf"
  ["musiconhold.conf"]="voipdomain/bootstrap/musiconhold.conf"
  ["agents.conf"]="voipdomain/bootstrap/agents.conf"
  ["pjsip_notify.conf"]="voipdomain/bootstrap/pjsip_notify.conf"
  ["indications.conf"]="voipdomain/bootstrap/indications.conf"
)

#
# Placeholder files required for Asterisk glob includes
#
declare -a CONFIG_PLACEHOLDERS=(
  "agent-000000.conf"
  "dialplan-block-000000.conf"
  "dialplan-bootstrap-000000.conf"
  "dialplan-configs-000000.conf"
  "dialplan-db-000000.conf"
  "dialplan-exception-000000.conf"
  "dialplan-extension-000000.conf"
  "dialplan-gateway-000000.conf"
  "dialplan-globals.conf"
  "dialplan-hint-000000.conf"
  "dialplan-ivr-000000.conf"
  "dialplan-profile-000000.conf"
  "dialplan-range-000000.conf"
  "indications-voipdomain.conf"
  "manager-000000.conf"
  "moh-000000.conf"
  "queue-000000.conf"
  "sip-account-000000.conf"
  "sip-extension-000000.conf"
  "sip-gateway-000000.conf"
  "sip-group-000000.conf"
  "sip-model-000000.conf"
  "sip-profile-000000.conf"
  "sip-server-000000.conf"
  "voicemail-000000.conf"
)

#
# Show help
#
show_help() {
  cat << 'HELPEOF'
VoIP Domain Server Installer v0.3

Usage: install.sh [OPTIONS]

Options:
  -y, --yes              Assume "yes" to all prompts (non-interactive mode)
  -u, --user USERNAME    MariaDB admin username (default: root)
  -p, --pass PASSWORD    MariaDB admin password (default: empty)
  -d, --db DATABASE      Local database name (default: vdclient)
  --vduser USERNAME      VoIP Domain database user (default: vdclient)
  --vdpass PASSWORD      VoIP Domain database password (default: random)
  --country CODE         Server country code for indications (default: US)
  --skip-packages        Skip package installation (configure only)
  -h, --help             Show this help message

Examples:
  ./install.sh                         Interactive installation
  ./install.sh -y                      Non-interactive with defaults
  ./install.sh -y -u root -p secret    Non-interactive with custom MariaDB password

HELPEOF
}

#
# Check if package is installed
#
is_pkg_installed() {
  rpm -q "$1" &>/dev/null
}

#
# Check packages status
#
check_packages() {
  local -n installed_ref=$1
  local -n missing_ref=$2
  shift 2
  local -a packages=("$@")

  installed_ref=()
  missing_ref=()

  for pkg in "${packages[@]}"; do
    if is_pkg_installed "$pkg"; then
      installed_ref+=("$pkg")
    else
      missing_ref+=("$pkg")
    fi
  done
}

#
# Display package status
#
display_package_status() {
  local label="$1"
  shift
  local -a installed=()
  local -a missing=()

  check_packages installed missing "$@"

  echo
  echo -e "${BOLD}${label}:${RESET}"
  echo

  if [[ ${#installed[@]} -gt 0 ]]; then
    for pkg in "${installed[@]}"; do
      echo -e "  ${GREEN}✓${RESET} ${pkg}"
    done
  fi

  if [[ ${#missing[@]} -gt 0 ]]; then
    for pkg in "${missing[@]}"; do
      echo -e "  ${RED}✗${RESET} ${pkg} ${YELLOW}(not installed)${RESET}"
    done
  fi

  echo
  return ${#missing[@]}
}

#
# Add include to Asterisk config file
#
add_asterisk_include() {
  local config_file="$1"
  local include_path="$2"
  local asterisk_path="/etc/asterisk"

  if [[ ! -f "${asterisk_path}/${config_file}" ]]; then
    msg_warn "${config_file} not found, skipping"
    return 1
  fi

  if grep -q "^#include ${include_path}" "${asterisk_path}/${config_file}" 2>/dev/null; then
    return 0
  fi

  echo "" >> "${asterisk_path}/${config_file}"
  echo "#include ${include_path}" >> "${asterisk_path}/${config_file}"
  return 0
}

#
# Create placeholder config file
#
create_placeholder() {
  local file="$1"
  local dir="/etc/asterisk/voipdomain/configs"

  if [[ ! -f "${dir}/${file}" ]]; then
    local prefix="${file%-000000.conf}"
    echo "; Placeholder so the ${prefix}-*.conf glob is never empty on fresh installs." > "${dir}/${file}"
    chown root:asterisk "${dir}/${file}"
    chmod 644 "${dir}/${file}"
  fi
}

#
# Installer script welcome banner
#
echo
echo -e "${BOLD}VoIP Domain Server Installer${RESET} v0.3"
echo -e "by Ernani Azevedo <azevedo@voipdomain.io>"
echo

#
# Process parameters
#
while [[ $# -gt 0 ]]; do
  case "${1}" in
    -y|--yes)
      ASSUME_YES=true
      shift
      ;;
    -u|--user|--username)
      MARIADB_USER="${2}"
      shift 2
      ;;
    -p|--pass|--password)
      MARIADB_PASS="${2}"
      shift 2
      ;;
    -d|--db|--database)
      MARIADB_DB="${2}"
      shift 2
      ;;
    --vduser|--vdusername)
      MARIADB_UUSER="${2}"
      shift 2
      ;;
    --vdpass|--vdpassword)
      MARIADB_UPASS="${2}"
      shift 2
      ;;
    --skip-packages)
      SKIP_PKG_INSTALL=true
      shift
      ;;
    --country)
      SERVER_COUNTRY="${2}"
      shift 2
      ;;
    -h|--help)
      show_help
      exit 0
      ;;
    *)
      msg_error "Unknown parameter: ${1}"
      echo "Use --help for usage information."
      exit 1
      ;;
  esac
done

#
# Check if it's a supported OS (RHEL 7-10, Fedora 44+, or compatible):
#
if [[ ! -f /etc/os-release ]]; then
  msg_error "Cannot find \"/etc/os-release\"!"
  exit 1
fi
source /etc/os-release

if [[ "${ID}" == "rhel" || "${ID}" == "rocky" || "${ID}" == "almalinux" || "${ID}" == "centos" ]]; then
  MAJOR_VERSION="${VERSION_ID%%.*}"
  if [[ "${MAJOR_VERSION}" != "7" && "${MAJOR_VERSION}" != "8" && "${MAJOR_VERSION}" != "9" && "${MAJOR_VERSION}" != "10" ]]; then
    msg_error "This script requires RHEL/Rocky/AlmaLinux/CentOS version 7, 8, 9 or 10!"
    exit 1
  fi
  REPO_HOST="rhel.voipdomain.io"
elif [[ "${ID}" == "fedora" ]]; then
  if [[ "${VERSION_ID}" -lt 44 ]]; then
    msg_error "This script requires Fedora version 44 or higher!"
    exit 1
  fi
  REPO_HOST="fedora.voipdomain.io"
else
  msg_error "This script runs only on RHEL, Rocky Linux, AlmaLinux, CentOS or Fedora Server!"
  exit 1
fi

msg_info "Detected: ${BOLD}${NAME} ${VERSION_ID}${RESET}"
echo

#
# Determine package manager
#
if command -v dnf &>/dev/null; then
  PKG_MGR="dnf"
else
  PKG_MGR="yum"
fi

#
# Check for VoIP Domain repository
#
if [[ ! -f /etc/yum.repos.d/VoIPDomain.repo ]]; then
  msg_warn "VoIP Domain repository not configured"
  if [[ "${ASSUME_YES}" == true ]]; then
    INSTALL_REPO="y"
  else
    echo -ne "Do you want to add the VoIP Domain repository (${REPO_HOST})? [Y/n]: "
    read -r INSTALL_REPO
    INSTALL_REPO="${INSTALL_REPO:-y}"
  fi

  if [[ "${INSTALL_REPO,,}" == "y" || "${INSTALL_REPO,,}" == "yes" ]]; then
    msg_step "Adding VoIP Domain repository (${REPO_HOST})"
    wget -q -O /etc/yum.repos.d/VoIPDomain.repo "http://${REPO_HOST}:8081/VoIPDomain.repo"
    rpm --import "http://${REPO_HOST}:8081/azevedo@voipdomain.io.key"
    msg_done
  fi
fi

#
# Display package status
#
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
echo -e "${BOLD}                           PACKAGE STATUS                                      ${RESET}"
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"

declare -a REQUIRED_INSTALLED=()
declare -a REQUIRED_MISSING=()
declare -a OPTIONAL_INSTALLED=()
declare -a OPTIONAL_MISSING=()

check_packages REQUIRED_INSTALLED REQUIRED_MISSING "${PKGS_REQUIRED[@]}"
check_packages OPTIONAL_INSTALLED OPTIONAL_MISSING "${PKGS_OPTIONAL[@]}"

echo
echo -e "${BOLD}Required Packages:${RESET}"
echo
for pkg in "${PKGS_REQUIRED[@]}"; do
  if [[ " ${REQUIRED_INSTALLED[*]} " =~ " ${pkg} " ]]; then
    echo -e "  ${GREEN}✓${RESET} ${pkg}"
  else
    echo -e "  ${RED}✗${RESET} ${pkg} ${YELLOW}(not installed)${RESET}"
  fi
done

echo
echo -e "${BOLD}Optional Packages:${RESET}"
echo
for pkg in "${PKGS_OPTIONAL[@]}"; do
  if [[ " ${OPTIONAL_INSTALLED[*]} " =~ " ${pkg} " ]]; then
    echo -e "  ${GREEN}✓${RESET} ${pkg}"
  else
    echo -e "  ${YELLOW}○${RESET} ${pkg} ${YELLOW}(not installed)${RESET}"
  fi
done
echo

#
# Summary
#
TOTAL_REQUIRED=${#PKGS_REQUIRED[@]}
INSTALLED_REQUIRED=${#REQUIRED_INSTALLED[@]}
MISSING_REQUIRED=${#REQUIRED_MISSING[@]}
MISSING_OPTIONAL=${#OPTIONAL_MISSING[@]}

echo -e "${BOLD}Summary:${RESET} ${INSTALLED_REQUIRED}/${TOTAL_REQUIRED} required packages installed"

if [[ ${MISSING_REQUIRED} -eq 0 && ${MISSING_OPTIONAL} -eq 0 ]]; then
  msg_ok "All packages are already installed!"
  NEED_INSTALL=false
else
  if [[ ${MISSING_REQUIRED} -gt 0 ]]; then
    echo -e "  ${RED}${MISSING_REQUIRED} required package(s) missing${RESET}"
  fi
  if [[ ${MISSING_OPTIONAL} -gt 0 ]]; then
    echo -e "  ${YELLOW}${MISSING_OPTIONAL} optional package(s) missing${RESET}"
  fi
  NEED_INSTALL=true
fi

echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
echo

#
# Install packages if needed
#
if [[ "${SKIP_PKG_INSTALL}" == true ]]; then
  msg_info "Skipping package installation (--skip-packages)"
elif [[ "${NEED_INSTALL}" == true ]]; then
  INSTALL_REQUIRED=false
  INSTALL_OPTIONAL=false

  if [[ ${MISSING_REQUIRED} -gt 0 ]]; then
    if [[ "${ASSUME_YES}" == true ]]; then
      INSTALL_REQUIRED=true
    else
      echo -ne "Install ${BOLD}${MISSING_REQUIRED}${RESET} missing required packages? [Y/n]: "
      read -r answer
      [[ -z "$answer" || "${answer,,}" == "y" || "${answer,,}" == "yes" ]] && INSTALL_REQUIRED=true
    fi
  fi

  if [[ ${MISSING_OPTIONAL} -gt 0 ]]; then
    if [[ "${ASSUME_YES}" == true ]]; then
      INSTALL_OPTIONAL=true
    else
      echo -ne "Install ${BOLD}${MISSING_OPTIONAL}${RESET} optional packages? [y/N]: "
      read -r answer
      [[ "${answer,,}" == "y" || "${answer,,}" == "yes" ]] && INSTALL_OPTIONAL=true
    fi
  fi

  echo

  if [[ "${INSTALL_REQUIRED}" == true && ${MISSING_REQUIRED} -gt 0 ]]; then
    msg_step "Installing required packages"
    if ${PKG_MGR} install -y "${REQUIRED_MISSING[@]}" &>/dev/null; then
      msg_done
    else
      msg_fail
      msg_error "Failed to install required packages!"
      exit 1
    fi
  fi

  if [[ "${INSTALL_OPTIONAL}" == true && ${MISSING_OPTIONAL} -gt 0 ]]; then
    msg_step "Installing optional packages"
    if ${PKG_MGR} install -y "${OPTIONAL_MISSING[@]}" &>/dev/null; then
      msg_done
    else
      msg_warn "Some optional packages failed to install"
    fi
  fi

  if [[ "${INSTALL_REQUIRED}" == false && ${MISSING_REQUIRED} -gt 0 ]]; then
    msg_error "Required packages not installed. Cannot continue."
    exit 1
  fi
fi

#
# Verify all required packages are installed
#
for pkg in "${PKGS_REQUIRED[@]}"; do
  if ! is_pkg_installed "$pkg"; then
    msg_error "Required package '${pkg}' is not installed!"
    exit 1
  fi
done

#
# Check MariaDB service
#
msg_step "Starting MariaDB service"
systemctl enable mariadb.service &>/dev/null || true
systemctl start mariadb.service &>/dev/null
msg_done

msg_step "Checking MariaDB credentials"
if ! mysql --user="${MARIADB_USER}" --password="${MARIADB_PASS}" -e "SHOW DATABASES;" &>/dev/null; then
  msg_fail
  msg_error "Cannot log into MariaDB with user \"${MARIADB_USER}\"!"
  echo "TIP: Use -u USER and -p PASS parameters to set MariaDB credentials."
  exit 3
fi
msg_done

msg_step "Checking database availability"
if mysql --user="${MARIADB_USER}" --password="${MARIADB_PASS}" -e "USE \`${MARIADB_DB}\`;" &>/dev/null; then
  msg_fail
  msg_error "Database \"${MARIADB_DB}\" already exists!"
  echo "TIP: Use -d DATABASE to specify a different database name, or remove the existing one."
  exit 3
fi
msg_done

#
# Confirm installation
#
echo
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
echo -e "${BOLD}                         INSTALLATION SUMMARY                                  ${RESET}"
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
echo
echo -e "  Server ID:        ${BOLD}{{{ID}}}${RESET}"
echo -e "  Database:         ${BOLD}${MARIADB_DB}${RESET}"
echo -e "  Database User:    ${BOLD}${MARIADB_UUSER}${RESET}"
echo -e "  AMI User:         ${BOLD}${AMI_USER}${RESET}"
echo -e "  Gearman Server:   ${BOLD}{{{GEARMAN_HOST}}}:{{{GEARMAN_PORT}}}${RESET}"
echo
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
echo

if [[ "${ASSUME_YES}" != true ]]; then
  echo -ne "Type \"${BOLD}Yes${RESET}\" (case sensitive) to proceed with installation: "
  read -r confirm
  echo
  if [[ "${confirm}" != "Yes" ]]; then
    msg_warn "Installation ABORTED by user."
    exit 2
  fi
fi

echo -e "${BOLD}Starting installation...${RESET}"
echo

#
# Create MariaDB database
#
msg_step "Creating local database"
mysqladmin --user="${MARIADB_USER}" --password="${MARIADB_PASS}" create "${MARIADB_DB}" &>/dev/null
mysql --user="${MARIADB_USER}" --password="${MARIADB_PASS}" -e "GRANT SELECT, INSERT, UPDATE, DELETE ON \`${MARIADB_DB}\`.* TO '${MARIADB_UUSER}'@'localhost' IDENTIFIED BY '${MARIADB_UPASS}'" &>/dev/null
msg_done

#
# Configure Asterisk VoIP Domain directories
#
msg_step "Creating VoIP Domain directories"
mkdir -p /etc/asterisk/voipdomain/{bootstrap,configs}
chown -R root:asterisk /etc/asterisk/voipdomain
chmod 750 /etc/asterisk/voipdomain
chmod 755 /etc/asterisk/voipdomain/bootstrap
chmod 750 /etc/asterisk/voipdomain/configs
msg_done

#
# Create placeholder config files
#
msg_step "Creating placeholder config files"
for placeholder in "${CONFIG_PLACEHOLDERS[@]}"; do
  create_placeholder "${placeholder}"
done
msg_done

#
# Configure Asterisk bootstrap includes
#
echo
echo -e "${BOLD}Configuring Asterisk bootstrap includes:${RESET}"
echo

for config in "${!ASTERISK_BOOTSTRAPS[@]}"; do
  bootstrap="${ASTERISK_BOOTSTRAPS[$config]}"
  echo -ne "  ${BOLD}${config}${RESET} -> ${bootstrap}... "

  if [[ ! -f "/etc/asterisk/${config}" ]]; then
    echo -e "${YELLOW}not found, skipped${RESET}"
    continue
  fi

  if grep -q "^#include ${bootstrap}" "/etc/asterisk/${config}" 2>/dev/null; then
    echo -e "${GREEN}already configured${RESET}"
  else
    echo "" >> "/etc/asterisk/${config}"
    echo "#include ${bootstrap}" >> "/etc/asterisk/${config}"
    echo -e "${GREEN}added${RESET}"
  fi
done

#
# Configure manager.conf specifically (enable AMI)
#
echo
msg_step "Configuring AMI (manager.conf)"
if [[ -f /etc/asterisk/manager.conf ]]; then
  if ! grep -q "^enabled = yes" /etc/asterisk/manager.conf; then
    sed -i "s/^enabled = no/enabled = yes/" /etc/asterisk/manager.conf
    sed -i "s/^;enabled = yes/enabled = yes/" /etc/asterisk/manager.conf
  fi
  if ! grep -q "^bindaddr = 127.0.0.1" /etc/asterisk/manager.conf; then
    sed -i "s/^bindaddr = .*/bindaddr = 127.0.0.1/" /etc/asterisk/manager.conf
    sed -i "s/^;bindaddr = .*/bindaddr = 127.0.0.1/" /etc/asterisk/manager.conf
  fi
fi
msg_done

#
# Create AMI user configuration
#
msg_step "Creating AMI user configuration"
cat > /etc/asterisk/voipdomain/configs/manager-voipdomain.conf << EOF
;
; VoIP Domain AMI user - auto-generated by install script
;
[${AMI_USER}]
secret = ${AMI_PASS}
deny = 0.0.0.0/0.0.0.0
permit = 127.0.0.1/255.255.255.255
read = system,call,log,verbose,command,agent,user,config,dtmf,reporting,cdr,dialplan,originate,message
write = system,call,log,verbose,command,agent,user,config,dtmf,reporting,cdr,dialplan,originate,message
writetimeout = 5000
EOF
chmod 640 /etc/asterisk/voipdomain/configs/manager-voipdomain.conf
chown root:asterisk /etc/asterisk/voipdomain/configs/manager-voipdomain.conf
msg_done

#
# Configure VoIP Domain daemon files
#
echo
msg_step "Configuring VoIP Domain client daemon"

# Client configuration
sed -i "s/^serverid = .*\$/serverid = {{{ID}}}/" /etc/voipdomain/client.conf
sed -i "s|^lpurl = .*|lpurl = {{{URL}}}lp|" /etc/voipdomain/client.conf
sed -i "s|^apiurl = .*|apiurl = {{{URL}}}api|" /etc/voipdomain/client.conf
sed -i "s/^username = .*\$/username = ${MARIADB_UUSER}/" /etc/voipdomain/client.conf
sed -i "s/^password = .*\$/password = ${MARIADB_UPASS}/" /etc/voipdomain/client.conf
sed -i "s/^database = .*\$/database = ${MARIADB_DB}/" /etc/voipdomain/client.conf

# Configure local server address (used for SIP registration client_uri)
sed -i "s|^; *address = .*|address = {{{ADDRESS}}}|" /etc/voipdomain/client.conf
if ! grep -q "^address" /etc/voipdomain/client.conf; then
  sed -i "/^\[general\]/a address = {{{ADDRESS}}}" /etc/voipdomain/client.conf
fi

# Configure directories (uncomment if commented and set values)
sed -i "s|^; *tftpdir = .*|tftpdir = /var/lib/tftpboot|" /etc/voipdomain/client.conf
sed -i "s|^; *datadir = .*|datadir = /var/lib/voipdomain/data|" /etc/voipdomain/client.conf
sed -i "s|^; *soundsdir = .*|soundsdir = /var/lib/asterisk/sounds/voipdomain|" /etc/voipdomain/client.conf
sed -i "s|^; *transferdir = .*|transferdir = /var/spool/voipdomain/file-transfer|" /etc/voipdomain/client.conf

# If directories are not in config, add them
if ! grep -q "^tftpdir" /etc/voipdomain/client.conf; then
  sed -i "/^\[general\]/a tftpdir = /var/lib/tftpboot" /etc/voipdomain/client.conf
fi
if ! grep -q "^datadir" /etc/voipdomain/client.conf; then
  sed -i "/^\[general\]/a datadir = /var/lib/voipdomain/data" /etc/voipdomain/client.conf
fi
if ! grep -q "^soundsdir" /etc/voipdomain/client.conf; then
  sed -i "/^\[general\]/a soundsdir = /var/lib/asterisk/sounds/voipdomain" /etc/voipdomain/client.conf
fi
if ! grep -q "^transferdir" /etc/voipdomain/client.conf; then
  sed -i "/^\[general\]/a transferdir = /var/spool/voipdomain/file-transfer" /etc/voipdomain/client.conf
fi

# Create directories if they don't exist and set ownership
mkdir -p /var/lib/tftpboot
mkdir -p /var/lib/voipdomain/data
mkdir -p /var/lib/asterisk/sounds/voipdomain
mkdir -p /usr/share/asterisk/sounds
ln -sfn /var/lib/asterisk/sounds/voipdomain /usr/share/asterisk/sounds/voipdomain
mkdir -p /var/spool/voipdomain/file-transfer
chown -R asterisk:asterisk /var/lib/tftpboot
chown -R asterisk:asterisk /var/lib/voipdomain/data
chown -R asterisk:asterisk /var/lib/asterisk/sounds/voipdomain
chown -R asterisk:asterisk /var/spool/voipdomain/file-transfer


# Client Gearman configuration
if grep -q "^\[gearman\]" /etc/voipdomain/client.conf; then
  sed -i "s/^hostname = .*\$/hostname = {{{GEARMAN_HOST}}}/" /etc/voipdomain/client.conf
  sed -i "s/^port = .*\$/port = {{{GEARMAN_PORT}}}/" /etc/voipdomain/client.conf
else
  cat >> /etc/voipdomain/client.conf << 'EOF'

[gearman]
hostname = {{{GEARMAN_HOST}}}
port = {{{GEARMAN_PORT}}}
EOF
fi
msg_done

msg_step "Configuring Asterisk dialplan globals"
sed -i "s/^SERVER=.*\$/SERVER={{{ID}}}/" /etc/asterisk/voipdomain/configs/dialplan-globals.conf
sed -i "s/^SERVERADDR=.*\$/SERVERADDR={{{ADDRESS}}}/" /etc/asterisk/voipdomain/configs/dialplan-globals.conf
sed -i "s/^DIALTIME=.*\$/DIALTIME=30/" /etc/asterisk/voipdomain/configs/dialplan-globals.conf
sed -i "s/^TRANSTIME=.*\$/TRANSTIME=60/" /etc/asterisk/voipdomain/configs/dialplan-globals.conf
sed -i "s/^AGIADDR=.*\$/AGIADDR=agi:\/\/127.0.0.1:4573/" /etc/asterisk/voipdomain/configs/dialplan-globals.conf
msg_done

msg_step "Configuring Asterisk indications country"
# Use placeholder value if present, otherwise use command-line option
INDICATION_COUNTRY="{{{COUNTRY}}}"
if [[ "${INDICATION_COUNTRY}" == "{{{COUNTRY}}}" ]]; then
  INDICATION_COUNTRY="${SERVER_COUNTRY,,}"
fi
cat > /etc/asterisk/voipdomain/configs/indications-voipdomain.conf << EOF
;
; VoIP Domain Indications Configuration
; This file is managed by the VoIP Domain system
;

[general]
country = ${INDICATION_COUNTRY}
EOF
chown root:asterisk /etc/asterisk/voipdomain/configs/indications-voipdomain.conf
chmod 644 /etc/asterisk/voipdomain/configs/indications-voipdomain.conf
msg_done

msg_step "Configuring VoIP Domain FastAGI daemon"
sed -i "s/^username = .*\$/username = ${MARIADB_UUSER}/" /etc/voipdomain/fastagi.conf
sed -i "s/^password = .*\$/password = ${MARIADB_UPASS}/" /etc/voipdomain/fastagi.conf
sed -i "s/^database = .*\$/database = ${MARIADB_DB}/" /etc/voipdomain/fastagi.conf
msg_done

msg_step "Configuring VoIP Domain monitor daemon"
sed -i "s/^serverid = .*\$/serverid = {{{ID}}}/" /etc/voipdomain/monitor.conf

# Remove existing [ami], [gearman], [mysql] sections and rebuild them
# This avoids sed replacing wrong values across sections
sed -i '/^\[ami\]/,/^\[/{ /^\[ami\]/d; /^\[/!d; }' /etc/voipdomain/monitor.conf
sed -i '/^\[gearman\]/,/^\[/{ /^\[gearman\]/d; /^\[/!d; }' /etc/voipdomain/monitor.conf
sed -i '/^\[mysql\]/,/^\[/{ /^\[mysql\]/d; /^\[/!d; }' /etc/voipdomain/monitor.conf

# Remove trailing blank lines
sed -i -e :a -e '/^\n*$/{$d;N;ba' -e '}' /etc/voipdomain/monitor.conf

# Add fresh sections with correct values
cat >> /etc/voipdomain/monitor.conf << EOF

[ami]
hostname = 127.0.0.1
port = 5038
username = ${AMI_USER}
password = ${AMI_PASS}

[gearman]
hostname = {{{GEARMAN_HOST}}}
port = {{{GEARMAN_PORT}}}

[mysql]
hostname = localhost
username = ${MARIADB_UUSER}
password = ${MARIADB_UPASS}
database = ${MARIADB_DB}
EOF
msg_done

#
# Install certificates
#
msg_step "Installing security certificates"
cat > /etc/voipdomain/client-certificate.key << 'EOF'
{{{PRIVATEKEY}}}
EOF
chmod 600 /etc/voipdomain/client-certificate.key

cat > /etc/voipdomain/client-certificate.pub << 'EOF'
{{{PUBLICKEY}}}
EOF

cat > /etc/voipdomain/master-certificate.pub << 'EOF'
{{{MASTERPUBLICKEY}}}
EOF
msg_done

#
# Configure SELinux (if enabled)
#
if command -v getenforce &>/dev/null && [[ "$(getenforce)" != "Disabled" ]]; then
  echo
  echo -e "${BOLD}Configuring SELinux:${RESET}"
  echo

  msg_step "Adding FastAGI port (4573) to Asterisk SELinux context"
  if ! semanage port -l 2>/dev/null | grep -q "asterisk_port_t.*4573"; then
    semanage port -a -t asterisk_port_t -p tcp 4573 2>/dev/null || true
  fi
  msg_done
fi

#
# Start services
#
echo
echo -e "${BOLD}Starting services:${RESET}"
echo

msg_step "Starting Asterisk"
systemctl enable asterisk.service &>/dev/null || true
systemctl start asterisk.service &>/dev/null
sleep 2
asterisk -rx "manager reload" &>/dev/null || true
msg_done

for service in voipdomain-client voipdomain-fastagi voipdomain-monitor; do
  msg_step "Starting ${service}"
  systemctl enable "${service}.service" &>/dev/null || true
  systemctl start "${service}.service" &>/dev/null
  msg_done
done

#
# Installation complete
#
echo
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
echo -e "${GREEN}${BOLD}                      INSTALLATION COMPLETE!                                  ${RESET}"
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
echo
echo -e "VoIP Domain server #{{{ID}}} has been successfully installed."
echo
echo -e "${BOLD}Configuration files:${RESET}"
echo "  /etc/voipdomain/client.conf"
echo "  /etc/voipdomain/fastagi.conf"
echo "  /etc/voipdomain/monitor.conf"
echo
echo -e "${BOLD}Asterisk VoIP Domain configs:${RESET}"
echo "  /etc/asterisk/voipdomain/bootstrap/  (static includes)"
echo "  /etc/asterisk/voipdomain/configs/    (dynamic configs)"
echo
echo -e "${BOLD}Services:${RESET}"
echo "  systemctl status voipdomain-client"
echo "  systemctl status voipdomain-fastagi"
echo "  systemctl status voipdomain-monitor"
echo
echo -e "${BOLD}Database:${RESET}"
echo "  Client database schema is synchronized from the admin interface"
echo "  (plugin_db_apply) after daemons connect."
echo
