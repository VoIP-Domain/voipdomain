# VoIP Domain — Dialplan Asterisk

Estrutura do dialplan no servidor, geração dinâmica e extensão via plugins.

**Relacionado:** [MANUAL-DO-DESENVOLVEDOR.md](MANUAL-DO-DESENVOLVEDOR.md) · [PLANEJAMENTO-DE-IMPLANTACAO.md](PLANEJAMENTO-DE-IMPLANTACAO.md)

English: [../en/DIALPLAN.md](../en/DIALPLAN.md)

---

## 1. Duas camadas

| Camada | Local | Atualização |
|--------|-------|-------------|
| **Bootstrap** (estático) | `/etc/asterisk/voipdomain/bootstrap/` | RPM ou edição manual |
| **Gerado** (dinâmico) | `/etc/asterisk/voipdomain/configs/` | CRUD no admin → client daemon |

Dialplan de teclado nos telefones (auto-provisionamento) é separado — hooks `dialplan_generate` / `dialplan_country_*`.

---

## 2. Árvore de includes do bootstrap

```
dialplan.conf
├── dialplan-includes.conf
├── dialplan-services.conf
├── dialplan-routing.conf
├── dialplan-internal.conf
├── dialplan-tools-core.conf
├── dialplan-tools-calls.conf
├── dialplan-tools-cdr.conf
├── dialplan-handlers.conf
└── configs/dialplan-bootstrap-*.conf   ← plugins
```

Fragmentos gerados: `dialplan-profile-*`, `dialplan-extension-*`, `dialplan-db-*`, `dialplan-gateway-*`, `dialplan-ivr-*`, etc.

Nomes de contexto: `[contexts]` em `/etc/voipdomain/client.conf` (ex.: `public = VoIPDomain-public`).

---

## 3. Extensão via plugins

### Fragmento bootstrap

```php
write_dialplan_plugin ( "meuplugin", "[VoIPDomain-Tools]\n" .
  "exten => minha_func,1,NoOp(Plugin)\n same => n,Return()\n");
asterisk_exec ( "dialplan reload");
```

Hook **`dialplan_bootstrap_regenerate`** no rebuild completo do servidor.

### Pós-processamento

Hook **`dialplan_write_post`** — altera conteúdo antes de gravar ficheiros `dialplan-*`.

### Regras por país

Ver `profiles/country-*.php` e `extensions-phones/country-*.php`.

---

## 4. Operação

- Stubs `*-000000.conf` evitam globs vazios.
- `AGIADDR` em `dialplan-globals.conf` deve coincidir com o FastAGI planeado.

---

*Versão: 2026-06 — VoIP Domain 0.3 RC1.*
