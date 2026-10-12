# VoIP Domain — Início Rápido

Instalação mínima em **30 minutos** para conhecer o sistema. English: [../en/QUICK-START.md](../en/QUICK-START.md).

> **Objetivo:** Subir um ambiente all-in-one (admin + Asterisk) em uma única máquina para explorar a interface e testar chamadas básicas. Não é recomendado para produção sem ajustes de segurança e escalabilidade.

---

## Pré-requisitos

| Item | Requisito |
|------|-----------|
| **SO** | RHEL 9 ou compatível (Rocky Linux, AlmaLinux, etc) |
| **RAM** | 2 GB mínimo (4 GB recomendado) |
| **Disco** | 20 GB livres |
| **Rede** | Acesso à internet para baixar pacotes |
| **Usuário** | Acesso root ou sudo |

---

## Passo 1 — Configurar repositório VoIP Domain

```bash
sudo tee /etc/yum.repos.d/VoIPDomain.repo > /dev/null << 'EOF'
[voipdomain]
name=VoIP Domain Repository
baseurl=https://rhel.voipdomain.io/9/
enabled=1
gpgcheck=1
gpgkey=https://rhel.voipdomain.io/azevedo@voipdomain.io.key
EOF

sudo rpm --import https://rhel.voipdomain.io/azevedo@voipdomain.io.key
```

---

## Passo 2 — Instalar pacotes

```bash
sudo dnf install -y \
  mariadb-server \
  gearmand \
  nginx \
  php-fpm \
  asterisk \
  asterisk-pjsip \
  asterisk-voicemail-plain \
  asterisk-sounds-core-en-ulaw \
  asterisk-sounds-core-en-alaw \
  tftp-server \
  voipdomain-webserver \
  voipdomain-router \
  voipdomain-client \
  voipdomain-fastagi \
  voipdomain-monitor \
  voipdomain-daemons-common
```

---

## Passo 3 — Iniciar serviços base

```bash
sudo systemctl enable --now mariadb.service
sudo systemctl enable --now gearmand.service
sudo systemctl enable --now nginx.service
sudo systemctl enable --now php-fpm.service
sudo systemctl enable --now asterisk.service
sudo systemctl enable --now tftp.service
```

---

## Passo 4 — Criar diretórios e permissões

```bash
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain.conf
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-webserver.conf
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-client.conf
```

---

## Passo 5 — Liberar firewall (opcional)

Se estiver usando firewalld:

```bash
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --permanent --add-port=5060/udp   # SIP
sudo firewall-cmd --permanent --add-port=5060/tcp   # SIP
sudo firewall-cmd --permanent --add-port=10000-20000/udp  # RTP
sudo firewall-cmd --reload
```

---

## Passo 6 — Acessar o instalador web

1. Abra o navegador e acesse: `http://<IP_DO_SERVIDOR>/`

2. O instalador web vai guiá-lo pelos passos:
   - **Verificação de permissões** — deve passar automaticamente
   - **Banco de dados** — informe:
     - Host: `localhost`
     - Usuário root MariaDB: `root` (deixe senha em branco se não configurou)
     - O instalador criará o banco `vd` e usuário `vd` automaticamente
   - **Administrador** — crie o usuário admin (padrão: `admin` / `admin`)

3. Após concluir, faça login com as credenciais criadas.

---

## Passo 7 — Iniciar daemons VoIP Domain

```bash
sudo systemctl enable --now voipdomain-router.service
sudo systemctl enable --now voipdomain-client.service
sudo systemctl enable --now voipdomain-fastagi.service
sudo systemctl enable --now voipdomain-monitor.service

# Path units para reload automático
sudo systemctl enable --now voipdomain-daemon-reload@router.path
sudo systemctl enable --now voipdomain-daemon-reload@client.path
sudo systemctl enable --now voipdomain-daemon-reload@fastagi.path
sudo systemctl enable --now voipdomain-daemon-reload@monitor.path
```

---

## Passo 8 — Registrar o servidor Asterisk

1. Na interface web, vá em **Servidores** → **Adicionar**
2. Preencha:
   - **Nome:** `Servidor Local`
   - **Endereço:** `127.0.0.1`
   - **Porta:** `5060`
3. Salve e anote o **ID do servidor** gerado (ex: `1`)

---

## Passo 9 — Configurar Asterisk

Execute o script de configuração com o ID do servidor:

```bash
sudo /etc/asterisk/voipdomain/configure-asterisk.sh <ID_DO_SERVIDOR>
# Exemplo: sudo /etc/asterisk/voipdomain/configure-asterisk.sh 1
```

Confirme com `y` quando solicitado.

Recarregue o Asterisk:

```bash
sudo asterisk -rx "core reload"
```

---

## Passo 10 — Criar estrutura mínima para teste

Na interface web, crie os objetos na seguinte ordem:

| Ordem | Menu | O que criar |
|-------|------|-------------|
| 1 | **Perfis** | Um perfil (ex: "Padrão") |
| 2 | **Centros de Custo** | Um centro de custo (ex: "Geral") |
| 3 | **Grupos** | Um grupo associado ao perfil e centro de custo |
| 4 | **Faixas** | Uma faixa de ramais (ex: 1000-1099) associada ao grupo |
| 5 | **Ramais** | Dois ramais para teste (ex: 1001 e 1002) |

---

## Passo 11 — Testar uma chamada

1. Configure um softphone (ex: Zoiper, Linphone, MicroSIP) com:
   - **Servidor:** IP da sua máquina
   - **Usuário:** número do ramal (ex: `1001`)
   - **Senha:** senha definida no ramal
   - **Protocolo:** SIP (UDP)

2. Repita para o segundo ramal em outro dispositivo/softphone

3. Disque de um ramal para o outro (ex: `1002`)

4. Se a chamada completar, o sistema está funcionando!

---

## Verificação rápida

| Verificação | Comando |
|-------------|---------|
| Interface web carrega | Acessar `http://<IP>/` |
| MariaDB rodando | `sudo systemctl status mariadb` |
| Gearman rodando | `gearadmin --status` |
| Router ativo | `sudo systemctl status voipdomain-router` |
| Client registrado | `gearadmin --status | grep vd_client` |
| Asterisk ok | `sudo asterisk -rx "pjsip show endpoints"` |

---

## Solução de problemas comuns

| Problema | Solução |
|----------|---------|
| Página em branco/erro 500 | Verifique logs: `sudo journalctl -u php-fpm -f` |
| Instalador diz sem permissão | Execute: `sudo chmod 0770 /etc/voipdomain && sudo chown root:nginx /etc/voipdomain` |
| Nó offline na UI | Verifique: `sudo systemctl status voipdomain-client` |
| Ramal não registra | Verifique firewall e porta 5060 |
| Sem áudio na chamada | Verifique portas RTP (10000-20000 UDP) |

---

## Próximos passos

Após validar a instalação básica, consulte:

| Tópico | Documento |
|--------|-----------|
| Instalação completa | [INSTALL.md](INSTALL.md) |
| Planejamento de produção | [PLANEJAMENTO-DE-IMPLANTACAO.md](PLANEJAMENTO-DE-IMPLANTACAO.md) |
| Segurança | [SEGURANCA.md](SEGURANCA.md) |
| Arquitetura | [ARQUITETURA.md](ARQUITETURA.md) |
| Multi-tenant | Configurar em **Sistema** → **Tenants** |
| Gateways externos | Adicionar em **Gateways** para chamadas externas |
| TFTP/DHCP | Para provisionamento automático de telefones |

---

## Resumo dos comandos

```bash
# 1. Repositório
sudo tee /etc/yum.repos.d/VoIPDomain.repo > /dev/null << 'EOF'
[voipdomain]
name=VoIP Domain Repository
baseurl=https://rhel.voipdomain.io/9/
enabled=1
gpgcheck=1
gpgkey=https://rhel.voipdomain.io/azevedo@voipdomain.io.key
EOF
sudo rpm --import https://rhel.voipdomain.io/azevedo@voipdomain.io.key

# 2. Pacotes
sudo dnf install -y mariadb-server gearmand nginx php-fpm asterisk \
  asterisk-pjsip asterisk-voicemail-plain asterisk-sounds-core-en-ulaw \
  asterisk-sounds-core-en-alaw tftp-server voipdomain-webserver \
  voipdomain-router voipdomain-client voipdomain-fastagi voipdomain-monitor \
  voipdomain-daemons-common

# 3. Serviços base
sudo systemctl enable --now mariadb.service gearmand.service nginx.service php-fpm.service \
  asterisk.service tftp.service

# 4. Diretórios
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain.conf
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-webserver.conf
sudo systemd-tmpfiles --create /usr/lib/tmpfiles.d/voipdomain-client.conf

# 5. Firewall (se aplicável)
sudo firewall-cmd --permanent --add-service=http --add-service=https
sudo firewall-cmd --permanent --add-port=5060/udp --add-port=5060/tcp
sudo firewall-cmd --permanent --add-port=10000-20000/udp
sudo firewall-cmd --reload

# 6. Acessar http://<IP>/ e completar instalador web

# 7. Daemons VoIP Domain (após instalador web)
sudo systemctl enable --now voipdomain-router.service voipdomain-client.service \
  voipdomain-fastagi.service voipdomain-monitor.service
sudo systemctl enable --now voipdomain-daemon-reload@{router,client,fastagi,monitor}.path

# 8. Registrar servidor na UI e anotar ID

# 9. Configurar Asterisk
sudo /etc/asterisk/voipdomain/configure-asterisk.sh <ID>
sudo asterisk -rx "core reload"

# 10. Criar perfil, centro de custo, grupo, faixa, ramais na UI

# 11. Testar chamada entre dois ramais
```
