Name: voipdomain
Version: 1.0
Release: 1%{?dist}
Summary: Enterprise network VoIP solution
License: GPLv3
Url: http://voipdomain.io/
Group: Applications/Communications
Source: https://voipdomain.io/download/%{name}-%{version}.tar.xz
BuildArch: noarch

%description
VoIP Domain network telephony solution meta package.

%package filesystem
Summary: VoIP Domain common directory structure and signing keys
Requires: openssl

%description filesystem
This package provides the VoIP Domain common filesystem layout, master signing
key, and keyring policy configuration. All other VoIP Domain packages depend on
this package.

%package webserver
Summary: VoIP Domain Web administrative interface
Requires: %{name}-filesystem = %{version}
Requires: %{name}-selinux = %{version}
Requires: php(language) >= 5.5.0
%if 0%{?rhel} > 7 || 0%{?fedora}
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-mbstring
Requires: php-gd
Requires: php-xml
Requires: php-fpm
Requires: php-pecl-gearman
Requires: php-pecl-zip
Requires: nginx
Recommends: mariadb-server
Recommends: gearmand
Recommends: php-pecl-imagick

%description webserver
This package provides VoIP Domain Web administrative interface files. You need
to use it with PHP-FPM and NGiNX.

%package daemons-common
Summary: VoIP Domain daemons common files
Requires: %{name}-filesystem = %{version}
Requires: %{name}-selinux = %{version}
Requires: php(language) >= 5.5.0
%if 0%{?rhel} > 7 || 0%{?fedora}
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli
Requires: php-mbstring
Requires: php-xml
Requires: php-process
Requires: php-pecl-inotify
Recommends: gearmand

%description daemons-common
This package contains VoIP Domain daemons common files.

%package router
Summary: VoIP Domain message router management daemon
Requires: %{name} = %{version}
Requires: %{name}-daemons-common = %{version}
Requires: php(language) >= 5.5.0
%if 0%{?rhel} > 7 || 0%{?fedora}
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli
Requires: php-pecl-gearman
Recommends: gearmand
%if 0%{?rhel} >= 8
Recommends: php-pecl-proctitle
%endif

%description router
This package provides the VoIP Domain message router daemon, which manages
communication between the server, Asterisk clients, and other daemons.

%package client
Summary: VoIP Domain Asterisk management client daemons
Requires: %{name} = %{version}
Requires: %{name}-daemons-common = %{version}
Requires: php(language) >= 5.5.0
%if 0%{?rhel} > 7 || 0%{?fedora}
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli
Requires: php-pecl-gearman
Requires: asterisk
Requires: mpg123
Requires: sox
Recommends: gearmand
%if 0%{?rhel} >= 8
Recommends: php-pecl-proctitle
%endif

%description client
This package provides Asterisk control client for the VoIP Domain
administration system.

%package fastagi
Summary: VoIP Domain FastAGI application daemon
Requires: %{name} = %{version}
Requires: %{name}-daemons-common = %{version}
Requires: php(language) >= 5.5.0
%if 0%{?rhel} > 7 || 0%{?fedora}
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli
Requires: php-pecl-gearman
Recommends: gearmand
%if 0%{?rhel} >= 8
Recommends: php-pecl-proctitle
%endif

%description fastagi
This package provides Asterisk FastAGI daemon for the applications of VoIP Domain
administration system.

%package monitor
Summary: VoIP Domain Asterisk server monitor daemon
Requires: %{name} = %{version}
Requires: %{name}-daemons-common = %{version}
Requires: php(language) >= 5.5.0
%if 0%{?rhel} > 7 || 0%{?fedora}
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli
Requires: php-pecl-gearman
Recommends: gearmand
%if 0%{?rhel} >= 8
Recommends: php-pecl-proctitle
%endif

%description monitor
This package provides Asterisk server monitor daemon of VoIP Domain
administration system.

%package selinux
Summary: VoIP Domain SELinux policy module
Requires: %{name} = %{version}
Requires: selinux-policy-targeted
Requires: policycoreutils
Requires: policycoreutils-python-utils
BuildRequires: selinux-policy-devel

%description selinux
This package provides the SELinux policy module for VoIP Domain daemons
and services. It defines security contexts and permissions required for
proper operation when SELinux is enforcing.


%prep
%setup -q


%build
# Build SELinux policy module
cd Documentations/selinux
make -f %{_datadir}/selinux/devel/Makefile voipdomain.pp
cd ../..


%install
rm -fr %{buildroot}
%{__mkdir_p} %{buildroot}%{_sysconfdir}/%{name}
%{__install} -p -D -m660 Documentations/etc/voipdomain/webinterface.conf %{buildroot}%{_sysconfdir}/%{name}/webinterface.conf
%{__install} -p -D -m600 Documentations/etc/voipdomain/router.conf %{buildroot}%{_sysconfdir}/%{name}/router.conf
%{__install} -p -D -m600 Documentations/etc/voipdomain/client.conf %{buildroot}%{_sysconfdir}/%{name}/client.conf
%{__install} -p -D -m600 Documentations/etc/voipdomain/fastagi.conf %{buildroot}%{_sysconfdir}/%{name}/fastagi.conf
%{__install} -p -D -m600 Documentations/etc/voipdomain/monitor.conf %{buildroot}%{_sysconfdir}/%{name}/monitor.conf
%{__mkdir_p} %{buildroot}%{_sysconfdir}/asterisk/%{name}
cp -R -d -p Documentations/etc/asterisk/%{name}/{configs,bootstrap} %{buildroot}%{_sysconfdir}/asterisk/%{name}/
%{__install} -p -D -m644 Documentations/etc/asterisk/asterisk.txt %{buildroot}%{_sysconfdir}/asterisk/%{name}/
%{__install} -p -D -m755 Documentations/etc/asterisk/configure-asterisk.sh %{buildroot}%{_sysconfdir}/asterisk/%{name}/
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/%{name}
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/%{name}/webroot/plugins
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/%{name}/storage/{avatars,captures,firmwares,recordings,sounds}
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/%{name}/daemons/{includes,client-modules,fastagi-modules,monitor-modules,router-modules}
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/%{name}/daemons/{client-plugins,fastagi-plugins,monitor-plugins,router-plugins}
cp -R -d -p Administrative\ Interface/* %{buildroot}%{_sharedstatedir}/%{name}/webroot

%{__mkdir_p} %{buildroot}%{_unitdir}
%{__install} -p -D -m644 Documentations/systemd/voipdomain-{client,fastagi,monitor,router}.service %{buildroot}%{_unitdir}/
%{__install} -p -D -m644 Documentations/systemd/voipdomain-daemon-reload@.{path,service} %{buildroot}%{_unitdir}/
%{__install} -p -D -m644 Documentations/systemd/voipdomain-tmpfiles.conf %{buildroot}%{_tmpfilesdir}/voipdomain.conf
%{__install} -p -D -m644 Documentations/systemd/voipdomain-webserver-tmpfiles.conf %{buildroot}%{_tmpfilesdir}/voipdomain-webserver.conf
%{__install} -p -D -m644 Documentations/systemd/voipdomain-client-tmpfiles.conf %{buildroot}%{_tmpfilesdir}/voipdomain-client.conf

%{__mkdir_p} %{buildroot}%{_localstatedir}/log/%{name}
%{__install} -p -D -m755 Daemons/{client,fastagi,monitor,router}.php %{buildroot}%{_sharedstatedir}/voipdomain/daemons/
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/%{name}/data
%{__mkdir_p} %{buildroot}%{_localstatedir}/spool/%{name}/file-transfer
%{__mkdir_p} %{buildroot}%{_localstatedir}/spool/%{name}/plugins

# Plugin signing keyring (owned by voipdomain-filesystem)
%{__mkdir_p} %{buildroot}%{_sysconfdir}/%{name}/keyring/developers
%{__install} -p -D -m644 Documentations/etc/voipdomain/keyring/voipdomain.pem %{buildroot}%{_sysconfdir}/%{name}/keyring/voipdomain.pem
%{__install} -p -D -m644 Documentations/etc/voipdomain/keyring/policy.conf %{buildroot}%{_sysconfdir}/%{name}/keyring/policy.conf
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/asterisk/sounds/voipdomain
%{__mkdir_p} %{buildroot}%{_datadir}/asterisk/sounds
ln -sf ../../../../var/lib/asterisk/sounds/voipdomain %{buildroot}%{_datadir}/asterisk/sounds/voipdomain
cp -R -d -p Daemons/{includes,client-modules,fastagi-modules,monitor-modules,router-modules} %{buildroot}%{_sharedstatedir}/voipdomain/daemons/

%{__mkdir_p} %{buildroot}%{_sysconfdir}/nginx/conf.d
%{__install} -p -D -m644 Documentations/etc/nginx/voipdomain.conf %{buildroot}%{_sysconfdir}/nginx/conf.d/voipdomain.conf
%{__install} -p -D -m644 Documentations/etc/nginx/voipdomain-fastcgi.conf %{buildroot}%{_sysconfdir}/nginx/voipdomain-fastcgi.conf
%{__install} -p -D -m644 Documentations/etc/php-fpm.d/voipdomain.conf %{buildroot}%{_sysconfdir}/php-fpm.d/voipdomain.conf
%{__mkdir_p} %{buildroot}%{_libexecdir}/voipdomain
ln -sf ../../../var/lib/voipdomain/daemons %{buildroot}%{_libexecdir}/voipdomain/daemons

# SELinux policy module
%{__mkdir_p} %{buildroot}%{_datadir}/selinux/packages/%{name}
%{__install} -p -D -m644 Documentations/selinux/voipdomain.pp %{buildroot}%{_datadir}/selinux/packages/%{name}/voipdomain.pp
%{__install} -p -D -m644 Documentations/selinux/voipdomain.te %{buildroot}%{_datadir}/selinux/packages/%{name}/voipdomain.te
%{__install} -p -D -m644 Documentations/selinux/voipdomain.fc %{buildroot}%{_datadir}/selinux/packages/%{name}/voipdomain.fc
%{__install} -p -D -m644 Documentations/selinux/voipdomain.if %{buildroot}%{_datadir}/selinux/packages/%{name}/voipdomain.if

# Logrotate configuration files
%{__mkdir_p} %{buildroot}%{_sysconfdir}/logrotate.d
%{__install} -p -D -m644 Documentations/etc/logrotate.d/voipdomain-client %{buildroot}%{_sysconfdir}/logrotate.d/voipdomain-client
%{__install} -p -D -m644 Documentations/etc/logrotate.d/voipdomain-fastagi %{buildroot}%{_sysconfdir}/logrotate.d/voipdomain-fastagi
%{__install} -p -D -m644 Documentations/etc/logrotate.d/voipdomain-monitor %{buildroot}%{_sysconfdir}/logrotate.d/voipdomain-monitor
%{__install} -p -D -m644 Documentations/etc/logrotate.d/voipdomain-router %{buildroot}%{_sysconfdir}/logrotate.d/voipdomain-router

%clean
rm -fr %{buildroot}


%posttrans
if rpm -q voipdomain-webserver >/dev/null 2>&1 && getent group nginx >/dev/null 2>&1; then
  install -d -m 0770 -o root -g nginx %{_sysconfdir}/voipdomain
  if [ -f %{_sysconfdir}/voipdomain/webinterface.conf ]; then
    chown root:nginx %{_sysconfdir}/voipdomain/webinterface.conf 2>/dev/null || :
    chmod 0660 %{_sysconfdir}/voipdomain/webinterface.conf 2>/dev/null || :
  fi
fi

%post daemons-common
systemd-tmpfiles --create %{_tmpfilesdir}/voipdomain.conf 1>/dev/null 2>&1 || :
install -d -m 0770 -o root -g root %{_sharedstatedir}/voipdomain/data 2>/dev/null || :

%post webserver
%systemd_post nginx.service php-fpm.service
systemctl enable nginx php-fpm 1>/dev/null 2>&1 || :
if getent group nginx >/dev/null 2>&1; then
  install -d -m 0770 -o root -g nginx %{_sysconfdir}/voipdomain
  if [ -f %{_sysconfdir}/voipdomain/webinterface.conf ]; then
    chown root:nginx %{_sysconfdir}/voipdomain/webinterface.conf 2>/dev/null || :
    chmod 0660 %{_sysconfdir}/voipdomain/webinterface.conf 2>/dev/null || :
  fi
fi
systemd-tmpfiles --create %{_tmpfilesdir}/voipdomain.conf 1>/dev/null 2>&1 || :
systemd-tmpfiles --create %{_tmpfilesdir}/voipdomain-webserver.conf 1>/dev/null 2>&1 || :
chown -R nginx:nginx %{_sharedstatedir}/voipdomain/webroot %{_sharedstatedir}/voipdomain/storage 2>/dev/null || :
find %{_sharedstatedir}/voipdomain/webroot -type d -exec chmod 0755 {} \; 2>/dev/null || :
find %{_sharedstatedir}/voipdomain/webroot -type f -exec chmod 0644 {} \; 2>/dev/null || :
chmod 0755 %{_sharedstatedir}/voipdomain/storage %{_sharedstatedir}/voipdomain/storage/* 2>/dev/null || :
restorecon -RF %{_sharedstatedir}/voipdomain/webroot 2>/dev/null || :
restorecon -RF %{_sharedstatedir}/voipdomain/storage 2>/dev/null || :
restorecon -F %{_sysconfdir}/voipdomain 2>/dev/null || :
systemctl start nginx php-fpm 1>/dev/null 2>&1 || :

%posttrans webserver
if getent group nginx >/dev/null 2>&1; then
  install -d -m 0770 -o root -g nginx %{_sysconfdir}/voipdomain
  systemd-tmpfiles --create %{_tmpfilesdir}/voipdomain-webserver.conf 1>/dev/null 2>&1 || :
  if [ -f %{_sysconfdir}/voipdomain/webinterface.conf ]; then
    chown root:nginx %{_sysconfdir}/voipdomain/webinterface.conf 2>/dev/null || :
    chmod 0660 %{_sysconfdir}/voipdomain/webinterface.conf 2>/dev/null || :
  fi
fi
restorecon -RF %{_sysconfdir}/voipdomain 2>/dev/null || :

%post router
%systemd_post voipdomain-router.service
%systemd_post voipdomain-daemon-reload@router.path
systemctl enable voipdomain-router.service 1>/dev/null 2>&1 || :
systemctl enable voipdomain-daemon-reload@router.path 1>/dev/null 2>&1 || :
systemctl start voipdomain-daemon-reload@router.path 1>/dev/null 2>&1 || :
install -d -m 0755 -o root -g root %{_localstatedir}/log/%{name} 2>/dev/null || :
install -d -m 0770 -o nobody -g nobody %{_sharedstatedir}/voipdomain/data 2>/dev/null || :
systemctl start voipdomain-router.service 1>/dev/null 2>&1 || :

%post client
%systemd_post voipdomain-client.service
%systemd_post voipdomain-daemon-reload@client.path
systemctl enable voipdomain-client.service 1>/dev/null 2>&1 || :
systemctl enable voipdomain-daemon-reload@client.path 1>/dev/null 2>&1 || :
systemctl start voipdomain-daemon-reload@client.path 1>/dev/null 2>&1 || :
rm -f %{_sysconfdir}/tmpfiles.d/voipdomain-tftpboot.conf 2>/dev/null || :
systemd-tmpfiles --create %{_tmpfilesdir}/voipdomain.conf 1>/dev/null 2>&1 || :
systemd-tmpfiles --create %{_tmpfilesdir}/voipdomain-client.conf 1>/dev/null 2>&1 || :
install -d -m 0755 -o asterisk -g asterisk /var/lib/tftpboot 2>/dev/null || :
install -d -m 0770 -o asterisk -g asterisk %{_sharedstatedir}/voipdomain/data 2>/dev/null || :
install -d -m 0755 -o asterisk -g asterisk %{_sysconfdir}/asterisk/voipdomain/bootstrap 2>/dev/null || :
install -d -m 0750 -o asterisk -g asterisk %{_sysconfdir}/asterisk/voipdomain/configs 2>/dev/null || :
chown asterisk:asterisk %{_sysconfdir}/asterisk/voipdomain/configs/*.conf 2>/dev/null || :
chown asterisk:asterisk %{_sysconfdir}/asterisk/voipdomain/bootstrap/*.conf 2>/dev/null || :
systemctl start voipdomain-client.service 1>/dev/null 2>&1 || :

%post fastagi
%systemd_post voipdomain-fastagi.service
%systemd_post voipdomain-daemon-reload@fastagi.path
systemctl enable voipdomain-fastagi.service 1>/dev/null 2>&1 || :
systemctl enable voipdomain-daemon-reload@fastagi.path 1>/dev/null 2>&1 || :
systemctl start voipdomain-daemon-reload@fastagi.path 1>/dev/null 2>&1 || :
systemd-tmpfiles --create %{_tmpfilesdir}/voipdomain.conf 1>/dev/null 2>&1 || :
install -d -m 0770 -o asterisk -g asterisk %{_sharedstatedir}/voipdomain/data 2>/dev/null || :
systemctl start voipdomain-fastagi.service 1>/dev/null 2>&1 || :

%post monitor
%systemd_post voipdomain-monitor.service
%systemd_post voipdomain-daemon-reload@monitor.path
systemctl enable voipdomain-monitor.service 1>/dev/null 2>&1 || :
systemctl enable voipdomain-daemon-reload@monitor.path 1>/dev/null 2>&1 || :
systemctl start voipdomain-daemon-reload@monitor.path 1>/dev/null 2>&1 || :
install -d -m 0770 -o asterisk -g asterisk %{_sharedstatedir}/voipdomain/data 2>/dev/null || :
systemctl start voipdomain-monitor.service 1>/dev/null 2>&1 || :

%post selinux
semodule -i %{_datadir}/selinux/packages/%{name}/voipdomain.pp 2>/dev/null || :
restorecon -RF %{_sysconfdir}/voipdomain 2>/dev/null || :
restorecon -RF %{_sysconfdir}/asterisk/voipdomain 2>/dev/null || :
restorecon -RF %{_sharedstatedir}/voipdomain 2>/dev/null || :
restorecon -RF %{_sharedstatedir}/asterisk/sounds/voipdomain 2>/dev/null || :
restorecon -RF %{_localstatedir}/log/voipdomain 2>/dev/null || :
restorecon -RF %{_localstatedir}/log/asterisk/cdr.db 2>/dev/null || :
restorecon -RF %{_localstatedir}/spool/voipdomain 2>/dev/null || :
restorecon -RF %{_sharedstatedir}/tftpboot 2>/dev/null || :
restorecon -RF %{_unitdir}/voipdomain-*.service 2>/dev/null || :
restorecon -RF %{_unitdir}/voipdomain-*.path 2>/dev/null || :

%preun router
%systemd_preun voipdomain-daemon-reload@router.path
%systemd_preun voipdomain-router.service

%preun client
%systemd_preun voipdomain-daemon-reload@client.path
%systemd_preun voipdomain-client.service

%preun fastagi
%systemd_preun voipdomain-daemon-reload@fastagi.path
%systemd_preun voipdomain-fastagi.service

%preun monitor
%systemd_preun voipdomain-daemon-reload@monitor.path
%systemd_preun voipdomain-monitor.service

%preun selinux
if [ $1 -eq 0 ]; then
  semodule -r voipdomain 2>/dev/null || :
fi

%postun selinux
if [ $1 -eq 0 ]; then
  restorecon -RF %{_sysconfdir}/voipdomain 2>/dev/null || :
  restorecon -RF %{_sysconfdir}/asterisk/voipdomain 2>/dev/null || :
  restorecon -RF %{_sharedstatedir}/voipdomain 2>/dev/null || :
  restorecon -RF %{_sharedstatedir}/asterisk/sounds/voipdomain 2>/dev/null || :
  restorecon -RF %{_localstatedir}/log/voipdomain 2>/dev/null || :
  restorecon -RF %{_localstatedir}/spool/voipdomain 2>/dev/null || :
  restorecon -RF %{_sharedstatedir}/tftpboot 2>/dev/null || :
fi


%files
%defattr(0644,root,root,0755)

%files webserver
%defattr(0644,root,root,0755)
%doc Documentations/LICENSE.TXT Documentations/README.md Documentations/en Documentations/pt_BR Documentations/etc/nginx Documentations/etc/php-fpm.d
%attr(0644,root,root) %config(noreplace) %{_sysconfdir}/nginx/conf.d/voipdomain.conf
%attr(0644,root,root) %config(noreplace) %{_sysconfdir}/nginx/voipdomain-fastcgi.conf
%attr(0644,root,root) %config(noreplace) %{_sysconfdir}/php-fpm.d/voipdomain.conf
%attr(0660,root,nginx) %config(noreplace) %{_sysconfdir}/%{name}/webinterface.conf
%attr(0755,nginx,nginx) %dir %{_sharedstatedir}/voipdomain/webroot
%attr(0755,nginx,nginx) %dir %{_sharedstatedir}/voipdomain/storage
%attr(0755,nginx,nginx) %dir %{_sharedstatedir}/voipdomain/storage/avatars
%attr(0755,nginx,nginx) %dir %{_sharedstatedir}/voipdomain/storage/captures
%attr(0755,nginx,nginx) %dir %{_sharedstatedir}/voipdomain/storage/firmwares
%attr(0755,nginx,nginx) %dir %{_sharedstatedir}/voipdomain/storage/recordings
%attr(0755,nginx,nginx) %dir %{_sharedstatedir}/voipdomain/storage/sounds
%attr(0755,nginx,nginx) %dir %{_sharedstatedir}/voipdomain/webroot/plugins
%attr(0770,root,nginx) %dir %{_localstatedir}/spool/%{name}/plugins
%{_tmpfilesdir}/voipdomain-webserver.conf
%exclude %{_sharedstatedir}/voipdomain/webroot/plugins
%{_sharedstatedir}/voipdomain/webroot/*

%files filesystem
%defattr(0644,root,root,0755)
%attr(0770,root,root) %dir %{_sysconfdir}/%{name}
%attr(0755,root,root) %dir %{_sysconfdir}/%{name}/keyring
%attr(0755,root,root) %dir %{_sysconfdir}/%{name}/keyring/developers
%attr(0644,root,root) %{_sysconfdir}/%{name}/keyring/voipdomain.pem
%config(noreplace) %{_sysconfdir}/%{name}/keyring/policy.conf
%attr(0755,root,root) %dir %{_sharedstatedir}/%{name}
%attr(0770,root,root) %dir %{_sharedstatedir}/%{name}/data
%attr(0755,root,root) %dir %{_localstatedir}/log/%{name}
%attr(0755,root,root) %dir %{_localstatedir}/spool/%{name}

%files daemons-common
%defattr(0644,root,root,0755)
%doc Documentations/LICENSE.TXT Documentations/README.md Documentations/en Documentations/pt_BR
%{_libexecdir}/voipdomain/daemons
%{_sharedstatedir}/voipdomain/daemons/includes/
%{_unitdir}/voipdomain-daemon-reload@.path
%{_unitdir}/voipdomain-daemon-reload@.service
%{_tmpfilesdir}/voipdomain.conf

%files router
%defattr(0644,root,root,0755)
%doc Documentations/LICENSE.TXT Documentations/README.md Documentations/en Documentations/pt_BR
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/router.conf
%{_unitdir}/voipdomain-router.service
%config(noreplace) %{_sysconfdir}/logrotate.d/voipdomain-router
%attr(0755,root,root) %{_sharedstatedir}/voipdomain/daemons/router.php
%{_sharedstatedir}/voipdomain/daemons/router-modules/
%attr(0775,root,nobody) %dir %{_sharedstatedir}/voipdomain/daemons/router-plugins

%files client
%defattr(0644,root,root,0755)
%doc Documentations/LICENSE.TXT Documentations/README.md Documentations/en Documentations/pt_BR
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/client.conf
%attr(0750,root,asterisk) %dir %{_sysconfdir}/asterisk/%{name}
%attr(0640,root,root) %{_sysconfdir}/asterisk/%{name}/asterisk.txt
%attr(0750,root,root) %{_sysconfdir}/asterisk/%{name}/configure-asterisk.sh
%attr(0750,asterisk,asterisk) %dir %{_sysconfdir}/asterisk/%{name}/configs
%attr(0755,asterisk,asterisk) %dir %{_sysconfdir}/asterisk/%{name}/bootstrap
%attr(0644,asterisk,asterisk) %{_sysconfdir}/asterisk/%{name}/bootstrap/*.conf
%config(noreplace) %attr(0644,asterisk,asterisk) %{_sysconfdir}/asterisk/%{name}/configs/dialplan-globals.conf
%config(noreplace) %attr(0644,asterisk,asterisk) %{_sysconfdir}/asterisk/%{name}/configs/indications-voipdomain.conf
%attr(0644,asterisk,asterisk) %{_sysconfdir}/asterisk/%{name}/configs/*-000000.conf
%{_unitdir}/voipdomain-client.service
%{_tmpfilesdir}/voipdomain-client.conf
%config(noreplace) %{_sysconfdir}/logrotate.d/voipdomain-client
%attr(0755,asterisk,asterisk) %dir %{_localstatedir}/spool/%{name}/file-transfer
%attr(0755,asterisk,asterisk) %dir %{_sharedstatedir}/asterisk/sounds/voipdomain
%dir %{_datadir}/asterisk
%dir %{_datadir}/asterisk/sounds
%{_datadir}/asterisk/sounds/voipdomain
%attr(0755,root,root) %{_sharedstatedir}/voipdomain/daemons/client.php
%{_sharedstatedir}/voipdomain/daemons/client-modules/
%attr(0775,root,asterisk) %dir %{_sharedstatedir}/voipdomain/daemons/client-plugins

%files fastagi
%defattr(0644,root,root,0755)
%doc Documentations/LICENSE.TXT Documentations/README.md Documentations/en Documentations/pt_BR
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/fastagi.conf
%{_unitdir}/voipdomain-fastagi.service
%config(noreplace) %{_sysconfdir}/logrotate.d/voipdomain-fastagi
%attr(0755,root,root) %{_sharedstatedir}/voipdomain/daemons/fastagi.php
%{_sharedstatedir}/voipdomain/daemons/fastagi-modules/
%attr(0775,root,asterisk) %dir %{_sharedstatedir}/voipdomain/daemons/fastagi-plugins

%files monitor
%defattr(0644,root,root,0755)
%doc Documentations/LICENSE.TXT Documentations/README.md Documentations/en Documentations/pt_BR
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/monitor.conf
%{_unitdir}/voipdomain-monitor.service
%config(noreplace) %{_sysconfdir}/logrotate.d/voipdomain-monitor
%attr(0755,root,root) %{_sharedstatedir}/voipdomain/daemons/monitor.php
%{_sharedstatedir}/voipdomain/daemons/monitor-modules/
%attr(0775,root,asterisk) %dir %{_sharedstatedir}/voipdomain/daemons/monitor-plugins

%files selinux
%defattr(0644,root,root,0755)
%doc Documentations/LICENSE.TXT Documentations/selinux
%{_datadir}/selinux/packages/%{name}/voipdomain.pp
%{_datadir}/selinux/packages/%{name}/voipdomain.te
%{_datadir}/selinux/packages/%{name}/voipdomain.fc
%{_datadir}/selinux/packages/%{name}/voipdomain.if


%changelog
* Tue Jul 21 2026 Ernani Azevedo <azevedo@voipdomain.io> - 1.0-1
- First stable release
- Server rebuild logic: selective main/backup rebuild with per-server crypto keys
- Router: per-tenant key management; pre-privilege-drop key caching
- Command lifecycle: executed commands properly removed from database
- Comprehensive bug fix sweep (~30 issues across 16 files)
- Full documentation refresh for 1.0

* Wed Jul 15 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-19
- Client: fix soundsdir to write to Asterisk var sounds directory (/var/lib/asterisk/sounds/voipdomain)
- Client: remove broken symlink from /usr/share/asterisk/sounds/voipdomain
- Client: remove /var/lib/voipdomain/storage/sounds from client package (webserver-only)
- Client: fix SELinux: use /var/lib/asterisk/sounds (asterisk_var_lib_t) instead of /usr/share (usr_t)

* Mon Jul 06 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-18
- Client: add storage directories (/var/lib/voipdomain/storage/sounds, /var/spool/voipdomain/file-transfer)
- Client: fix soundsdir path consistency (use /var/lib/voipdomain/storage/sounds)
- install.sh: configure server address in client.conf for PJSIP client_uri
- Client daemon: validate address is configured before creating server configs

* Fri Jul 03 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-17
- install.sh v2.0: major rewrite with interactive/non-interactive modes
- install.sh: package status display (installed vs missing)
- install.sh: -y/--yes flag for non-interactive installation
- install.sh: --skip-packages flag for configure-only mode
- install.sh: automatic configuration of all Asterisk bootstrap includes
- install.sh: creation of placeholder configs to prevent Asterisk include errors
- Add manager-000000.conf placeholder for manager bootstrap

* Fri Jul 03 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-16
- Add bootstrap/manager.conf for AMI configuration using VoIP Domain structure
- configure-asterisk.sh: now configures manager.conf with VoIP Domain bootstrap include
- install.sh: use /etc/asterisk/voipdomain/configs/manager-voipdomain.conf for AMI user

* Thu Jul 02 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-15
- New subpackage: voipdomain-selinux with dedicated policy module for all VoIP Domain components
- webserver, daemons-common: require voipdomain-selinux for SELinux policy
- Daemon PHP scripts: add %attr(0755) for execute permission (fixes systemd EXEC error 203)
- daemons-common: add php-mbstring, php-xml, php-process, php-pecl-inotify dependencies
- Daemons: friendly error messages for MySQL connection failures (try-catch mysqli_sql_exception)
- PHP 8 compatibility: fix stream_select/socket_select inline assignment in reference parameters
  (fastagi.php, asterisk.inc.php, amicli.php)

* Wed Jun 10 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-14
- webinterface.conf: mode 0660 root:nginx (0640 gave group read-only; PHP could not write)
- install check: write probes; preflight before DB install; clearer 0640 vs 0660 errors

* Wed Jun 10 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-11
- install/check API: verbose Checks[] diagnostics; %%post webserver fixes /etc/voipdomain 0770

* Wed Jun 10 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-10
- nginx: remove strict CSP; TLS 1.2+1.3; static /js /css locations; X-Frame-Options SAMEORIGIN

* Wed Jun 10 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-9
- nginx vhost: fix GET / returning 404 (remove index.php + try_files $uri/ trap)

* Wed Jun 10 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-8
- webinterface.conf sample: installed=false, no [mysql] (web installer on first boot)
- PHP 8: MySQL connect uses error page instead of uncaught mysqli_sql_exception

* Wed Jun 10 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-7
- /etc/voipdomain: 0770 root:nginx via webserver %%dir, tmpfiles, and %%posttrans
- webinterface.conf 0640 root:nginx on install and upgrade (fixes PHP-FPM read access)

* Wed Jun 10 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-6
- webinterface.conf: mode 0640 root:nginx so PHP-FPM can read it

* Fri Jun 05 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-5
- Split nginx storage tmpfiles into voipdomain-webserver.conf (fixes Asterisk-only upgrade)

* Fri Jun 05 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-4
- Split asterisk datadir tmpfiles into voipdomain-client.conf (fixes admin-only upgrade)

* Fri Jun 05 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-3
- voipdomain-client: Requires asterisk

* Fri Jun 05 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-2
- Webserver: ship Documentations/etc/nginx in %%doc (incl. nginx.conf reference)
- Vhost source path Documentations/etc/nginx/voipdomain.conf; nginx user ownership
- tmpfiles: storage subdirs (nginx) and data (asterisk); %%post permission fixes

* Fri Jun 05 2026 Ernani Azevedo <azevedo@voipdomain.io> - 0.3-1
- Release candidate 1: dynamic timezone picker from host tzdata
- Documentation hub reorganized (en/, pt_BR/); rewritten install and security guides
- VDFT streaming, plugin remote sync, monitor delta sync, inter-server PJSIP trunks

* Mon Jul 21 2025 Ernani Azevedo <azevedo@voipdomain.io> - 0.2-1
- Align systemd units, nginx/php-fpm examples, and daemon paths
- Install web vhost and PHP-FPM pool as config files
- Document RPM layout; fix %doc references

* Fri Apr 11 2025 Ernani Azevedo <azevedo@voipdomain.io> - 0.1-1
- Initial package file
