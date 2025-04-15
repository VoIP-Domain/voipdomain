Name: voipdomain
Version: 0.1.rc1
Release: 1%{?dist}
Summary: Asterisk web administration interface
License: GPLv3
Url: http://voipdomain.io/
Group: Applications/Communications
Source: https://voipdomain.io/download/%{name}-%{version}.tar.xz
BuildArch: noarch

%description
VoIP Domain network telephony solution meta package.

%package webserver
Summary: VoIP Domain Web administrative interface
Requires: %{name}-%{version}
Requires: php(language) >= 5.4.0
%if 0%{?rhel} > 7
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-mbstring
Requires: php-fpm
Requires: php-pecl-gearman
Requires: nginx

%description webserver
This package provides VoIP Domain Web administrative interface files. You need
to use it with PHP-FPM and NGiNX.

%package daemons-common
Summary: VoIP Domain daemons common files
Requires: %{name}-%{version}
Requires: php(language) >= 5.4.0
%if 0%{?rhel} > 7
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli

%description daemons-common
This package contains VoIP Domain daemons common files.

%package router
Summary: VoIP Domain message router management daemon
Requires: %{name}-%{version}
Requires: %{name}-daemons-common-%{version}
Requires: php(language) >= 5.4.0
%if 0%{?rhel} > 7
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli
Requires: php-pecl-gearman
%if 0%{?rhel} >= 8
Recommends: php-pecl-proctitle
%endif

%description router
This package provides VoIP Domain message router daemon, that manage
communication between server and Asterisk clients and other daemons.

%package client
Summary: VoIP Domain Asterisk management client daemons
Requires: %{name}-%{version}
Requires: %{name}-daemons-common-%{version}
Requires: php(language) >= 5.4.0
%if 0%{?rhel} > 7
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli
Requires: php-pecl-gearman
%if 0%{?rhel} >= 8
Recommends: php-pecl-proctitle
%endif

%description client
This package provides Asterisk controlled client for the VoIP Domain
administration system.

%package fastagi
Summary: VoIP Domain FastAGI application daemon
Requires: %{name}-%{version}
Requires: %{name}-daemons-common-%{version}
Requires: php(language) >= 5.4.0
%if 0%{?rhel} > 7
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli
%if 0%{?rhel} >= 8
Recommends: php-pecl-proctitle
%endif

%description fastagi
This package provides Asterisk FastAGI daemon for the applications of VoIP Domain
administration system.

%package monitor
Summary: VoIP Domain Asterisk server monitor daemon
Requires: %{name}-%{version}
Requires: %{name}-daemons-common-%{version}
Requires: php(language) >= 5.4.0
%if 0%{?rhel} > 7
Requires: php-mysqlnd
%else
Requires: php-mysql
%endif
%if 0%{?rhel} == 8
Requires: php-json
%endif
Requires: php-cli
Requires: php-pecl-gearman
%if 0%{?rhel} >= 8
Recommends: php-pecl-proctitle
%endif

%description monitor
This package provides Asterisk server monitor daemon of VoIP Domain
administration system.


%prep
%setup -q


%build


%install
rm -fr %{buildroot}
%{__mkdir_p} %{buildroot}%{_sysconfdir}/%{name}
%{__install} -p -D -m644 Documentations/etc/voipdomain/{client,fastagi,monitor,router,webinterface}.conf %{buildroot}%{_sysconfdir}/%{name}/
%{__mkdir_p} %{buildroot}%{_sysconfdir}/asterisk/%{name}
cp -R -d -p Documentations/etc/asterisk/%{name}/{configs,bootstrap} %{buildroot}%{_sysconfdir}/asterisk/%{name}/
%{__install} -p -D -m644 Documentations/etc/asterisk/asterisk.txt %{buildroot}%{_sysconfdir}/asterisk/%{name}/
%{__install} -p -D -m755 Documentations/etc/asterisk/configure-asterisk.sh %{buildroot}%{_sysconfdir}/asterisk/%{name}/
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/%{name}
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/%{name}/{webroot,daemons,data,storage}
%{__mkdir_p} %{buildroot}%{_sharedstatedir}/%{name}/daemons/{includes,client-modules,fastagi-modules,monitor-modules,router-modules}
cp -R -d -p Administrative\ Interface/* %{buildroot}%{_sharedstatedir}/%{name}/webroot

%{__mkdir_p} %{buildroot}%{_unitdir}
%{__install} -p -D -m644 Documentations/systemd/voipdomain-{client,fastagi,monitor,router}.service %{buildroot}%{_unitdir}/

%{__mkdir_p} %{buildroot}%{_localstatedir}/log/%{name}
%{__install} -p -D -m755 Daemons/{client,fastagi,monitor,router}.php %{buildroot}%{_sharedstatedir}/voipdomain/daemons/
cp -R -d -p Daemons/{includes,client-modules,fastagi-modules,monitor-modules,router-modules} %{buildroot}%{_sharedstatedir}/voipdomain/daemons/


%clean
rm -fr %{buildroot}


%post router
%systemd_post voipdomain-router.service

%post client
%systemd_post voipdomain-client.service

%post fastagi
%systemd_post voipdomain-fastagi.service

%post monitor
%systemd_post voipdomain-monitor.service

%preun router
%systemd_preun voipdomain-router.service

%preun client
%systemd_preun voipdomain-client.service

%preun fastagi
%systemd_preun voipdomain-fastagi.service

%preun monitor
%systemd_preun voipdomain-monitor.service


%files
%defattr(0644,root,root,0755)

%files webserver
%defattr(0644,root,root,0755)
%doc Documentations/DEVELOPERS-FAQ.TXT Documentations/FAQ.TXT Documentations/INSTALL.TXT Documentations/INSTALL-pt_BR.TXT Documentations/SECURITY-TIPS.TXT Documentations/SECURITY-TIPS-pt_BR.TXT Documentations/LICENSE.TXT Documentations/etc/nginx Documentations/etc/php-fpm.d
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/webinterface.conf
%{_sharedstatedir}/voipdomain/webroot
%{_sharedstatedir}/voipdomain/storage

%files daemons-common
%defattr(0644,root,root,0755)
%doc Documentations/DEVELOPERS-FAQ.TXT Documentations/FAQ.TXT Documentations/INSTALL.TXT Documentations/INSTALL-pt_BR.TXT Documentations/SECURITY-TIPS.TXT Documentations/SECURITY-TIPS-pt_BR.TXT Documentations/LICENSE.TXT
%{_sharedstatedir}/voipdomain/daemons/includes/

%files router
%defattr(0644,root,root,0755)
%doc Documentations/DEVELOPERS-FAQ.TXT Documentations/FAQ.TXT Documentations/INSTALL.TXT Documentations/INSTALL-pt_BR.TXT Documentations/SECURITY-TIPS.TXT Documentations/SECURITY-TIPS-pt_BR.TXT Documentations/LICENSE.TXT
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/router.conf
%attr(0700,root,root) %dir %{_localstatedir}/log/%{name}
%{_unitdir}/voipdomain-router.service
%{_sharedstatedir}/voipdomain/daemons/router.php
%{_sharedstatedir}/voipdomain/daemons/router-modules/

%files client
%defattr(0644,root,root,0755)
%doc Documentations/DEVELOPERS-FAQ.TXT Documentations/FAQ.TXT Documentations/INSTALL.TXT Documentations/INSTALL-pt_BR.TXT Documentations/SECURITY-TIPS.TXT Documentations/SECURITY-TIPS-pt_BR.TXT Documentations/LICENSE.TXT
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/client.conf
%attr(0750,asterisk,asterisk) %dir %{_sysconfdir}/asterisk/%{name}
%attr(0640,root,root) %{_sysconfdir}/asterisk/%{name}/asterisk.txt
%attr(0750,root,root) %{_sysconfdir}/asterisk/%{name}/configure-asterisk.sh
%attr(0750,asterisk,asterisk) %dir %{_sysconfdir}/asterisk/%{name}/configs
%attr(0755,root,root) %dir %{_sysconfdir}/asterisk/%{name}/bootstrap
%attr(0644,root,root) %{_sysconfdir}/asterisk/%{name}/bootstrap/*.conf
%attr(0755,asterisk,asterisk) %dir %{_sysconfdir}/asterisk/%{name}/configs
%attr(0644,asterisk,asterisk) %{_sysconfdir}/asterisk/%{name}/configs/dialplan-globals.conf
%attr(0644,root,root) %{_sysconfdir}/asterisk/%{name}/configs/*-000000.conf
%attr(0700,root,root) %dir %{_localstatedir}/log/%{name}
%{_unitdir}/voipdomain-client.service
%{_sharedstatedir}/voipdomain/data
%{_sharedstatedir}/voipdomain/daemons/client.php
%{_sharedstatedir}/voipdomain/daemons/client-modules/

%files fastagi
%defattr(0644,root,root,0755)
%doc Documentations/DEVELOPERS-FAQ.TXT Documentations/FAQ.TXT Documentations/INSTALL.TXT Documentations/INSTALL-pt_BR.TXT Documentations/SECURITY-TIPS.TXT Documentations/SECURITY-TIPS-pt_BR.TXT Documentations/LICENSE.TXT
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/fastagi.conf
%attr(0700,root,root) %dir %{_localstatedir}/log/%{name}
%{_unitdir}/voipdomain-fastagi.service
%{_sharedstatedir}/voipdomain/daemons/fastagi.php
%{_sharedstatedir}/voipdomain/daemons/fastagi-modules/

%files monitor
%defattr(0644,root,root,0755)
%doc Documentations/DEVELOPERS-FAQ.TXT Documentations/FAQ.TXT Documentations/INSTALL.TXT Documentations/INSTALL-pt_BR.TXT Documentations/SECURITY-TIPS.TXT Documentations/SECURITY-TIPS-pt_BR.TXT Documentations/LICENSE.TXT
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/monitor.conf
%attr(0700,root,root) %dir %{_localstatedir}/log/%{name}
%{_unitdir}/voipdomain-monitor.service
%{_sharedstatedir}/voipdomain/daemons/monitor.php
%{_sharedstatedir}/voipdomain/daemons/monitor-modules/


%changelog
* Sat Apr 12 2025 Ernani Azevedo <azevedo@voipdomain.io> - 0.1.rc1-1
- Initial package file
