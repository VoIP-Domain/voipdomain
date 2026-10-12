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
 * VoIP Domain timezone picker UI helpers (Administrative Interface).
 *
 * Generates map and select markup from tz_world.txt / tz_islands.txt with
 * standard-time offsets from the host tzdata (winter reference date).
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

require_once ( dirname ( __FILE__) . "/../vendors/timezonepicker/includes/parser.inc");

/**
 * Map deprecated timezone picker IDs to canonical IANA identifiers.
 *
 * @param string $timezone_id Timezone identifier from tz_world.txt
 * @return string
 */
function voipdomain_timezonepicker_canonical ( $timezone_id)
{
  static $aliases = array (
    "America/Coral_Harbour" => "America/Atikokan",
    "America/Godthab" => "America/Nuuk",
    "America/Montreal" => "America/Toronto",
    "America/Nipigon" => "America/Toronto",
    "America/Pangnirtung" => "America/Iqaluit",
    "America/Rainy_River" => "America/Winnipeg",
    "America/Santa_Isabel" => "America/Tijuana",
    "America/Shiprock" => "America/Denver",
    "America/Thunder_Bay" => "America/Toronto",
    "America/Yellowknife" => "America/Edmonton",
    "Asia/Choibalsan" => "Asia/Ulaanbaatar",
    "Asia/Chongqing" => "Asia/Shanghai",
    "Asia/Harbin" => "Asia/Shanghai",
    "Asia/Kashgar" => "Asia/Urumqi",
    "Asia/Rangoon" => "Asia/Yangon",
    "Australia/Currie" => "Australia/Hobart",
    "Europe/Kiev" => "Europe/Kyiv",
    "Europe/Uzhgorod" => "Europe/Kyiv",
    "Europe/Zaporozhye" => "Europe/Kyiv",
    "Pacific/Enderbury" => "Pacific/Kanton",
    "Pacific/Johnston" => "Pacific/Honolulu",
  );

  if ( isset ( $aliases[$timezone_id]))
  {
    return $aliases[$timezone_id];
  }

  return $timezone_id;
}

/**
 * Winter reference instant (no DST) for the current calendar year.
 *
 * @return DateTime
 */
function voipdomain_timezonepicker_reference_date ()
{
  static $reference = NULL;

  if ( $reference === NULL)
  {
    $reference = date_create ( gmdate ( "Y") . "-01-01T00:00:00Z");
  }

  return $reference;
}

/**
 * Standard-time UTC offset in hours for a timezone identifier.
 *
 * @param string $timezone_id IANA timezone identifier
 * @return float|null
 */
function voipdomain_timezonepicker_standard_offset ( $timezone_id)
{
  $timezone_id = voipdomain_timezonepicker_canonical ( $timezone_id);
  $timezone = @timezone_open ( $timezone_id);

  if ( ! $timezone)
  {
    return NULL;
  }

  return round ( timezone_offset_get ( $timezone, voipdomain_timezonepicker_reference_date ()) / 3600, 1);
}

/**
 * Normalize TimeZone and Offset fields from authoritative tzdata.
 *
 * @param array $parameters Request parameters (modified in place)
 * @return void
 */
function voipdomain_timezonepicker_apply_parameters ( &$parameters)
{
  if ( empty ( $parameters["TimeZone"]))
  {
    return;
  }

  $canonical = voipdomain_timezonepicker_canonical ( $parameters["TimeZone"]);

  if ( ! @timezone_open ( $canonical))
  {
    return;
  }

  $parameters["TimeZone"] = $canonical;
  $offset = voipdomain_timezonepicker_standard_offset ( $canonical);

  if ( $offset !== NULL)
  {
    $parameters["Offset"] = $offset;
  }
}

/**
 * Human-readable label for a timezone select option.
 *
 * @param string $timezone_id IANA timezone identifier
 * @return string
 */
function voipdomain_timezonepicker_label ( $timezone_id)
{
  $parts = explode ( "/", $timezone_id);
  $last = str_replace ( "_", " ", end ( $parts));

  if ( count ( $parts) > 2)
  {
    $region = str_replace ( "_", " ", $parts[1]);

    return $parts[0] . "/" . $region . "/" . $last;
  }

  return $parts[0] . "/" . $last;
}

/**
 * Parsed picker zones keyed by canonical identifier (cached per request).
 *
 * @return array
 */
function voipdomain_timezonepicker_zones ()
{
  static $zones = NULL;

  if ( $zones !== NULL)
  {
    return $zones;
  }

  $picker_dir = dirname ( __FILE__) . "/../vendors/timezonepicker";
  $map_width = 600;
  $map_height = 300;
  $raw = timezone_picker_parse_files (
    $map_width,
    $map_height,
    $picker_dir . "/tz_world.txt",
    $picker_dir . "/tz_islands.txt"
  );

  $zones = array ();

  foreach ( $raw as $map_id => $info)
  {
    if ( $info["offset"] === NULL)
    {
      continue;
    }

    $canonical = voipdomain_timezonepicker_canonical ( $map_id);
    $offset = voipdomain_timezonepicker_standard_offset ( $canonical);

    if ( $offset === NULL)
    {
      continue;
    }

    if ( ! isset ( $zones[$canonical]))
    {
      $timezone = timezone_open ( $canonical);
      $location = timezone_location_get ( $timezone);
      $pin = timezone_picker_convert_xy ( $location["latitude"], $location["longitude"], $map_width, $map_height);

      $zones[$canonical] = array (
        "offset" => $offset,
        "pin" => $pin,
        "country" => $location["country_code"] !== "??" ? $location["country_code"] : NULL,
        "polys" => array (),
        "rects" => array (),
      );
    }

    if ( ! empty ( $info["polys"]))
    {
      $zones[$canonical]["polys"] = array_merge ( $zones[$canonical]["polys"], $info["polys"]);
    }

    if ( ! empty ( $info["rects"]))
    {
      $zones[$canonical]["rects"] = array_merge ( $zones[$canonical]["rects"], $info["rects"]);
    }
  }

  ksort ( $zones, SORT_STRING);

  return $zones;
}

/**
 * Extra select-only zones (no map polygon).
 *
 * @return array
 */
function voipdomain_timezonepicker_extra_select_zones ()
{
  static $extra = NULL;

  if ( $extra !== NULL)
  {
    return $extra;
  }

  $map_ids = array_keys ( voipdomain_timezonepicker_zones ());
  $extra = array ( "UTC");

  foreach ( timezone_identifiers_list () as $timezone_id)
  {
    if ( strpos ( $timezone_id, "Antarctica/") !== 0)
    {
      continue;
    }

    $canonical = voipdomain_timezonepicker_canonical ( $timezone_id);

    if ( in_array ( $canonical, $map_ids, true) || in_array ( $canonical, $extra, true))
    {
      continue;
    }

    if ( voipdomain_timezonepicker_standard_offset ( $canonical) === NULL)
    {
      continue;
    }

    $extra[] = $canonical;
  }

  sort ( $extra, SORT_STRING);

  return $extra;
}

/**
 * HTML fragment: imagemap area tags for the 600x300 living-earth map.
 *
 * @param string $indent Leading whitespace for each line
 * @return string
 */
function voipdomain_timezonepicker_map_markup ( $indent = "            ")
{
  $html = "";

  foreach ( voipdomain_timezonepicker_zones () as $timezone_name => $timezone)
  {
    $country = $timezone["country"] !== NULL ? $timezone["country"] : "";
    $pin = implode ( ",", $timezone["pin"]);
    $offset = $timezone["offset"];

    foreach ( $timezone["polys"] as $coords)
    {
      $html .= $indent . "<area data-timezone=\"" . htmlspecialchars ( $timezone_name, ENT_QUOTES, "UTF-8") . "\" data-country=\"" . htmlspecialchars ( $country, ENT_QUOTES, "UTF-8") . "\" data-pin=\"" . $pin . "\" data-offset=\"" . $offset . "\" shape=\"poly\" coords=\"" . implode ( ",", $coords) . "\" />\n";
    }

    foreach ( $timezone["rects"] as $coords)
    {
      $html .= $indent . "<area data-timezone=\"" . htmlspecialchars ( $timezone_name, ENT_QUOTES, "UTF-8") . "\" data-country=\"" . htmlspecialchars ( $country, ENT_QUOTES, "UTF-8") . "\" data-pin=\"" . $pin . "\" data-offset=\"" . $offset . "\" shape=\"rect\" coords=\"" . implode ( ",", $coords) . "\" />\n";
    }
  }

  return $html;
}

/**
 * HTML fragment: option elements for timezone select lists.
 *
 * @param string $indent Leading whitespace for each line
 * @return string
 */
function voipdomain_timezonepicker_select_markup ( $indent = "            ")
{
  $html = "";

  foreach ( voipdomain_timezonepicker_zones () as $timezone_name => $timezone)
  {
    $label = voipdomain_timezonepicker_label ( $timezone_name);
    $html .= $indent . "<option value=\"" . htmlspecialchars ( $timezone_name, ENT_QUOTES, "UTF-8") . "\">" . htmlspecialchars ( $label, ENT_QUOTES, "UTF-8") . "</option>\n";
  }

  foreach ( voipdomain_timezonepicker_extra_select_zones () as $timezone_name)
  {
    $label = voipdomain_timezonepicker_label ( $timezone_name);
    $html .= $indent . "<option value=\"" . htmlspecialchars ( $timezone_name, ENT_QUOTES, "UTF-8") . "\">" . htmlspecialchars ( $label, ENT_QUOTES, "UTF-8") . "</option>\n";
  }

  return $html;
}
