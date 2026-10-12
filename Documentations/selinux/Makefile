# VoIP Domain SELinux module Makefile

POLICY_NAME = voipdomain
POLICY_VERSION = 1.0.0

# Paths
SHAREDIR ?= /usr/share
SELINUX_DEVEL = $(SHAREDIR)/selinux/devel

# Files
TE_FILE = $(POLICY_NAME).te
FC_FILE = $(POLICY_NAME).fc
IF_FILE = $(POLICY_NAME).if
PP_FILE = $(POLICY_NAME).pp

all: $(PP_FILE)

$(PP_FILE): $(TE_FILE) $(FC_FILE) $(IF_FILE)
	$(MAKE) -f $(SELINUX_DEVEL)/Makefile $(POLICY_NAME).pp

clean:
	rm -f $(POLICY_NAME).pp tmp/*.mod tmp/*.mod.fc
	rm -rf tmp

install: $(PP_FILE)
	semodule -i $(PP_FILE)

uninstall:
	semodule -r $(POLICY_NAME) || true

relabel:
	restorecon -RF /etc/voipdomain
	restorecon -RF /var/lib/voipdomain
	restorecon -RF /var/log/voipdomain
	restorecon -RF /var/spool/voipdomain
	restorecon -RF /run/voipdomain 2>/dev/null || true
	restorecon -RF /usr/lib/systemd/system/voipdomain-*.service
	restorecon -RF /usr/lib/systemd/system/voipdomain-*.path

.PHONY: all clean install uninstall relabel
