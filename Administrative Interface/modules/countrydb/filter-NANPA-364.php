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
 * VoIP Domain country database module filters. This module add the filter calls
 * related to country database of United States of America.
 *
 * Reference: https://www.itu.int/oth/T02020000DE/en (2006-11-22)
 *            https://www.nationalpooling.com/reports/region/AllBlocksAugmentedReport.zip (2023-02-13)
 *
 * Glossary:
 *  CC - Country Code
 *  NDC - National Destination Code (also known as area code)
 *  N(S)N - National (Significant) Number
 *  SN - Subscriber Number
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage CountryDB
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * E.164 United States of America NDC 364 country hook
 */
framework_add_filter ( "e164_identify_NANPA_364", "e164_identify_NANPA_364");

/**
 * E.164 North American area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "USA" (code for
 * United States of America). This hook will verify if phone number is valid,
 * returning the area code, area name, phone number, others number related
 * information and if possible, the number type (mobile, landline, Premium Rate
 * Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_NANPA_364 ( $buffer, $parameters)
{
  /**
   * Check if number country code is from United States of America at NDC 364 area
   */
  if ( substr ( $parameters["Number"], 0, 5) != "+1364")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  $prefixes = array (
    "9999" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "TERRA NOVA TELECOM INC."),
    "9998" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "TELNYX LLC"),
    "9997" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "9996" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "9995" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "9994" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "9993" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "9992" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "9991" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "9990" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "9105" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "ONVOY, LLC - KY"),
    "9104" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "ONVOY, LLC - KY"),
    "9103" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "ONVOY, LLC - KY"),
    "9100" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "RADIANTIQ LLC"),
    "9009" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "FRACTEL, LLC"),
    "9008" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "ONVOY, LLC - KY"),
    "9007" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "ONVOY, LLC - KY"),
    "9006" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "ONVOY, LLC - KY"),
    "9005" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "ONVOY, LLC - KY"),
    "9004" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "ONVOY, LLC - KY"),
    "9003" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "ONVOY, LLC - KY"),
    "9002" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "ONVOY, LLC - KY"),
    "9001" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "TELNYX LLC"),
    "9000" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "FRACTEL, LLC"),
    "8958" => array ( "Area" => "Kentucky", "City" => "Madisonville", "Operator" => "TWILIO INTERNATIONAL, INC."),
    "8889" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "8888" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "8887" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "8886" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "8885" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "8884" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "8883" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "8882" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "8881" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "8880" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "VONAGE AMERICA LLC"),
    "8676" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - KY"),
    "8675" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "COMMIO, LLC"),
    "8008" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "FRACTEL, LLC"),
    "8005" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "ONVOY, LLC - KY"),
    "8004" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "ONVOY, LLC - KY"),
    "8001" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "FRACTEL, LLC"),
    "7777" => array ( "Area" => "Kentucky", "City" => "Scottsville Rural", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "7776" => array ( "Area" => "Kentucky", "City" => "Scottsville Rural", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "7774" => array ( "Area" => "Kentucky", "City" => "Scottsville Rural", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - KY"),
    "7771" => array ( "Area" => "Kentucky", "City" => "Scottsville Rural", "Operator" => "DISH WIRELESS, LLC"),
    "7770" => array ( "Area" => "Kentucky", "City" => "Scottsville Rural", "Operator" => "DISH WIRELESS, LLC"),
    "7687" => array ( "Area" => "Kentucky", "City" => "Burkesville", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "7686" => array ( "Area" => "Kentucky", "City" => "Burkesville", "Operator" => "TELEPORT COMMUNICATIONS AMERICA, LLC - KY"),
    "7669" => array ( "Area" => "Kentucky", "City" => "Battletown", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "7667" => array ( "Area" => "Kentucky", "City" => "Battletown", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "7666" => array ( "Area" => "Kentucky", "City" => "Battletown", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "7665" => array ( "Area" => "Kentucky", "City" => "Battletown", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "7664" => array ( "Area" => "Kentucky", "City" => "Battletown", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "7663" => array ( "Area" => "Kentucky", "City" => "Battletown", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "7662" => array ( "Area" => "Kentucky", "City" => "Battletown", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "7287" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "ONVOY, LLC - KY"),
    "7286" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "ONVOY, LLC - KY"),
    "7285" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "7284" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "7283" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "7282" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "7281" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "7280" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "7109" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "7108" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "7107" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "ONVOY SPECTRUM, LLC"),
    "7106" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "ONVOY SPECTRUM, LLC"),
    "7105" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "ONVOY SPECTRUM, LLC"),
    "7104" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "ONVOY SPECTRUM, LLC"),
    "7103" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "TWILIO INTERNATIONAL, INC."),
    "7102" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "7101" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "7100" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "6646" => array ( "Area" => "Kentucky", "City" => "Madisonville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "6444" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "SKYE TELECOM LLC DBA SKYETEL"),
    "6436" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY SPECTRUM, LLC"),
    "6435" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "ONVOY, LLC - KY"),
    "6434" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "6433" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "6432" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "6431" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "6430" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "5978" => array ( "Area" => "Kentucky", "City" => "Caneyville", "Operator" => "ONVOY, LLC - KY"),
    "5977" => array ( "Area" => "Kentucky", "City" => "Caneyville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "5976" => array ( "Area" => "Kentucky", "City" => "Caneyville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "5975" => array ( "Area" => "Kentucky", "City" => "Caneyville", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "5974" => array ( "Area" => "Kentucky", "City" => "Caneyville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "5973" => array ( "Area" => "Kentucky", "City" => "Caneyville", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "5972" => array ( "Area" => "Kentucky", "City" => "Caneyville", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "5971" => array ( "Area" => "Kentucky", "City" => "Caneyville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "5970" => array ( "Area" => "Kentucky", "City" => "Caneyville", "Operator" => "TIME WARNER CBL INFO SV (KY) DBA TIME WARNER-KY"),
    "5477" => array ( "Area" => "Kentucky", "City" => "Brandenburg", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "5476" => array ( "Area" => "Kentucky", "City" => "Brandenburg", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "5475" => array ( "Area" => "Kentucky", "City" => "Brandenburg", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "5474" => array ( "Area" => "Kentucky", "City" => "Brandenburg", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "5472" => array ( "Area" => "Kentucky", "City" => "Brandenburg", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "5471" => array ( "Area" => "Kentucky", "City" => "Brandenburg", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "5470" => array ( "Area" => "Kentucky", "City" => "Brandenburg", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "5265" => array ( "Area" => "Kentucky", "City" => "Clarkson", "Operator" => "ONVOY SPECTRUM, LLC"),
    "5264" => array ( "Area" => "Kentucky", "City" => "Clarkson", "Operator" => "ONVOY SPECTRUM, LLC"),
    "5263" => array ( "Area" => "Kentucky", "City" => "Clarkson", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "5009" => array ( "Area" => "Kentucky", "City" => "Burkesville Rural", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "5008" => array ( "Area" => "Kentucky", "City" => "Burkesville Rural", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "5005" => array ( "Area" => "Kentucky", "City" => "Burkesville Rural", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "5001" => array ( "Area" => "Kentucky", "City" => "Burkesville Rural", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "5000" => array ( "Area" => "Kentucky", "City" => "Burkesville Rural", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "4888" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "SKYE TELECOM LLC DBA SKYETEL"),
    "4880" => array ( "Area" => "Kentucky", "City" => "Elizabethtown", "Operator" => "ONVOY, LLC - KY"),
    "4646" => array ( "Area" => "Kentucky", "City" => "Owensboro", "Operator" => "ONVOY SPECTRUM, LLC"),
    "4449" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "4448" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "TELNYX LLC"),
    "4447" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "4446" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "4445" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "COMMIO, LLC"),
    "4444" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "ONVOY, LLC - KY"),
    "4443" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "TELNYX LLC"),
    "4442" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "TELNYX LLC"),
    "4441" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "WEST KENTUCKY RURAL TELEPHONE COOPERATIVE CORPORAT"),
    "4440" => array ( "Area" => "Kentucky", "City" => "Mayfield", "Operator" => "TELNYX LLC"),
    "4004" => array ( "Area" => "Kentucky", "City" => "Jamestown", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "4000" => array ( "Area" => "Kentucky", "City" => "Jamestown", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "3669" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "PEERLESS NETWORK OF KENTUCKY, LLC - KY"),
    "3668" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "PEERLESS NETWORK OF KENTUCKY, LLC - KY"),
    "3666" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "TERRA NOVA TELECOM INC."),
    "3663" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "3658" => array ( "Area" => "Kentucky", "City" => "Cecilia", "Operator" => "DISH WIRELESS, LLC"),
    "3657" => array ( "Area" => "Kentucky", "City" => "Cecilia", "Operator" => "ONVOY, LLC - KY"),
    "3656" => array ( "Area" => "Kentucky", "City" => "Cecilia", "Operator" => "ONVOY SPECTRUM, LLC"),
    "3655" => array ( "Area" => "Kentucky", "City" => "Cecilia", "Operator" => "ONVOY SPECTRUM, LLC"),
    "3654" => array ( "Area" => "Kentucky", "City" => "Cecilia", "Operator" => "ONVOY SPECTRUM, LLC"),
    "3653" => array ( "Area" => "Kentucky", "City" => "Cecilia", "Operator" => "ONVOY SPECTRUM, LLC"),
    "3652" => array ( "Area" => "Kentucky", "City" => "Cecilia", "Operator" => "ONVOY SPECTRUM, LLC"),
    "3651" => array ( "Area" => "Kentucky", "City" => "Cecilia", "Operator" => "ONVOY SPECTRUM, LLC"),
    "3650" => array ( "Area" => "Kentucky", "City" => "Cecilia", "Operator" => "DISH WIRELESS, LLC"),
    "3335" => array ( "Area" => "Kentucky", "City" => "Custer", "Operator" => "CORETEL KENTUCKY, INC. - KY"),
    "3333" => array ( "Area" => "Kentucky", "City" => "Custer", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "3003" => array ( "Area" => "Kentucky", "City" => "Loretto", "Operator" => "FRACTEL, LLC"),
    "3000" => array ( "Area" => "Kentucky", "City" => "Loretto", "Operator" => "FRACTEL, LLC"),
    "2767" => array ( "Area" => "Kentucky", "City" => "Glasgow", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "2623" => array ( "Area" => "Kentucky", "City" => "Fairplay", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "2464" => array ( "Area" => "Kentucky", "City" => "Nebo", "Operator" => "ONVOY, LLC - KY"),
    "2320" => array ( "Area" => "Kentucky", "City" => "Mammoth Cave", "Operator" => "RADIANTIQ LLC"),
    "2229" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2228" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "TELNYX LLC"),
    "2227" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "ONVOY, LLC - KY"),
    "2226" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "TON80 COMMUNICATIONS, LLC"),
    "2225" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "ONVOY, LLC - KY"),
    "2224" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "ONVOY, LLC - KY"),
    "2223" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "ONVOY, LLC - KY"),
    "2222" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "TERRA NOVA TELECOM INC."),
    "2221" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "PEERLESS NETWORK OF KENTUCKY, LLC - KY"),
    "2220" => array ( "Area" => "Kentucky", "City" => "Bessie Bend", "Operator" => "TELNYX LLC"),
    "2210" => array ( "Area" => "Kentucky", "City" => "Columbus", "Operator" => "RADIANTIQ LLC"),
    "2208" => array ( "Area" => "Kentucky", "City" => "Beaver Dam", "Operator" => "COMMIO, LLC"),
    "2204" => array ( "Area" => "Kentucky", "City" => "Beaver Dam", "Operator" => "ONVOY, LLC - KY"),
    "2203" => array ( "Area" => "Kentucky", "City" => "Beaver Dam", "Operator" => "ONVOY, LLC - KY"),
    "2201" => array ( "Area" => "Kentucky", "City" => "Beaver Dam", "Operator" => "METROPCS, INC."),
    "2200" => array ( "Area" => "Kentucky", "City" => "Beaver Dam", "Operator" => "RADIANTIQ LLC"),
    "2199" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "DISH WIRELESS, LLC"),
    "2179" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "METROPCS, INC."),
    "2178" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "PEERLESS NETWORK OF KENTUCKY, LLC - KY"),
    "2177" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "PEERLESS NETWORK OF KENTUCKY, LLC - KY"),
    "2176" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2175" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "DISH WIRELESS, LLC"),
    "2174" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2173" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2172" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2171" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2170" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2169" => array ( "Area" => "Kentucky", "City" => "Radcliff", "Operator" => "DISH WIRELESS, LLC"),
    "2151" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "NUSO, LLC"),
    "2149" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "DISH WIRELESS, LLC"),
    "2141" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "TIME WARNER CBL INFO SV (KY) DBA TIME WARNER-KY"),
    "2140" => array ( "Area" => "Kentucky", "City" => "Bee Spring", "Operator" => "TELNYX LLC"),
    "2132" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "NUSO, LLC"),
    "2130" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "COMMIO, LLC"),
    "2125" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "ONVOY, LLC - KY"),
    "2124" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "ONVOY, LLC - KY"),
    "2123" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "ONVOY, LLC - KY"),
    "2120" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2109" => array ( "Area" => "Kentucky", "City" => "Princeton", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "2100" => array ( "Area" => "Kentucky", "City" => "Princeton", "Operator" => "COMMIO, LLC"),
    "2096" => array ( "Area" => "Kentucky", "City" => "Bradfordsville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "2094" => array ( "Area" => "Kentucky", "City" => "Bradfordsville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "2093" => array ( "Area" => "Kentucky", "City" => "Bradfordsville", "Operator" => "TWILIO INTERNATIONAL, INC."),
    "2092" => array ( "Area" => "Kentucky", "City" => "Bradfordsville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "2091" => array ( "Area" => "Kentucky", "City" => "Bradfordsville", "Operator" => "TIME WARNER CBL INFO SV (KY) DBA TIME WARNER-KY"),
    "2090" => array ( "Area" => "Kentucky", "City" => "Bradfordsville", "Operator" => "RADIANTIQ LLC"),
    "2089" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "2088" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "2087" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "2086" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "2085" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "ONVOY, LLC - KY"),
    "2084" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "ONVOY, LLC - KY"),
    "2083" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "TWILIO INTERNATIONAL, INC."),
    "2082" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "2081" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "ONVOY SPECTRUM, LLC"),
    "2080" => array ( "Area" => "Kentucky", "City" => "Campbellsville", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - KY"),
    "2070" => array ( "Area" => "Kentucky", "City" => "Elkton", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - KY"),
    "2061" => array ( "Area" => "Kentucky", "City" => "Auburn", "Operator" => "TIME WARNER CBL INFO SV (KY) DBA TIME WARNER-KY"),
    "2060" => array ( "Area" => "Kentucky", "City" => "Auburn", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - KY"),
    "2051" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "IP HORIZON LLC"),
    "2049" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2048" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2047" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2046" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "PEERLESS NETWORK OF KENTUCKY, LLC - KY"),
    "2045" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "METROPCS, INC."),
    "2044" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2043" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2042" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "ONVOY, LLC - KY"),
    "2041" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "NUSO, LLC"),
    "2040" => array ( "Area" => "Kentucky", "City" => "Oak Grove", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2039" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "2038" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "2037" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "2036" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "2035" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "2034" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "2033" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "2032" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "CSC WIRELESS, LLC"),
    "2031" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "POWERTEL KENTUCKY LICENSES, INC."),
    "2030" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "CELLCO PARTNERSHIP DBA VERIZON WIRELESS - KY"),
    "2029" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "ONVOY, LLC - KY"),
    "2028" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "ONVOY, LLC - KY"),
    "2027" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2026" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2025" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2024" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2023" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2022" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2021" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2020" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2019" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "NEW CINGULAR WIRELESS PCS, LLC - GA"),
    "2018" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "TELNYX LLC"),
    "2015" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - KY"),
    "2013" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "METROPCS, INC."),
    "2012" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "LEVEL 3 COMMUNICATIONS, LLC - KY"),
    "2011" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "EXIANT COMMUNICATIONS LLC"),
    "2010" => array ( "Area" => "Kentucky", "City" => "Bowling Green", "Operator" => "TELNYX LLC"),
    "2008" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "ONVOY, LLC - KY"),
    "2007" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2006" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "BANDWIDTH.COM CLEC, LLC - KY"),
    "2005" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "ONVOY, LLC - KY"),
    "2004" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "ONVOY, LLC - KY"),
    "2003" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "PEERLESS NETWORK OF KENTUCKY, LLC - KY"),
    "2002" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "FRACTEL, LLC"),
    "2001" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "NUSO, LLC"),
    "2000" => array ( "Area" => "Kentucky", "City" => "Jordan", "Operator" => "FRACTEL, LLC")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 5, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( is_array ( $buffer) ? $buffer : array (), $data);
    }
  }
  return array_merge_recursive ( is_array ( $buffer) ? $buffer : array (), array ( "Area" => $data["Area"]));
}
