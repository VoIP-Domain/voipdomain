# VoIP Domain — Metodologia de testes do sistema

Metodologia repetível para testar a plataforma VoIP Domain de ponta a ponta, com
**cobertura explícita da arquitetura baseada em hooks**. Destinada a ambientes
lab/staging — não produção.

**Docs relacionados:** [INSTALL.md](INSTALL.md), [../rpm/README.md](../rpm/README.md),
[../en/DEVELOPER-GUIDE.md](../en/DEVELOPER-GUIDE.md),
[RECURSOS-IMPLEMENTADOS.md](RECURSOS-IMPLEMENTADOS.md)

Versão completa em inglês: [../en/TESTING-METHODOLOGY.md](../en/TESTING-METHODOLOGY.md)

---

## 1. Inventário (varredura do código)

| Ativo | Qtd. aprox. | Local |
|-------|-------------|-------|
| Hooks | ~770 | `framework_add_hook()` |
| Filters | ~685 | `framework_add_filter()` |
| Módulos REST API | 44 | `Administrative Interface/modules/*/api.php` |
| Módulos client daemon | 31 | `Daemons/client-modules/*/client.php` |

Não dá para executar manualmente cada hook antes de cada release. Priorize
**cobertura por risco**: hooks de infraestrutura, cadeias CRUD, telefonia e
funcionalidades recentes.

---

## 2. Princípios

1. **Lab primeiro** — tenant dedicado, gateway dummy, extensões de teste, snapshot BD.
2. **Em camadas** — estático → hook isolado → API → daemon → telefonia → UI.
3. **Observável** — cada teste define logs, ficheiros, BD e eventos Gearman esperados.
4. **Não destrutivo por defeito** — classifique hooks antes de invocar (secção 4).
5. **Reproduzível** — registe nome do hook, JSON, ServerID e timestamp.

---

## 3. Ambiente de teste

### Lab mínimo (all-in-one)

NGiNX + PHP-FPM (`/var/lib/voipdomain/webroot` em RPM), MariaDB (`vd` + `vdclient`), Gearman, Asterisk, daemons
`voipdomain-{client,router,monitor,fastagi}`, spool
`/var/spool/voipdomain/file-transfer/`.

Debug no client daemon durante investigação de hooks:

```ini
debug = true
eventsfile = /var/log/voipdomain/client-events.log
```

Monitor em foreground:

```bash
php /var/lib/voipdomain/daemons/monitor.php --debug
```

### Pré-voo

```bash
systemctl is-active voipdomain-client voipdomain-router voipdomain-monitor gearmand mariadb asterisk
curl -sS -b cookies.txt https://lab/api/debug/ping    # "Pong"
find Daemons -name '*.php' | xargs -n1 php -l
test -d /var/spool/voipdomain/file-transfer && echo OK
```

---

## 4. Taxonomia e classes de risco dos hooks

### Categorias

| Categoria | Padrão de nome | Onde corre |
|-----------|----------------|------------|
| API principal | `<modulo>_<acao>` | Admin PHP |
| Sub-hook API | `_{start,validate,sanitize,pre,post,notify,finish}` | Admin PHP |
| Página WebUI | `<modulo>_<pagina>_page` | Admin WebUI |
| Filter | ex. `e164_*` | Admin PHP |
| Evento client | `<entidade>_{add,change,remove}` | Client daemon |
| Wipe | `*_wipe` | Client daemon |
| Router | `channels_*`, `queues_*`, `router_*` | Router daemon |
| Infraestrutura | global | variável |

### Classes de risco

| Classe | Descrição | Como testar |
|--------|-----------|-------------|
| **R0** | Só leitura | Seguro anytime |
| **R1** | Transformação (filters, validate) | Payload fixture; sem escrita BD |
| **R2** | Mutação (add/change/remove) | Só tenant lab |
| **R3** | Destrutivo (wipe, rebuild) | Servidor descartável ou snapshot |
| **R4** | Efeito externo (notify, systemd) | Capture ou nó único |

---

## 5. Ferramentas nativas para testar hooks

### API autenticada (recomendado)

Permissões de Administrator + debug.

| Endpoint | Função |
|----------|--------|
| `GET /api/debug/ping` | Smoke test |
| `GET /api/debug/api/map` | Árvore API + hook → ficheiro/linha |
| `POST /api/debug/callhook/:Hook` | Executar hook com JSON |
| `GET /api/debug/explain/:Number` | Cadeia `e164_identify` |

Exemplos:

```bash
# Hook read-only
curl -X POST 'https://lab/api/debug/callhook/debug_ping' \
  -H 'Authorization: Bearer TOKEN' -H 'Content-Type: application/json' -d '{}'

# Validate (esperar 422)
curl -X POST 'https://lab/api/debug/callhook/ranges_add_validate' \
  -H 'Authorization: Bearer TOKEN' -H 'Content-Type: application/json' \
  -d '{"Number":""}'

# Infraestrutura
curl -X POST 'https://lab/api/debug/callhook/router_reload_cache' \
  -H 'Authorization: Bearer TOKEN' -H 'Content-Type: application/json' \
  -d '{"ServerID":"1"}'
```

O corpo JSON torna-se `$parameters` do hook. `:Hook` fica em `$parameters["Hook"]`.

### WebUI debug (só desenvolvimento)

Módulo `debug` — **nunca em produção**. Paths **sem autenticação**:

- `/debug/dump_hooks` — registo completo de hooks
- `/debug/exec_hook/:hook` — executar hook
- `/debug/exec_filter/:filter` — executar filter

### Inventário local

```bash
rg 'framework_add_hook\s*\(\s*"([^"]+)"' -o -r '$1' --glob '*.php' \
  Administrative\ Interface/modules Daemons Plugins | sort -u > hooks.txt
```

---

## 6. Procedimento de teste de hook (passo a passo)

1. **Descobrir** — `rg`, `/api/debug/api/map`, ler handler e `$parameters`
2. **Classificar** — R0–R4
3. **Fixtures** — tenant, árvore mínima de objetos
4. **Executar** — `debug_call_hook`, API CRUD, ou Gearman (router)
5. **Assertar** — retorno, BD, filesystem, logs, Asterisk CLI
6. **Teardown** — `*_remove`, snapshot restore, `notify_capture_discard()`

Para sub-hooks: prefira a **API principal** (`POST /api/extensions`) e verifique
efeitos indiretos nos logs/BD.

---

## 7. Hooks nos daemons

### Router (JSON Gearman directo)

Cada hook do router é função Gearman. Payload JSON → `framework_call()`.

```bash
echo '{}' | gearman -f router_reload_cache -h 127.0.0.1 -p 4730
systemctl reload voipdomain-router.service
grep 'cache reloaded' /var/log/voipdomain/router.log
```

### Client (fila encriptada)

Hooks **não** são funções Gearman públicas. Job encriptado em
`vd_client_{serverid}_task`.

**Teste preferido:** acção API → verificar `client.log` / `eventsfile` →
verificar ficheiros Asterisk.

Use `notify_capture($serverid)` no admin para inspecionar eventos VDFT antes
de enviar ao nó.

### Monitor (AMI)

Hooks disparados por eventos Asterisk. Teste: reiniciar monitor (bootup),
`core reload` (delta sync), alterar membros de fila.

---

## 8. Matrizes de teste por domínio

### Bootstrap

Instalação, login, `debug_api_map`, `debug_ping`.

### Árvore do tenant (CRUD + notify)

Ordem: Server → Gateway → Profile → Cost center → Group → Range → Extension →
Queue → IVR → Audio.

Para cada: Create → Read → Update → Delete; validar códigos HTTP e hooks client
no log.

### Telefonia E2E

Chamada interna, saída via gateway, fila, IVR, voicemail, gravação.

```bash
asterisk -rx "core show channels"
asterisk -rx "pjsip show endpoints"
```

### Infraestrutura recente

VDFT, `file_transfer_abort`, delta monitor, `router_reload_cache`, migração
astDB, PJSIP inter-servidor, wipe Locale Cisco — ver
[RECURSOS-IMPLEMENTADOS.md](RECURSOS-IMPLEMENTADOS.md).

### Filters (numbering)

Não teste os ~685 filters um a um. Amostragem por região + `/api/debug/explain/`.

### Plugins

Install, toggle enable/disable, uninstall, sync remoto, `plugin_db_apply`.

---

## 9. Hooks wipe (R3)

```bash
rg 'cleanup_register|function \w+_wipe' Daemons/client-modules -g '*.php'
```

Testar só com snapshot: rebuild com `Clean: true`, verificar TFTP/conf vazios,
restaurar.

---

## 10. Checklist de sign-off (release candidate)

### Smoke

- [ ] `php -l` em todos os PHP
- [ ] `GET /api/debug/ping` → Pong
- [ ] Daemons + Gearman activos
- [ ] Spool file-transfer existe

### Hooks (amostra)

- [ ] 10 hooks R0 via `debug_call_hook`
- [ ] 5 validates com 422 esperado
- [ ] CRUD árvore tenant completa
- [ ] Wipe em nó descartável
- [ ] `router_reload_cache` (API + SIGHUP)
- [ ] VDFT áudio + firmware (1 vendor)

### Telefonia

- [ ] Chamada interna e saída
- [ ] Provisioning de 1 equipamento

### Segurança

- [ ] Módulo debug desactivado em produção
- [ ] `debug_call_hook` não concedido a admins de tenant

---

## 11. Triagem de falhas

| Sintoma | Acção |
|---------|-------|
| 404 em callhook | Hook não registado — `/debug/dump_hooks` |
| Client ignora evento | Erros sign/HMAC em client.log |
| Estado monitor stale | `router_reload_cache` |
| Ficheiro parcial | Spool VDFT; falta `file_transfer_commit` |

---

## 12. Automação

Toolkit em `Documentations/testing/` — ver [README.md](testing/README.md).

| Script | Função |
|--------|--------|
| `../scripts/hook-inventory.sh` | CSV com classificação R0–R4 |
| `run-all.sh` | Smoke completo |
| `hook-call.sh` | Chamar um hook isolado |
| `postman/*.json` | Coleção Postman |

Progressão CI: `smoke-static.sh` → `smoke-api.sh` (lab) → inventário CSV → Gearman → telefonia.

---

*Inventário: ~772 hooks, ~685 filters — re-executar secção 5 após merges grandes.*
