# Guia do Desenvolvedor de Plugins do VoIP Domain

## Visão Geral

O sistema de plugins do VoIP Domain fornece uma arquitetura modular que permite aos desenvolvedores estender a plataforma sem modificar o código core. O design é conceitualmente baseado no sistema de plugins do WordPress, adaptado para um ambiente de telefonia distribuída.

A interface administrativa central gerencia o ciclo de vida dos plugins: importação, habilitação, desabilitação, atualização e remoção. Código de plugins destinado a daemons remotos (Client, FastAGI, Monitor, Router) é implantado automaticamente via dispatch de jobs Gearman e transferência de arquivos.

**Distinções importantes:**

- Diretórios `*-plugins/` contêm código de plugins de terceiros implantado em cada daemon.
- Diretórios `*-modules/` são reservados para código core empacotado pelo RPM. Nunca coloque arquivos de plugin lá.

Configuração específica de plugins é armazenada em `/var/lib/voipdomain/data/plugin-{nome}.json` e distribuída automaticamente aos daemons alvo.

---

## Estrutura do Plugin

Um plugin é empacotado como um arquivo zip contendo um único diretório cujo nome corresponde ao campo `name` no `plugin.json`. O layout do diretório é:

```
meuplugin/
  plugin.json          (obrigatório - manifesto)
  api.php              (obrigatório - hooks da interface administrativa e endpoints de API)
  install.php          (opcional - configuração do banco de dados na instalação limpa)
  update.php           (opcional - atualizações de banco com controle de versão)
  uninstall.php        (opcional - limpeza do banco na remoção)
  filter.php           (opcional - hooks de filtro de dados, ex: itens de menu)
  webui.php            (opcional - páginas e formulários da interface administrativa)
  language/            (opcional - traduções i18n)
    pt_BR.php
    en_US.php
  client-plugins/      (opcional - código implantado no daemon Client)
    meuplugin/
      client.php
  fastagi-plugins/     (opcional - código implantado no daemon FastAGI)
    meuplugin/
      fastagi.php
  monitor-plugins/     (opcional - código implantado no daemon Monitor)
    meuplugin/
      monitor.php
  router-plugins/      (opcional - código implantado no daemon Router)
    meuplugin/
      router.php
  _signature/          (opcional - assinatura criptográfica)
    MANIFEST
    MANIFEST.sig
    developer.pem
    MANIFEST.official.sig  (opcional - selo oficial VoIP Domain)
```

---

## Manifesto plugin.json

O manifesto é o identificador central do plugin. Exemplo completo:

```json
{
  "$schema": "voipdomain-plugin-1.0",
  "name": "meuplugin",
  "display_name": "Meu Plugin",
  "version": "1.0.0",
  "author": {
    "name": "Nome do Desenvolvedor",
    "email": "dev@exemplo.com",
    "url": "https://exemplo.com"
  },
  "published": "2026-07-22",
  "description": "Descrição curta do plugin",
  "license": "GPLv3",
  "support": {
    "url": "https://exemplo.com/suporte",
    "email": "suporte@exemplo.com"
  },
  "requires": {
    "core": ">=1.0.0",
    "php": ">=8.1"
  },
  "conflicts": [],
  "daemons": ["client", "fastagi"],
  "hooks": {
    "install": "meuplugin_install_db",
    "upgrade": "meuplugin_upgrade_db"
  },
  "config": {
    "targets": ["client", "fastagi"]
  },
  "signature": {}
}
```

### Campos Obrigatórios

| Campo | Tipo | Descrição |
|-------|------|-----------|
| name | string | Nome do diretório (deve corresponder à pasta do zip) |
| display_name | string | Nome amigável para exibição |
| version | string | Versão semântica (ex: "1.2.3") |
| author | object | Deve conter ao menos `name` |
| description | string | Descrição curta do plugin |
| license | string | Identificador de licença SPDX |

### Campos Opcionais

| Campo | Tipo | Descrição |
|-------|------|-----------|
| published | string | Data de publicação (AAAA-MM-DD) |
| support | object | URLs de suporte {url, email} |
| requires | object | Restrições de versão {modulo: ">=versão"} |
| conflicts | array | Lista de plugins conflitantes |
| daemons | array | Daemons alvo: "client", "fastagi", "monitor", "router" |
| hooks | object | Nomes de funções de ciclo de vida |
| config | object | Configuração distribuída aos daemons |
| signature | object | Preenchido pela ferramenta de assinatura |

---

## Ciclo de Vida do Plugin

### Estados

| Estado | Código | Descrição |
|--------|--------|-----------|
| Ativo | A | Plugin está carregado e funcional |
| Inativo | I | Desabilitado pelo administrador |
| Suspenso | S | Suspenso por falha de dependência |
| Falha | F | Estado de erro |

### Fluxo de Instalação

1. Upload do arquivo zip via API (`POST /config/plugins/import`)
2. Extração e validação do `plugin.json`
3. Verificação de assinatura criptográfica
4. Verificação de requisitos e conflitos
5. Inserção no banco de dados (tabela `Plugins`)
6. Execução de `install.php` (se existir)
7. Deploy de módulos para daemons remotos
8. Sincronização de configuração para datadir
9. Reload dos daemons afetados via systemd

### Desabilitar/Habilitar

- **Desabilitar**: verifica dependências reversa → renomeia diretório com prefixo `disabled_` → atualiza status no BD → reload daemons
- **Habilitar**: renomeia de volta → atualiza BD → reload daemons

### Remover

- Verifica dependências reversas (outros plugins que dependem deste)
- Executa `uninstall.php` (se existir)
- Remove módulos dos daemons remotos
- Remove configuração do datadir
- Deleta diretório local
- Remove entrada do banco de dados

### Atualizar

- Verifica se versão nova > versão atual
- Executa `update.php` com parâmetros de versão
- Reimplanta módulos nos daemons
- Atualiza metadados no banco

---

## Gerenciamento de Banco de Dados

### install.php (instalação limpa)

```php
<?php
framework_add_hook("install_db", "meuplugin_install_db");
function meuplugin_install_db($buffer, $parameters)
{
  install_add_db_table("MeuPlugin", "CREATE TABLE `MeuPlugin` (
    `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `Name` varchar(255) NOT NULL,
    PRIMARY KEY (`ID`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

  install_add_db_data("MeuPlugin_defaults", "INSERT INTO `MeuPlugin` (`Name`) VALUES ('Padrão');", array("MeuPlugin"));
  return $buffer;
}
?>
```

### update.php (atualizações com controle de versão)

```php
<?php
framework_add_hook("upgrade_db", "meuplugin_upgrade_db");
function meuplugin_upgrade_db($buffer, $parameters)
{
  if (version_compare($parameters["version_from"], "1.1.0", "<")) {
    install_add_db_alter("MeuPlugin_add_priority",
      "ALTER TABLE `MeuPlugin` ADD COLUMN `Priority` INT NOT NULL DEFAULT 0");
  }
  if (version_compare($parameters["version_from"], "1.2.0", "<")) {
    install_add_db_alter("MeuPlugin_idx_priority",
      "ALTER TABLE `MeuPlugin` ADD INDEX `idx_priority` (`Priority`)",
      array("MeuPlugin_add_priority"));
  }
  return $buffer;
}
?>
```

### Funções Disponíveis

| Função | Uso |
|--------|-----|
| `install_add_db_table($name, $sql, $deps)` | Criar tabela |
| `install_add_db_data($name, $sql, $deps)` | Inserir dados |
| `install_add_db_alter($name, $sql, $deps)` | ALTER TABLE |
| `install_add_db_raw($name, $sql, $deps)` | SQL arbitrário |
| `install_add_db_drop($name, $type, $deps)` | DROP (table/trigger/procedure) |
| `install_add_db_procedure($name, $sql, $deps)` | Stored procedure |
| `install_add_db_trigger($name, $sql, $deps)` | Trigger |

---

## Assinatura Criptográfica (X.509)

### Visão Geral

O sistema de assinatura garante integridade e autenticidade dos plugins usando certificados X.509 auto-assinados e RSA-SHA256 (via extensão PHP openssl). O modelo é baseado em confiança explícita por keyring:

1. A **chave mestra VoIP Domain** (`voipdomain.pem`) é um certificado X.509 auto-assinado. Chave pública distribuída via repositório git e pacotes RPM. É a chave mestra confiável do projeto.
2. Cada **desenvolvedor** gera seu próprio certificado X.509 auto-assinado. O administrador instala manualmente os certificados no keyring do sistema.
3. Na publicação, o desenvolvedor gera um MANIFEST com hashes SHA-256 de todos os arquivos e assina com sua chave privada RSA.
4. Inclui `_signature/` no zip do plugin (MANIFEST + MANIFEST.sig + developer.pem).
5. Opcionalmente, a equipe VoIP Domain pode assinar o MANIFEST com a chave mestra (`MANIFEST.official.sig`), conferindo um selo oficial ao plugin.

### Modelo de Confiança

```
Keyring do sistema (/etc/voipdomain/keyring/)
  ├── voipdomain.pem              (chave mestra — selo oficial)
  └── developers/
        ├── alice.pem             (certificado auto-assinado do desenvolvedor)
        ├── bob.pem
        └── ...
```

A confiança é estabelecida pela presença do certificado no keyring do sistema. Certificados de desenvolvedores são auto-assinados e considerados confiáveis porque o administrador os instalou explicitamente no diretório `developers/`.

### Política de Assinatura

Configurada em `/etc/voipdomain/keyring/policy.conf`:

```ini
[signing]
policy = block
trust_embedded_keys = false
```

- `policy = block` (padrão): rejeita plugins sem assinatura válida
- `policy = warn`: permite instalação com aviso no log
- `policy = allow_unsigned`: nenhuma verificação necessária
- `trust_embedded_keys = true`: aceita o certificado embutido no plugin mesmo que não esteja no keyring (com aviso de não confiável)

### Gerando Chave e Certificado de Desenvolvedor

```bash
# 1. Gerar chave privada RSA (2048 bits)
openssl genrsa -out developer.key 2048

# 2. Gerar certificado X.509 auto-assinado (válido por 3 anos)
openssl req -new -x509 -key developer.key -sha256 -days 1095 \
  -out developer.pem -subj "/CN=Developer Name/O=Company/OU=VoIP Domain Plugins"
```

O arquivo `developer.key` é a chave privada — mantenha-o em SEGREDO. O arquivo `developer.pem` é o certificado público que será incluído nos plugins assinados.

Para que plugins assinados com este certificado sejam considerados confiáveis, o administrador do sistema deve instalar o certificado no keyring:

```bash
cp developer.pem /etc/voipdomain/keyring/developers/nomedodesenvolvedor.pem
```

### Assinando um Plugin

```bash
# 1. Extrair plugin para diretório temporário
mkdir -p /tmp/signing && unzip meuplugin.zip -d /tmp/signing/

# 2. Gerar manifesto (lista de hashes SHA-256 dos arquivos)
php -r "
require '/var/lib/voipdomain/webroot/includes/plugin-signing.inc.php';
\$manifest = plugin_generate_manifest('/tmp/signing', 'meuplugin', 'meu-id');
@mkdir('/tmp/signing/meuplugin/_signature', 0755, true);
file_put_contents('/tmp/signing/meuplugin/_signature/MANIFEST', \$manifest);
"

# 3. Assinar MANIFEST com chave privada do desenvolvedor
openssl dgst -sha256 -sign developer.key -out MANIFEST.sig \
  /tmp/signing/meuplugin/_signature/MANIFEST
base64 MANIFEST.sig > MANIFEST.sig.b64 && mv MANIFEST.sig.b64 /tmp/signing/meuplugin/_signature/MANIFEST.sig
rm MANIFEST.sig

# 4. Incluir certificado do desenvolvedor
cp developer.pem /tmp/signing/meuplugin/_signature/developer.pem

# 5. Recriar zip
cd /tmp/signing && zip -r meuplugin-signed.zip meuplugin/
```

### Assinatura Oficial (Selo VoIP Domain)

Se a equipe VoIP Domain validar o plugin, pode adicionar uma assinatura oficial com a chave mestra. Plugins com assinatura oficial recebem um selo verde "oficial/validado" na interface administrativa.

```bash
# Assinar MANIFEST com a chave mestra VoIP Domain
openssl dgst -sha256 -sign voipdomain.key -out MANIFEST.official.sig \
  /tmp/signing/meuplugin/_signature/MANIFEST
base64 MANIFEST.official.sig > MANIFEST.official.sig.b64 \
  && mv MANIFEST.official.sig.b64 /tmp/signing/meuplugin/_signature/MANIFEST.official.sig
rm MANIFEST.official.sig
```

A assinatura oficial é independente da assinatura do desenvolvedor. Um plugin pode ter ambas, apenas a do desenvolvedor, ou nenhuma.

### Conteúdo do Diretório `_signature/`

| Arquivo | Formato | Descrição |
|---------|---------|-----------|
| MANIFEST | Texto | Lista de hashes SHA-256 de todos os arquivos do plugin |
| MANIFEST.sig | Base64 (RSA-SHA256) | Assinatura digital do MANIFEST pelo desenvolvedor |
| developer.pem | PEM (X.509) | Certificado X.509 auto-assinado do desenvolvedor |
| MANIFEST.official.sig | Base64 (RSA-SHA256) | (Opcional) Assinatura pela chave mestra VoIP Domain |

### Verificação (Fluxo Interno)

O sistema executa os seguintes passos ao verificar um plugin:

1. Lê `MANIFEST`, `MANIFEST.sig` e `developer.pem` do diretório `_signature/`
2. Calcula a fingerprint SHA-256 do `developer.pem`
3. Busca no keyring (`/etc/voipdomain/keyring/developers/*.pem`) um certificado com fingerprint correspondente
   - Se encontrado → desenvolvedor é "confiável"
   - Se não encontrado e `trust_embedded_keys=true` → usa o certificado embutido (com aviso de "não confiável")
   - Se não encontrado e `trust_embedded_keys=false` → rejeita conforme política
4. Extrai a chave pública do certificado: `openssl_pkey_get_public()`
5. Verifica a assinatura do MANIFEST: `openssl_verify()` com `OPENSSL_ALGO_SHA256`
6. Verifica os hashes SHA-256 dos arquivos listados no MANIFEST
7. Se `MANIFEST.official.sig` existir → carrega `voipdomain.pem` do keyring e verifica a assinatura oficial → se válida, marca o plugin como "oficial"

Se qualquer etapa falhar, a ação é determinada pela política configurada (`block`/`warn`/`allow_unsigned`).

### Chaveiro do Sistema

- `/etc/voipdomain/keyring/` — diretório principal
- `/etc/voipdomain/keyring/voipdomain.pem` — certificado da chave mestra VoIP Domain
- `/etc/voipdomain/keyring/developers/` — certificados auto-assinados dos desenvolvedores
- `/etc/voipdomain/keyring/policy.conf` — configuração de política

O certificado da chave mestra (`voipdomain.pem`) é distribuído pelo pacote RPM `voipdomain-filesystem` e instalado automaticamente em `/etc/voipdomain/keyring/`.

---

## Arquitetura Multi-Daemon

### Daemons e Funções

| Daemon | Função | Worker Gearman |
|--------|--------|----------------|
| Client | Config Asterisk, transferências, rebuild | Existente (notificações) |
| FastAGI | Scripts AGI, roteamento de chamadas | `vd_fastagi_{serverid}_task` |
| Monitor | Eventos AMI, status em tempo real | `vd_monitor_{serverid}_task` |
| Router | Roteamento Gearman, ciclo de comandos | `vd_router_task` |

### Carregamento de Plugins

Cada daemon carrega plugins do seu diretório `*-plugins/` na inicialização:

```php
// Em cada daemon (client.php, fastagi.php, monitor.php, router.php):
$pluginsdir = dirname(__FILE__) . "/{daemon}-plugins";
foreach (scandir($pluginsdir) as $entry) {
  if (is_dir($pluginsdir . "/" . $entry . "/{daemon}.php")) {
    require_once($pluginpath . "/{daemon}.php");
  }
}
```

### Distribuição de Configuração

A configuração do plugin (campo `config` no `plugin.json`) é distribuída como `/var/lib/voipdomain/data/plugin-{nome}.json` para todos os daemons alvo. Daemons com inotify detectam alterações automaticamente.

---

## Gerenciamento de Dependências

### Requisitos

O campo `requires` no `plugin.json` define dependências:

```json
{
  "requires": {
    "core": ">=1.0.0",
    "php": ">=8.1",
    "outro-plugin": ">=2.0.0"
  }
}
```

Operadores suportados: `>=`, `<=`, `>`, `<`, `=`, `!=`

### Verificação Reversa

Ao desabilitar ou remover um plugin, o sistema verifica se outros plugins ativos dependem dele. Se encontrar dependentes, a operação é rejeitada com erro 422 listando os plugins afetados.

### Conflitos

O campo `conflicts` lista plugins incompatíveis. A instalação é bloqueada se um plugin conflitante estiver ativo.

---

## Endpoints da API

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | /config/plugins/import | Importar/atualizar plugin (upload multipart) |
| GET | /config/plugins | Listar todos os plugins |
| GET | /config/plugin/:Plugin | Detalhes do plugin |
| PUT | /config/plugin/:Plugin/toggle | Alternar habilitar/desabilitar |
| PUT | /config/plugin/:Plugin/enable | Habilitar |
| PUT | /config/plugin/:Plugin/disable | Desabilitar |
| DELETE | /config/plugin/:Plugin | Remover |

---

## Internacionalização

Todas as strings visíveis ao usuário em um plugin devem ser envolvidas com `__()` para suporte a tradução. As traduções são definidas em `language.php`.

### Escrevendo `language.php`

O arquivo possui duas seções: registro de chaves em inglês e traduções.

```php
<?php
/**
 * Add basic language structure (english)
 */
i18n_add ( "Search my plugin items");
i18n_add ( "Add new item");
i18n_add ( "The item name is required.");
i18n_add ( "An object containing the plugin item details.");

/**
 * Add Brazilian Portuguese support
 */
i18n_add ( "Search my plugin items", "Pesquisar itens do meu plugin", "pt_BR");
i18n_add ( "Add new item", "Adicionar novo item", "pt_BR");
i18n_add ( "The item name is required.", "O nome do item é obrigatório.", "pt_BR");
i18n_add ( "An object containing the plugin item details.", "Um objeto contendo os detalhes do item do plugin.", "pt_BR");
```

**Regras:**

- A string em inglês é a chave de busca — deve corresponder exatamente entre o registro `i18n_add()` e o uso `__()` (case-sensitive).
- Chamadas apenas em inglês (`i18n_add("text")`) registram a chave. Chamadas com tradução (`i18n_add("text", "tradução", "lang")`) adicionam a tradução para um locale específico.
- `i18n_add()` usa automaticamente o nome do módulo atual como contexto. Não passe o parâmetro `$context` a menos que tenha um motivo específico.
- Não registre novamente strings já definidas em `modules/interface/language.php` — essas são globalmente disponíveis para todos os módulos.

### Usando traduções no código

```php
// Em api.php — metadados do endpoint:
"title" => __ ( "View item"),
"description" => __ ( "Returns item details."),

// Em webui.php — configuração da página:
sys_set_title ( __ ( "My Plugin"));
sys_set_subtitle ( __ ( "item management"));

// Em filter.php — entrada no menu:
"text" => __ ( "My Plugin"),

// Em webui.php — strings JavaScript para uso específico da página:
sys_addtext ( "Confirm delete?", __ ( "Confirm delete?"));
```

### Traduções em JavaScript

Para strings usadas em JavaScript inline, use `sys_addtext()` no hook da página para entregar traduções ao frontend:

```php
function myplugin_page ( $buffer, $parameters)
{
  sys_addtext ( "Item saved", __ ( "Item saved"));
  sys_addtext ( "Delete failed", __ ( "Delete failed"));
  // ... HTML da página ...
}
```

No JavaScript, use `VoIP.i18n.__()` para buscar a string traduzida:

```javascript
VoIP.REST.call ( url, "DELETE", null, function ( data)
{
  new PNotify ( { title: VoIP.i18n.__ ( "Item saved"), type: "success" });
});
```

### Validação

Use a ferramenta CLI `checklanguage` para verificar o `language.php` do seu plugin:

```bash
cd modules
./checklanguage myplugin
```

Reporta traduções ausentes, entradas não utilizadas e duplicatas. Execute antes de cada release.

Para documentação completa do sistema de i18n, veja [MANUAL-DO-DESENVOLVEDOR.md §24](MANUAL-DO-DESENVOLVEDOR.md#24-internacionalização).

---

## Boas Práticas

1. **Versionamento semântico**: use sempre MAJOR.MINOR.PATCH
2. **update.php idempotente**: use guards `version_compare()` para cada bloco de migração
3. **Desenvolvimento**: configure `policy = allow_unsigned` temporariamente
4. **Publicação**: sempre assine releases antes da distribuição
5. **config.targets**: especifique quais daemons precisam da configuração
6. **Hooks sobre chamadas diretas**: prefira `framework_add_hook()` para extensibilidade
7. **Ordenação de hooks**: use `framework_add_hook_at()` quando a ordem importa
8. **Diretórios corretos**: use `*-plugins/` para código de plugin, nunca `*-modules/`
9. **Testes**: teste em ambiente com política `block` antes de publicar

---

## Referência Rápida

### Registrar um Hook

```php
framework_add_hook("nome_evento", "minha_funcao");
function minha_funcao($buffer, $parameters)
{
  // Modificar $buffer ou processar $parameters
  return $buffer;
}
```

### Registrar um Endpoint API

```php
framework_add_api_call("/meu/endpoint", "Read", "meu_hook", array(
  "permissions" => array("Administrator"),
  "title" => "Meu Endpoint",
  "description" => "Descrição do endpoint."
));
```

### Registrar uma Permissão

```php
framework_add_permission("meu_hook", __("Descrição da permissão"));
```
