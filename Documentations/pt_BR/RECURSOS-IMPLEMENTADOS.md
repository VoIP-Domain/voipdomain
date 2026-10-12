# Recursos implementados (v1.0)

Este documento descreve a infraestrutura e os recursos de plataforma implementados
no ciclo de desenvolvimento recente. Destina-se a operadores e desenvolvedores
que precisam entender **o que foi adicionado**, **como funciona** e **onde está no
código**.

Para instalação e pré-requisitos de deploy, veja [INSTALL.md](INSTALL.md) e [README.md](README.md).

Para convenções de hooks, plugins e arquitetura completa, veja [MANUAL-DO-DESENVOLVEDOR.md](MANUAL-DO-DESENVOLVEDOR.md).

---

## 1. VoIP Domain File Transfer (VDFT)

Payloads binários grandes (firmware, áudio, arquivos de plugin) deixam de ser
enviados como blob base64 único em eventos Gearman quando excedem o limite inline.
A interface administrativa prepara o ficheiro localmente e transmite-o aos nós
Asterisk em chunks.

### Eventos do protocolo

| Evento | Função |
|--------|--------|
| `file_transfer_begin` | Abre sessão de transferência no nó destino |
| `file_transfer_chunk` | Entrega um chunk (payload base64, máx. 256 KiB) |
| `file_transfer_commit` | Valida a sessão e dispara o `TargetEvent` |
| `file_transfer_abort` | Cancela a sessão e remove partes em staging |

Helpers do emissor: `Administrative Interface/includes/file-transfer.inc.php`.
Sessões no receptor: `Daemons/client-modules/file-transfer/client.php`.

### Escrita de payloads nos daemons

Handlers aceitam `Data` inline (legado) ou `FilePath` em staging. Use
`write_payload_to_file()` em `Daemons/includes/functions.inc.php` — faz streaming
em blocos de 1 MiB e apaga o ficheiro de staging ao concluir.

### Falha parcial

Se `file_transfer_send()` falhar ao despachar eventos, emite `file_transfer_abort`
para o `TransferID` ativo, limpando chunks órfãos em
`/var/spool/voipdomain/file-transfer/`.

### Diretório de runtime

Instale `Documentations/systemd/voipdomain-tmpfiles.conf` e execute
`systemd-tmpfiles --create`.

---

## 2. Sincronização de áudio

Operações de áudio na interface administrativa usam VDFT em vez de base64 inline.

| Hook API | Evento destino | Notas |
|----------|----------------|-------|
| `audios_add` | `audio_add` | Envia o ficheiro a todos os servidores |
| `audios_edit` | `audio_change` | Dispara quando o conteúdo muda, **mesmo se o nome do ficheiro permanecer igual** |
| `audios_remove` | `audio_remove` | Remove áudio convertido nos nós |

Daemon: `Daemons/client-modules/audios/client.php`.

---

## 3. Deploy de firmware (todos os vendors)

Handlers de upload migrados para `write_payload_to_file()`:

Yealink, Audiocodes, Khomp, Snom, Grandstream, Fanvil, Htek e Cisco —
em `Daemons/client-modules/equipments-*/client.php`.

---

## 4. Extração de firmware Cisco

Arquivos são escritos no TFTP e extraídos no lugar. O arquivo original é mantido;
conteúdo extraído é achatado no diretório do modelo.

### Destinos

| Subdiretório | Tipo | Modelos |
|--------------|------|---------|
| `Cisco/3905` | zip | CP3905 |
| `Cisco/7811` | zip | SPA78xx |
| `Cisco/8961` | zip | CP8961 |
| `Cisco/Locale` | tar | Pacotes de locale |

### Limpeza

- `remove_firmware` — limpa ficheiros extraídos e arquivos por modelo, incluindo Locale
- `firmware_cisco_wipe` — wipe global também limpa `Cisco/Locale` antes do diretório vendor

Hooks corrigidos de `firmare_*` para `firmware_*`.

---

## 5. Ciclo de vida de plugins e sync remoto

### API enable / disable

`POST /api/plugins/:Plugin/toggle|enable|disable` (`plugins_toggle`):

1. Atualiza BD e renomeia diretório (`disabled_`)
2. Hooks de lifecycle (`enable` / `disable`)
3. Deploy/undeploy local de módulos daemon
4. Sync remoto de `*-modules/` via VDFT
5. Reload systemd nos units afetados (local + remoto)

Permissão: `plugins_toggle` (Super-Administrator).

### Sync remoto

- **deploy** → `plugin_daemon_module_deploy` (tar via VDFT)
- **remove** → `plugin_daemon_module_remove`
- **BD** → manifest JSON via `plugin_db_apply` no `vdclient` de cada nó

Código: `Administrative Interface/includes/plugin-install.inc.php`,
`Daemons/client-modules/plugins/client.php`.

### Reload systemd

Path units `voipdomain-daemon-reload@<tipo>.path`. Em dev/all-in-one sem path
units, use reload manual:

```bash
systemctl reload voipdomain-client.service
```

---

## 6. Trunks PJSIP inter-servidor

Peers PJSIP são criados automaticamente ao adicionar, alterar ou reconstruir
servidores num tenant multi-servidor. A comunicação entre servidores usa
**confiança baseada em IP** (via seção `identify` do PJSIP) em vez de
autenticação por senha, já que servidores do mesmo tenant são considerados
em uma rede interna confiável.

### Configuração gerada

Para cada servidor peer, o daemon client cria:

- **Endpoint** com `context=VoIPDomain-Internal` e `allow=all`
- **AOR** com `contact=sip:ENDERECO:PORTA`
- **Identify** com `match=ENDERECO` (confiança por IP)

Nenhuma credencial de autenticação é gerada ou trocada entre servidores.

---

## 7. Sincronização delta do monitor

Após reload AMI, o monitor compara snapshot anterior vs. atual e emite só diferenças
(apenas em reload, nunca no mesmo ciclo que bootup).

### Canais

`channels_sync_reload()` — `channels_destroy`, `channels_create`, `channels_state`,
`channels_callerid`. Bootup continua com `channels_bootup` completo.

### Filas

`queues_sync_reload()` — eventos delta de membros e callers na fila.

### Router

`queues_member_remove` aceita `Member` (monitor) com fallback para `Name` (legado).

---

## 8. Reload de cache do router

### Hook `router_reload_cache`

Aliases: `notifications_reload`, `notification_create`, `notification_change`,
`notification_destroy`.

Recarrega notificações, limpa `$_in["keys"]` e zera `Channels`/`Queues`/`Peers`
(global ou por `ServerID`).

### Disparo

```php
router_request_cache_reload ( array ());
router_request_cache_reload ( array ( "ServerID" => 3));
```

Task Gearman: `router_reload_cache`. API de notificações usa este helper após
add/edit/remove.

SIGHUP recarrega cache **in-process** (sem restart do daemon).

---

## 9. Migração de número de extensão (astDB)

Hook `extension_number_migrate` —
`extension_phone_migrate_asterisk_database()` em
`Daemons/client-modules/extensions-phones/client.php`.

Migra: `lastcallerid`, `CFBS`, `CIDA`, `VoIPDomain-counter`, `denylist-{ext}`,
`lastcalled`. Remove typo legado `lastcalledid`.

Na remoção de extensão, `extension_phone_clear_asterisk_database()` limpa tudo.

---

## 10. Formatação numérica JavaScript

`voipdomain_toFixed()` em `Administrative Interface/js/voipdomain.js` — corrige
arredondamento em browsers legados. Usado por `sprintf()` (`%e/%f/%g`) e
`number_format()`.

Formatação localizada: use `number_format(n, dec, decPoint, thousandsSep)` com
separadores explícitos; `sprintf %f` não aplica locale.

---

## 11. Nomenclatura Gearman

Worker do client daemon: `vd_client_{serverid}_task`. Nós standby com
`backup = true` em `client.conf` também registram `vd_backup_server_{serverid}_task`.

---

## 12. Instalação e operações

Ver [INSTALL.md](INSTALL.md): path units, tmpfiles, setup dev/all-in-one,
sync BD de plugins via `plugin_db_apply`.

---

## 13. Referência rápida

| Nome | Tipo | Local |
|------|------|-------|
| `router_reload_cache` | Gearman / hook | `Daemons/router.php` |
| `write_payload_to_file()` | Helper PHP | `Daemons/includes/functions.inc.php` |
| `file_transfer_abort` | Evento Gearman | `Daemons/client-modules/file-transfer/client.php` |
| `extension_number_migrate` | Hook | `Daemons/client-modules/extensions-phones/client.php` |
| `plugins_toggle` | Hook API | `Administrative Interface/modules/config/api.php` |
| `equipment_model_cisco_deploy_firmware_archive()` | Helper | `equipments-cisco/client.php` |

Documentação completa em inglês: [../en/IMPLEMENTED-FEATURES.md](../en/IMPLEMENTED-FEATURES.md).
