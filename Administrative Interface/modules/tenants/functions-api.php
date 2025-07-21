<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2025 Ernani José Camargo Azevedo
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
 * VoIP Domain multi-tenant module functions. This module add the functions
 * related to multi-tenant.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Multi-Tenant
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Function to fetch current tenant ID.
 *
 * @global array $_in Framework global configuration variable
 * @param string $domain[optional] Use this domain instead of server hostname
 * @return int ID of current tenant (0 if not found)
 */
function get_tenant ( $domain = "")
{
  global $_in;

  if ( $domain == "")
  {
    return $_in["session"]["Tenant"];
  }

  /**
   * Search domain hostname in Tenant table
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Tenants` WHERE `Domain` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $domain) . "' AND `Status` = 'Active'"))
  {
    return 0;
  }
  $tenant = 0;
  while ( $data = $result->fetch_assoc ())
  {
    if ( $tenant == 0)
    {
      $tenant = $data["ID"];
    } else {
      if ( $data["Domain"] == $domain)
      {
        $tenant = $data["ID"];
      }
    }
  }

  return $tenant;
}
?>
