# VoIP Domain — Visão geral para planejamento de implantação

Este documento ajuda arquitetos e operadores a **desenhar a instalação** antes de executar [INSTALL.md](INSTALL.md). O VoIP Domain é flexível: componentes podem ser distribuídos por site, colocalizados ou omitidos quando a funcionalidade não for necessária.

**Relacionado:** [ARQUITETURA.md](ARQUITETURA.md) · [DIALPLAN.md](DIALPLAN.md) · [SEGURANCA.md](SEGURANCA.md) · [../rpm/README.md](../rpm/README.md)

English: [../en/DEPLOYMENT-PLANNING.md](../en/DEPLOYMENT-PLANNING.md)

---

## 1. Entradas para o planejamento

| Entrada | Motivo |
|---------|--------|
| Número de **sites** (filiais, DCs, regiões) | Gearman, TFTP, estratégia MariaDB |
| **Confiabilidade WAN** entre filiais e admin | Fila `Commands` vs UI em tempo real |
| **Quantidade de telefones** e fabricantes | Provisionamento, firmware, TFTP por LAN |
| **LCR / billing / AGI de agentes** | Onde rodar FastAGI e qual banco usar |
| **Dashboard ao vivo** | Monitor + router obrigatórios |
| **HA / regulatório** | Servidores standby, réplicas de BD |

---

## 2. Componentes de software

| Componente | Onde roda | Dependências | Função |
|------------|-----------|--------------|--------|
| **Interface web + API** | Servidor admin | MariaDB **`vd`**, NGiNX, PHP-FPM | Configuração, relatórios, comandos de provisionamento |
| **voipdomain-router** | Admin (típico) | **`vd`**, Gearman | Estado ao vivo, notificações, replay de `Commands` |
| **gearmand** | Admin e/ou cada site | Rede entre nós | Barramento de jobs (porta **4730**) |
| **voipdomain-client** | Cada nó Asterisk | Gearman, **`vdclient`**, Asterisk | Dialplan/PJSIP/TFTP; aplica mudanças do admin |
| **Asterisk + PJSIP** | Nó de telefonia | `/etc/asterisk/voipdomain/` | Processamento de chamadas |
| **voipdomain-monitor** | Cada nó (típico) | AMI **5038**, Gearman → router | Canais, filas, peers na UI |
| **voipdomain-fastagi** | Flexível (§6) | TCP **1234**, `datadir`, MariaDB opcional | LCR, agentes, custo de chamada |
| **MariaDB `vd`** | Servidor admin | — | **Central**: tenants, servidores, `Commands` |
| **MariaDB `vdclient`** | Cada nó (típico) | — | **Local**: plugins, cache offline |
| **TFTP / HTTP** | **Por LAN de telefones** (externo) | Arquivos do `tftpdir` do client | Auto-provisionamento (não incluso no RPM) |

O VoIP Domain **gera** arquivos de provisionamento; **não inclui** servidor TFTP ou DHCP. A opção **66** ou **150** do DHCP deve apontar para o **servidor de provisionamento na mesma rede dos telefones**.

---

## 3. Dois bancos de dados — central vs nó

| Banco | Conteúdo | Quem usa | Independência |
|-------|----------|----------|---------------|
| **`vd`** | Modelo lógico, tenants, **`Commands`** | Admin, **router** | Instância central (réplica para HA se necessário) |
| **`vdclient`** | Plugins, cache local | **Client**, **monitor**, **FastAGI** no nó | **Um por nó Asterisk** |

**Princípios:**

- Nós Asterisk **não precisam** de `vd` online para chamadas do dia a dia — dialplan/PJSIP/JSON em cache bastam.
- Ao reconectar, o client **reprocessa** `vd`.`Commands`.
- **`vdclient`** é populado por `plugin_db_apply` — não pelo instalador web no nó.

**Dados FastAGI:** FastAGI lê dados de gateways dos arquivos JSON locais (`gateway-*.json`) em `datadir`, sincronizados pelo daemon client. Não requer conexão com banco de dados. Quando FastAGI roda em host diferente do client, garanta que `datadir` seja compartilhado ou replicado.

---

## 4. Topologias

### 4.1 Admin central, Asterisk distribuído

Admin (web + router + `vd` + Gearman) + N nós (client + Asterisk + `vdclient`).

### 4.2 Filial remota (resiliente a queda de link)

Asterisk + client + `vdclient` na filial; TFTP na **LAN local**; Gearman central ou local. Link down: **chamadas seguem**; **alterações ficam na fila**; UI ao vivo para o site para sem monitor→router.

### 4.3 All-in-one (lab)

Tudo num host — ver [INSTALL.md](INSTALL.md) §4.

### 4.4 Pool FastAGI centralizado

Todos os nós com `AGIADDR` apontando para o pool; firewall Asterisk → **4573/tcp**; `datadir` replicado para que FastAGI leia os arquivos JSON de gateways.

### 4.5 Trunking entre sites

Vários registros **`Servers`** no admin com configuração automática de peers PJSIP (confiança por IP).

---

## 5. Matriz de daemons opcionais

| Omitir | Chamadas? | Efeito |
|--------|-----------|--------|
| **client** | Só com config em cache | Sem updates do admin |
| **monitor** | Sim | Sem estado ao vivo na UI |
| **fastagi** | Parcial | LCR/AGI de agentes falham |
| **router** | Sim nos nós | Sem UI ao vivo / replay central |

**Mínimo filial (só chamadas):** Asterisk + **client** + **`vdclient`** + Gearman (ou replay após reconexão).

---

## 6. Auto-provisionamento por rede

1. Cada **`Server`** no admin tem **address** na VLAN dos telefones.
2. Client grava em **`tftpdir`** (definir em `client.conf`).
3. Templates usam **`general.address`** do client.
4. **tftpd** (ou HTTP do fabricante) na **mesma LAN**.
5. **DHCP:** opção **66** (TFTP) ou **150** (VoIP).
6. Telefones **não precisam** alcançar a interface admin.

**Exemplo multi-site:** Filial A → DHCP/TFTP A; Filial B → DHCP/TFTP B; admin central único.

---

## 7. Gearman

| Modelo | Uso |
|--------|-----|
| **Central** | VPN estável; `client.conf`: `[gearman] servers = admin:4730` |
| **Por site** | WAN instável; gearmand local por filial |

Router/monitor: `hostname` + `port`; client: `servers = host:port`.

---

## 8. Portas (checklist)

| Serviço | Porta |
|---------|-------|
| HTTPS admin | 443 |
| Gearman | 4730 |
| MariaDB `vd` | 3306 (rede interna) |
| FastAGI | 1234/tcp |
| AMI | 5038 (local) |

Detalhes: [SEGURANCA.md](SEGURANCA.md).

---

## 9. Comportamento offline

| Cenário | Comportamento |
|---------|---------------|
| WAN down | Asterisk mantém config; `Commands` na fila |
| Client parado | Sem novas configs |
| Monitor parado | Chamadas OK; UI desatualizada |
| FastAGI parado | LCR/AGI falham |

Sem merge automático de conflitos em partições longas.

---

## 10. Standby

`ServerBackup` + `backup = true` em `client.conf` → worker `vd_backup_server_{id}_task`.

---

## 11. Chaves `client.conf` (produção)

Além do exemplo mínimo, definir em **cada nó**:

```ini
[general]
address = 10.0.1.50
tftpdir = /var/lib/tftpboot/voipdomain
datadir = /var/lib/voipdomain/data
soundsdir = /var/lib/voipdomain/storage/sounds

[gearman]
servers = admin.exemplo.com:4730

[mysql]
database = vdclient
```

---

## 12. Mapa para instalação

| Decisão | Documento |
|---------|-----------|
| RPM | [../rpm/README.md](../rpm/README.md) |
| Passo a passo | [INSTALL.md](INSTALL.md) |
| Dialplan / plugins | [DIALPLAN.md](DIALPLAN.md) |
| Testes | [../testing/README.md](../testing/README.md) |

---

## 13. Checklist pré-voo

### Servidor admin

- [ ] MariaDB **`vd`**, NGiNX, PHP-FPM, `gearmand`, `voipdomain-router`
- [ ] Firewall 443, 4730

### Cada nó Asterisk

- [ ] Client + Asterisk + **`vdclient`**
- [ ] `address`, `tftpdir`, `datadir`, `[gearman]`
- [ ] TFTP/DHCP na LAN local
- [ ] `#include voipdomain/bootstrap/dialplan.conf`

### FastAGI centralizado (opcional)

- [ ] `AGIADDR`, `datadir`, firewall :4573

---

*Versão: 2026-06 — VoIP Domain 0.3 RC1.*
