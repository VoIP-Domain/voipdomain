    ___ ___       ___ _______     ______                        __
   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
   |:  1   |     |:  |:  |       |:  1    /
    \:.. ./      |::.|::.|       |::.. . /
     `---'       `---`---'       `------'

 Copyright (C) 2016-2025 Ernani José Camargo Azevedo

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <https://www.gnu.org/licenses/>.

                           COMO INSTALAR
                         --=============--

O sistema VoIP Domain necessita de uma série de requisitos para funcionar. O
desenvolvedor deste software utiliza o RHEL como sistema Linux principal para
todos os servidores (ou apenas um servidor, caso deseje rodar tudo junto).

Os requisitos para a interface de administração principal são:

* Servidor NGiNX (http://nginx.org/)
* Servidor MariaDB (https://mariadb.org/)
* PHP (no mínimo versão 5.5.0) com suporte ao PHP-FPM
  (https://www.php.net/manual/en/install.fpm.php) e os seguintes módulos:
  - PDO
  - SimpleXML
  - curl
  - date
  - dom
  - ereg
  - exif
  - fileinfo
  - gd
  - gearman (https://www.php.net/manual/en/book.gearman.php)
  - gettext
  - hash
  - iconv
  - imagick
  - json
  - libxml
  - mbstring
  - mysql
  - mysqli
  - openssl
  - pcntl
  - pcre
  - session
  - sockets
  - standard
  - xml
  - zip
* Módulo Gearman PECL para PHP (https://pecl.php.net/package/gearman)

Você pode executar a interface administrativa em uma pequena máquina virtual ou
servidor de baixo custo. A interface administrativa não precisa estar ativa 100%
do tempo, pois os servidores Asterisk gerenciados pelo sistema não dependem dela
para funcionar. Qualquer dado offline (como bilhetagem, RMC etc.) pode ser
processado localmente nos servidores Asterisk e depois enviado à interface
administrativa.

A interface administrativa será usada para configurar seu ambiente de telefonia
e fornecer relatórios de uso.

Para configurar o PHP-FPM e o NGiNX, você pode utilizar como referência os
exemplos no diretório de documentações.

Os requisitos para os daemons de controle são:

* PHP (no mínimo versão 5.5.0) com suporte CLI e os seguintes módulos:
  - SimpleXML
  - curl
  - date
  - dom
  - ereg
  - gearman (https://www.php.net/manual/en/book.gearman.php)
  - gettext
  - hash
  - json
  - mbstring
  - mysql
  - mysqli
  - openssl
  - pcntl
  - pcre
  - posix
  - proctitle (https://www.php.net/manual/en/book.proctitle.php) (opcional)
  - sockets
  - standard
* Módulo Gearman PECL para PHP (https://pecl.php.net/package/gearman)

Os daemons de controle irão basicamente rotear os eventos do Asterisk e da
interface administrativa e gerar notificações para daemons externos. O daemon de
monitoramento conectará à interface AMI do Asterisk e processará os eventos de
telefonia enviados pela API Gearman ao daemon de roteamento. Você precisará de um
daemon de monitoramento por servidor Asterisk e apenas um daemon de roteamento
para todo o sistema. É possível executar esses daemons em qualquer lugar da sua
rede, mas é recomendado rodar o daemon de monitoramento do Asterisk na mesma
máquina do servidor Asterisk, para evitar perda de eventos em caso de problemas
de rede. O daemon de roteamento pode rodar no mesmo servidor da interface
administrativa.

Os requisitos para os servidores Asterisk são:

* Asterisk (no mínimo versão 20.0.0)
* Servidor MariaDB (https://mariadb.org/)
* PHP (no mínimo versão 5.5.0) com suporte CLI e os seguintes módulos:
  - SimpleXML
  - curl
  - date
  - dom
  - ereg
  - gettext
  - hash
  - json
  - mbstring
  - mysql
  - mysqli
  - openssl
  - pcntl
  - pcre
  - posix
  - proctitle (https://www.php.net/manual/en/book.proctitle.php) (opcional)
  - sockets
  - standard
* Módulo Gearman PECL para PHP (https://pecl.php.net/package/gearman)

O servidor MariaDB é necessário para funcionar com o Asterisk, armazenando
localmente os dados de bilhetagem. Caso a comunicação com a interface
administrativa seja interrompida, os dados não serão perdidos e serão
sincronizados quando a conexão for restabelecida. Além disso, alguns plugins
precisam de um banco de dados local para funcionar (por exemplo, o padrão da
agência de telecomunicações brasileira, que exige uma base de dados enorme para
determinar o custo da chamada). O PHP é necessário para executar o daemon de
configuração, que receberá eventos da interface administrativa para alterar as
configurações do Asterisk e fornecer inteligência básica ao sistema, como RMC.

Uma vez que tudo esteja instalado, será necessário criar o banco de dados
principal do sistema e populá-lo com o script de instalação MariaDB encontrado em
docs/voipdomain.sqldump.

Ao iniciar o sistema, o usuário padrão será "admin", com senha "admin".

Para configurar o sistema, será necessário criar uma estrutura básica de objetos,
na seguinte sequência:

1) Servidor
2) Gateway (você pode criar um fictício para testar o sistema)
3) Perfil
4) Centro de Custo
5) Grupo
6) Faixa
7) Extensão

Depois de tudo isso, você poderá realizar chamadas e gerar relatórios na
interface administrativa.

Instalando servidor all-in-one:
-------------------------------

Se você estiver executando todos os serviços no mesmo servidor, lembre-se de que
o processo php-fpm rodará como o usuário nginx, e o certificado principal do
servidor (/etc/voipdomain/master-certificate.key) será de propriedade desse
usuário. O daemon router precisará ler esse arquivo. Use setfacl para permitir
que o usuário asterisk (que executa o daemon router) leia o arquivo após a
instalação do sistema:
```
# setfacl -m u:asterisk:rw- /etc/voipdomain/master-certificate.key
```

Instalando servidor Asterisk:
-----------------------------

A configuração do servidor Asterisk é feita incluindo os arquivos bootstrap do
VoIP Domain em cada arquivo de configuração necessário.

Basta instalar um servidor Asterisk novo e incluir as linhas abaixo em cada
arquivo de configuração:
- agents.conf:
  #include voipdomain/bootstrap/agents.conf
- extensions.conf:
  #include voipdomain/bootstrap/dialplan.conf
- musiconhold.conf:
  #include voipdomain/bootstrap/musiconhold.conf
- pjsip.conf:
  #include voipdomain/bootstrap/pjsip.conf
- pjsip_notify.conf:
  #include voipdomain/bootstrap/pjsip_notify.conf
- queues.conf:
  #include voipdomain/bootstrap/queues.conf
- voicemail.conf:
  #include voipdomain/bootstrap/voicemail.conf

Você também pode executar o script "configure-asterisk.sh" disponível no
diretório /etc/asterisk/voipdomain/ para fazer essa configuração automaticamente.
