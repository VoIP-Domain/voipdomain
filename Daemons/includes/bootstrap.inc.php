<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * VoIP Domain Asterisk bootstrap configuration generation helpers.
 *
 * This module provides the infrastructure for hook-based Asterisk config
 * generation. Each Asterisk configuration file (dialplan, PJSIP, queues, etc.)
 * is generated from named hooks, allowing core modules and plugins to register
 * their contributions. The pipeline pattern ensures plugins can not only append
 * content but also modify, replace, or inject into existing configurations.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Asterisk bootstrap configuration section registry.
 *
 * Each entry maps an Asterisk config area to its output file, the CLI command
 * used to reload it, and the ordered list of hook suffixes that compose it.
 */
$GLOBALS["_asterisk_bootstrap_sections"] = array (
  "dialplan" => array (
    "file" => "dialplan.conf",
    "reload" => "dialplan reload",
    "hooks" => array ( "globals", "database", "extensions", "hints", "exceptions", "blocks", "ranges", "ivr", "services", "routing", "internal", "tools", "handlers", "extra")
  ),
  "pjsip" => array (
    "file" => "pjsip.conf",
    "reload" => "pjsip reload",
    "hooks" => array ( "transports", "defaults", "includes")
  ),
  "pjsip_notify" => array (
    "file" => "pjsip_notify.conf",
    "reload" => "pjsip reload",
    "hooks" => array ( "notify")
  ),
  "queues" => array (
    "file" => "queues.conf",
    "reload" => "module reload app_queue.so",
    "hooks" => array ( "queues")
  ),
  "confbridge" => array (
    "file" => "confbridge.conf",
    "reload" => "module reload app_confbridge.so",
    "hooks" => array ( "confbridge")
  ),
  "voicemail" => array (
    "file" => "voicemail.conf",
    "reload" => "voicemail reload",
    "hooks" => array ( "voicemail")
  ),
  "moh" => array (
    "file" => "musiconhold.conf",
    "reload" => "moh reload",
    "hooks" => array ( "moh")
  ),
  "manager" => array (
    "file" => "manager.conf",
    "reload" => "manager reload",
    "hooks" => array ( "manager")
  ),
  "indications" => array (
    "file" => "indications.conf",
    "reload" => "module reload indications",
    "hooks" => array ( "indications")
  ),
  "cdr" => array (
    "file" => "cdr.conf",
    "reload" => "cdr reload",
    "hooks" => array ( "cdr_general")
  ),
  "cdr_manager" => array (
    "file" => "cdr_manager.conf",
    "reload" => "cdr reload",
    "hooks" => array ( "cdr_manager")
  ),
  "cdr_sqlite3_custom" => array (
    "file" => "cdr_sqlite3_custom.conf",
    "reload" => "cdr reload",
    "hooks" => array ( "cdr_sqlite3_custom")
  ),
  "cel" => array (
    "file" => "cel.conf",
    "reload" => "cel reload",
    "hooks" => array ( "cel_general", "cel_manager")
  )
);

/**
 * Return the Asterisk bootstrap directory path.
 *
 * @global array $_in Framework global configuration variable
 * @return string
 */
function asterisk_bootstrap_dir ()
{
  global $_in;

  if ( array_key_exists ( "general", $_in) && array_key_exists ( "asteriskdir", $_in["general"]))
  {
    return rtrim ( $_in["general"]["asteriskdir"], "/") . "/voipdomain/bootstrap/";
  }
  return "/etc/asterisk/voipdomain/bootstrap/";
}

/**
 * Write a bootstrap configuration file. Compares the body content against the
 * existing file to determine if the file actually changed (excluding the
 * auto-generated header which contains a timestamp).
 *
 * @param string $filename Bootstrap file name (e.g., "dialplan.conf")
 * @param string $content Generated content (without header)
 * @return boolean True if file was written (content changed), false if unchanged
 */
function write_bootstrap_file ( $filename, $content)
{
  $path = asterisk_bootstrap_dir () . $filename;

  if ( is_readable ( $path))
  {
    $existing = file_get_contents ( $path);
    $existingBody = preg_replace ( "/^;;[^\n]*\n(?:;;[^\n]*\n)*\n/", "", $existing, 1);
    if ( $existingBody === $content)
    {
      return false;
    }
  }

  $header = ";;\n";
  $header .= ";; VoIP Domain — AUTO-GENERATED configuration file\n";
  $header .= ";; Do not edit manually. Changes will be overwritten on next generation.\n";
  $header .= ";; Generated: " . date ( "c") . "\n";
  $header .= ";;\n\n";

  $dir = dirname ( $path);
  if ( ! is_dir ( $dir))
  {
    mkdir ( $dir, 0755, true);
  }
  return file_put_contents ( $path, $header . $content) !== false;
}

/**
 * Generate all Asterisk bootstrap configuration files from registered hooks.
 *
 * Iterates each registered section, calls all hooks in order to build content,
 * writes the output file, and selectively reloads only the Asterisk modules
 * whose configuration actually changed.
 *
 * @global array $_in Framework global configuration variable
 * @param array $parameters Optional parameters passed to hook functions
 * @return array List of reload commands that were executed
 */
function asterisk_bootstrap_generate ( $parameters = array ())
{
  $reloads = array ();

  foreach ( $GLOBALS["_asterisk_bootstrap_sections"] as $name => $config)
  {
    $content = "";
    foreach ( $config["hooks"] as $hook)
    {
      $hookName = "asterisk_" . $name . "_" . $hook;
      if ( framework_has_hook ( $hookName))
      {
        $result = framework_call ( $hookName, $parameters, false, "");
        if ( is_string ( $result))
        {
          $content .= $result;
        }
      }
    }
    if ( write_bootstrap_file ( $config["file"], $content))
    {
      $reloads[$config["reload"]] = true;
    }
  }

  foreach ( array_keys ( $reloads) as $cmd)
  {
    asterisk_exec ( $cmd);
  }

  if ( sizeof ( $reloads) > 0)
  {
    writeLog ( "Bootstrap regenerated: reloaded " . implode ( ", ", array_keys ( $reloads)), VoIP_LOG_NOTICE);
  }

  return array_keys ( $reloads);
}

/**
 * Generate a single section of the Asterisk bootstrap (e.g., only "dialplan").
 *
 * Useful when a module knows only its own section changed and wants to avoid
 * regenerating all configs.
 *
 * @param string $section Section name (e.g., "dialplan", "pjsip", "queues")
 * @param array $parameters Optional parameters passed to hook functions
 * @return boolean True if the section was regenerated and reloaded
 */
function asterisk_bootstrap_generate_section ( $section, $parameters = array ())
{
  if ( ! array_key_exists ( $section, $GLOBALS["_asterisk_bootstrap_sections"]))
  {
    writeLog ( "asterisk_bootstrap_generate_section: unknown section \"" . $section . "\"", VoIP_LOG_WARNING);
    return false;
  }

  $config = $GLOBALS["_asterisk_bootstrap_sections"][$section];
  $content = "";
  foreach ( $config["hooks"] as $hook)
  {
    $hookName = "asterisk_" . $section . "_" . $hook;
    if ( framework_has_hook ( $hookName))
    {
      $result = framework_call ( $hookName, $parameters, false, "");
      if ( is_string ( $result))
      {
        $content .= $result;
      }
    }
  }

  if ( write_bootstrap_file ( $config["file"], $content))
  {
    asterisk_exec ( $config["reload"]);
    writeLog ( "Bootstrap section \"" . $section . "\" regenerated: " . $config["reload"], VoIP_LOG_NOTICE);
    return true;
  }
  return false;
}
?>
