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
 * VoIP Domain gateways Brazilian ANATEL regulatory agency standard module. This
 * module provides a full support to Brazil ANATEL standard, including LCR
 * calculations, gateway's interface modifications and other minor supports.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage ANATEL
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_hook ( "gateways_add_page", "gateways_add_ANATEL_page");
framework_add_hook ( "gateways_view_page", "gateways_view_ANATEL_page");
framework_add_hook ( "gateways_edit_page", "gateways_edit_ANATEL_page");

/**
 * Function to generate the gateway add page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_add_ANATEL_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add ANATEL configuration type option
   */
  $buffer = substr_replace ( $buffer, "\n            <option value=\"ANATEL\">" . __ ( "ANATEL") . "</option>", strpos ( $buffer, "\n", strpos ( $buffer, "<option value=\"manual\">")), 0);

  /**
   * Add ANATEL configuration tab
   */
  $buffer = substr_replace ( $buffer, "\n    <li class=\"disabled\" role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_fares\">" . __ ( "Fares") . "</a><li>", strpos ( $buffer, "\n", strpos ( $buffer, "<li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_add_tab_timing\">")), 0);

  /**
   * Create operators fares fields
   */
  $operators = "";
  foreach ( $_in["anatel"]["operators"] as $operator)
  {
    $key = preg_replace ( "/[^a-z0-9]/", "", strtolower ( $operator));
    $operators .= "      <div class=\"form-group\">\n" .
    "        <label for=\"gateway_add_anatel_mobile_" . $key . "_1\" class=\"control-label col-xs-2\">" . $operator . "</label>\n" .
    "        <div class=\"col-xs-10 col-nopadding\">\n" .
    "          <div class=\"col-xs-4\">\n" .
    "            <input type=\"text\" name=\"anatel_mobile_" . $key . "_1\" class=\"form-control\" id=\"gateway_add_anatel_mobile_" . $key . "_1\" placeholder=\"VC1 " . $operator . " (" . __ ( "US\$/m") . ")\" />\n" .
    "          </div>\n" .
    "          <div class=\"col-xs-4\">\n" .
    "            <input type=\"text\" name=\"anatel_mobile_" . $key . "_2\" class=\"form-control\" id=\"gateway_add_anatel_mobile_" . $key . "_2\" placeholder=\"VC2 " . $operator . " (" . __ ( "US\$/m") . ")\" />\n" .
    "          </div>\n" .
    "          <div class=\"col-xs-4\">\n" .
    "            <input type=\"text\" name=\"anatel_mobile_" . $key . "_3\" class=\"form-control\" id=\"gateway_add_anatel_mobile_" . $key . "_3\" placeholder=\"VC3 " . $operator . " (" . __ ( "US\$/m") . ")\" />\n" .
    "          </div>\n" .
    "        </div>\n" .
    "      </div>\n";
  }

  /**
   * Add ANATEL configuration panel
   */
  $buffer = substr_replace ( $buffer, "\n" .
  "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_add_tab_fares\">\n" .

  // Add local landline call cost field
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_add_anatel_local\" class=\"control-label col-xs-2\">" . __ ( "Landline local") . "</label>\n" .
  "        <div class=\"col-xs-10\">\n" .
  "          <input type=\"text\" name=\"anatel_local\" class=\"form-control\" id=\"gateway_add_anatel_local\" placeholder=\"" . __ ( "Landline local call cost") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add interstate company code field
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_add_anatel_interstate\" class=\"control-label col-xs-2\">" . __ ( "LDN operator") . "</label>\n" .
  "        <div class=\"col-xs-10\">\n" .
  "          <input type=\"text\" name=\"anatel_interstate\" class=\"form-control\" id=\"gateway_add_anatel_interstate\" placeholder=\"" . __ ( "LDN (National Long Distance) operator code") . "\" />\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add international company code field
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_add_anatel_international\" class=\"control-label col-xs-2\">" . __ ( "LDI operator") . "</label>\n" .
  "        <div class=\"col-xs-10\">\n" .
  "          <input type=\"text\" name=\"anatel_international\" class=\"form-control\" id=\"gateway_add_anatel_international\" placeholder=\"" . __ ( "LDI (International Long Distance) operator code") . "\" />\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity fares headers
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6 column-label\">" . __ ( "Differentiated") . "</div>\n" .
  "          <div class=\"col-xs-6 column-label\">" . __ ( "Normal") . "</div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6 column-label\">" . __ ( "Reduced") . "</div>\n" .
  "          <div class=\"col-xs-6 column-label\">" . __ ( "Super reduced") . "</div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 1 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_add_anatel_dc1d\" class=\"control-label col-xs-2\">DC1 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1d\" class=\"form-control\" id=\"gateway_add_anatel_dc1d\" placeholder=\"DC1 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1n\" class=\"form-control\" id=\"gateway_add_anatel_dc1n\" placeholder=\"DC1 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1r\" class=\"form-control\" id=\"gateway_add_anatel_dc1r\" placeholder=\"DC1 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1s\" class=\"form-control\" id=\"gateway_add_anatel_dc1s\" placeholder=\"DC1 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 2 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_add_anatel_dc2d\" class=\"control-label col-xs-2\">DC2 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2d\" class=\"form-control\" id=\"gateway_add_anatel_dc2d\" placeholder=\"DC2 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2n\" class=\"form-control\" id=\"gateway_add_anatel_dc2n\" placeholder=\"DC2 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2r\" class=\"form-control\" id=\"gateway_add_anatel_dc2r\" placeholder=\"DC2 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2s\" class=\"form-control\" id=\"gateway_add_anatel_dc2s\" placeholder=\"DC2 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 3 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_add_anatel_dc3d\" class=\"control-label col-xs-2\">DC3 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3d\" class=\"form-control\" id=\"gateway_add_anatel_dc3d\" placeholder=\"DC3 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3n\" class=\"form-control\" id=\"gateway_add_anatel_dc3n\" placeholder=\"DC3 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3r\" class=\"form-control\" id=\"gateway_add_anatel_dc3r\" placeholder=\"DC3 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3s\" class=\"form-control\" id=\"gateway_add_anatel_dc3s\" placeholder=\"DC3 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 4 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_add_anatel_dc4d\" class=\"control-label col-xs-2\">DC4 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4d\" class=\"form-control\" id=\"gateway_add_anatel_dc4d\" placeholder=\"DC4 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4n\" class=\"form-control\" id=\"gateway_add_anatel_dc4n\" placeholder=\"DC4 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4r\" class=\"form-control\" id=\"gateway_add_anatel_dc4r\" placeholder=\"DC4 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4s\" class=\"form-control\" id=\"gateway_add_anatel_dc4s\" placeholder=\"DC4 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add landline to mobile levels headers
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">VC1</div>\n" .
  "          <div class=\"col-xs-4 column-label\">VC2</div>\n" .
  "          <div class=\"col-xs-4 column-label\">VC3</div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add fares from landline to mobile to each mobile operator
  $operators .

  // Add international call groups fares fields
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 1</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 2</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 3</div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <label class=\"control-label col-xs-2\"></label>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi1\" class=\"form-control\" id=\"gateway_add_anatel_ldi1\" placeholder=\"" . __ ( "LDI - Group") . " 1 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi2\" class=\"form-control\" id=\"gateway_add_anatel_ldi2\" placeholder=\"" . __ ( "LDI - Group") . " 2 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi3\" class=\"form-control\" id=\"gateway_add_anatel_ldi3\" placeholder=\"" . __ ( "LDI - Group") . " 3 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 4</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 5</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 6</div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_add_anatel_ldi1\" class=\"control-label col-xs-2\">LDI</label>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi4\" class=\"form-control\" id=\"gateway_add_anatel_ldi4\" placeholder=\"" . __ ( "LDI - Group") . " 4 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi5\" class=\"form-control\" id=\"gateway_add_anatel_ldi5\" placeholder=\"" . __ ( "LDI - Group") . " 5 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi6\" class=\"form-control\" id=\"gateway_add_anatel_ldi6\" placeholder=\"" . __ ( "LDI - Group") . " 6 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 7</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 8</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 9</div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <label class=\"control-label col-xs-2\"></label>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi7\" class=\"form-control\" id=\"gateway_add_anatel_ldi7\" placeholder=\"" . __ ( "LDI - Group") . " 7 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi8\" class=\"form-control\" id=\"gateway_add_anatel_ldi8\" placeholder=\"" . __ ( "LDI - Group") . " 8 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi9\" class=\"form-control\" id=\"gateway_add_anatel_ldi9\" placeholder=\"" . __ ( "LDI - Group") . " 9 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "    </div>", strrpos ( $buffer, "\n", strpos ( strrev ( $buffer), strrev ( "id=\"gateway_add_tab_routes\"")) * -1), 0);

  /**
   * Add JavaScript code to enable ANATEL tab
   */
  sys_addjs ( "$('#gateway_add_config').on ( 'change', function ( e)\n" .
              "{\n" .
              "  if ( $(this).val () == 'ANATEL')\n" .
              "  {\n" .
              "    $('a[href=\"#gateway_add_tab_fares\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_add_tab_routes\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_add_tab_translations\"]').parent ().addClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('a[href=\"#gateway_add_tab_fares\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_add_tab_routes\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_add_tab_translations\"]').parent ().removeClass ( 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_add_tab_fares [name^=\"anatel_\"]').not ( '#gateway_add_anatel_interstate,#gateway_add_anatel_international').mask ( '" . __ ( "#,##0.00000") . "', { reverse: true});\n" .
              "$('#gateway_add_anatel_interstate,#gateway_add_anatel_international').mask ( '00');\n");

  return $buffer;
}

/**
 * Function to generate the gateway edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_edit_ANATEL_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add ANATEL configuration type option
   */
  $buffer = substr_replace ( $buffer, "\n            <option value=\"ANATEL\">ANATEL</option>", strpos ( $buffer, "\n", strpos ( $buffer, "<option value=\"manual\">")), 0);

  /**
   * Add ANATEL configuration tab
   */
  $buffer = substr_replace ( $buffer, "\n    <li class=\"disabled\" role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_fares\">Tarifas</a><li>", strpos ( $buffer, "\n", strpos ( $buffer, "<li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_edit_tab_timing\">")), 0);

  /**
   * Create operators fares fields
   */
  $operators = "";
  foreach ( $_in["anatel"]["operators"] as $operator)
  {
    $key = preg_replace ( "/[^a-z0-9]/", "", strtolower ( $operator));
    $operators .= "      <div class=\"form-group\">\n" .
    "        <label for=\"gateway_edit_anatel_mobile_" . $key . "_1\" class=\"control-label col-xs-2\">" . $operator . "</label>\n" .
    "        <div class=\"col-xs-10 col-nopadding\">\n" .
    "          <div class=\"col-xs-4\">\n" .
    "            <input type=\"text\" name=\"anatel_mobile_" . $key . "_1\" class=\"form-control\" id=\"gateway_edit_anatel_mobile_" . $key . "_1\" placeholder=\"VC1 " . $operator . " (" . __ ( "US\$/m") . ")\" />\n" .
    "          </div>\n" .
    "          <div class=\"col-xs-4\">\n" .
    "            <input type=\"text\" name=\"anatel_mobile_" . $key . "_2\" class=\"form-control\" id=\"gateway_edit_anatel_mobile_" . $key . "_2\" placeholder=\"VC2 " . $operator . " (" . __ ( "US\$/m") . ")\" />\n" .
    "          </div>\n" .
    "          <div class=\"col-xs-4\">\n" .
    "            <input type=\"text\" name=\"anatel_mobile_" . $key . "_3\" class=\"form-control\" id=\"gateway_edit_anatel_mobile_" . $key . "_3\" placeholder=\"VC3 " . $operator . " (" . __ ( "US\$/m") . ")\" />\n" .
    "          </div>\n" .
    "        </div>\n" .
    "      </div>\n";
  }

  /**
   * Add ANATEL configuration panel
   */
  $buffer = substr_replace ( $buffer, "\n" .
  "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_edit_tab_fares\">\n" .

  // Add local call cost field
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_edit_anatel_local\" class=\"control-label col-xs-2\">Local fixo</label>\n" .
  "        <div class=\"col-xs-10\">\n" .
  "          <input type=\"text\" name=\"anatel_local\" class=\"form-control\" id=\"gateway_edit_anatel_local\" placeholder=\"Custo da ligação local (" . __ ( "US\$/m") . ")\" />\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add interstate company code field
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_edit_anatel_interstate\" class=\"control-label col-xs-2\">Operadora LDN</label>\n" .
  "        <div class=\"col-xs-10\">\n" .
  "          <input type=\"text\" name=\"anatel_interstate\" class=\"form-control\" id=\"gateway_edit_anatel_interstate\" placeholder=\"Código da operator de LDN (Longa Distância Nacional)\" />\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add international company code field
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_edit_anatel_international\" class=\"control-label col-xs-2\">Operadora LDI</label>\n" .
  "        <div class=\"col-xs-10\">\n" .
  "          <input type=\"text\" name=\"anatel_international\" class=\"form-control\" id=\"gateway_edit_anatel_international\" placeholder=\"Código da operator de LDI (Longa Distância Internacional)\" />\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity fares headers
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6 column-label\">Diferenciada</div>\n" .
  "          <div class=\"col-xs-6 column-label\">Normal</div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6 column-label\">Reduzida</div>\n" .
  "          <div class=\"col-xs-6 column-label\">Super reduzida</div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 1 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_edit_anatel_dc1d\" class=\"control-label col-xs-2\">DC1 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1d\" class=\"form-control\" id=\"gateway_edit_anatel_dc1d\" placeholder=\"DC1 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1n\" class=\"form-control\" id=\"gateway_edit_anatel_dc1n\" placeholder=\"DC1 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1r\" class=\"form-control\" id=\"gateway_edit_anatel_dc1r\" placeholder=\"DC1 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1s\" class=\"form-control\" id=\"gateway_edit_anatel_dc1s\" placeholder=\"DC1 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 2 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_edit_anatel_dc2d\" class=\"control-label col-xs-2\">DC2 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2d\" class=\"form-control\" id=\"gateway_edit_anatel_dc2d\" placeholder=\"DC2 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2n\" class=\"form-control\" id=\"gateway_edit_anatel_dc2n\" placeholder=\"DC2 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2r\" class=\"form-control\" id=\"gateway_edit_anatel_dc2r\" placeholder=\"DC2 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2s\" class=\"form-control\" id=\"gateway_edit_anatel_dc2s\" placeholder=\"DC2 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 3 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_edit_anatel_dc3d\" class=\"control-label col-xs-2\">DC3 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3d\" class=\"form-control\" id=\"gateway_edit_anatel_dc3d\" placeholder=\"DC3 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3n\" class=\"form-control\" id=\"gateway_edit_anatel_dc3n\" placeholder=\"DC3 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3r\" class=\"form-control\" id=\"gateway_edit_anatel_dc3r\" placeholder=\"DC3 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3s\" class=\"form-control\" id=\"gateway_edit_anatel_dc3s\" placeholder=\"DC3 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 4 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_edit_anatel_dc4d\" class=\"control-label col-xs-2\">DC4 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4d\" class=\"form-control\" id=\"gateway_edit_anatel_dc4d\" placeholder=\"DC4 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4n\" class=\"form-control\" id=\"gateway_edit_anatel_dc4n\" placeholder=\"DC4 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4r\" class=\"form-control\" id=\"gateway_edit_anatel_dc4r\" placeholder=\"DC4 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4s\" class=\"form-control\" id=\"gateway_edit_anatel_dc4s\" placeholder=\"DC4 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add landline to mobile levels headers
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">VC1</div>\n" .
  "          <div class=\"col-xs-4 column-label\">VC2</div>\n" .
  "          <div class=\"col-xs-4 column-label\">VC3</div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add fares from landline to mobile to each mobile operator
  $operators .

  // Add international call groups fares fields
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 1</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 2</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 3</div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <label class=\"control-label col-xs-2\"></label>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi1\" class=\"form-control\" id=\"gateway_edit_anatel_ldi1\" placeholder=\"" . __ ( "LDI - Group") . " 1 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi2\" class=\"form-control\" id=\"gateway_edit_anatel_ldi2\" placeholder=\"" . __ ( "LDI - Group") . " 2 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi3\" class=\"form-control\" id=\"gateway_edit_anatel_ldi3\" placeholder=\"" . __ ( "LDI - Group") . " 3 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 4</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 5</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 6</div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_edit_anatel_ldi1\" class=\"control-label col-xs-2\">LDI</label>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi4\" class=\"form-control\" id=\"gateway_edit_anatel_ldi4\" placeholder=\"" . __ ( "LDI - Group") . " 4 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi5\" class=\"form-control\" id=\"gateway_edit_anatel_ldi5\" placeholder=\"" . __ ( "LDI - Group") . " 5 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi6\" class=\"form-control\" id=\"gateway_edit_anatel_ldi6\" placeholder=\"" . __ ( "LDI - Group") . " 6 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 7</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 8</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 9</div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <label class=\"control-label col-xs-2\"></label>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi7\" class=\"form-control\" id=\"gateway_edit_anatel_ldi7\" placeholder=\"" . __ ( "LDI - Group") . " 7 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi8\" class=\"form-control\" id=\"gateway_edit_anatel_ldi8\" placeholder=\"" . __ ( "LDI - Group") . " 8 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi9\" class=\"form-control\" id=\"gateway_edit_anatel_ldi9\" placeholder=\"" . __ ( "LDI - Group") . " 9 (" . __ ( "US\$/m") . ")\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "    </div>", strrpos ( $buffer, "\n", strpos ( strrev ( $buffer), strrev ( "id=\"gateway_edit_tab_routes\"")) * -1), 0);

  /**
   * Add JavaScript code to enable ANATEL tab
   */
  sys_addjs ( "$('#gateway_edit_config').on ( 'change', function ( e)\n" .
              "{\n" .
              "  if ( $(this).val () == 'ANATEL')\n" .
              "  {\n" .
              "    $('a[href=\"#gateway_edit_tab_fares\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_edit_tab_routes\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_edit_tab_translations\"]').parent ().addClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('a[href=\"#gateway_edit_tab_fares\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_edit_tab_routes\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_edit_tab_translations\"]').parent ().removeClass ( 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_edit_tab_fares [name^=\"anatel_\"]').not ( '#gateway_edit_anatel_interstate,#gateway_edit_anatel_international').mask ( '" . __ ( "#,##0.00000") . "', { reverse: true});\n" .
              "$('#gateway_edit_anatel_interstate,#gateway_edit_anatel_international').mask ( '00');\n" .
              "$('#gateway_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  for ( var x in data)\n" .
              "  {\n" .
              "    if ( x.substr ( 0, 7) == 'anatel_' && $('#gateway_edit_' + x).length == 1)\n" .
              "    {\n" .
              "      $('#gateway_edit_' + x).val ( data[x]);\n" .
              "    }\n" .
              "  }\n" .
              "});\n");

  return $buffer;
}

/**
 * Function to generate the gateway view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateways_view_ANATEL_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add ANATEL configuration type option
   */
  $buffer = substr_replace ( $buffer, "\n            <option value=\"ANATEL\">ANATEL</option>", strpos ( $buffer, "\n", strpos ( $buffer, "<option value=\"manual\">")), 0);

  /**
   * Add ANATEL configuration tab
   */
  $buffer = substr_replace ( $buffer, "\n    <li class=\"disabled\" role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_fares\">Tarifas</a><li>", strpos ( $buffer, "\n", strpos ( $buffer, "<li role=\"presentation\"><a class=\"nav-tablink\" href=\"#gateway_view_tab_timing\">")), 0);

  /**
   * Create operators fares fields
   */
  $operators = "";
  foreach ( $_in["anatel"]["operators"] as $operator)
  {
    $key = preg_replace ( "/[^a-z0-9]/", "", strtolower ( $operator));
    $operators .= "      <div class=\"form-group\">\n" .
    "        <label for=\"gateway_view_anatel_mobile_" . $key . "_1\" class=\"control-label col-xs-2\">" . $operator . "</label>\n" .
    "        <div class=\"col-xs-10 col-nopadding\">\n" .
    "          <div class=\"col-xs-4\">\n" .
    "            <input type=\"text\" name=\"anatel_mobile_" . $key . "_1\" class=\"form-control\" id=\"gateway_view_anatel_mobile_" . $key . "_1\" placeholder=\"VC1 " . $operator . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
    "          </div>\n" .
    "          <div class=\"col-xs-4\">\n" .
    "            <input type=\"text\" name=\"anatel_mobile_" . $key . "_2\" class=\"form-control\" id=\"gateway_view_anatel_mobile_" . $key . "_2\" placeholder=\"VC2 " . $operator . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
    "          </div>\n" .
    "          <div class=\"col-xs-4\">\n" .
    "            <input type=\"text\" name=\"anatel_mobile_" . $key . "_3\" class=\"form-control\" id=\"gateway_view_anatel_mobile_" . $key . "_3\" placeholder=\"VC3 " . $operator . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
    "          </div>\n" .
    "        </div>\n" .
    "      </div>\n";
  }

  /**
   * Add ANATEL configuration panel
   */
  $buffer = substr_replace ( $buffer, "\n" .
  "    <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"gateway_view_tab_fares\">\n" .

  // Add local call cost field
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_view_anatel_local\" class=\"control-label col-xs-2\">Local fixo</label>\n" .
  "        <div class=\"col-xs-10\">\n" .
  "          <input type=\"text\" name=\"anatel_local\" class=\"form-control\" id=\"gateway_view_anatel_local\" placeholder=\"Custo da ligação local (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add interstate company code field
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_view_anatel_interstate\" class=\"control-label col-xs-2\">Operadora LDN</label>\n" .
  "        <div class=\"col-xs-10\">\n" .
  "          <input type=\"text\" name=\"anatel_interstate\" class=\"form-control\" id=\"gateway_view_anatel_interstate\" placeholder=\"Código da operator de LDN (Longa Distância Nacional)\" disabled=\"disabled\" />\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add international company code field
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_view_anatel_international\" class=\"control-label col-xs-2\">Operadora LDI</label>\n" .
  "        <div class=\"col-xs-10\">\n" .
  "          <input type=\"text\" name=\"anatel_international\" class=\"form-control\" id=\"gateway_view_anatel_international\" placeholder=\"Código da operator de LDI (Longa Distância Internacional)\" disabled=\"disabled\" />\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity fares headers
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6 column-label\">Diferenciada</div>\n" .
  "          <div class=\"col-xs-6 column-label\">Normal</div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6 column-label\">Reduzida</div>\n" .
  "          <div class=\"col-xs-6 column-label\">Super reduzida</div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 1 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_view_anatel_dc1d\" class=\"control-label col-xs-2\">DC1 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1d\" class=\"form-control\" id=\"gateway_view_anatel_dc1d\" placeholder=\"DC1 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1n\" class=\"form-control\" id=\"gateway_view_anatel_dc1n\" placeholder=\"DC1 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1r\" class=\"form-control\" id=\"gateway_view_anatel_dc1r\" placeholder=\"DC1 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc1s\" class=\"form-control\" id=\"gateway_view_anatel_dc1s\" placeholder=\"DC1 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 2 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_view_anatel_dc2d\" class=\"control-label col-xs-2\">DC2 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2d\" class=\"form-control\" id=\"gateway_view_anatel_dc2d\" placeholder=\"DC2 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2n\" class=\"form-control\" id=\"gateway_view_anatel_dc2n\" placeholder=\"DC2 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2r\" class=\"form-control\" id=\"gateway_view_anatel_dc2r\" placeholder=\"DC2 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc2s\" class=\"form-control\" id=\"gateway_view_anatel_dc2s\" placeholder=\"DC2 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 3 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_view_anatel_dc3d\" class=\"control-label col-xs-2\">DC3 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3d\" class=\"form-control\" id=\"gateway_view_anatel_dc3d\" placeholder=\"DC3 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3n\" class=\"form-control\" id=\"gateway_view_anatel_dc3n\" placeholder=\"DC3 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3r\" class=\"form-control\" id=\"gateway_view_anatel_dc3r\" placeholder=\"DC3 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc3s\" class=\"form-control\" id=\"gateway_view_anatel_dc3s\" placeholder=\"DC3 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add intercity level 4 fares fields
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_view_anatel_dc4d\" class=\"control-label col-xs-2\">DC4 " . __ ( "Landline") . "</label>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4d\" class=\"form-control\" id=\"gateway_view_anatel_dc4d\" placeholder=\"DC4 " . __ ( "differentiated") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4n\" class=\"form-control\" id=\"gateway_view_anatel_dc4n\" placeholder=\"DC4 " . __ ( "normal") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "        <div class=\"col-xs-5 col-nopadding\">\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4r\" class=\"form-control\" id=\"gateway_view_anatel_dc4r\" placeholder=\"DC4 " . __ ( "reduced") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-6\">\n" .
  "            <input type=\"text\" name=\"anatel_dc4s\" class=\"form-control\" id=\"gateway_view_anatel_dc4s\" placeholder=\"DC4 " . __ ( "super reduced") . " (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add landline to mobile levels headers
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">VC1</div>\n" .
  "          <div class=\"col-xs-4 column-label\">VC2</div>\n" .
  "          <div class=\"col-xs-4 column-label\">VC3</div>\n" .
  "        </div>\n" .
  "      </div>\n" .

  // Add fares from landline to mobile to each mobile operator
  $operators .

  // Add international call groups fares fields
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 1</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 2</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 3</div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <label class=\"control-label col-xs-2\"></label>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi1\" class=\"form-control\" id=\"gateway_view_anatel_ldi1\" placeholder=\"" . __ ( "LDI - Group") . " 1 (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi2\" class=\"form-control\" id=\"gateway_view_anatel_ldi2\" placeholder=\"" . __ ( "LDI - Group") . " 2 (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi3\" class=\"form-control\" id=\"gateway_view_anatel_ldi3\" placeholder=\"" . __ ( "LDI - Group") . " 3 (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 4</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 5</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 6</div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <label for=\"gateway_view_anatel_ldi1\" class=\"control-label col-xs-2\">LDI</label>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi4\" class=\"form-control\" id=\"gateway_view_anatel_ldi4\" placeholder=\"" . __ ( "LDI - Group") . " 4 (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi5\" class=\"form-control\" id=\"gateway_view_anatel_ldi5\" placeholder=\"" . __ ( "LDI - Group") . " 5 (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi6\" class=\"form-control\" id=\"gateway_view_anatel_ldi6\" placeholder=\"" . __ ( "LDI - Group") . " 6 (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <span class=\"col-xs-2\"></span>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 7</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 8</div>\n" .
  "          <div class=\"col-xs-4 column-label\">" . __ ( "Group") . " 9</div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "      <div class=\"form-group\">\n" .
  "        <label class=\"control-label col-xs-2\"></label>\n" .
  "        <div class=\"col-xs-10 col-nopadding\">\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi7\" class=\"form-control\" id=\"gateway_view_anatel_ldi7\" placeholder=\"" . __ ( "LDI - Group") . " 7 (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi8\" class=\"form-control\" id=\"gateway_view_anatel_ldi8\" placeholder=\"" . __ ( "LDI - Group") . " 8 (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "          <div class=\"col-xs-4\">\n" .
  "            <input type=\"text\" name=\"anatel_ldi9\" class=\"form-control\" id=\"gateway_view_anatel_ldi9\" placeholder=\"" . __ ( "LDI - Group") . " 9 (" . __ ( "US\$/m") . ")\" disabled=\"disabled\" />\n" .
  "          </div>\n" .
  "        </div>\n" .
  "      </div>\n" .
  "    </div>", strrpos ( $buffer, "\n", strpos ( strrev ( $buffer), strrev ( "id=\"gateway_view_tab_routes\"")) * -1), 0);

  /**
   * Add JavaScript code to enable ANATEL tab
   */
  sys_addjs ( "$('#gateway_view_config').on ( 'change', function ( e)\n" .
              "{\n" .
              "  if ( $(this).val () == 'ANATEL')\n" .
              "  {\n" .
              "    $('a[href=\"#gateway_view_tab_fares\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_view_tab_routes\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_view_tab_translations\"]').parent ().addClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('a[href=\"#gateway_view_tab_fares\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_view_tab_routes\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#gateway_view_tab_translations\"]').parent ().removeClass ( 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "$('#gateway_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  for ( var x in data)\n" .
              "  {\n" .
              "    if ( x.substr ( 0, 7) == 'anatel_' && $('#gateway_view_' + x).length == 1)\n" .
              "    {\n" .
              "      $('#gateway_view_' + x).val ( data[x]);\n" .
              "    }\n" .
              "  }\n" .
              "});\n");

  return $buffer;
}
?>
