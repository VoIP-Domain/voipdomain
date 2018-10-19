#!/usr/bin/php -q
<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2018 Ernani José Camargo Azevedo
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
 * FastAGI daemon with applications to Asterisk server.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Asterisk FastAGI Application Daemon
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Set error reporting level
 */
error_reporting ( E_ERROR);
ini_set ( "display_errors", "false");
// error_reporting ( E_ALL); ini_set ( "display_errors", "true");

/**
 * Check if script is running from CLI
 */
if ( ! defined ( "STDIN"))
{
  echo "This script must be executed into CLI!\n";
  exit ( 1);
}

/**
 * Include functions library
 */
require_once ( dirname ( __FILE__) . "/includes/functions.inc.php");
require_once ( dirname ( __FILE__) . "/includes/plugins.inc.php");

/**
 * Parse configuration file. You should put your configuration file OUTSIDE
 * the web server files path, or you must block access to this file at the
 * web server configuration. Your configuration would contain passwords and
 * other sensitive configurations.
 */
$_in = parse_ini_file ( "/etc/voipdomain/fastagi.conf", true);

/**
 * Include all modules configuration files
 */
foreach ( glob ( dirname ( __FILE__) . "/modules/*/fastagi.php") as $filename)
{
  require_once ( $filename);
}

/**
 * Check for mandatory basic configurations (if didn't exist, set default)
 */
if ( ! array_key_exists ( "general", $_in))
{
  $_in["general"] = array ();
}
if ( ! array_key_exists ( "version", $_in["general"]))
{
  $_in["version"] = "1.0";
} else {
  $_in["version"] = $_in["general"]["version"];
  unset ( $_in["general"]["version"]);
}
if ( ! array_key_exists ( "charset", $_in["general"]))
{
  $_in["general"]["charset"] = "UTF-8";
}
if ( ! array_key_exists ( "language", $_in["general"]))
{
  $_in["general"]["language"] = "en_US";
}

/**
 * Configure locale and encoding
 */
mb_internal_encoding ( $_in["general"]["charset"]);
setlocale ( LC_ALL, $_in["general"]["language"] . "." . $_in["general"]["charset"]);

/**
 * Show software version header
 */
echo chr ( 27) . "[1;37mVoIP Domain FastAGI Daemon" . chr ( 27) . "[1;0m v" . $_in["version"] . "\n";
echo "\n";

/**
 * Validate MySQL session
 */
if ( ! is_array ( $_in["mysql"]))
{
  echo "Error: Cannot find \"mysql\" session at configuration file.\n";
  exit ( 1);
}

/**
 * Function to translate a number based on an indexed array.
 *
 * @param $translations Array An array containing the translations. Each
 *                            translation must be an array with keys "pattern",
 *                            "remove" and "add".
 * @param $number String Number to be translated.
 * @return String
 */
function translateNumber ( $translations, $number)
{
  $number = preg_replace ( "/[^0-9+]/", "", $number);
  foreach ( $translations as $translation)
  {
    $pattern = preg_replace ( "/[^0-9+]/", "", $translation["pattern"]);
    $remove = preg_replace ( "/[^0-9+]/", "", $translation["remove"]);
    $add = preg_replace ( "/[^0-9+]/", "", $translation["add"]);
    if ( substr ( $number, 0, strlen ( $pattern)) == $pattern)
    {
      return $add . substr ( $number, strlen ( $remove));
    }
  }
  return $number;
}

/**
 * Function to validate a number based on a mask (Asterisk standard). 0 to 9, +,
 * X (0 to 9), Z (1 to 9), N (2 to 9) and groups [1236-9] for example.
 *
 * @param $mask String Mask to be applied.
 * @param $number String Number to validate.
 * @return boolean
 */
function matchMask ( $mask, $number)
{
  $number = preg_replace ( "/[^0-9+]/", "", $number);
  $mask = preg_replace ( "/[^0-9(\[.*\-.*\])+XZN]/", "", strtoupper ( $mask));

  $pos = 0;
  for ( $x = 0; $x < strlen ( $mask); $x++)
  {
    $digit = substr ( $mask, $x, 1);
    $match = substr ( $number, $pos, 1);
    $pos++;
    switch ( $digit)
    {
      case "0":		// Digit 0
      case "1":		// Digit 1
      case "2":		// Digit 2
      case "3":		// Digit 3
      case "4":		// Digit 4
      case "5":		// Digit 5
      case "6":		// Digit 6
      case "7":		// Digit 7
      case "8":		// Digit 8
      case "9":		// Digit 9
      case "+":		// Digit +
        if ( $match != $digit)
        {
          return false;
        }
        break;
      case "X":		// Any number between 0 to 9
        if ( ord ( $digit) < 48 && ord ( $digit) > 57)
        {
          return false;
        }
        break;
      case "Z":		// Any number between 1 to 9
        if ( ord ( $digit) < 48 && ord ( $digit) > 57)
        {
          return false;
        }
        break;
      case "N":		// Any number between 2 to 9
        if ( ord ( $digit) < 49 && ord ( $digit) > 57)
        {
          return false;
        }
        break;
      case "[":		// Group
        $group = array ();
        $last = 48;
        while ( $digit != 93)
        {
          $x++;
          $digit = ord ( substr ( $mask, $x, 1));
          if ( $x > strlen ( $mask))
          {
            return false;
          }
          if ( $digit >= 48 && $digit <= 57)
          {
            $group[] = $digit;
            $last = $digit;
          }
          if ( $digit == 45 && ( ord ( substr ( $mask, $x + 1, 1)) >= 48 && ord ( substr ( $mask, $x + 1, 1)) <= 57))
          {
            $last++;
            $x++;
            $digit = ord ( substr ( $mask, $x, 1));
            if ( $digit < $last)
            {
              $digit = $last;
              $last = ord ( substr ( $mask, $x, 1));
            }
            for ( $y = $last; $y <= $digit; $y++)
            {
              $group[] = $y;
            }
          }
        }
        if ( ! in_array ( ord ( $match), $group))
        {
          return false;
        }
        break;
    }
  }
  return true;
}

/**
 * Function to ordenate routes based on cost, type and priority.
 *
 * @param $a mixed Cost a.
 * @param $b mixed Cost b.
 * @return int
 */
function costOrder ( $a, $b)
{
  global $defaultgw;

  if ( $a["cost"] == $b["cost"])
  {
    if ( $a["type"] == $b["type"])
    {
      if ( $a["priority"] == $b["priority"])
      {
        if ( in_array ( $b["gw"], $defaultgw))
        {
          return 1;
        }
        return 0;
      }
      return ( $a["priority"] > $b["priority"] ? -1 : 1);
    }
    if ( $a["type"] == "Digital")
    {
      return -1;
    }
    if ( $a["type"] == "Mobile")
    {
      if ( $b["type"] == "Digital")
      {
        return 1;
      }
      return -1;
    }
    if ( $a["type"] == "VoIP")
    {
      if ( $b["type"] == "Digital" || $b["type"] == "Mobile")
      {
        return 1;
      }
      return -1;
    }
    return 1;
  }
  return ( $a["cost"] < $b["cost"] ? -1 : 1);
}

/**
 * Function to return if fare is normal or reduced to a group of countries
 * (ANATEL standard).
 *
 * @param $num int Group of countries (ANATEL standard)
 * @return string
 */
function internationalFare ( $num)
{
  switch ( $num)
  {
    case 1:
    case 2:
    case 3:
    case 4:
    case 5:
    case 6:
    case 8:
      /**
       * Check fare type
       *
       * Sundays: Reduced
       * National holidays: Reduced
       * 00:00:00 to 04:59:59: Reduced
       * 05:00:00 to 19:59:59: Normal
       * 20:00:00 to 23:59:59: Reduced
       */
      $time = (int) date ( "G");
      $day = (int) date ( "j");
      $month = (int) date ( "n");
      $week = (int) date ( "w");
      if ( $week == 0                     // Sundays
        || $time <= 4                     // 00:00:00 to 04:59:59
        || $time >= 20                    // 20:00:00 to 23:59:59
        || ( $day == 1 && $month == 1)    // January 1st: New year's day
        || ( $day == 21 && $month == 4)   // April 21: Tiradentes
        || ( $day == 1 && $month == 5)    // May 1st: Labor day
        || ( $day == 7 && $month == 9)    // September 7: Independency
        || ( $day == 12 && $month == 10)  // October 12: Our Lady Aparecida
        || ( $day == 2 && $month == 11)   // November 2nd: All Soul's day
        || ( $day == 15 && $month == 11)  // November 15: Republic Proclamation Day
        || ( $day == 25 && $month == 12)  // December 25: Christmas Day
      )
      {
        $type = "Reduced";
      } else {
        $type = "Normal";
      }
      break;
    case 7:
    case 9:
      /**
       * Check fare type
       *
       * Sundays: Reduced
       * National holidays: Reduced
       * 00:00:00 to 00:59:59: Normal
       * 01:00:00 to 05:59:59: Reduced
       * 06:00:00 to 12:59:59: Normal
       * 13:00:00 to 16:59:59: Reduced
       * 17:00:00 to 23:59:59: Normal
       */
      $time = (int) date ( "G");
      $day = (int) date ( "j");
      $month = (int) date ( "n");
      $week = (int) date ( "w");
      if ( $week == 0                     // Sundays
        || ( $time >= 1 && $time <= 5)    // 01:00:00 to 05:59:59
        || ( $time >= 13 && $time <= 16)  // 13:00:00 to 16:59:59
        || ( $day == 1 && $month == 1)    // January 1st: New year's day
        || ( $day == 21 && $month == 4)   // April 21: Tiradentes
        || ( $day == 1 && $month == 5)    // May 1st: Labor day
        || ( $day == 7 && $month == 9)    // September 7: Independency
        || ( $day == 12 && $month == 10)  // October 12: Our Lady Aparecida
        || ( $day == 2 && $month == 11)   // November 2nd: All Soul's day
        || ( $day == 15 && $month == 11)  // November 15: Republic Proclamation Day
        || ( $day == 25 && $month == 12)  // December 25: Christmas Day
      )
      {
        $type = "Reduced";
      } else {
        $type = "Normal";
      }
      break;
  }

  return $type;
}

/**
 * Function to return ANATEL international group number based on a E.164 number.
 *
 * @param $num string Number (E.164 standard)
 * @return int
 */
function internationalGroup ( $num)
{
/**
http://www.mpsp.mp.br/portal/page/portal/cao_consumidor/legislacao/leg_servicos_publico/leg_sp_telecomunicacoes/Resol424-05anatel-Anexo.pdf

DA ESTRUTURA E CRITÉRIOS TARIFÁRIOS DO PLANO BÁSICO DO STFC LDI

Art. 36. O plano básico do STFC LDI é constituído exclusivamente pelo item tarifa de utilização.

Parágrafo único. A utilização do STFC LDI é tarifada por tempo de utilização.

Art. 37. A estrutura do plano básico do STFC LDI para chamada originada de acesso fixo ou móvel é função do agrupamento de países de destino, sua duração, a hora e dia de realização da mesma.

§ 1º O agrupamento de países é descrito no Anexo II deste Regulamento.
§ 2º Em função do dia e hora de realização da chamada aplica-se tarifa reduzida, para cada Grupo de Países de destino, ficando estabelecido que os demais horários são horários de tarifa normal:

I – Grupo 1, das 20 horas às 5 horas de segunda-feira a sábado e de 0 h às 24 horas aos domingos e feriados nacionais;
II – Grupo 2, das 20 horas às 5 h de segunda-feira a sábado e de 0 h às 24 h aos domingos e feriados nacionais;
III – Grupo 3, das 20 h às 5 h de segunda-feira a sábado e de 0 h às 24 h aos domingos e feriados nacionais;
IV – Grupo 4, das 20 h às 5 h de segunda-feira a sábado e de 0 h às 24 h aos domingos e feriados nacionais;
V – Grupo 5, das 20 h às 5 h de segunda-feira a sábado e de 0 h a 24 h aos domingos e feriados nacionais;
VI – Grupo 6, das 20 h às 5 h de segunda-feira a sábado e de 0 h às 24 h aos domingos e feriados nacionais;
VII – Grupo 7, de 1 h às 6 h e das 13 h às 17 h de segunda-feira a sábado e de 0 h às 24 h aos domingos e feriados nacionais;
VIII – Grupo 8, das 20 h às 5 h de segunda-feira a sábado e de 0 h às 24 h aos domingos e feriados nacionais; e
IX – Grupo 9, de 1 h às 6 h e das 13 h às 17 h de segunda-feira a sábado e de 0 h às 24 h aos domingos e feriados nacionais.

Art. 38. A estrutura do Plano Básico do STFC LDI para chamada originada de acesso fixo ou móvel é função também do relacionamento de part es do território nacional com partes de países vizinhos, sua duração, a hora e dia de realização da mesma.

§ 1º Este relacionamento é descrito no Anexo III deste Regulamento.
§ 2º Em função do dia e hora de realização da chamada aplica-se tarifa reduzida no horário das 20 às 5 h de segunda-feira a sábado e de 0h às 24 h aos domingos e feriados nacionais, ficando estabelecido que os demais horários são horários de tarifa normal.

Art. 39. As chamadas LDI originadas em telefones de uso público ou em terminais de acesso público e destinadas a acessos localizados em outros paises são tarifadas com base no valor da unidade de tarifação para TUP e TAP (VTP), sendo a primeira UTP incidente no atendimento da chamada e as seguintes a cada período calculado pela fórmula: T = 60 s . VTP/VC
Onde:
T é o período de incidência das UTP, em segundos, com uma casa decimal, com arredondamento;
VC é a designação genérica do valor da chamada com 1 (um) minuto de duração, para o grupo de países e intervalo horário especificados, conforme o plano básico da prestadora selecionada.

Art. 40. Na tarifação de chamada originada de acesso do STFC entre localidades situadas em uma região fronteiriça aplicam-se os critérios correspondentes ao degrau 1 do plano básico do STFC na modalidade longa distância nacional.

ANEXO II – Agrupamento dos Países para Efeito de Tarifação das Chamadas LDI
Grupo/Países
1 Argentina, Chile, Paraguai e Uruguai;
2 Estados Unidos da América e Havaí;
3 Alaska, Anguila, Antártida, Antigua e Barbuda, Aruba, Bahamas, Barbados, Belize, Bermudas, Bolívia, Canadá, Colômbia, Costa Rica, Cuba, Dominica, El Salvador, Equador, Granada, Groelândia, Guadalupe, Guatemala, Guiana Inglesa, Guiana Francesa, Haiti, Honduras, Ilhas Cayman, Ilhas Malvinas, Ilhas Turquesas e Caicos, Ilhas Virgens Americanas, Ilhas Virgens Britânicas, Jamaica, Martinica, México, Montserrat, Nicarágua, Panamá, Peru, Porto Rico, República Dominicana, Santa Lucia, São Cristóvão e Névis, São Pedro e Miguel, São Vicente e Granadinas, Suriname, Trindad e Tobago, Venezuela e Antilhas;
4 Portugal, Açores e Ilha da Madeira;
5 Alemanha, Andorra, Áustria, Bélgica, Dinamarca, Espanha, Finlândia, França, Holanda (Países Baixos), Irlanda, Itália, Liechtenstein, Noruega, Reino Unido, Suécia e Suíça;
6 Albânia, Arábia Saudita, Armênia, Azerbaijão, Bareine, Belarus, Bósnia-Herzegovina, Bulgária, Catar, Chipre, Croácia, Emirados Árabes Unidos, Eslováquia, Eslovênia, Estônia, Geórgia, Grécia, Hungria, Iêmen, Ilhas Feroe, Irã, Iraque, Islândia, Israel, Jordânia, Kuaite, Letônia, Líbano, Lituânia, Luxemburgo, Macedônia, Malta, Moldova, Mônaco, Omã, Palestina, Polônia, República Tcheca, Romênia, Rússia, San Marino, Sérvia e Montenegro, Síria, Turquia, Ucrânia e Vaticano;
7 Austrália e Japão;
8 África do Sul, Angola, Argélia, Benin, Botsuana, Burkina Faso, Burundi, Cabo Verde, Camarões, Chade, Costa do Marfim, Djibuti, Egito, Eritréia, Etiópia, Gabão, Gâmbia, Gana, Guiné, Guiné-Bissau, Guiné-Equatorial, Ilhas Ascensão, Ilhas Comores, Ilhas Maurício, Ilhas Mayotte, Lesoto, Libéria, Líbia, Madagascar, Maláwi, Mali, Marrocos, Mauritânia, Moçambique, Namíbia, Níger, Nigéria, Quênia, República Centro-Africana, República Democrática do Congo, República do Congo, Reunião, Ruanda, Santa Helena, São Tomé e Príncipe, Seicheles, Senegal, Serra Leoa, Somália, Suazilândia, Sudão, Tanzânia, Togo, Tunísia, Uganda, Zâmbia, Zimbábue;
9 Afeganistão, Bangladesh, Brunei, Butão, Camboja, Cazaquistão, China, Cingapura, Coréia do Norte, Coréia do Sul, Diego Garcia, Estados Federados da Micronésia, Fiji, Filipinas, Guam, Hong-Kong, Ilha Christmas, Ilha de Pitcairn, Ilha Johnston, Ilha Niue, Ilha Norfolk, Ilhas Coco, Ilha Cook, Ilha Wake, Ilhas de Wallis e Futuna, Ilhas Mariana do Norte, Ilhas Marshall, Ilhas Salomão, Índia, Indonésia, Kiribati, Laos, Macau, Malásia, Maldivas, Midway, Mongólia, Myanmar, Nauru, Nepal, Nova Caledônia, Nova Zelândia, Palau, Papua-Nova Guiné, Paquistão, Polinésia Francesa, Quirguízia, Samoa, Samoa Americana, Sri Lanka, Tadjiquistão, Tailândia, Taiwan, Timor-Leste, Tonga, Toquelau, Turcomenistão, Tuvalu, Uzbequistão, Vanuato, Vietnã e Ilhas do Pacífico (exceto Havaí).
*/

  // Group 1: Argentina (+54), Chile (+56), Paraguai (+595) e Uruguai (+598):
  if ( substr ( $num, 0, 3) == "+54" || substr ( $num, 0, 3) == "+56" || substr ( $num, 0, 4) == "+595" || substr ( $num, 0, 4) == "+598")
  {
    return 1;
  }

  // Group 2: Estados Unidos da América (+1325, +1330, +1234, +1518, +1229, +1957, +1505, +1320, +1730, +1618, +1657, +1909, +1752, +1714, +1907, +1734, +1278, +1703, +1571, +1828, +1606, +1404, +1770, +1678, +1470, +1609, +1762, +1706, +1331, +1737, +1512, +1667, +1443, +1410, +1225, +1425, +1360, +1240, +1610, +1484, +1835, +1406, +1228, +1659, +1205, +1952, +1208, +1857, +1617, +1802, +1631, +1203, +1475, +1718, +1347, +1979, +1818, +1747, +1856, +1239, +1319, +1447, +1217, +1843, +1681, +1304, +1980, +1704, +1423, +1872, +1773, +1312, +1413, +1708, +1464, +1283, +1513, +1931, +1440, +1216, +1573, +1803, +1614, +1380, +1925, +1361, +1214, +1972, +1469, +1764, +1650, +1276, +1563, +1937, +1386, +1940, +1720, +1303, +1313, +1679, +1620, +1218, +1715, +1534, +1848, +1732, +1915, +1908, +1607, +1814, +1760, +1442, +1541, +1458, +1812, +1701, +1910, +1810, +1954, +1754, +1479, +1260, +1682, +1817, +1559, +1352, +1409, +1219, +1970, +1616, +1231, +1920, +1274, +1336, +1864, +1254, +1985, +1959, +1860, +1516, +1808, +1832, +1713, +1281, +1938, +1256, +1936, +1317, +1515, +1949, +1769, +1601, +1731, +1904, +1551, +1201, +1870, +1913, +1975, +1816, +1308, +1262, +1845, +1865, +1337, +1765, +1863, +1717, +1740, +1517, +1307, +1956, +1575, +1702, +1580, +1859, +1501, +1562, +1323, +1310, +1213, +1502, +1978, +1351, +1806, +1434, +1339, +1781, +1478, +1608, +1603, +1507, +1660, +1641, +1830, +1901, +1786, +1305, +1414, +1612, +1251, +1334, +1630, +1615, +1724, +1504, +1917, +1646, +1212, +1973, +1862, +1716, +1510, +1341, +1432, +1405, +1531, +1402, +1927, +1689, +1407, +1321, +1269, +1364, +1270, +1445, +1267, +1215, +1623, +1602, +1480, +1878, +1412, +1763, +1626, +1248, +1772, +1971, +1503, +1207, +1401, +1719, +1919, +1984, +1530, +1775, +1804, +1951, +1540, +1585, +1309, +1815, +1779, +1252, +1916, +1989, +1831, +1801, +1385, +1210, +1935, +1858, +1619, +1628, +1415, +1408, +1669, +1805, +1661, +1424, +1627, +1369, +1707, +1941, +1906, +1912, +1570, +1206, +1564, +1318, +1301, +1227, +1712, +1605, +1574, +1509, +1417, +1636, +1435, +1314, +1557, +1651, +1727, +1662, +1209, +1209, +1315, +1253, +1850, +1813, +1419, +1567, +1785, +1947, +1520, +1918, +1430, +1903, +1757, +1586, +1202, +1847, +1224, +1561, +1316, +1302, +1774, +1508, +1914, +1928) e Havaí (+1 808):
  if ( substr ( $num, 0, 5) == "+1325" || substr ( $num, 0, 5) == "+1330" || substr ( $num, 0, 5) == "+1234" || substr ( $num, 0, 5) == "+1518" || substr ( $num, 0, 5) == "+1229" || substr ( $num, 0, 5) == "+1957" || substr ( $num, 0, 5) == "+1505" || substr ( $num, 0, 5) == "+1320" || substr ( $num, 0, 5) == "+1730" || substr ( $num, 0, 5) == "+1618" || substr ( $num, 0, 5) == "+1657" || substr ( $num, 0, 5) == "+1909" || substr ( $num, 0, 5) == "+1752" || substr ( $num, 0, 5) == "+1714" || substr ( $num, 0, 5) == "+1907" || substr ( $num, 0, 5) == "+1734" || substr ( $num, 0, 5) == "+1278" || substr ( $num, 0, 5) == "+1703" || substr ( $num, 0, 5) == "+1571" || substr ( $num, 0, 5) == "+1828" || substr ( $num, 0, 5) == "+1606" || substr ( $num, 0, 5) == "+1404" || substr ( $num, 0, 5) == "+1770" || substr ( $num, 0, 5) == "+1678" || substr ( $num, 0, 5) == "+1470" || substr ( $num, 0, 5) == "+1609" || substr ( $num, 0, 5) == "+1762" || substr ( $num, 0, 5) == "+1706" || substr ( $num, 0, 5) == "+1331" || substr ( $num, 0, 5) == "+1737" || substr ( $num, 0, 5) == "+1512" || substr ( $num, 0, 5) == "+1667" || substr ( $num, 0, 5) == "+1443" || substr ( $num, 0, 5) == "+1410" || substr ( $num, 0, 5) == "+1225" || substr ( $num, 0, 5) == "+1425" || substr ( $num, 0, 5) == "+1360" || substr ( $num, 0, 5) == "+1240" || substr ( $num, 0, 5) == "+1610" || substr ( $num, 0, 5) == "+1484" || substr ( $num, 0, 5) == "+1835" || substr ( $num, 0, 5) == "+1406" || substr ( $num, 0, 5) == "+1228" || substr ( $num, 0, 5) == "+1659" || substr ( $num, 0, 5) == "+1205" || substr ( $num, 0, 5) == "+1952" || substr ( $num, 0, 5) == "+1208" || substr ( $num, 0, 5) == "+1857" || substr ( $num, 0, 5) == "+1617" || substr ( $num, 0, 5) == "+1802" || substr ( $num, 0, 5) == "+1631" || substr ( $num, 0, 5) == "+1203" || substr ( $num, 0, 5) == "+1475" || substr ( $num, 0, 5) == "+1718" || substr ( $num, 0, 5) == "+1347" || substr ( $num, 0, 5) == "+1979" || substr ( $num, 0, 5) == "+1818" || substr ( $num, 0, 5) == "+1747" || substr ( $num, 0, 5) == "+1856" || substr ( $num, 0, 5) == "+1239" || substr ( $num, 0, 5) == "+1319" || substr ( $num, 0, 5) == "+1447" || substr ( $num, 0, 5) == "+1217" || substr ( $num, 0, 5) == "+1843" || substr ( $num, 0, 5) == "+1681" || substr ( $num, 0, 5) == "+1304" || substr ( $num, 0, 5) == "+1980" || substr ( $num, 0, 5) == "+1704" || substr ( $num, 0, 5) == "+1423" || substr ( $num, 0, 5) == "+1872" || substr ( $num, 0, 5) == "+1773" || substr ( $num, 0, 5) == "+1312" || substr ( $num, 0, 5) == "+1413" || substr ( $num, 0, 5) == "+1708" || substr ( $num, 0, 5) == "+1464" || substr ( $num, 0, 5) == "+1283" || substr ( $num, 0, 5) == "+1513" || substr ( $num, 0, 5) == "+1931" || substr ( $num, 0, 5) == "+1440" || substr ( $num, 0, 5) == "+1216" || substr ( $num, 0, 5) == "+1573" || substr ( $num, 0, 5) == "+1803" || substr ( $num, 0, 5) == "+1614" || substr ( $num, 0, 5) == "+1380" || substr ( $num, 0, 5) == "+1925" || substr ( $num, 0, 5) == "+1361" || substr ( $num, 0, 5) == "+1214" || substr ( $num, 0, 5) == "+1972" || substr ( $num, 0, 5) == "+1469" || substr ( $num, 0, 5) == "+1764" || substr ( $num, 0, 5) == "+1650" || substr ( $num, 0, 5) == "+1276" || substr ( $num, 0, 5) == "+1563" || substr ( $num, 0, 5) == "+1937" || substr ( $num, 0, 5) == "+1386" || substr ( $num, 0, 5) == "+1940" || substr ( $num, 0, 5) == "+1720" || substr ( $num, 0, 5) == "+1303" || substr ( $num, 0, 5) == "+1313" || substr ( $num, 0, 5) == "+1679" || substr ( $num, 0, 5) == "+1620" || substr ( $num, 0, 5) == "+1218" || substr ( $num, 0, 5) == "+1715" || substr ( $num, 0, 5) == "+1534" || substr ( $num, 0, 5) == "+1848" || substr ( $num, 0, 5) == "+1732" || substr ( $num, 0, 5) == "+1915" || substr ( $num, 0, 5) == "+1908" || substr ( $num, 0, 5) == "+1607" || substr ( $num, 0, 5) == "+1814" || substr ( $num, 0, 5) == "+1760" || substr ( $num, 0, 5) == "+1442" || substr ( $num, 0, 5) == "+1541" || substr ( $num, 0, 5) == "+1458" || substr ( $num, 0, 5) == "+1812" || substr ( $num, 0, 5) == "+1701" || substr ( $num, 0, 5) == "+1910" || substr ( $num, 0, 5) == "+1810" || substr ( $num, 0, 5) == "+1954" || substr ( $num, 0, 5) == "+1754" || substr ( $num, 0, 5) == "+1479" || substr ( $num, 0, 5) == "+1260" || substr ( $num, 0, 5) == "+1682" || substr ( $num, 0, 5) == "+1817" || substr ( $num, 0, 5) == "+1559" || substr ( $num, 0, 5) == "+1352" || substr ( $num, 0, 5) == "+1409" || substr ( $num, 0, 5) == "+1219" || substr ( $num, 0, 5) == "+1970" || substr ( $num, 0, 5) == "+1616" || substr ( $num, 0, 5) == "+1231" || substr ( $num, 0, 5) == "+1920" || substr ( $num, 0, 5) == "+1274" || substr ( $num, 0, 5) == "+1336" || substr ( $num, 0, 5) == "+1864" || substr ( $num, 0, 5) == "+1254" || substr ( $num, 0, 5) == "+1985" || substr ( $num, 0, 5) == "+1959" || substr ( $num, 0, 5) == "+1860" || substr ( $num, 0, 5) == "+1516" || substr ( $num, 0, 5) == "+1808" || substr ( $num, 0, 5) == "+1832" || substr ( $num, 0, 5) == "+1713" || substr ( $num, 0, 5) == "+1281" || substr ( $num, 0, 5) == "+1938" || substr ( $num, 0, 5) == "+1256" || substr ( $num, 0, 5) == "+1936" || substr ( $num, 0, 5) == "+1317" || substr ( $num, 0, 5) == "+1515" || substr ( $num, 0, 5) == "+1949" || substr ( $num, 0, 5) == "+1769" || substr ( $num, 0, 5) == "+1601" || substr ( $num, 0, 5) == "+1731" || substr ( $num, 0, 5) == "+1904" || substr ( $num, 0, 5) == "+1551" || substr ( $num, 0, 5) == "+1201" || substr ( $num, 0, 5) == "+1870" || substr ( $num, 0, 5) == "+1913" || substr ( $num, 0, 5) == "+1975" || substr ( $num, 0, 5) == "+1816" || substr ( $num, 0, 5) == "+1308" || substr ( $num, 0, 5) == "+1262" || substr ( $num, 0, 5) == "+1845" || substr ( $num, 0, 5) == "+1865" || substr ( $num, 0, 5) == "+1337" || substr ( $num, 0, 5) == "+1765" || substr ( $num, 0, 5) == "+1863" || substr ( $num, 0, 5) == "+1717" || substr ( $num, 0, 5) == "+1740" || substr ( $num, 0, 5) == "+1517" || substr ( $num, 0, 5) == "+1307" || substr ( $num, 0, 5) == "+1956" || substr ( $num, 0, 5) == "+1575" || substr ( $num, 0, 5) == "+1702" || substr ( $num, 0, 5) == "+1580" || substr ( $num, 0, 5) == "+1859" || substr ( $num, 0, 5) == "+1501" || substr ( $num, 0, 5) == "+1562" || substr ( $num, 0, 5) == "+1323" || substr ( $num, 0, 5) == "+1310" || substr ( $num, 0, 5) == "+1213" || substr ( $num, 0, 5) == "+1502" || substr ( $num, 0, 5) == "+1978" || substr ( $num, 0, 5) == "+1351" || substr ( $num, 0, 5) == "+1806" || substr ( $num, 0, 5) == "+1434" || substr ( $num, 0, 5) == "+1339" || substr ( $num, 0, 5) == "+1781" || substr ( $num, 0, 5) == "+1478" || substr ( $num, 0, 5) == "+1608" || substr ( $num, 0, 5) == "+1603" || substr ( $num, 0, 5) == "+1507" || substr ( $num, 0, 5) == "+1660" || substr ( $num, 0, 5) == "+1641" || substr ( $num, 0, 5) == "+1830" || substr ( $num, 0, 5) == "+1901" || substr ( $num, 0, 5) == "+1786" || substr ( $num, 0, 5) == "+1305" || substr ( $num, 0, 5) == "+1414" || substr ( $num, 0, 5) == "+1612" || substr ( $num, 0, 5) == "+1251" || substr ( $num, 0, 5) == "+1334" || substr ( $num, 0, 5) == "+1630" || substr ( $num, 0, 5) == "+1615" || substr ( $num, 0, 5) == "+1724" || substr ( $num, 0, 5) == "+1504" || substr ( $num, 0, 5) == "+1917" || substr ( $num, 0, 5) == "+1646" || substr ( $num, 0, 5) == "+1212" || substr ( $num, 0, 5) == "+1973" || substr ( $num, 0, 5) == "+1862" || substr ( $num, 0, 5) == "+1716" || substr ( $num, 0, 5) == "+1510" || substr ( $num, 0, 5) == "+1341" || substr ( $num, 0, 5) == "+1432" || substr ( $num, 0, 5) == "+1405" || substr ( $num, 0, 5) == "+1531" || substr ( $num, 0, 5) == "+1402" || substr ( $num, 0, 5) == "+1927" || substr ( $num, 0, 5) == "+1689" || substr ( $num, 0, 5) == "+1407" || substr ( $num, 0, 5) == "+1321" || substr ( $num, 0, 5) == "+1269" || substr ( $num, 0, 5) == "+1364" || substr ( $num, 0, 5) == "+1270" || substr ( $num, 0, 5) == "+1445" || substr ( $num, 0, 5) == "+1267" || substr ( $num, 0, 5) == "+1215" || substr ( $num, 0, 5) == "+1623" || substr ( $num, 0, 5) == "+1602" || substr ( $num, 0, 5) == "+1480" || substr ( $num, 0, 5) == "+1878" || substr ( $num, 0, 5) == "+1412" || substr ( $num, 0, 5) == "+1763" || substr ( $num, 0, 5) == "+1626" || substr ( $num, 0, 5) == "+1248" || substr ( $num, 0, 5) == "+1772" || substr ( $num, 0, 5) == "+1971" || substr ( $num, 0, 5) == "+1503" || substr ( $num, 0, 5) == "+1207" || substr ( $num, 0, 5) == "+1401" || substr ( $num, 0, 5) == "+1719" || substr ( $num, 0, 5) == "+1919" || substr ( $num, 0, 5) == "+1984" || substr ( $num, 0, 5) == "+1530" || substr ( $num, 0, 5) == "+1775" || substr ( $num, 0, 5) == "+1804" || substr ( $num, 0, 5) == "+1951" || substr ( $num, 0, 5) == "+1540" || substr ( $num, 0, 5) == "+1585" || substr ( $num, 0, 5) == "+1309" || substr ( $num, 0, 5) == "+1815" || substr ( $num, 0, 5) == "+1779" || substr ( $num, 0, 5) == "+1252" || substr ( $num, 0, 5) == "+1916" || substr ( $num, 0, 5) == "+1989" || substr ( $num, 0, 5) == "+1831" || substr ( $num, 0, 5) == "+1801" || substr ( $num, 0, 5) == "+1385" || substr ( $num, 0, 5) == "+1210" || substr ( $num, 0, 5) == "+1935" || substr ( $num, 0, 5) == "+1858" || substr ( $num, 0, 5) == "+1619" || substr ( $num, 0, 5) == "+1628" || substr ( $num, 0, 5) == "+1415" || substr ( $num, 0, 5) == "+1408" || substr ( $num, 0, 5) == "+1669" || substr ( $num, 0, 5) == "+1805" || substr ( $num, 0, 5) == "+1661" || substr ( $num, 0, 5) == "+1424" || substr ( $num, 0, 5) == "+1627" || substr ( $num, 0, 5) == "+1369" || substr ( $num, 0, 5) == "+1707" || substr ( $num, 0, 5) == "+1941" || substr ( $num, 0, 5) == "+1906" || substr ( $num, 0, 5) == "+1912" || substr ( $num, 0, 5) == "+1570" || substr ( $num, 0, 5) == "+1206" || substr ( $num, 0, 5) == "+1564" || substr ( $num, 0, 5) == "+1318" || substr ( $num, 0, 5) == "+1301" || substr ( $num, 0, 5) == "+1227" || substr ( $num, 0, 5) == "+1712" || substr ( $num, 0, 5) == "+1605" || substr ( $num, 0, 5) == "+1574" || substr ( $num, 0, 5) == "+1509" || substr ( $num, 0, 5) == "+1417" || substr ( $num, 0, 5) == "+1636" || substr ( $num, 0, 5) == "+1435" || substr ( $num, 0, 5) == "+1314" || substr ( $num, 0, 5) == "+1557" || substr ( $num, 0, 5) == "+1651" || substr ( $num, 0, 5) == "+1727" || substr ( $num, 0, 5) == "+1662" || substr ( $num, 0, 5) == "+1209" || substr ( $num, 0, 5) == "+1209" || substr ( $num, 0, 5) == "+1315" || substr ( $num, 0, 5) == "+1253" || substr ( $num, 0, 5) == "+1850" || substr ( $num, 0, 5) == "+1813" || substr ( $num, 0, 5) == "+1419" || substr ( $num, 0, 5) == "+1567" || substr ( $num, 0, 5) == "+1785" || substr ( $num, 0, 5) == "+1947" || substr ( $num, 0, 5) == "+1520" || substr ( $num, 0, 5) == "+1918" || substr ( $num, 0, 5) == "+1430" || substr ( $num, 0, 5) == "+1903" || substr ( $num, 0, 5) == "+1757" || substr ( $num, 0, 5) == "+1586" || substr ( $num, 0, 5) == "+1202" || substr ( $num, 0, 5) == "+1847" || substr ( $num, 0, 5) == "+1224" || substr ( $num, 0, 5) == "+1561" || substr ( $num, 0, 5) == "+1316" || substr ( $num, 0, 5) == "+1302" || substr ( $num, 0, 5) == "+1774" || substr ( $num, 0, 5) == "+1508" || substr ( $num, 0, 5) == "+1914" || substr ( $num, 0, 5) == "+1928" || substr ( $num, 0, 5) == "+1808")
  {
    return 2;
  }

  // Group 3: Alaska (+1 907), Anguila (+1 264), Antártida (+672), Antigua e Barbuda (+1 268), Aruba (+297), Bahamas (+1 242), Barbados (+1 246), Belize (+501), Bermudas (+1 441), Bolívia (+591), Canadá (+1587, +1403, +1587, +1780, +1819, +1902, +1519, +1226, +1905, +1289, +1514, +1438, +1613, +1343, +1581, +1418, +1306, +1705, +1249, +1600, +1506, +1709, +1450, +1579, +1807, +1647, +1416, +1236, +1778, +1604, +1250, +1204, +1867), Colômbia (+57), Costa Rica (+506), Cuba (+53), Dominica (+1 767), El Salvador (+503), Equador (+593), Granada (+1 473), Groelândia (+299), Guadalupe (+590), Guatemala (+502), Guiana Inglesa (+592), Guiana Francesa (+594), Haiti (+509), Honduras (+504), Ilhas Cayman (+1 345), Ilhas Malvinas (+500), Ilhas Turquesas e Caicos (+1 649), Ilhas Virgens Americanas (+1 340), Ilhas Virgens Britânicas (+1 284), Jamaica (+1 876), Martinica (+596), México (+52), Montserrat (+1 664), Nicarágua (+505), Panamá (+507), Peru (+51), Porto Rico (+1 787, +1 939), República Dominicana (+1 809, +1 829, +1 849), Santa Lucia (+1 758), São Cristóvão e Névis (+1 869), São Pedro e Miguel (+508), São Vicente e Granadinas (+1 784), Suriname (+597), Trindad e Tobago (+1 868), Venezuela (+58) e Antilhas (+599):
  if ( substr ( $num, 0, 5) == "+1907" || substr ( $num, 0, 5) == "+1264" || substr ( $num, 0, 4) == "+672" || substr ( $num, 0, 5) == "+1268" || substr ( $num, 0, 4) == "+297" || substr ( $num, 0, 5) == "+1242" || substr ( $num, 0, 5) == "+1246" || substr ( $num, 0, 4) == "+501" || substr ( $num, 0, 5) == "+1441" || substr ( $num, 0, 4) == "+591" || substr ( $num, 0, 5) == "+1587" || substr ( $num, 0, 5) == "+1403" || substr ( $num, 0, 5) == "+1587" || substr ( $num, 0, 5) == "+1780" || substr ( $num, 0, 5) == "+1819" || substr ( $num, 0, 5) == "+1902" || substr ( $num, 0, 5) == "+1519" || substr ( $num, 0, 5) == "+1226" || substr ( $num, 0, 5) == "+1905" || substr ( $num, 0, 5) == "+1289" || substr ( $num, 0, 5) == "+1514" || substr ( $num, 0, 5) == "+1438" || substr ( $num, 0, 5) == "+1613" || substr ( $num, 0, 5) == "+1343" || substr ( $num, 0, 5) == "+1581" || substr ( $num, 0, 5) == "+1418" || substr ( $num, 0, 5) == "+1306" || substr ( $num, 0, 5) == "+1705" || substr ( $num, 0, 5) == "+1249" || substr ( $num, 0, 5) == "+1600" || substr ( $num, 0, 5) == "+1506" || substr ( $num, 0, 5) == "+1709" || substr ( $num, 0, 5) == "+1450" || substr ( $num, 0, 5) == "+1579" || substr ( $num, 0, 5) == "+1807" || substr ( $num, 0, 5) == "+1647" || substr ( $num, 0, 5) == "+1416" || substr ( $num, 0, 5) == "+1236" || substr ( $num, 0, 5) == "+1778" || substr ( $num, 0, 5) == "+1604" || substr ( $num, 0, 5) == "+1250" || substr ( $num, 0, 5) == "+1204" || substr ( $num, 0, 5) == "+1867" || substr ( $num, 0, 3) == "+57" || substr ( $num, 0, 4) == "+506" || substr ( $num, 0, 3) == "+53" || substr ( $num, 0, 5) == "+1767" || substr ( $num, 0, 4) == "+503" || substr ( $num, 0, 4) == "+593" || substr ( $num, 0, 5) == "+1473" || substr ( $num, 0, 4) == "+299" || substr ( $num, 0, 4) == "+590" || substr ( $num, 0, 4) == "+502" || substr ( $num, 0, 4) == "+592" || substr ( $num, 0, 4) == "+594" || substr ( $num, 0, 4) == "+509" || substr ( $num, 0, 4) == "+504" || substr ( $num, 0, 5) == "+1345" || substr ( $num, 0, 4) == "+500" || substr ( $num, 0, 5) == "+1649" || substr ( $num, 0, 5) == "+1758" || substr ( $num, 0, 5) == "+1869" || substr ( $num, 0, 4) == "+508" || substr ( $num, 0, 5) == "+1784" || substr ( $num, 0, 4) == "+597" || substr ( $num, 0, 3) == "+52" || substr ( $num, 0, 5) == "+1664" || substr ( $num, 0, 4) == "+505" || substr ( $num, 0, 4) == "+507" || substr ( $num, 0, 3) == "+51" || substr ( $num, 0, 5) == "+1787" || substr ( $num, 0, 5) == "+1939" || substr ( $num, 0, 5) == "+1809" || substr ( $num, 0, 5) == "+1829" || substr ( $num, 0, 5) == "+1849" || substr ( $num, 0, 5) == "+1758" || substr ( $num, 0, 5) == "+1869" || substr ( $num, 0, 4) == "+508" || substr ( $num, 0, 5) == "+1784" || substr ( $num, 0, 4) == "+597" || substr ( $num, 0, 5) == "+1868" || substr ( $num, 0, 3) == "+58" || substr ( $num, 0, 4) == "+599")
  {
    return 3;
  }

  // Group 4: Portugal (+351), Açores (+351) e Ilha da Madeira (+351):
  if ( substr ( $num, 0, 4) == "351")
  {
    return 4;
  }

  // Group 5: Alemanha (+49), Andorra (+376), Áustria (+43), Bélgica (+32), Dinamarca (+45), Espanha (+34), Finlândia (+358), França (+33), Holanda (Países Baixos) (+31), Irlanda (+353), Itália (+39), Liechtenstein (+423), Noruega (+47), Reino Unido (+44), Suécia (+46) e Suíça (+41):
  if ( substr ( $num, 0, 3) == "+49" || substr ( $num, 0, 4) == "+376" || substr ( $num, 0, 3) == "+43" || substr ( $num, 0, 3) == "+32" || substr ( $num, 0, 3) == "+45" || substr ( $num, 0, 3) == "+34" || substr ( $num, 0, 4) == "+358" || substr ( $num, 0, 3) == "+33" || substr ( $num, 0, 3) == "+31" || substr ( $num, 0, 4) == "+353" || substr ( $num, 0, 3) == "+39" || substr ( $num, 0, 4) == "+423" || substr ( $num, 0, 3) == "+47" || substr ( $num, 0, 3) == "+44" || substr ( $num, 0, 3) == "+46" || substr ( $num, 0, 3) == "+41")
  {
    return 5;
  }

  // Group 6: Albânia (+355), Arábia Saudita (+966), Armênia (+374), Azerbaijão (+994), Bareine (+973), Belarus (+375), Bósnia-Herzegovina (+387), Bulgária (+359), Catar (+974), Chipre (+357), Croácia (+385), Emirados Árabes Unidos (+971), Eslováquia (+421), Eslovênia (+386), Estônia (+372), Geórgia (+995), Grécia (+30), Hungria (+36), Iêmen (+967), Ilhas Feroe (+298), Irã (+98), Iraque (+964), Islândia (+354), Israel (+972), Jordânia (+962), Kuaite (+965), Letônia (+371), Líbano (+961), Lituânia (+370), Luxemburgo (+352), Macedônia (+389), Malta (+356), Moldova (+373), Mônaco (+377), Omã (+968), Palestina (+970), Polônia (+48), República Tcheca (+420), Romênia (+40), Rússia (+7472, +7483, +7493, +7401, +7484, +7494, +7471, +7474, +7495, +7496, +7486, +7491, +7481, +7475, +7487, +7482, +7492, +7473, +7485), San Marino (+378), Sérvia (+381) e Montenegro (+382), Síria (+963), Turquia (+90), Ucrânia (+380) e Vaticano (+39):
  if ( substr ( $num, 0, 4) == "+355" || substr ( $num, 0, 4) == "+966" || substr ( $num, 0, 4) == "+374" || substr ( $num, 0, 4) == "+994" || substr ( $num, 0, 4) == "+973" || substr ( $num, 0, 4) == "+375" || substr ( $num, 0, 4) == "+387" || substr ( $num, 0, 4) == "+359" || substr ( $num, 0, 4) == "+974" || substr ( $num, 0, 4) == "+357" || substr ( $num, 0, 4) == "+385" || substr ( $num, 0, 4) == "+971" || substr ( $num, 0, 4) == "+421" || substr ( $num, 0, 4) == "+386" || substr ( $num, 0, 4) == "+372" || substr ( $num, 0, 4) == "+995" || substr ( $num, 0, 3) == "+30" || substr ( $num, 0, 3) == "+36" || substr ( $num, 0, 4) == "+967" || substr ( $num, 0, 4) == "+298" || substr ( $num, 0, 3) == "+98" || substr ( $num, 0, 4) == "+964" || substr ( $num, 0, 4) == "+354" || substr ( $num, 0, 4) == "+972" || substr ( $num, 0, 4) == "+962" || substr ( $num, 0, 4) == "+965" || substr ( $num, 0, 4) == "+371" || substr ( $num, 0, 4) == "+961" || substr ( $num, 0, 4) == "+370" || substr ( $num, 0, 4) == "+352" || substr ( $num, 0, 4) == "+389" || substr ( $num, 0, 4) == "+356" || substr ( $num, 0, 4) == "+373" || substr ( $num, 0, 4) == "+377" || substr ( $num, 0, 4) == "+968" || substr ( $num, 0, 4) == "+970" || substr ( $num, 0, 3) == "+48" || substr ( $num, 0, 4) == "+420" || substr ( $num, 0, 3) == "+40" || substr ( $num, 0, 5) == "+7472" || substr ( $num, 0, 5) == "+7483" || substr ( $num, 0, 5) == "+7493" || substr ( $num, 0, 5) == "+7401" || substr ( $num, 0, 5) == "+7484" || substr ( $num, 0, 5) == "+7494" || substr ( $num, 0, 5) == "+7471" || substr ( $num, 0, 5) == "+7474" || substr ( $num, 0, 5) == "+7495" || substr ( $num, 0, 5) == "+7496" || substr ( $num, 0, 5) == "+7486" || substr ( $num, 0, 5) == "+7491" || substr ( $num, 0, 5) == "+7481" || substr ( $num, 0, 5) == "+7475" || substr ( $num, 0, 5) == "+7487" || substr ( $num, 0, 5) == "+7482" || substr ( $num, 0, 5) == "+7492" || substr ( $num, 0, 5) == "+7473" || substr ( $num, 0, 5) == "+7485" || substr ( $num, 0, 4) == "+378" || substr ( $num, 0, 4) == "+381" || substr ( $num, 0, 4) == "+382" || substr ( $num, 0, 4) == "+963" || substr ( $num, 0, 3) == "+90" || substr ( $num, 0, 4) == "+380" || substr ( $num, 0, 3) == "+39")
  {
    return 6;
  }

  // Group 7: Austrália (+61) e Japão (+81):
  if ( substr ( $num, 0, 3) == "+61" || substr ( $num, 0, 3) == "+81")
  {
    return 7;
  }

  // Group 8: África do Sul (+27), Angola (+244), Argélia (+213), Benin (+229), Botsuana (+267), Burkina Faso (+226), Burundi (+257), Cabo Verde (+238), Camarões (+237), Chade (+235), Costa do Marfim (+225), Djibuti (+253), Egito (+20), Eritréia (+291), Etiópia (+251), Gabão (+241), Gâmbia (+220), Gana (+233), Guiné (+224), Guiné-Bissau (+245), Guiné-Equatorial (+240), Ilhas Ascensão (+247), Ilhas Comores (+269), Ilhas Maurício (+230), Ilhas Mayotte (+262), Lesoto (+266), Libéria (+231), Líbia (+218), Madagascar (+261), Maláwi (+265), Mali (+223), Marrocos (+212), Mauritânia (+222), Moçambique (+258), Namíbia (+264), Níger (+227), Nigéria (+234), Quênia (+254), República Centro-Africana (+236), República Democrática do Congo (+243), República do Congo (+242), Reunião (+262), Ruanda (+250), Santa Helena (+290), São Tomé e Príncipe (+239), Seicheles (+248), Senegal (+221), Serra Leoa (+232), Somália (+252), Suazilândia (+268), Sudão (+249), Tanzânia (+255), Togo (+228), Tunísia (+216), Uganda (+256), Zâmbia (+260), Zimbábue (+263):
  if ( substr ( $num, 0, 3) == "+27" || substr ( $num, 0, 4) == "+244" || substr ( $num, 0, 4) == "+213" || substr ( $num, 0, 4) == "+229" || substr ( $num, 0, 4) == "+267" || substr ( $num, 0, 4) == "+226" || substr ( $num, 0, 4) == "+257" || substr ( $num, 0, 4) == "+238" || substr ( $num, 0, 4) == "+237" || substr ( $num, 0, 4) == "+235" || substr ( $num, 0, 4) == "+225" || substr ( $num, 0, 4) == "+253" || substr ( $num, 0, 3) == "+20" || substr ( $num, 0, 4) == "+291" || substr ( $num, 0, 4) == "+251" || substr ( $num, 0, 4) == "+241" || substr ( $num, 0, 4) == "+220" || substr ( $num, 0, 4) == "+233" || substr ( $num, 0, 4) == "+224" || substr ( $num, 0, 4) == "+245" || substr ( $num, 0, 4) == "+240" || substr ( $num, 0, 4) == "+247" || substr ( $num, 0, 4) == "+269" || substr ( $num, 0, 4) == "+230" || substr ( $num, 0, 4) == "+262" || substr ( $num, 0, 4) == "+266" || substr ( $num, 0, 4) == "+231" || substr ( $num, 0, 4) == "+218" || substr ( $num, 0, 4) == "+261" || substr ( $num, 0, 4) == "+265" || substr ( $num, 0, 4) == "+223" || substr ( $num, 0, 4) == "+212" || substr ( $num, 0, 4) == "+222" || substr ( $num, 0, 4) == "+258" || substr ( $num, 0, 4) == "+264" || substr ( $num, 0, 4) == "+227" || substr ( $num, 0, 4) == "+234" || substr ( $num, 0, 4) == "+254" || substr ( $num, 0, 4) == "+236" || substr ( $num, 0, 4) == "+243" || substr ( $num, 0, 4) == "+242" || substr ( $num, 0, 4) == "+262" || substr ( $num, 0, 4) == "+250" || substr ( $num, 0, 4) == "+290" || substr ( $num, 0, 4) == "+239" || substr ( $num, 0, 4) == "+248" || substr ( $num, 0, 4) == "+221" || substr ( $num, 0, 4) == "+232" || substr ( $num, 0, 4) == "+252" || substr ( $num, 0, 4) == "+268" || substr ( $num, 0, 4) == "+249" || substr ( $num, 0, 4) == "+255" || substr ( $num, 0, 4) == "+228" || substr ( $num, 0, 4) == "+216" || substr ( $num, 0, 4) == "+256" || substr ( $num, 0, 4) == "+260" || substr ( $num, 0, 4) == "+263")
  {
    return 8;
  }

  // Group 9: Afeganistão (+93), Bangladesh (+880), Brunei (+673), Butão (+975), Camboja (+855), Cazaquistão (+7317, +7329, +7313, +7327, +7330, +7717, +7312, +7321, +7314, +7324, +7336, +7318, +7315, +7322, +7325, +7328, +7311, +7323, +7326, +7310), China (+86), Cingapura (+65), Coréia do Norte (+850), Coréia do Sul (+82), Diego Garcia (+246), Estados Federados da Micronésia (+691), Fiji (+679), Filipinas (+63), Guam (+1671), Hong-Kong (+852), Ilha Christmas (+6189164), Ilha de Pitcairn (+64), Ilha Johnston (???), Ilha Niue (+683), Ilha Norfolk (+6723), Ilhas Coco (+6189162), Ilha Cook (+682), Ilha Wake (+839), Ilhas de Wallis e Futuna (+681), Ilhas Mariana do Norte (+1670), Ilhas Marshall (+692), Ilhas Salomão (+677), Índia (+91), Indonésia (+62), Kiribati (+686), Laos (+856), Macau (+853), Malásia (+60), Maldivas (+960), Midway (+838), Mongólia (+976), Myanmar (+95), Nauru (+674), Nepal (+977), Nova Caledônia (+687), Nova Zelândia (+64), Palau (+680), Papua-Nova Guiné (+675), Paquistão (+92), Polinésia Francesa (+689), Quirguízia (+996), Samoa (+685), Samoa Americana (+1684), Sri Lanka (+94), Tadjiquistão (+992), Tailândia (+66), Taiwan (+886), Timor-Leste (+670), Tonga (+676), Toquelau (+690), Turcomenistão (+993), Tuvalu (+688), Uzbequistão (+998), Vanuato (+678), Vietnã (+84) e Ilhas do Pacífico (exceto Havaí) (???):
  if ( substr ( $num, 0, 3) == "+93" || substr ( $num, 0, 4) == "+880" || substr ( $num, 0, 4) == "+673" || substr ( $num, 0, 4) == "+975" || substr ( $num, 0, 4) == "+855" || substr ( $num, 0, 5) == "+7317" || substr ( $num, 0, 5) == "+7329" || substr ( $num, 0, 5) == "+7313" || substr ( $num, 0, 5) == "+7327" || substr ( $num, 0, 5) == "+7330" || substr ( $num, 0, 5) == "+7717" || substr ( $num, 0, 5) == "+7312" || substr ( $num, 0, 5) == "+7321" || substr ( $num, 0, 5) == "+7314" || substr ( $num, 0, 5) == "+7324" || substr ( $num, 0, 5) == "+7336" || substr ( $num, 0, 5) == "+7318" || substr ( $num, 0, 5) == "+7315" || substr ( $num, 0, 5) == "+7322" || substr ( $num, 0, 5) == "+7325" || substr ( $num, 0, 5) == "+7328" || substr ( $num, 0, 5) == "+7311" || substr ( $num, 0, 5) == "+7323" || substr ( $num, 0, 5) == "+7326" || substr ( $num, 0, 5) == "+7310" || substr ( $num, 0, 3) == "+86" || substr ( $num, 0, 3) == "+65" || substr ( $num, 0, 4) == "+850" || substr ( $num, 0, 3) == "+82" || substr ( $num, 0, 4) == "+246" || substr ( $num, 0, 4) == "+691" || substr ( $num, 0, 4) == "+679" || substr ( $num, 0, 3) == "+63" || substr ( $num, 0, 5) == "+1671" || substr ( $num, 0, 4) == "+852" || substr ( $num, 0, 8) == "+6189164" || substr ( $num, 0, 3) == "+64" || substr ( $num, 0, 4) == "+683" || substr ( $num, 0, 5) == "+6723" || substr ( $num, 0, 8) == "+6189162" || substr ( $num, 0, 4) == "+682" || substr ( $num, 0, 4) == "+839" || substr ( $num, 0, 4) == "+681" || substr ( $num, 0, 5) == "+1670" || substr ( $num, 0, 4) == "+692" || substr ( $num, 0, 4) == "+677" || substr ( $num, 0, 3) == "+91" || substr ( $num, 0, 3) == "+62" || substr ( $num, 0, 4) == "+686" || substr ( $num, 0, 4) == "+856" || substr ( $num, 0, 4) == "+853" || substr ( $num, 0, 3) == "+60" || substr ( $num, 0, 4) == "+960" || substr ( $num, 0, 4) == "+838" || substr ( $num, 0, 4) == "+976" || substr ( $num, 0, 3) == "+95" || substr ( $num, 0, 4) == "+674" || substr ( $num, 0, 4) == "+977" || substr ( $num, 0, 4) == "+687" || substr ( $num, 0, 3) == "+64" || substr ( $num, 0, 4) == "+680" || substr ( $num, 0, 4) == "+675" || substr ( $num, 0, 3) == "+92" || substr ( $num, 0, 4) == "+689" || substr ( $num, 0, 4) == "+996" || substr ( $num, 0, 4) == "+685" || substr ( $num, 0, 5) == "+1684" || substr ( $num, 0, 3) == "+94" || substr ( $num, 0, 4) == "+992" || substr ( $num, 0, 3) == "+66" || substr ( $num, 0, 4) == "+886" || substr ( $num, 0, 4) == "+670" || substr ( $num, 0, 4) == "+676" || substr ( $num, 0, 4) == "+690" || substr ( $num, 0, 4) == "+993" || substr ( $num, 0, 4) == "+688" || substr ( $num, 0, 4) == "+998" || substr ( $num, 0, 4) == "+678" || substr ( $num, 0, 3) == "+84")
  {
    return 9;
  }

  // If none matched, return false:
  return false;
}

/**
 * Process parameters
 */
$debug = false;
for ( $x = 1; $x < $argc; $x++)
{
  switch ( $argv[$x])
  {
    case "--debug":
    case "-d":
      $debug = true;
      break;
    case "--help":
    case "-h":
      echo "Usage: " . basename ( $argv[0]) . " [--help|-h] [--debug|-d]\n";
      echo "  --help|-h:    Show this help informations\n";
      echo "  --debug|-d:   Enable debug messages (do not fork the daemon)\n";
      exit ();
      break;
    default:
      echo "ERROR: Invalid parameter \"" . $argv[$x] . "\"!\n";
      exit ( -1);
      break;
  }
}

/**
 * Conect to the database
 */
echo "Executing: Connecting to database... ";
if ( ! $_in["mysql"]["id"] = @new mysqli ( $_in["mysql"]["hostname"] . ( ! empty ( $_in["mysql"]["port"]) ? ":" . $_in["mysql"]["port"] : ""), $_in["mysql"]["username"], $_in["mysql"]["password"], $_in["mysql"]["database"]))
{
  writeLog ( "Cannot connect to database server!", VoIP_LOG_FATAL);
}
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * Fetch gateways database
 */
echo "Executing: Fetching gateways database... ";
if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE `Active` = 'Y'"))
{
  writeLog ( "Cannot request gateways informations to database!", VoIP_LOG_FATAL);
}
$gateways = array ();
while ( $gateway = $result->fetch_assoc ())
{
  $gateway["Routes"] = json_decode ( $gateway["Routes"], true);
  $gateway["Translations"] = json_decode ( $gateway["Translations"], true);
  if ( ! $gateway["Config"] == "ANATEL")
  {
    $newroutes = array ();
    foreach ( $gateway["Routes"] as $id => $route)
    {
      $routelen = 0;
      $group = false;
      $staticroute = true;
      $tmp = $route["route"];
      for ( $x = 0; $x <= strlen ( $route["route"]); $x++)
      {
        $char = ord ( substr ( $route["route"], $x, 1));
        if ( $group)
        {
          if ( $char == 93)
          {
            $group = false;
          }
        } else {
          if ( $char == 43 || ( $char >= 48 && $char <= 57))
          {
            $routelen++;
          }
          if ( $char == 91 && ! $group)
          {
            $group = true;
            $staticroute = false;
            $routelen++;
          }
        }
      }
      $gateway["Routes"][$id]["static"] = $staticroute;
      $gateway["Routes"][$id]["length"] = $routelen;
      $newroutes[$routelen][] = $gateway["Routes"][$id];
    }
    krsort ( $newroutes);
    $gateway["Routes"] = array ();
    foreach ( $newroutes as $addroute)
    {
      $gateway["Routes"] = array_merge ( $gateway["Routes"], $addroute);
    }
  } else {
    $gateway["ANATEL"] = json_decode ( $gateway["ANATEL"], true);
  }
  $gateways[] = $gateway;
}
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * If possible, change process name
 */
if ( function_exists ( "setproctitle"))
{
  setproctitle ( "VoIP Domain FastAGI daemon");
}

/**
 * Change effective UID/GID to an unprivileged user
 */
echo "Executing: Changing effective UID/GID... ";
if ( ! $uid = posix_getpwnam ( $_in["daemon"]["uid"]))
{
  writeLog ( "Cannot check for the user \"" . $_in["daemon"]["uid"] . "\"!", VoIP_LOG_FATAL);
}
if ( ! $gid = posix_getgrnam ( $_in["daemon"]["gid"]))
{
  writeLog ( "Cannot check for the group \"" . $_in["daemon"]["gid"] . "\"!", VoIP_LOG_FATAL);
}
if ( ! posix_setgid ( $gid["gid"]))
{
  writeLog ( "Cannot change to GID " . $gid["gid"] . " \"" . $_in["daemon"]["gid"] . "\"!", VoIP_LOG_FATAL);
}
if ( ! posix_setuid ( $uid["uid"]))
{
  writeLog ( "Cannot change to UID " . $uid["uid"] . " \"" . $_in["daemon"]["uid"] . "\"!", VoIP_LOG_FATAL);
}
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * Create new socket and start listening port
 */
echo "Executing: Creating TCP socket at port " . $_in["daemon"]["port"] . "... ";
$socket = socket_create ( AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option ( $socket, SOL_SOCKET, SO_REUSEADDR, 1);
if ( ! socket_bind ( $socket, 0, $_in["daemon"]["port"]))
{
  writeLog ( "Cannot bind to TCP port " . $_in["daemon"]["port"] . "!", VoIP_LOG_FATAL);
}
socket_listen ( $socket, $_in["daemon"]["max_clients"]);
echo chr ( 27) . "[1;37m" . gettext ( "OK") . chr ( 27) . "[1;0m\n";

/**
 * Show start of operations message
 */
echo "Everything done. Waiting for connections!\n\n";

/**
 * Log system initialization
 */
writeLog ( "VoIP Domain FastAGI daemon initialized.");

/**
 * Fork process to daemon mode (except if in debug mode)
 */
error_reporting ( E_ERROR);
set_time_limit ( 0);
if ( ! $debug)
{
  $pid = pcntl_fork ();
  if ( $pid == -1)
  {
    writeLog ( "Cannot fork process!", VoIP_LOG_FATAL);
  }
  if ( $pid)
  {
    exit ();
  }
}

/**
 * Instantiate client array
 */
$clients = array ( "0" => array ( "socket" => $socket));

/**
 * Start infinite loop to process connections
 */
while ( true)
{
  $read[0] = $socket;

  for ( $i = 1; $i <= count ( $clients); ++$i)
  {
    if ( $clients[$i] != NULL)
    {
      $read[$i + 1] = $clients[$i]["socket"];
    }
  }

  $ready = socket_select ( $read, $write = NULL, $except = NULL, $tv_sec = NULL);

  if ( in_array ( $socket, $read))
  {
    for ( $i = 1; $i <= $_in["daemon"]["max_clients"]; ++$i)
    {
      if ( ! isset ( $clients[$i]))
      {
        $clients[$i]["socket"] = socket_accept ( $socket);

        socket_getpeername ( $clients[$i]["socket"], $ip, $port);

        $clients[$i]["ip"] = $ip;
        $clients[$i]["port"] = $port;
        $clients[$i]["vars"] = array ();
        $clients[$i]["headers"] = true;
        $clients[$i]["buffer"] = array ();
        $clients[$i]["last"] = "";

        writeLog ( "New client connected: " . $clients[$i]["ip"] . ":" . $clients[$i]["port"]);
        break;
      } else {
        if ( $i == $_in["daemon"]["max_clients"] - 1)
        {
          writeLog ( "Too many Clients connected!", VoIP_LOG_WARNING);
        }
      }

      if ( $ready < 1)
      {
        continue;
      }
    }
  }

  for ( $i = 1; $i <= $_in["daemon"]["max_clients"]; ++$i)
  {
    if ( in_array ( $clients[$i]["socket"], $read))
    {
      $data = @socket_read ( $clients[$i]["socket"], 65535, PHP_NORMAL_READ);

      if ( $data === FALSE)
      {
        writeLog ( "Client " . $i . " (" . $clients[$i]["ip"] . ":" . $clients[$i]["port"] . ") disconnected!", VoIP_LOG_WARNING);
        unset ( $clients[$i]);
        continue;
      }

      $data = trim ( $data);

      if ( empty ( $data))
      {
        if ( $clients[$i]["headers"])
        {
          $clients[$i]["headers"] = false;

          // Check for requested application:
          switch ( $clients[$i]["vars"]["network_script"])
          {
            // LCR application (Least Cost Route):
            //  Arg 1: Destination (non geographic code or E.164 standard)
            case "lcr":
              $num = preg_replace ( "/[^0-9+]/", "", $clients[$i]["vars"]["arg_1"]);
              $defaultgw = explode ( ",", $clients[$i]["vars"]["arg_2"]);
              writeLog ( "Client " . $i . " (" . $clients[$i]["ip"] . ":" . $clients[$i]["port"] . ") requested application \"lcr\" to \"" . $num . "\" (default gw " . implode ( ",", $defaultgw) . ").");

              // Check database connection:
              mysql_check ();

              // Calculate LCR if it's an E.164 number
              if ( substr ( $num, 0, 1) == "+")
              {
                // Check if number is from the same country
                // TODO: Este código é MUITO direcionado ao padrão ANATEL e Brasileiro. Revisar para fazer algo mais global.
                if ( substr ( $num, 1, strlen ( $_in["daemon"]["country"])) == $_in["daemon"]["country"])
                {
                  // Check if destination is landline
                  if ( substr ( $num, 5, 1) >= 2 && substr ( $num, 5, 1) <= 5)
                  {
                    $target = "Landline";

                    // Check CNL (National Location Database) of destination (to use at ANATEL standard) if not national number starting with 3003, 4003 ou 4004
                    if ( substr ( $num, 5, 4) != "3003" && substr ( $num, 5, 4) != "4003" && substr ( $num, 5, 4) != "4004")
                    {
                      $destination = array ();
                      if ( $result = @$_in["mysql"]["id"]->query ( "SELECT `Centrais`.`Localidade`, `CNL`.`Latitude`, `CNL`.`Longitude` FROM `ANATEL`.`CNL` LEFT JOIN `ANATEL`.`Centrais` ON `Centrais`.`Localidade` = `CNL`.`Codigo` WHERE `Centrais`.`Inicial` <= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $num, 3)) . " AND `Centrais`.`Final` >= " . $_in["mysql"]["id"]->real_escape_string ( (int) substr ( $num, 3)) . " LIMIT 0,1"))
                      {
                        if ( $result->num_rows == 1)
                        {
                          $destination = $result->fetch_assoc ();
                        }
                      }
                      if ( sizeof ( $destination) == 0)
                      {
                        writeLog ( "Cannot find the location of number " . $num . "!", VoIP_LOG_WARNING);
                        writeLog ( "MySQL error: " . mysql_error ( $_in["mysql"]["id"]), VoIP_LOG_NOTICE);
                      }
                    }
                  }
                  // Check if destination is mobile
                  if ( substr ( $num, 5, 1) >= 6 && substr ( $num, 5, 1) <= 9)
                  {
                    $target = "Mobile";
                  }
                } else {
                  // Otherwise, it's an international call
                  $target = "international";
                }
                switch ( $target)
                {
                  case "Landline":
                    /**
                     * Check fare type
                     *
                     * 00:00:00 to 05:59:59: Super-Reduced
                     * 06:00:00 to 06:59:59: Reduced
                     * Sundays from 06:00:00 to 23:59:59: Reduced
                     * National holidays from 06:00:00 to 23:59:59: Reduced
                     * Saturdays from 07:00:00 to 13:59:59: Normal
                     * Saturdays from 14:00:00 to 23:59:59: Reduced
                     * Monday to friday from 07:00:00 to 08:59:59: Normal
                     * Monday to friday from 09:00:00 to 11:59:59: Differentiated
                     * Monday to friday from 12:00:00 to 13:59:59: Normal
                     * Monday to friday from 14:00:00 to 17:59:59: Differentiated
                     * Monday to friday from 18:00:00 to 20:59:59: Normal
                     * Monday to friday from 21:00:00 to 23:59:59: Reduced
                     */
                    $time = (int) date ( "G");
                    $day = (int) date ( "j");
                    $month = (int) date ( "n");
                    $week = (int) date ( "w");
                    if ( $time <= 5)			// 00:00:00 to 05:59:59
                    {
                      $type = "s";
                      break;
                    }
                    if ( $time <= 6			// 06:00:00 to 06:59:59
                      || $week == 0			// Sundays
                      || ( $day == 1 && $month == 1)    // January 1st: New year's day
                      || ( $day == 21 && $month == 4)   // April 21: Tiradentes
                      || ( $day == 1 && $month == 5)    // May 1st: Labor day
                      || ( $day == 7 && $month == 9)    // September 7: Independency
                      || ( $day == 12 && $month == 10)  // October 12: Our Lady Aparecida
                      || ( $day == 2 && $month == 11)   // November 2nd: All Soul's day
                      || ( $day == 15 && $month == 11)  // November 15: Republic Proclamation Day
                      || ( $day == 25 && $month == 12)  // December 25: Christmas Day
                    )
                    {
                      $type = "r";
                      break;
                    }
                    if ( $week == 6 && $time <= 13)	// Saturdays from 07:00:00 to 13:59:59
                    {
                      $type = "n";
                      break;
                    }
                    if ( $week == 6 && $time >= 14)	// Saturdays from 14:00:00 to 23:59:59
                    {
                      $type = "r";
                      break;
                    }
                    if ( $time <= 8)			// Monday to friday from 07:00:00 to 08:59:59
                    {
                      $type = "n";
                      break;
                    }
                    if ( $time <= 11)			// Monday to friday from 09:00:00 to 11:59:59
                    {
                      $type = "d";
                      break;
                    }
                    if ( $time <= 13)			// Monday to friday from 12:00:00 to 13:59:59
                    {
                      $type = "n";
                      break;
                    }
                    if ( $time <= 17)			// Monday to friday from 14:00:00 to 17:59:59
                    {
                      $type = "d";
                      break;
                    }
                    if ( $time <= 20)			// Monday to friday from 18:00:00 to 20:59:59
                    {
                      $type = "n";
                      break;
                    }
                    // Monday to friday from 21:00:00 to 23:59:59
                    $type = "r";
                    break;
                  case "Mobile":
                    /**
                     * Check fare type
                     *
                     * Sundays: Reduced
                     * National holidays: Reduced
                     * 00:00:00 to 06:59:59: Reduced
                     * 07:00:00 to 20:59:59: Normal
                     * 21:00:00 to 23:59:59: Reduced
                     */
                    $time = (int) date ( "G");
                    $day = (int) date ( "j");
                    $month = (int) date ( "n");
                    $week = (int) date ( "w");
                    if ( $week == 0			// Sundays
                      || $time <= 6			// 00:00:00 to 06:59:59
                      || $time >= 21			// 21:00:00 to 23:59:59
                      || ( $day == 1 && $month == 1)    // January 1st: New year's day
                      || ( $day == 21 && $month == 4)   // April 21: Tiradentes
                      || ( $day == 1 && $month == 5)    // May 1st: Labor day
                      || ( $day == 7 && $month == 9)    // September 7: Independency
                      || ( $day == 12 && $month == 10)  // October 12: Our Lady Aparecida
                      || ( $day == 2 && $month == 11)   // November 2nd: All Soul's day
                      || ( $day == 15 && $month == 11)  // November 15: Republic Proclamation Day
                      || ( $day == 25 && $month == 12)  // December 25: Christmas Day
                    )
                    {
                      $type = "Reduced";
                    } else {
                      $type = "Normal";
                    }
                    break;
                }

                /**
                 * Process all active output gateways
                 */
                $routes = array ();
                foreach ( $gateways as $gateway)
                {
                  // Process ANATEL standard type gateways
                  if ( $gateway["Config"] == "ANATEL")
                  {
                    // If mobile, check for mobile fares
                    if ( $target == "Mobile")
                    {
                      // Discover the destination mobile company
                      // TODO: Implementar suporte a consulta de portabilidade!
                      // A ANATEL removeu da base do Cadastro Nacional de
                      // Localidade e prefixos a base de números móveis, devido
                      // a portabilidade. Por este motivo não temos como afirmar
                      // ou sequer presumir a operadora de um dado número. Por
                      // isto foi implementado o campo de operadora
                      // "Desconhecida", que será utilizada até implementação de
                      // um sistema de pesquisa de portabilidade. Este é um
                      // ponto um pouco controverso, pois a ANATEL sinaliza a
                      // muito tempo a extinção da distinção de operadoras,
                      // visando uma tarifa única para móvel.
                      $company = "desconhecida";
                      if ( empty ( $company))
                      {
                        continue;
                      }
                      // TODO: Corrigir essa bagunça da tabela da ANATEL, sanear os dados e padronizar para as tabelas do sistema!
                      // VC1: Same area code
                      if ( substr ( $num, 3, 2) == $gateway["AreaCode"])
                      {
                        $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => substr ( $num, 5), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => ( $type == "Normal" ? $gateway["ANATEL"]["mobile_" . $company . "_1"] : $gateway["ANATEL"]["mobile_" . $company . "_1"] * 0.7));
                        continue;
                      }
                      // VC2: First area code digit equal
                      if ( substr ( $num, 3, 1) == substr ( $gateway["AreaCode"], 0, 1))
                      {
                        $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => "0" . $gateway["ANATEL"]["interstate"] . substr ( $num, 3), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => ( $type == "Normal" ? $gateway["ANATEL"]["mobile_" . $company . "_2"] : $gateway["ANATEL"]["mobile_" . $company . "_2"] * 0.7));
                        continue;
                      }
                      // VC3: Different area code
                      $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => "0" . $gateway["ANATEL"]["interstate"] . substr ( $num, 3), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => ( $type == "Normal" ? $gateway["ANATEL"]["mobile_" . $company . "_3"] : $gateway["ANATEL"]["mobile_" . $company . "_3"] * 0.7));
                      continue;
                    }

                    // Landline
                    if ( $target == "Landline")
                    {
                      // Check if it's destination to national numbers 3003, 4003 ou 4004
                      if ( substr ( $num, 5, 4) == "3003" || substr ( $num, 5, 4) == "4003" || substr ( $num, 5, 4) == "4004")
                      {
                        $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => substr ( $num, 5), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => $gateway["ANATEL"]["local"]);
                        continue;
                      }

                      // Check if it's local gateway call
                      if ( $gateway["ANATEL"]["CNL"] == $destination["Localidade"])
                      {
                        $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => substr ( $num, 5), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => $gateway["ANATEL"]["local"]);
                        continue;
                      }

                      // Check if it's conurbated area
                      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Codigo` FROM `ANATEL`.`AreaLocal` WHERE `Codigo` IN (SELECT `Codigo` FROM `ANATEL`.`AreaLocal` WHERE `CNL` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $gateway["ANATEL"]["CNL"]) . ") AND `CNL` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $destination["Localidade"])))
                      {
                        continue;
                      }
                      if ( $result->num_rows == 1)
                      {
                        $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => substr ( $num, 5), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => $gateway["ANATEL"]["local"]);
                        continue;
                      }

                      // If it's not conurbated, not local, calculate the distance to discover the fare step used
                      $distance = distance ( $gateway["ANATEL"]["Latitude"], $gateway["ANATEL"]["Longitude"], $destination["Latitude"], $destination["Longitude"], "k");
                      if ( $distance < 50)
                      {
                        $step = 1;
                      }
                      if ( $distance >= 50 && $distance < 100)
                      {
                        $step = 2;
                      }
                      if ( $distance >= 100 && $distance < 300)
                      {
                        $step = 3;
                      }
                      if ( $distance >= 300)
                      {
                        $step = 4;
                      }
                      $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => "0" . $gateway["ANATEL"]["interstate"] . substr ( $num, 3), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => $gateway["ANATEL"]["dc" . $step . $type]);
                      continue;
                    }

                    // TODO: international
                    if ( $target == "international")
                    {
                      // Check country group code at ANATEL standard:
                      if ( ! $group = internationalGroup ( $num))
                      {
                        writeLog ( "Unknown country to number " . $num . "!");
                        continue;
                      }
                      // Check fare type (reduced or normal):
                      $type = internationalFare ( $group);
                      $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => "00" . $gateway["ANATEL"]["international"] . substr ( $num, 1), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => ( $type == "Normal" ? $gateway["ANATEL"]["ldi" . $group] : $gateway["ANATEL"]["ldi" . $group] * 0.7));
                      continue;
                    }
                  } else {
                    // Process non-ANATEL gateway
                    foreach ( $gateway["Routes"] as $route)
                    {
                      if ( matchMask ( $route["route"], $num))
                      {
                        $routes[] = array ( "gw" => $gateway["ID"], "type" => $gateway["Type"], "priority" => $gateway["Priority"], "dial" => translateNumber ( $gateway["Translations"], $num), "td" => $gateway["Discard"], "tm" => $gateway["Minimum"], "tf" => $gateway["Fraction"], "cost" => $route["cost"]);
                        break;
                      }
                    }
                  }
                }
              } else {
                // Process non geographic numbers...
              }

              // If not resulted any route, just close the connection:
              if ( sizeof ( $routes) == 0)
              {
                socket_close ( $clients[$i]["socket"]);
                writeLog ( "Client " . $i . " (" . $clients[$i]["ip"] . ":" . $clients[$i]["port"] . ") is exiting without any route found!", VoIP_LOG_WARNING);
                unset ( $clients[$i]);
                break;
              }

              // Add resulted routes to reply to client:
              usort ( $routes, "costOrder");
              foreach ( $routes as $id => $route)
              {
                $id++;
                writeLog ( "Route weight " . $id . ": GW = " . $route["gw"] . ", Dial = " . $route["dial"] . ", Cost = " . sprintf ( "%.5f", $route["cost"]));
                $clients[$i]["buffer"][] = "SET VARIABLE \"r_" . $id . "_gw\" \"" . $route["gw"] . "\"";
                $clients[$i]["buffer"][] = "SET VARIABLE \"r_" . $id . "_dial\" \"" . $route["dial"] . "\"";
                $clients[$i]["buffer"][] = "SET VARIABLE \"r_" . $id . "_td\" \"" . $route["td"] . "\"";
                $clients[$i]["buffer"][] = "SET VARIABLE \"r_" . $id . "_tm\" \"" . $route["tm"] . "\"";
                $clients[$i]["buffer"][] = "SET VARIABLE \"r_" . $id . "_tf\" \"" . $route["tf"] . "\"";
                $clients[$i]["buffer"][] = "SET VARIABLE \"r_" . $id . "_cm\" \"" . $route["cost"] . "\"";
              }
              break;

            // CallID application (check if incoming number it's at user black list):
            //  Arg 1: Destination extension
            //  Arg 2: Source number (E.164 standard)
            case "callid":
              // Verifica lista negra... se tiver, verbose + hangup...
              // Verifica agenda pessoal, se tiver, seta CDR(name)
              // Verifica lista geral, se tiver, seta CDR(name)
              break;

            // Cost application (Cost calculation):
            //  Arg 1: Total bid time
            //  Arg 2: Discard time
            //  Arg 3: Minimum time
            //  Arg 4: Fraction time
            //  Arg 5: Cost (R$/m)
            case "cost":
              if ( (int) $clients[$i]["vars"]["arg_1"] >= (int) $clients[$i]["vars"]["arg_3"])
              {
                $cost = ( (float) $clients[$i]["vars"]["arg_5"] / 60) * ceil ( (int) $clients[$i]["vars"]["arg_1"] / (int) $clients[$i]["vars"]["arg_4"]) * (int) $clients[$i]["vars"]["arg_4"];
              } else {
                if ( (int) $clients[$i]["vars"]["arg_1"] > (int) $clients[$i]["vars"]["arg_2"])
                {
                  $cost = ( (float) $clients[$i]["vars"]["arg_5"] / 60) * (int) $clients[$i]["vars"]["arg_3"];
                } else {
                  $cost = 0;
                }
              }
              $clients[$i]["buffer"][] = "SET VARIABLE \"CDR(value)\" \"" . $cost . "\"";
              break;

            default:
              writeLog ( "Invalid application \"" . $clients[$i]["vars"]["network_script"] . "\" requested from client " . $i . " (" . $clients[$i]["ip"] . ":" . $clients[$i]["port"] . "). Closing connection.", VoIP_LOG_WARNING);
              socket_close ( $clients[$i]["socket"]);
              unset ( $clients[$i]);
              break;
          }
        }
      } else {
        if ( $clients[$i]["headers"] && preg_match ( "/^agi_(.*): (.*)$/", $data))
        {
          $clients[$i]["vars"][substr ( $data, 4, strpos ( $data, ":") - 4)] = substr ( $data, strpos ( $data, " ") + 1);
          break;
        }
        if ( $data == "HANGUP" || ( substr ( $data, 0, 4) == "200 " && sizeof ( $clients[$i]["buffer"]) == 0))
        {
          socket_close ( $clients[$i]["socket"]);
          writeLog ( "Client " . $i . " (" . $clients[$i]["ip"] . ":" . $clients[$i]["port"] . ") is exiting.");
          unset ( $clients[$i]);
          continue;
        }
      }

      if ( sizeof ( $clients[$i]["buffer"]) != 0)
      {
        $output = array_shift ( $clients[$i]["buffer"]) . "\n";
        $clients[$i]["last"] = $output;
        socket_write ( $clients[$i]["socket"], $output);
      }
    }
  }
}
?>
