# Sample Items Plugin (example)

This folder contains a **minimal, fully-featured CRUD plugin** that you can use as a template for your own modules.

* `api.php` – registers hooks, permissions and REST endpoints for *search*, *view*, *add*, *edit*, *remove*.
* `webui.php` – injects a tiny AdminLTE/DataTables list page into the Administrative Interface and object manipulation.
* `language.php` - the sentences translation used in this plugin.
* `install.php` - installation script triggered if the plugin was injected using the system interface.
* `filter.php` - registers filters hooks, as the menu entry.

## Installation

1. Make sure the table exists (MariaDB):
   ```sql
   CREATE TABLE `SampleItems` (
     `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
     `Name` VARCHAR(64) NOT NULL,
     `Description` TEXT NULL,
     PRIMARY KEY (`ID`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
   ```
2. Copy this directory into `Plugins/` (already done if you are reading this).
3. Reload the UI – a new **Sample items** entry should appear in the sidebar.
4. Use `/api/sampleitems` to interact programmatically (see Swagger docs).

Feel free to delete this plugin after you have used it as reference.
