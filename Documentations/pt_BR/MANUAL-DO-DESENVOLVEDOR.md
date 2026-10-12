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

Módulos core incluem: `interface`, `authentication`, `tenants`, `users`, `tokens`, `servers`, `extensions`, `extensions-phones`, `extensions-queues`, `extensions-ivr`, `extensions-hunts`, `extensions-conferences`, `ivr`, `queues`, `conferences`, `agents`, `gateways`, `ranges`, `profiles`, `groups`, `blocks`, `exceptions`, `equipments`, `equipments-*` (vendors), `audios`, `notifications`, `config`, `debug`, `countrydb`, `currencies`, `costcenters`.

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

Hooks são o mecanismo central de extensibilidade — cadeias ordenadas de funções registradas sob um nome string. Quando um hook dispara, cada função registrada é chamada em sequência, passando o valor de retorno acumulado (`$buffer`) de uma para a próxima.

### 6.1 Estado global (`$_plugins`)

O sistema de hooks usa um array global `$_plugins` com estes sub-arrays:

| Chave | Propósito |
|-------|-----------|
| `hooks` | `[nome_hook => [fn1, fn2, ...]]` — cadeias ordenadas de funções |
| `map` | `[nome_funcao => nome_modulo]` — rastreia qual módulo registrou cada função |
| `functions` | `[nome_funcao => array_documentacao]` — documentação OpenAPI por função |
| `components` | `[nome_componente => array_schema]` — schemas de componentes OAS |
| `componentmap` | `[nome_componente => nome_modulo]` — rastreia qual módulo registrou cada componente |
| `buffers` | `[nome_hook => buffer_atual]` — buffers em runtime durante execução do hook |
| `current` | `[nome_hook => [fn1, fn2, ...]]` — detecção de loop (funções em execução) |
| `deferred_hooks` | Fila para chamadas `framework_add_hook_at()` cuja função referência ainda não existe |

### 6.2 Registro

```php
framework_add_hook ( $hook, $function, $options = 0, $documentation = array () )
```

| Parâmetro | Descrição |
|-----------|-----------|
| `$hook` | Nome string do hook (ex.: `"ranges_add"`) |
| `$function` | Nome da função PHP a registrar |
| `$options` | Bitmask de constantes `IN_HOOK_*` |
| `$documentation` | Array de documentação OpenAPI (mesclado na exportação OAS) |

**Constantes de opção:**

| Constante | Valor | Efeito |
|-----------|-------|--------|
| `IN_HOOK_NULL` | 0 | Anexa handler ao final da cadeia (padrão) |
| `IN_HOOK_INSERT_FIRST` | 1 | Prepend do handler no início da cadeia |
| `IN_HOOK_REMOVE_OTHERS` | 2 | Limpa todos os handlers existentes antes de adicionar |
| `IN_HOOK_REPLACE` | 4 | Substitui uma função específica (usado com `framework_add_hook_at`) |
| `IN_HOOK_INSERT_BEFORE` | 8 | Insere antes de uma função específica (usado com `framework_add_hook_at`) |
| `IN_HOOK_INSERT_AFTER` | 16 | Insere após uma função específica (usado com `framework_add_hook_at`) |

**Notas de comportamento:**

- Registrar a mesma função duas vezes no mesmo hook gera um erro do framework e é silenciosamente ignorado.
- `$_plugins["map"][$function]` é definido como `$_in["module"]` — rastreia qual módulo é dono de cada função (usado para lazy loading e contexto de i18n).
- Arrays de documentação são mesclados via `array_merge_recursive_distinct_with_sequencial()` se a função já possui documentação.

### 6.3 Registro posicional (`framework_add_hook_at`)

```php
framework_add_hook_at ( $hook, $function, $reference, $options = IN_HOOK_INSERT_AFTER )
```

Insere `$function` relativo a uma função `$reference` existente na cadeia do hook.

| `$options` | Efeito |
|------------|--------|
| `IN_HOOK_INSERT_AFTER` | Insere `$function` imediatamente após `$reference` (padrão) |
| `IN_HOOK_INSERT_BEFORE` | Insere `$function` imediatamente antes de `$reference` |
| `IN_HOOK_REPLACE` | Substitui `$reference` por `$function` |

**Resolução adiada:** se o hook ou a função referência ainda não existir (porque o módulo não carregou), o registro é enfileirado em `$_plugins["deferred_hooks"]`. Após todos os módulos carregarem, `framework_resolve_deferred_hooks()` processa a fila em até 10 passagens até que todas as referências sejam resolvidas. Entradas não resolvíveis geram um erro do framework.

### 6.4 Invocação

```php
framework_call ( $hook, $parameters = array (), $output = false, $buffer = "" )
```

| Parâmetro | Descrição |
|-----------|-----------|
| `$hook` | Nome do hook a executar |
| `$parameters` | Array passado como 2º argumento para cada handler |
| `$output` | Se `true`, imprime (echo) o buffer final (usado para renderização de página) |
| `$buffer` | Valor inicial do buffer (passado como 1º argumento para o primeiro handler) |

**Fluxo de execução:**

1. Verifica se hook existe → erro do framework se não.
2. Verifica chamada reentrante no mesmo hook → erro do framework se detectado (prevenção de loop).
3. Inicializa buffer (usa parâmetro `$buffer` se não houver buffer existente).
4. Para cada função na cadeia:
   a. **Lazy load** — se a função não existe em memória, tenta `require_once` dos arquivos `filter.php` e `webui.php` do módulo.
   b. **Define contexto do módulo** — `$_in["module"]` é definido como o módulo proprietário da função (de `$_plugins["map"]`).
   c. **Chamada** — `call_user_func($function, $buffer, $parameters)`. O valor de retorno se torna o novo buffer.
5. Limpa entrada de detecção de loop. Reseta `$_in["module"]` para vazio.
6. Se `$output` é true, imprime o buffer.
7. Retorna o buffer final.

**Assinatura do handler:**

```php
function my_handler ( $buffer, $parameters)
{
  // $buffer: retorno acumulado dos handlers anteriores na cadeia
  // $parameters: contexto do caller (frequentemente o array de parâmetros da API)
  return $buffer; // ou novo valor substitui buffer para o próximo handler
}
```

**Importante:** sempre retorne `$buffer` (modificado ou não). Não retornar quebra a cadeia para os handlers subsequentes.

**Padrões comuns de invocação:**

```php
// Chamada simples — dispara hook, obtém resultado:
$result = framework_call ( "ranges_view", $parameters);

// Com buffer inicial (comum para hooks de API onde $parameters é o 4º arg):
$result = framework_call ( "ranges_add", array (), false, $parameters);

// Guard para hooks opcionais (dinâmicos ou que podem não existir):
if ( framework_has_hook ( "ranges_add_pre"))
{
  $parameters = framework_call ( "ranges_add_pre", $parameters);
}

// Renderização de página — imprime o HTML acumulado:
framework_call ( "extensions_search_page", $parameters, true);
```

### 6.5 Inspeção e remoção de hooks

```php
// Verificar se um hook existe:
framework_has_hook ( $hook )                    // retorna boolean

// Verificar se uma função específica está registrada no hook:
framework_has_hook ( $hook, $function )         // retorna array (function, hook chain)

// Remover uma única função de um hook:
framework_remove_hook_function ( $hook, $function )  // retorna boolean

// Remover um hook inteiro (todas as funções):
framework_remove_hook ( $hook )                 // retorna boolean

// Ler o buffer atual durante a execução:
framework_get_buffer ( $hook )                  // retorna mixed

// Limpar o buffer de um hook:
framework_clear_buffer ( $hook )                // void
```

### 6.6 Hooks dinâmicos

Alguns hooks são construídos em runtime a partir de dados:

```php
framework_call ( "ivr_operator_" . $operator["Operator"], $params);
framework_call ( "extensions_add_" . $parameters["Type"] . "_post", ...);
framework_call ( "equipment_model_" . $model . "_configure", ...);
```

Sempre proteja com `framework_has_hook()` quando o alvo pode não existir.

### 6.7 Formato de retorno de hook de daemon

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

Filters diferem de hooks: **no máximo um handler por módulo** por nome de filter. São indexados pelo nome do módulo, não ordenados sequencialmente.

### 7.1 Registro

```php
framework_add_filter ( $filter, $function )
```

Armazena `$_filters[$filter][$_in["module"]] = $function`. Retorna `false` se o módulo já tem um handler para este filter.

### 7.2 Invocação

```php
$result = filters_call ( $filter, $parameters = array (), $buffer = array () )
```

Retorna `false` se nenhum handler está registrado. Caso contrário:

1. Adiciona `$parameters["called_filter"] = $filter` aos parâmetros.
2. Salva `$_in["module"]`, depois itera cada handler registrado.
3. **Lazy load** do `filter.php` do módulo se a função não existir.
4. Define `$_in["module"]` como o módulo do handler antes de cada chamada.
5. Chama cada handler: `$buffer = call_user_func($function, $buffer, $parameters)`.
6. Restaura `$_in["module"]` para o valor original.
7. Retorna o `$buffer` final.

### 7.3 Assinatura do handler

```php
function myplugin_menu ( $buffer, $parameters)
{
  $buffer[] = array ( "title" => __ ( "My plugin"), "url" => "/myplugin");
  return $buffer;
}
```

Igual aos hooks: sempre retorne `$buffer`.

### 7.4 Filters comuns

| Filter | Propósito |
|--------|-----------|
| `objects_types` | Registra tipos de extensão/objeto |
| `page_menu_registers` / `page_menu_configurations` / `page_menu_resources` | Seções do menu admin |
| `session_extend` | Adiciona dados à sessão do usuário após login |
| `authentication_plugins` | Lista de provedores OAuth |
| `extensions_add_subpages` | Abas extras nos formulários de extensão |
| `e164_identify` / `e164_identify_country_XXX` | Normalização de número |
| `audio_inuse` | Verifica se um arquivo de áudio é referenciado por um módulo |

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
| `extensions-conferences` | `extension_conference` | `extension_conference_*` |

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

- Editor visual de workflow (`modules/ivr/config.php` define operadores).
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

### 21.3 Operadores built-in

#### Controle de fluxo

| Operador | Saídas | App Asterisk | Descrição |
|----------|--------|--------------|-----------|
| `start` | 1 | — | Ponto de entrada |
| `answer` | 1 | `Answer()` | Atender o canal |
| `wait` | 1 | `Wait()` | Pausar por N segundos |
| `hangup` | 0 | `Hangup()` | Desligar o canal |
| `redirect` | 0 | `GoTo()` | Redirecionar para extensão/contexto |
| `gotoivr` | 0 | `GoTo()` | Saltar para outro workflow IVR |

#### Condições e roteamento

| Operador | Saídas | App Asterisk | Descrição |
|----------|--------|--------------|-----------|
| `time` | 2+ | `GotoIfTime()` | Desvio por faixa horária |
| `date` | 2+ | `GotoIf()` + `${STRFTIME}` | Desvio por intervalo de data (com fallback "other") |
| `weekday` | 2+ | `GotoIf()` + `${STRFTIME}` | Desvio por dia da semana |
| `router` | 2+ | `GotoIf()` | Desvio por condições de variável (igual, contém, começa, termina, maior, menor, regex); suporta lógica AND/OR |
| `calleridcheck` | match/nomatch | `GotoIf()` | Desvio por padrão de CallerID |
| `menu` | dígitos + timeout + maxretries | `Read()` | Coleta de dígitos DTMF com limite de tentativas |
| `read` | 1 | `Read()` | Coletar dígitos em uma variável |

#### Mídia

| Operador | Saídas | App Asterisk | Descrição |
|----------|--------|--------------|-----------|
| `play` | 1 | `PlayBack()` ou `Background()` | Reproduzir áudio (opção interruptível usa `Background()`) |
| `record` | 1 | `MixMonitor()` | Iniciar/parar gravação |
| `stop` | 1 | `StopMixMonitor()` | Parar gravação |
| `say` | 1 | `SayDigits()` / `SayNumber()` / `SayAlpha()` / `SayUnixTime()` | Falar dígitos, números, letras ou data/hora |

#### Telefonia

| Operador | Saídas | App Asterisk | Descrição |
|----------|--------|--------------|-----------|
| `dial` | answered/noanswer/busy/congestion | `Dial()` | Discar extensão ou número; suporta CallerID e classe MOH |
| `queue` | answered/timeout/full/joinempty | `Queue()` | Encaminhar chamada para fila com anúncio de posição e limite de chamadores |
| `voicemail` | 1 | `VoiceMail()` | Enviar chamador para correio de voz (saudação ocupado/indisponível) |
| `conference` | 1 | `ConfBridge()` | Enviar chamador para sala de conferência |
| `callback` | ok/error | `Dial(Local/...)` | Agendar retorno de chamada via canal local |

#### Ações

| Operador | Saídas | App Asterisk | Descrição |
|----------|--------|--------------|-----------|
| `setvar` | 1 | `Set()` | Definir variável do canal |
| `script` | ok/error | `System()` | Executar script shell (desvia por código de saída) |
| `email` | 1 | `System(curl)` | Enviar email (suporta substituição de variáveis em To/Subject/Body) |
| `http` | ok/error | `Set(result=${CURL()})` | Requisição HTTP GET/POST; resposta armazenada em variável |

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

O VoIP Domain utiliza um sistema de i18n customizado inspirado no gettext, baseado em um dicionário em memória (`$_in["i18n"]`). Todas as strings visíveis ao usuário são escritas em inglês e envolvidas com funções de tradução. As traduções são carregadas dos arquivos `language.php` durante o carregamento dos módulos.

### 24.1 Estrutura do arquivo de idioma (`language.php`)

Cada módulo possui um `language.php` que registra as strings traduzíveis. O arquivo possui duas seções:

```php
/**
 * Add basic language structure (english)
 */
i18n_add ( "Search gateways");
i18n_add ( "The gateway description is required.");
i18n_add ( "An object containing information about the system gateway.");

/**
 * Add Brazilian Portuguese support
 */
i18n_add ( "Search gateways", "Pesquisar gateways", "pt_BR");
i18n_add ( "The gateway description is required.", "A descrição do gateway é obrigatória.", "pt_BR");
i18n_add ( "An object containing information about the system gateway.", "Um objeto contendo informações sobre o gateway do sistema.", "pt_BR");
```

Chamadas apenas em inglês (`i18n_add("text")`) registram a chave em `$_in["i18n"]` para que o sistema saiba que a string existe. Chamadas com tradução (`i18n_add("text", "tradução", "lang")`) armazenam a tradução indexada por idioma e contexto do módulo.

### 24.2 Funções PHP de tradução

| Função | Assinatura | Propósito |
|--------|-----------|-----------|
| `__()` | `__ ( $text, $strip = true, $slashes = true, $context = "" )` | Retorna string traduzida |
| `_e()` | `_e ( $text, $strip = true, $slashes = true, $context = "" )` | Imprime (echo) string traduzida |
| `_n()` | `_n ( $singular, $plural, $count, $strip = true, $slashes = true, $context = "" )` | Retorna forma singular/plural |
| `_ne()` | `_ne ( $singular, $plural, $count, $strip = true, $slashes = true, $context = "" )` | Imprime (echo) forma singular/plural |
| `i18n_add()` | `i18n_add ( $text, $translation = "", $language = "", $context = "" )` | Registra uma string ou tradução |

**Parâmetros:**

- `$strip` — remove tags HTML do resultado (padrão `true`). Defina como `false` quando a string contiver HTML intencional.
- `$slashes` — adiciona barras invertidas antes de `"` e `'` (padrão `true`). Defina como `false` quando o resultado não será incorporado em um literal de string JavaScript.
- `$context` — nome do módulo para busca com escopo. Quando vazio (padrão), usa `$_in["module"]`.

### 24.3 Cadeia de resolução da tradução

Quando `__("Some text")` é chamado, o sistema resolve a tradução nesta ordem:

1. **`{idioma}-{contexto}`** — ex.: `pt_BR-gateways`. Correspondência exata do módulo.
2. **`{idioma}`** — ex.: `pt_BR`. Fallback sem contexto (strings do módulo `interface` são armazenadas assim porque `$_in["module"]` é vazio quando `interface/language.php` carrega).
3. **Texto original** — se nenhuma tradução for encontrada, a string em inglês é retornada como está.

Exemplo com módulo `gateways` e idioma `pt_BR`:

```
__("Search gateways")
  → busca "pt_BR-gateways" em $_in["i18n"]["Search gateways"] → ENCONTRADO → "Pesquisar gateways"

__("Search gateways", true, true, "blocks")
  → busca "pt_BR-blocks" → NÃO ENCONTRADO
  → busca "pt_BR"        → NÃO ENCONTRADO (registrado no contexto gateways)
  → fallback             → "Search gateways"
```

### 24.4 Contexto do módulo (`$_in["module"]`)

O contexto é fundamental para o escopo das traduções:

- Quando `language.php` carrega, `$_in["module"]` está definido com o nome do diretório do módulo (ex.: `"gateways"`, `"servers"`, `"ivr"`).
- `i18n_add()` usa `$_in["module"]` como contexto padrão, então as traduções são armazenadas como `{idioma}-{módulo}`.
- `__()` também usa `$_in["module"]` como padrão para busca.
- **O módulo `interface` é especial:** `$_in["module"]` é definido como `""` (string vazia) quando `modules/interface/language.php` carrega. Isso significa que suas traduções são armazenadas na chave sem contexto `{idioma}` (ex.: `pt_BR`), tornando-as globalmente acessíveis como fallback para todos os módulos.

### 24.5 Formato de armazenamento

Internamente, `$_in["i18n"]` é um array associativo aninhado:

```php
$_in["i18n"] = array (
  "Search gateways" => array (
    "pt_BR-gateways" => "Pesquisar gateways"
  ),
  "Add" => array (
    "pt_BR" => "Adicionar"   // módulo interface (global)
  ),
  "Error" => array (
    "pt_BR" => "Erro",              // módulo interface (global)
    "pt_BR-debug" => "Erro debug"   // override do módulo debug (hipotético)
  )
);
```

### 24.6 Cache

No modo produção (`general.debug = false`), `$_in["i18n"]` é armazenado em cache no `modules/cache.json` junto com hooks, filtros e paths. O cache inclui todas as traduções de todos os arquivos `language.php`, com resultados de `__()` pré-avaliados para strings de registro de hooks (menus, títulos de API, etc.).

No modo debug, todos os arquivos `language.php` são recarregados a cada requisição.

**Invalidação de cache:** delete `modules/cache.json` para forçar uma recarga completa. Isso acontece automaticamente quando um plugin é habilitado/desabilitado.

### 24.7 Seleção do idioma

O idioma do usuário é determinado a partir de:

1. **Sessão Web UI:** `$_in["general"]["language"]` é definido a partir do perfil do usuário durante a autenticação.
2. **Token/sessão API:** mesmo mecanismo, definido durante a autenticação da API.
3. **Padrão:** `$_in["general"]["language"]` usa `en_US` por padrão de `webinterface.conf` (`[general] language`).

**Restrição importante:** no `api.php`, os arquivos dos módulos (incluindo `language.php` e `api.php`) carregam **antes** da autenticação. Isso significa que chamadas `__()` em `framework_add_hook()` e `framework_add_api_call()` (arrays de documentação) avaliam com o idioma padrão (`en_US`). A exportação da documentação OAS (`debug_api_export()`) compensa isso retraduzindo as strings no momento da exportação usando o idioma do usuário autenticado com parâmetros `$context` explícitos.

### 24.8 i18n no JavaScript

#### 24.8.1 Strings globais (carregadas no boot da página)

Quando o shell SPA carrega, `index.php` envia o dicionário completo de `$_in["i18n"]` para o idioma atual como parte da inicialização da página. O framework JavaScript (`voipdomain.js`) armazena essas strings em um objeto interno `i18ndata`.

#### 24.8.2 Strings por página (`sys_addtext`)

Para strings necessárias apenas em uma página específica (ex.: labels do DataTables, JavaScript inline), use `sys_addtext()` no hook da página:

```php
function myfeature_page ( $buffer, $parameters)
{
  sys_addtext ( "No records found", __ ( "No records found"));
  sys_addtext ( "Loading...", __ ( "Loading..."));
  // ...
}
```

Isso popula `$_in["page"]["i18n"]`, que é incluído no JSON de resposta AJAX da página. O frontend processa antes de executar o JavaScript inline:

```javascript
// voipdomain.js — processamento automático no carregamento da página
if ( 'i18n' in modules[module])
{
  for ( var key in modules[module].i18n)
  {
    if ( modules[module].i18n.hasOwnProperty ( key))
    {
      VoIP.i18n.add ( key, modules[module].i18n[key]);
    }
  }
}
```

#### 24.8.3 API de tradução JavaScript

```javascript
// Registrar uma string:
VoIP.i18n.add ( "original", "tradução");

// Traduzir uma string:
var text = VoIP.i18n.__ ( "original");
// Retorna "tradução" se registrada, "original" caso contrário
```

O sistema de i18n do JavaScript é um lookup simples chave-valor (sem contexto, sem cadeia de fallback). Toda a resolução de contexto acontece no lado do servidor em `sys_addtext()`.

### 24.9 i18n na documentação OAS

As strings de documentação da API (títulos de endpoint, descrições, descrições de parâmetros, descrições de resposta, descrições de campos e exemplos de schemas) são definidas nos arquivos `api.php` dos módulos usando `__()`. Como esses arquivos carregam antes da autenticação, as strings são inicialmente armazenadas em inglês.

A função `debug_api_export()` (em `modules/debug/api.php`) retraduz essas strings no momento da exportação:

- **Summary/description do endpoint:** retraduzidos usando o contexto do módulo primário (`$primarymodule`) determinado a partir de `$_plugins["map"]`.
- **Schemas de request/response:** retraduzidos via `format_schema($parameters, $context)` com o contexto do módulo propagado por todas as chamadas recursivas.
- **Schemas de componentes:** contexto do módulo resolvido a partir de `$_plugins["componentmap"]`, que é populado por `framework_add_component_documentation()`.

### 24.10 Adicionando um novo idioma

1. Abra cada arquivo `language.php` em `modules/*/`.
2. Adicione um novo bloco abaixo das traduções existentes:

```php
/**
 * Add Spanish support
 */
i18n_add ( "Search gateways", "Buscar gateways", "es_ES");
i18n_add ( "The gateway description is required.", "La descripción del gateway es obligatoria.", "es_ES");
// ... todas as strings
```

3. Defina o idioma em `webinterface.conf`: `language = es_ES`.
4. Delete `modules/cache.json` para reconstruir.

### 24.11 Ferramenta de validação (`checklanguage`)

Uma ferramenta CLI em `modules/checklanguage` valida a consistência do i18n por módulo:

```bash
cd modules
./checklanguage gateways
```

Ela varre os arquivos PHP procurando chamadas `__()`, `_e()`, `_n()`, `_ne()` e cruza com os registros em `language.php`. Reporta:

- **Traduções ausentes:** strings usadas no código mas não registradas em `language.php`.
- **Entradas não utilizadas:** strings registradas mas não encontradas em nenhum arquivo PHP (podem ser strings globais do módulo `interface` — verifique antes de remover).
- **Entradas duplicadas:** mesma string registrada múltiplas vezes.

Executar em todos os módulos:

```bash
for m in */; do [ -f "$m/language.php" ] && echo "=== $m ===" && ./checklanguage "${m%/}"; done
```

### 24.12 Boas práticas

1. **Sempre registre as chaves em inglês** — mesmo que o deployment atual seja em inglês. `i18n_add("My string")` garante que a chave existe em `$_in["i18n"]`.
2. **Use `$strip = false, $slashes = false`** quando a string traduzida contiver HTML ou não será incorporada em JS: `__("Link: <a href='...'", false, false)`.
3. **Use `$context` explicitamente** ao traduzir strings fora do módulo de origem (ex.: em `debug_api_export()`).
4. **Não duplique strings do interface** — se uma string está registrada em `modules/interface/language.php`, ela é globalmente acessível. Não registre novamente em outros módulos.
5. **Mantenha as chaves estáveis** — a string em inglês É a chave. Alterá-la quebra o vínculo com todas as traduções.
6. **Use `sys_addtext()` para strings JS específicas de página** — não coloque todas as strings no dicionário global de boot.
7. **Execute `checklanguage` após alterações** — detecte traduções faltantes antes do deploy.

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

*Versão do documento: 2026-07 — alinhado com VoIP Domain 1.1.*
