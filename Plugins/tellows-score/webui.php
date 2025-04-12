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
 * VoIP Domain Tellows score configuration module WebUI.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Tellows Score
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_hook ( "config_page", "tellows_config_page");

/**
 * Function to create the tellows score configuration page tab code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $output Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function tellows_config_page ( $output, $parameters)
{
  global $_in;

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/css/bootstrap-slider.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-slider", "src" => "/vendors/bootstrap-slider/dist/bootstrap-slider.js", "dep" => array ()));

  // Add Tellows panel entry
  $output = substr ( $output, 0, strpos ( $output, "</ul>", strpos ( $output, "role=\"tablist\""))) . "  <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#config_tab_tellows\">" . __ ( "Tellows Score") . "</a></li>\n  " . substr ( $output, strpos ( $output, "</ul>", strpos ( $output, "role=\"tablist\"")));

  // Tellows score panel content
  $tellows = "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"config_tab_tellows\">\n";

  // Add Tellows key option
  $tellows .= "      <div class=\"form-group\">\n";
  $tellows .= "        <label for=\"config_tellows_key\" class=\"control-label col-xs-2\">" . __ ( "API Key (MD5)") . "</label>\n";
  $tellows .= "        <div class=\"col-xs-10\">\n";
  $tellows .= "          <input type=\"text\" name=\"TellowsKey\" class=\"form-control\" id=\"config_tellows_key\" maxlength=\"32\" placeholder=\"" . __ ( "Tellows API key in MD5 format") . "\" />\n";
  $tellows .= "        </div>\n";
  $tellows .= "      </div>\n";

  // Add call antispam switch
  $tellows .= "      <div class=\"form-group\">\n";
  $tellows .= "        <label for=\"config_tellows_antispam\" class=\"control-label col-xs-2\">" . __ ( "Anti-spam") . "</label>\n";
  $tellows .= "        <div class=\"col-xs-10\">\n";
  $tellows .= "          <input type=\"checkbox\" name=\"TellowsAntiSpam\" id=\"config_tellows_antispam\" value=\"false\" class=\"form-control\" />\n";
  $tellows .= "        </div>\n";
  $tellows .= "      </div>\n";

  // Add drop minimum score slider
  $tellows .= "      <div class=\"form-group\">\n";
  $tellows .= "        <label for=\"config_tellows_score\" class=\"control-label col-xs-2\">" . __ ( "Minimum score") . "</label>\n";
  $tellows .= "        <div class=\"col-xs-10\">\n";
  $tellows .= "          <input type=\"text\" name=\"TellowsScore\" id=\"config_tellows_score\" class=\"form-control tabslider\" />\n";
  $tellows .= "        </div>\n";
  $tellows .= "      </div>\n";

  // Close Tellows panel
  $tellows .= "    </div>\n";

  // Add Tellows panel code
  $output = substr ( $output, 0, strpos ( $output, "  <div role=\"tabpanel\"")) . $tellows . substr ( $output, strpos ( $output, "  <div role=\"tabpanel\""));

  /**
   * Add configuration JavaScript code
   */
  sys_addjs ( "$('#config_tellows_key').mask ( 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', { 'translation': { x: { pattern: /[A-Fa-f0-9]/}}}).on ( 'keyup', function ( event)\n" .
              "{\n" .
              "  if ( $(this).val ().length == 32)\n" .
              "  {\n" .
              "    $('#config_tellows_antispam').bootstrapToggle ( 'enable');\n" .
              "    if ( $('#config_tellows_antispam').prop ( 'checked'))\n" .
              "    {\n" .
              "      $('#config_tellows_score').bootstrapSlider ( 'enable');\n" .
              "    } else {\n" .
              "      $('#config_tellows_score').bootstrapSlider ( 'disable');\n" .
              "    }\n" .
              "  } else {\n" .
              "    $('#config_tellows_antispam').bootstrapToggle ( 'disable');\n" .
              "    $('#config_tellows_score').bootstrapSlider ( 'disable');\n" .
              "  }\n" .
              "});\n" .
              "$('#config_tellows_antispam').bootstrapToggle ( { on: '" . __ ( "Active") . "', off: '" . __ ( "Inactive") . "', onstyle: 'success', offstyle: 'danger'}).bootstrapToggle ( 'disable').on ( 'change', function ( event)\n" .
              "{\n" .
              "  if ( $(this).prop ( 'checked'))\n" .
              "  {\n" .
              "    $('#config_tellows_score').bootstrapSlider ( 'enable');\n" .
              "  } else {\n" .
              "    $('#config_tellows_score').bootstrapSlider ( 'disable');\n" .
              "  }\n" .
              "});\n" .
              "$('#config_tellows_score').bootstrapSlider (\n" .
              "{\n" .
              "  ticks: [ 6, 7, 8, 9, 10],\n" .
              "  ticks_labels: [ '6', '7', '8', '9', '10'],\n" .
              "  min: 6,\n" .
              "  max: 10,\n" .
              "  value: 9,\n" .
              "  enabled: false,\n" .
              "  tooltip: 'hide'\n" .
              "});\n" .
              "$('#config_form').find ( 'ul.nav').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  $($(e.target).attr ( 'href')).find ( 'input.tabslider').bootstrapSlider ( 'refresh', { useCurrentValue: true });\n" .
              "});" .
              "$('#config_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#config_tellows_key').val ( data.Tellows.Key);\n" .
              "  $('#config_tellows_antispam').bootstrapToggle ( 'enable').bootstrapToggle ( data.Tellows.AntiSpam ? 'on' : 'off');\n" .
              "  $('#config_tellows_score').bootstrapSlider ( 'enable').bootstrapSlider ( 'setValue', data.Tellows.Score ? data.Tellows.Score : 6);\n" .
              "  if ( data.Tellows.Key.length == 32)\n" .
              "  {\n" .
              "    if ( ! data.Tellows.AntiSpam)\n" .
              "    {\n" .
              "      $('#config_tellows_score').bootstrapSlider ( 'disable');\n" .
              "    }\n" .
              "  } else {\n" .
              "    $('#config_tellows_antispam').bootstrapToggle ( 'disable');\n" .
              "    $('#config_tellows_score').bootstrapSlider ( 'disable');\n" .
              "  }\n" .
              "});\n" .
              "$('#config_form').on ( 'formFilter', function ()\n" .
              "{\n" .
              "  var formData = $('#config_form').data ( 'formData');\n" .
              "  formData.Tellows = new Object ();\n" .
              "  formData.Tellows.Key = formData.TellowsKey;\n" .
              "  delete ( formData.TellowsKey);\n" .
              "  formData.Tellows.AntiSpam = $('#config_tellows_antispam').prop ( 'checked');\n" .
              "  delete ( formData.TellowsAntiSpam);\n" .
              "  formData.Tellows.Score = formData.TellowsScore;\n" .
              "  delete ( formData.TellowsScore);\n" .
              "  $('#config_form').data ( 'formData', formData);\n" .
              "});\n");

  return $output;
}
?>
