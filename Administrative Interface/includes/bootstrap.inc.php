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
 * Generic bootstrap functions to VoIP Domain framework.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Function to generate graceful internal server error pages.
 * 
 * @global array $_in Framework global configuration variable
 * @param $errno int The error number (from 500 to 599). 
 * @param $message string The error message.
 * @return null
 */
function error_5xx ( $errno, $message)
{
  global $_in;

  if ( ! is_array ( $_in))
  {
    $_in = array ( "general" => array ( "language" => "en_US", "charset" => "UTF-8", "debug" => false));
  } elseif ( ! array_key_exists ( "general", $_in) || ! is_array ( $_in["general"]))
  {
    $_in["general"] = array ( "language" => "en_US", "charset" => "UTF-8", "debug" => false);
  } else {
    if ( ! array_key_exists ( "language", $_in["general"]))
    {
      $_in["general"]["language"] = "en_US";
    }
    if ( ! array_key_exists ( "charset", $_in["general"]))
    {
      $_in["general"]["charset"] = "UTF-8";
    }
    if ( ! array_key_exists ( "debug", $_in["general"]))
    {
      $_in["general"]["debug"] = false;
    }
  }

  if ( ! headers_sent ())
  {
    switch ( $errno)
    {
      case 501:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Not Implemented");
        break;
      case 502:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Bad Gateway");
        break;
      case 503:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Service Unavailable");
        break;
      case 504:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Gateway Timeout");
        break;
      case 505:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " HTTP Version Not Supported");
        break;
      case 506:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Variant Also Negotiates");
        break;
      case 507:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Insufficient Storage");
        break;
      case 508:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Loop Detected");
        break;
      case 509:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Bandwidth Limit Exceeded");
        break;
      case 510:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Not Extended");
        break;
      case 511:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Network Authentication Required");
        break;
      case 599:
        header ( $_SERVER["SERVER_PROTOCOL"] . " " . $errno . " Network Connection Timeout Error");
        break;
      default:
        $errno = 500;
        header ( $_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        break;
    }
    if ( array_key_exists ( "HTTP_X_INFRAMEWORK", $_SERVER) && $_in["general"]["debug"] === true)
    {
      header ( "Content-Type: text/json; charset=utf-8");
    } else {
      header ( "Content-Type: text/html; charset=utf-8");
    }
  }
  if ( array_key_exists ( "HTTP_X_INFRAMEWORK", $_SERVER) && $_in["general"]["debug"] === true)
  {
    echo json_encode ( array ( "result" => false, "message" => $message, "error" => $errono));
  } else {
    echo "<!DOCTYPE html>\n";
    echo generate_html_banner ();
    echo "<html lang=\"" . ( ! empty ( $_in["general"]["language"]) ? $_in["general"]["language"] : "en_US") . "\">\n";
    echo "<head>\n";
    echo "  <meta charset=\"" . ( ! empty ( $_in["general"]["charset"]) ? strtolower ( $_in["general"]["charset"]) : "utf-8") . "\">\n";
    echo "  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
    echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\">\n";
    echo "  <meta name=\"application-name\" content=\"VoIP Domain\">\n";
    echo "  <meta name=\"description\" content=\"" . __ ( "VoIP PBX management system.") . "\">\n";
    echo "  <meta name=\"author\" content=\"Ernani José Camargo Azevedo\">\n";
    if ( ! empty ( $_in["general"]["version"]))
    {
      echo "  <meta name=\"version\" content=\"" . addslashes ( strip_tags ( $_in["general"]["version"])) . "\">\n";
    }
    if ( ! empty ( $_in["general"]["favicon"]))
    {
      echo "  <link rel=\"icon\" type=\"" . mime_content_type ( strip_tags ( dirname ( __FILE__) . "/../.." . $_in["general"]["favicon"])) . "\" href=\"" . addslashes ( strip_tags ( ( substr ( $_in["general"]["favicon"], 0, 1) != "/" ? "/" : "") . $_in["general"]["favicon"])) . "\">\n";
    }
    if ( ! empty ( $_in["general"]["title"]))
    {
      echo "  <title>" . addslashes ( strip_tags ( $_in["general"]["title"])) . " - " . __ ( "Error") . " " . $errno . "</title>\n";
    } else {
      echo "  <title>" . __ ( "Error") . " " . $errno . "</title>\n";
    }
    echo "  <link type=\"text/css\" rel=\"stylesheet\" href=\"/css/error" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".css\" />\n";
    echo "</head>\n";
    echo "\n";
    echo "<body>\n";
    echo "  <div class=\"container\">\n";
    echo "    <div class=\"error\">\n";
    echo "      <h1>" . $errno . "</h1>\n";
    echo "      <h2>" . __ ( "error") . "</h2>\n";
    echo "      <p>" . htmlspecialchars ( $message) . "</p>\n";
    echo "    </div>\n";
    echo "    <div class=\"stack-container\">\n";
    echo "      <div class=\"card-container\">\n";
    echo "        <div class=\"perspec\" style=\"--spreaddist: 125px; --scaledist: .75; --vertdist: -25px;\">\n";
    echo "          <div class=\"card\">\n";
    echo "            <div class=\"writing\">\n";
    echo "              <div class=\"topbar\">\n";
    echo "                <div class=\"red\"></div>\n";
    echo "                <div class=\"yellow\"></div>\n";
    echo "                <div class=\"green\"></div>\n";
    echo "              </div>\n";
    echo "              <div class=\"code\"><ul></ul></div>\n";
    echo "            </div>\n";
    echo "          </div>\n";
    echo "        </div>\n";
    echo "      </div>\n";
    echo "      <div class=\"card-container\">\n";
    echo "        <div class=\"perspec\" style=\"--spreaddist: 100px; --scaledist: .8; --vertdist: -20px;\">\n";
    echo "          <div class=\"card\">\n";
    echo "            <div class=\"writing\">\n";
    echo "              <div class=\"topbar\">\n";
    echo "                <div class=\"red\"></div>\n";
    echo "                <div class=\"yellow\"></div>\n";
    echo "                <div class=\"green\"></div>\n";
    echo "              </div>\n";
    echo "              <div class=\"code\"><ul></ul></div>\n";
    echo "            </div>\n";
    echo "          </div>\n";
    echo "        </div>\n";
    echo "      </div>\n";
    echo "      <div class=\"card-container\">\n";
    echo "        <div class=\"perspec\" style=\"--spreaddist:75px; --scaledist: .85; --vertdist: -15px;\">\n";
    echo "          <div class=\"card\">\n";
    echo "            <div class=\"writing\">\n";
    echo "              <div class=\"topbar\">\n";
    echo "                <div class=\"red\"></div>\n";
    echo "                <div class=\"yellow\"></div>\n";
    echo "                <div class=\"green\"></div>\n";
    echo "              </div>\n";
    echo "              <div class=\"code\"><ul></ul></div>\n";
    echo "            </div>\n";
    echo "          </div>\n";
    echo "        </div>\n";
    echo "      </div>\n";
    echo "      <div class=\"card-container\">\n";
    echo "        <div class=\"perspec\" style=\"--spreaddist: 50px; --scaledist: .9; --vertdist: -10px;\">\n";
    echo "          <div class=\"card\">\n";
    echo "            <div class=\"writing\">\n";
    echo "              <div class=\"topbar\">\n";
    echo "                <div class=\"red\"></div>\n";
    echo "                <div class=\"yellow\"></div>\n";
    echo "                <div class=\"green\"></div>\n";
    echo "              </div>\n";
    echo "              <div class=\"code\"><ul></ul></div>\n";
    echo "            </div>\n";
    echo "          </div>\n";
    echo "        </div>\n";
    echo "      </div>\n";
    echo "      <div class=\"card-container\">\n";
    echo "        <div class=\"perspec\" style=\"--spreaddist: 25px; --scaledist: .95; --vertdist: -5px;\">\n";
    echo "          <div class=\"card\">\n";
    echo "            <div class=\"writing\">\n";
    echo "              <div class=\"topbar\">\n";
    echo "                <div class=\"red\"></div>\n";
    echo "                <div class=\"yellow\"></div>\n";
    echo "                <div class=\"green\"></div>\n";
    echo "              </div>\n";
    echo "              <div class=\"code\"><ul></ul></div>\n";
    echo "            </div>\n";
    echo "          </div>\n";
    echo "        </div>\n";
    echo "      </div>\n";
    echo "      <div class=\"card-container\">\n";
    echo "        <div class=\"perspec\" style=\"--spreaddist: 0px; --scaledist: 1; --vertdist: 0px;\">\n";
    echo "          <div class=\"card\">\n";
    echo "            <div class=\"writing\">\n";
    echo "              <div class=\"topbar\">\n";
    echo "                <div class=\"red\"></div>\n";
    echo "                <div class=\"yellow\"></div>\n";
    echo "                <div class=\"green\"></div>\n";
    echo "              </div>\n";
    echo "              <div class=\"code\"><ul></ul></div>\n";
    echo "            </div>\n";
    echo "          </div>\n";
    echo "        </div>\n";
    echo "      </div>\n";
    echo "    </div>\n";
    echo "  </div>\n";
    echo "\n";
    echo "  <script type=\"text/javascript\">\n";
    echo "    const stackContainer = document.querySelector ( '.stack-container');\n";
    echo "    const cardNodes = document.querySelectorAll ( '.card-container');\n";
    echo "    const perspecNodes = document.querySelectorAll ( '.perspec');\n";
    echo "    const perspec = document.querySelector ( '.perspec');\n";
    echo "    const card = document.querySelector ( '.card');\n";
    echo "\n";
    echo "    let counter = stackContainer.children.length;\n";
    echo "\n";
    echo "    // function to generate random number\n";
    echo "    function randomIntFromInterval ( min, max)\n";
    echo "    {\n";
    echo "      return Math.floor ( Math.random () * ( max - min + 1) + min);\n";
    echo "    }\n";
    echo "\n";
    echo "    // after tilt animation, fire the explode animation\n";
    echo "    card.addEventListener ( 'animationend', function ()\n";
    echo "    {\n";
    echo "      perspecNodes.forEach ( function ( elem, index)\n";
    echo "      {\n";
    echo "        elem.classList.add ( 'explode');\n";
    echo "      });\n";
    echo "    });\n";
    echo "\n";
    echo "    // after explode animation do a bunch of stuff\n";
    echo "    perspec.addEventListener ( 'animationend', function ( e)\n";
    echo "    {\n";
    echo "      if ( e.animationName === 'explode')\n";
    echo "      {\n";
    echo "        cardNodes.forEach ( function ( elem, index)\n";
    echo "        {\n";
    echo "          // add hover animation class\n";
    echo "          elem.classList.add ( 'pokeup');\n";
    echo "\n";
    echo "          //add event listner to throw card on click\n";
    echo "          elem.addEventListener ( 'click', function ()\n";
    echo "          {\n";
    echo "            let updown = [800, -800];\n";
    echo "            let randomY = updown[Math.floor ( Math.random () * updown.length)];\n";
    echo "            let randomX = Math.floor ( Math.random () * 1000) - 1000;\n";
    echo "            elem.style.transform = 'translate(\${randomX}px, \${randomY}px) rotate(-540deg)';\n";
    echo "            elem.style.transition = 'transform 1s ease, opacity 2s';\n";
    echo "            elem.style.opacity = '0';\n";
    echo "            counter--;\n";
    echo "            if ( counter === 0)\n";
    echo "            {\n";
    echo "              stackContainer.style.width = '0';\n";
    echo "              stackContainer.style.height = '0';\n";
    echo "            }\n";
    echo "          });\n";
    echo "\n";
    echo "          // generate random number of lines of code between 4 and 10 and add to each card\n";
    echo "          let numLines = randomIntFromInterval ( 5, 10);\n";
    echo "\n";
    echo "          // loop through the lines and add them to the DOM\n";
    echo "          for ( let index = 0; index < numLines; index++)\n";
    echo "          {\n";
    echo "            let lineLength = randomIntFromInterval ( 25, 97);\n";
    echo "            var node = document.createElement ( 'li');\n";
    echo "            node.classList.add ( 'node-' + index);\n";
    echo "            elem.querySelector ( '.code ul').appendChild ( node).setAttribute ( 'style', '--linelength: ' + lineLength + '%;');\n";
    echo "\n";
    echo "            // draw lines of code 1 by 1\n";
    echo "            if ( index == 0)\n";
    echo "            {\n";
    echo "              elem.querySelector ( '.code ul .node-' + index).classList.add ( 'writeLine');\n";
    echo "            } else {\n";
    echo "              elem.querySelector ( '.code ul .node-' + ( index - 1)).addEventListener ( 'animationend', function ( e)\n";
    echo "              {\n";
    echo "                elem.querySelector ( '.code ul .node-' + index).classList.add ( 'writeLine');\n";
    echo "              });\n";
    echo "            }\n";
    echo "          }\n";
    echo "        });\n";
    echo "      }\n";
    echo "    });\n";
    echo "  </script>\n";
    echo "\n";
    echo "</body>\n";
    echo "</html>\n";
  }
  exit ( 1);
}

/**
 * Function to generate system HTML head banner.
 * 
 * @return string The system HTML head banner.
 */
function generate_html_banner ()
{
  return "\n" .
         "<!--\n" .
         "   ___ ___       ___ _______     ______                        __\n" .
         "  |   Y   .‑‑‑‑‑|   |   _   |   |   _  \ .‑‑‑‑‑.‑‑‑‑‑‑‑‑.‑‑‑.-|__.‑‑‑‑‑.\n" .
         "  |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |\n" .
         "  |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|\n" .
         "  |:  1   |     |:  |:  |       |:  1    /\n" .
         "   \:.. ./      |::.|::.|       |::.. . /\n" .
         "    `‑‑‑'       `‑‑‑`‑‑‑'       `‑‑‑‑‑‑'\n" .
         "\n" .
         "Copyright (C) 2016-2026 Ernani José Camargo Azevedo\n" .
         "\n" .
         "This program is free software: you can redistribute it and/or modify\n" .
         "it under the terms of the GNU General Public License as published by\n" .
         "the Free Software Foundation, either version 3 of the License, or\n" .
         "(at your option) any later version.\n" .
         "\n" .
         "This program is distributed in the hope that it will be useful,\n" .
         "but WITHOUT ANY WARRANTY; without even the implied warranty of\n" .
         "MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n" .
         "GNU General Public License for more details.\n" .
         "\n" .
         "You should have received a copy of the GNU General Public License\n" .
         "along with this program.  If not, see <https://www.gnu.org/licenses/>.\n" .
         "-->\n" .
         "\n";
}

/**
 * Resolve webroot from this file (includes/…) — never rely on PHP-FPM chdir or pool config.
 */
if ( ! defined ( "VD_WEBROOT"))
{
  $vd_root = realpath ( dirname ( __FILE__) . "/..");
  define ( "VD_WEBROOT", $vd_root !== false ? $vd_root : dirname ( __FILE__) . "/..");
}
@chdir ( VD_WEBROOT);

/**
 * Load each required bootstrap include file
 *
 * functions.inc.php: miscelaneous function library
 * plugins.inc.php: modular plugin library
 * plugin-install.inc.php: plugin installation helper library
 * plugin-signing.inc.php: plugin Ed25519 signing and verification library
 * file-transfer.inc.php: remote file transfer helper library
 * systemd.inc.php: systemd integration helper library
 * timezonepicker_ui.inc.php: timezone picker UI helper library
 */
foreach ( array ( "functions", "plugins", "plugin-install", "plugin-signing", "file-transfer", "systemd", "module-versions", "timezonepicker_ui", "config") as $include)
{
  if ( ! is_file ( VD_WEBROOT . "/includes/" . $include . ".inc.php"))
  {
    error_5xx ( 503, "Missing file: includes/" . $include . ".inc.php");
  }
  if ( ! is_readable ( VD_WEBROOT . "/includes/" . $include . ".inc.php"))
  {
    error_5xx ( 503, "Cannot read: " . $config . ". Check directory permissions (execute bit on all parent dirs), PHP open_basedir (" . ini_get ( "open_basedir") . "), and SELinux (restorecon -RF " . VD_WEBROOT . ").");
  }
  require_once ( VD_WEBROOT . "/includes/" . $include . ".inc.php");
}
?>
