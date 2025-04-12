Name: voipdomain
Version: 1.0.0.rc1
Release: 1%{?dist}
Summary: Asterisk web administration interface
License: GPLv3
Url: http://www.voipdomain.io/
Group: Applications/Communications
Source: https://www.voipdomain.io/download/%{name}-%{version}.tar.bz2
BuildArch: noarch
Requires: php(language) >= 5.4.0
Requires: php-mysql >= 5.4.0

%description
This package provides VoIP Domain administrative interface files.

%package client
Summary: VoIP Domain Asterisk management client daemons
Requires: php(language) >= 5.4.0
Requires: php-mysql >= 5.4.0
Requires: php-cgi >= 5.4.0

%description client
This package provides Asterisk controlled client for the VoIP Domain
administration system.

%package fastagi
Summary: VoIP Domain FastAGI application daemon
Requires: php(language) >= 5.4.0
Requires: php-mysql >= 5.4.0
Requires: php-cgi >= 5.4.0

%description fastagi
This package provides Asterisk FastAGI daemon for the applications of VoIP Domain
administration system.

%package autodetect
Summary: VoIP Domain IP VoIP hardware network auto detect daemon
Requires: php(language) >= 5.4.0
Requires: php-mysql >= 5.4.0
Requires: php-cgi >= 5.4.0

%description autodetect
This package provides network IP VoIP hardware auto detect for the VoIP Domain
administration system.


%prep
%setup -q


%build


%install
rm -fr %{buildroot}
%{__mkdir_p} %{buildroot}%{_sysconfdir}/%{name}
%{__install} -p -D -m644 docs/etc/voipdomain/webinterface.conf %{buildroot}%{_sysconfdir}/%{name}/
%{__mkdir_p} %{buildroot}%{_datadir}/%{name}
cp -R -d -p webinterface/* %{buildroot}%{_datadir}/%{name}

%{__mkdir_p} %{buildroot}%{_unitdir}
%{__install} -p -D -m644 docs/systemd/voipdomain-{autodetect,client,fastagi,monitor,router}.service %{buildroot}%{_unitdir}/

%{__mkdir_p} %{buildroot}%{_libexecdir}/voipdomain/{autodetect,client,fastagi,monitor,router} %{buildroot}%{_localstatedir}/log/%{name}
%{__install} -p -D -m755 daemons/autodetect.php %{buildroot}%{_libexecdir}/voipdomain/autodetect/
%{__install} -p -D -m755 daemons/client.php %{buildroot}%{_libexecdir}/voipdomain/client/
%{__install} -p -D -m755 daemons/fastagi.php %{buildroot}%{_libexecdir}/voipdomain/fastagi/
%{__install} -p -D -m755 daemons/monitor.php %{buildroot}%{_libexecdir}/voipdomain/monitor/
%{__install} -p -D -m755 daemons/router.php %{buildroot}%{_libexecdir}/voipdomain/router/
%{__install} -p -D -m644 docs/etc/voipdomain/{autodetect,client,fastagi,monitor,router}.conf %{buildroot}%{_sysconfdir}/%{name}/


%clean
rm -fr %{buildroot}


%post client
%systemd_post voipdomain-client.service
%systemd_post voipdomain-monitor.service
%systemd_post voipdomain-router.service

%post fastagi
%systemd_post voipdomain-fastagi.service

%post autodetect
%systemd_post voipdomain-autodetect.service

%preun client
%systemd_preun voipdomain-client.service
%systemd_preun voipdomain-monitor.service
%systemd_preun voipdomain-router.service

%preun fastagi
%systemd_preun voipdomain-fastagi.service

%preun autodetect
%systemd_preun voipdomain-autodetect.service


%files
%defattr(0644,root,root,0755)
%doc docs/INSTALL.TXT docs/INSTALL-pt_BR.TXT docs/LICENSE.TXT docs/voipdomain.sqldump docs/etc/nginx docs/etc/php-fpm.d docs/API docs/utils
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/webinterface.conf
%{_datadir}/%{name}

%files client
%defattr(0644,root,root,0755)
%doc docs/LICENSE.TXT docs/SECURITY-TIPS.TXT docs/SECURITY-TIPS-pt_BR.TXT docs/voipdomain-client.sqldump
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/client.conf
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/monitor.conf
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/router.conf
%attr(0700,root,root) %dir %{_localstatedir}/log/%{name}
%{_unitdir}/voipdomain-client.service
%{_unitdir}/voipdomain-monitor.service
%{_unitdir}/voipdomain-router.service
%{_libexecdir}/voipdomain/client
%{_libexecdir}/voipdomain/monitor
%{_libexecdir}/voipdomain/router

%files fastagi
%defattr(0644,root,root,0755)
%doc docs/LICENSE.TXT
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/fastagi.conf
%attr(0700,root,root) %dir %{_localstatedir}/log/%{name}
%{_unitdir}/voipdomain-fastagi.service
%{_libexecdir}/voipdomain/fastagi

%files autodetect
%defattr(0644,root,root,0755)
%doc docs/LICENSE.TXT
%attr(0600,root,root) %config(noreplace) %{_sysconfdir}/%{name}/autodetect.conf
%attr(0700,root,root) %dir %{_localstatedir}/log/%{name}
%{_unitdir}/voipdomain-autodetect.service
%{_libexecdir}/voipdomain/autodetect


%changelog
* Mon Aug  5 2019 Ernani Azevedo <azevedo@voipdomain.io> - 1.0.0-1
- Initial package file
