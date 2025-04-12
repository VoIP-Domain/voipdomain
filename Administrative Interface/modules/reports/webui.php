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
 * VoIP Domain reports module WebUI. This module add some reports feature to the
 * system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Reports
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/reports/graph/heat", "reports_graph_heat");
framework_add_hook ( "reports_graph_heat", "reports_graph_heat", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the heat map report page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function reports_graph_heat ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Graphical reports"));
  sys_set_subtitle ( __ ( "heat map graph"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Heat map graph"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "tui.chart", "src" => "/vendors/tui.chart/dist/tui-chart.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "raphael", "src" => "/vendors/raphael/raphael.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tui.code-snippet", "src" => "/vendors/tui.code-snippet/dist/tui-code-snippet.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "tui.chart", "src" => "/vendors/tui.chart/dist/tui-chart.js", "dep" => array ( "raphael", "tui.code-snippet")));

  /**
   * Report main div
   */
  $output = "<div class=\"row\">\n";
  $output .= "  <span class=\"col-md-5 center\">\n";
  $output .= "    <select name=\"Type\" id=\"type\" class=\"form-control\" data-placeholder=\"" . __ ( "Call type") . "\">\n";
  $output .= "      <option value=\"1\" selected=\"selected\">" . __ ( "All types") . "</option>\n";
  $output .= "      <option value=\"2\">" . __ ( "Internal calls") . "</option>\n";
  $output .= "      <option value=\"3\">" . __ ( "External calls") . "</option>\n";
  $output .= "      <option value=\"4\">" . __ ( "External calls (With no cost)") . "</option>\n";
  $output .= "      <option value=\"5\">" . __ ( "External calls (With cost)") . "</option>\n";
  $output .= "      <option value=\"6\">" . __ ( "Mobile calls") . "</option>\n";
  $output .= "      <option value=\"7\">" . __ ( "Interstate calls") . "</option>\n";
  $output .= "      <option value=\"8\">" . __ ( "International calls") . "</option>\n";
  $output .= "    </select>\n";
  $output .= "  </span>\n";
  $output .= "  <span class=\"col-md-5 pull-right\">\n";
  $output .= "    <div id=\"week\" class=\"week-picker\"><input type=\"text\" id=\"weekinput\" /><i class=\"fa fa-calendar\"></i> <span> - </span> <b class=\"caret pull-right\"></b></div>\n";
  $output .= "  </span>\n";
  $output .= "</div>\n";
  $output .= "<div id=\"graph\" style=\"width: 100% !important\"></div>\n";

  /**
   * Add heat map graphic JavaScript code
   */
  sys_addjs ( "$('#type').on ( 'change', function ()\n" .
              "                           {\n" .
              "                             $('#graph').trigger ( 'update');\n" .
              "                           }).select2 ( { allowClear: false });\n" .
              "$('#weekinput').css ( { width: 0, margin: 0, border: 0, padding: 0}).datetimepicker (\n" .
              "{\n" .
              "  format: '" . __ ( "MM/DD/YYYY") . "',\n" .
              "  defaultDate: moment ().day ( 0)\n" .
              "});\n" .
              "$('#week').css ( 'display', 'block').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e.preventDefault ();\n" .
              "  $('#weekinput').data ( 'DateTimePicker').show ();\n" .
              "}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  var value = $('#weekinput').val ();\n" .
              "  $('#weekinput').val ( $('#weekinput').data ( 'start')).data ( 'start', moment ( value, '" . __ ( "MM/DD/YYYY") . "').day ( 0).format ( '" . __ ( "MM/DD/YYYY") . "')).data ( 'end', moment ( value, '" . __ ( "MM/DD/YYYY") . "').day ( 6).format ( '" . __ ( "MM/DD/YYYY") . "'));\n" .
              "  $('#week span').html ( $('#weekinput').data ( 'start') + ' - ' + $('#weekinput').data ( 'end'));\n" .
              "  $('#graph').trigger ( 'update');\n" .
              "}).on ( 'dp.show', function ( e)\n" .
              "{\n" .
              "  $(this).find ( 'td.active').parent ( 'tr').find ( 'td').each ( function () { $(this).addClass ( 'active');});\n" .
              "});\n" .
              "( function ()\n" .
              "{\n" .
              "  var container = document.getElementById ( 'graph');\n" .
              "  var chartdata = {\n" .
              "    categories: {\n" .
              "      x: [ '00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],\n" .
              "      y: [ '" . __ ( "Sa") . "', '" . __ ( "Fr") . "', '" . __ ( "Th") . "', '" . __ ( "We") . "', '" . __ ( "Tu") . "', '" . __ ( "Mo") . "', '" . __ ( "Su") . "']\n" .
              "    },\n" .
              "    series: [\n" .
              "      [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],\n" .
              "      [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],\n" .
              "      [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],\n" .
              "      [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],\n" .
              "      [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],\n" .
              "      [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],\n" .
              "      [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]\n" .
              "    ]\n" .
              "  };\n" .
              "  var margin = { top: 50, right: 0, bottom: 100, left: 30};\n" .
              "  var options = {\n" .
              "    chart: {\n" .
              "      width: 960 - margin.left - margin.right,\n" .
              "      height: 430 - margin.top - margin.bottom\n" .
              "    },\n" .
              "    series: {\n" .
              "      showLabel: true\n" .
              "    },\n" .
              "    tooltip: {\n" .
              "      template: function ( category, item, categoryTimestamp)\n" .
              "                {\n" .
              "                  switch ( category.substr ( 4))\n" .
              "                  {\n" .
              "                    case '" . __ ( "Su") . "':\n" .
              "                      var day = moment ( $('#weekinput').val (), '" . __ ( "MM/DD/YYYY") . "').day ( 0).format ( '" . __ ( "MM/DD/YYYY") . "');\n" .
              "                      break;\n" .
              "                    case '" . __ ( "Mo") . "':\n" .
              "                      var day = moment ( $('#weekinput').val (), '" . __ ( "MM/DD/YYYY") . "').day ( 1).format ( '" . __ ( "MM/DD/YYYY") . "');\n" .
              "                      break;\n" .
              "                    case '" . __ ( "Tu") . "':\n" .
              "                      var day = moment ( $('#weekinput').val (), '" . __ ( "MM/DD/YYYY") . "').day ( 2).format ( '" . __ ( "MM/DD/YYYY") . "');\n" .
              "                      break;\n" .
              "                    case '" . __ ( "We") . "':\n" .
              "                      var day = moment ( $('#weekinput').val (), '" . __ ( "MM/DD/YYYY") . "').day ( 3).format ( '" . __ ( "MM/DD/YYYY") . "');\n" .
              "                      break;\n" .
              "                    case '" . __ ( "Th") . "':\n" .
              "                      var day = moment ( $('#weekinput').val (), '" . __ ( "MM/DD/YYYY") . "').day ( 4).format ( '" . __ ( "MM/DD/YYYY") . "');\n" .
              "                      break;\n" .
              "                    case '" . __ ( "Fr") . "':\n" .
              "                      var day = moment ( $('#weekinput').val (), '" . __ ( "MM/DD/YYYY") . "').day ( 5).format ( '" . __ ( "MM/DD/YYYY") . "');\n" .
              "                      break;\n" .
              "                    case '" . __ ( "Sa") . "':\n" .
              "                      var day = moment ( $('#weekinput').val (), '" . __ ( "MM/DD/YYYY") . "').day ( 6).format ( '" . __ ( "MM/DD/YYYY") . "');\n" .
              "                      break;\n" .
              "                    default:\n" .
              "                      var day = category.substr ( 4);\n" .
              "                      break;\n" .
              "                  }\n" .
              "                  return '<div class=\"tui-chart-default-tooltip\"><div class=\"tui-chart-tooltip-head show\">' + day + ' ' + category.substr ( 0, 2) + 'h</div><div class=\"tui-chart-tooltip-body\"><span class=\"tui-chart-legend-rect heatmap\" style=\"' + item.cssText + '\"></span><span>' + item.label + ' ' + ( item.label == 1 ? '" . __ ( "call") . "' : '" . __ ( "calls") . "') + '</span></div></div>';\n" .
              "                }\n" .
              "    },\n" .
              "    chartExportMenu: {\n" .
              "      filename: 'VoIPDomain-Heatmap'\n" .
              "    }\n" .
              "  };\n" .
              "  var theme = {\n" .
              "    series: {\n" .
              "      startColor: '#ffefef',\n" .
              "      endColor: '#ac4142',\n" .
              "      overColor: '#75b5aa',\n" .
              "      borderColor: '#f4511e'\n" .
              "    }\n" .
              "  };\n" .
              "  $('#graph').on ( 'update', function ( event, data)\n" .
              "  {\n" .
              "    if ( data && 'Startup' in data)\n" .
              "    {\n" .
              "      $('#weekinput').val ( moment ().day ( 0).format ( '" . __ ( "MM/DD/YYYY") . "')).data ( 'start', moment ().day ( 0).format ( '" . __ ( "MM/DD/YYYY") . "')).data ( 'end', moment ().day ( 6).format ( '" . __ ( "MM/DD/YYYY") . "'));\n" .
              "      $('#week span').html ( $('#weekinput').data ( 'start') + ' - ' + $('#weekinput').data ( 'end'));\n" .
              "      $('#type').val ( 1).trigger ( 'change.select2');\n" .
              "    }\n" .
              "    if ( data && 'Start' in data)\n" .
              "    {\n" .
              "      $('#weekinput').val ( moment ( data.Start).format ( '" . __ ( "MM/DD/YYYY") . "')).data ( 'start', moment ( data.Start).day ( 0).format ( '" . __ ( "MM/DD/YYYY") . "')).data ( 'end', moment ( data.Start).day ( 6).format ( '" . __ ( "MM/DD/YYYY") . "'));\n" .
              "      $('#week span').html ( $('#weekinput').data ( 'start') + ' - ' + $('#weekinput').data ( 'end'));\n" .
              "    }\n" .
              "    if ( data && 'Type' in data)\n" .
              "    {\n" .
              "      $('#type').val ( data.Type).trigger ( 'change.select2');\n" .
              "    }\n" .
              "    if ( ! data || ( data && ! 'Startup' in data))\n" .
              "    {\n" .
              "      $.hashForm ( 'set', { data: { Type: $('#type').val (), Start: moment ( $('#weekinput').val (), 'L LTS').isValid () ? moment ( $('#weekinput').val (), 'L LTS').utc ().format () : ''}});\n" .
              "    }\n" .
              "    VoIP.rest ( '/reports/weekhour', 'GET', { Type: $('#type').val (), Start: moment ( $('#weekinput').val (), 'L LTS').isValid () ? moment ( $('#weekinput').val (), 'L LTS').utc ().format () : ''}).done ( function ( result, textStatus, jqXHR)\n" .
              "    {\n" .
              "      chartdata.series = result;\n" .
              "      $('#graph').empty ();\n" .
              "      tui.chart.heatmapChart ( container, chartdata, options);\n" .
              "      $('div.tui-chart').css ( 'margin', '0 auto');\n" .
              "      $('li.tui-chart-chartExportMenu-head').html ( '" . __ ( "Export to") . "');\n" .
              "    }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "    {\n" .
              "      new PNotify ( { title: '" . __ ( "Heat map graph") . "', text: '" . __ ( "Error requesting information!") . "', type: 'error'});\n" .
              "    });\n" .
              "  });\n" .
              "} ());\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "graph", "update");

  /**
   * Set page start event
   */
  sys_set_startevent ( "graph", "update", array ( "Startup" => true));

  return $output;
}

/**
 * Add extensions list report page
 */
framework_add_path ( "/reports/list", "extensions_list_report_page");
framework_add_hook ( "extensions_list_report_page", "extensions_list_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the extensions list report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_list_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "extensions listing"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-5\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group\">\n";
  $output .= "          <input name=\"Description\" id=\"description\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Description") . "\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-5\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"Type\" id=\"type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\">\n";
  $output .= "          <option value=\"\" selected></option>\n";
  $intobjects = (array) filters_call ( "objects_types");
  foreach ( $intobjects as $object)
  {
    if ( substr ( $object["object"], 0, 10) == "extension_")
    {
      $output .= "          <option value=\"" . addslashes ( strip_tags ( substr ( $object["object"], 10))) . "\">" . addslashes ( strip_tags ( __ ( $object["text"]["plural"]))) . "</option>\n";
    }
  }
  $output .= "        </select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#type').select2 (\n" .
              "{\n" .
              "  allowClear: true\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Number', title: '" . __ ( "Number") . "', width: '10%', class: 'export all'},\n" .
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '50%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/extensions/' + row.ID + '/view\">' + row.Description + '</a>'; } else { return row.Description; }}},\n" .
              "             { data: 'Type', title: '" . __ ( "Type") . "', width: '40%', class: 'export all', render: function ( data, type, row, meta) { return VoIP.interface.objTextSingular ( row.Type); }}\n" .
              "           ],\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#description').val ( '');\n" .
              "    $('#type').val ( '').trigger ( 'change.select2');\n" .
              "  }\n" .
              "  if ( data && 'Name' in data)\n" .
              "  {\n" .
              "    $('#description').val ( data.Name);\n" .
              "  }\n" .
              "  if ( data && 'Type' in data)\n" .
              "  {\n" .
              "    $('#type').val ( data.Type).trigger ( 'change.select2');\n" .
              "  }\n" .
              "  if ( ! data || ( data && ! 'Startup' in data))\n" .
              "  {\n" .
              "    $.hashForm ( 'set', { data: { Type: $('#type').val (), Name: $('#description').val ()}});\n" .
              "  }\n" .
              "  VoIP.dataTablesUpdate ( { path: '/reports/list', fields: 'ID,Number,Description,Type', data: { Name: $('#description').val (), Type: $('#type').val ()}}, $('#report').data ( 'dt'));\n" .
              "});\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  return $output;
}

/**
 * Add ranges list report page
 */
framework_add_path ( "/reports/ranges", "ranges_list_report_page");
framework_add_hook ( "ranges_list_report_page", "ranges_list_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the ranges list report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function ranges_list_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "ranges listing"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"Range\" id=\"range\" class=\"form-control\" data-placeholder=\"" . __ ( "Range") . "\"><option value=\"\"></option></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#range').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/ranges', fields: 'ID,Description,Server', formatText: '%Description% (%Server%)'})\n" .
              "}).on ( 'select2:select, select2:unselect, change', function ()\n" .
              "{\n" .
              "  $('#filters').trigger ( 'submit');\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Extension', title: '" . __ ( "Extension") . "', width: '10%', class: 'export all'},\n" .
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '40%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return row.Description ? '<a href=\"/extensions/' + row.ID + '/view\">' + row.Description + '</a>' : '--'; } else { return row.Description; }}},\n" .
              "             { data: 'Type', title: '" . __ ( "Type") . "', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return row.Type ? '<span class=\"label label-' + VoIP.interface.objLabel ( row.Type) + '\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-value=\"' + row.Type + '\" data-original-title=\"' + VoIP.interface.objTextSingular ( row.Type) + '\" title=\"\"><i class=\"fa fa-' + VoIP.interface.objIcon ( row.Type) + '\"></i></span>' : '--'; } else { return row.Type ? VoIP.interface.objTextSingular ( row.Type) : '--'; }}},\n" .
              "             { data: 'Range', title: '" . __ ( "Range") . "', width: '20%', class: 'export min-mobile-l', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/ranges/' + row.RangeID + '/view\">' + row.Range + '</a>'; } else { return row.Range; }}},\n" .
              "             { data: 'Server', title: '" . __ ( "Server") . "', width: '20%', class: 'export min-mobile-l', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/servers/' + row.ServerID + '/view\">' + row.Server + '</a>'; } else { return row.Server; }}}\n" .
              "           ],\n" .
	      "  order: [[ 0, 'asc' ]],\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#range').val ( '').trigger ( 'change.select2');\n" .
              "  }\n" .
              "  if ( data && 'Range' in data)\n" .
              "  {\n" .
              "    $('#range').val ( data.Range).trigger ( 'change.select2');\n" .
              "  }\n" .
              "  if ( ! data || ( data && ! 'Startup' in data))\n" .
              "  {\n" .
              "    $.hashForm ( 'set', { data: { Range: $('#range').val ()}});\n" .
              "  }\n" .
              "  VoIP.dataTablesUpdate ( { path: '/reports/ranges', fields: 'ID,Extension,Description,Type,Range,RangeID,Server,ServerID', data: { Range: $('#range').val ()}}, $('#report').data ( 'dt'));\n" .
              "});\n");

   /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  return $output;
}

/**
 * Add extensions activity list report page
 */
framework_add_path ( "/reports/activity", "extensions_activity_report_page");
framework_add_hook ( "extensions_activity_report_page", "extensions_activity_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the extensions activity report page code.
 *
 * @global array $_in Framework global configuration variable
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_activity_report_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "activity listing"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "accent-neutralise", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-timeago", "src" => "/vendors/jquery-timeago/jquery.timeago.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-timeago-locale", "src" => "/vendors/jquery-timeago/locales/jquery.timeago." . ( $_in["session"]["Language"] == "en_US" ? "en" : $_in["session"]["Language"]) . ".js", "dep" => array ( "jquery-timeago")));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-xs-10 form-group\">\n";
  $output .= "      <div class=\"input-group\">\n";
  $output .= "        <input name=\"filter\" id=\"filter\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Filter") . "\" />\n";
  $output .= "        <div class=\"input-group-btn\">\n";
  $output .= "          <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Number', title: '" . __ ( "Extension") . "', width: '10%', class: 'export all'},\n" .
              "             { data: 'Description', title: '" . __ ( "Name") . "', width: '40%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/extensions/' + row.ID + '/view\">' + row.Description + '</a>'; } else { return row.Description; }}},\n" .
              "             { data: 'LastDialed', title: '" . __ ( "Last dialed") . "', width: '25%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return row.LastDialed.Timestamp == '' ? '" . __ ( "Never dialed") . "' : moment ( row.LastDialed.Datetime).format ( '" . __ ( "MM/DD/YYYY LTS") . "') + ' (' + jQuery.timeago ( new Date ( row.LastDialed.Timestamp * 1000).toISOString ()) + ')'; } else { return row.LastDialed.Timestamp; }}},\n" .
              "             { data: 'LastReceived', title: '" . __ ( "Last received") . "', width: '25%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return row.LastReceived.Timestamp == '' ? '" . __ ( "Never received") . "' : moment ( row.LastReceived.Datetime).format ( '" . __ ( "MM/DD/YYYY LTS") . "') + ' (' + jQuery.timeago ( new Date ( row.LastReceived.Timestamp * 1000).toISOString ()) + ')'; } else { return row.LastReceived.Timestamp; }}}\n" .
              "           ],\n" .
              "  stateLoadParams: function ( settings, data)\n" .
              "                   {\n" .
              "                     $('#filter').val ( data.search.search);\n" .
              "                   },\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>',\n" .
              "  order: [[ 2, 'desc' ], [ 3, 'desc' ]]\n" .
              "}));\n" .
              "$('#filter').on ( 'keyup', function ()\n" .
              "{\n" .
              "  $('#report').data ( 'dt').search ( $(this).val ()).draw ();\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "  $('#report').data ( 'dt').search ( '').draw ();\n" .
              "});\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "});\n" .
              "VoIP.dataTablesUpdate ( { path: '/reports/activity', fields: 'ID,Number,Description,LastDialed,LastReceived'}, $('#report').data ( 'dt'));\n");

  return $output;
}

/**
 * Add financial cost center report page
 */
framework_add_path ( "/reports/financial/costcenter", "costcenter_financial_report_page");
framework_add_hook ( "costcenter_financial_report_page", "costcenter_financial_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the financial cost center report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function costcenter_financial_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "financial by cost center"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"Start\" id=\"start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"End\" id=\"end\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-4\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"costcenter\" id=\"costcenter\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\"><option value=\"\"></option></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#costcenter').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/costcenters', fields: 'ID,Description,Code', formatText: '%Description% (%Code%)'})\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Extension.Number', title: '" . __ ( "Extension") . "', width: '15%', class: 'export all'},\n" .
              "             { data: 'Extension.Description', title: '" . __ ( "Name") . "', width: '35%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/extensions/' + row.Extension.ID + '/report#' + btoa ( JSON.stringify ( { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''})) + '\">' + row.Extension.Description + '</a>'; } else { return row.Extension.Description; }}},\n" .
              "             { data: 'Total', title: '" . __ ( "Calls") . "', width: '15%', class: 'export min-mobile-l'},\n" .
              "             { data: 'FormattedTime', title: '" . __ ( "Time") . "', width: '20%', class: 'export min-mobile-l', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return row.FormattedTime; } else { return row.Time; }}},\n" .
              "             { data: 'Cost', title: '" . __ ( "Cost") . "', width: '15%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Cost; }}}\n" .
              "           ],\n" .
              "  footerCallback: function ( row, data, start, end, display)\n" .
              "  {\n" .
              "    let api = this.api ();\n" .
              "    let totalcalls = 0;\n" .
              "    let totaltime = 0;\n" .
              "    let totalcost = 0;\n" .
              "    data.forEach ( function ( currentRow)\n" .
              "    {\n" .
              "      totalcalls += currentRow.Total;\n" .
              "      totaltime += currentRow.Time;\n" .
              "      totalcost += currentRow.Cost;\n" .
              "    });\n" .
              "    $(api.column ( 2).footer()).html ( totalcalls);\n" .
              "    $(api.column ( 3).footer()).html ( format_secs_to_string ( totaltime));\n" .
              "    $(api.column ( 4).footer()).html ( parseFloat ( totalcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "  },\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#start').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#end').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "});\n" .
              "$('#end').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#start').data ( 'DateTimePicker').maxDate ( e.date);\n" .
              "});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "    $('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "    $('#costcenter').val ( '').trigger ( 'change.select2');\n" .
              "    $('#report').data ( 'dt').clear ().draw ().responsive.recalc ();\n" .
              "    $('#costcenter').focus ();\n" .
              "    return;\n" .
              "  }\n" .
              "  if ( data && 'Start' in data)\n" .
              "  {\n" .
              "    $('#start').val ( moment ( data.Start).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "  }\n" .
              "  if ( data && 'End' in data)\n" .
              "  {\n" .
              "    $('#end').val ( moment ( data.End).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "  }\n" .
              "  if ( data && 'CostCenter' in data)\n" .
              "  {\n" .
              "    $('#costcenter').val ( data.CostCenter).trigger ( 'change.select2');\n" .
              "  }\n" .
              "  if ( $('#start').val () == '')\n" .
              "  {\n" .
              "    $('#start').alerts ( 'add', { message: '" . __ ( "The start date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#end').val () == '')\n" .
              "  {\n" .
              "    $('#end').alerts ( 'add', { message: '" . __ ( "The finish date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#costcenter').val () == '')\n" .
              "  {\n" .
              "    $('#costcenter').alerts ( 'add', { message: '" . __ ( "The cost center is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#start').val () == '' || $('#end').val () == '' || $('#costcenter').val () == '')\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { data: { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : '', CostCenter: $('#costcenter').val ()}});\n" .
              "  VoIP.dataTablesUpdate ( { path: '/reports/financial/costcenter/' + encodeURIComponent ( $('#costcenter').val ()), fields: 'Extension,Total,Time,FormattedTime,Cost', data: { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}}, $('#report').data ( 'dt'));\n" .
              "});\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  return $output;
}

/**
 * Add financial group report page
 */
framework_add_path ( "/reports/financial/group", "group_financial_report_page");
framework_add_hook ( "group_financial_report_page", "group_financial_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the financial group report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function group_financial_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "financial by group"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"Start\" id=\"start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"End\" id=\"end\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-4\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"group\" id=\"group\" class=\"form-control\" data-placeholder=\"" . __ ( "Group") . "\"><option value=\"\"></option></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#group').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.APIsearch ( { path: '/groups', fields: 'ID,Description', formatText: '%Description%'})\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Extension.Number', title: '" . __ ( "Extension") . "', width: '15%', class: 'export all'},\n" .
              "             { data: 'Extension.Description', title: '" . __ ( "Name") . "', width: '35%', class: 'export min-tablet-l', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/extensions/' + row.Extension.ID + '/report#' + btoa ( JSON.stringify ( { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''})) + '\">' + row.Extension.Description + '</a>'; } else { return row.Extension.Description; }}},\n" .
              "             { data: 'Total', title: '" . __ ( "Calls") . "', width: '15%', class: 'export all'},\n" .
              "             { data: 'FormattedTime', title: '" . __ ( "Time") . "', width: '20%', class: 'export all'},\n" .
              "             { data: 'Cost', title: '" . __ ( "Cost") . "', width: '15%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Cost; }}}\n" .
              "           ],\n" .
              "  footerCallback: function ( row, data, start, end, display)\n" .
              "  {\n" .
              "    let api = this.api ();\n" .
              "    let totalcalls = 0;\n" .
              "    let totaltime = 0;\n" .
              "    let totalcost = 0;\n" .
              "    data.forEach ( function ( currentRow)\n" .
              "    {\n" .
              "      totalcalls += currentRow.Total;\n" .
              "      totaltime += currentRow.Time;\n" .
              "      totalcost += currentRow.Cost;\n" .
              "    });\n" .
              "    $(api.column ( 2).footer()).html ( totalcalls);\n" .
              "    $(api.column ( 3).footer()).html ( format_secs_to_string ( totaltime));\n" .
              "    $(api.column ( 4).footer()).html ( parseFloat ( totalcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "  },\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#start').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#end').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "});\n" .
              "$('#end').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#start').data ( 'DateTimePicker').maxDate ( e.date);\n" .
              "});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "    $('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "    $('#group').val ( '').trigger ( 'change.select2');\n" .
              "    $('#report').data ( 'dt').clear ().draw ().responsive.recalc ();\n" .
              "    $('#group').focus ();\n" .
              "    return;\n" .
              "  }\n" .
              "  if ( data && 'Start' in data)\n" .
              "  {\n" .
              "    $('#start').val ( moment ( data.Start).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "  }\n" .
              "  if ( data && 'End' in data)\n" .
              "  {\n" .
              "    $('#end').val ( moment ( data.End).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "  }\n" .
              "  if ( data && 'Group' in data)\n" .
              "  {\n" .
              "    $('#group').val ( data.Group).trigger ( 'change.select2');\n" .
              "  }\n" .
              "  if ( $('#start').val () == '')\n" .
              "  {\n" .
              "    $('#start').alerts ( 'add', { message: '" . __ ( "The start date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#end').val () == '')\n" .
              "  {\n" .
              "    $('#end').alerts ( 'add', { message: '" . __ ( "The finish date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#group').val () == '')\n" .
              "  {\n" .
              "    $('#group').alerts ( 'add', { message: '" . __ ( "The group is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#start').val () == '' || $('#end').val () == '' || $('#group').val () == '')\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { data: { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : '', Group: $('#group').val ()}});\n" .
              "  VoIP.dataTablesUpdate ( { path: '/reports/financial/group/' + encodeURIComponent ( $('#group').val ()), fields: 'Extension,Total,Time,FormattedTime,Cost', data: { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}}, $('#report').data ( 'dt'));\n" .
              "});\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  return $output;
}

/**
 * Add financial gateway report page
 */
framework_add_path ( "/reports/financial/gateway", "gateway_financial_report_page");
framework_add_hook ( "gateway_financial_report_page", "gateway_financial_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the financial gateway report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateway_financial_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "financial by gateway"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-5\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"Start\" id=\"start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-5\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"End\" id=\"end\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th>" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Gateway.Description', title: '" . __ ( "Gateway") . "', width: '50%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/gateways/' + row.Gateway.ID + '/report#' + btoa ( JSON.stringify ( { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''})) + '\">' + row.Gateway.Description + '</a>'; } else { return row.Gateway.Description; }}},\n" .
              "             { data: 'Total', title: '" . __ ( "Calls") . "', width: '15%', class: 'export all'},\n" .
              "             { data: 'FormattedTime', title: '" . __ ( "Time") . "', width: '20%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return row.FormattedTime; } else { return row.Time; }}},\n" .
              "             { data: 'Cost', title: '" . __ ( "Cost") . "', width: '15%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.
Cost; }}}\n" .
              "           ],\n" .
              "  footerCallback: function ( row, data, start, end, display)\n" .
              "  {\n" .
              "    let api = this.api ();\n" .
              "    let totalcalls = 0;\n" .
              "    let totaltime = 0;\n" .
              "    let totalcost = 0;\n" .
              "    data.forEach ( function ( currentRow)\n" .
              "    {\n" .
              "      totalcalls += currentRow.Total;\n" .
              "      totaltime += currentRow.Time;\n" .
              "      totalcost += currentRow.Cost;\n" .
              "    });\n" .
              "    $(api.column ( 1).footer()).html ( totalcalls);\n" .
              "    $(api.column ( 2).footer()).html ( format_secs_to_string ( totaltime));\n" .
              "    $(api.column ( 3).footer()).html ( parseFloat ( totalcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "  },\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#start').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#end').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "});\n" .
              "$('#end').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#start').data ( 'DateTimePicker').maxDate ( e.date);\n" .
              "});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#start').val ( moment ().subtract ( 30, 'days').format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "    $('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "    $('#report').data ( 'dt').draw ().clear ().responsive.recalc ();\n" .
              "    return;\n" .
              "  }\n" .
              "  if ( data && 'Start' in data)\n" .
              "  {\n" .
              "    $('#start').val ( moment ( data.Start).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "  }\n" .
              "  if ( data && 'End' in data)\n" .
              "  {\n" .
              "    $('#end').val ( moment ( data.End).format ( '" . __ ( "MM/DD/YYYY LTS") . "'));\n" .
              "  }\n" .
              "  if ( $('#start').val () == '')\n" .
              "  {\n" .
              "    $('#start').alerts ( 'add', { message: '" . __ ( "The start date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#end').val () == '')\n" .
              "  {\n" .
              "    $('#end').alerts ( 'add', { message: '" . __ ( "The finish date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#start').val () == '' || $('#end').val () == '')\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { data: { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}});\n" .
              "  VoIP.dataTablesUpdate ( { path: '/reports/financial/gateway', fields: 'Gateway,Total,Time,FormattedTime,Cost', data: { Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}}, $('#report').data ( 'dt'));\n" .
              "});\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  return $output;
}

/**
 * Server health page
 */
framework_add_hook ( "health_page", "health_page");
framework_add_path ( "/reports/status", "health_page");

/**
 * Function to draw a server health page to user.
 *
 * @global array $_in Framework global configuration variable
 * @param string $page Buffer from plugin system if processed by other function
 *                     before
 * @param array $parameters Framework page structure
 * @return array Framework page structure with generated content
 */
function health_page ( $page, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "server health"));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-knob", "src" => "/vendors/jquery-knob/dist/jquery.knob.js", "dep" => array ()));

  /**
   * Dashboard
   */
  $output = "<div class=\"pull-right\">\n";
  $output .= "  <select name=\"refresh\" id=\"refresh\" class=\"form-control\" data-placeholder=\"" . __ ( "Automatic update") . "\">\n";
  $output .= "    <option value=\"\">" . __ ( "Disabled") . "</option>\n";
  $output .= "    <option value=\"1\">" . __ ( "1 second") . "</option>\n";
  $output .= "    <option value=\"5\">" . sprintf ( __ ( "%d seconds"), 5) . "</option>\n";
  $output .= "    <option value=\"15\">" . sprintf ( __ ( "%d seconds"), 15) . "</option>\n";
  $output .= "    <option value=\"30\">" . sprintf ( __ ( "%d seconds"), 30) . "</option>\n";
  $output .= "    <option value=\"60\">" . sprintf ( __ ( "%d seconds"), 60) . "</option>\n";
  $output .= "  </select>\n";
  $output .= "</div>\n";
  $output .= "<div class=\"clearfix\"></div>\n";

  // Draw information knob dials
  $output .= "<div id=\"stats\" class=\"row circleStats\">\n";

  // Memory
  $output .= "  <div class=\"col-md-2 col-sm-4 col-xs-6\">\n";
  $output .= "    <div class=\"circleStatsItem blue\">\n";
  $output .= "      <i class=\"fa fa-bullhorn\"></i>\n";
  $output .= "      <span class=\"plus\">+</span>\n";
  $output .= "      <span class=\"percent\">%</span>\n";
  $output .= "      <input type=\"text\" id=\"health_memory\" value=\"0\" class=\"blueCircle\" />\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"box-small-title\"><span class=\"legend\" data-original-title=\"" . __ ( "Used memory * 100 / Total memory") . "\" data-toggle=\"tooltip\">" . __ ( "Memory") . "</span><br /><span id=\"health_memory_used\"></span> " . __ ( "of") . " <span id=\"health_memory_total\"></span></div>\n";
  $output .= "  </div>\n";

  // CPU
  $output .= "  <div class=\"col-md-2 col-sm-4 col-xs-6\">\n";
  $output .= "    <div class=\"circleStatsItem red\">\n";
  $output .= "      <i class=\"fa fa-thumbs-up\"></i>\n";
  $output .= "      <span class=\"plus\">+</span>\n";
  $output .= "      <span class=\"percent\">%</span>\n";
  $output .= "      <input type=\"text\" id=\"health_cpu\" value=\"0\" class=\"orangeCircle\" />\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"box-small-title\"><span class=\"legend\" data-original-title=\"" . __ ( "Processors usage sum (percentage) / Number of processors") . "\" data-toggle=\"tooltip\">" . __ ( "CPU") . "</span><br /><span id=\"health_cpu_processors\"></span> " . __ ( "processor(s)") . "</div>\n";
  $output .= "  </div>\n";

  // Storage
  $output .= "  <div class=\"col-md-2 col-sm-4 col-xs-6\">\n";
  $output .= "    <div class=\"circleStatsItem yellow\">\n";
  $output .= "      <i class=\"fa fa-user\"></i>\n";
  $output .= "      <span class=\"plus\">+</span>\n";
  $output .= "      <span class=\"percent\">%</span>\n";
  $output .= "      <input type=\"text\" id=\"health_storage\" value=\"0\" class=\"yellowCircle\" />\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"box-small-title\"><span class=\"legend\" data-original-title=\"" . __ ( "Used space * 100 / Total space") . "\" data-toggle=\"tooltip\">" . __ ( "Storage") . "</span><br /><span id=\"health_storage_used\"></span> " . __ ( "of") . " <span id=\"health_storage_total\"></span></div>\n";
  $output .= "  </div>\n";

  /**
   * Add system health JavaScript code
   */
  sys_addjs ( "$('#refresh').select2 (\n" .
              "{\n" .
              "  allowClear: true\n" .
              "}).on ( 'select2:select', function ( e)\n" .
              "{\n" .
              "  clearTimeout ( $(this).data ( 'timeout'));\n" .
              "  if ( e.params.data != '')\n" .
              "  {\n" .
              "    $(this).data ( 'timeout', setTimeout ( function () { $('#stats').trigger ( 'update'); }, e.params.data * 1000));\n" .
              "  }\n" .
              "}).on ( 'select2:unselect', function ()\n" .
              "{\n" .
              "  clearTimeout ( $(this).data ( 'timeout'));\n" .
              "});\n" .
              "$('.blueCircle').knob (\n" .
              "{\n" .
              "  'min': 0,\n" .
              "  'max': 100,\n" .
              "  'readOnly': true,\n" .
              "  'width': 120,\n" .
              "  'height': 120,\n" .
              "  'fgColor': '#2FABE9',\n" .
              "  'dynamicDraw': true,\n" .
              "  'thickness': 0.2,\n" .
              "  'tickColorizeValues': true,\n" .
              "  'skin': 'tron',\n" .
              "  'animated': 2000\n" .
              "});\n" .
              "$('.orangeCircle').knob (\n" .
              "{\n" .
              "  'min': 0,\n" .
              "  'max': 100,\n" .
              "  'readOnly': true,\n" .
              "  'width': 120,\n" .
              "  'height': 120,\n" .
              "  'fgColor': '#FA5833',\n" .
              "  'dynamicDraw': true,\n" .
              "  'thickness': 0.2,\n" .
              "  'tickColorizeValues': true,\n" .
              "  'skin': 'tron',\n" .
              "  'animated': 2000\n" .
              "});\n" .
              "$('.yellowCircle').knob (\n" .
              "{\n" .
              "  'min': 0,\n" .
              "  'max': 100,\n" .
              "  'readOnly': true,\n" .
              "  'width': 120,\n" .
              "  'height': 120,\n" .
              "  'fgColor': '#e7e572',\n" .
              "  'dynamicDraw': true,\n" .
              "  'thickness': 0.2,\n" .
              "  'tickColorizeValues': true,\n" .
              "  'skin': 'tron',\n" .
              "  'animated': 2000\n" .
              "});\n" .
              "$('span.legend').tooltip (\n" .
              "{\n" .
              "  delay: { 'show': 100, 'hide': 100 }\n" .
              "});\n" .
              "$('#stats').on ( 'update', function ( event, start, end)\n" .
              "{\n" .
              "  VoIP.rest ( '/reports/status', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#health_memory').val ( data.Memory.Percent).trigger ( 'change');\n" .
              "    $('#health_memory_used').html ( data.Memory.Used);\n" .
              "    $('#health_memory_total').html ( data.Memory.Total);\n" .
              "    $('#health_cpu').val ( data.CPU.Percent).trigger ( 'change');\n" .
              "    $('#health_cpu_processors').html ( data.CPU.Processors);\n" .
              "    $('#health_storage').val ( data.Storage.Percent).trigger ( 'change');\n" .
              "    $('#health_storage_used').html ( data.Storage.Used);\n" .
              "    $('#health_storage_total').html ( data.Storage.Total);\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Server health") . "', text: '" . __ ( "Error requesting information!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    if ( $('#refresh').val () != '')\n" .
              "    {\n" .
              "      $(this).data ( 'timeout', setTimeout ( function () { $('#stats').trigger ( 'update'); }, $('#refresh').val () * 1000));\n" .
              "    }\n" .
              "  });\n" .
              "}).trigger ( 'update');\n");

  /**
   * Return generated HTML
   */
  return $output;
}

/**
 * Add user received calls report page
 */
framework_add_path ( "/reports/received/user", "user_calls_report_page");
framework_add_hook ( "user_calls_report_page", "user_calls_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the user received calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function user_calls_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "calls received by user"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  // Generate call report page through hook call:
  return framework_call ( "call_report_page_generate", array (
    "Filters" => "    <div class=\"col-md-4\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <select name=\"Extension\" id=\"extension\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension") . "\"><option value=\"\"></option></select>\n" .
                 "      </div>\n" .
                 "    </div>\n",
    "JS" => array (
      "Start" => "$('#extension').select2 (\n" .
                 "{\n" .
                 "  allowClear: true,\n" .
                 "  data: VoIP.APIsearch ( { path: '/extensions', Fields: 'ID,Description,Number', formatText: '%Description% (%Number%)'})\n" .
                 "});\n",
      "Focus" => "extension",
      "StartFilters" => "    $('#extension').val ( '').trigger ( 'change.select2');\n",
      "Filters" => "  if ( data && 'Extension' in data)\n" .
                   "  {\n" .
                   "    $('#extension').val ( data.Extension).trigger ( 'change.select2');\n" .
                   "  }\n",
      "CheckFilters" => "  if ( $('#extension').val () == '')\n" .
                        "  {\n" .
                        "    $('#extension').alerts ( 'add', { message: '" . __ ( "The extension is required.") . "'});\n" .
                        "  }\n",
      "CheckIf" => "$('#start').val () == '' || $('#end').val () == '' || $('#extension').val () == ''"
    ),
    "DataFilter" => "      data[x].ID = $('#extension').val ();\n",
    "HashForm" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : '', Extension: $('#extension').val ()}",
    "Endpoint" => array (
      "URL" => "'/reports/received/user/' + encodeURIComponent ( $('#extension').val ())",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}

/**
 * Add group received calls report page
 */
framework_add_path ( "/reports/received/group", "group_calls_report_page");
framework_add_hook ( "group_calls_report_page", "group_calls_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the group received calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function group_calls_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "calls received by group"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  // Generate call report page through hook call:
  return framework_call ( "call_report_page_generate", array (
    "Filters" => "    <div class=\"col-md-4\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <select name=\"Group\" id=\"group\" class=\"form-control\" data-placeholder=\"" . __ ( "Group") . "\"><option value=\"\"></option></select>\n" .
                 "      </div>\n" .
                 "    </div>\n",
    "JS" => array (
      "Start" => "$('#group').select2 (\n" .
                 "{\n" .
                 "  allowClear: true,\n" .
                 "  data: VoIP.APIsearch ( { path: '/groups', Fields: 'ID,Description', formatText: '%Description%'})\n" .
                 "});\n",
      "Focus" => "group",
      "StartFilters" => "    $('#group').val ( '').trigger ( 'change.select2');\n",
      "Filters" => "  if ( data && 'Group' in data)\n" .
                   "  {\n" .
                   "    $('#group').val ( data.Group).trigger ( 'change.select2');\n" .
                   "  }\n",
      "CheckFilters" => "  if ( $('#group').val () == '')\n" .
                        "  {\n" .
                        "    $('#group').alerts ( 'add', { message: '" . __ ( "The group is required.") . "'});\n" .
                        "  }\n",
      "CheckIf" => "$('#start').val () == '' || $('#end').val () == '' || $('#group').val () == ''"
    ),
    "DataFilter" => "      data[x].ID = $('#group').val ();\n",
    "HashForm" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : '', Group: $('#group').val ()}",
    "Endpoint" => array (
      "URL" => "'/reports/received/group/' + encodeURIComponent ( $('#group').val ())",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}

/**
 * Add gateway received calls report page
 */
framework_add_path ( "/reports/received/gateway", "gateway_calls_report_page");
framework_add_hook ( "gateway_calls_report_page", "gateway_calls_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the gateway received calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateway_calls_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "calls received by gateway"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  // Generate call report page through hook call:
  return framework_call ( "call_report_page_generate", array (
    "Filters" => "    <div class=\"col-md-4\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <select name=\"Gateway\" id=\"gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\"><option value=\"\"></option></select>\n" .
                 "      </div>\n" .
                 "    </div>\n",
    "JS" => array (
      "Start" => "$('#gateway').select2 (\n" .
                 "{\n" .
                 "  allowClear: true,\n" .
                 "  data: VoIP.APIsearch ( { path: '/gateways', Fields: 'ID,Description', formatText: '%Description%'})\n" .
                 "});\n",
      "Focus" => "gateway",
      "StartFilters" => "    $('#gateway').val ( '').trigger ( 'change.select2');\n",
      "Filters" => "  if ( data && 'Gateway' in data)\n" .
                   "  {\n" .
                   "    $('#gateway').val ( data.Gateway).trigger ( 'change.select2');\n" .
                   "  }\n",
      "CheckFilters" => "  if ( $('#gateway').val () == '')\n" .
                        "  {\n" .
                        "    $('#gateway').alerts ( 'add', { message: '" . __ ( "The gateway is required.") . "'});\n" .
                        "  }\n",
      "CheckIf" => "$('#start').val () == '' || $('#end').val () == '' || $('#gateway').val () == ''"
    ),
    "DataFilter" => "      data[x].ID = $('#gateway').val ();\n",
    "HashForm" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : '', Gateway: $('#gateway').val ()}",
    "Endpoint" => array (
      "URL" => "'/reports/received/gateway/' + encodeURIComponent ( $('#gateway').val ())",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}

/**
 * Add system received calls report page
 */
framework_add_path ( "/reports/received/all", "system_received_report_page");
framework_add_hook ( "system_received_report_page", "system_received_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the system received calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function system_received_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "calls received by all"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  // Generate call report page through hook call:
  return framework_call ( "call_report_page_generate", array (
    "FiltersOverwrite" => true,
    "Filters" => "  <form id=\"filters\">\n" .
                 "  <div class=\"col-xs-12\">\n" .
                 "    <div class=\"col-md-5\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <div class=\"input-group date\">\n" .
                 "          <input name=\"Start\" id=\"start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n" .
                 "          <div class=\"input-group-btn\">\n" .
                 "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n" .
                 "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n" .
                 "          </div>\n" .
                 "        </div>\n" .
                 "      </div>\n" .
                 "    </div>\n" .
                 "    <div class=\"col-md-5\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <div class=\"input-group date\">\n" .
                 "          <input name=\"End\" id=\"end\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n" .
                 "          <div class=\"input-group-btn\">\n" .
                 "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n" .
                 "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n" .
                 "          </div>\n" .
                 "        </div>\n" .
                 "      </div>\n" .
                 "    </div>\n" .
                 "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n" .
                 "  </div>\n" .
                 "  </form>\n",
    "Endpoint" => array (
      "URL" => "'/reports/received/all'",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}

/**
 * Add user made calls report page
 */
framework_add_path ( "/reports/made/user", "user_made_report_page");
framework_add_hook ( "user_made_report_page", "user_made_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the user made calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function user_made_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "calls made by user"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  // Generate call report page through hook call:
  return framework_call ( "call_report_page_generate", array (
    "Filters" => "    <div class=\"col-md-4\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <select name=\"Extension\" id=\"extension\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension") . "\"><option value=\"\"></option></select>\n" .
                 "      </div>\n" .
                 "    </div>\n",
    "JS" => array (
      "Start" => "$('#extension').select2 (\n" .
                 "{\n" .
                 "  allowClear: true,\n" .
                 "  data: VoIP.APIsearch ( { path: '/extensions', Fields: 'ID,Description,Number', formatText: '%Description% (%Number%)'})\n" .
                 "});\n",
      "Focus" => "extension",
      "StartFilters" => "    $('#extension').val ( '').trigger ( 'change.select2');\n",
      "Filters" => "  if ( data && 'Extension' in data)\n" .
                   "  {\n" .
                   "    $('#extension').val ( data.Extension).trigger ( 'change.select2');\n" .
                   "  }\n",
      "CheckFilters" => "  if ( $('#extension').val () == '')\n" .
                        "  {\n" .
                        "    $('#extension').alerts ( 'add', { message: '" . __ ( "The extension is required.") . "'});\n" .
                        "  }\n",
      "CheckIf" => "$('#start').val () == '' || $('#end').val () == '' || $('#extension').val () == ''"
    ),
    "DataFilter" => "      data[x].ID = $('#extension').val ();\n",
    "HashForm" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : '', Extension: $('#extension').val ()}",
    "Endpoint" => array (
      "URL" => "'/reports/made/user/' + encodeURIComponent ( $('#extension').val ())",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}

/**
 * Add group made calls report page
 */
framework_add_path ( "/reports/made/group", "group_made_report_page");
framework_add_hook ( "group_made_report_page", "group_made_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the group made calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function group_made_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "calls made by group"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  // Generate call report page through hook call:
  return framework_call ( "call_report_page_generate", array (
    "Filters" => "    <div class=\"col-md-4\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <select name=\"Group\" id=\"group\" class=\"form-control\" data-placeholder=\"" . __ ( "Group") . "\"><option value=\"\"></option></select>\n" .
                 "      </div>\n" .
                 "    </div>\n",
    "JS" => array (
      "Start" => "$('#group').select2 (\n" .
                 "{\n" .
                 "  allowClear: true,\n" .
                 "  data: VoIP.APIsearch ( { path: '/groups', Fields: 'ID,Description', formatText: '%Description%'})\n" .
                 "});\n",
      "Focus" => "group",
      "StartFilters" => "    $('#group').val ( '').trigger ( 'change.select2');\n",
      "Filters" => "  if ( data && 'Group' in data)\n" .
                   "  {\n" .
                   "    $('#group').val ( data.Group).trigger ( 'change.select2');\n" .
                   "  }\n",
      "CheckFilters" => "  if ( $('#group').val () == '')\n" .
                        "  {\n" .
                        "    $('#group').alerts ( 'add', { message: '" . __ ( "The group is required.") . "'});\n" .
                        "  }\n",
      "CheckIf" => "$('#start').val () == '' || $('#end').val () == '' || $('#group').val () == ''"
    ),
    "DataFilter" => "      data[x].ID = $('#group').val ();\n",
    "HashForm" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : '', Group: $('#group').val ()}",
    "Endpoint" => array (
      "URL" => "'/reports/made/group/' + encodeURIComponent ( $('#group').val ())",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}

/**
 * Add gateway made calls report page
 */
framework_add_path ( "/reports/made/gateway", "gateway_made_report_page");
framework_add_hook ( "gateway_made_report_page", "gateway_made_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the gateway made calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function gateway_made_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "calls made by gateway"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  // Generate call report page through hook call:
  return framework_call ( "call_report_page_generate", array (
    "Filters" => "    <div class=\"col-md-4\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <select name=\"Gateway\" id=\"gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\"><option value=\"\"></option></select>\n" .
                 "      </div>\n" .
                 "    </div>\n",
    "JS" => array (
      "Start" => "$('#gateway').select2 (\n" .
                 "{\n" .
                 "  allowClear: true,\n" .
                 "  data: VoIP.APIsearch ( { path: '/gateways', Fields: 'ID,Description', formatText: '%Description%'})\n" .
                 "});\n",
      "Focus" => "gateway",
      "StartFilters" => "    $('#gateway').val ( '').trigger ( 'change.select2');\n",
      "Filters" => "  if ( data && 'Gateway' in data)\n" .
                   "  {\n" .
                   "    $('#gateway').val ( data.Gateway).trigger ( 'change.select2');\n" .
                   "  }\n",
      "CheckFilters" => "  if ( $('#gateway').val () == '')\n" .
                        "  {\n" .
                        "    $('#gateway').alerts ( 'add', { message: '" . __ ( "The gateway is required.") . "'});\n" .
                        "  }\n",
      "CheckIf" => "$('#start').val () == '' || $('#end').val () == '' || $('#gateway').val () == ''"
    ),
    "DataFilter" => "      data[x].ID = $('#gateway').val ();\n",
    "HashForm" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : '', Gateway: $('#gateway').val ()}",
    "Endpoint" => array (
      "URL" => "'/reports/made/gateway/' + encodeURIComponent ( $('#gateway').val ())",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}

/**
 * Add system made calls report page
 */
framework_add_path ( "/reports/made/all", "system_made_report_page");
framework_add_hook ( "system_made_report_page", "system_made_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the system made calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function system_made_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "calls made by all"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  // Generate call report page through hook call:
  return framework_call ( "call_report_page_generate", array (
    "FiltersOverwrite" => true,
    "Filters" => "  <form id=\"filters\">\n" .
                 "  <div class=\"col-xs-12\">\n" .
                 "    <div class=\"col-md-5\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <div class=\"input-group date\">\n" .
                 "          <input name=\"Start\" id=\"start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n" .
                 "          <div class=\"input-group-btn\">\n" .
                 "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n" .
                 "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n" .
                 "          </div>\n" .
                 "        </div>\n" .
                 "      </div>\n" .
                 "    </div>\n" .
                 "    <div class=\"col-md-5\">\n" .
                 "      <div class=\"form-group\">\n" .
                 "        <div class=\"input-group date\">\n" .
                 "          <input name=\"End\" id=\"end\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n" .
                 "          <div class=\"input-group-btn\">\n" .
                 "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n" .
                 "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n" .
                 "          </div>\n" .
                 "        </div>\n" .
                 "      </div>\n" .
                 "    </div>\n" .
                 "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n" .
                 "  </div>\n" .
                 "  </form>\n",
    "Endpoint" => array (
      "URL" => "'/reports/made/all'",
      "Method" => "GET",
      "Parameters" => "{ Start: moment ( $('#start').val (), 'L LTS').isValid () ? moment ( $('#start').val (), 'L LTS').utc ().format () : '', End: moment ( $('#end').val (), 'L LTS').isValid () ? moment ( $('#end').val (), 'L LTS').utc ().format () : ''}"
    )
  ));
}

/**
 * Add consolidated extension calls report page
 */
framework_add_path ( "/reports/consolidated/user", "consolidated_extensions_report_page");
framework_add_hook ( "consolidated_extensions_report_page", "consolidated_extensions_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the consolidated extensions calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function consolidated_extensions_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "consolidated per extension"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"Month\" id=\"month\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Month/Year") . "\" maxlength=\"7\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th rowspan=\"2\" class=\"dt-vmiddle\" width=\"5%\">" . __ ( "Extension") . "</th>\n";
  $output .= "      <th rowspan=\"2\" class=\"dt-vmiddle\" width=\"15%\">" . __ ( "Name") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Local") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Interstate") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "International") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Others") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "    <tr>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"14\">" . __ ( "All durations are billing time in [[DD:]HH:]MM:SS format.") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#month').mask ( '00/0000');\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Number', width: '5%', class: 'export tablel-l'},\n" .
              "             { data: 'Description', width: '15%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/extensions/' + row.ID + '/view\">' + row.Description + '</a>'; } else { return row.Description; }}},\n" .
              "             { data: 'Local.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Local.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Local.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Local.Cost; }}},\n" .
              "             { data: 'Local.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Local.Time); } else { return row.Local.Time; }}},\n" .
              "             { data: 'Interstate.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Interstate.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Interstate.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Interstate.Cost; }}},\n" .
              "             { data: 'Interstate.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Interstate.Time); } else { return row.Interstate.Time; }}},\n" .
              "             { data: 'International.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'International.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.International.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.International.Cost; }}},\n" .
              "             { data: 'International.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.International.Time); } else { return row.International.Time; }}},\n" .
              "             { data: 'Others.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Others.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Others.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Others.Cost; }}},\n" .
              "             { data: 'Others.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Others.Time); } else { return row.Others.Time; }}}\n" .
              "           ],\n" .
              "  footerCallback: function ( row, data, start, end, display)\n" .
              "  {\n" .
              "    let api = this.api ();\n" .
              "    let localcalls = 0;\n" .
              "    let localcost = 0;\n" .
              "    let localtime = 0;\n" .
              "    let interstatecalls = 0;\n" .
              "    let interstatecost = 0;\n" .
              "    let interstatetime = 0;\n" .
              "    let internationalcalls = 0;\n" .
              "    let internationalcost = 0;\n" .
              "    let internationaltime = 0;\n" .
              "    let otherscalls = 0;\n" .
              "    let otherscost = 0;\n" .
              "    let otherstime = 0;\n" .
              "    data.forEach ( function ( currentRow)\n" .
              "    {\n" .
              "      localcalls += currentRow.Local.Total;\n" .
              "      localcost += currentRow.Local.Cost;\n" .
              "      localtime += currentRow.Local.Time;\n" .
              "      interstatecalls += currentRow.Interstate.Total;\n" .
              "      interstatecost += currentRow.Interstate.Cost;\n" .
              "      interstatetime += currentRow.Interstate.Time;\n" .
              "      internationalcalls += currentRow.International.Total;\n" .
              "      internationalcost += currentRow.International.Cost;\n" .
              "      internationaltime += currentRow.International.Time;\n" .
              "      otherscalls += currentRow.Others.Total;\n" .
              "      otherscost += currentRow.Others.Cost;\n" .
              "      otherstime += currentRow.Others.Time;\n" .
              "    });\n" .
              "    $(api.column ( 2).footer()).html ( localcalls);\n" .
              "    $(api.column ( 3).footer()).html ( parseFloat ( localcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 4).footer()).html ( format_secs_to_string ( localtime));\n" .
              "    $(api.column ( 5).footer()).html ( interstatecalls);\n" .
              "    $(api.column ( 6).footer()).html ( parseFloat ( interstatecost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 7).footer()).html ( format_secs_to_string ( interstatetime));\n" .
              "    $(api.column ( 8).footer()).html ( internationalcalls);\n" .
              "    $(api.column ( 9).footer()).html ( parseFloat ( internationalcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 10).footer()).html ( format_secs_to_string ( internationaltime));\n" .
              "    $(api.column ( 11).footer()).html ( otherscalls);\n" .
              "    $(api.column ( 12).footer()).html ( parseFloat ( otherscost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 13).footer()).html ( format_secs_to_string ( otherstime));\n" .
              "  },\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#month').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/YYYY") . "', viewMode: 'months'});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#month').val ( moment ().format ( '" . __ ( "MM/YYYY") . "'));\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#report').data ( 'dt').clear ().draw ().responsive.recalc ();\n" .
              "    $('#month').val ( '').focus ();\n" .
              "    return;\n" .
              "  }\n" .
              "  if ( data && 'Month' in data)\n" .
              "  {\n" .
              "    $('#month').val ( data.Month);\n" .
              "  }\n" .
              "  if ( $('#month').val () == '')\n" .
              "  {\n" .
              "    $('#month').alerts ( 'add', { message: '" . __ ( "The month/year is required.") . "'});\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { data: { Month: $('#month').val ()}});\n" .
              "  VoIP.dataTablesUpdate ( { path: '/reports/consolidated/extensions', fields: 'ID,Number,Description,Local,Interstate,International,Others', data: { Month: $('#month').val ()}}, $('#report').data ( 'dt'));\n" .
              "});\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  return $output;
}

/**
 * Add consolidated group calls report page
 */
framework_add_path ( "/reports/consolidated/group", "consolidated_groups_report_page");
framework_add_hook ( "consolidated_groups_report_page", "consolidated_groups_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the consolidated groups calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function consolidated_groups_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "consolidated per group"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"month\" id=\"month\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Month/Year") . "\" maxlength=\"7\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th rowspan=\"2\" class=\"dt-vmiddle\" width=\"20%\">" . __ ( "Group") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Local") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Interstate") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "International") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Others") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "    <tr>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th>" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"14\">" . __ ( "All durations are billing time in [[DD:]HH:]MM:SS format.") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#month').mask ( '00/0000');\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Description', width: '20%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/groups/' + row.ID + '/view\">' + row.Description + '</a>'; } else { return row.Description; }}},\n" .
              "             { data: 'Local.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Local.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Local.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Local.Cost; }}},\n" .
              "             { data: 'Local.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Local.Time); } else { return row.Local.Time; }}},\n" .
              "             { data: 'Interstate.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Interstate.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Interstate.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Interstate.Cost; }}},\n" .
              "             { data: 'Interstate.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Interstate.Time); } else { return row.Interstate.Time; }}},\n" .
              "             { data: 'International.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'International.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.International.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.International.Cost; }}},\n" .
              "             { data: 'International.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.International.Time); } else { return row.International.Time; }}},\n" .
              "             { data: 'Others.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Others.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Others.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Others.Cost; }}},\n" .
              "             { data: 'Others.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Others.Time); } else { return row.Others.Time; }}}\n" .
              "           ],\n" .
              "  footerCallback: function ( row, data, start, end, display)\n" .
              "  {\n" .
              "    let api = this.api ();\n" .
              "    let localcalls = 0;\n" .
              "    let localcost = 0;\n" .
              "    let localtime = 0;\n" .
              "    let interstatecalls = 0;\n" .
              "    let interstatecost = 0;\n" .
              "    let interstatetime = 0;\n" .
              "    let internationalcalls = 0;\n" .
              "    let internationalcost = 0;\n" .
              "    let internationaltime = 0;\n" .
              "    let otherscalls = 0;\n" .
              "    let otherscost = 0;\n" .
              "    let otherstime = 0;\n" .
              "    data.forEach ( function ( currentRow)\n" .
              "    {\n" .
              "      localcalls += currentRow.Local.Total;\n" .
              "      localcost += currentRow.Local.Cost;\n" .
              "      localtime += currentRow.Local.Time;\n" .
              "      interstatecalls += currentRow.Interstate.Total;\n" .
              "      interstatecost += currentRow.Interstate.Cost;\n" .
              "      interstatetime += currentRow.Interstate.Time;\n" .
              "      internationalcalls += currentRow.International.Total;\n" .
              "      internationalcost += currentRow.International.Cost;\n" .
              "      internationaltime += currentRow.International.Time;\n" .
              "      otherscalls += currentRow.Others.Total;\n" .
              "      otherscost += currentRow.Others.Cost;\n" .
              "      otherstime += currentRow.Others.Time;\n" .
              "    });\n" .
              "    $(api.column ( 1).footer()).html ( localcalls);\n" .
              "    $(api.column ( 2).footer()).html ( parseFloat ( localcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 3).footer()).html ( format_secs_to_string ( localtime));\n" .
              "    $(api.column ( 4).footer()).html ( interstatecalls);\n" .
              "    $(api.column ( 5).footer()).html ( parseFloat ( interstatecost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 6).footer()).html ( format_secs_to_string ( interstatetime));\n" .
              "    $(api.column ( 7).footer()).html ( internationalcalls);\n" .
              "    $(api.column ( 8).footer()).html ( parseFloat ( internationalcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 9).footer()).html ( format_secs_to_string ( internationaltime));\n" .
              "    $(api.column ( 10).footer()).html ( otherscalls);\n" .
              "    $(api.column ( 11).footer()).html ( parseFloat ( otherscost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 12).footer()).html ( format_secs_to_string ( otherstime));\n" .
              "  },\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#month').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/YYYY") . "', viewMode: 'months'});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#month').val ( moment ().format ( '" . __ ( "MM/YYYY") . "'));\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#report').data ( 'dt').clear ().draw ().responsive.recalc ();\n" .
              "    $('#month').val ( '').focus ();\n" .
              "    return;\n" .
              "  }\n" .
              "  if ( data && 'Month' in data)\n" .
              "  {\n" .
              "    $('#month').val ( data.Month);\n" .
              "  }\n" .
              "  if ( $('#month').val () == '')\n" .
              "  {\n" .
              "    $('#month').alerts ( 'add', { message: '" . __ ( "The month/year is required.") . "'});\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { data: { Month: $('#month').val ()}});\n" .
              "  VoIP.dataTablesUpdate ( { path: '/reports/consolidated/groups', fields: 'ID,Description,Local,Interstate,International,Others', data: { Month: $('#month').val ()}}, $('#report').data ( 'dt'));\n" .
              "});\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  return $output;
}

/**
 * Add consolidated gateway calls report page
 */
framework_add_path ( "/reports/consolidated/gateway", "consolidated_gateways_report_page");
framework_add_hook ( "consolidated_gateways_report_page", "consolidated_gateways_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the consolidated gateways calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function consolidated_gateways_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "consolidated per gateway"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"month\" id=\"month\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Month/Year") . "\" maxlength=\"7\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th rowspan=\"2\" class=\"dt-vmiddle\" width=\"20%\">" . __ ( "Gateway") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Local") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Interstate") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "International") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Others") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "    <tr>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th>" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"14\">" . __ ( "All durations are billing time in [[DD:]HH:]MM:SS format.") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#month').mask ( '00/0000');\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Description', width: '20%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/gateways/' + row.ID + '/view\">' + row.Description + '</a>'; } else { return row.Description; }}},\n" .
              "             { data: 'Local.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Local.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Local.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Local.Cost; }}},\n" .
              "             { data: 'Local.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Local.Time); } else { return row.Local.Time; }}},\n" .
              "             { data: 'Interstate.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Interstate.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Interstate.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Interstate.Cost; }}},\n" .
              "             { data: 'Interstate.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Interstate.Time); } else { return row.Interstate.Time; }}},\n" .
              "             { data: 'International.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'International.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.International.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.International.Cost; }}},\n" .
              "             { data: 'International.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.International.Time); } else { return row.International.Time; }}},\n" .
              "             { data: 'Others.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Others.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Others.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Others.Cost; }}},\n" .
              "             { data: 'Others.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Others.Time); } else { return row.Others.Time; }}}\n" .
              "           ],\n" .
              "  footerCallback: function ( row, data, start, end, display)\n" .
              "  {\n" .
              "    let api = this.api ();\n" .
              "    let localcalls = 0;\n" .
              "    let localcost = 0;\n" .
              "    let localtime = 0;\n" .
              "    let interstatecalls = 0;\n" .
              "    let interstatecost = 0;\n" .
              "    let interstatetime = 0;\n" .
              "    let internationalcalls = 0;\n" .
              "    let internationalcost = 0;\n" .
              "    let internationaltime = 0;\n" .
              "    let otherscalls = 0;\n" .
              "    let otherscost = 0;\n" .
              "    let otherstime = 0;\n" .
              "    data.forEach ( function ( currentRow)\n" .
              "    {\n" .
              "      localcalls += currentRow.Local.Total;\n" .
              "      localcost += currentRow.Local.Cost;\n" .
              "      localtime += currentRow.Local.Time;\n" .
              "      interstatecalls += currentRow.Interstate.Total;\n" .
              "      interstatecost += currentRow.Interstate.Cost;\n" .
              "      interstatetime += currentRow.Interstate.Time;\n" .
              "      internationalcalls += currentRow.International.Total;\n" .
              "      internationalcost += currentRow.International.Cost;\n" .
              "      internationaltime += currentRow.International.Time;\n" .
              "      otherscalls += currentRow.Others.Total;\n" .
              "      otherscost += currentRow.Others.Cost;\n" .
              "      otherstime += currentRow.Others.Time;\n" .
              "    });\n" .
              "    $(api.column ( 1).footer()).html ( localcalls);\n" .
              "    $(api.column ( 2).footer()).html ( parseFloat ( localcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 3).footer()).html ( format_secs_to_string ( localtime));\n" .
              "    $(api.column ( 4).footer()).html ( interstatecalls);\n" .
              "    $(api.column ( 5).footer()).html ( parseFloat ( interstatecost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 6).footer()).html ( format_secs_to_string ( interstatetime));\n" .
              "    $(api.column ( 7).footer()).html ( internationalcalls);\n" .
              "    $(api.column ( 8).footer()).html ( parseFloat ( internationalcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 9).footer()).html ( format_secs_to_string ( internationaltime));\n" .
              "    $(api.column ( 10).footer()).html ( otherscalls);\n" .
              "    $(api.column ( 11).footer()).html ( parseFloat ( otherscost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 12).footer()).html ( format_secs_to_string ( otherstime));\n" .
              "  },\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#month').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/YYYY") . "', viewMode: 'months'});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#month').val ( moment ().format ( '" . __ ( "MM/YYYY") . "'));\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#report').data ( 'dt').clear ().draw ().responsive.recalc ();\n" .
              "    $('#month').val ( '').focus ();\n" .
              "    return;\n" .
              "  }\n" .
              "  if ( data && 'Month' in data)\n" .
              "  {\n" .
              "    $('#month').val ( data.Month);\n" .
              "  }\n" .
              "  if ( $('#month').val () == '')\n" .
              "  {\n" .
              "    $('#month').alerts ( 'add', { message: '" . __ ( "The month/year is required.") . "'});\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { data: { Month: $('#month').val ()}});\n" .
              "  VoIP.dataTablesUpdate ( { path: '/reports/consolidated/gateways', fields: 'ID,Description,Local,Interstate,International,Others', data: { Month: $('#month').val ()}}, $('#report').data ( 'dt'));\n" .
              "});\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  return $output;
}

/**
 * Add consolidated server calls report page
 */
framework_add_path ( "/reports/consolidated/server", "consolidated_servers_report_page");
framework_add_hook ( "consolidated_servers_report_page", "consolidated_servers_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the consolidated servers calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function consolidated_servers_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "consolidated per server"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Reports"))
  ));

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "jquery-dataTables", "src" => "/vendors/datatables/media/js/jquery.dataTables.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/js/dataTables.bootstrap.js", "dep" => array ( "bootstrap", "jquery-dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/js/dataTables.buttons.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-buttons-html5", "src" => "/vendors/buttons/js/buttons.html5.js", "dep" => array ( "dataTables-buttons", "jszip", "pdfmake", "vfs_fonts")));
  sys_addjs ( array ( "name" => "dataTables-buttons-print", "src" => "/vendors/buttons/js/buttons.print.js", "dep" => array ( "dataTables-buttons")));
  sys_addjs ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/js/dataTables.responsive.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "dataTables-bootstrap-responsive", "src" => "/vendors/responsive/js/responsive.bootstrap.js", "dep" => array ( "dataTables-responsive")));
  sys_addjs ( array ( "name" => "jszip", "src" => "/vendors/jszip/dist/jszip.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "vfs_fonts", "src" => "/vendors/pdfmake/build/vfs_fonts.js", "dep" => array ( "pdfmake")));
  sys_addjs ( array ( "name" => "pdfmake", "src" => "/vendors/pdfmake/build/pdfmake.js", "dep" => array ( "jszip")));
  sys_addjs ( array ( "name" => "tempus-dominus", "src" => "/vendors/tempus-dominus/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "dataTables-mark", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"month\" id=\"month\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Month/Year") . "\" maxlength=\"7\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><i class=\"fa fa-search\"></i></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th rowspan=\"2\" class=\"dt-vmiddle\" width=\"20%\">" . __ ( "Server") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Local") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Interstate") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "International") . "</th>\n";
  $output .= "      <th colspan=\"3\" class=\"center\" width=\"20%\">" . __ ( "Others") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "    <tr>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Calls") . "</th>\n";
  $output .= "      <th width=\"5%\">" . __ ( "Cost") . "</th>\n";
  $output .= "      <th width=\"10%\">" . __ ( "Duration") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th>" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"14\">" . __ ( "All durations are billing time in [[DD:]HH:]MM:SS format.") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#month').mask ( '00/0000');\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Description', width: '20%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return '<a href=\"/servers/' + row.ID + '/view\">' + row.Description + '</a>'; } else { return row.Description; }}},\n" .
              "             { data: 'Local.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Local.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Local.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Local.Cost; }}},\n" .
              "             { data: 'Local.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Local.Time); } else { return row.Local.Time; }}},\n" .
              "             { data: 'Interstate.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Interstate.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Interstate.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Interstate.Cost; }}},\n" .
              "             { data: 'Interstate.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Interstate.Time); } else { return row.Interstate.Time; }}},\n" .
              "             { data: 'International.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'International.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.International.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.International.Cost; }}},\n" .
              "             { data: 'International.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.International.Time); } else { return row.International.Time; }}},\n" .
              "             { data: 'Others.Total', width: '5%', class: 'export all'},\n" .
              "             { data: 'Others.Cost', width: '5%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return parseFloat ( row.Others.Cost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'); } else { return row.Others.Cost; }}},\n" .
              "             { data: 'Others.Time', width: '10%', class: 'export all', render: function ( data, type, row, meta) { if ( type === 'display' || type === 'filter') { return format_secs_to_string ( row.Others.Time); } else { return row.Others.Time; }}}\n" .
              "           ],\n" .
              "  footerCallback: function ( row, data, start, end, display)\n" .
              "  {\n" .
              "    let api = this.api ();\n" .
              "    let localcalls = 0;\n" .
              "    let localcost = 0;\n" .
              "    let localtime = 0;\n" .
              "    let interstatecalls = 0;\n" .
              "    let interstatecost = 0;\n" .
              "    let interstatetime = 0;\n" .
              "    let internationalcalls = 0;\n" .
              "    let internationalcost = 0;\n" .
              "    let internationaltime = 0;\n" .
              "    let otherscalls = 0;\n" .
              "    let otherscost = 0;\n" .
              "    let otherstime = 0;\n" .
              "    data.forEach ( function ( currentRow)\n" .
              "    {\n" .
              "      localcalls += currentRow.Local.Total;\n" .
              "      localcost += currentRow.Local.Cost;\n" .
              "      localtime += currentRow.Local.Time;\n" .
              "      interstatecalls += currentRow.Interstate.Total;\n" .
              "      interstatecost += currentRow.Interstate.Cost;\n" .
              "      interstatetime += currentRow.Interstate.Time;\n" .
              "      internationalcalls += currentRow.International.Total;\n" .
              "      internationalcost += currentRow.International.Cost;\n" .
              "      internationaltime += currentRow.International.Time;\n" .
              "      otherscalls += currentRow.Others.Total;\n" .
              "      otherscost += currentRow.Others.Cost;\n" .
              "      otherstime += currentRow.Others.Time;\n" .
              "    });\n" .
              "    $(api.column ( 1).footer()).html ( localcalls);\n" .
              "    $(api.column ( 2).footer()).html ( parseFloat ( localcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 3).footer()).html ( format_secs_to_string ( localtime));\n" .
              "    $(api.column ( 4).footer()).html ( interstatecalls);\n" .
              "    $(api.column ( 5).footer()).html ( parseFloat ( interstatecost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 6).footer()).html ( format_secs_to_string ( interstatetime));\n" .
              "    $(api.column ( 7).footer()).html ( internationalcalls);\n" .
              "    $(api.column ( 8).footer()).html ( parseFloat ( internationalcost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 9).footer()).html ( format_secs_to_string ( internationaltime));\n" .
              "    $(api.column ( 10).footer()).html ( otherscalls);\n" .
              "    $(api.column ( 11).footer()).html ( parseFloat ( otherscost).toFixed ( 2).replace ( '" . __ ( ",") . "', '" . __ ( ".") . "'));\n" .
              "    $(api.column ( 12).footer()).html ( format_secs_to_string ( otherstime));\n" .
              "  },\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#month').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false, format: '" . __ ( "MM/YYYY") . "', viewMode: 'months'});\n" .
              "$('button.btn-calendar').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').datetimepicker ( 'show');\n" .
              "});\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#month').val ( moment ().format ( '" . __ ( "MM/YYYY") . "'));\n" .
              "$('#filters').on ( 'submit', function ( event, data)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( data && 'Startup' in data)\n" .
              "  {\n" .
              "    $('#report').data ( 'dt').clear ().draw ().responsive.recalc ();\n" .
              "    $('#month').val ( '').focus ();\n" .
              "    return;\n" .
              "  }\n" .
              "  if ( data && 'Month' in data)\n" .
              "  {\n" .
              "    $('#month').val ( data.Month);\n" .
              "  }\n" .
              "  if ( $('#month').val () == '')\n" .
              "  {\n" .
              "    $('#month').alerts ( 'add', { message: '" . __ ( "The month/year is required.") . "'});\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { data: { Month: $('#month').val ()}});\n" .
              "  VoIP.dataTablesUpdate ( { path: '/reports/consolidated/servers', fields: 'ID,Description,Local,Interstate,International,Others', data: { Month: $('#month').val ()}}, $('#report').data ( 'dt'));\n" .
              "});\n");

  /**
   * Set page hash event
   */
  sys_set_hashevent ( "filters", "submit");

  /**
   * Set page start event
   */
  sys_set_startevent ( "filters", "submit", array ( "Startup" => true));

  return $output;
}
?>
