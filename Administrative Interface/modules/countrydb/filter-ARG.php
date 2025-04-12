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
 * related to country database of Argentina.
 *
 * Reference: https://www.itu.int/oth/T0202000009/en (2011-12-16)
 * Reference: https://www.enacom.gob.ar/indicativos-interurbanos_p366
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
 * E.164 Argentina country hook
 */
framework_add_filter ( "e164_identify_country_ARG", "e164_identify_country_ARG");

/**
 * E.164 Argentinian area number identification hook. This hook is an
 * e164_identify sub hook, called when the ISO3166 Alpha3 are "ARG" (code for
 * Argentina). This hook will verify if phone number is valid, returning the
 * area code, area name, phone number, others number related information and if
 * possible, the number type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_ARG ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Argentina
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+54")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Argentina has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for fixed line or mobile network (they share same prefixes and area
   * codes)
   */
  $prefixes = array (
    "3894" => array ( "area" => "Tucuman", "city" => ""),
    "3892" => array ( "area" => "Tucuman", "city" => ""),
    "3891" => array ( "area" => "Tucuman", "city" => ""),
    "3888" => array ( "area" => "Jujuy", "city" => ""),
    "3887" => array ( "area" => "Jujuy", "city" => ""),
    "3886" => array ( "area" => "Jujuy", "city" => ""),
    "3885" => array ( "area" => "Jujuy", "city" => ""),
    "3878" => array ( "area" => "Salta", "city" => ""),
    "3877" => array ( "area" => "Salta, Chaco", "city" => ""),
    "3876" => array ( "area" => "Salta", "city" => ""),
    "3873" => array ( "area" => "Salta", "city" => ""),
    "3869" => array ( "area" => "Tucuman", "city" => ""),
    "3868" => array ( "area" => "Salta", "city" => ""),
    "3867" => array ( "area" => "Tucuman", "city" => ""),
    "3865" => array ( "area" => "Tucuman", "city" => ""),
    "3863" => array ( "area" => "Tucuman", "city" => ""),
    "3862" => array ( "area" => "Tucuman", "city" => ""),
    "3861" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3858" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3857" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3856" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3855" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3854" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3846" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3845" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3844" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3843" => array ( "area" => "Santiago Del Estero", "city" => "Quimili"),
    "3841" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "3838" => array ( "area" => "Catamarca", "city" => ""),
    "3837" => array ( "area" => "Catamarca", "city" => ""),
    "3835" => array ( "area" => "Catamarca", "city" => ""),
    "3832" => array ( "area" => "Catamarca", "city" => ""),
    "3827" => array ( "area" => "La Rioja", "city" => ""),
    "3826" => array ( "area" => "La Rioja", "city" => ""),
    "3825" => array ( "area" => "La Rioja", "city" => ""),
    "3821" => array ( "area" => "La Rioja", "city" => ""),
    "3786" => array ( "area" => "Corrientes", "city" => ""),
    "3782" => array ( "area" => "Corrientes", "city" => ""),
    "3781" => array ( "area" => "Corrientes", "city" => ""),
    "3777" => array ( "area" => "Corrientes", "city" => ""),
    "3775" => array ( "area" => "Corrientes", "city" => ""),
    "3774" => array ( "area" => "Corrientes", "city" => ""),
    "3773" => array ( "area" => "Corrientes", "city" => ""),
    "3772" => array ( "area" => "Corrientes", "city" => ""),
    "3758" => array ( "area" => "Misiones", "city" => ""),
    "3757" => array ( "area" => "Misiones", "city" => ""),
    "3756" => array ( "area" => "Corrientes", "city" => ""),
    "3755" => array ( "area" => "Misiones", "city" => ""),
    "3754" => array ( "area" => "Misiones", "city" => ""),
    "3751" => array ( "area" => "Misiones", "city" => ""),
    "3743" => array ( "area" => "Misiones", "city" => ""),
    "3741" => array ( "area" => "Misiones", "city" => ""),
    "3735" => array ( "area" => "Chaco", "city" => ""),
    "3734" => array ( "area" => "Chaco", "city" => ""),
    "3731" => array ( "area" => "Chaco", "city" => ""),
    "3725" => array ( "area" => "Chaco", "city" => ""),
    "3721" => array ( "area" => "Chaco", "city" => ""),
    "3718" => array ( "area" => "Formosa", "city" => ""),
    "3716" => array ( "area" => "Formosa", "city" => ""),
    "3715" => array ( "area" => "Formosa, Chaco, Formosa, Chaco, Chaco", "city" => ""),
    "3711" => array ( "area" => "Chaco, Formosa, Formosa, Formosa, Formosa", "city" => ""),
    "3585" => array ( "area" => "Cordoba", "city" => ""),
    "3584" => array ( "area" => "Cordoba", "city" => ""),
    "3583" => array ( "area" => "Cordoba", "city" => ""),
    "3582" => array ( "area" => "Cordoba", "city" => ""),
    "3576" => array ( "area" => "Cordoba", "city" => ""),
    "3575" => array ( "area" => "Cordoba", "city" => ""),
    "3574" => array ( "area" => "Cordoba", "city" => ""),
    "3573" => array ( "area" => "Cordoba", "city" => ""),
    "3572" => array ( "area" => "Cordoba", "city" => ""),
    "3571" => array ( "area" => "Cordoba", "city" => ""),
    "3564" => array ( "area" => "Cordoba", "city" => ""),
    "3563" => array ( "area" => "Cordoba", "city" => ""),
    "3562" => array ( "area" => "Cordoba", "city" => ""),
    "3549" => array ( "area" => "Cordoba", "city" => ""),
    "3548" => array ( "area" => "Cordoba", "city" => ""),
    "3547" => array ( "area" => "Cordoba", "city" => ""),
    "3546" => array ( "area" => "Cordoba", "city" => ""),
    "3544" => array ( "area" => "Cordoba", "city" => ""),
    "3543" => array ( "area" => "Cordoba", "city" => ""),
    "3542" => array ( "area" => "Cordoba", "city" => ""),
    "3541" => array ( "area" => "Cordoba", "city" => ""),
    "3537" => array ( "area" => "Cordoba", "city" => ""),
    "3533" => array ( "area" => "Cordoba", "city" => ""),
    "3532" => array ( "area" => "Cordoba", "city" => ""),
    "3525" => array ( "area" => "Cordoba", "city" => ""),
    "3524" => array ( "area" => "Cordoba", "city" => ""),
    "3522" => array ( "area" => "Cordoba", "city" => ""),
    "3521" => array ( "area" => "Cordoba", "city" => ""),
    "3498" => array ( "area" => "Santa Fe", "city" => ""),
    "3497" => array ( "area" => "Santa Fe", "city" => ""),
    "3496" => array ( "area" => "Santa Fe", "city" => ""),
    "3493" => array ( "area" => "Santa Fe", "city" => ""),
    "3492" => array ( "area" => "Santa Fe", "city" => ""),
    "3491" => array ( "area" => "Santa Fe", "city" => ""),
    "3489" => array ( "area" => "Buenos Aires", "city" => ""),
    "3487" => array ( "area" => "Buenos Aires", "city" => ""),
    "3483" => array ( "area" => "Santa Fe", "city" => ""),
    "3482" => array ( "area" => "Santa Fe", "city" => ""),
    "3476" => array ( "area" => "Santa Fe", "city" => ""),
    "3472" => array ( "area" => "Cordoba", "city" => ""),
    "3471" => array ( "area" => "Santa Fe", "city" => ""),
    "3469" => array ( "area" => "Santa Fe", "city" => ""),
    "3468" => array ( "area" => "Cordoba", "city" => ""),
    "3467" => array ( "area" => "Cordoba, Santa Fe, Cordoba, Cordoba, Cordoba, Cordoba", "city" => ""),
    "3466" => array ( "area" => "Santa Fe", "city" => ""),
    "3465" => array ( "area" => "Santa Fe", "city" => ""),
    "3464" => array ( "area" => "Santa Fe", "city" => ""),
    "3463" => array ( "area" => "Cordoba", "city" => ""),
    "3462" => array ( "area" => "Santa Fe", "city" => ""),
    "3460" => array ( "area" => "Santa Fe", "city" => ""),
    "3458" => array ( "area" => "Entre Rios", "city" => ""),
    "3456" => array ( "area" => "Entre Rios", "city" => ""),
    "3455" => array ( "area" => "Entre Rios", "city" => ""),
    "3454" => array ( "area" => "Entre Rios", "city" => ""),
    "3447" => array ( "area" => "Entre Rios", "city" => ""),
    "3446" => array ( "area" => "Entre Rios", "city" => ""),
    "3445" => array ( "area" => "Entre Rios", "city" => ""),
    "3444" => array ( "area" => "Entre Rios", "city" => ""),
    "3442" => array ( "area" => "Entre Rios", "city" => ""),
    "3438" => array ( "area" => "Entre Rios", "city" => ""),
    "3437" => array ( "area" => "Entre Rios", "city" => ""),
    "3436" => array ( "area" => "Entre Rios", "city" => ""),
    "3435" => array ( "area" => "Entre Rios", "city" => ""),
    "3409" => array ( "area" => "Santa Fe", "city" => ""),
    "3408" => array ( "area" => "Santa Fe", "city" => ""),
    "3407" => array ( "area" => "Buenos Aires", "city" => ""),
    "3406" => array ( "area" => "Santa Fe", "city" => ""),
    "3405" => array ( "area" => "Santa Fe", "city" => ""),
    "3404" => array ( "area" => "Santa Fe", "city" => ""),
    "3402" => array ( "area" => "Santa Fe", "city" => ""),
    "3401" => array ( "area" => "Santa Fe", "city" => ""),
    "3400" => array ( "area" => "Santa Fe", "city" => ""),
    "3388" => array ( "area" => "Buenos Aires", "city" => ""),
    "3387" => array ( "area" => "Cordoba", "city" => ""),
    "3385" => array ( "area" => "Cordoba", "city" => ""),
    "3382" => array ( "area" => "Santa Fe, Buenos Aires, Santa Fe, Santa Fe, Santa Fe, Santa Fe, Santa Fe, Cordoba, Cordoba", "city" => ""),
    "3329" => array ( "area" => "Buenos Aires", "city" => ""),
    "3327" => array ( "area" => "Buenos Aires", "city" => ""),
    "2983" => array ( "area" => "Buenos Aires", "city" => ""),
    "2982" => array ( "area" => "Buenos Aires", "city" => ""),
    "2972" => array ( "area" => "Neuquen", "city" => ""),
    "2966" => array ( "area" => "Santa Cruz", "city" => ""),
    "2964" => array ( "area" => "Tierra Del Fuego", "city" => "Rio Grande"),
    "2963" => array ( "area" => "Santa Cruz", "city" => ""),
    "2962" => array ( "area" => "Santa Cruz", "city" => ""),
    "2954" => array ( "area" => "La Pampa", "city" => ""),
    "2953" => array ( "area" => "La Pampa", "city" => ""),
    "2952" => array ( "area" => "La Pampa", "city" => ""),
    "2948" => array ( "area" => "Neuquen", "city" => ""),
    "2946" => array ( "area" => "Rio Negro", "city" => ""),
    "2945" => array ( "area" => "Chubut", "city" => ""),
    "2942" => array ( "area" => "Neuquen", "city" => ""),
    "2940" => array ( "area" => "Rio Negro", "city" => ""),
    "2936" => array ( "area" => "Buenos Aires", "city" => ""),
    "2935" => array ( "area" => "Buenos Aires", "city" => ""),
    "2934" => array ( "area" => "Rio Negro", "city" => ""),
    "2933" => array ( "area" => "Buenos Aires", "city" => "Huanguelen Sur"),
    "2932" => array ( "area" => "Buenos Aires", "city" => ""),
    "2931" => array ( "area" => "Rio Negro", "city" => ""),
    "2929" => array ( "area" => "Buenos Aires", "city" => ""),
    "2928" => array ( "area" => "Buenos Aires", "city" => ""),
    "2927" => array ( "area" => "Buenos Aires", "city" => ""),
    "2926" => array ( "area" => "Buenos Aires", "city" => ""),
    "2925" => array ( "area" => "Buenos Aires", "city" => ""),
    "2924" => array ( "area" => "Buenos Aires, La Pampa", "city" => ""),
    "2923" => array ( "area" => "Buenos Aires", "city" => ""),
    "2922" => array ( "area" => "Buenos Aires", "city" => "Coronel Pringles"),
    "2921" => array ( "area" => "Buenos Aires", "city" => ""),
    "2920" => array ( "area" => "Rio Negro", "city" => ""),
    "2903" => array ( "area" => "Chubut", "city" => ""),
    "2902" => array ( "area" => "Santa Cruz", "city" => ""),
    "2901" => array ( "area" => "Tierra Del Fuego", "city" => ""),
    "2658" => array ( "area" => "San Luis", "city" => ""),
    "2657" => array ( "area" => "San Luis", "city" => ""),
    "2656" => array ( "area" => "San Luis", "city" => ""),
    "2655" => array ( "area" => "San Luis", "city" => ""),
    "2651" => array ( "area" => "San Luis", "city" => ""),
    "2648" => array ( "area" => "San Juan", "city" => ""),
    "2647" => array ( "area" => "San Juan", "city" => ""),
    "2646" => array ( "area" => "San Juan", "city" => ""),
    "2626" => array ( "area" => "Mendoza", "city" => ""),
    "2625" => array ( "area" => "Mendoza", "city" => ""),
    "2624" => array ( "area" => "Mendoza", "city" => ""),
    "2622" => array ( "area" => "Mendoza", "city" => ""),
    "2478" => array ( "area" => "Buenos Aires", "city" => ""),
    "2477" => array ( "area" => "Buenos Aires, Santa Fe", "city" => ""),
    "2475" => array ( "area" => "Buenos Aires", "city" => ""),
    "2474" => array ( "area" => "Buenos Aires", "city" => ""),
    "2473" => array ( "area" => "Buenos Aires, Santa Fe, Buenos Aires, Santa Fe", "city" => ""),
    "2396" => array ( "area" => "Buenos Aires", "city" => ""),
    "2395" => array ( "area" => "Buenos Aires", "city" => ""),
    "2394" => array ( "area" => "Buenos Aires", "city" => ""),
    "2393" => array ( "area" => "Buenos Aires", "city" => "Salazar"),
    "2392" => array ( "area" => "Buenos Aires", "city" => ""),
    "2358" => array ( "area" => "Buenos Aires", "city" => ""),
    "2357" => array ( "area" => "Buenos Aires", "city" => ""),
    "2356" => array ( "area" => "Buenos Aires", "city" => ""),
    "2355" => array ( "area" => "Buenos Aires", "city" => ""),
    "2354" => array ( "area" => "Buenos Aires", "city" => ""),
    "2353" => array ( "area" => "Buenos Aires", "city" => ""),
    "2352" => array ( "area" => "Buenos Aires", "city" => ""),
    "2346" => array ( "area" => "Buenos Aires", "city" => ""),
    "2345" => array ( "area" => "Buenos Aires", "city" => ""),
    "2344" => array ( "area" => "Buenos Aires", "city" => ""),
    "2343" => array ( "area" => "Buenos Aires", "city" => ""),
    "2342" => array ( "area" => "Buenos Aires", "city" => ""),
    "2338" => array ( "area" => "La Pampa", "city" => ""),
    "2337" => array ( "area" => "Buenos Aires", "city" => ""),
    "2336" => array ( "area" => "Cordoba", "city" => ""),
    "2335" => array ( "area" => "La Pampa", "city" => ""),
    "2334" => array ( "area" => "La Pampa", "city" => ""),
    "2333" => array ( "area" => "La Pampa", "city" => ""),
    "2331" => array ( "area" => "La Pampa", "city" => ""),
    "2326" => array ( "area" => "Buenos Aires", "city" => ""),
    "2325" => array ( "area" => "Buenos Aires", "city" => ""),
    "2324" => array ( "area" => "Buenos Aires", "city" => ""),
    "2323" => array ( "area" => "Buenos Aires", "city" => ""),
    "2320" => array ( "area" => "Buenos Aires", "city" => ""),
    "2317" => array ( "area" => "Buenos Aires", "city" => ""),
    "2316" => array ( "area" => "Buenos Aires", "city" => ""),
    "2314" => array ( "area" => "Buenos Aires", "city" => ""),
    "2302" => array ( "area" => "La Pampa", "city" => ""),
    "2297" => array ( "area" => "Buenos Aires", "city" => "Rauch"),
    "2296" => array ( "area" => "Buenos Aires", "city" => "Ayacucho"),
    "2292" => array ( "area" => "Buenos Aires", "city" => ""),
    "2291" => array ( "area" => "Buenos Aires", "city" => ""),
    "2286" => array ( "area" => "Buenos Aires", "city" => ""),
    "2285" => array ( "area" => "Buenos Aires", "city" => "Laprida"),
    "2284" => array ( "area" => "Buenos Aires", "city" => ""),
    "2283" => array ( "area" => "Buenos Aires", "city" => "Tapalque"),
    "2281" => array ( "area" => "Buenos Aires", "city" => ""),
    "2274" => array ( "area" => "Buenos Aires", "city" => "Carlos Spegazzini"),
    "2273" => array ( "area" => "Buenos Aires", "city" => ""),
    "2272" => array ( "area" => "Buenos Aires", "city" => ""),
    "2271" => array ( "area" => "Buenos Aires", "city" => ""),
    "2268" => array ( "area" => "Buenos Aires", "city" => ""),
    "2267" => array ( "area" => "Buenos Aires", "city" => "General Madariaga"),
    "2266" => array ( "area" => "Buenos Aires", "city" => ""),
    "2265" => array ( "area" => "Buenos Aires", "city" => ""),
    "2264" => array ( "area" => "Buenos Aires", "city" => ""),
    "2262" => array ( "area" => "Buenos Aires", "city" => ""),
    "2261" => array ( "area" => "Buenos Aires", "city" => ""),
    "2257" => array ( "area" => "Buenos Aires", "city" => ""),
    "2255" => array ( "area" => "Buenos Aires", "city" => "Villa Gesell"),
    "2254" => array ( "area" => "Buenos Aires", "city" => ""),
    "2252" => array ( "area" => "Buenos Aires", "city" => ""),
    "2246" => array ( "area" => "Buenos Aires", "city" => "Santa Teresita"),
    "2245" => array ( "area" => "Buenos Aires", "city" => ""),
    "2244" => array ( "area" => "Buenos Aires", "city" => "Las Flores"),
    "2243" => array ( "area" => "Buenos Aires", "city" => ""),
    "2242" => array ( "area" => "Buenos Aires", "city" => ""),
    "2241" => array ( "area" => "Buenos Aires", "city" => ""),
    "2229" => array ( "area" => "Buenos Aires", "city" => ""),
    "2227" => array ( "area" => "Buenos Aires", "city" => ""),
    "2226" => array ( "area" => "Buenos Aires", "city" => ""),
    "2225" => array ( "area" => "Buenos Aires", "city" => ""),
    "2224" => array ( "area" => "Buenos Aires", "city" => ""),
    "2223" => array ( "area" => "Buenos Aires", "city" => ""),
    "2221" => array ( "area" => "Buenos Aires", "city" => ""),
    "2202" => array ( "area" => "Buenos Aires", "city" => ""),
    "388" => array ( "area" => "Jujuy", "city" => ""),
    "387" => array ( "area" => "Salta", "city" => ""),
    "385" => array ( "area" => "Santiago Del Estero", "city" => ""),
    "383" => array ( "area" => "Catamarca", "city" => ""),
    "381" => array ( "area" => "Tucuman", "city" => ""),
    "380" => array ( "area" => "La Rioja", "city" => ""),
    "379" => array ( "area" => "Corrientes", "city" => ""),
    "376" => array ( "area" => "Misiones", "city" => ""),
    "370" => array ( "area" => "Formosa", "city" => ""),
    "364" => array ( "area" => "Chaco", "city" => ""),
    "362" => array ( "area" => "Chaco", "city" => ""),
    "358" => array ( "area" => "Cordoba", "city" => ""),
    "353" => array ( "area" => "Cordoba", "city" => ""),
    "351" => array ( "area" => "Cordoba", "city" => ""),
    "348" => array ( "area" => "Buenos Aires", "city" => ""),
    "345" => array ( "area" => "Entre Rios", "city" => ""),
    "343" => array ( "area" => "Entre Rios", "city" => ""),
    "342" => array ( "area" => "Santa Fe", "city" => ""),
    "341" => array ( "area" => "Santa Fe", "city" => ""),
    "336" => array ( "area" => "Buenos Aires", "city" => ""),
    "299" => array ( "area" => "Neuquen", "city" => ""),
    "298" => array ( "area" => "Rio Negro, La Pampa", "city" => ""),
    "297" => array ( "area" => "Chubut", "city" => ""),
    "294" => array ( "area" => "Rio Negro, Chubut, Rio Negro, Rio Negro, Rio Negro, Rio Negro, Rio Negro, Rio Negro, Rio Negro, Rio Negro, Rio Negro", "city" => ""),
    "291" => array ( "area" => "Buenos Aires", "city" => ""),
    "280" => array ( "area" => "Chubut", "city" => ""),
    "266" => array ( "area" => "San Luis", "city" => ""),
    "264" => array ( "area" => "San Juan", "city" => ""),
    "263" => array ( "area" => "Mendoza", "city" => ""),
    "261" => array ( "area" => "Mendoza", "city" => ""),
    "260" => array ( "area" => "Mendoza", "city" => ""),
    "249" => array ( "area" => "Buenos Aires", "city" => ""),
    "237" => array ( "area" => "Buenos Aires", "city" => ""),
    "236" => array ( "area" => "Buenos Aires", "city" => ""),
    "230" => array ( "area" => "Buenos Aires", "city" => ""),
    "223" => array ( "area" => "Buenos Aires", "city" => ""),
    "221" => array ( "area" => "Buenos Aires", "city" => ""),
    "220" => array ( "area" => "Buenos Aires", "city" => ""),
    "11" => array ( "area" => "Buenos Aires", "city" => "Buenos Aires Metropolitan Area (AMBA)")
  );
  foreach ( $prefixes as $prefix => $data)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      switch ( strlen ( $prefix))
      {
        case 2:
          $callformats = array ( "Local" => "(" . $prefix . ") " . substr ( $parameters["Number"], 5, 4) . "-" . substr ( $parameters["Number"], 9), "International" => "+54 " . $prefix . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9));
          break;
        case 3:
          $callformats = array ( "Local" => "(" . $prefix . ") " . substr ( $parameters["Number"], 6, 3) . "-" . substr ( $parameters["Number"], 9), "International" => "+54 " . $prefix . " " . substr ( $parameters["Number"], 6, 3) . " " . substr ( $parameters["Number"], 9));
          break;
        case 4:
          $callformats = array ( "Local" => "(" . $prefix . ") " . substr ( $parameters["Number"], 7, 2) . "-" . substr ( $parameters["Number"], 9), "International" => "+54 " . $prefix . " " . substr ( $parameters["Number"], 7, 2) . " " . substr ( $parameters["Number"], 9));
          break;
      }
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "54", "NDC" => (string) $prefix, "Country" => "Argentina", "Area" => $data["area"], "City" => $data["city"], "Operator" => "", "SN" => substr ( $parameters["Number"], 3 + strlen ( $prefix)), "Type" => VD_PHONETYPE_MOBILE | VD_PHONETYPE_LANDLINE, "CallFormats" => $callformats));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Argentinian phone
   * number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
