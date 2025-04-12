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
 * VoIP Domain extensions reserves module filters. This module add the api calls
 * related to extensions reserves.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Reserves
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API hook to extend extensions addition of reserve type
 */
framework_add_function_documentation (
  "extensions_search",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "reserve"),
          "example" => "reserve"
        )
      )
    ),
    "response" => array (
      200 => array (
        "schema" => array (
          "items" => array (
            "properties" => array (
              "Type" => array (
                "enum" => array ( "reserve"),
                "example" => "reserve"
              )
            )
          )
        )
      )
    )
  )
);
framework_add_function_documentation (
  "extensions_view",
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "properties" => array (
            "Type" => array (
              "enum" => array ( "reserve"),
              "example" => array ( "reserve")
            ),
            "oneOf" => array (
              array (
                "type" => "null",
                "description" => __ ( "An object with reserve information.")
              )
            )
          )
        )
      )
    )
  )
);
framework_add_function_documentation (
  "extensions_add",
  array (
    "requests" => array (
      "properties" => array (
        "oneOf" => array (
          array (
            "type" => "null",
            "required" => true
          )
        )
      ),
      "properties" => array (
        "Type" => array (
          "enum" => array ( "reserve")
        )
      )
    )
  )
);
framework_add_function_documentation (
  "extensions_edit",
  array (
    "requests" => array (
      "properties" => array (
        "oneOf" => array (
          array (
            "type" => "null",
            "required" => true
          )
        )
      ),
      "properties" => array (
        "Type" => array (
          "enum" => array ( "reserve")
        )
      )
    )
  )
);
?>
