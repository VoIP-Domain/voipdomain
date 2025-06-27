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
 * VoIP Domain currencies module install script.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Currencies
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Hook to create Currencies database
 */
framework_add_hook ( "install_db", "currencies_install_db");

/**
 * Function to create currencies database structure.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function currencies_install_db ( $buffer, $parameters)
{
  /**
   * Add basic system tables
   */
  install_add_db_table ( "Currencies", "CREATE TABLE `Currencies` (\n" .
                                       "  `ISO4217` int(2) unsigned NOT NULL,\n" .
                                       "  `Code` char(3) NOT NULL,\n" .
                                       "  `Name` varchar(255) NOT NULL,\n" .
                                       "  `Symbol` varchar(20) NOT NULL,\n" .
                                       "  `NativeSymbol` varchar(20) NOT NULL,\n" .
                                       "  `MajorSingle` varchar(255) NOT NULL,\n" .
                                       "  `MinorSingle` varchar(255) NOT NULL,\n" .
                                       "  `MajorPlural` varchar(255) NOT NULL,\n" .
                                       "  `MinorPlural` varchar(255) NOT NULL,\n" .
                                       "  `Digits` int(1) unsigned NOT NULL,\n" .
                                       "  `Decimals` int(1) unsigned NOT NULL,\n" .
                                       "  `NumToBasic` int(1) unsigned NOT NULL,\n" .
                                       "  PRIMARY KEY (`ISO4217`,`Code`),\n" .
                                       "  UNIQUE KEY `ISO4217` (`ISO4217`),\n" .
                                       "  UNIQUE KEY `Code` (`Code`)\n" .
                                       ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Currencies information';\n");
  install_add_db_table ( "CurrenciesRates", "CREATE TABLE `CurrenciesRates` (\n" .
                                            "  `SourceCurrency` int(2) unsigned NOT NULL,\n" .
                                            "  `TargetCurrency` int(2) unsigned NOT NULL,\n" .
                                            "  `Date` date NOT NULL,\n" .
                                            "  `Value` double(24,12) UNSIGNED NOT NULL,\n" .
                                            "  PRIMARY KEY (`SourceCurrency`,`TargetCurrency`,`Date`),\n" .
                                            "  KEY `SourceCurrency` (`SourceCurrency`),\n" .
                                            "  KEY `TargetCurrency` (`TargetCurrency`),\n" .
                                            "  KEY `Date` (`Date`),\n" .
                                            "  CONSTRAINT `CurrenciesRates_ibfk_1` FOREIGN KEY (`SourceCurrency`) REFERENCES `Currencies` (`ISO4217`) ON DELETE CASCADE ON UPDATE CASCADE,\n" .
                                            "  CONSTRAINT `CurrenciesRates_ibfk_2` FOREIGN KEY (`TargetCurrency`) REFERENCES `Currencies` (`ISO4217`) ON DELETE CASCADE ON UPDATE CASCADE\n" .
                                            ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Currencies exchange values';\n", array ( "Currencies"));

  /**
   * Add basic system triggers
   */
  install_add_db_trigger ( "CurrenciesInsert", "CREATE TRIGGER `CurrenciesInsert` AFTER INSERT ON `Currencies` FOR EACH ROW CALL UpdateCache('Currencies')");
  install_add_db_trigger ( "CurrenciesUpdate", "CREATE TRIGGER `CurrenciesUpdate` AFTER UPDATE ON `Currencies` FOR EACH ROW CALL UpdateCache('Currencies')");
  install_add_db_trigger ( "CurrenciesDelete", "CREATE TRIGGER `CurrenciesDelete` AFTER DELETE ON `Currencies` FOR EACH ROW CALL UpdateCache('Currencies')");
  install_add_db_trigger ( "CurrenciesRatesInsert", "CREATE TRIGGER `CurrenciesRatesInsert` AFTER INSERT ON `CurrenciesRates` FOR EACH ROW CALL UpdateCache('CurrenciesRates')");
  install_add_db_trigger ( "CurrenciesRatesUpdate", "CREATE TRIGGER `CurrenciesRatesUpdate` AFTER UPDATE ON `CurrenciesRates` FOR EACH ROW CALL UpdateCache('CurrenciesRates')");
  install_add_db_trigger ( "CurrenciesRatesDelete", "CREATE TRIGGER `CurrenciesRatesDelete` AFTER DELETE ON `CurrenciesRates` FOR EACH ROW CALL UpdateCache('CurrenciesRates')");

  /**
   * Add basic system tables data
   */
  install_add_db_data ( "Currencies", array (
    array ( "ISO4217" => 8, "Code" => "ALL", "Name" => "Albanian Lek", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Lek", "MinorSingle" => "Qindarka", "MajorPlural" => "Lekë", "MinorPlural" => "Qindarka", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 12, "Code" => "DZD", "Name" => "Algerian Dinar", "Symbol" => "DA", "NativeSymbol" => "د.ج.", "MajorSingle" => "Dinar", "MinorSingle" => "Santeem", "MajorPlural" => "Dinars", "MinorPlural" => "Santeems", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 32, "Code" => "ARS", "Name" => "Argentine Peso", "Symbol" => "AR$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 36, "Code" => "AUD", "Name" => "Australian Dollar", "Symbol" => "AU$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 44, "Code" => "BSD", "Name" => "Bahamian Dollar", "Symbol" => "$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 48, "Code" => "BHD", "Name" => "Bahraini Dinar", "Symbol" => "BD", "NativeSymbol" => "د.ب.", "MajorSingle" => "Dinar", "MinorSingle" => "Fils", "MajorPlural" => "Dinars", "MinorPlural" => "Fils", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 50, "Code" => "BDT", "Name" => "Bangladeshi Taka", "Symbol" => "৳", "NativeSymbol" => "৳", "MajorSingle" => "Taka", "MinorSingle" => "Poisha", "MajorPlural" => "Taka", "MinorPlural" => "Poisha", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 51, "Code" => "AMD", "Name" => "Armenian Dram", "Symbol" => "֏", "NativeSymbol" => "դր", "MajorSingle" => "Dram", "MinorSingle" => "Luma", "MajorPlural" => "Dram", "MinorPlural" => "Luma", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 52, "Code" => "BBD", "Name" => "Barbadian Dollar", "Symbol" => "BBD$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 60, "Code" => "BMD", "Name" => "Bermudian Dollar", "Symbol" => "$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 64, "Code" => "BTN", "Name" => "Bhutanese Ngultrum", "Symbol" => "Nu.", "NativeSymbol" => "Nu.", "MajorSingle" => "Ngultrum", "MinorSingle" => "Chetrum", "MajorPlural" => "Ngultrums", "MinorPlural" => "Chetrums", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 68, "Code" => "BOB", "Name" => "Bolivian Boliviano", "Symbol" => "Bs.", "NativeSymbol" => "Bs.", "MajorSingle" => "Boliviano", "MinorSingle" => "Centavo", "MajorPlural" => "Bolivianos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 72, "Code" => "BWP", "Name" => "Botswana Pula", "Symbol" => "P", "NativeSymbol" => "P", "MajorSingle" => "Pula", "MinorSingle" => "Thebe", "MajorPlural" => "Pula", "MinorPlural" => "Thebe", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 84, "Code" => "BZD", "Name" => "Belize Dollar", "Symbol" => "BZ$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 90, "Code" => "SBD", "Name" => "Solomon Islands Dollar", "Symbol" => "SI$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 96, "Code" => "BND", "Name" => "Brunei Dollar", "Symbol" => "B$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 104, "Code" => "MMK", "Name" => "Myanmar Kyat", "Symbol" => "Ks", "NativeSymbol" => "Ks", "MajorSingle" => "Kyat", "MinorSingle" => "Pya", "MajorPlural" => "Kyat", "MinorPlural" => "Pya", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 108, "Code" => "BIF", "Name" => "Burundian Franc", "Symbol" => "FBu", "NativeSymbol" => "FBu", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 116, "Code" => "KHR", "Name" => "Cambodian Riel", "Symbol" => "៛", "NativeSymbol" => "៛", "MajorSingle" => "Riel", "MinorSingle" => "Sen", "MajorPlural" => "Riels", "MinorPlural" => "Sen", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 124, "Code" => "CAD", "Name" => "Canadian Dollar", "Symbol" => "CA$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 132, "Code" => "CVE", "Name" => "Cabo Verdean Escudo", "Symbol" => "CV$", "NativeSymbol" => "$", "MajorSingle" => "Escudo", "MinorSingle" => "Centavo", "MajorPlural" => "Escudo", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 136, "Code" => "KYD", "Name" => "Cayman Islands Dollar", "Symbol" => "CI$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 144, "Code" => "LKR", "Name" => "Sri Lankan Rupee", "Symbol" => "Rs.", "NativeSymbol" => "රු or ரூ", "MajorSingle" => "Rupee", "MinorSingle" => "Cent", "MajorPlural" => "Rupees", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 152, "Code" => "CLP", "Name" => "Chilean Peso", "Symbol" => "CL$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 0, "Decimals" => 0, "NumToBasic" => 100),
    array ( "ISO4217" => 156, "Code" => "CNY", "Name" => "Chinese Yuan", "Symbol" => "CN¥", "NativeSymbol" => "¥元", "MajorSingle" => "Yuan", "MinorSingle" => "Fen", "MajorPlural" => "Yuan", "MinorPlural" => "Fen", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 170, "Code" => "COP", "Name" => "Colombian Peso", "Symbol" => "CO$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 174, "Code" => "KMF", "Name" => "Comorian Franc", "Symbol" => "CF", "NativeSymbol" => "CF", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 188, "Code" => "CRC", "Name" => "Costa Rican Colon", "Symbol" => "₡", "NativeSymbol" => "₡", "MajorSingle" => "Colón", "MinorSingle" => "Centimo", "MajorPlural" => "Colones", "MinorPlural" => "Centimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 191, "Code" => "HRK", "Name" => "Croatian Kuna", "Symbol" => "kn", "NativeSymbol" => "kn", "MajorSingle" => "Kuna", "MinorSingle" => "Lipa", "MajorPlural" => "Kuna", "MinorPlural" => "Lipa", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 192, "Code" => "CUP", "Name" => "Cuban Peso", "Symbol" => "\$MN", "NativeSymbol" => "₱", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 203, "Code" => "CZK", "Name" => "Czech Koruna", "Symbol" => "Kč", "NativeSymbol" => "Kč", "MajorSingle" => "Koruna", "MinorSingle" => "Haléř", "MajorPlural" => "Koruny", "MinorPlural" => "Haléř", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 208, "Code" => "DKK", "Name" => "Danish Krone", "Symbol" => "kr.", "NativeSymbol" => "kr.", "MajorSingle" => "Krone", "MinorSingle" => "Øre", "MajorPlural" => "Kroner", "MinorPlural" => "Øre", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 214, "Code" => "DOP", "Name" => "Dominican Peso", "Symbol" => "RD$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 222, "Code" => "SVC", "Name" => "Salvadoran Colón", "Symbol" => "₡", "NativeSymbol" => "₡", "MajorSingle" => "Colón", "MinorSingle" => "Centavo", "MajorPlural" => "Colones", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 230, "Code" => "ETB", "Name" => "Ethiopian Birr", "Symbol" => "Br", "NativeSymbol" => "ብር", "MajorSingle" => "Birr", "MinorSingle" => "Santim", "MajorPlural" => "Birr", "MinorPlural" => "Santim", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 232, "Code" => "ERN", "Name" => "Eritrean Nakfa", "Symbol" => "Nkf", "NativeSymbol" => "ناكفا", "MajorSingle" => "Nakfa", "MinorSingle" => "Cent", "MajorPlural" => "Nakfa", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 238, "Code" => "FKP", "Name" => "Falkland Islands Pound", "Symbol" => "FK£", "NativeSymbol" => "£", "MajorSingle" => "Pound", "MinorSingle" => "Penny", "MajorPlural" => "Pounds", "MinorPlural" => "Pence", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 242, "Code" => "FJD", "Name" => "Fijian Dollar", "Symbol" => "FJ$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 262, "Code" => "DJF", "Name" => "Djiboutian Franc", "Symbol" => "Fdj", "NativeSymbol" => "ف.ج.", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 270, "Code" => "GMD", "Name" => "Gambian Dalasi", "Symbol" => "D", "NativeSymbol" => "D", "MajorSingle" => "Dalasi", "MinorSingle" => "Butut", "MajorPlural" => "Dalasis", "MinorPlural" => "Bututs", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 292, "Code" => "GIP", "Name" => "Gibraltar Pound", "Symbol" => "£", "NativeSymbol" => "£", "MajorSingle" => "Pound", "MinorSingle" => "Penny", "MajorPlural" => "Pounds", "MinorPlural" => "Pence", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 320, "Code" => "GTQ", "Name" => "Guatemalan Quetzal", "Symbol" => "Q", "NativeSymbol" => "$", "MajorSingle" => "Quetzal", "MinorSingle" => "Centavo", "MajorPlural" => "Quetzales", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 324, "Code" => "GNF", "Name" => "Guinean Franc", "Symbol" => "FG", "NativeSymbol" => "FG", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 328, "Code" => "GYD", "Name" => "Guyanese Dollar", "Symbol" => "G$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 332, "Code" => "HTG", "Name" => "Haitian Gourde", "Symbol" => "G", "NativeSymbol" => "G", "MajorSingle" => "Gourde", "MinorSingle" => "Centime", "MajorPlural" => "Gourdes", "MinorPlural" => "Centimes", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 340, "Code" => "HNL", "Name" => "Honduran Lempira", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Lempira", "MinorSingle" => "Centavo", "MajorPlural" => "Lempiras", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 344, "Code" => "HKD", "Name" => "Hong Kong Dollar", "Symbol" => "HK$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 348, "Code" => "HUF", "Name" => "Hungarian Forint", "Symbol" => "Ft", "NativeSymbol" => "Ft", "MajorSingle" => "Forint", "MinorSingle" => "fillér", "MajorPlural" => "Forint", "MinorPlural" => "fillér", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 352, "Code" => "ISK", "Name" => "Icelandic Krona", "Symbol" => "kr", "NativeSymbol" => "kr", "MajorSingle" => "Krona", "MinorSingle" => "Aurar", "MajorPlural" => "Krónur", "MinorPlural" => "Aurar", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 356, "Code" => "INR", "Name" => "Indian Rupee", "Symbol" => "Rs.", "NativeSymbol" => "₹", "MajorSingle" => "Rupee", "MinorSingle" => "Paisa", "MajorPlural" => "Rupees", "MinorPlural" => "Paise", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 360, "Code" => "IDR", "Name" => "Indonesian Rupiah", "Symbol" => "Rp", "NativeSymbol" => "Rp", "MajorSingle" => "Rupiah", "MinorSingle" => "Sen", "MajorPlural" => "Rupiah", "MinorPlural" => "Sen", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 364, "Code" => "IRR", "Name" => "Iranian Rial", "Symbol" => "﷼", "NativeSymbol" => "﷼", "MajorSingle" => "Rial", "MinorSingle" => "Dinar", "MajorPlural" => "Rials", "MinorPlural" => "Dinars", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 368, "Code" => "IQD", "Name" => "Iraqi Dinar", "Symbol" => "د.ع.", "NativeSymbol" => "د.ع.", "MajorSingle" => "Dinar", "MinorSingle" => "Fils", "MajorPlural" => "Dinars", "MinorPlural" => "Fils", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 376, "Code" => "ILS", "Name" => "Israeli new Shekel", "Symbol" => "₪", "NativeSymbol" => "₪", "MajorSingle" => "Shekel", "MinorSingle" => "Agora", "MajorPlural" => "Shekels", "MinorPlural" => "Agoras", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 388, "Code" => "JMD", "Name" => "Jamaican Dollar", "Symbol" => "J$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 392, "Code" => "JPY", "Name" => "Japanese Yen", "Symbol" => "¥", "NativeSymbol" => "¥", "MajorSingle" => "Yen", "MinorSingle" => "Sen", "MajorPlural" => "Yen", "MinorPlural" => "Sen", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 398, "Code" => "KZT", "Name" => "Kazakhstani Tenge", "Symbol" => "₸", "NativeSymbol" => "₸", "MajorSingle" => "Tenge", "MinorSingle" => "Tıyn", "MajorPlural" => "Tenge", "MinorPlural" => "Tıyn", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 400, "Code" => "JOD", "Name" => "Jordanian Dinar", "Symbol" => "JD", "NativeSymbol" => "د.أ.", "MajorSingle" => "Dinar", "MinorSingle" => "Fils", "MajorPlural" => "Dinars", "MinorPlural" => "Fils", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 404, "Code" => "KES", "Name" => "Kenyan Shilling", "Symbol" => "KSh", "NativeSymbol" => "KSh", "MajorSingle" => "Shilling", "MinorSingle" => "Cent", "MajorPlural" => "Shillings", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 408, "Code" => "KPW", "Name" => "North Korean Won", "Symbol" => "₩", "NativeSymbol" => "₩", "MajorSingle" => "Won", "MinorSingle" => "Chon", "MajorPlural" => "Won", "MinorPlural" => "Chon", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 410, "Code" => "KRW", "Name" => "South Korean Won", "Symbol" => "₩", "NativeSymbol" => "₩", "MajorSingle" => "Won", "MinorSingle" => "Jeon", "MajorPlural" => "Won", "MinorPlural" => "Jeon", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 414, "Code" => "KWD", "Name" => "Kuwaiti Dinar", "Symbol" => "KD", "NativeSymbol" => "د.ك.", "MajorSingle" => "Dinar", "MinorSingle" => "Fils", "MajorPlural" => "Dinars", "MinorPlural" => "Fils", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 417, "Code" => "KGS", "Name" => "Kyrgyzstani Som", "Symbol" => "с", "NativeSymbol" => "с", "MajorSingle" => "Som", "MinorSingle" => "Tyiyn", "MajorPlural" => "Som", "MinorPlural" => "Tyiyn", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 418, "Code" => "LAK", "Name" => "Lao Kip", "Symbol" => "₭N", "NativeSymbol" => "₭", "MajorSingle" => "Kip", "MinorSingle" => "Att", "MajorPlural" => "Kip", "MinorPlural" => "Att", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 422, "Code" => "LBP", "Name" => "Lebanese Pound", "Symbol" => "LL.", "NativeSymbol" => "ل.ل.", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 426, "Code" => "LSL", "Name" => "Lesotho Loti", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Loti", "MinorSingle" => "Sente", "MajorPlural" => "maLoti", "MinorPlural" => "Lisente", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 430, "Code" => "LRD", "Name" => "Liberian Dollar", "Symbol" => "L$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 434, "Code" => "LYD", "Name" => "Libyan Dinar", "Symbol" => "LD", "NativeSymbol" => "ل.د.", "MajorSingle" => "Dinar", "MinorSingle" => "Dirham", "MajorPlural" => "Dinars", "MinorPlural" => "Dirhams", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 446, "Code" => "MOP", "Name" => "Macanese Pataca", "Symbol" => "MOP$", "NativeSymbol" => "MOP$", "MajorSingle" => "Pataca", "MinorSingle" => "Avo", "MajorPlural" => "Patacas", "MinorPlural" => "Avos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 454, "Code" => "MWK", "Name" => "Malawian Kwacha", "Symbol" => "MK", "NativeSymbol" => "MK", "MajorSingle" => "Kwacha", "MinorSingle" => "Tambala", "MajorPlural" => "Kwacha", "MinorPlural" => "Tambala", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 458, "Code" => "MYR", "Name" => "Malaysian Ringgit", "Symbol" => "RM", "NativeSymbol" => "RM", "MajorSingle" => "Ringgit", "MinorSingle" => "Sen", "MajorPlural" => "Ringgit", "MinorPlural" => "Sen", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 462, "Code" => "MVR", "Name" => "Maldivian Rufiyaa", "Symbol" => "MRf", "NativeSymbol" => ".ރ", "MajorSingle" => "Rufiyaa", "MinorSingle" => "laari", "MajorPlural" => "Rufiyaa", "MinorPlural" => "laari", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 480, "Code" => "MUR", "Name" => "Mauritian Rupee", "Symbol" => "Rs.", "NativeSymbol" => "रु ", "MajorSingle" => "Rupee", "MinorSingle" => "Cent", "MajorPlural" => "Rupees", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 484, "Code" => "MXN", "Name" => "Mexican Peso", "Symbol" => "MX$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 496, "Code" => "MNT", "Name" => "Mongolian Tögrög", "Symbol" => "₮", "NativeSymbol" => "₮", "MajorSingle" => "Tögrög", "MinorSingle" => "möngö", "MajorPlural" => "Tögrög", "MinorPlural" => "möngö", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 498, "Code" => "MDL", "Name" => "Moldovan Leu", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Leu", "MinorSingle" => "Ban", "MajorPlural" => "Lei", "MinorPlural" => "Bani", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 504, "Code" => "MAD", "Name" => "Moroccan Dirham", "Symbol" => "DH", "NativeSymbol" => "د.م.", "MajorSingle" => "Dirham", "MinorSingle" => "Centime", "MajorPlural" => "Dirhams", "MinorPlural" => "Centimes", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 512, "Code" => "OMR", "Name" => "Omani Rial", "Symbol" => "OR", "NativeSymbol" => "ر.ع.", "MajorSingle" => "Rial", "MinorSingle" => "Baisa", "MajorPlural" => "Rials", "MinorPlural" => "Baisa", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 516, "Code" => "NAD", "Name" => "Namibian Dollar", "Symbol" => "N$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 524, "Code" => "NPR", "Name" => "Nepalese Rupee", "Symbol" => "Rs.", "NativeSymbol" => "रू", "MajorSingle" => "Rupee", "MinorSingle" => "Paisa", "MajorPlural" => "Rupees", "MinorPlural" => "Paise", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 532, "Code" => "ANG", "Name" => "Netherlands Antillean Guilder", "Symbol" => "ƒ", "NativeSymbol" => "ƒ", "MajorSingle" => "Guilder", "MinorSingle" => "Cent", "MajorPlural" => "Guilders", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 533, "Code" => "AWG", "Name" => "Aruban Florin", "Symbol" => "ƒ", "NativeSymbol" => "ƒ", "MajorSingle" => "Florin", "MinorSingle" => "Cent", "MajorPlural" => "Florin", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 548, "Code" => "VUV", "Name" => "Vanuatu Vatu", "Symbol" => "VT", "NativeSymbol" => "VT", "MajorSingle" => "Vatu", "MinorSingle" => "", "MajorPlural" => "Vatu", "MinorPlural" => "", "Digits" => 0, "Decimals" => 0, "NumToBasic" => 0),
    array ( "ISO4217" => 554, "Code" => "NZD", "Name" => "New Zealand Dollar", "Symbol" => "NZ$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 558, "Code" => "NIO", "Name" => "Nicaraguan Córdoba", "Symbol" => "C$", "NativeSymbol" => "C$", "MajorSingle" => "Córdoba Oro", "MinorSingle" => "Centavo", "MajorPlural" => "Córdoba Oro", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 566, "Code" => "NGN", "Name" => "Nigerian Naira", "Symbol" => "₦", "NativeSymbol" => "₦", "MajorSingle" => "Naira", "MinorSingle" => "Kobo", "MajorPlural" => "Naira", "MinorPlural" => "Kobo", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 578, "Code" => "NOK", "Name" => "Norwegian Krone", "Symbol" => "kr", "NativeSymbol" => "kr", "MajorSingle" => "Krone", "MinorSingle" => "øre", "MajorPlural" => "Kroner", "MinorPlural" => "øre", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 586, "Code" => "PKR", "Name" => "Pakistani Rupee", "Symbol" => "Rs.", "NativeSymbol" => "Rs", "MajorSingle" => "Rupee", "MinorSingle" => "Paisa", "MajorPlural" => "Rupees", "MinorPlural" => "Paise", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 590, "Code" => "PAB", "Name" => "Panamanian Balboa", "Symbol" => "B/.", "NativeSymbol" => "B/.", "MajorSingle" => "Balboa", "MinorSingle" => "Centésimo", "MajorPlural" => "Balboa", "MinorPlural" => "Centésimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 598, "Code" => "PGK", "Name" => "Papua New Guinean Kina", "Symbol" => "K", "NativeSymbol" => "K", "MajorSingle" => "Kina", "MinorSingle" => "Toea", "MajorPlural" => "Kina", "MinorPlural" => "Toea", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 600, "Code" => "PYG", "Name" => "Paraguayan Guaraní", "Symbol" => "₲", "NativeSymbol" => "₲", "MajorSingle" => "Guaraní", "MinorSingle" => "Centimo", "MajorPlural" => "Guaraníes", "MinorPlural" => "Centimos", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 604, "Code" => "PEN", "Name" => "Peruvian Sol", "Symbol" => "S/.", "NativeSymbol" => "S/.", "MajorSingle" => "Sol", "MinorSingle" => "Céntimo", "MajorPlural" => "Soles", "MinorPlural" => "Céntimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 608, "Code" => "PHP", "Name" => "Philippine Peso", "Symbol" => "₱", "NativeSymbol" => "₱", "MajorSingle" => "Peso", "MinorSingle" => "Sentimo", "MajorPlural" => "Pesos", "MinorPlural" => "Sentimo", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 634, "Code" => "QAR", "Name" => "Qatari Riyal", "Symbol" => "QR", "NativeSymbol" => "ر.ق.", "MajorSingle" => "Riyal", "MinorSingle" => "Dirham", "MajorPlural" => "Riyals", "MinorPlural" => "Dirhams", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 643, "Code" => "RUB", "Name" => "Russian Ruble", "Symbol" => "₽", "NativeSymbol" => "₽", "MajorSingle" => "Ruble", "MinorSingle" => "Kopek", "MajorPlural" => "Rubles", "MinorPlural" => "Kopeks", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 646, "Code" => "RWF", "Name" => "Rwandan Franc", "Symbol" => "FRw", "NativeSymbol" => "R₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 654, "Code" => "SHP", "Name" => "Saint Helena Pound", "Symbol" => "£", "NativeSymbol" => "£", "MajorSingle" => "Pound", "MinorSingle" => "Penny", "MajorPlural" => "Pounds", "MinorPlural" => "Pence", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 682, "Code" => "SAR", "Name" => "Saudi Riyal", "Symbol" => "SR", "NativeSymbol" => "ر.س.", "MajorSingle" => "Riyal", "MinorSingle" => "Halalah", "MajorPlural" => "Riyals", "MinorPlural" => "Halalahs", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 690, "Code" => "SCR", "Name" => "Seychellois Rupee", "Symbol" => "Rs.", "NativeSymbol" => "Rs", "MajorSingle" => "Rupee", "MinorSingle" => "Cent", "MajorPlural" => "Rupees", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 694, "Code" => "SLL", "Name" => "Sierra Leonean Leone", "Symbol" => "Le", "NativeSymbol" => "Le", "MajorSingle" => "Leone", "MinorSingle" => "Cent", "MajorPlural" => "Leones", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 702, "Code" => "SGD", "Name" => "Singapore Dollar", "Symbol" => "S$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 704, "Code" => "VND", "Name" => "Vietnamese Dong", "Symbol" => "₫", "NativeSymbol" => "₫", "MajorSingle" => "Dong", "MinorSingle" => "Hào", "MajorPlural" => "Dong", "MinorPlural" => "Hào", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 10),
    array ( "ISO4217" => 706, "Code" => "SOS", "Name" => "Somali Shilling", "Symbol" => "Sh.So.", "NativeSymbol" => "Ssh", "MajorSingle" => "Shilling", "MinorSingle" => "Senti", "MajorPlural" => "Shillings", "MinorPlural" => "Senti", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 710, "Code" => "ZAR", "Name" => "South African Rand", "Symbol" => "R", "NativeSymbol" => "R", "MajorSingle" => "Rand", "MinorSingle" => "Cent", "MajorPlural" => "Rand", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 728, "Code" => "SSP", "Name" => "South Sudanese Pound", "Symbol" => "SS£", "NativeSymbol" => "SS£", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 748, "Code" => "SZL", "Name" => "Swazi Lilangeni", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Lilangeni", "MinorSingle" => "Cent", "MajorPlural" => "Emalangeni", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 752, "Code" => "SEK", "Name" => "Swedish Krona", "Symbol" => "kr", "NativeSymbol" => "kr", "MajorSingle" => "Krona", "MinorSingle" => "Öre", "MajorPlural" => "Kronor", "MinorPlural" => "Öre", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 756, "Code" => "CHF", "Name" => "Swiss Franc", "Symbol" => "Fr.", "NativeSymbol" => "₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 760, "Code" => "SYP", "Name" => "Syrian Pound", "Symbol" => "LS", "NativeSymbol" => "ل.س.", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 764, "Code" => "THB", "Name" => "Thai Baht", "Symbol" => "฿", "NativeSymbol" => "฿", "MajorSingle" => "Baht", "MinorSingle" => "Satang", "MajorPlural" => "Baht", "MinorPlural" => "Satang", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 776, "Code" => "TOP", "Name" => "Tongan Paʻanga", "Symbol" => "T$", "NativeSymbol" => "PT", "MajorSingle" => "Pa'anga", "MinorSingle" => "Seniti", "MajorPlural" => "Pa'anga", "MinorPlural" => "Seniti", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 780, "Code" => "TTD", "Name" => "Trinidad and Tobago Dollar", "Symbol" => "TT$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 784, "Code" => "AED", "Name" => "United Arab Emirates Dirham", "Symbol" => "د.إ.", "NativeSymbol" => "د.إ.", "MajorSingle" => "Dirham", "MinorSingle" => "Fils", "MajorPlural" => "Dirhams", "MinorPlural" => "Fils", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 788, "Code" => "TND", "Name" => "Tunisian Dinar", "Symbol" => "DT", "NativeSymbol" => "د.ت.", "MajorSingle" => "Dinar", "MinorSingle" => "Millime", "MajorPlural" => "Dinars", "MinorPlural" => "Millime", "Digits" => 3, "Decimals" => 3, "NumToBasic" => 1000),
    array ( "ISO4217" => 800, "Code" => "UGX", "Name" => "Ugandan Shilling", "Symbol" => "USh", "NativeSymbol" => "Sh", "MajorSingle" => "Shilling", "MinorSingle" => "Cent", "MajorPlural" => "Shillings", "MinorPlural" => "Cents", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 807, "Code" => "MKD", "Name" => "Macedonian Denar", "Symbol" => "den", "NativeSymbol" => "ден", "MajorSingle" => "Denar", "MinorSingle" => "Deni", "MajorPlural" => "Denars", "MinorPlural" => "Deni", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 818, "Code" => "EGP", "Name" => "Egyptian Pound", "Symbol" => "E£", "NativeSymbol" => "ج.م.", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 826, "Code" => "GBP", "Name" => "Pound Sterling", "Symbol" => "£", "NativeSymbol" => "£", "MajorSingle" => "Pound", "MinorSingle" => "Penny", "MajorPlural" => "Pounds", "MinorPlural" => "Pence", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 834, "Code" => "TZS", "Name" => "Tanzanian Shilling", "Symbol" => "TSh", "NativeSymbol" => "TSh", "MajorSingle" => "Shilling", "MinorSingle" => "Senti", "MajorPlural" => "Shillings", "MinorPlural" => "Senti", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 840, "Code" => "USD", "Name" => "United States Dollar", "Symbol" => "$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 858, "Code" => "UYU", "Name" => "Uruguayan Peso", "Symbol" => "\$U", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centésimo", "MajorPlural" => "Pesos", "MinorPlural" => "Centésimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 860, "Code" => "UZS", "Name" => "Uzbekistani Som", "Symbol" => "сум", "NativeSymbol" => "сум", "MajorSingle" => "Som", "MinorSingle" => "Tiyin", "MajorPlural" => "Som", "MinorPlural" => "Tiyin", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 882, "Code" => "WST", "Name" => "Samoan Tala", "Symbol" => "T", "NativeSymbol" => "ST", "MajorSingle" => "Tala", "MinorSingle" => "Sene", "MajorPlural" => "Tala", "MinorPlural" => "Sene", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 886, "Code" => "YER", "Name" => "Yemeni Rial", "Symbol" => "YR", "NativeSymbol" => "ر.ي.", "MajorSingle" => "Rial", "MinorSingle" => "Fils", "MajorPlural" => "Rials", "MinorPlural" => "Fils", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 901, "Code" => "TWD", "Name" => "New Taiwan Dollar", "Symbol" => "NT$", "NativeSymbol" => "圓", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 928, "Code" => "VES", "Name" => "Venezuelan Bolívar Soberano", "Symbol" => "Bs.F", "NativeSymbol" => "Bs.F", "MajorSingle" => "Bolívar", "MinorSingle" => "Centimo", "MajorPlural" => "Bolívares", "MinorPlural" => "Centimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 929, "Code" => "MRU", "Name" => "Mauritanian Ouguiya", "Symbol" => "UM", "NativeSymbol" => "أ.م.", "MajorSingle" => "Ouguiya", "MinorSingle" => "Khoums", "MajorPlural" => "Ouguiya", "MinorPlural" => "Khoums", "Digits" => 2, "Decimals" => 0, "NumToBasic" => 5),
    array ( "ISO4217" => 930, "Code" => "STN", "Name" => "Sao Tome and Príncipe Dobra", "Symbol" => "Db", "NativeSymbol" => "Db", "MajorSingle" => "Dobra", "MinorSingle" => "Centimo", "MajorPlural" => "Dobras", "MinorPlural" => "Centimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 931, "Code" => "CUC", "Name" => "Cuban convertible Peso", "Symbol" => "CUC$", "NativeSymbol" => "$", "MajorSingle" => "Peso", "MinorSingle" => "Centavo", "MajorPlural" => "Pesos", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 932, "Code" => "ZWL", "Name" => "Zimbabwean Dollar", "Symbol" => "Z$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 933, "Code" => "BYN", "Name" => "Belarusian Ruble", "Symbol" => "Br", "NativeSymbol" => "руб.", "MajorSingle" => "Ruble", "MinorSingle" => "Kapiejka", "MajorPlural" => "Rubles", "MinorPlural" => "Kapiejka", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 934, "Code" => "TMT", "Name" => "Turkmenistan Manat", "Symbol" => "m.", "NativeSymbol" => "T", "MajorSingle" => "Manat", "MinorSingle" => "Tenge", "MajorPlural" => "Manat", "MinorPlural" => "Tenge", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 936, "Code" => "GHS", "Name" => "Ghanaian Cedi", "Symbol" => "GH₵", "NativeSymbol" => "₵", "MajorSingle" => "Cedi", "MinorSingle" => "Pesewa", "MajorPlural" => "Cedis", "MinorPlural" => "Pesewas", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 938, "Code" => "SDG", "Name" => "Sudanese Pound", "Symbol" => "£SD", "NativeSymbol" => "ج.س.", "MajorSingle" => "Pound", "MinorSingle" => "Qirsh", "MajorPlural" => "Pounds", "MinorPlural" => "Qirsh", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 941, "Code" => "RSD", "Name" => "Serbian Dinar", "Symbol" => "din", "NativeSymbol" => "дин", "MajorSingle" => "Dinar", "MinorSingle" => "Para", "MajorPlural" => "Dinars", "MinorPlural" => "Para", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 943, "Code" => "MZN", "Name" => "Mozambican Metical", "Symbol" => "MTn", "NativeSymbol" => "MT", "MajorSingle" => "Metical", "MinorSingle" => "Centavo", "MajorPlural" => "Meticais", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 944, "Code" => "AZN", "Name" => "Azerbaijani Manat", "Symbol" => "ман", "NativeSymbol" => "₼", "MajorSingle" => "Manat", "MinorSingle" => "Qapik", "MajorPlural" => "Manat", "MinorPlural" => "Qapik", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 946, "Code" => "RON", "Name" => "Romanian Leu", "Symbol" => "L", "NativeSymbol" => "L", "MajorSingle" => "Leu", "MinorSingle" => "Ban", "MajorPlural" => "Lei", "MinorPlural" => "Bani", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 949, "Code" => "TRY", "Name" => "Turkish Lira", "Symbol" => "TL", "NativeSymbol" => "₺", "MajorSingle" => "Lira", "MinorSingle" => "Kuruş", "MajorPlural" => "Lira", "MinorPlural" => "Kuruş", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 950, "Code" => "XAF", "Name" => "Central African CFA Franc BEAC", "Symbol" => "Fr", "NativeSymbol" => "Fr.", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 951, "Code" => "XCD", "Name" => "East Caribbean Dollar", "Symbol" => "$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 952, "Code" => "XOF", "Name" => "West African CFA Franc BCEAO", "Symbol" => "₣", "NativeSymbol" => "₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 953, "Code" => "XPF", "Name" => "CFP Franc (Franc Pacifique)", "Symbol" => "₣", "NativeSymbol" => "₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 0, "Decimals" => 0, "NumToBasic" => 100),
    array ( "ISO4217" => 967, "Code" => "ZMW", "Name" => "Zambian Kwacha", "Symbol" => "ZK", "NativeSymbol" => "ZK", "MajorSingle" => "Kwacha", "MinorSingle" => "Ngwee", "MajorPlural" => "Kwacha", "MinorPlural" => "Ngwee", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 968, "Code" => "SRD", "Name" => "Surinamese Dollar", "Symbol" => "Sr$", "NativeSymbol" => "$", "MajorSingle" => "Dollar", "MinorSingle" => "Cent", "MajorPlural" => "Dollars", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 969, "Code" => "MGA", "Name" => "Malagasy Ariary", "Symbol" => "Ar", "NativeSymbol" => "Ar", "MajorSingle" => "Ariary", "MinorSingle" => "Iraimbilanja", "MajorPlural" => "Ariary", "MinorPlural" => "Iraimbilanja", "Digits" => 2, "Decimals" => 0, "NumToBasic" => 5),
    array ( "ISO4217" => 971, "Code" => "AFN", "Name" => "Afghan Afghani", "Symbol" => "Af", "NativeSymbol" => "؋", "MajorSingle" => "Afghani", "MinorSingle" => "Pul", "MajorPlural" => "Afghani", "MinorPlural" => "Pul", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 972, "Code" => "TJS", "Name" => "Tajikistani Somoni", "Symbol" => "SM", "NativeSymbol" => "SM", "MajorSingle" => "Somoni", "MinorSingle" => "Diram", "MajorPlural" => "Somoni", "MinorPlural" => "Diram", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 973, "Code" => "AOA", "Name" => "Angolan Kwanza", "Symbol" => "Kz", "NativeSymbol" => "Kz", "MajorSingle" => "Kwanza", "MinorSingle" => "Centimo", "MajorPlural" => "Kwanza", "MinorPlural" => "Centimos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 975, "Code" => "BGN", "Name" => "Bulgarian Lev", "Symbol" => "лв.", "NativeSymbol" => "лв.", "MajorSingle" => "Lev", "MinorSingle" => "Stotinka", "MajorPlural" => "Leva", "MinorPlural" => "Stotinki", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 976, "Code" => "CDF", "Name" => "Congolese Franc", "Symbol" => "FC", "NativeSymbol" => "₣", "MajorSingle" => "Franc", "MinorSingle" => "Centime", "MajorPlural" => "Francs", "MinorPlural" => "Centimes", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 977, "Code" => "BAM", "Name" => "Bosnia and Herzegovina Convertible Mark", "Symbol" => "KM", "NativeSymbol" => "КМ", "MajorSingle" => "Convertible Mark", "MinorSingle" => "Fening", "MajorPlural" => "Marks", "MinorPlural" => "Fening", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 978, "Code" => "EUR", "Name" => "Euro", "Symbol" => "€", "NativeSymbol" => "€", "MajorSingle" => "Euro", "MinorSingle" => "Cent", "MajorPlural" => "Euros", "MinorPlural" => "Cents", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 980, "Code" => "UAH", "Name" => "Ukrainian Hryvnia", "Symbol" => "₴", "NativeSymbol" => "грн", "MajorSingle" => "Hryvnia", "MinorSingle" => "Kopiyka", "MajorPlural" => "Hryvnias", "MinorPlural" => "kopiyky", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 981, "Code" => "GEL", "Name" => "Georgian Lari", "Symbol" => "₾", "NativeSymbol" => "₾", "MajorSingle" => "Lari", "MinorSingle" => "Tetri", "MajorPlural" => "Lari", "MinorPlural" => "Tetri", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 985, "Code" => "PLN", "Name" => "Polish Zloty", "Symbol" => "zł", "NativeSymbol" => "zł", "MajorSingle" => "Zloty", "MinorSingle" => "Grosz", "MajorPlural" => "Zlotys", "MinorPlural" => "Groszy", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100),
    array ( "ISO4217" => 986, "Code" => "BRL", "Name" => "Brazilian Real", "Symbol" => "R$", "NativeSymbol" => "R$", "MajorSingle" => "Real", "MinorSingle" => "Centavo", "MajorPlural" => "Reais", "MinorPlural" => "Centavos", "Digits" => 2, "Decimals" => 2, "NumToBasic" => 100)
  ));

  /**
   * Return structured data
   */
  return $buffer;
}
?>
