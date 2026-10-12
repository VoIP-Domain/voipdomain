# VoIP Domain — Manual do Desenvolvedor

Referência completa para manter o VoIP Domain, escrever módulos e criar plugins.

**English:** [../en/DEVELOPER-MANUAL.md](../en/DEVELOPER-MANUAL.md)  
**Visão geral da arquitetura:** [ARQUITETURA.md](ARQUITETURA.md)  
**Instalação:** [INSTALL.md](INSTALL.md)

---

## Índice

1. [Introdução](#1-introdução)
2. [Estrutura do repositório](#2-estrutura-do-repositório)
3. [Ambientes de execução](#3-ambientes-de-execução)
4. [Bootstrap e carregamento de módulos](#4-bootstrap-e-carregamento-de-módulos)
5. [Estado global: `$_in`](#5-estado-global-_in)
6. [Hooks](#6-hooks)
7. [Filters](#7-filters)
8. [Convenções de nomenclatura de hooks e filters](#8-convenções-de-nomenclatura-de-hooks-e-filters)
9. [API REST](#9-api-rest)
10. [Interface web](#10-interface-web)
11. [Autenticação e permissões](#11-autenticação-e-permissões)
12. [Modelo multi-tenant](#12-modelo-multi-tenant)
13. [Gearman e `notify_server`](#13-gearman-e-notify_server)
14. [Daemons](#14-daemons)
15. [Fluxo de eventos do monitor e do router](#15-fluxo-de-eventos-do-monitor-e-do-router)
16. [Daemon client e Asterisk](#16-daemon-client-e-asterisk)
17. [Transferência de arquivos VDFT](#17-transferência-de-arquivos-vdft)
18. [Sistema de plugins](#18-sistema-de-plugins)
19. [Extensões](#19-extensões)
20. [Provisionamento de equipamentos](#20-provisionamento-de-equipamentos)
21. [Motor de workflow IVR](#21-motor-de-workflow-ivr)
22. [Banco de dados: `vd` vs `vdclient`](#22-banco-de-dados-vd-vs-vdclient)
23. [Arquivos de configuração](#23-arquivos-de-configuração)
24. [Internacionalização](#24-internacionalização)
25. [Depuração e ferramentas](#25-depuração-e-ferramentas)
26. [Criando um plugin passo a passo](#26-criando-um-plugin-passo-a-passo)
27. [Convenções de codificação](#27-convenções-de-codificação)
28. [Apêndice: fases do ciclo de vida dos hooks](#28-apêndice-fases-do-ciclo-de-vida-dos-hooks)

---

## 1. Introdução

O VoIP Domain é uma **plataforma de gerenciamento VoIP multi-tenant** construída em torno do Asterisk. Seu princípio central de design é **extensibilidade sem fork do código core**: quase toda funcionalidade é uma função PHP registrada em um hook de string, similar às funções plugáveis do WordPress.

Você trabalhará com:

- **Hooks** — cadeias ordenadas de funções para ações e handlers de API.
- **Filters** — uma função por módulo por nome de filter; transformam dados compartilhados (menus, tipos, sessão).
- **Gearman** — fila assíncrona de jobs entre os daemons admin, router e client.
- **Arquivos de config INI** em `/etc/voipdomain/`.

Versão mínima do PHP para a lógica da aplicação: **5.5** (transações mysqli, sintaxe moderna). O RHEL 7 traz PHP 5.4 — use Remi ou um SO mais recente em produção.

---

## 2. Estrutura do repositório

```
VoIP Domain/
├── Administrative Interface/
│   ├── index.php              # Shell SPA, roteamento de páginas
│   ├── api.php                # Front controller da API REST
│   ├── calls.php              # Entrada de reprodução de gravações
│   ├── includes/
│   │   ├── config.inc.php     # Bootstrap, init $_in, conexão DB
│   │   ├── plugins.inc.php    # framework_add_hook, framework_call
│   │   ├── plugin-install.inc.php
│   │   ├── file-transfer.inc.php
│   │   ├── systemd.inc.php    # Helpers de reload de units systemd
│   │   ├── timezonepicker_ui.inc.php  # Mapa/select de timezone dinâmico
│   │   └── functions.inc.php  # notify_server, helpers
│   ├── modules/<name>/        # Módulos core (veja abaixo)
│   └── plugins/<name>/        # Plugins implantados (runtime)
├── Daemons/
│   ├── client.php             # Worker do nó Asterisk
│   ├── router.php             # Roteador central de eventos
│   ├── monitor.php            # Listener AMI
│   ├── fastagi.php            # Servidor FastAGI
│   ├── includes/              # Bibliotecas compartilhadas dos daemons
│   ├── client-modules/        # Hooks client por feature + vendor
│   ├── router-modules/
│   ├── monitor-modules/
│   └── fastagi-modules/
├── Plugins/                   # Templates fonte para plugins customizados
└── Documentations/
```

### Arquivos padrão de módulo (admin)

| Arquivo | Propósito |
|---------|-----------|
| `api.php` | Hooks REST + `framework_add_api_call` |
| `webui.php` | Hooks de página, fragmentos HTML |
| `filter.php` | Registros `framework_add_filter` |
| `language.php` | Strings de tradução `i18n_add()` |
| `install.php` | Schema DB no hook `install_db` |
| `update.php` | Migrações de schema no `upgrade_db` |
| `functions.php` | Helpers compartilhados |
| `functions-api.php` / `functions-webui.php` | Helpers separados |
| `config.php` | Config do módulo injetada em `$_in` |

Módulos core incluem: `interface`, `authentication`, `tenants`, `users`, `tokens`, `servers`, `extensions`, `extensions-phones`, `extensions-queues`, `extensions-ivr`, `extensions-hunts`, `ivr`, `queues`, `agents`, `gateways`, `ranges`, `profiles`, `groups`, `blocks`, `exceptions`, `equipments`, `equipments-*` (vendors), `audios`, `notifications`, `config`, `debug`, `countrydb`, `currencies`, `costcenters`.

---

## 3. Ambientes de execução

| Ambiente | Ponto de entrada | Arquivo de config | Banco de dados |
|----------|------------------|-------------------|----------------|
| Web UI | `index.php` | `webinterface.conf` | `vd` |
| API REST | `api.php` | `webinterface.conf` | `vd` |
| Daemon client | `client.php -d` | `client.conf` | `vdclient` |
| Daemon router | `router.php -d` | `router.conf` | `vd` |
| Daemon monitor | `monitor.php -d` | `monitor.conf` | `vdclient` |
| FastAGI | `fastagi.php -d` | `fastagi.conf` | Arquivos locais |

Cada ambiente carrega apenas os arquivos PHP relevantes ao seu papel (módulos de API não são carregados no caminho de cache de páginas do `index.php`, exceto via mapa de hooks em cache).

---

## 4. Bootstrap e carregamento de módulos

### 4.1 Web UI (`index.php`)

1. `config.inc.php` — parse de `/etc/voipdomain/webinterface.conf` ou define modo install.
2. Carrega `modules/interface/{language,filter,webui}.php`.
3. Se modo install → `install_page_generate` e exit.
4. **Descoberta de módulos:**
   - **Produção:** lê `modules/cache.json` (hooks, filters, paths, i18n); carrega `functions.php`, `functions-webui.php`, `config.php` por módulo.
   - **Modo debug (`general.debug = true`):** reescaneia todos `modules/*` e `plugins/*`; reconstrói o cache.
5. Autentica sessão; roteia para `framework_page_generate` ou hook de login.

Plugins em `plugins/disabled_<name>/` são **ignorados**.

### 4.2 API REST (`api.php`)

1. `config.inc.php` + shutdown handler (flush de `notifycapture` via Gearman).
2. Carrega `modules/interface/api.php` + todo `modules/*/api.php` (ou `install.php` em modo install).
3. Carrega `plugins/*/api.php` (ignorado para chamadas internas `127.0.0.1`).
4. Autentica (veja §11).
5. Corresponde `$_paths` por método HTTP + URI → `framework_call(main_hook, $parameters)`.
6. JSON-encode do valor de retorno.

### 4.3 Daemon client (`Daemons/client.php`)

1. Carrega `includes/{functions,plugins,file-transfer,systemd,plugin-install}.inc.php`.
2. Parse de `client.conf`; conecta MySQL `vdclient`; carrega chaves crypto.
3. Glob `client-modules/*/config.php` depois `client-modules/*/client.php`.
4. Reduz privilégios para `[daemon] uid/gid` (tipicamente `asterisk`).
5. Registra worker Gearman `vd_client_{serverid}_task` (+ task de backup se `backup = true`).
6. `task_replay` na inicialização; entra no loop `work()`.

### 4.4 Daemon router (`Daemons/router.php`)

1. Parse de `router.conf`; conecta `vd`.
2. Carrega todos `router-modules/*/router.php`.
3. Registra **todo nome de hook** como função Gearman → `gearman_event` → `framework_call`.
4. Servidor HTTP em `[daemon] port` (padrão 8080) para callbacks internos.
5. Carrega regras de notificação do DB.

### 4.5 Daemon monitor (`Daemons/monitor.php`)

1. Parse de `monitor.conf`; conecta `vdclient`.
2. Carrega `monitor-modules/*/monitor.php` (registra hooks de eventos AMI).
3. Conecta AMI; a cada evento → `framework_call($event["Event"], $event)`.
4. Módulos monitor chamam `$gm->doBackground("<router_hook>", $json)`.

---

## 5. Estado global: `$_in`

`$_in` é a bolsa de configuração e runtime do framework, inicializada no bootstrap de cada ambiente.

### Admin (`config.inc.php`)

| Chave | Descrição |
|-------|-----------|
| `mode` | `install` ou `normal` |
| `general` | INI `[general]` + overrides de runtime |
| `mysql` | Parâmetros de conexão + `id` (recurso mysqli) |
| `api` | `baseurl`, `baseuri` |
| `gearman` | `servers`, `socket` lazy (GearmanClient) |
| `session` | Estado de auth (veja §11) |
| `page` | Título da página atual, assets, strings i18n para JS |
| `notifycapture` | Chamadas `notify_server` em lote (flush no shutdown da API) |
| `module` | Nome do módulo atual durante execução de hook |

### Daemon client

| Chave | Descrição |
|-------|-----------|
| `daemon` | `serverid`, `backup`, `uid`, `gid`, URLs |
| `keys` | Conteúdo PEM carregado: `privateKey`, `publicKey`, `masterPublicKey` |
| `processed` | Mapa de deduplicação de ID de comando |
| `cleanup` | Handlers de wipe registrados (ordem de dependência) |
| `equipments` | Registro vendor/model dos módulos de config |

### Daemon router

| Chave | Descrição |
|-------|-----------|
| `Channels`, `Queues`, `Peers` | Estado ao vivo indexado por ID de servidor |
| `Notifications` | Regras de notificação ativas |

---

## 6. Hooks

### 6.1 Registro

```php
framework_add_hook ( "ranges_add", "ranges_add", IN_HOOK_NULL, $documentation);
```

| Constante | Efeito |
|-----------|--------|
| `IN_HOOK_NULL` (0) | Anexa handler ao final da cadeia |
| `IN_HOOK_INSERT_FIRST` (1) | Prepend do handler |
| `IN_HOOK_REMOVE_OTHERS` (2) | Limpa handlers existentes primeiro |
| `IN_HOOK_REPLACE` (4) | Usado com `framework_add_hook_at` |
| `IN_HOOK_INSERT_BEFORE` / `IN_HOOK_INSERT_AFTER` | Inserção posicional |

4º argumento opcional: array de documentação OpenAPI (mesclado na exportação Swagger).

### 6.2 Invocação

```php
if ( framework_has_hook ( "ranges_add_pre"))
{
  $parameters = framework_call ( "ranges_add_pre", $parameters);
}
$result = framework_call ( "ranges_add", array (), false, $parameters);
```

Assinatura para todo handler de hook:

```php
function my_handler ( $buffer, $parameters)
{
  // $buffer: retorno acumulado dos handlers anteriores na cadeia
  // $parameters: contexto do caller (frequentemente o array de parâmetros da API)
  return $buffer; // ou novo valor substitui buffer para o próximo handler
}
```

`framework_call` retorna o buffer final após todos os handlers executarem. Detecção de loop impede chamadas reentrantes no mesmo hook.

### 6.3 Hooks dinâmicos

Alguns hooks são construídos em runtime:

```php
framework_call ( "ivr_operator_" . $operator["Operator"], $params);
framework_call ( "extensions_add_" . $parameters["Type"] . "_post", ...);
framework_call ( "equipment_model_" . $model . "_configure", ...);
```

Sempre proteja com `framework_has_hook()` quando o alvo pode não existir.

### 6.4 Formato de retorno de hook de daemon

Handlers de evento client invocados de `task_manager` devem retornar:

```php
return array (
  "code"    => 200,           // Status no estilo HTTP
  "message" => "OK",
  "content" => $optionalData,
);
```

Códigos não-2xx são logados; `task_finished` ainda atualiza `Commands` com base no código.

---

## 7. Filters

Filters diferem de hooks: **no máximo um handler por módulo** por nome de filter.

```php
framework_add_filter ( "page_menu_registers", "myplugin_menu");
```

```php
$menu = filters_call ( "page_menu_registers", $parameters, $existingMenu);
```

Assinatura dos handlers de filter:

```php
function myplugin_menu ( $buffer, $parameters)
{
  $buffer[] = array ( "title" => __ ( "My plugin"), "url" => "/myplugin");
  return $buffer;
}
```

Filters comuns:

| Filter | Propósito |
|--------|-----------|
| `objects_types` | Registra tipos de extensão/objeto |
| `page_menu_registers` / `page_menu_configurations` / `page_menu_resources` | Seções do menu admin |
| `session_extend` | Adiciona dados à sessão do usuário após login |
| `authentication_plugins` | Lista de provedores OAuth |
| `extensions_add_subpages` | Abas extras nos formulários de extensão |
| `e164_identify` / `e164_identify_country_XXX` | Normalização de número |

Slots de filter opcionais (podem ter zero registrantes): `page_menu_others`, `page_menu_reports_*`, `authentication_failure`.

---

## 8. Convenções de nomenclatura de hooks e filters

### 8.1 Hooks de API CRUD

Padrão: `{entity}_{action}` como **hook principal**, com fases opcionais:

```
{entity}_{action}_start
{entity}_{action}_validate
{entity}_{action}_sanitize
{entity}_{action}_pre
{entity}_{action}_post
{entity}_{action}_notify
{entity}_{action}_finish
```

| Ação | Significado |
|------|-------------|
| `search` | Listar/filtrar |
| `view` | Ler único |
| `add` | Criar |
| `edit` | Atualizar |
| `remove` | Excluir |
| `clone` | Duplicar |

Exemplos: `agents_add`, `queues_edit_notify`, `servers_rebuild_config`.

**Prefixo da entidade** corresponde ao nome do módulo: `extensions`, `ranges`, `gateways`, `extension_phone`, `extension_queue`.

### 8.2 Hooks de página (Web UI)

Padrão: `{entity}_{action}_page` ou `{feature}_page`.

Exemplos: `extensions_search_page`, `login_page_generate`, `install_page_generate`, `about_page`.

### 8.3 Hooks de evento daemon / Gearman (client)

Padrão: `{domain}_{action}` — **deve corresponder** exatamente ao segundo argumento de `notify_server`.

Exemplos:

```
server_rebuild_config
extension_phone_add
extension_phone_change
extension_phone_remove
queue_add
queue_join_member
equipment_configure
firmware_remove
plugin_daemon_module_deploy
plugin_db_apply
daemon_reload
account_add
```

Lista completa: grep `notify_server(` em `Administrative Interface/` ou inspecione hooks client via `GET /debug/api/map` (requer `general.debug = true`).

### 8.4 Hooks do router (do monitor)

Eventos derivados de AMI encaminhados pelos módulos monitor:

```
channels_bootup, channels_create, channels_state, channels_destroy, channels_unload
queues_bootup, queues_member_add, queues_caller_add, queues_caller_abandon, ...
peers_bootup, peer_status, peers_unload
```

Registrados em `router-modules/`; chamados via Gearman de `monitor-modules/`.

### 8.5 Operadores IVR (client)

Padrão: `ivr_operator_{operator}` onde `{operator}` é minúsculo do JSON de workflow (`start`, `menu`, `dial`, …).

Filters de validação admin: `ivr_operator_filter_{operator}`.

### 8.6 Equipamentos (client)

Por model (minúsculo, sem espaços):

```
equipment_model_{model}_configure
equipment_model_{model}_firmware_add
equipment_model_{model}_firmware_remove
ap_type_{model}
account_type_{model}
```

Config de vendor em `client-modules/equipments-{vendor}/config.php` registra models em `$_in["equipments"]`.

### 8.7 Ciclo de vida do plugin

```
plugin_{name}_install
plugin_{name}_upgrade
plugin_{name}_remove
install_db / upgrade_db / uninstall_db   (hooks globais de DB)
```

### 8.8 Hooks de infraestrutura

| Hook | Papel |
|------|-------|
| `router_reload_cache` | Invalida cache em memória do router |
| `dialplan_generate` | Regenera snippet de dialplan |
| `file_transfer_begin/chunk/commit/abort` | Protocolo VDFT |
| `ping` | Health check |
| `debug_api_map` | Exporta mapa de hooks/rotas |

---

## 9. API REST

### 9.1 Declarando um endpoint

```php
framework_add_hook ( "widgets_view", "widgets_view", IN_HOOK_NULL, $doc);
framework_add_api_call (
  "/widgets/:ID",
  "Read",
  "widgets_view",
  array (
    "permissions" => array ( "User", "widgets_view"),
    "title"       => __ ( "View widget"),
    "parameters"  => array ( ...),
    "response"    => array ( 200 => array ( "schema" => ...)),
  )
);
framework_add_permission ( "widgets_view", __ ( "View widgets"));
```

Verbos HTTP para `framework_add_api_call`: `Create`, `Read`, `Update`, `Delete`, `Custom`.

URL real: `{baseuri}/widgets/:ID` (padrão `/api/widgets/:ID`).

### 9.2 Versionamento

- Header: `X-API-VERSION: 2`
- Prefixo de path: `/api/v2/widgets`

### 9.3 Parâmetros

Params de rota (`:ID`) e body/query são mesclados em `$parameters` passados aos hooks. Validação em `{hook}_validate` retorna erros de campo → **422**.

### 9.4 Permissões

`framework_add_permission ( "perm_name", "Human label")` — referenciado no array `permissions` do endpoint como `array ( "User", "perm_name")`.

Reservados: `Internal`, `Install`, `Token`, `AuthToken`, `User`, `Administrator`, `Super-Administrator`.

### 9.5 OpenAPI

Arrays de documentação nos hooks alimentam `/api/docs` (Swagger UI). Use `GET /debug/api/map` para mapa legível por máquina.

### 9.6 Chamadas internas de API

De localhost (`127.0.0.1`), a autenticação torna-se `Internal` — usado para callbacks servidor-a-servidor.

---

## 10. Interface web

Páginas são fragmentos HTML montados por cadeias de hooks terminando em `framework_page_generate`.

1. Router resolve path → hook de página (ex.: `extensions_search_page`).
2. Módulos registram com `IN_HOOK_INSERT_FIRST` ou ordem de append padrão.
3. Layout AdminLTE 3 de `modules/interface/webui.php`.
4. Assets via `addjs` / `addcss` no `config.php` do módulo ou hooks de página.

Adicionando uma página:

```php
// modules/myfeature/webui.php
framework_add_hook ( "myfeature_list_page", "myfeature_list_page");
function myfeature_list_page ( $buffer, $parameters)
{
  return $buffer . "<section class=\"content\">...</section>";
}
```

Registre rota em `filter.php` ou `webui.php` via path hooks (`framework_add_path`).

---

## 11. Autenticação e permissões

### 11.1 Métodos (`$_in["session"]["Method"]`)

| Método | Gatilho |
|--------|---------|
| `Install` | Ainda sem `webinterface.conf` |
| `Internal` | Requisição de `127.0.0.1` |
| `Token` | Header `X-{cookie}-TOKEN` ou `?token=` |
| `Server` | Headers `X-{cookie}-SID` + `X-{cookie}-SPWD` |
| `AuthToken` | Cookie `{cookie}_authtoken` (plugins OAuth) |
| `Session` | Cookie `{cookie}` (usuário tenant) ou `{cookie}_adm` (super-admin) |

### 11.2 Campos da sessão

```php
$_in["session"]["Authenticated"]  // bool
$_in["session"]["Tenant"]         // int, 0 = super-admin
$_in["session"]["Permissions"]    // lista plana de strings de permissão
$_in["session"]["Data"]           // específico do método (User ID, Token ID, …)
```

### 11.3 Segurança de senha

`[security] iterations` em webinterface.conf — rounds PBKDF2 para hashes de senha. TOTP via `[security] totprange`.

---

## 12. Modelo multi-tenant

- **Tabela `Tenants`** — sufixo de domínio, branding, limites.
- **`masterhostname`** — host que serve a UI super-admin (`multitenant_login_page_generate`, path `/master`).
- **`authhostname`** — UI de auth dedicada opcional (`authentication_login_page_generate`, path `/auth`).
- Todos os outros hosts → login tenant (`login_page_generate`) resolvido por `get_tenant()` do HTTP Host.

Consultas de API acrescentam `Tenant = $_in["session"]["Tenant"]` exceto contextos super-admin.

---

## 13. Gearman e `notify_server`

### 13.1 Nomes de task

| Função Gearman | Direção |
|----------------|---------|
| `vd_client_{serverId}_task` | Admin/router → client |
| `vd_backup_server_{serverId}_task` | Para nó standby (`ServerBackup`) |
| `server_task_reply` | Client → router |
| `router_reload_cache` | Admin → router |
| Nomes de hook do router | Monitor → router |

### 13.2 Algoritmo `notify_server`

Localização: `Administrative Interface/includes/functions.inc.php`.

1. Resolve servidores alvo (`0` = todos, negativo = todos exceto).
2. `INSERT INTO Commands (Server, Event, Data)`.
3. Ignora se servidor tem `PublicKey` vazio.
4. Monta JSON `{event, id, data, group}`.
5. Criptografa payload (AES-256-CBC + HMAC; RSA-wrap da chave com chave pública do servidor; assina com chave privada master).
6. `GearmanClient::doBackground(gearman_client_task_name($id), ...)`.
7. Repete para servidores de backup.

Chave privada master: `/etc/voipdomain/master-certificate.key` ou gerada na instalação.

### 13.3 `task_manager` do client

1. Descriptografa + verifica assinatura.
2. Dedupe por `id` de comando em `$_in["processed"]`.
3. Se evento `group` → expande sub-eventos.
4. Senão `framework_call($event, $data)`.
5. `reply_event` → Gearman `server_task_reply`.

### 13.4 Replay de comandos

Na inicialização do client, hook router `task_replay` reenvia linhas `Commands` pendentes para aquele servidor.

---

## 14. Daemons

### 14.1 Unidades systemd

| Unidade | Binário |
|---------|---------|
| `voipdomain-client.service` | `/var/lib/voipdomain/daemons/client.php -d` |
| `voipdomain-router.service` | `router.php -d` |
| `voipdomain-monitor.service` | `monitor.php -d` |
| `voipdomain-fastagi.service` | `fastagi.php -d` |

Reload sem restart completo: escreva em `/run/voipdomain/reload/{client,router,monitor,fastagi}` (path units) ou `systemctl reload voipdomain-client`.

### 14.2 Módulos daemon de plugin

Plugins podem incluir:

```
Plugins/myplugin/client-modules/myfeature/client.php
Plugins/myplugin/router-modules/...
```

Implantados em `Daemons/*-modules/` localmente e sincronizados aos nós via VDFT (veja §18).

---

## 15. Fluxo de eventos do monitor e do router

```
Evento Asterisk AMI
  → monitor.php: framework_call("Newchannel", $event)
  → monitor-modules/channels/monitor.php: atualiza $_in, gm->doBackground("channels_create", ...)
  → router.php: gearman_event → framework_call("channels_create", ...)
  → router-modules/channels/router.php: atualiza $_in["Channels"][$serverId]
  → opcional: match de notificação → HTTP push para pushpub
```

Exemplo de abandon de fila: AMI `QueueCallerAbandon` → monitor `queues_caller_abandon` → Gearman → router `queues_caller_abandon` atualiza estado de fila do cluster.

Este caminho é **independente** dos hooks de config do client — apenas estado ao vivo.

---

## 16. Daemon client e Asterisk

### 16.1 Saída de config

Hooks client escrevem arquivos sob `[general] confdir` (padrão `/etc/asterisk/voipdomain/configs/`). O Asterisk os carrega via includes bootstrap:

```
#include voipdomain/bootstrap/pjsip.conf
#include voipdomain/bootstrap/dialplan.conf
...
```

Use `/etc/asterisk/voipdomain/configure-asterisk.sh <SERVER_ID>` para acrescentar includes.

### 16.2 Rebuild do servidor

`notify_server($id, "server_rebuild_config", ...)` dispara regeneração completa de config no nó.

### 16.3 Contextos e macros

De `client.conf` `[contexts]` e `[macros]` — dialplan referencia `VoIPDomain-extensions`, `VoIPDomain-LCR`, etc.

---

## 17. Transferência de arquivos VDFT

**VoIP Domain File Transfer** — sincronização binária em chunks pela cadeia de notify Gearman.

Eventos: `file_transfer_begin`, `file_transfer_chunk`, `file_transfer_commit`, `file_transfer_abort`.

| Tamanho | Comportamento |
|---------|---------------|
| ≤ 32 KiB | Base64 inline em único notify |
| Maior | Chunks (256 KiB) em staging em `/var/spool/voipdomain/file-transfer/` |

Remetente: `file_transfer_send($serverId, $filepath, array ( "TargetEvent" => "equipment_configure", ...))`.

Receptor: `Daemons/client-modules/file-transfer/client.php` remonta e despacha `TargetEvent` com path local do arquivo.

Usado para: upload de áudio aos nós, firmware, deploy de tarball de plugin, manifest JSON de DB de plugin.

---

## 18. Sistema de plugins

### 18.1 Localizações de plugin

| Localização | Papel |
|-------------|-------|
| `Plugins/<name>/` | Fonte no git (referência) |
| `Administrative Interface/plugins/<name>/` | Runtime (webroot) |
| `plugins/disabled_<name>/` | Desabilitado (ignorado pelo loader) |

### 18.2 Arquivos obrigatórios

Mínimo: `api.php` e/ou `webui.php`, `language.php`.  
Recomendado: `install.php`, `filter.php`.

Árvores daemon opcionais: `client-modules/`, `router-modules/`, `monitor-modules/`, `fastagi-modules/`.

### 18.3 Hooks de banco de dados

```php
// install.php
framework_add_hook ( "install_db", "myplugin_install_db");
function myplugin_install_db ( $buffer, $parameters)
{
  return plugin_process_db ( $buffer, $parameters); // usa manifest $_in["install"]
}
```

Helpers de manifest de install: `install_add_db_table`, `install_add_db_trigger`, `install_add_db_data`.

### 18.4 Habilitar / desabilitar

API: `PATCH /config/plugin/:Plugin/toggle` (hook `plugins_toggle`).

- **Habilitar:** renomeia `disabled_*` → ativo; `plugin_apply_installation()` (DB + deploy local de daemon + sync remoto + reload systemd).
- **Desabilitar:** `plugin_apply_removal()`; renomeia para `disabled_*`.

Implementação: `Administrative Interface/includes/plugin-install.inc.php`.

### 18.5 Sync remoto para nós Asterisk

1. Tar do diretório de módulo daemon.
2. `file_transfer_send(..., TargetEvent => plugin_daemon_module_deploy)`.
3. Client extrai sob `Daemons/*-modules/`.
4. Manifest JSON de DB → `plugin_db_apply` em cada `vdclient` do nó.
5. `notify_server(0, "daemon_reload", ...)` ou `systemd_request_daemon_reload()`.

### 18.6 Hooks de ciclo de vida

```php
framework_add_hook ( "plugin_myplugin_install", "myplugin_on_install");
```

Chamado de `plugin_call_lifecycle()` durante toggle/install.

Veja `Plugins/sampleitems/` para exemplo funcional.

---

## 19. Extensões

Módulo core `extensions` fornece CRUD compartilhado; comportamento específico por tipo nos submódulos:

| Submódulo | Tipo de filter | Eventos client |
|-----------|----------------|----------------|
| `extensions-phones` | `extension_phone` | `extension_phone_*` |
| `extensions-queues` | `extension_queue` | `extension_queue_*` |
| `extensions-ivr` | `extension_ivr` | `extension_ivr_*` |
| `extensions-hunts` | `extension_hunt` | `extension_hunt_*` |

Exemplo de hooks cross-module: `extensions_number_changed`, `extensions_remove_pre` (cleanup de hunt).

Registrar tipos:

```php
framework_add_filter ( "objects_types", "phones_objects_types");
function phones_objects_types ( $buffer, $parameters)
{
  $buffer["extension_phone"] = array ( "type" => "human", ...);
  return $buffer;
}
```

---

## 20. Provisionamento de equipamentos

### 20.1 Lado admin

- `equipments` — CRUD base, armazenamento de firmware.
- `equipments-{vendor}` — UI específica de vendor, filters de model, upload de firmware.

### 20.2 Lado client

`Daemons/client-modules/equipments-{vendor}/`:

- `config.php` — registra vendor/models em `$_in["equipments"]`.
- `client.php` — hooks por model (configure, firmware, templates AP).
- Arquivos de template com placeholders `{{{MAC}}}`, `{{{SERVER}}}`, etc.

Evento `equipment_configure` envia config gerada ao telefone via HTTP/TFTP/FTP conforme hook do vendor.

---

## 21. Motor de workflow IVR

### 21.1 Admin

- Editor visual de workflow (`modules/ivr/config.php` define operadores: time, menu, dial, …).
- Salvo como JSON no IVR + registros de extensão.
- Filters de validação: `ivr_operator_filter_{operator}`.

### 21.2 Dialplan client

`Daemons/client-modules/ivr/client.php`:

```php
create_ivr_workflow ( ...);
// foreach operator:
framework_call ( "ivr_operator_" . $operator["Operator"], array (
  "Properties" => ...,
  "Links"      => ...,
  "ID"         => $nodeId,
));
```

Operadores built-in em `operator-builtin.php` retornam linhas de dialplan Asterisk (`Answer()`, `Read()`, `GoTo()`, …).

Variáveis em expressões: `{date}`, `{time}`, `{callerid}`, `{var_NAME}` via `operator_apply_variables()`.

---

## 22. Banco de dados: `vd` vs `vdclient`

| Banco de dados | Hosts | Conteúdo |
|----------------|-------|----------|
| **`vd`** | Admin, router, fastagi | Tenants, usuários, servidores, commands, config CDR, catálogo de equipamentos, notificações |
| **`vdclient`** | Client, monitor | Cache de provisionamento local do nó, tabelas locais de plugin, espelhos de runtime |

**Regra:** admin escreve dados autoritativos em `vd`, empurra execução aos nós via Gearman. `install.php` de plugin roda em `vd`; `plugin_db_apply` replica manifest em cada `vdclient`.

Fila de trabalho pendente: tabela `Commands` em **`vd`**.

---

## 23. Arquivos de configuração

Todos em `/etc/voipdomain/`. Amostras em `Documentations/etc/voipdomain/`.

### webinterface.conf

| Seção | Chaves |
|-------|--------|
| `[general]` | `baseurl`, `masterhostname`, `authhostname`, `storagedir`, `spooldir`, `tempdir`, `debug`, … |
| `[mysql]` | `hostname`, `username`, `password`, `database` (= `vd`) |
| `[gearman]` | `servers` (host:port separados por vírgula) |
| `[security]` | `iterations`, `totprange` |

### client.conf

| Seção | Chaves |
|-------|--------|
| `[daemon]` | `serverid`, `backup`, `uid`, `gid`, `lpurl`, `apiurl` |
| `[mysql]` | database = `vdclient` |
| `[general]` | `confdir`, `privateKey`, `publicKey`, `masterPublicKey`, `transferdir` |

### router.conf / monitor.conf

Veja amostras; router usa `vd`, monitor usa `vdclient` + credenciais `[ami]`.

---

## 24. Internacionalização

```php
// language.php
i18n_add ( "My string");
echo __ ( "My string"); // resolvido em runtime de $_in["i18n"]
```

Locale de `[general] language` (ex.: `en_US`, `pt_BR`). Strings JS exportadas via `$_in["page"]["i18n"]`.

---

## 25. Depuração e ferramentas

| Ferramenta | Propósito |
|------------|-----------|
| `GET /debug/ping` | Health check |
| `GET /debug/api/map` | Todas as rotas + hooks + arquivos fonte |
| `GET /debug/callhook/{name}` | Executa hook com saída de debug |
| `general.debug = true` | Desabilita cache de módulo, erros verbosos |
| `Documentations/scripts/hook-inventory.sh` | Inventário CSV de hooks |

Debug client: execute `php client.php` sem `-d`; defina `[daemon] debug = true` e opcional `eventsfile`.

---

## 26. Criando um plugin passo a passo

1. Copie `Plugins/sampleitems/` para `Plugins/mycompany-billing/`.
2. Renomeie hooks (`sampleitems_*` → `mycompany_billing_*`).
3. Escreva `install.php` com tabelas/triggers.
4. Adicione endpoints `api.php` + permissões.
5. Adicione página `webui.php` + entrada de menu em `filter.php`.
6. (Opcional) Adicione `client-modules/` para lógica no lado do nó.
7. Deploy: symlink ou cópia para `Administrative Interface/plugins/mycompany-billing/`.
8. Na UI super-admin: Config → Plugins → habilitar (executa install + sync remoto).
9. Verifique: `/debug/api/map`, `hook-inventory.sh`, teste chamadas de API.

Para extensões apenas daemon sem UI admin, adicione arquivos diretamente em `Daemons/client-modules/mymodule/` (deploy core).

---

## 27. Convenções de codificação

- **Identificadores em inglês**; strings visíveis ao usuário via `__()`.
- **Nunca** faça `require` manual de arquivos de módulo — use descoberta.
- **SQL:** use mysqli com strings escapadas ou padrões preparados; escopo tenant em toda query.
- **Segurança:** valide em `_validate`, sanitize em `_sanitize`; nunca confie em input de extensão na geração de dialplan.
- **Hooks:** prefira fases opcionais a handlers monolíticos.
- **Plugins:** mantenha em `Plugins/` no git; evite editar `modules/` core para lógica específica do site.

---

## 28. Apêndice: fases do ciclo de vida dos hooks

| Fase | Responsabilidade típica |
|------|-------------------------|
| `_start` | Normaliza estrutura de input, define defaults |
| `_validate` | Retorna array de erros ou vazio |
| `_sanitize` | Remove tags, faz cast de tipos |
| `_pre` | Últimas verificações antes do SQL |
| hook principal | Leitura/escrita DB, lógica core |
| `_post` | Formata resposta, efeitos colaterais |
| `_notify` | Chamadas `notify_server()` |
| `_finish` | Logging, métricas, cleanup |

Nem todas as fases são implementadas para toda entidade — registre apenas o que precisa; callers sempre verificam `framework_has_hook()` primeiro.

---

*Versão do documento: 2026-06 — alinhado com VoIP Domain 0.3 RC1.*
