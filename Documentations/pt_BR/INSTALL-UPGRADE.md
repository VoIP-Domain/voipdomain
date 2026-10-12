# Sistema de Instalação/Upgrade

O VoIP Domain utiliza um mecanismo de instalação e upgrade com reconhecimento de
versão tanto para módulos core quanto para plugins. Este documento descreve como o
gerenciamento de schema funciona e como implementar migrações de banco de dados
versionadas.

## Visão Geral

- **Instalação limpa**: O hook `install_db` é chamado com `type = "install"` e um
  parâmetro `version` indicando a versão alvo sendo instalada.
- **Upgrade**: O hook `upgrade_db` é chamado com `type = "upgrade"`, `version_from`
  (versão instalada atualmente) e `version_to` (versão alvo).
- **Log de schema**: Toda operação de install/upgrade é registrada na tabela `SchemaLog`.
- **Versões de módulos**: A tabela `ModuleVersions` rastreia a versão atual de cada
  módulo core e plugin.

## Tabelas do Banco de Dados

### SchemaLog

Registra cada migração de schema aplicada ao banco:

```sql
CREATE TABLE `SchemaLog` (
  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Module` varchar(128) NOT NULL,
  `VersionFrom` varchar(32) DEFAULT NULL,
  `VersionTo` varchar(32) NOT NULL,
  `Type` enum('install','upgrade') NOT NULL,
  `AppliedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('success','failed','partial') NOT NULL,
  `Details` text,
  PRIMARY KEY (`ID`),
  KEY `idx_module_version` (`Module`, `VersionTo`)
) ENGINE=InnoDB;
```

### ModuleVersions

Rastreia a versão atual de cada módulo:

```sql
CREATE TABLE `ModuleVersions` (
  `Module` varchar(128) NOT NULL,
  `Version` varchar(32) NOT NULL,
  `InstalledAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`Module`)
) ENGINE=InnoDB;
```

## Versionamento de Módulos Core

Todas as versões de módulos core são declaradas em `includes/module-versions.inc.php`:

```php
define ( "MODULE_USERS_VERSION", "1.0.0");
define ( "MODULE_SERVERS_VERSION", "1.0.0");
// ... etc.
```

Ao incrementar a versão de um módulo, atualize a constante e adicione a lógica
correspondente no hook `upgrade_db`.

## Hook de Instalação (Instalação Limpa)

Chamado durante a instalação inicial do sistema. O array `$parameters` contém:

| Chave | Valor |
|-------|-------|
| `type` | `"install"` |
| `version` | Versão alvo (ex: `"1.0.0"`) |

Exemplo:

```php
framework_add_hook ( "install_db", "meumodulo_install_db");

function meumodulo_install_db ( $buffer, $parameters)
{
  // $parameters["type"] == "install"
  // $parameters["version"] == "1.0.0"
  install_add_db_table ( "MinhaTabela", "CREATE TABLE `MinhaTabela` ( ... )", array("Dep"));
  return $buffer;
}
```

## Hook de Upgrade (Mudança de Versão)

Chamado quando o sistema ou plugin é atualizado. O array `$parameters` contém:

| Chave | Valor |
|-------|-------|
| `type` | `"upgrade"` |
| `version_from` | Versão instalada atualmente |
| `version_to` | Versão alvo |
| `version` | Mesmo que `version_to` |

Exemplo:

```php
framework_add_hook ( "upgrade_db", "meumodulo_upgrade_db");

function meumodulo_upgrade_db ( $buffer, $parameters)
{
  if ( version_compare ( $parameters["version_from"], "1.1.0", "<"))
  {
    install_add_db_alter ( "MinhaTabela_add_col",
      "ALTER TABLE `MinhaTabela` ADD COLUMN `NovaCol` VARCHAR(64) DEFAULT NULL");
  }
  if ( version_compare ( $parameters["version_from"], "1.2.0", "<"))
  {
    install_add_db_alter ( "MinhaTabela_idx_novacol",
      "ALTER TABLE `MinhaTabela` ADD INDEX `idx_novacol` (`NovaCol`)",
      array("MinhaTabela_add_col"));
  }
  return $buffer;
}
```

As verificações de versão são cumulativas — upgrade de 1.0.0 para 1.2.0 aplica
ambos os blocos.

## Funções DB Disponíveis

### install_add_db_table($name, $sql, $deps)

Registra um CREATE TABLE. Usado durante instalações limpas.

### install_add_db_alter($name, $sql, $deps)

Registra um ALTER TABLE. Usado durante upgrades. O `$name` deve ser único
(ex: `"MinhaTabela_add_priority"`). Dependências referenciam outros nomes de alter.

### install_add_db_raw($name, $sql, $deps)

Escape hatch para SQL arbitrário (migrações de dados, operações complexas). Executa
após alters, antes de triggers.

### install_add_db_drop($name, $type, $deps)

Registra um DROP. `$type` é um de: `"table"`, `"trigger"`, `"procedure"`.

### install_add_db_procedure($name, $sql, $deps)

Registra um stored procedure.

### install_add_db_trigger($name, $sql, $deps)

Registra um trigger.

### install_add_db_data($name, $data, $deps)

Registra dados INSERT para uma tabela.

## Ordem de Execução

`plugin_process_db()` executa os statements registrados nesta ordem:

1. Procedures
2. Tables (CREATE)
3. Alters (ALTER TABLE)
4. Raw SQL
5. Triggers
6. Data (INSERT)

Dentro de cada categoria, a ordenação por dependência é respeitada.

## Guia do Desenvolvedor de Plugins

### plugin.json

O `plugin.json` do seu plugin deve declarar uma versão semver:

```json
{
  "name": "Meu Plugin",
  "version": "1.0.0",
  "author": "Nome do Autor",
  "description": "Descrição do plugin",
  "license": "GPLv3"
}
```

### install.php

Cria o schema inicial. Chamado na primeira instalação e no upgrade se não existir
`update.php`:

```php
framework_add_hook ( "install_db", "meuplugin_install_db");

function meuplugin_install_db ( $buffer, $parameters)
{
  install_add_db_table ( "TabelaPlugin", "CREATE TABLE ...");
  return $buffer;
}
```

### update.php

Gerencia migrações de schema para upgrades. Se este arquivo existir e o plugin
estiver sendo atualizado, ele é usado no lugar do `install.php`:

```php
framework_add_hook ( "upgrade_db", "meuplugin_upgrade_db");

function meuplugin_upgrade_db ( $buffer, $parameters)
{
  if ( version_compare ( $parameters["version_from"], "1.1.0", "<"))
  {
    install_add_db_alter ( "TabelaPlugin_add_col",
      "ALTER TABLE `TabelaPlugin` ADD COLUMN `NovaCol` INT DEFAULT 0");
  }
  return $buffer;
}
```

### Comparação de Versão

Sempre use `version_compare()` para verificações de versão:

```php
if ( version_compare ( $parameters["version_from"], "1.1.0", "<"))
```

Nunca use operadores numéricos (`<`, `>`, `==`) em strings de versão.

## API de Upgrade do Sistema

O upgrade do sistema é acionado via:

```
POST /api/system/upgrade
```

Este endpoint (somente admin):
1. Garante que as tabelas `SchemaLog` e `ModuleVersions` existam (bootstrap de instalações pré-1.1)
2. Corrige a coluna `Plugins.Version` se ainda for `float` (legado)
3. Lê versões atuais de `ModuleVersions`
4. Compara com as constantes `MODULE_*_VERSION` declaradas
5. Executa hooks `upgrade_db` para módulos que precisam de upgrade
6. Registra resultados no `SchemaLog`
7. Atualiza `ModuleVersions`

## Sincronização com Servidores Remotos

Quando um plugin é instalado ou atualizado, o manifesto do banco de dados (com
informações de versão) é sincronizado para servidores remotos via VDFT. O handler
remoto `plugin_db_apply` recebe tanto o manifesto quanto o contexto de versão,
permitindo que servidores remotos apliquem as mesmas alterações de schema.

## Compatibilidade Retroativa

- Hooks `install_db` existentes que ignoram `$parameters` continuam funcionando
- Plugins sem `update.php` recaem para `install.php` no upgrade (comportamento existente)
- Instalações pré-1.1 sem tabelas `SchemaLog`/`ModuleVersions` são inicializadas
  automaticamente no primeiro upgrade
