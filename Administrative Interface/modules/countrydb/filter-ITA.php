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
 * related to country database of Italy.
 *
 * Reference: https://www.itu.int/oth/T020200006B/en (2012-08-21)
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
 * E.164 Italy country hook
 */
framework_add_filter ( "e164_identify_country_ITA", "e164_identify_country_ITA");

/**
 * E.164 Italian area number identification hook. This hook is an e164_identify
 * sub hook, called when the ISO3166 Alpha3 are "ITA" (code for Italy). This hook
 * will verify if phone number is valid, returning the area code, area name,
 * phone number, others number related information and if possible, the number
 * type (mobile, landline, Premium Rate Number, etc).
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Parameters to the function. Number provided as
 *                          $parameters["Number"]
 * @return array Array contaning many information about the requested number
 */
function e164_identify_country_ITA ( $buffer, $parameters)
{
  /**
   * Check if number country code is from Italy
   */
  if ( substr ( $parameters["Number"], 0, 3) != "+39")
  {
    return ( is_array ( $buffer) ? $buffer : false);
  }

  /**
   * Check for mobile network without NDC and 9 to 10 digits SN
   */
  $prefixes = array (
    "39",
    "38",
    "37",
    "36",
    "35",
    "34",
    "33",
    "32"
  );
  foreach ( $prefixes as $prefix)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 12 && strlen ( $parameters["Number"]) <= 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "39", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Italy", "Area" => "", "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_MOBILE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5), "International" => "+39 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5))));
    }
  }

  /**
   * Check for fixed line network with 2 to 4 digits NDC and 4 to 8 digits SN
   */
  $prefixes = array (
    "0985" => "Scalea",
    "0984" => "Cosenza",
    "0983" => "Rossano",
    "0982" => "Paola",
    "0981" => "Castrovillari",
    "0976" => "Muro Lucano",
    "0975" => "Sala Consilina",
    "0974" => "Vallo della Lucania",
    "0973" => "Lagonegro",
    "0972" => "Melfi",
    "0971" => "Potenza",
    "0968" => "Lamezia Terme",
    "0967" => "Soverato",
    "0966" => "Palmi",
    "0965" => "Reggio Calabria",
    "0964" => "Locri",
    "0963" => "Vibo Valentia",
    "0962" => "Crotone",
    "0961" => "Catanzaro",
    "0942" => "Taormina",
    "0941" => "Patti",
    "0935" => "Enna",
    "0934" => "Caltanissetta",
    "0933" => "Caltagirone",
    "0932" => "Ragusa",
    "0931" => "Siracusa",
    "0925" => "Sciacca",
    "0924" => "Alcamo",
    "0923" => "Trapani",
    "0922" => "Agrigento",
    "0921" => "Cefalù",
    "0885" => "Cerignola",
    "0884" => "Manfredonia",
    "0883" => "Andria",
    "0882" => "S. Severo",
    "0881" => "Foggia",
    "0875" => "Termoli",
    "0874" => "Campobasso",
    "0873" => "Vasto",
    "0872" => "Lanciano",
    "0871" => "Chieti",
    "0865" => "Isernia",
    "0864" => "Sulmona",
    "0863" => "Avezzano",
    "0862" => "L'Aquila",
    "0861" => "Teramo",
    "0836" => "Maglie",
    "0835" => "Matera",
    "0833" => "Gallipoli",
    "0832" => "Lecce",
    "0831" => "Brindisi",
    "0828" => "Battipaglia",
    "0827" => "S. Angelo dei Lombardi",
    "0825" => "Avellino",
    "0824" => "Benevento",
    "0823" => "Caserta",
    "0789" => "Olbia",
    "0785" => "Macomer",
    "0784" => "Nuoro",
    "0783" => "Oristano",
    "0782" => "Lanusei",
    "0781" => "Iglesias",
    "0776" => "Cassino",
    "0775" => "Frosinone",
    "0774" => "Tivoli",
    "0773" => "Latina",
    "0771" => "Formia",
    "0769" => "Roma",
    "0766" => "Civitavecchia",
    "0765" => "Poggio Mirteto",
    "0763" => "Orvieto",
    "0761" => "Viterbo",
    "0746" => "Rieti",
    "0744" => "Terni",
    "0743" => "Spoleto",
    "0742" => "Foligno",
    "0737" => "Camerino",
    "0736" => "Ascoli Piceno",
    "0735" => "S. Benedetto del Tronto",
    "0734" => "Fermo",
    "0733" => "Macerata",
    "0732" => "Fabriano",
    "0731" => "Jesi",
    "0722" => "Urbino",
    "0721" => "Pesaro",
    "0588" => "Volterra",
    "0587" => "Pontedera",
    "0586" => "Livorno",
    "0585" => "Massa",
    "0584" => "Viareggio",
    "0583" => "Lucca",
    "0578" => "Chianciano Terme",
    "0577" => "Siena",
    "0575" => "Arezzo",
    "0574" => "Prato",
    "0573" => "Pistoia",
    "0572" => "Montecatini Terme",
    "0571" => "Empoli",
    "0566" => "Follonica",
    "0565" => "Piombino",
    "0564" => "Grosseto",
    "0549" => "S. Marino (Rep. di)",
    "0547" => "Cesena",
    "0546" => "Faenza",
    "0545" => "Lugo",
    "0544" => "Ravenna",
    "0543" => "Forlì",
    "0542" => "Imola",
    "0541" => "Rimini",
    "0536" => "Sassuolo",
    "0535" => "Mirandola",
    "0534" => "Porretta Terme",
    "0533" => "Comacchio",
    "0532" => "Ferrara",
    "0525" => "Fornovo di Taro",
    "0524" => "Fidenza",
    "0523" => "Piacenza",
    "0522" => "Reggio nell'Emilia",
    "0521" => "Parma",
    "0481" => "Gorizia",
    "0474" => "Brunico",
    "0473" => "Merano",
    "0472" => "Bressanone",
    "0471" => "Bolzano",
    "0465" => "Tione di Trento",
    "0464" => "Rovereto",
    "0463" => "Cles",
    "0462" => "Cavalese",
    "0461" => "Trento",
    "0445" => "Schio",
    "0444" => "Vicenza",
    "0442" => "Legnago",
    "0439" => "Feltre",
    "0438" => "Conegliano",
    "0437" => "Belluno",
    "0436" => "Cortina d'Ampezzo",
    "0435" => "Pieve di Cadore",
    "0434" => "Pordenone",
    "0433" => "Tolmezzo",
    "0432" => "Udine",
    "0431" => "Cervignano del Friuli",
    "0429" => "Este",
    "0428" => "Tarvisio",
    "0427" => "Spilimbergo",
    "0426" => "Adria",
    "0425" => "Rovigo",
    "0424" => "Bassano del Grappa",
    "0423" => "Montebelluna",
    "0422" => "Treviso",
    "0421" => "S. Dona' di Piave",
    "0386" => "Ostiglia",
    "0385" => "Stradella",
    "0384" => "Mortara",
    "0383" => "Voghera",
    "0382" => "Pavia",
    "0381" => "Vigevano",
    "0377" => "Codogno",
    "0376" => "Mantova",
    "0375" => "Casalmaggiore",
    "0374" => "Soresina",
    "0373" => "Crema",
    "0372" => "Cremona",
    "0371" => "Lodi",
    "0369" => "Milano",
    "0365" => "Salò",
    "0364" => "Breno",
    "0363" => "Treviglio",
    "0362" => "Seregno",
    "0346" => "Clusone",
    "0345" => "S. Pellegrino Terme",
    "0344" => "Menaggio",
    "0343" => "Chiavenna",
    "0342" => "Sondrio",
    "0341" => "Lecco",
    "0332" => "Varese",
    "0331" => "Busto Arsizio",
    "0324" => "Domodossola",
    "0323" => "Baveno",
    "0322" => "Arona",
    "0321" => "Novara",
    "0187" => "La Spezia",
    "0185" => "Rapallo",
    "0184" => "San remo",
    "0183" => "Imperia",
    "0182" => "Albenga",
    "0175" => "Saluzzo",
    "0174" => "Mondovi'",
    "0173" => "Alba",
    "0172" => "Savigliano",
    "0171" => "Cuneo",
    "0166" => "St. Vincent",
    "0165" => "Aosta",
    "0163" => "Borgosesia",
    "0161" => "Vercelli",
    "0144" => "Acqui Terme",
    "0143" => "Novi Ligure",
    "0142" => "Casale Monferrato",
    "0141" => "Asti",
    "0131" => "Alessandria",
    "0125" => "Ivrea",
    "0124" => "Rivarolo Canavese",
    "0123" => "Lanzo Torinese",
    "0122" => "Susa",
    "0121" => "Pinerolo",
    "099" => "Taranto",
    "095" => "Catania",
    "091" => "Palermo",
    "090" => "Messina",
    "089" => "Salerno",
    "085" => "Pescara",
    "081" => "Napoli",
    "080" => "Bari",
    "079" => "Sassari",
    "075" => "Perugia",
    "071" => "Ancona",
    "070" => "Cagliari",
    "059" => "Modena",
    "055" => "Firenze",
    "051" => "Bologna",
    "050" => "Pisa",
    "049" => "Padova",
    "045" => "Verona",
    "041" => "Venezia (Mestre)",
    "040" => "Trieste",
    "039" => "Monza",
    "035" => "Bergamo",
    "031" => "Como",
    "030" => "Brescia",
    "019" => "Savona",
    "015" => "Biella",
    "011" => "Torino",
    "010" => "Genova",
    "06" => "Roma",
    "02" => "Milano"
  );
  foreach ( $prefixes as $prefix => $area)
  {
    if ( (int) substr ( $parameters["Number"], 3, strlen ( $prefix)) == $prefix && strlen ( $parameters["Number"]) >= 9 && strlen ( $parameters["Number"]) <= 13)
    {
      return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "CC" => "39", "NDC" => substr ( $parameters["Number"], 3, 2), "Country" => "Italy", "Area" => $area, "City" => "", "Operator" => "", "SN" => substr ( $parameters["Number"], 5), "Type" => VD_PHONETYPE_LANDLINE, "CallFormats" => array ( "Local" => substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 5) . " " . substr ( $parameters["Number"], 10), "International" => "+39 " . substr ( $parameters["Number"], 3, 2) . " " . substr ( $parameters["Number"], 5, 5) . " " . substr ( $parameters["Number"], 10))));
    }
  }

  /**
   * If reached here, number wasn't identified as a valid Italian phone number
   */
  return ( is_array ( $buffer) ? $buffer : false);
}
?>
