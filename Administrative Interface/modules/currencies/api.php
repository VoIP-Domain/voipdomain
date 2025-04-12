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
 * VoIP Domain currencies and currencies rates database module API. This module
 * provides the currencies database and rate conversion value between two
 * currencies.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Currencies
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API call to search currencies
 */
framework_add_hook (
  "currencies_search",
  "currencies_search",
  IN_HOOK_NULL,
  array (
    "requests" => array (
      "type" => "object",
      "properties" => array (
        "Filter" => array (
          "type" => "string",
          "description" => __ ( "Filter search with this string. If not provided, return all currencies."),
          "example" => __ ( "filter")
        ),
        "Fields" => array (
          "type" => "string",
          "description" => __ ( "A comma delimited list of fields that should be returned."),
          "default" => "ISO4217,Code,Name,Demonym,Symbol,MajorSingle,MinorSingle,MajorPlural,MinorPlural,Digits,Decimals",
          "example" => "ISO4217,Code,Name,Demonym,Symbol,NativeSymbol,MajorSingle,MinorSingle,MajorPlural,MinorPlural,Digits,Decimals,NumToBasic"
        )
      )
    ),
    "response" => array (
      200 => array (
        "description" => __ ( "An array containing the currencies information."),
        "schema" => array (
          "type" => "array",
          "xml" => array (
            "name" => "responses",
            "wrapped" => true
          ),
          "items" => array (
            "type" => "object",
            "xml" => array (
              "name" => "currency"
            ),
            "properties" => array (
              "ISO4217" => array (
                "type" => "integer",
                "description" => __ ( "The ISO4217 number of the currency."),
                "example" => 986
              ),
              "Code" => array (
                "type" => "string",
                "description" => __ ( "The code of the currency."),
                "minLength" => 3,
                "maxLength" => 3,
                "pattern" => "/^[A-Z]{3}$/",
                "example" => __ ( "BRL")
              ),
              "Name" => array (
                "type" => "string",
                "description" => __ ( "The name of the currency (translated to current request language)."),
                "example" => __ ( "Brazilian Real")
              ),
              "NameEN" => array (
                "type" => "string",
                "description" => __ ( "The english name of the currency."),
                "example" => "Brazilian Real"
              ),
              "Demonym" => array (
                "type" => "string",
                "description" => __ ( "The adjective (demonym) of the currency (translated to current request language)."),
                "example" => __ ( "Brazilian")
              ),
              "DemonymEN" => array (
                "type" => "string",
                "description" => __ ( "The english adjective (demonym) of the currency."),
                "example" => "Brazilian"
              ),
              "Symbol" => array (
                "type" => "string",
                "description" => __ ( "The symbol in Latin form as most internationally used of the currency."),
                "example" => "R$"
              ),
              "NativeSymbol" => array (
                "type" => "string",
                "description" => __ ( "The native language symbol of the currency."),
                "example" => "R$"
              ),
              "MajorSingle" => array (
                "type" => "string",
                "description" => __ ( "The major unit name in single of the currency (translated to current request language)."),
                "example" => __ ( "Real")
              ),
              "MajorSingleEN" => array (
                "type" => "string",
                "description" => __ ( "The english major unit name in single of the currency."),
                "example" => "Real"
              ),
              "MajorPlural" => array (
                "type" => "string",
                "description" => __ ( "The major unit name in plural of the currency (translated to current request language)."),
                "example" => __ ( "Reais")
              ),
              "MajorPluralEN" => array (
                "type" => "string",
                "description" => __ ( "The english unit name in major plural of the currency."),
                "example" => "Reais"
              ),
              "MinorSingle" => array (
                "type" => "string",
                "description" => __ ( "The minor unit name in single of the currency (translated to current request language)."),
                "example" => __ ( "Centavo")
              ),
              "MinorSingleEN" => array (
                "type" => "string",
                "description" => __ ( "The english minor unit name in single of the currency."),
                "example" => "Centavo"
              ),
              "MinorPlural" => array (
                "type" => "string",
                "description" => __ ( "The minor unit name in plural of the currency (translated to current request language)."),
                "example" => __ ( "Centavos")
              ),
              "MinorPluralEN" => array (
                "type" => "string",
                "description" => __ ( "The english minor unit name in plural of the currency."),
                "example" => "Centavos"
              ),
              "Digits" => array (
                "type" => "integer",
                "description" => __ ( "The number of digits after the decimal separator of the currency."),
                "example" => 2
              ),
              "Decimals" => array (
                "type" => "integer",
                "description" => __ ( "The number of decimal places for the minor unit of the currency."),
                "example" => 2
              ),
              "NumToBasic" => array (
                "type" => "string",
                "description" => __ ( "The total number of minor units in a major unit of the currency."),
                "example" => 100
              )
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Filter" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid filter content.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "currencies_search", __ ( "Search currencies"));
framework_add_api_call (
  "/currencies",
  "Read",
  "currencies_search",
  array (
    "permissions" => array ( "user", "token"),
    "title" => __ ( "Search currencies"),
    "description" => __ ( "Search for currencies database.")
  )
);

/**
 * Function to search currencies.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function currencies_search ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "counries_search_start"))
  {
    $parameters = framework_call ( "currencies_search_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Currencies");

  /**
   * Validate received parameters
   */
  $data = array ();

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "currencies_search_validate"))
  {
    $data = framework_call ( "currencies_search_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "currencies_search_sanitize"))
  {
    $parameters = framework_call ( "currencies_search_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "currencies_search_pre"))
  {
    $parameters = framework_call ( "currencies_search_pre", $parameters, false, $parameters);
  }

  /**
   * Search currencies
   */
  $data = array ();
  if ( ! $results = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Currencies`" . ( ! empty ( $parameters["Filter"]) ? " WHERE `Name` LIKE '%" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "%' OR `ISO4217` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "' OR `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Filter"]) . "'" : "") . " ORDER BY `Name`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Create result structure
   */
  $data = array ();
  $fields = api_filter_fields ( $parameters["Fields"], "ISO4217,Code,Name,Demonym,Symbol,MajorSingle,MinorSingle,MajorPlural,MinorPlural,Digits,Decimals", "ISO4217,Code,Name,NameEN,Demonym,DemonymEN,Symbol,NativeSymbol,MajorSingle,MajorSingleEN,MinorSingle,MinorSingleEN,MajorPlural,MajorPluralEN,MinorPlural,MinorPluralEN,Digits,Decimals,NumToBasic");
  while ( $result = $results->fetch_assoc ())
  {
    $result["NameEN"] = $result["Name"];
    $result["Name"] = __ ( $result["Name"], true, false);
    $result["DemonymEN"] = $result["Demonym"];
    $result["Demonym"] = __ ( $result["Demonym"], true, false);
    $result["MajorSingleEN"] = $result["MajorSingle"];
    $result["MajorSingle"] = __ ( $result["MajorSingle"], true, false);
    $result["MajorPluralEN"] = $result["MajorPlural"];
    $result["MajorPlural"] = __ ( $result["MajorPlural"], true, false);
    $result["MinorSingleEN"] = $result["MinorSingle"];
    $result["MinorSingle"] = __ ( $result["MinorSingle"], true, false);
    $result["MinorPluralEN"] = $result["MinorPlural"];
    $result["MinorPlural"] = __ ( $result["MinorPlural"], true, false);
    $data[] = api_filter_entry ( $fields, $result);
  }

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "currencies_search_post"))
  {
    $data = framework_call ( "currencies_search_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "currencies_search_finish"))
  {
    framework_call ( "currencies_search_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * API call to get currency information
 */
framework_add_hook (
  "currencies_view",
  "currencies_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing the currency information."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "ISO4217" => array (
              "type" => "integer",
              "description" => __ ( "The ISO4217 number of the currency."),
              "example" => 986
            ),
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The code of the currency."),
              "minLength" => 3,
              "maxLength" => 3,
              "pattern" => "/^[A-Z]{3}$/",
              "example" => __ ( "BRL")
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name of the currency (translated to current request language)."),
              "example" => __ ( "Brazilian Real")
            ),
            "NameEN" => array (
              "type" => "string",
              "description" => __ ( "The english name of the currency."),
              "example" => "Brazilian Real"
            ),
            "Demonym" => array (
              "type" => "string",
              "description" => __ ( "The adjective (demonym) of the currency (translated to current request language)."),
              "example" => __ ( "Brazilian")
            ),
            "DemonymEN" => array (
              "type" => "string",
              "description" => __ ( "The english adjective (demonym) of the currency."),
              "example" => "Brazilian"
            ),
            "Symbol" => array (
              "type" => "string",
              "description" => __ ( "The symbol in Latin form as most internationally used of the currency."),
              "example" => "R$"
            ),
            "NativeSymbol" => array (
              "type" => "string",
              "description" => __ ( "The native language symbol of the currency."),
              "example" => "R$"
            ),
            "MajorSingle" => array (
              "type" => "string",
              "description" => __ ( "The major unit name in single of the currency (translated to current request language)."),
              "example" => __ ( "Real")
            ),
            "MajorSingleEN" => array (
              "type" => "string",
              "description" => __ ( "The english major unit name in single of the currency."),
              "example" => "Real"
            ),
            "MajorPlural" => array (
              "type" => "string",
              "description" => __ ( "The major unit name in plural of the currency (translated to current request language)."),
              "example" => __ ( "Reais")
            ),
            "MajorPluralEN" => array (
              "type" => "string",
              "description" => __ ( "The english unit name in major plural of the currency."),
              "example" => "Reais"
            ),
            "MinorSingle" => array (
              "type" => "string",
              "description" => __ ( "The minor unit name in single of the currency (translated to current request language)."),
              "example" => __ ( "Centavo")
            ),
            "MinorSingleEN" => array (
              "type" => "string",
              "description" => __ ( "The english minor unit name in single of the currency."),
              "example" => "Centavo"
            ),
            "MinorPlural" => array (
              "type" => "string",
              "description" => __ ( "The minor unit name in plural of the currency (translated to current request language)."),
              "example" => __ ( "Centavos")
            ),
            "MinorPluralEN" => array (
              "type" => "string",
              "description" => __ ( "The english minor unit name in plural of the currency."),
              "example" => "Centavos"
            ),
            "Digits" => array (
              "type" => "integer",
              "description" => __ ( "The number of digits after the decimal separator of the currency."),
              "example" => 2
            ),
            "Decimals" => array (
              "type" => "integer",
              "description" => __ ( "The number of decimal places for the minor unit of the currency."),
              "example" => 2
            ),
            "NumToBasic" => array (
              "type" => "string",
              "description" => __ ( "The total number of minor units in a major unit of the currency."),
              "example" => 100
            )
          )
        )
      ),
      422 => array (
        "description" => __ ( "An error occurred while processing the request. An object with field name and a text error message will be returned to all inconsistency found."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Code" => array (
              "type" => "string",
              "description" => __ ( "The text description of this field error."),
              "example" => __ ( "Invalid country code.")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "currencies_view", __ ( "View currencies information"));
framework_add_api_call (
  "/currencies/:Code",
  "Read",
  "currencies_view",
  array (
    "permissions" => array ( "user", "token"),
    "title" => __ ( "View currency"),
    "description" => __ ( "View a currency information."),
    "parameters" => array (
      array (
        "name" => "Code",
        "type" => "string",
        "description" => __ ( "The code of currency to be requested."),
        "example" => "BRL"
      )
    )
  )
);

/**
 * Function to generate currency information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function currencies_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Call start hook if exist
   */
  if ( framework_has_hook ( "currencies_view_start"))
  {
    $parameters = framework_call ( "currencies_view_start", $parameters);
  }

  /**
   * Check for modifications time
   */
  check_table_modification ( "Currencies");

  /**
   * Validate received parameters
   */
  $data = array ();
  if ( ! array_key_exists ( "Code", $parameters) || ! is_numeric ( $parameters["Code"]))
  {
    $data["Code"] = __ ( "Invalid country code.");
  }

  /**
   * Call validate hook if exist
   */
  if ( framework_has_hook ( "currencies_view_validate"))
  {
    $data = framework_call ( "currencies_view_validate", $parameters);
  }

  /**
   * Return error data if some error occurred
   */
  if ( sizeof ( $data) != 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 422 Unprocessable Entity");
    return $data;
  }

  /**
   * Sanitize parameters
   */
  $parameters["Code"] = str_replace ( " ", "", $parameters["Code"]);

  /**
   * Call sanitize hook if exist
   */
  if ( framework_has_hook ( "currencies_view_sanitize"))
  {
    $parameters = framework_call ( "currencies_view_sanitize", $parameters, false, $parameters);
  }

  /**
   * Call pre hook if exist
   */
  if ( framework_has_hook ( "currencies_view_pre"))
  {
    $parameters = framework_call ( "currencies_view_pre", $parameters, false, $parameters);
  }

  /**
   * Search currency
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Currencies` WHERE `Code` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Code"]) . "'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $currency = $result->fetch_assoc ();

  /**
   * Filter return fields
   */
  $currency["NameEN"] = $currency["Name"];
  $currency["Name"] = __ ( $currency["Name"], true, false);
  $currency["DemonymEN"] = $currency["Demonym"];
  $currency["Demonym"] = __ ( $currency["Demonym"], true, false);
  $currency["MajorSingleEN"] = $currency["MajorSingle"];
  $currency["MajorSingle"] = __ ( $currency["MajorSingle"], true, false);
  $currency["MajorPluralEN"] = $currency["MajorPlural"];
  $currency["MajorPlural"] = __ ( $currency["MajorPlural"], true, false);
  $currency["MinorSingleEN"] = $currency["MinorSingle"];
  $currency["MinorSingle"] = __ ( $currency["MinorSingle"], true, false);
  $currency["MinorPluralEN"] = $currency["MinorPlural"];
  $currency["MinorPlural"] = __ ( $currency["MinorPlural"], true, false);
  $data = api_filter_entry ( array ( "ISO4217", "Code", "Name", "NameEN", "Demonym", "DemonymEN", "Symbol", "NativeSymbol", "MajorSingle", "MajorSingleEN", "MinorSingle", "MinorSingleEN", "MajorPlural", "MajorPluralEN", "MinorPlural", "MinorPluralEN", "Digits", "Decimals", "NumToBasic"), $currency);

  /**
   * Call post hook if exist
   */
  if ( framework_has_hook ( "currencies_view_post"))
  {
    $data = framework_call ( "currencies_view_post", $parameters, false, $data);
  }

  /**
   * Execute finish hook if exist
   */
  if ( framework_has_hook ( "currencies_view_finish"))
  {
    framework_call ( "currencies_view_finish", $parameters);
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}
?>
