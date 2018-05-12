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
 * VoIP Domain configuration module.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Configuration
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_hook ( "config_page", "config_page");
framework_add_path ( "/config", "config_page");

/**
 * Function to create the configuration page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $output Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function config_page ( $output, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Configuration"));
  sys_set_subtitle ( __ ( "system configuration"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Configuration"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( "css/bootstrap-datetimepicker.css");
  sys_addcss ( "css/bootstrap-switch.css");
  sys_addcss ( "css/bootstrap-slider.css");
  sys_addcss ( "css/bootstrap-dataTables.css");
  sys_addcss ( "css/buttons.bootstrap.css");
  sys_addcss ( "css/buttons.dataTables.css");
  sys_addcss ( "css/daterangepicker.css");
  sys_addcss ( "css/font-awesome.css");
  sys_addcss ( "css/pnotify.css");
  sys_addcss ( "css/select2.css");
  sys_addcss ( "css/select2-bootstrap.css");
  sys_addcss ( "css/bootstrap-tagsinput.css");
  sys_addcss ( "css/flags.css");
  sys_addcss ( "css/bootstrap-duallistbox.css");

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( "js/moment.js");
  sys_addjs ( "js/moment-pt_BR.js");
  sys_addjs ( "js/bootstrap-datetimepicker.js");
  sys_addjs ( "js/bootstrap-switch.js");
  sys_addjs ( "js/bootstrap-tabdrop.js");
  sys_addjs ( "js/bootstrap-slider.js");
  sys_addjs ( "js/jquery.dataTables.js");
  sys_addjs ( "js/dataTables.bootstrap.js");
  sys_addjs ( "js/dataTables.buttons.js");
  sys_addjs ( "js/jszip.js");
  sys_addjs ( "js/pdfmake.js");
  sys_addjs ( "js/vfs_fonts.js");
  sys_addjs ( "js/buttons.html5.js");
  sys_addjs ( "js/buttons.print.js");
  sys_addjs ( "js/jquery.address.js");
  sys_addjs ( "js/jquery.mask.js");
  sys_addjs ( "js/daterangepicker.js");
  sys_addjs ( "js/spin.js");
  sys_addjs ( "js/ladda.js");
  sys_addjs ( "js/pnotify.js");
  sys_addjs ( "js/select2.js");
  sys_addjs ( "js/select2.pt_BR.js");
  sys_addjs ( "js/bootstrap-tagsinput.js");
  sys_addjs ( "js/jquery.bootstrap-duallistbox.js");

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"config_form\">\n";

  // Add configuration tabs
  $output .= "  <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "    <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#config_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#config_tab_permissions\">" . __ ( "Permissions") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#config_tab_plugins\">" . __ ( "Plugins") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#config_tab_emergency\">" . __ ( "Emergency") . "</a></li>\n";
  $output .= "    <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#config_tab_extras\">" . __ ( "Extras") . "</a></li>\n";
  $output .= "  </ul>\n";
  $output .= "  <div class=\"tab-content\"><br />\n";

  // Basic options panel
  $output .= "  <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"config_tab_basic\">\n";

  // Add language selector
  $output .= "    <div class=\"form-group\">\n";
  $output .= "      <label for=\"config_language\" class=\"control-label col-xs-2\">" . __ ( "Language") . "</label>\n";
  $output .= "      <div class=\"col-xs-10\">\n";
  $output .= "        <select name=\"language\" id=\"config_language\" class=\"form-control\" data-placeholder=\"" . __ ( "Select a language") . "\">\n";
  $output .= "          <option value=\"\"></option>\n";
  foreach ( $_in["languages"] as $locale => $language)
  {
    $output .= "          <option value=\"" . $locale . "\">" . strip_tags ( $language) . "</option>\n";
  }
  $output .= "        </select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Add operator extension selector
  $output .= "    <div class=\"form-group\">\n";
  $output .= "      <label for=\"config_operator\" class=\"control-label col-xs-2\">" . __ ( "Operator") . "</label>\n";
  $output .= "      <div class=\"col-xs-10\">\n";
  $output .= "        <select name=\"transhipments\" id=\"config_operator\" class=\"form-control\" data-placeholder=\"" . __ ( "Operator extension") . "\"></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Add external call prefix field
  $output .= "    <div class=\"form-group\">\n";
  $output .= "      <label for=\"config_prefix\" class=\"control-label col-xs-2\">Prefixo externo</label>\n";
  $output .= "      <div class=\"col-xs-10\">\n";
  $output .= "        <input type=\"text\" name=\"prefix\" class=\"form-control\" id=\"config_prefix\" data-mask=\"0#\" data-mask-maxlength=\"false\" placeholder=\"Prefixo para ligações externas\" />\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Close basic tab
  $output .= "  </div>\n";

  // Permissions panel
  $output .= "  <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"config_tab_permissions\">\n";

  // Close basic tab
  $output .= "  </div>\n";

  // Plugins panel
  $output .= "  <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"config_tab_plugins\">\n";

  // Add plugins selector
  $output .= "    <div class=\"form-group\">\n";
  $output .= "      <label for=\"config_plugins\" class=\"control-label col-xs-2\">Plugins</label>\n";
  $output .= "      <div class=\"col-xs-10\">\n";
  $output .= "        <select name=\"plugins\" id=\"config_plugins\" class=\"form-control\" multiple=\"multiple\">\n";
  $output .= "          <option value=\"login-avatar\">Avatar</option>\n";
  $output .= "          <option value=\"centrals\" selected=\"selected\">Centrais</option>\n";
  $output .= "          <option value=\"reserves\" selected=\"selected\">Reservas</option>\n";
  $output .= "          <option value=\"blocks\" selected=\"selected\">Bloqueios</option>\n";
  $output .= "          <option value=\"exceptions\" selected=\"selected\">Excessões</option>\n";
  $output .= "          <option value=\"anatel\">Anatel</option>\n";
  $output .= "        </select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Close basic tab
  $output .= "  </div>\n";

  // Emergency numbers panel
  $output .= "  <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"config_tab_emergency\">\n";

  // Add emergency phone number list field
  $output .= "    <div class=\"form-group\">\n";
  $output .= "      <label for=\"config_emergency\" class=\"control-label col-xs-2\">Números de emergência</label>\n";
  $output .= "      <div class=\"col-xs-10\">\n";
  $output .= "        <input type=\"text\" name=\"emergency\" class=\"form-control\" id=\"config_emergency\" placeholder=\"Adicionar número\" />\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";

  // Close basic tab
  $output .= "  </div>\n";

  // Extras panel
  $output .= "  <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"config_tab_extras\">\n";

  // Close basic tab
  $output .= "  </div>\n";

  // Finish tabs
  $output .= "</div>\n";

  // Add buttons
  $output .= "<div class=\"form-group\">\n";
  $output .= "  <div class=\"col-md-6 col-sm-6 col-xs-12 col-md-offset-3\">\n";
  $output .= "    <a href=\"/config\" alt=\"\" role=\"button\" class=\"btn btn-primary\">Cancelar</a>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-success ladda-button\" data-style=\"zoom-out\" data-action=\"submit\">Alterar</button>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add configuration JavaScript code
   */
  sys_addjs ("$('#config_operator').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/extensions/search', 'GET')\n" .
              "});\n" .
              "$('#config_country').select2 (\n" .
              "{\n" .
              "  templateResult: function ( state)\n" .
              "  {\n" .
              "    if ( ! state.id)\n" .
              "    {\n" .
              "      return state.text;\n" .
              "    }\n" .
              "    return $('<span><span class=\"flag flag-' + state.id.toLowerCase () + '\"></span> ' + state.text + '</span>');\n" .
              "  },\n" .
              "  templateSelection: function ( state)\n" .
              "  {\n" .
              "    if ( ! state.id)\n" .
              "    {\n" .
              "      return state.text;\n" .
              "    }\n" .
              "    return $('<span><span class=\"flag flag-' + state.id.toLowerCase () + '\"></span> ' + state.text + '</span>');\n" .
              "  }\n" .
              "});\n" .
              "$('#config_language').select2 ();\n" .
              "$('#config_emergency').tagsinput (\n" .
              "{\n" .
              "  maxChars: 15,\n" .
              "  trimValue: true\n" .
              "});\n" .
              "$('#config_emergency').tagsinput ( 'input').on ( 'keyup', function ( e) { $(this).val ( $(this).val ().replace ( /\D/g, '')); });\n" .
              "$('#config_country').select2 ( 'open');\n" .
              "$('#config_plugins').bootstrapDualListbox (\n" .
              "{\n" .
              "  filterTextClear: 'Remover filtro',\n" .
              "  filterPlaceHolder: 'Filtro',\n" .
              "  moveSelectedLabel: 'Mover selecionados',\n" .
              "  moveAllLabel: 'Mover tudo',\n" .
              "  removeSelectedLabel: 'Remover selecionados',\n" .
              "  removeAllLabel: 'Remover tudo',\n" .
              "  selectedListLabel: 'Habilitados',\n" .
              "  nonSelectedListLabel: 'Desabilitados',\n" .
              "  infoText: 'Exibindo {0} plugin(s)',\n" .
              "  infoTextFiltered: '<span class=\"label label-warning\">Filtrando</span> {0} de {1}',\n" .
              "  infoTextEmpty: 'Lista vazia',\n" .
              "  moveOnSelect: false\n" .
              "});");

  return $output;
}
?>
