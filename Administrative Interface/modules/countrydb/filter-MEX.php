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
 * related to country database of Mexico.
 *
 * Reference: https://www.itu.int/oth/T020200008A/en (2018-07-27)
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
 * E.164 Mexico country hook
 */
framework_add_filter ( "e164_identify_country_MEX", "e164_identify_country_MEX");

/**
 * E.164 Mexican area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "MEX" (code for Mexico). This
 * hook will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_MEX ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Mexico
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+52")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * All numbers in Mexico has 13 digits E.164 format
   */
  if ( strlen ( $parameters["Number"]) != 13)
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Mexico areas
   */
  $areas = array (
    "9" => "South-East",
    "8" => "North-East",
    "7" => "South-West",
    "6" => "North-West",
    "5" => "Central",
    "4" => "North",
    "3" => "West",
    "2" => "East"
  );

  /**
   * Check for mobile network with 2 digits NDC and 8 digits SN
  if ( (int) substr ( $parameters["Number"], 3, 1) >= 2 && substr ( $parameters["Number"], 4, 1) == "0")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "52", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Mexico", "Area" => $areas[substr ( $parameters["Number"], 3, 1)], "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+52 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
  }
   */

  /**
   * Check for fixed line network with 2 digits NDC and 8 digits SN
   */
  $prefixes = array (
    "999" => "Merida, Yuc",
    "998" => "Puerto Morelos, Qroo",
    "997" => "Tzucacab, Yuc",
    "996" => "Tenabo, Camp",
    "995" => "Santa Maria Jalapa Del Marquez, Oax",
    "994" => "Tres Picos, Chis",
    "993" => "Villahermosa, Tab",
    "992" => "Villa Las Rosas, Chis",
    "991" => "Tixcocob, Yuc",
    "988" => "Uman, Yuc",
    "987" => "Cozumel, Qroo",
    "986" => "Tizimin, Yuc",
    "985" => "Valladolid, Yuc",
    "984" => "Xcaret, Qroo",
    "983" => "Felipe Carrillo Puerto, Qroo",
    "982" => "Seyba Playa, Camp",
    "981" => "Campeche, Camp",
    "972" => "Matias Romero, Oax",
    "971" => "Union Hidalgo, Oax",
    "969" => "Yucalpeten, Yuc",
    "968" => "Tecpatan, Chis",
    "967" => "San Cristobal De Las Casas, Chis",
    "966" => "Tonala, Chis",
    "965" => "Villa Flores, Chis",
    "964" => "Mazatan, Chis",
    "963" => "Las Margaritas, Chis",
    "962" => "Union Juarez, Chis",
    "961" => "Villa De Acala, Chis",
    "958" => "Santa Maria Huatulco, Oax",
    "954" => "Santiago Jamiltepec, Oax",
    "953" => "Tlaxiaco, Oax",
    "951" => "Zimatlan De Alvarez, Oax",
    "938" => "Ciudad Del Carmen, Camp",
    "937" => "Cardenas, Tab",
    "936" => "Villa Benito Juarez, Tab",
    "934" => "Tenosique, Tab",
    "933" => "Tecolutilla, Tab",
    "932" => "Teapa, Tab",
    "924" => "Texistepec, Ver",
    "923" => "Pueblo Sanchez Magallanes, Tab",
    "922" => "Minatitlan, Ver",
    "921" => "Ixhuatlan Del Sureste, Ver",
    "919" => "Yajalon, Chis",
    "918" => "Pueblo Nuevo Comaltitlan, Chis",
    "917" => "Reforma, Chis",
    "916" => "Salto Del Agua, Chis",
    "914" => "Once De Febrero, Tab",
    "913" => "Vicente Guerrero, Tab",
    "899" => "Rio Bravo, Tamps",
    "897" => "Nueva Ciudad Guerrero, Tamps",
    "894" => "Valle Hermoso, Tamps",
    "892" => "Villa De Paras, Nl",
    "891" => "Valadeces, Tamps",
    "878" => "Piedras Negras, Coah",
    "877" => "Ciudad Acuña, Coah",
    "873" => "Lampazos, Nl",
    "872" => "Tlahualilo De Zaragoza, Dgo",
    "871" => "Villa Juarez, Dgo",
    "869" => "San Buenaventura, Coah",
    "868" => "Matamoros, Tamps",
    "867" => "Nuevo Laredo, Tamps",
    "866" => "Monclova, Coah",
    "864" => "Palau, Coah",
    "862" => "Zaragoza, Coah",
    "861" => "Sabinas, Coah",
    "846" => "Villa Cacalilao, Ver",
    "845" => "Ponciano Arriaga, Slp",
    "844" => "Saltillo, Coah",
    "842" => "Parras De La Fuente, Coah",
    "841" => "San Fernando, Tamps",
    "836" => "Villa Aldama, Tamps",
    "835" => "Villagran, Tamps",
    "834" => "Ciudad Victoria, Tamps",
    "833" => "Tampico, Tamps",
    "832" => "Xicotencatl, Tamps",
    "831" => "Los Aztecas, Tamps",
    "829" => "Villa Aldama, Nl",
    "828" => "Cadereyta, Nl",
    "827" => "Santiago (El Cercado), Nl",
    "826" => "Montemorelos, Nl",
    "825" => "Pesqueria, Nl",
    "824" => "Sabinas Hidalgo, Nl",
    "823" => "Los Ramones, Nl",
    "821" => "Linares, Nl",
    "797" => "Zacatlan, Pue",
    "791" => "Ciudad Sahagun, Hgo",
    "789" => "Tempoal, Ver",
    "786" => "Tuxpan, Mich",
    "785" => "Tepetzintla, Ver",
    "784" => "Papantla, Ver",
    "783" => "Tuxpan, Ver",
    "782" => "Poza Rica, Ver",
    "781" => "San Jeronimo De Juarez, Gro",
    "779" => "Tizayuca, Hgo",
    "778" => "Tlaxcoapan, Hgo",
    "777" => "Xochitepec, Mor",
    "776" => "Huauchinango, Pue",
    "775" => "Tulancingo, Hgo",
    "774" => "Zacualtipan, Hgo",
    "773" => "Tula, Hgo",
    "772" => "Actopan, Hgo",
    "771" => "Real Del Monte, Hgo",
    "769" => "Tepalcingo, Mor",
    "768" => "Tamiahua, Ver",
    "767" => "Zirandaro, Gro",
    "766" => "Tecolutla, Ver",
    "765" => "Alazan Potrero Del Llano, Ver",
    "764" => "Xicotepec De Juarez, Pue",
    "763" => "Tlahuelilpan, Hgo",
    "762" => "Taxco, Gro",
    "761" => "Tecozautla, Hgo",
    "759" => "Zimapan, Hgo",
    "758" => "San Jeronimito, Gro",
    "757" => "Tlapa De Comonfort, Gro",
    "756" => "Olinala, Gro",
    "755" => "Zihuatanejo, Gro",
    "754" => "Tixtla, Gro",
    "753" => "Playa Azul, Mich",
    "752" => "Yautepec, Mor",
    "751" => "Tilzapotla, Mor",
    "749" => "Calpulalpan, Tlax",
    "748" => "Nanacamilpa, Tlax",
    "747" => "Zumpango Del Rio, Gro",
    "746" => "Venustiano Carranza, Pue",
    "745" => "Tierra Colorada, Gro",
    "744" => "Xaltianguis, Gro",
    "743" => "Zempoala, Hgo",
    "742" => "Tecpan De Galeana, Gro",
    "741" => "San Luis Acatlan, Gro",
    "739" => "Tepoztlan, Mor",
    "738" => "Tepatepec, Hgo",
    "737" => "Xoxocotla, Mor",
    "736" => "Tlacotepec, Gro",
    "735" => "Jonacatepec, Mor",
    "734" => "Zacatepec, Mor",
    "733" => "Iguala, Gro",
    "732" => "Tlapehuala, Gro",
    "731" => "Zacualpan De Amilpas, Mor",
    "729" => "San Francisco Chimalpa, Mex",
    "728" => "Santa Maria Atarasquillo, Mex",
    "727" => "Tepecoacuilco De Trujano, Gro",
    "726" => "Villa Victoria, Mex",
    "725" => "Santa Maria Del Monte, Mex",
    "724" => "Tejupilco De Hidalgo, Mex",
    "723" => "Coatepec Harinas, Mex",
    "722" => "Toluca, Mex",
    "721" => "Ixtapan De La Sal, Mex",
    "719" => "Temoaya, Mex",
    "718" => "Temascalcingo, Mex",
    "717" => "Tenango Del Valle, Mex",
    "716" => "Texcaltitlan, Mex",
    "715" => "Zitacuaro, Mich",
    "714" => "Villa Guerrero, Mex",
    "713" => "Santiago Tianguistenco, Mex",
    "712" => "Santiago Yeche, Mex",
    "711" => "Tlalpujahua, Mich",
    "698" => "San Blas, Sin",
    "697" => "Pericos, Sin",
    "696" => "San Ignacio, Sin",
    "695" => "Teacapan, Sin",
    "694" => "Rosario, Sin",
    "687" => "Tamazula, Sin",
    "686" => "Sinaloa, Bcn",
    "677" => "San Juan Del Rio, Dgo",
    "676" => "Peñon Blanco, Dgo",
    "675" => "Villa Union, Dgo",
    "674" => "Tepehuanes, Dgo",
    "673" => "Villa Benito Juarez, Sin",
    "672" => "Villa Juarez, Sin",
    "671" => "Viesca, Coah",
    "669" => "Villa Union, Sin",
    "668" => "Villa Gustavo Diaz Ordaz, Sin",
    "667" => "Villa Adolfo Lopez Mateos, Sin",
    "666" => "Tijuana, Bcn",
    "665" => "Tecate, Bcn",
    "662" => "San Pedro El Saucito, Son",
    "661" => "Rosarito, Bcn",
    "659" => "Temosachic, Chih",
    "658" => "Saltillo, Bcn",
    "656" => "Villa Ahumada, Chih",
    "653" => "San Luis Rio Colorado, Son",
    "652" => "Nicolas Bravo, Chih",
    "651" => "Sonoita, Son",
    "649" => "Villa Ocampo, Dgo",
    "648" => "Ciudad Camargo, Chih",
    "647" => "Yavaros, Son",
    "646" => "Maneadero, Bcn",
    "645" => "Cananea, Son",
    "644" => "Marte R. Gomez (El Tobarito), Son",
    "643" => "Vicam, Son",
    "642" => "Pueblo Mayo, Son",
    "641" => "Santa Ana, Son",
    "639" => "Rosales, Chih",
    "638" => "Puerto Peñasco, Son",
    "637" => "Caborca, Son",
    "636" => "Ricardo Flores Magon, Chih",
    "635" => "San Juanito, Chih",
    "634" => "Villa Hidalgo, Son",
    "633" => "Naco, Son",
    "632" => "Magdalena, Son",
    "631" => "Nogales, Son",
    "629" => "Villa Lopez, Chih",
    "628" => "Villa Matamoros, Chih",
    "627" => "Parral, Chih",
    "626" => "Ojinaga, Chih",
    "625" => "Colonia Anahuac, Chih",
    "624" => "San Jose Del Cabo, Bcs",
    "623" => "Ures, Son",
    "622" => "San Carlos (nuevo Guaymas), Son",
    "621" => "Saucillo, Chih",
    "618" => "Durango, Dgo",
    "616" => "San Quintin, Bcn",
    "615" => "Santa Rosalia, Bcs",
    "614" => "Villa Aldama, Chih",
    "613" => "Villa Insurgentes, Bcs",
    "612" => "Todos Santos, Bcs",
    "599" => "Tlapanaloya, Mex",
    "596" => "Temascalapa, Mex",
    "595" => "Texcoco, Mex",
    "594" => "Xometla, Mex",
    "593" => "Teoloyucan, Mex",
    "592" => "Otumba, Mex",
    "591" => "Zumpango, Mex",
    "589" => "Ozumba, Mex",
    "588" => "Villa Del Carbon, Mex",
    "587" => "Amecameca, Mex",
    "586" => "Tlalmanalco, Mex",
    "499" => "Villanueva, Zac",
    "498" => "Sain El Alto, Zac",
    "496" => "Villa Hidalgo, Zac",
    "495" => "Villa Hidalgo, Jal",
    "494" => "Jerez De Garcia Salinas, Zac",
    "493" => "Fresnillo, Zac",
    "492" => "Zacatecas, Zac",
    "489" => "Xilitla, Slp",
    "488" => "Matehuala, Slp",
    "487" => "Rio Verde, Slp",
    "486" => "Villa Juarez, Slp",
    "485" => "Villa De Reyes, Slp",
    "483" => "Tamazunchale, Slp",
    "482" => "Tancanhuitz, Slp",
    "481" => "Ciudad Valles, Slp",
    "478" => "Calera Victor Rosales, Zac",
    "477" => "Leon, Gto",
    "476" => "San Francisco Del Rincon, Gto",
    "475" => "Encarnacion De Diaz, Jal",
    "474" => "Paso De Cuarenta, Jal",
    "473" => "Guanajuato, Gto",
    "472" => "Silao, Gto",
    "471" => "Tlazazalca, Mich",
    "469" => "Penjamo, Gto",
    "468" => "San Luis De La Paz, Gto",
    "467" => "Teul De Gonzalez Ortega, Zac",
    "466" => "Tarimoro, Gto",
    "465" => "San Francisco De Los Romos, Ags",
    "464" => "Salamanca, Gto",
    "463" => "Tabasco, Zac",
    "462" => "Irapuato, Gto",
    "461" => "San Juan De La Vega, Gto",
    "459" => "Tiquicheo, Mich",
    "458" => "Villa De Cos, Zac",
    "457" => "Valparaiso, Zac",
    "456" => "Valle De Santiago, Gto",
    "455" => "Santa Ana Maya, Mich",
    "454" => "Villa Jimenez, Mich",
    "453" => "Apatzingan, Mich",
    "452" => "Uruapan, Mich",
    "451" => "Zinapecuaro, Mich",
    "449" => "Jesus Maria, Ags",
    "448" => "Pedro Escobedo, Qro",
    "447" => "Maravatio, Mich",
    "445" => "Moroleon, Gto",
    "444" => "San Luis Potosi, Slp",
    "443" => "Tarimbaro, Mich",
    "442" => "Tlacote El Bajo, Qro",
    "441" => "Pinal De Amoles, Qro",
    "438" => "Villa Morelos, Mich",
    "437" => "Villa Guerrero, Jal",
    "436" => "Zacapu, Mich",
    "435" => "San Lucas, Mich",
    "434" => "Villa Escalante (S. C. Del Cobre), Mich",
    "433" => "Sombrerete, Zac",
    "432" => "Romita, Gto",
    "431" => "Villa Obregon, Jal",
    "429" => "Pueblo Nuevo, Gto",
    "428" => "San Felipe, Gto",
    "427" => "San Juan Del Rio, Qro",
    "426" => "Felipe Carrillo Puerto, Mich",
    "425" => "Tancitaro, Mich",
    "424" => "Tepalcatepec, Mich",
    "423" => "Ziracuaretiro, Mich",
    "422" => "Taretan, Mich",
    "421" => "Tarandacuao, Gto",
    "419" => "San Jose Iturbide, Gto",
    "418" => "San Diego De La Union, Gto",
    "417" => "Paracuaro, Gto",
    "415" => "San Miguel Allende, Gto",
    "414" => "Tequisquiapan, Qro",
    "413" => "Apaseo El Grande, Gto",
    "412" => "Villagran, Gto",
    "411" => "Yuriria, Gto",
    "395" => "Union De San Antonio, Jal",
    "394" => "Cotija De La Paz, Mich",
    "393" => "San Ramon, Jal",
    "392" => "Ocotlan, Jal",
    "391" => "Tototlan, Jal",
    "389" => "Tecuala, Nay",
    "388" => "Talpa De Allende, Jal",
    "387" => "Zapotitlan, Jal",
    "386" => "San Marcos, Jal",
    "385" => "Tamazulita, Jal",
    "384" => "Teuchitlan, Jal",
    "383" => "Villamar, Mich",
    "382" => "Valle De Juarez, Jal",
    "381" => "San Jose De Gracia, Mich",
    "379" => "Zinaparo, Mich",
    "378" => "Tepatitlan, Jal",
    "377" => "Estipac, Jal",
    "376" => "Tizapan El Alto, Jal",
    "375" => "Ameca, Jal",
    "374" => "Tequila, Jal",
    "373" => "Zapotlanejo, Jal",
    "372" => "Teocuitatlan De Corona, Jal",
    "371" => "Tuxpan, Jal",
    "358" => "Zapoltitic, Jal",
    "357" => "Villa Purificacion, Jal",
    "356" => "Yurecuaro, Mich",
    "355" => "Tangancicuaro, Mich",
    "354" => "Tocumbo, Mich",
    "353" => "Venustiano Carranza, Mich",
    "352" => "La Piedad, Mich",
    "351" => "Zamora, Mich",
    "349" => "Tenamaxtlan, Jal",
    "348" => "Santiaguito De Velazquez, Jal",
    "347" => "Valle De Guadalupe, Jal",
    "346" => "Teocaltiche, Jal",
    "345" => "La Concepcion, Jal",
    "344" => "Yahualica, Jal",
    "343" => "Venustiano Carranza, Jal",
    "342" => "Sayula, Jal",
    "341" => "Ciudad Guzman, Jal",
    "329" => "Valle De Banderas, Nay",
    "328" => "Vista Hermosa, Mich",
    "327" => "Zacoalpan, Nay",
    "326" => "Zacoalco, Jal",
    "325" => "Acaponeta, Nay",
    "324" => "Jala, Nay",
    "323" => "Villa Hidalgo, Nay",
    "322" => "Tomatlan, Jal",
    "321" => "El Limon, Jal",
    "319" => "Tuxpan, Nay",
    "318" => "Tonila, Jal",
    "317" => "El Chante, Jal",
    "316" => "Union De Tula, Jal",
    "315" => "San Patricio Melaque, Jal",
    "314" => "Peña Colorada, Col",
    "313" => "Tecoman, Col",
    "312" => "Los Tepames, Col",
    "311" => "Tepic, Nay",
    "297" => "Alvarado, Ver",
    "296" => "Zempoala, Ver",
    "294" => "Santiago Tuxtla, Ver",
    "288" => "Tres Valles, Ver",
    "287" => "Tuxtepec, Oax",
    "285" => "Soledad De Doblado, Ver",
    "284" => "Lerdo De Tejada, Ver",
    "283" => "Villa Azueta, Ver",
    "282" => "Profesor Rafael Ramirez, Ver",
    "281" => "Loma Bonita, Oax",
    "279" => "Villa Emiliano Zapata, Ver",
    "278" => "Zongolica, Ver",
    "276" => "Villa Rafael Lara Grajales, Pue",
    "275" => "Tulcingo Del Valle, Pue",
    "274" => "Vicente Camalote, Oax",
    "273" => "Villa Tejeda (Camaron), Ver",
    "272" => "Orizaba, Ver",
    "271" => "Potrero, Ver",
    "249" => "Tecamachalco, Pue",
    "248" => "Santa Rita Tlahuapan, Pue",
    "247" => "San Cosme Xalostoc, Tlax",
    "246" => "Zacatelco, Tlax",
    "245" => "Tlachichuca, Pue",
    "244" => "Tochimilco, Pue",
    "243" => "San Felipe Ayutla, Pue",
    "241" => "Tlaxco, Tlax",
    "238" => "Tehuacan, Pue",
    "237" => "Tlacotepec, Pue",
    "236" => "Teotitlan Del Camino, Oax",
    "235" => "Vega De Alatorre, Ver",
    "233" => "Zaragoza, Pue",
    "232" => "Martinez De La Torre, Ver",
    "231" => "Teziutlan, Pue",
    "229" => "Veracruz, Ver",
    "228" => "Tuzamapan, Ver",
    "227" => "San Buenaventura Nealtican, Pue",
    "226" => "Jalacingo, Ver",
    "225" => "Tlapacoyan, Ver",
    "224" => "Tochtepec, Pue",
    "223" => "Tepeaca, Pue",
    "222" => "San Miguel Canoa, Pue",
    "81" => "Monterrey, Nl, And Metropolitan Area",
    "55" => "Mexico City, Fd, And Metropolitan Area",
    "33" => "Guadalajara, Jal, And Metropolitan Area"
  );
  foreach ( $prefixes as $prefix => $city)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "52", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Mexico", "Area" => $areas[substr ( $parameters["Number"], 3, 1)], "City" => $city, "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+52 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
    }
  }

  /**
   * Check for VSAT network
   */
  if ( substr ( $parameters["Number"], 3, 3) == "200" || substr ( $parameters["Number"], 3, 3) == "201")
  {
    return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "52", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Mexico", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_VSAT, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9), "International" => "+52 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 4) . " " . substr ( $parameters["Number"], 9))));
  }

  /**
   * If reached here, number wasn't identified as a valid Mexican phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
