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
 * VoIP Domain reports module. This module add the feature of key reports
 * that rings in one or many reports at same time when called. This is usefull
 * to service numbers inside a company, where many employees can answer the call.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Reports
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
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
  sys_addcss ( array ( "name" => "bootstrap-datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "pnotify", "src" => "/vendors/pnotify/dist/pnotify.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "d3", "src" => "/vendors/d3/d3.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "bootstrap-datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment")));
  sys_addjs ( array ( "name" => "pnotify", "src" => "/vendors/pnotify/dist/pnotify.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "system-pnotify", "src" => "/js/pnotify.js", "dep" => array ( "pnotify")));

  /**
   * Report main div
   */
  $output = "<div class=\"row\">\n";
  $output .= "  <span class=\"col-md-5 center\">\n";
  $output .= "    <select name=\"type\" id=\"type\" class=\"form-control\" data-placeholder=\"" . __ ( "Call type") . "\">\n";
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
  $output .= "<div id=\"graph\" class=\"table-center\"></div>\n";

  /**
   * Add heat map graphic JavaScript code
   */
  sys_addjs ( "$('#weekinput').data ( 'start', moment ().day ( 0).format ( '" . __ ( "MM/DD/YYYY") . "'));\n" .
              "$('#weekinput').data ( 'end', moment ().day ( 6).format ( '" . __ ( "MM/DD/YYYY") . "'));\n" .
              "$('#type').on ( 'change', function ()\n" .
              "                           {\n" .
              "                             $('#graph').trigger ( 'update');\n" .
              "                           }).select2 ( { allowClear: false });\n" .
              "$('#week span').html ( $('#weekinput').data ( 'start') + ' - ' + $('#weekinput').data ( 'end'));\n" .
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
              "  $('#weekinput').data ( 'start', moment ( value, '" . __ ( "MM/DD/YYYY") . "').day ( 0).format ( '" . __ ( "MM/DD/YYYY") . "'));\n" .
              "  $('#weekinput').data ( 'end', moment ( value, '" . __ ( "MM/DD/YYYY") . "').day ( 6).format ( '" . __ ( "MM/DD/YYYY") . "'));\n" .
              "  $('#weekinput').val ( $('#weekinput').data ( 'start'));\n" .
              "  $('#week span').html ( $('#weekinput').data ( 'start') + ' - ' + $('#weekinput').data ( 'end'));\n" .
              "  $('#graph').trigger ( 'update');\n" .
              "}).on ( 'dp.show', function ( e)\n" .
              "{\n" .
              "  $(this).find ( 'td.active').parent ( 'tr').find ( 'td').each ( function () { $(this).addClass ( 'active');});\n" .
              "});\n" .
              "( function ()\n" .
              "{\n" .
              "  var margin = { top: 50, right: 0, bottom: 100, left: 30};\n" .
              "  var width = 960 - margin.left - margin.right;\n" .
              "  var height = 430 - margin.top - margin.bottom;\n" .
              "  var gridSize = Math.floor ( width / 24);\n" .
              "  var legendElementWidth = gridSize * 2;\n" .
              "  var buckets = 9;\n" .
              "  var colors = [ '#ffffd9', '#edf8b1', '#c7e9b4', '#7fcdbb', '#41b6c4', '#1d91c0', '#225ea8', '#253494', '#081d58'];\n" .
              "  var days = [ '" . __ ( "Su") . "', '" . __ ( "Mo") . "', '" . __ ( "Tu") . "', '" . __ ( "We") . "', '" . __ ( "Th") . "', '" . __ ( "Fr") . "', '" . __ ( "Sa") . "'];\n" .
              "  var times = [ '00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];\n" .
              "  var svg = d3.select ( '#graph').append ( 'svg').attr ( 'width', width + margin.left + margin.right).attr ( 'height', height + margin.top + margin.bottom).append ( 'g').attr ( 'transform', 'translate(' + margin.left + ',' + margin.top + ')');\n" .
              "  var dayLabels = svg.selectAll ( '.dayLabel').data ( days).enter ().append ( 'text').text ( function ( d) { return d;}).attr ( 'x', 0).attr ( 'y', function ( d, i) { return i * gridSize;}).style ( 'text-anchor', 'end').attr ( 'transform', 'translate(-6,' + gridSize / 1.5 + ')').attr ( 'class', function ( d, i) { return (( i >= 0 && i <= 4) ? 'dayLabel mono axis axis-workweek' : 'dayLabel mono axis');});\n" .
              "  var timeLabels = svg.selectAll ( '.timeLabel').data ( times).enter ().append ( 'text').text ( function ( d) { return d;}).attr ( 'x', function ( d, i) { return i * gridSize;}).attr ( 'y', 0).style ( 'text-anchor', 'middle').attr ( 'transform', 'translate(' + gridSize / 2 + ', -6)').attr ( 'class', function ( d, i) { return (( i >= 7 && i <= 16) ? 'timeLabel mono axis axis-worktime' : 'timeLabel mono axis');});\n" .
              "  $('#graph').on ( 'update', function ()\n" .
              "  {\n" .
              "    var data = VoIP.rest ( '/reports/heat', 'GET', { type: $('#type').val (), start: $('#weekinput').data ( 'start'), end: $('#weekinput').data ( 'end')});\n" .
              "    if ( data.API.status != 'ok')\n" .
              "    {\n" .
              "      new PNotify ( { title: '" . __ ( "Heat map graph") . "', text: '" . __ ( "Error requesting informations!") . "', type: 'error'});\n" .
              "      return false;\n" .
              "    }\n" .
              "    var colorScale = d3.scaleQuantile ().domain ( [ 0, buckets - 1, d3.max ( data.result, function ( d) { return d.value;})]).range ( colors);\n" .
              "    var cards = svg.selectAll ( '.hour').data ( data.result, function ( d) { return d.day + ':' + d.hour;});\n" .
              "    cards.append ( 'title');\n" .
              "    cards.enter ().append ( 'rect').attr ( 'x', function ( d) { return d.hour * gridSize;}).attr ( 'y', function ( d) { return ( d.day - 1) * gridSize;}).attr ( 'rx', 4).attr ( 'ry', 4).attr ( 'class', 'hour bordered').attr ( 'width', gridSize).attr ( 'height', gridSize).style ( 'fill', colors[0]);\n" .
              "    cards.transition ().duration ( 1000).style ( 'fill', function ( d) { return colorScale ( d.value);});\n" .
              "    cards.select ( 'title').text ( function ( d) { return d.value;});\n" .
              "    cards.exit ().remove ();\n" .
              "    var legend = svg.selectAll ( '.legend').data ( [0].concat ( colorScale.quantiles ()), function ( d) { return d;});\n" .
              "    legend.enter ().append ( 'g').attr ( 'class', 'legend');\n" .
              "    legend.append ( 'rect').attr ( 'x', function ( d, i) { return legendElementWidth * i;}).attr ( 'y', height).attr ( 'width', legendElementWidth).attr ( 'height', gridSize / 2).style ( 'fill', function ( d, i) { return colors[i];});\n" .
              "    legend.append ( 'text').attr ( 'class', 'mono').text ( function ( d) { return '≥ ' + Math.round ( d);}).attr ( 'x', function ( d, i) { return legendElementWidth * i;}).attr ( 'y', height + gridSize);\n" .
              "    legend.exit ().remove ();\n" .
              "  });\n" .
              "} ());\n" .
              "$('#graph').trigger ( 'update').trigger ( 'update');\n");

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
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group\">\n";
  $output .= "          <input name=\"name\" id=\"name\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Name") . "\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group\">\n";
  $output .= "          <input name=\"group\" id=\"group\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Group") . "\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group\">\n";
  $output .= "          <input name=\"server\" id=\"server\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Server") . "\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Extension") . "</th>\n";
  $output .= "      <th>" . __ ( "Name") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Group") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Server") . "</th>\n";
  $output .= "      <th>" . __ ( "Permissions") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    var name = data.result[x][2];\n" .
              "    data.result[x][2] = '<a href=\"/extensions/' + data.result[x][0] + '/view\">' + data.result[x][2] + '</a>';\n" .
              "    var group = data.result[x][4];\n" .
              "    data.result[x][4] = '<a href=\"/groups/' + data.result[x][3] + '/view\">' + data.result[x][4] + '</a>';\n" .
              "    var server = data.result[x][6];\n" .
              "    data.result[x][6] = '<a href=\"/servers/' + data.result[x][5] + '/view\">' + data.result[x][6] + '</a>';\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][7].mobile == true)\n" .
              "    {\n" .
              "      tmp += '<i class=\"fa fa-mobile-alt\" title=\"" . __ ( "Mobile") . "\"></i>';\n" .
              "    }\n" .
              "    if ( data.result[x][7].international == true)\n" .
              "    {\n" .
              "      tmp += '<i class=\"fa fa-globe\" title=\"" . __ ( "International") . "\"></i>';\n" .
              "    }\n" .
              "    if ( data.result[x][7].longdistance == true)\n" .
              "    {\n" .
              "      tmp += '<i class=\"fa fa-image\" title=\"" . __ ( "Interstate") . "\"></i>';\n" .
              "    }\n" .
              "    if ( data.result[x][7].nopass == true)\n" .
              "    {\n" .
              "      tmp += '<i class=\"fa fa-unlock-alt\" title=\"" . __ ( "No password") . "\"></i>';\n" .
              "    }\n" .
              "    if ( data.result[x][7].voicemail == true)\n" .
              "    {\n" .
              "      tmp += '<i class=\"fa fa-envelope\" title=\"" . __ ( "Voice mail") . "\"></i>';\n" .
              "    }\n" .
              "    data.result[x][7] = tmp;\n" .
              "    var tr = table.row.add ( data.result[x]).node ();\n" .
              "    $(tr).find ( 'td').eq ( 1).attr ( 'data-sort', name);\n" .
              "    $(tr).find ( 'td').eq ( 2).attr ( 'data-sort', group);\n" .
              "    $(tr).find ( 'td').eq ( 3).attr ( 'data-sort', server);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  responsive: true,\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 3, 5, 7 ]},\n" .
              "                { searchable: false, targets: [ 0, 2, 3, 5, 7 ]},\n" .
              "                { visible: false, targets: [ 0, 3, 5 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '10%', class: 'export all'},\n" .
              "             { width: '40%', class: 'export min-tablet-l'},\n" .
              "             { class: 'never'},\n" .
              "             { width: '20%', class: 'export min-mobile-l'},\n" .
              "             { class: 'never'},\n" .
              "             { width: '20%', class: 'export all'},\n" .
              "             { width: '10%'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('button.btn-clean').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $(this).parent ().parent ().find ( 'input').val ( '');\n" .
              "});\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/list', 'GET', $(this).serializeObject ()));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfinish: function () { $('#filters').trigger ( 'submit'); }});\n");

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
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"range\" id=\"range\" class=\"form-control\" data-placeholder=\"" . __ ( "Range") . "\"><option value=\"\"></option></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th>" . __ ( "Extension") . "</th>\n";
  $output .= "      <th>" . __ ( "Name") . "</th>\n";
  $output .= "      <th>" . __ ( "Type") . "</th>\n";
  $output .= "      <th>" . __ ( "Group") . "</th>\n";
  $output .= "      <th>" . __ ( "Server") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#range').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/ranges/search', 'GET')\n" .
              "}).on ( 'select2:select, select2:unselect, change', function ()\n" .
              "{\n" .
              "  $('#filters').trigger ( 'submit');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    table.row.add ( data.result[x]).node ();\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  order: [[ 0, 'asc' ]],\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 4 ]},\n" .
              "                { searchable: false, targets: [ 4 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { width: '10%', class: 'export all'},\n" .
              "             { width: '40%', class: 'export min-tablet-l'},\n" .
              "             { width: '10%', class: 'export all'},\n" .
              "             { width: '20%', class: 'export min-mobile-l'},\n" .
              "             { width: '20%', class: 'export min-mobile-l'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/ranges', 'GET', $(this).serializeObject ()));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n");

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
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
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
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
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
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Extension") . "</th>\n";
  $output .= "      <th>" . __ ( "Name") . "</th>\n";
  $output .= "      <th>" . __ ( "Calls") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Time") . "</th>\n";
  $output .= "      <th>" . __ ( "Cost") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th colspan=\"2\"></th>\n";
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
              "  data: VoIP.select2 ( '/costcenters/search', 'GET')\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  table.clear ();\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  var total_cost = 0;\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls += parseInt ( data.result[x][3]) || 0;\n" .
              "    total_time += parseInt ( data.result[x][4]) || 0;\n" .
              "    total_cost += parseFloat ( data.result[x][6]) || 0;\n" .
              "    data.result[x][2] = '<a href=\"/extensions/' + data.result[x][0] + '/report#start=' + encodeURIComponent ( $('#start').val ()) + '&end=' + encodeURIComponent ( $('#end').val ()) + '&source=&destination=\">' + data.result[x][2] + '</a>';\n" .
              "    data.result[x][6] = parseFloat ( data.result[x][6]).toFixed ( 2).replace ( '.', ',');\n" .
              "    table.row.add ( data.result[x]).node ();\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( total_calls);\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 3).html ( number_format ( total_cost, 2, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 4 ]},\n" .
              "                { searchable: false, targets: [ 0, 4 ]},\n" .
              "                { visible: false, targets: [ 0, 4 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '15%', class: 'export all'},\n" .
              "             { width: '35%', class: 'export min-tablet-l'},\n" .
              "             { width: '15%', class: 'export min-mobile-l'},\n" .
              "             { class: 'never'},\n" .
              "             { width: '20%', class: 'export min-mobile-l'},\n" .
              "             { width: '15%', class: 'export all'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#start').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#end').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "}).val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#start').data ( 'DateTimePicker').maxDate ( e.date);\n" .
              "}).val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
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
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
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
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/financial/costcenter/' + $('#costcenter').val (), 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n");

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
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
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
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
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
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Extension") . "</th>\n";
  $output .= "      <th>" . __ ( "Name") . "</th>\n";
  $output .= "      <th>" . __ ( "Calls") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Time") . "</th>\n";
  $output .= "      <th>" . __ ( "Cost") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th colspan=\"2\"></th>\n";
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
              "  data: VoIP.select2 ( '/groups/search', 'GET')\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  table.clear ();\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  var total_cost = 0;\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls += parseInt ( data.result[x][3]) || 0;\n" .
              "    total_time += parseInt ( data.result[x][4]) || 0;\n" .
              "    total_cost += parseFloat ( data.result[x][6]) || 0;\n" .
              "    data.result[x][2] = '<a href=\"/extensions/' + data.result[x][0] + '/report#start=' + encodeURIComponent ( $('#start').val ()) + '&end=' + encodeURIComponent ( $('#end').val ()) + '&source=&destination=\">' + data.result[x][2] + '</a>';\n" .
              "    data.result[x][6] = parseFloat ( data.result[x][6]).toFixed ( 2).replace ( '.', ',');\n" .
              "    table.row.add ( data.result[x]).node ();\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( total_calls);\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 3).html ( number_format ( total_cost, 2, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 4 ]},\n" .
              "                { searchable: false, targets: [ 0, 4 ]},\n" .
              "                { visible: false, targets: [ 0, 4 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '15%', class: 'export all'},\n" .
              "             { width: '35%', class: 'export min-tablet-l'},\n" .
              "             { width: '15%', class: 'export all'},\n" .
              "             { class: 'never'},\n" .
              "             { width: '20%', class: 'export all'},\n" .
              "             { width: '15%', class: 'export all'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#start').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#end').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "}).val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#start').data ( 'DateTimePicker').maxDate ( e.date);\n" .
              "}).val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
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
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
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
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/financial/group/' + $('#group').val (), 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n");

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
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
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
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" value=\"\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Gateway") . "</th>\n";
  $output .= "      <th>" . __ ( "Calls") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Time") . "</th>\n";
  $output .= "      <th>" . __ ( "Cost") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Totals") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th colspan=\"2\"></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  table.clear ();\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  var total_cost = 0;\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls += parseInt ( data.result[x][2]) || 0;\n" .
              "    total_time += parseInt ( data.result[x][3]) || 0;\n" .
              "    total_cost += parseFloat ( data.result[x][5]) || 0;\n" .
              "    data.result[x][1] = '<a href=\"/gateways/' + data.result[x][0] + '/report#start=' + encodeURIComponent ( $('#start').val ()) + '&end=' + encodeURIComponent ( $('#end').val ()) + '&source=&destination=\">' + data.result[x][1] + '</a>';\n" .
              "    data.result[x][5] = parseFloat ( data.result[x][5]).toFixed ( 2).replace ( '.', ',');\n" .
              "    table.row.add ( data.result[x]).node ();\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( total_calls);\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 3).html ( number_format ( total_cost, 2, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 3 ]},\n" .
              "                { searchable: false, targets: [ 0, 3 ]},\n" .
              "                { visible: false, targets: [ 0, 3 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '50%', class: 'export all'},\n" .
              "             { width: '15%', class: 'export all'},\n" .
              "             { class: 'never'},\n" .
              "             { width: '20%', class: 'export all'},\n" .
              "             { width: '15%', class: 'export all'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
              "}));\n" .
              "$('#start').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#end').data ( 'DateTimePicker').minDate ( e.date);\n" .
              "}).val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').datetimepicker ( { locale: '" . __ ( "en-us") . "', useCurrent: false}).on ( 'dp.change', function ( e)\n" .
              "{\n" .
              "  $('#start').data ( 'DateTimePicker').maxDate ( e.date);\n" .
              "}).val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
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
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
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
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/financial/gateway', 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n");

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

  // Draw informations knob dials
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
              "  var health = VoIP.rest ( '/reports/status', 'GET');\n" .
              "  if ( health.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#health_memory').val ( health.result.memory.percent).trigger ( 'change');\n" .
              "    $('#health_memory_used').html ( health.result.memory.used);\n" .
              "    $('#health_memory_total').html ( health.result.memory.total);\n" .
              "    $('#health_cpu').val ( health.result.cpu.percent).trigger ( 'change');\n" .
              "    $('#health_cpu_processors').html ( health.result.cpu.processors);\n" .
              "    $('#health_storage').val ( health.result.storage.percent).trigger ( 'change');\n" .
              "    $('#health_storage_used').html ( health.result.storage.used);\n" .
              "    $('#health_storage_total').html ( health.result.storage.total);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Server health") . "', text: '" . __ ( "Error requesting informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  if ( $('#refresh').val () != '')\n" .
              "  {\n" .
              "    $(this).data ( 'timeout', setTimeout ( function () { $('#stats').trigger ( 'update'); }, $('#refresh').val () * 1000));\n" .
              "  }\n" .
              "}).trigger ( 'update');\n");

  /**
   * Return generated HTML
   */
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
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_activity_report_page ( $buffer, $parameters)
{
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
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "jquery-timeago-pt_BR", "src" => "/vendors/jquery-timeago/locales/jquery.timeago." . __ ( "en-us") . ".js", "dep" => array ( "jquery-timeago")));

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
  $output .= "<table id=\"report\" class=\"table table-hover table-striped table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Extension") . "</th>\n";
  $output .= "      <th>" . __ ( "Name") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th>" . __ ( "Last use") . "</th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "</table>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    data.result[x][2] = '<a href=\"/extensions/' + data.result[x][0] + '/view\">' + data.result[x][2] + '</a>';\n" .
              "    if ( data.result[x][3] == '')\n" .
              "    {\n" .
              "      data.result[x][3] = 0;\n" .
              "      data.result[x][4] = '" . __ ( "Never used") . "';\n" .
              "      data.result[x][5] = '" . __ ( "Never used") . "';\n" .
              "    } else {\n" .
              "      data.result[x][5] = jQuery.timeago ( data.result[x][4]);\n" .
              "    }\n" .
              "    table.row.add ( data.result[x]).node ();\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 0, 3 ]},\n" .
              "                { searchable: false, targets: [ 0, 3, 5 ]},\n" .
              "                { visible: false, targets: [ 0, 3 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { width: '10%', class: 'export all'},\n" .
              "             { width: '40%', class: 'export min-tablet-l'},\n" .
              "             { class: 'never'},\n" .
              "             { width: '25%', class: 'export min-mobile-l', dataSort: 3},\n" .
              "             { width: '25%', class: 'export all', dataSort: 3}\n" .
              "           ],\n" .
              "  stateLoadParams: function ( settings, data)\n" .
              "                   {\n" .
              "                     $('#filter').val ( data.search.search);\n" .
              "                   },\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
              "  dom: '<\"row-center\"Br>t+<\"row\"<\"col-xs-12\">>'\n" .
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
              "$('#report').data ( 'dt').processing ( true);\n" .
              "$('#report').trigger ( 'update', VoIP.rest ( '/reports/activity', 'GET'));\n");

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

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-4\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"extension\" id=\"extension\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension") . "\"><option value=\"\"></option></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Source") . "</th>\n";
  $output .= "      <th>" . __ ( "Destination") . "</th>\n";
  $output .= "      <th>" . __ ( "Duration ([[DD:]HH:]MM:SS)") . "</th>\n";
  $output .= "      <th>" . __ ( "Result") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Quality") . "</th>\n";
  $output .= "      <th>" . __ ( "Observations") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"9\"></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Call details modal
   */
  $output .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $output .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "        <div class=\"tab-content\"><br />\n";

  // Basic informations tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add source number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add destination number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add date and time
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add billing duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call result
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call type
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost center
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add gateway
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // QOS tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add called SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add received packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add RTT
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // Call flow tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $output .= "            <div class=\"diagram\"></div>\n";
  $output .= "          </div>\n";

  // Download tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $output .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><span class=\"fa fa-paperclip\" aria-hidden=\"true\"></span> " . __ ( "PCAP") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><span class=\"fa fa-copy\" aria-hidden=\"true\"></span> " . __ ( "Text") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><span class=\"fa fa-headphones\" aria-hidden=\"true\"></span> " . __ ( "Audio") . "</button>\n";
  $output .= "          </div>\n";

  // Finish modal
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server').select2 ();\n" .
              "$('#call_view_type').select2 ();\n" .
              "$('#call_view_cc').select2 ();\n" .
              "$('#call_view_gateway').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
              "$('#extension').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/extensions/search', 'GET')\n" .
              "});\n" .
              "$('#call_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#call_view_server').find ( 'option').remove ();\n" .
              "  if ( data.server != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.server + '\" selected=\"selected\">' + data.serverdesc + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.server).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( data.date);\n" .
              "  $('#call_view_duration').val ( data.duration);\n" .
              "  $('#call_view_billsec').val ( data.billsec);\n" .
              "  $('#call_view_disposition').val ( data.disposition);\n" .
              "  $('#call_view_source').val ( data.src);\n" .
              "  $('#call_view_destination').val ( data.dst);\n" .
              "  $('#call_view_cost').val ( data.value);\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.type + '\" selected=\"selected\">' + data.typedesc + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.type).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.cc != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.cc + '\" selected=\"selected\">' + data.ccdesc + '</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.cc).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.gw != 0)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.gw + '\" selected=\"selected\">' + data.gwdesc + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.gw).trigger ( 'change');\n" .
              "  }\n" .
              "  if ( data.QOSa.ssrc)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.QOSa.ssrc);\n" .
              "    $('#call_view_qos_themssrc').val ( data.QOSa.themssrc);\n" .
              "    $('#call_view_qos_rxlp').val ( data.QOSa.lp);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.QOSa.rxjitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.QOSa.rxcount);\n" .
              "    $('#call_view_qos_txjitter').val ( data.QOSa.txjitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.QOSa.txcount);\n" .
              "    $('#call_view_qos_txlp').val ( data.QOSa.rlp);\n" .
              "    $('#call_view_qos_rtt').val ( data.QOSa.rtt);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.siptrace.diagram)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('.diagram').html ( data.siptrace.diagram);\n" .
              "    $('#call_view_pcap').data ( 'content', data.siptrace.pcap).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap');\n" .
              "    $('#call_view_text').data ( 'content', data.siptrace.text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt');\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.monitor)\n" .
              "  {\n" .
              "    $('#call_view_audio').data ( 'content', data.uniqueid).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').data ( 'url', data.uniqueid).removeClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#call_view_audio').addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "});\n" .
              "$('#call_view').on ( 'show', function ( event, params)\n" .
              "{\n" .
              "  var l = Ladda.create ( params.button);\n" .
              "  l.start ();\n" .
              "  var call = VoIP.rest ( '/calls/' + encodeURI ( params.id), 'GET');\n" .
              "  if ( call.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#call_view_form').trigger ( 'fill', call.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "}).on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.fileDownload ( 'arquivos-audio.php?download=1&id=' + $(this).data ( 'url'));\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "    $('.diagram tspan').each ( function ()\n" .
              "    {\n" .
              "      $(this).html ( $(this).html ().replace ( '|', ':'));\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls++;\n" .
              "    total_time += parseInt ( data.result[x][5]) || 0;\n" .
              "    data.result[x][3] = '<i aria-hidden=\"true\" class=\"fa fa-' + ( data.result[x][3].type == 'external' ? 'phone' : 'user') + '\"></i> ' + data.result[x][3].number;\n" .
              "    var tmp = '<i aria-hidden=\"true\" class=\"fa fa-';\n" .
              "    switch ( data.result[x][4].type)\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "      case 'inbound':\n" .
              "        tmp += 'user';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "      case 'special':\n" .
              "        tmp += 'phone';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        tmp += 'mobile';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        tmp += 'tree';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        tmp += 'globe';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        tmp += 'leaf';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        tmp += 'wrench';\n" .
              "        break;\n" .
              "      default:\n" .
              "        tmp += 'exclamation-triangle';\n" .
              "        break;\n" .
              "    }\n" .
              "    data.result[x][4] = tmp + '\"></i> ' + data.result[x][4].number;\n" .
              "    data.result[x][5] = format_secs_to_string ( data.result[x][5]);\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + data.result[x][11].qos.lossrx + '% " . __ ( "rx") . " / ' + data.result[x][11].qos.losstx + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + data.result[x][11].qos.jitterrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.jittertx + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + data.result[x][11].qos.latencyrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.latencytx + ' ms " . __ ( "tx") . "\">';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '<font color=\"' + data.result[x][11].qualityColor + '\">';\n" .
              "    }\n" .
              "    tmp += data.result[x][11].quality;\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '</font>';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '</abbr>';\n" .
              "    }\n" .
              "    data.result[x][11] = tmp;\n" .
              "    $(table.row.add ( data.result[x]).node ()).addClass ( data.result[x][14]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( '<i class=\"fa fa-cloud\"></i> ' + sprintf ( total_calls != 1 ? '" . __ ( "%s calls") . "' : '" . __ ( "%s call") . "', total_calls));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "  $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 12, 13 ]},\n" .
              "                { visible: false, targets: [ 0, 2, 7, 8, 9, 10, 14, 15, 16 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all', dataSort: 0},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-mobile-l'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 10},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'all', createdCell: function ( cell, cellData, rowData, rowIndex, colIndex) { $(cell).html ( '<button class=\"btn btn-xs btn-call_view btn-info ladda-button\" data-id=\"' + rowData[15] + '\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Details") . "\" role=\"button\"><i class=\"fa fa-search\"></i></button>'); }},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
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
              "$('#report').on ( 'click', 'button.btn-call_view', function ( event) { event && event.preventDefault (); $('#call_view').trigger ( 'show', { button: this, id: $(this).data ( 'id')}); });\n" .
              "$('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( $('#start').val () == '')\n" .
              "  {\n" .
              "    $('#start').alerts ( 'add', { message: '" . __ ( "The start date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#end').val () == '')\n" .
              "  {\n" .
              "    $('#end').alerts ( 'add', { message: '" . __ ( "The finish date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#extension').val () == '')\n" .
              "  {\n" .
              "    $('#extension').alerts ( 'add', { message: '" . __ ( "The extension is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#start').val () == '' || $('#end').val () == '' || $('#extension').val () == '')\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/received/user/' + $('#extension').val (), 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n" .
              "$('#extension').focus ();\n");

  return $output;
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

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
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
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Source") . "</th>\n";
  $output .= "      <th>" . __ ( "Destination") . "</th>\n";
  $output .= "      <th>" . __ ( "Duration ([[DD:]HH:]MM:SS)") . "</th>\n";
  $output .= "      <th>" . __ ( "Result") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Quality") . "</th>\n";
  $output .= "      <th>" . __ ( "Observations") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"9\"></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Call details modal
   */
  $output .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $output .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "        <div class=\"tab-content\"><br />\n";

  // Basic informations tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add source number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add destination number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add date and time
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add billing duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call result
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call type
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost center
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add gateway
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // QOS tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add called SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add received packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add RTT
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // Call flow tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $output .= "            <div class=\"diagram\"></div>\n";
  $output .= "          </div>\n";

  // Download tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $output .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><span class=\"fa fa-paperclip\" aria-hidden=\"true\"></span> " . __ ( "PCAP") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><span class=\"fa fa-copy\" aria-hidden=\"true\"></span> " . __ ( "Text") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><span class=\"fa fa-headphones\" aria-hidden=\"true\"></span> " . __ ( "Audio") . "</button>\n";
  $output .= "          </div>\n";

  // Finish modal
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server').select2 ();\n" .
              "$('#call_view_type').select2 ();\n" .
              "$('#call_view_cc').select2 ();\n" .
              "$('#call_view_gateway').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
              "$('#group').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/groups/search', 'GET')\n" .
              "});\n" .
              "$('#call_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#call_view_server').find ( 'option').remove ();\n" .
              "  if ( data.server != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.server + '\" selected=\"selected\">' + data.serverdesc + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.server).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( data.date);\n" .
              "  $('#call_view_duration').val ( data.duration);\n" .
              "  $('#call_view_billsec').val ( data.billsec);\n" .
              "  $('#call_view_disposition').val ( data.disposition);\n" .
              "  $('#call_view_source').val ( data.src);\n" .
              "  $('#call_view_destination').val ( data.dst);\n" .
              "  $('#call_view_cost').val ( data.value);\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.type + '\" selected=\"selected\">' + data.typedesc + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.type).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.cc != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.cc + '\" selected=\"selected\">' + data.ccdesc + '</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.cc).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.gw != 0)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.gw + '\" selected=\"selected\">' + data.gwdesc + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.gw).trigger ( 'change');\n" .
              "  }\n" .
              "  if ( data.QOSa.ssrc)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.QOSa.ssrc);\n" .
              "    $('#call_view_qos_themssrc').val ( data.QOSa.themssrc);\n" .
              "    $('#call_view_qos_rxlp').val ( data.QOSa.lp);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.QOSa.rxjitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.QOSa.rxcount);\n" .
              "    $('#call_view_qos_txjitter').val ( data.QOSa.txjitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.QOSa.txcount);\n" .
              "    $('#call_view_qos_txlp').val ( data.QOSa.rlp);\n" .
              "    $('#call_view_qos_rtt').val ( data.QOSa.rtt);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.siptrace.diagram)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('.diagram').html ( data.siptrace.diagram);\n" .
              "    $('#call_view_pcap').data ( 'content', data.siptrace.pcap).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap');\n" .
              "    $('#call_view_text').data ( 'content', data.siptrace.text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt');\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.monitor)\n" .
              "  {\n" .
              "    $('#call_view_audio').data ( 'content', data.uniqueid).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').data ( 'url', data.uniqueid).removeClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#call_view_audio').addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "});\n" .
              "$('#call_view').on ( 'show', function ( event, params)\n" .
              "{\n" .
              "  var l = Ladda.create ( params.button);\n" .
              "  l.start ();\n" .
              "  var call = VoIP.rest ( '/calls/' + encodeURI ( params.id), 'GET');\n" .
              "  if ( call.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#call_view_form').trigger ( 'fill', call.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "}).on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.fileDownload ( 'arquivos-audio.php?download=1&id=' + $(this).data ( 'url'));\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "    $('.diagram tspan').each ( function ()\n" .
              "    {\n" .
              "      $(this).html ( $(this).html ().replace ( '|', ':'));\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls++;\n" .
              "    total_time += parseInt ( data.result[x][5]) || 0;\n" .
              "    data.result[x][3] = '<i aria-hidden=\"true\" class=\"fa fa-' + ( data.result[x][3].type == 'external' ? 'phone' : 'user') + '\"></i> ' + data.result[x][3].number;\n" .
              "    var tmp = '<i aria-hidden=\"true\" class=\"fa fa-';\n" .
              "    switch ( data.result[x][4].type)\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "      case 'inbound':\n" .
              "        tmp += 'user';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "      case 'special':\n" .
              "        tmp += 'phone';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        tmp += 'mobile';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        tmp += 'tree';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        tmp += 'globe';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        tmp += 'leaf';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        tmp += 'wrench';\n" .
              "        break;\n" .
              "      default:\n" .
              "        tmp += 'exclamation-triangle';\n" .
              "        break;\n" .
              "    }\n" .
              "    data.result[x][4] = tmp + '\"></i> ' + data.result[x][4].number;\n" .
              "    data.result[x][5] = format_secs_to_string ( data.result[x][5]);\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + data.result[x][11].qos.lossrx + '% " . __ ( "rx") . " / ' + data.result[x][11].qos.losstx + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + data.result[x][11].qos.jitterrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.jittertx + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + data.result[x][11].qos.latencyrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.latencytx + ' ms " . __ ( "tx") . "\">';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '<font color=\"' + data.result[x][11].qualityColor + '\">';\n" .
              "    }\n" .
              "    tmp += data.result[x][11].quality;\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '</font>';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '</abbr>';\n" .
              "    }\n" .
              "    data.result[x][11] = tmp;\n" .
              "    $(table.row.add ( data.result[x]).node ()).addClass ( data.result[x][14]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( '<i class=\"fa fa-cloud\"></i> ' + sprintf ( total_calls != 1 ? '" . __ ( "%s calls") . "' : '" . __ ( "%s call") . "', total_calls));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "  $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 12, 13 ]},\n" .
              "                { visible: false, targets: [ 0, 2, 7, 8, 9, 10, 14, 15, 16 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all', dataSort: 0},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-mobile-l'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 10},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'all', createdCell: function ( cell, cellData, rowData, rowIndex, colIndex) { $(cell).html ( '<button class=\"btn btn-xs btn-call_view btn-info ladda-button\" data-id=\"' + rowData[15] + '\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Details") . "\" role=\"button\"><i class=\"fa fa-search\"></i></button>'); }},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
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
              "$('#report').on ( 'click', 'button.btn-call_view', function ( event) { event && event.preventDefault (); $('#call_view').trigger ( 'show', { button: this, id: $(this).data ( 'id')}); });\n" .
              "$('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
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
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/received/group/' + $('#group').val (), 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n" .
              "$('#group').focus ();\n");

  return $output;
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

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-4\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"gateway\" id=\"gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\"><option value=\"\"></option></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Source") . "</th>\n";
  $output .= "      <th>" . __ ( "Destination") . "</th>\n";
  $output .= "      <th>" . __ ( "Duration ([[DD:]HH:]MM:SS)") . "</th>\n";
  $output .= "      <th>" . __ ( "Result") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Quality") . "</th>\n";
  $output .= "      <th>" . __ ( "Observations") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"9\"></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Call details modal
   */
  $output .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $output .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "        <div class=\"tab-content\"><br />\n";

  // Basic informations tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add source number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add destination number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add date and time
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add billing duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call result
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call type
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost center
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add gateway
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // QOS tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add called SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add received packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add RTT
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // Call flow tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $output .= "            <div class=\"diagram\"></div>\n";
  $output .= "          </div>\n";

  // Download tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $output .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><span class=\"fa fa-paperclip\" aria-hidden=\"true\"></span> " . __ ( "PCAP") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><span class=\"fa fa-copy\" aria-hidden=\"true\"></span> " . __ ( "Text") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><span class=\"fa fa-headphones\" aria-hidden=\"true\"></span> " . __ ( "Audio") . "</button>\n";
  $output .= "          </div>\n";

  // Finish modal
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server').select2 ();\n" .
              "$('#call_view_type').select2 ();\n" .
              "$('#call_view_cc').select2 ();\n" .
              "$('#call_view_gateway').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
              "$('#gateway').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/gateways/search', 'GET')\n" .
              "});\n" .
              "$('#call_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#call_view_server').find ( 'option').remove ();\n" .
              "  if ( data.server != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.server + '\" selected=\"selected\">' + data.serverdesc + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.server).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( data.date);\n" .
              "  $('#call_view_duration').val ( data.duration);\n" .
              "  $('#call_view_billsec').val ( data.billsec);\n" .
              "  $('#call_view_disposition').val ( data.disposition);\n" .
              "  $('#call_view_source').val ( data.src);\n" .
              "  $('#call_view_destination').val ( data.dst);\n" .
              "  $('#call_view_cost').val ( data.value);\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.type + '\" selected=\"selected\">' + data.typedesc + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.type).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.cc != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.cc + '\" selected=\"selected\">' + data.ccdesc + '</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.cc).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.gw != 0)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.gw + '\" selected=\"selected\">' + data.gwdesc + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.gw).trigger ( 'change');\n" .
              "  }\n" .
              "  if ( data.QOSa.ssrc)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.QOSa.ssrc);\n" .
              "    $('#call_view_qos_themssrc').val ( data.QOSa.themssrc);\n" .
              "    $('#call_view_qos_rxlp').val ( data.QOSa.lp);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.QOSa.rxjitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.QOSa.rxcount);\n" .
              "    $('#call_view_qos_txjitter').val ( data.QOSa.txjitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.QOSa.txcount);\n" .
              "    $('#call_view_qos_txlp').val ( data.QOSa.rlp);\n" .
              "    $('#call_view_qos_rtt').val ( data.QOSa.rtt);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.siptrace.diagram)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('.diagram').html ( data.siptrace.diagram);\n" .
              "    $('#call_view_pcap').data ( 'content', data.siptrace.pcap).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap');\n" .
              "    $('#call_view_text').data ( 'content', data.siptrace.text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt');\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.monitor)\n" .
              "  {\n" .
              "    $('#call_view_audio').data ( 'content', data.uniqueid).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').data ( 'url', data.uniqueid).removeClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#call_view_audio').addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "});\n" .
              "$('#call_view').on ( 'show', function ( event, params)\n" .
              "{\n" .
              "  var l = Ladda.create ( params.button);\n" .
              "  l.start ();\n" .
              "  var call = VoIP.rest ( '/calls/' + encodeURI ( params.id), 'GET');\n" .
              "  if ( call.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#call_view_form').trigger ( 'fill', call.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "}).on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.fileDownload ( 'arquivos-audio.php?download=1&id=' + $(this).data ( 'url'));\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "    $('.diagram tspan').each ( function ()\n" .
              "    {\n" .
              "      $(this).html ( $(this).html ().replace ( '|', ':'));\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls++;\n" .
              "    total_time += parseInt ( data.result[x][5]) || 0;\n" .
              "    data.result[x][3] = '<i aria-hidden=\"true\" class=\"fa fa-' + ( data.result[x][3].type == 'external' ? 'phone' : 'user') + '\"></i> ' + data.result[x][3].number;\n" .
              "    var tmp = '<i aria-hidden=\"true\" class=\"fa fa-';\n" .
              "    switch ( data.result[x][4].type)\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "      case 'inbound':\n" .
              "        tmp += 'user';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "      case 'special':\n" .
              "        tmp += 'phone';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        tmp += 'mobile';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        tmp += 'tree';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        tmp += 'globe';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        tmp += 'leaf';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        tmp += 'wrench';\n" .
              "        break;\n" .
              "      default:\n" .
              "        tmp += 'exclamation-triangle';\n" .
              "        break;\n" .
              "    }\n" .
              "    data.result[x][4] = tmp + '\"></i> ' + data.result[x][4].number;\n" .
              "    data.result[x][5] = format_secs_to_string ( data.result[x][5]);\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + data.result[x][11].qos.lossrx + '% " . __ ( "rx") . " / ' + data.result[x][11].qos.losstx + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + data.result[x][11].qos.jitterrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.jittertx + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + data.result[x][11].qos.latencyrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.latencytx + ' ms " . __ ( "tx") . "\">';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '<font color=\"' + data.result[x][11].qualityColor + '\">';\n" .
              "    }\n" .
              "    tmp += data.result[x][11].quality;\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '</font>';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '</abbr>';\n" .
              "    }\n" .
              "    data.result[x][11] = tmp;\n" .
              "    $(table.row.add ( data.result[x]).node ()).addClass ( data.result[x][14]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( '<i class=\"fa fa-cloud\"></i> ' + sprintf ( total_calls != 1 ? '" . __ ( "%s calls") . "' : '" . __ ( "%s call") . "', total_calls));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "  $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 12, 13 ]},\n" .
              "                { visible: false, targets: [ 0, 2, 7, 8, 9, 10, 14, 15, 16 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all', dataSort: 0},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-mobile-l'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 10},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'all', createdCell: function ( cell, cellData, rowData, rowIndex, colIndex) { $(cell).html ( '<button class=\"btn btn-xs btn-call_view btn-info ladda-button\" data-id=\"' + rowData[15] + '\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Details") . "\" role=\"button\"><i class=\"fa fa-search\"></i></button>'); }},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
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
              "$('#report').on ( 'click', 'button.btn-call_view', function ( event) { event && event.preventDefault (); $('#call_view').trigger ( 'show', { button: this, id: $(this).data ( 'id')}); });\n" .
              "$('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( $('#start').val () == '')\n" .
              "  {\n" .
              "    $('#start').alerts ( 'add', { message: '" . __ ( "The start date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#end').val () == '')\n" .
              "  {\n" .
              "    $('#end').alerts ( 'add', { message: '" . __ ( "The finish date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#gateway').val () == '')\n" .
              "  {\n" .
              "    $('#gateway').alerts ( 'add', { message: '" . __ ( "The gateway is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#start').val () == '' || $('#end').val () == '' || $('#gateway').val () == '')\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/received/gateway/' + $('#gateway').val (), 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n" .
              "$('#gateway').focus ();\n");

  return $output;
}

/**
 * Add system received calls report page
 */
framework_add_path ( "/reports/received/all", "all_calls_report_page");
framework_add_hook ( "all_calls_report_page", "all_calls_report_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the system received calls report page code.
 *
 * @param mixed $buffer Buffer from plugin system if processed by other function
 *                      before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function all_calls_report_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Reports"));
  sys_set_subtitle ( __ ( "calls received by all"));
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
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-5\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Source") . "</th>\n";
  $output .= "      <th>" . __ ( "Destination") . "</th>\n";
  $output .= "      <th>" . __ ( "Duration ([[DD:]HH:]MM:SS)") . "</th>\n";
  $output .= "      <th>" . __ ( "Result") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Quality") . "</th>\n";
  $output .= "      <th>" . __ ( "Observations") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"9\"></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Call details modal
   */
  $output .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $output .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "        <div class=\"tab-content\"><br />\n";

  // Basic informations tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add source number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add destination number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add date and time
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add billing duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call result
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call type
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost center
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add gateway
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // QOS tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add called SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add received packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add RTT
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // Call flow tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $output .= "            <div class=\"diagram\"></div>\n";
  $output .= "          </div>\n";

  // Download tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $output .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><span class=\"fa fa-paperclip\" aria-hidden=\"true\"></span> " . __ ( "PCAP") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><span class=\"fa fa-copy\" aria-hidden=\"true\"></span> " . __ ( "Text") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><span class=\"fa fa-headphones\" aria-hidden=\"true\"></span> " . __ ( "Audio") . "</button>\n";
  $output .= "          </div>\n";

  // Finish modal
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server').select2 ();\n" .
              "$('#call_view_type').select2 ();\n" .
              "$('#call_view_cc').select2 ();\n" .
              "$('#call_view_gateway').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
              "$('#call_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#call_view_server').find ( 'option').remove ();\n" .
              "  if ( data.server != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.server + '\" selected=\"selected\">' + data.serverdesc + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.server).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( data.date);\n" .
              "  $('#call_view_duration').val ( data.duration);\n" .
              "  $('#call_view_billsec').val ( data.billsec);\n" .
              "  $('#call_view_disposition').val ( data.disposition);\n" .
              "  $('#call_view_source').val ( data.src);\n" .
              "  $('#call_view_destination').val ( data.dst);\n" .
              "  $('#call_view_cost').val ( data.value);\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.type + '\" selected=\"selected\">' + data.typedesc + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.type).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.cc != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.cc + '\" selected=\"selected\">' + data.ccdesc + '</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.cc).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.gw != 0)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.gw + '\" selected=\"selected\">' + data.gwdesc + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.gw).trigger ( 'change');\n" .
              "  }\n" .
              "  if ( data.QOSa.ssrc)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.QOSa.ssrc);\n" .
              "    $('#call_view_qos_themssrc').val ( data.QOSa.themssrc);\n" .
              "    $('#call_view_qos_rxlp').val ( data.QOSa.lp);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.QOSa.rxjitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.QOSa.rxcount);\n" .
              "    $('#call_view_qos_txjitter').val ( data.QOSa.txjitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.QOSa.txcount);\n" .
              "    $('#call_view_qos_txlp').val ( data.QOSa.rlp);\n" .
              "    $('#call_view_qos_rtt').val ( data.QOSa.rtt);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.siptrace.diagram)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('.diagram').html ( data.siptrace.diagram);\n" .
              "    $('#call_view_pcap').data ( 'content', data.siptrace.pcap).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap');\n" .
              "    $('#call_view_text').data ( 'content', data.siptrace.text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt');\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.monitor)\n" .
              "  {\n" .
              "    $('#call_view_audio').data ( 'content', data.uniqueid).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').data ( 'url', data.uniqueid).removeClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#call_view_audio').addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "});\n" .
              "$('#call_view').on ( 'show', function ( event, params)\n" .
              "{\n" .
              "  var l = Ladda.create ( params.button);\n" .
              "  l.start ();\n" .
              "  var call = VoIP.rest ( '/calls/' + encodeURI ( params.id), 'GET');\n" .
              "  if ( call.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#call_view_form').trigger ( 'fill', call.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "}).on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.fileDownload ( 'arquivos-audio.php?download=1&id=' + $(this).data ( 'url'));\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "    $('.diagram tspan').each ( function ()\n" .
              "    {\n" .
              "      $(this).html ( $(this).html ().replace ( '|', ':'));\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls++;\n" .
              "    total_time += parseInt ( data.result[x][5]) || 0;\n" .
              "    data.result[x][3] = '<i aria-hidden=\"true\" class=\"fa fa-' + ( data.result[x][3].type == 'external' ? 'phone' : 'user') + '\"></i> ' + data.result[x][3].number;\n" .
              "    var tmp = '<i aria-hidden=\"true\" class=\"fa fa-';\n" .
              "    switch ( data.result[x][4].type)\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "      case 'inbound':\n" .
              "        tmp += 'user';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "      case 'special':\n" .
              "        tmp += 'phone';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        tmp += 'mobile';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        tmp += 'tree';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        tmp += 'globe';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        tmp += 'leaf';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        tmp += 'wrench';\n" .
              "        break;\n" .
              "      default:\n" .
              "        tmp += 'exclamation-triangle';\n" .
              "        break;\n" .
              "    }\n" .
              "    data.result[x][4] = tmp + '\"></i> ' + data.result[x][4].number;\n" .
              "    data.result[x][5] = format_secs_to_string ( data.result[x][5]);\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + data.result[x][11].qos.lossrx + '% " . __ ( "rx") . " / ' + data.result[x][11].qos.losstx + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + data.result[x][11].qos.jitterrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.jittertx + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + data.result[x][11].qos.latencyrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.latencytx + ' ms " . __ ( "tx") . "\">';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '<font color=\"' + data.result[x][11].qualityColor + '\">';\n" .
              "    }\n" .
              "    tmp += data.result[x][11].quality;\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '</font>';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '</abbr>';\n" .
              "    }\n" .
              "    data.result[x][11] = tmp;\n" .
              "    $(table.row.add ( data.result[x]).node ()).addClass ( data.result[x][14]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( '<i class=\"fa fa-cloud\"></i> ' + sprintf ( total_calls != 1 ? '" . __ ( "%s calls") . "' : '" . __ ( "%s call") . "', total_calls));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "  $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 12, 13 ]},\n" .
              "                { visible: false, targets: [ 0, 2, 7, 8, 9, 10, 14, 15, 16 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all', dataSort: 0},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-mobile-l'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 10},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'all', createdCell: function ( cell, cellData, rowData, rowIndex, colIndex) { $(cell).html ( '<button class=\"btn btn-xs btn-call_view btn-info ladda-button\" data-id=\"' + rowData[15] + '\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Details") . "\" role=\"button\"><i class=\"fa fa-search\"></i></button>'); }},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
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
              "$('#report').on ( 'click', 'button.btn-call_view', function ( event) { event && event.preventDefault (); $('#call_view').trigger ( 'show', { button: this, id: $(this).data ( 'id')}); });\n" .
              "$('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
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
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/received/all', 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n" .
              "$('#end').focus ();\n");

  return $output;
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

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-4\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"extension\" id=\"extension\" class=\"form-control\" data-placeholder=\"" . __ ( "Extension") . "\"><option value=\"\"></option></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Source") . "</th>\n";
  $output .= "      <th>" . __ ( "Destination") . "</th>\n";
  $output .= "      <th>" . __ ( "Duration ([[DD:]HH:]MM:SS)") . "</th>\n";
  $output .= "      <th>" . __ ( "Result") . "</th>\n";
  $output .= "      <th>" . __ ( "Type") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Cost") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Quality") . "</th>\n";
  $output .= "      <th>" . __ ( "Observations") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"6\"></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Call details modal
   */
  $output .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $output .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "        <div class=\"tab-content\"><br />\n";

  // Basic informations tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add source number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add destination number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add date and time
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add billing duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call result
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call type
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost center
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add gateway
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // QOS tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add called SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add received packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add RTT
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // Call flow tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $output .= "            <div class=\"diagram\"></div>\n";
  $output .= "          </div>\n";

  // Download tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $output .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><span class=\"fa fa-paperclip\" aria-hidden=\"true\"></span> " . __ ( "PCAP") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><span class=\"fa fa-copy\" aria-hidden=\"true\"></span> " . __ ( "Text") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><span class=\"fa fa-headphones\" aria-hidden=\"true\"></span> " . __ ( "Audio") . "</button>\n";
  $output .= "          </div>\n";

  // Finish modal
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server').select2 ();\n" .
              "$('#call_view_type').select2 ();\n" .
              "$('#call_view_cc').select2 ();\n" .
              "$('#call_view_gateway').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
              "$('#extension').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/extensions/search', 'GET')\n" .
              "});\n" .
              "$('#call_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#call_view_server').find ( 'option').remove ();\n" .
              "  if ( data.server != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.server + '\" selected=\"selected\">' + data.serverdesc + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.server).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( data.date);\n" .
              "  $('#call_view_duration').val ( data.duration);\n" .
              "  $('#call_view_billsec').val ( data.billsec);\n" .
              "  $('#call_view_disposition').val ( data.disposition);\n" .
              "  $('#call_view_source').val ( data.src);\n" .
              "  $('#call_view_destination').val ( data.dst);\n" .
              "  $('#call_view_cost').val ( data.value);\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.type + '\" selected=\"selected\">' + data.typedesc + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.type).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.cc != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.cc + '\" selected=\"selected\">' + data.ccdesc + '</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.cc).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.gw != 0)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.gw + '\" selected=\"selected\">' + data.gwdesc + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.gw).trigger ( 'change');\n" .
              "  }\n" .
              "  if ( data.QOSa.ssrc)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.QOSa.ssrc);\n" .
              "    $('#call_view_qos_themssrc').val ( data.QOSa.themssrc);\n" .
              "    $('#call_view_qos_rxlp').val ( data.QOSa.lp);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.QOSa.rxjitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.QOSa.rxcount);\n" .
              "    $('#call_view_qos_txjitter').val ( data.QOSa.txjitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.QOSa.txcount);\n" .
              "    $('#call_view_qos_txlp').val ( data.QOSa.rlp);\n" .
              "    $('#call_view_qos_rtt').val ( data.QOSa.rtt);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.siptrace.diagram)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('.diagram').html ( data.siptrace.diagram);\n" .
              "    $('#call_view_pcap').data ( 'content', data.siptrace.pcap).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap');\n" .
              "    $('#call_view_text').data ( 'content', data.siptrace.text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt');\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.monitor)\n" .
              "  {\n" .
              "    $('#call_view_audio').data ( 'content', data.uniqueid).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').data ( 'url', data.uniqueid).removeClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#call_view_audio').addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "});\n" .
              "$('#call_view').on ( 'show', function ( event, params)\n" .
              "{\n" .
              "  var l = Ladda.create ( params.button);\n" .
              "  l.start ();\n" .
              "  var call = VoIP.rest ( '/calls/' + encodeURI ( params.id), 'GET');\n" .
              "  if ( call.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#call_view_form').trigger ( 'fill', call.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "}).on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.fileDownload ( 'arquivos-audio.php?download=1&id=' + $(this).data ( 'url'));\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "    $('.diagram tspan').each ( function ()\n" .
              "    {\n" .
              "      $(this).html ( $(this).html ().replace ( '|', ':'));\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  var total_cost = 0;\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls++;\n" .
              "    data.result[x][3] = '<i aria-hidden=\"true\" class=\"fa fa-' + ( data.result[x][3].type == 'external' ? 'phone' : 'user') + '\"></i> ' + data.result[x][3].number;\n" .
              "    var tmp = '<i aria-hidden=\"true\" class=\"fa fa-';\n" .
              "    switch ( data.result[x][4].type)\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "      case 'inbound':\n" .
              "        tmp += 'user';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "      case 'special':\n" .
              "        tmp += 'phone';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        tmp += 'mobile';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        tmp += 'tree';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        tmp += 'globe';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        tmp += 'leaf';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        tmp += 'wrench';\n" .
              "        break;\n" .
              "      default:\n" .
              "        tmp += 'exclamation-triangle';\n" .
              "        break;\n" .
              "    }\n" .
              "    data.result[x][4] = tmp + '\"></i> ' + data.result[x][4].number;\n" .
              "    total_time += parseInt ( data.result[x][5]) || 0;\n" .
              "    data.result[x][5] = format_secs_to_string ( data.result[x][5]);\n" .
              "    switch ( data.result[x][7])\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "        data.result[x][7] = '" . __ ( "Extension") . "';\n" .
              "        break;\n" .
              "      case 'inbound':\n" .
              "        data.result[x][7] = '" . __ ( "Received") . "';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "        data.result[x][7] = '" . __ ( "Landline") . "';\n" .
              "        break;\n" .
              "      case 'special':\n" .
              "        data.result[x][7] = '" . __ ( "Special") . "';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        data.result[x][7] = '" . __ ( "Mobile") . "';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        data.result[x][7] = '" . __ ( "Interstate") . "';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        data.result[x][7] = '" . __ ( "International") . "';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        data.result[x][7] = '" . __ ( "Toll free") . "';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        data.result[x][7] = '" . __ ( "Services") . "';\n" .
              "        break;\n" .
              "      default:\n" .
              "        data.result[x][7] = '" . __ ( "Unknown") . "';\n" .
              "        break;\n" .
              "    }\n" .
              "    total_cost += parseFloat ( data.result[x][8].replace ( '.', '').replace ( ',', '.'));\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + data.result[x][11].qos.lossrx + '% " . __ ( "rx") . " / ' + data.result[x][11].qos.losstx + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + data.result[x][11].qos.jitterrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.jittertx + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + data.result[x][11].qos.latencyrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.latencytx + ' ms " . __ ( "tx") . "\">';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '<font color=\"' + data.result[x][11].qualityColor + '\">';\n" .
              "    }\n" .
              "    tmp += data.result[x][11].quality;\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '</font>';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '</abbr>';\n" .
              "    }\n" .
              "    data.result[x][11] = tmp;\n" .
              "    $(table.row.add ( data.result[x]).node ()).addClass ( data.result[x][14]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( '<i class=\"fa fa-cloud\"></i> ' + sprintf ( total_calls != 1 ? '" . __ ( "%s calls") . "' : '" . __ ( "%s call") . "', total_calls));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 3).html ( number_format ( total_cost, 2, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "  $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 12, 13, 14, 15, 16 ]},\n" .
              "                { visible: false, targets: [ 0, 2, 8, 10, 14, 15, 16 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all', dataSort: 0},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 8},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 10},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'all', createdCell: function ( cell, cellData, rowData, rowIndex, colIndex) { $(cell).html ( '<button class=\"btn btn-xs btn-call_view btn-info ladda-button\" data-id=\"' + rowData[15] + '\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Details") . "\" role=\"button\"><i class=\"fa fa-search\"></i></button>'); }},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
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
              "$('#report').on ( 'click', 'button.btn-call_view', function ( event) { event && event.preventDefault (); $('#call_view').trigger ( 'show', { button: this, id: $(this).data ( 'id')}); });\n" .
              "$('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( $('#start').val () == '')\n" .
              "  {\n" .
              "    $('#start').alerts ( 'add', { message: '" . __ ( "The start date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#end').val () == '')\n" .
              "  {\n" .
              "    $('#end').alerts ( 'add', { message: '" . __ ( "The finish date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#extension').val () == '')\n" .
              "  {\n" .
              "    $('#extension').alerts ( 'add', { message: '" . __ ( "The extension is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#start').val () == '' || $('#end').val () == '' || $('#extension').val () == '')\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/made/user/' + $('#extension').val (), 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n" .
              "$('#group').focus ();\n");

  return $output;
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

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
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
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Source") . "</th>\n";
  $output .= "      <th>" . __ ( "Destination") . "</th>\n";
  $output .= "      <th>" . __ ( "Duration ([[DD:]HH:]MM:SS)") . "</th>\n";
  $output .= "      <th>" . __ ( "Result") . "</th>\n";
  $output .= "      <th>" . __ ( "Type") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Cost") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Quality") . "</th>\n";
  $output .= "      <th>" . __ ( "Observations") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"6\"></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Call details modal
   */
  $output .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $output .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "        <div class=\"tab-content\"><br />\n";

  // Basic informations tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add source number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add destination number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add date and time
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add billing duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call result
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call type
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost center
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add gateway
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // QOS tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add called SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add received packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add RTT
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // Call flow tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $output .= "            <div class=\"diagram\"></div>\n";
  $output .= "          </div>\n";

  // Download tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $output .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><span class=\"fa fa-paperclip\" aria-hidden=\"true\"></span> " . __ ( "PCAP") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><span class=\"fa fa-copy\" aria-hidden=\"true\"></span> " . __ ( "Text") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><span class=\"fa fa-headphones\" aria-hidden=\"true\"></span> " . __ ( "Audio") . "</button>\n";
  $output .= "          </div>\n";

  // Finish modal
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server').select2 ();\n" .
              "$('#call_view_type').select2 ();\n" .
              "$('#call_view_cc').select2 ();\n" .
              "$('#call_view_gateway').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
              "$('#group').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/groups/search', 'GET')\n" .
              "});\n" .
              "$('#call_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#call_view_server').find ( 'option').remove ();\n" .
              "  if ( data.server != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.server + '\" selected=\"selected\">' + data.serverdesc + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.server).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( data.date);\n" .
              "  $('#call_view_duration').val ( data.duration);\n" .
              "  $('#call_view_billsec').val ( data.billsec);\n" .
              "  $('#call_view_disposition').val ( data.disposition);\n" .
              "  $('#call_view_source').val ( data.src);\n" .
              "  $('#call_view_destination').val ( data.dst);\n" .
              "  $('#call_view_cost').val ( data.value);\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.type + '\" selected=\"selected\">' + data.typedesc + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.type).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.cc != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.cc + '\" selected=\"selected\">' + data.ccdesc + '</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.cc).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.gw != 0)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.gw + '\" selected=\"selected\">' + data.gwdesc + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.gw).trigger ( 'change');\n" .
              "  }\n" .
              "  if ( data.QOSa.ssrc)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.QOSa.ssrc);\n" .
              "    $('#call_view_qos_themssrc').val ( data.QOSa.themssrc);\n" .
              "    $('#call_view_qos_rxlp').val ( data.QOSa.lp);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.QOSa.rxjitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.QOSa.rxcount);\n" .
              "    $('#call_view_qos_txjitter').val ( data.QOSa.txjitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.QOSa.txcount);\n" .
              "    $('#call_view_qos_txlp').val ( data.QOSa.rlp);\n" .
              "    $('#call_view_qos_rtt').val ( data.QOSa.rtt);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.siptrace.diagram)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('.diagram').html ( data.siptrace.diagram);\n" .
              "    $('#call_view_pcap').data ( 'content', data.siptrace.pcap).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap');\n" .
              "    $('#call_view_text').data ( 'content', data.siptrace.text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt');\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.monitor)\n" .
              "  {\n" .
              "    $('#call_view_audio').data ( 'content', data.uniqueid).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').data ( 'url', data.uniqueid).removeClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#call_view_audio').addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "});\n" .
              "$('#call_view').on ( 'show', function ( event, params)\n" .
              "{\n" .
              "  var l = Ladda.create ( params.button);\n" .
              "  l.start ();\n" .
              "  var call = VoIP.rest ( '/calls/' + encodeURI ( params.id), 'GET');\n" .
              "  if ( call.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#call_view_form').trigger ( 'fill', call.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "}).on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.fileDownload ( 'arquivos-audio.php?download=1&id=' + $(this).data ( 'url'));\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "    $('.diagram tspan').each ( function ()\n" .
              "    {\n" .
              "      $(this).html ( $(this).html ().replace ( '|', ':'));\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  var total_cost = 0;\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls++;\n" .
              "    data.result[x][3] = '<i aria-hidden=\"true\" class=\"fa fa-' + ( data.result[x][3].type == 'external' ? 'phone' : 'user') + '\"></i> ' + data.result[x][3].number;\n" .
              "    var tmp = '<i aria-hidden=\"true\" class=\"fa fa-';\n" .
              "    switch ( data.result[x][4].type)\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "      case 'inbound':\n" .
              "        tmp += 'user';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "      case 'special':\n" .
              "        tmp += 'phone';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        tmp += 'mobile';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        tmp += 'tree';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        tmp += 'globe';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        tmp += 'leaf';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        tmp += 'wrench';\n" .
              "        break;\n" .
              "      default:\n" .
              "        tmp += 'exclamation-triangle';\n" .
              "        break;\n" .
              "    }\n" .
              "    data.result[x][4] = tmp + '\"></i> ' + data.result[x][4].number;\n" .
              "    total_time += parseInt ( data.result[x][5]) || 0;\n" .
              "    data.result[x][5] = format_secs_to_string ( data.result[x][5]);\n" .
              "    switch ( data.result[x][7])\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "        data.result[x][7] = '" . __ ( "Extension") . "';\n" .
              "        break;\n" .
              "      case 'inbound':\n" .
              "        data.result[x][7] = '" . __ ( "Received") . "';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "        data.result[x][7] = '" . __ ( "Landline") . "';\n" .
              "        break;\n" .
              "      case 'special':\n" .
              "        data.result[x][7] = '" . __ ( "Special") . "';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        data.result[x][7] = '" . __ ( "Mobile") . "';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        data.result[x][7] = '" . __ ( "Interstate") . "';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        data.result[x][7] = '" . __ ( "International") . "';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        data.result[x][7] = '" . __ ( "Toll free") . "';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        data.result[x][7] = '" . __ ( "Services") . "';\n" .
              "        break;\n" .
              "      default:\n" .
              "        data.result[x][7] = '" . __ ( "Unknown") . "';\n" .
              "        break;\n" .
              "    }\n" .
              "    total_cost += parseFloat ( data.result[x][8].replace ( '.', '').replace ( ',', '.'));\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + data.result[x][11].qos.lossrx + '% " . __ ( "rx") . " / ' + data.result[x][11].qos.losstx + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + data.result[x][11].qos.jitterrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.jittertx + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + data.result[x][11].qos.latencyrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.latencytx + ' ms " . __ ( "tx") . "\">';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '<font color=\"' + data.result[x][11].qualityColor + '\">';\n" .
              "    }\n" .
              "    tmp += data.result[x][11].quality;\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '</font>';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '</abbr>';\n" .
              "    }\n" .
              "    data.result[x][11] = tmp;\n" .
              "    $(table.row.add ( data.result[x]).node ()).addClass ( data.result[x][14]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( '<i class=\"fa fa-cloud\"></i> ' + sprintf ( total_calls != 1 ? '" . __ ( "%s calls") . "' : '" . __ ( "%s call") . "', total_calls));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 3).html ( number_format ( total_cost, 2, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "  $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 12, 13, 14, 15, 16 ]},\n" .
              "                { visible: false, targets: [ 0, 2, 8, 10, 14, 15, 16 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all', dataSort: 0},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 8},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 10},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'all', createdCell: function ( cell, cellData, rowData, rowIndex, colIndex) { $(cell).html ( '<button class=\"btn btn-xs btn-call_view btn-info ladda-button\" data-id=\"' + rowData[15] + '\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Details") . "\" role=\"button\"><i class=\"fa fa-search\"></i></button>'); }},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
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
              "$('#report').on ( 'click', 'button.btn-call_view', function ( event) { event && event.preventDefault (); $('#call_view').trigger ( 'show', { button: this, id: $(this).data ( 'id')}); });\n" .
              "$('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
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
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/made/group/' + $('#group').val (), 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n" .
              "$('#group').focus ();\n");

  return $output;
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

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-3\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <div class=\"col-md-4\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <select name=\"gateway\" id=\"gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\"><option value=\"\"></option></select>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Source") . "</th>\n";
  $output .= "      <th>" . __ ( "Destination") . "</th>\n";
  $output .= "      <th>" . __ ( "Duration ([[DD:]HH:]MM:SS)") . "</th>\n";
  $output .= "      <th>" . __ ( "Result") . "</th>\n";
  $output .= "      <th>" . __ ( "Type") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Cost") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Quality") . "</th>\n";
  $output .= "      <th>" . __ ( "Observations") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"6\"></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Call details modal
   */
  $output .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $output .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "        <div class=\"tab-content\"><br />\n";

  // Basic informations tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add source number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add destination number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add date and time
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add billing duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call result
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call type
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost center
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add gateway
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // QOS tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add called SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add received packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add RTT
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // Call flow tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $output .= "            <div class=\"diagram\"></div>\n";
  $output .= "          </div>\n";

  // Download tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $output .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><span class=\"fa fa-paperclip\" aria-hidden=\"true\"></span> " . __ ( "PCAP") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><span class=\"fa fa-copy\" aria-hidden=\"true\"></span> " . __ ( "Text") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><span class=\"fa fa-headphones\" aria-hidden=\"true\"></span> " . __ ( "Audio") . "</button>\n";
  $output .= "          </div>\n";

  // Finish modal
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server').select2 ();\n" .
              "$('#call_view_type').select2 ();\n" .
              "$('#call_view_cc').select2 ();\n" .
              "$('#call_view_gateway').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
              "$('#gateway').select2 (\n" .
              "{\n" .
              "  allowClear: true,\n" .
              "  data: VoIP.select2 ( '/gateways/search', 'GET')\n" .
              "});\n" .
              "$('#call_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#call_view_server').find ( 'option').remove ();\n" .
              "  if ( data.server != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.server + '\" selected=\"selected\">' + data.serverdesc + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.server).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( data.date);\n" .
              "  $('#call_view_duration').val ( data.duration);\n" .
              "  $('#call_view_billsec').val ( data.billsec);\n" .
              "  $('#call_view_disposition').val ( data.disposition);\n" .
              "  $('#call_view_source').val ( data.src);\n" .
              "  $('#call_view_destination').val ( data.dst);\n" .
              "  $('#call_view_cost').val ( data.value);\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.type + '\" selected=\"selected\">' + data.typedesc + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.type).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.cc != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.cc + '\" selected=\"selected\">' + data.ccdesc + '</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.cc).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.gw != 0)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.gw + '\" selected=\"selected\">' + data.gwdesc + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.gw).trigger ( 'change');\n" .
              "  }\n" .
              "  if ( data.QOSa.ssrc)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.QOSa.ssrc);\n" .
              "    $('#call_view_qos_themssrc').val ( data.QOSa.themssrc);\n" .
              "    $('#call_view_qos_rxlp').val ( data.QOSa.lp);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.QOSa.rxjitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.QOSa.rxcount);\n" .
              "    $('#call_view_qos_txjitter').val ( data.QOSa.txjitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.QOSa.txcount);\n" .
              "    $('#call_view_qos_txlp').val ( data.QOSa.rlp);\n" .
              "    $('#call_view_qos_rtt').val ( data.QOSa.rtt);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.siptrace.diagram)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('.diagram').html ( data.siptrace.diagram);\n" .
              "    $('#call_view_pcap').data ( 'content', data.siptrace.pcap).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap');\n" .
              "    $('#call_view_text').data ( 'content', data.siptrace.text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt');\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.monitor)\n" .
              "  {\n" .
              "    $('#call_view_audio').data ( 'content', data.uniqueid).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').data ( 'url', data.uniqueid).removeClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#call_view_audio').addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "});\n" .
              "$('#call_view').on ( 'show', function ( event, params)\n" .
              "{\n" .
              "  var l = Ladda.create ( params.button);\n" .
              "  l.start ();\n" .
              "  var call = VoIP.rest ( '/calls/' + encodeURI ( params.id), 'GET');\n" .
              "  if ( call.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#call_view_form').trigger ( 'fill', call.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "}).on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.fileDownload ( 'arquivos-audio.php?download=1&id=' + $(this).data ( 'url'));\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "    $('.diagram tspan').each ( function ()\n" .
              "    {\n" .
              "      $(this).html ( $(this).html ().replace ( '|', ':'));\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  var total_cost = 0;\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls++;\n" .
              "    data.result[x][3] = '<i aria-hidden=\"true\" class=\"fa fa-' + ( data.result[x][3].type == 'external' ? 'phone' : 'user') + '\"></i> ' + data.result[x][3].number;\n" .
              "    var tmp = '<i aria-hidden=\"true\" class=\"fa fa-';\n" .
              "    switch ( data.result[x][4].type)\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "      case 'inbound':\n" .
              "        tmp += 'user';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "      case 'special':\n" .
              "        tmp += 'phone';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        tmp += 'mobile';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        tmp += 'tree';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        tmp += 'globe';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        tmp += 'leaf';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        tmp += 'wrench';\n" .
              "        break;\n" .
              "      default:\n" .
              "        tmp += 'exclamation-triangle';\n" .
              "        break;\n" .
              "    }\n" .
              "    data.result[x][4] = tmp + '\"></i> ' + data.result[x][4].number;\n" .
              "    total_time += parseInt ( data.result[x][5]) || 0;\n" .
              "    data.result[x][5] = format_secs_to_string ( data.result[x][5]);\n" .
              "    switch ( data.result[x][7])\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "        data.result[x][7] = '" . __ ( "Extension") . "';\n" .
              "        break;\n" .
              "      case 'inbound':\n" .
              "        data.result[x][7] = '" . __ ( "Received") . "';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "        data.result[x][7] = '" . __ ( "Landline") . "';\n" .
              "        break;\n" .
              "      case 'special':\n" .
              "        data.result[x][7] = '" . __ ( "Special") . "';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        data.result[x][7] = '" . __ ( "Mobile") . "';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        data.result[x][7] = '" . __ ( "Interstate") . "';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        data.result[x][7] = '" . __ ( "International") . "';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        data.result[x][7] = '" . __ ( "Toll free") . "';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        data.result[x][7] = '" . __ ( "Services") . "';\n" .
              "        break;\n" .
              "      default:\n" .
              "        data.result[x][7] = '" . __ ( "Unknown") . "';\n" .
              "        break;\n" .
              "    }\n" .
              "    total_cost += parseFloat ( data.result[x][8].replace ( '.', '').replace ( ',', '.'));\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + data.result[x][11].qos.lossrx + '% " . __ ( "rx") . " / ' + data.result[x][11].qos.losstx + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + data.result[x][11].qos.jitterrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.jittertx + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + data.result[x][11].qos.latencyrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.latencytx + ' ms " . __ ( "tx") . "\">';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '<font color=\"' + data.result[x][11].qualityColor + '\">';\n" .
              "    }\n" .
              "    tmp += data.result[x][11].quality;\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '</font>';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '</abbr>';\n" .
              "    }\n" .
              "    data.result[x][11] = tmp;\n" .
              "    $(table.row.add ( data.result[x]).node ()).addClass ( data.result[x][14]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( '<i class=\"fa fa-cloud\"></i> ' + sprintf ( total_calls != 1 ? '" . __ ( "%s calls") . "' : '" . __ ( "%s call") . "', total_calls));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 3).html ( number_format ( total_cost, 2, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "  $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 12, 13, 14, 15, 16 ]},\n" .
              "                { visible: false, targets: [ 0, 2, 8, 10, 14, 15, 16 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all', dataSort: 0},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 8},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 10},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'all', createdCell: function ( cell, cellData, rowData, rowIndex, colIndex) { $(cell).html ( '<button class=\"btn btn-xs btn-call_view btn-info ladda-button\" data-id=\"' + rowData[15] + '\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Details") . "\" role=\"button\"><i class=\"fa fa-search\"></i></button>'); }},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
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
              "$('#report').on ( 'click', 'button.btn-call_view', function ( event) { event && event.preventDefault (); $('#call_view').trigger ( 'show', { button: this, id: $(this).data ( 'id')}); });\n" .
              "$('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
              "  if ( $('#start').val () == '')\n" .
              "  {\n" .
              "    $('#start').alerts ( 'add', { message: '" . __ ( "The start date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#end').val () == '')\n" .
              "  {\n" .
              "    $('#end').alerts ( 'add', { message: '" . __ ( "The finish date is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#gateway').val () == '')\n" .
              "  {\n" .
              "    $('#gateway').alerts ( 'add', { message: '" . __ ( "The gateway is required.") . "'});\n" .
              "  }\n" .
              "  if ( $('#start').val () == '' || $('#end').val () == '' || $('#gateway').val () == '')\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/made/gateway/' + $('#gateway').val (), 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n" .
              "$('#gateway').focus ();\n");

  return $output;
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

  /**
   * Add page CSS requirements
   */
  sys_addcss ( array ( "name" => "select2", "src" => "/vendors/select2/dist/css/select2.css", "dep" => array ( "bootstrap", "AdminLTE")));
  sys_addcss ( array ( "name" => "select2-bootstrap-theme", "src" => "/vendors/select2-bootstrap-theme/dist/select2-bootstrap.css", "dep" => array ( "bootstrap", "select2")));
  sys_addcss ( array ( "name" => "dataTables", "src" => "/vendors/datatables/media/css/dataTables.bootstrap.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "buttons", "src" => "/vendors/buttons/css/buttons.bootstrap.css", "dep" => array ()));
  sys_addcss ( array ( "name" => "dataTables-buttons", "src" => "/vendors/buttons/css/buttons.dataTables.css", "dep" => array ( "buttons", "dataTables")));
  sys_addcss ( array ( "name" => "dataTables-responsive", "src" => "/vendors/responsive/css/responsive.bootstrap.css", "dep" => array ( "dataTables")));
  sys_addcss ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css", "dep" => array ( "bootstrap")));
  sys_addcss ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/css/styles.css", "dep" => array ()));

  /**
   * Add page JavaScript requirements
   */
  sys_addjs ( array ( "name" => "select2", "src" => "/vendors/select2/dist/js/select2.full.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "select2-locale", "src" => "/vendors/select2/dist/js/i18n/pt-BR.js", "dep" => array ( "select2")));
  sys_addjs ( array ( "name" => "jquery-mask", "src" => "/vendors/jQuery-Mask-Plugin/dist/jquery.mask.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "moment", "src" => "/vendors/moment/min/moment-with-locales.js", "dep" => array ()));
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
  sys_addjs ( array ( "name" => "datetimepicker", "src" => "/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.js", "dep" => array ( "moment", "bootstrap")));
  sys_addjs ( array ( "name" => "dataTables-processing", "src" => "/js/processing.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "system-dataTables", "src" => "/js/datatables.js", "dep" => array ( "dataTables", "dataTables-buttons", "stickytableheaders")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "UbaPlayer", "src" => "/vendors/UbaPlayer/dist/js/jquery.ubaplayer.js", "dep" => array ()));

  /**
   * Create page code
   */
  $output = "<div class=\"container\">\n";
  $output .= "  <form id=\"filters\">\n";
  $output .= "  <div class=\"col-xs-12\">\n";
  $output .= "    <div class=\"col-md-5\">\n";
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <div class=\"input-group date\">\n";
  $output .= "          <input name=\"start\" id=\"start\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Start") . "\" maxlength=\"16\" />\n";
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
  $output .= "          <input name=\"end\" id=\"end\" type=\"text\" class=\"form-control\" placeholder=\"" . __ ( "Finish") . "\" maxlength=\"16\" />\n";
  $output .= "          <div class=\"input-group-btn\">\n";
  $output .= "            <button class=\"btn btn-default btn-calendar\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>\n";
  $output .= "            <button class=\"btn btn-default btn-clean\" type=\"button\"><i class=\"fa fa-times\"></i></button>\n";
  $output .= "          </div>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "    <button type=\"submit\" class=\"btn btn-default btn-update\"><span class=\"fa fa-search\"></span></button>\n";
  $output .= "  </div>\n";
  $output .= "  </form>\n";
  $output .= "</div>\n";
  $output .= "<table id=\"report\" class=\"table table-hover table-condensed table-report\" cellspacing=\"0\" width=\"100%\">\n";
  $output .= "  <thead>\n";
  $output .= "    <tr>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Date/Time") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Source") . "</th>\n";
  $output .= "      <th>" . __ ( "Destination") . "</th>\n";
  $output .= "      <th>" . __ ( "Duration ([[DD:]HH:]MM:SS)") . "</th>\n";
  $output .= "      <th>" . __ ( "Result") . "</th>\n";
  $output .= "      <th>" . __ ( "Type") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Cost") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th>" . __ ( "Quality") . "</th>\n";
  $output .= "      <th>" . __ ( "Observations") . "</th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "      <th></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </thead>\n";
  $output .= "  <tbody>\n";
  $output .= "  </tbody>\n";
  $output .= "  <tfoot>\n";
  $output .= "    <tr>\n";
  $output .= "      <th colspan=\"2\">" . __ ( "Totals") . "</th>\n";
  $output .= "      <th colspan=\"2\"></th>\n";
  $output .= "      <th colspan=\"3\"></th>\n";
  $output .= "      <th colspan=\"7\"></th>\n";
  $output .= "    </tr>\n";
  $output .= "  </tfoot>\n";
  $output .= "</table>\n";

  /**
   * Call details modal
   */
  $output .= "<div id=\"call_view\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"call_view\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog modal-lg\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Call details") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\">\n";
  $output .= "        <form class=\"form-horizontal\" id=\"call_view_form\">\n";

  // Add tabs
  $output .= "        <ul class=\"nav nav-tabs\" role=\"tablist\">\n";
  $output .= "          <li role=\"presentation\" class=\"active\"><a class=\"nav-tablink\" href=\"#c_tab_basic\">" . __ ( "Basic") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_qos\">" . __ ( "QOS") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_siptrace\">" . __ ( "Flow") . "</a></li>\n";
  $output .= "          <li role=\"presentation\"><a class=\"nav-tablink\" href=\"#c_tab_download\">" . __ ( "Download") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "        <div class=\"tab-content\"><br />\n";

  // Basic informations tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"c_tab_basic\">\n";

  // Add server
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_server\" class=\"control-label col-xs-2\">" . __ ( "Server") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"server\" id=\"call_view_server\" class=\"form-control\" data-placeholder=\"" . __ ( "Server") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add source number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_source\" class=\"control-label col-xs-2\">" . __ ( "Source") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"source\" class=\"form-control\" id=\"call_view_source\" placeholder=\"" . __ ( "Source number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add destination number
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_destination\" class=\"control-label col-xs-2\">" . __ ( "Destination") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"destination\" class=\"form-control\" id=\"call_view_destination\" placeholder=\"" . __ ( "Destination number") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add date and time
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_datetime\" class=\"control-label col-xs-2\">" . __ ( "Date/Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"datetime\" class=\"form-control\" id=\"call_view_datetime\" placeholder=\"" . __ ( "Date and time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_duration\" class=\"control-label col-xs-2\">" . __ ( "Duration") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"duration\" class=\"form-control\" id=\"call_view_duration\" placeholder=\"" . __ ( "Call duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add billing duration
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_billsec\" class=\"control-label col-xs-2\">" . __ ( "Billed") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"billsec\" class=\"form-control\" id=\"call_view_billsec\" placeholder=\"" . __ ( "Billing duration") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call result
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_disposition\" class=\"control-label col-xs-2\">" . __ ( "Result") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"disposition\" class=\"form-control\" id=\"call_view_disposition\" placeholder=\"" . __ ( "Result") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cost\" class=\"control-label col-xs-2\">" . __ ( "Cost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"cost\" class=\"form-control\" id=\"call_view_cost\" placeholder=\"" . __ ( "Cost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add call type
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_type\" class=\"control-label col-xs-2\">" . __ ( "Type") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"type\" id=\"call_view_type\" class=\"form-control\" data-placeholder=\"" . __ ( "Type") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add cost center
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_cc\" class=\"control-label col-xs-2\">" . __ ( "Cost center") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"cc\" id=\"call_view_cc\" class=\"form-control\" data-placeholder=\"" . __ ( "Cost center") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add gateway
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_gateway\" class=\"control-label col-xs-2\">" . __ ( "Gateway") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <select name=\"gateway\" id=\"call_view_gateway\" class=\"form-control\" data-placeholder=\"" . __ ( "Gateway") . "\" disabled=\"disabled\"></select>\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // QOS tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_qos\">\n";

  // Add caller SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_ssrc\" class=\"control-label col-xs-2\">" . __ ( "Caller SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"ssrc\" class=\"form-control\" id=\"call_view_qos_ssrc\" placeholder=\"" . __ ( "Caller SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add called SSRC
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_themssrc\" class=\"control-label col-xs-2\">" . __ ( "Called SSRC") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"themssrc\" class=\"form-control\" id=\"call_view_qos_themssrc\" placeholder=\"" . __ ( "Called SSRC") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add received packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxcount\" class=\"control-label col-xs-2\">" . __ ( "RX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxcount\" class=\"form-control\" id=\"call_view_qos_rxcount\" placeholder=\"" . __ ( "Received packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packets counter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txcount\" class=\"control-label col-xs-2\">" . __ ( "TX packets") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txcount\" class=\"form-control\" id=\"call_view_qos_txcount\" placeholder=\"" . __ ( "Sent packets") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxlp\" class=\"control-label col-xs-2\">" . __ ( "RX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxlp\" class=\"form-control\" id=\"call_view_qos_rxlp\" placeholder=\"" . __ ( "Receive packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent packet lost
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txlp\" class=\"control-label col-xs-2\">" . __ ( "TX packet lost") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txlp\" class=\"form-control\" id=\"call_view_qos_txlp\" placeholder=\"" . __ ( "Sent packet lost") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add receive jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rxjitter\" class=\"control-label col-xs-2\">" . __ ( "RX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rxjitter\" class=\"form-control\" id=\"call_view_qos_rxjitter\" placeholder=\"" . __ ( "Receive jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add sent jitter
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_txjitter\" class=\"control-label col-xs-2\">" . __ ( "TX jitter") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"txjitter\" class=\"form-control\" id=\"call_view_qos_txjitter\" placeholder=\"" . __ ( "Sent jitter") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";

  // Add RTT
  $output .= "            <div class=\"form-group\">\n";
  $output .= "              <label for=\"call_view_qos_rtt\" class=\"control-label col-xs-2\">" . __ ( "Round Trip Time") . "</label>\n";
  $output .= "              <div class=\"col-xs-10\">\n";
  $output .= "                <input type=\"text\" name=\"rtt\" class=\"form-control\" id=\"call_view_qos_rtt\" placeholder=\"" . __ ( "Round Trip Time") . "\" disabled=\"disabled\" />\n";
  $output .= "              </div>\n";
  $output .= "            </div>\n";
  $output .= "          </div>\n";

  // Call flow tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_siptrace\">\n";
  $output .= "            <div class=\"diagram\"></div>\n";
  $output .= "          </div>\n";

  // Download tab
  $output .= "          <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"c_tab_download\">\n";
  $output .= "            <button type=\"button\" class=\"btn btn-primary\" data-type=\"pcap\" id=\"call_view_pcap\"><span class=\"fa fa-paperclip\" aria-hidden=\"true\"></span> " . __ ( "PCAP") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-success\" data-type=\"text\" id=\"call_view_text\"><span class=\"fa fa-copy\" aria-hidden=\"true\"></span> " . __ ( "Text") . "</button>\n";
  $output .= "            <button type=\"button\" class=\"btn btn-danger\" data-type=\"audio\" id=\"call_view_audio\"><span class=\"fa fa-headphones\" aria-hidden=\"true\"></span> " . __ ( "Audio") . "</button>\n";
  $output .= "          </div>\n";

  // Finish modal
  $output .= "        </div>\n";
  $output .= "        </form>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Close") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add report table JavaScript code
   */
  sys_addjs ( "$('#call_view_server').select2 ();\n" .
              "$('#call_view_type').select2 ();\n" .
              "$('#call_view_cc').select2 ();\n" .
              "$('#call_view_gateway').select2 ();\n" .
              "$('#start,#end').mask ( '00/00/0000 00:00');\n" .
              "$('#call_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#call_view_server').find ( 'option').remove ();\n" .
              "  if ( data.server != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.server + '\" selected=\"selected\">' + data.serverdesc + '</option>').appendTo ( $('#call_view_server'));\n" .
              "    $('#call_view_server').val ( data.server).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_datetime').val ( data.date);\n" .
              "  $('#call_view_duration').val ( data.duration);\n" .
              "  $('#call_view_billsec').val ( data.billsec);\n" .
              "  $('#call_view_disposition').val ( data.disposition);\n" .
              "  $('#call_view_source').val ( data.src);\n" .
              "  $('#call_view_destination').val ( data.dst);\n" .
              "  $('#call_view_cost').val ( data.value);\n" .
              "  $('#call_view_type').find ( 'option').remove ();\n" .
              "  if ( data.type != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.type + '\" selected=\"selected\">' + data.typedesc + '</option>').appendTo ( $('#call_view_type'));\n" .
              "    $('#call_view_type').val ( data.type).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_cc').find ( 'option').remove ();\n" .
              "  if ( data.cc != null)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.cc + '\" selected=\"selected\">' + data.ccdesc + '</option>').appendTo ( $('#call_view_cc'));\n" .
              "    $('#call_view_cc').val ( data.cc).trigger ( 'change');\n" .
              "  }\n" .
              "  $('#call_view_gateway').find ( 'option').remove ();\n" .
              "  if ( data.gw != 0)\n" .
              "  {\n" .
              "    $('<option value=\"' + data.gw + '\" selected=\"selected\">' + data.gwdesc + '</option>').appendTo ( $('#call_view_gateway'));\n" .
              "    $('#call_view_gateway').val ( data.gw).trigger ( 'change');\n" .
              "  }\n" .
              "  if ( data.QOSa.ssrc)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('#call_view_qos_ssrc').val ( data.QOSa.ssrc);\n" .
              "    $('#call_view_qos_themssrc').val ( data.QOSa.themssrc);\n" .
              "    $('#call_view_qos_rxlp').val ( data.QOSa.lp);\n" .
              "    $('#call_view_qos_rxjitter').val ( data.QOSa.rxjitter);\n" .
              "    $('#call_view_qos_rxcount').val ( data.QOSa.rxcount);\n" .
              "    $('#call_view_qos_txjitter').val ( data.QOSa.txjitter);\n" .
              "    $('#call_view_qos_txcount').val ( data.QOSa.txcount);\n" .
              "    $('#call_view_qos_txlp').val ( data.QOSa.rlp);\n" .
              "    $('#call_view_qos_rtt').val ( data.QOSa.rtt);\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_qos\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.siptrace.diagram)\n" .
              "  {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().removeClass ( 'disabled');\n" .
              "    $('.diagram').html ( data.siptrace.diagram);\n" .
              "    $('#call_view_pcap').data ( 'content', data.siptrace.pcap).data ( 'filename', 'voipdomain-' + data.SIPID + '.pcap');\n" .
              "    $('#call_view_text').data ( 'content', data.siptrace.text).data ( 'filename', 'voipdomain-' + data.SIPID + '.txt');\n" .
              "  } else {\n" .
              "    $('a[href=\"#c_tab_siptrace\"]').parent ().addClass ( 'disabled');\n" .
              "    $('a[href=\"#c_tab_download\"]').parent ().addClass ( 'disabled');\n" .
              "  }\n" .
              "  if ( data.monitor)\n" .
              "  {\n" .
              "    $('#call_view_audio').data ( 'content', data.uniqueid).data ( 'filename', 'voipdomain-' + data.SIPID + '.mp3').data ( 'url', data.uniqueid).removeClass ( 'disabled');\n" .
              "  } else {\n" .
              "    $('#call_view_audio').addClass ( 'disabled');\n" .
              "  }\n" .
              "  $('#call_view').modal ( 'show');\n" .
              "});\n" .
              "$('#call_view').on ( 'show', function ( event, params)\n" .
              "{\n" .
              "  var l = Ladda.create ( params.button);\n" .
              "  l.start ();\n" .
              "  var call = VoIP.rest ( '/calls/' + encodeURI ( params.id), 'GET');\n" .
              "  if ( call.API.status === 'ok')\n" .
              "  {\n" .
              "    $('#call_view_form').trigger ( 'fill', call.result);\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "Call view") . "', text: '" . __ ( "Error requesting call informations!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "}).on ( 'hidden.bs.modal', function ( e)\n" .
              "{\n" .
              "  $('#call_view a.nav-tablink').first ().tab ( 'show');\n" .
              "});\n" .
              "$('#call_view_pcap').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ atob ( $(this).data ( 'content'))], { type: 'application/vnd.tcpdump.pcap'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_text').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  saveAs ( new Blob ( [ $(this).data ( 'content')], { type: 'text/plain;charset=utf-8'}), $(this).data ( 'filename'));\n" .
              "});\n" .
              "$('#call_view_audio').on ( 'click', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  if ( $(this).hasClass ( 'disabled'))\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  $.fileDownload ( 'arquivos-audio.php?download=1&id=' + $(this).data ( 'url'));\n" .
              "});\n" .
              "$('#call_view a.nav-tablink[href=\"#c_tab_siptrace\"]').on ( 'shown.bs.tab', function ( e)\n" .
              "{\n" .
              "  if ( $('.diagram').has ( 'svg').length == 0)\n" .
              "  {\n" .
              "    $('.diagram').sequenceDiagram ( { theme: 'simple'});\n" .
              "    $('.diagram tspan').each ( function ()\n" .
              "    {\n" .
              "      $(this).html ( $(this).html ().replace ( '|', ':'));\n" .
              "    });\n" .
              "  }\n" .
              "});\n" .
              "$('#call_view').on ( 'click', '.nav-tabs a', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  e && e.stopPropagation ();\n" .
              "  $(this).tab ( 'show');\n" .
              "});\n" .
              "$('#report').on ( 'update', function ( event, data)\n" .
              "{\n" .
              "  var table = $(this).data ( 'dt');\n" .
              "  var total_calls = 0;\n" .
              "  var total_time = 0;\n" .
              "  var total_cost = 0;\n" .
              "  table.clear ();\n" .
              "  for ( var x in data.result)\n" .
              "  {\n" .
              "    total_calls++;\n" .
              "    data.result[x][3] = '<i aria-hidden=\"true\" class=\"fa fa-' + ( data.result[x][3].type == 'external' ? 'phone' : 'user') + '\"></i> ' + data.result[x][3].number;\n" .
              "    var tmp = '<i aria-hidden=\"true\" class=\"fa fa-';\n" .
              "    switch ( data.result[x][4].type)\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "      case 'inbound':\n" .
              "        tmp += 'user';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "      case 'special':\n" .
              "        tmp += 'phone';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        tmp += 'mobile';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        tmp += 'tree';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        tmp += 'globe';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        tmp += 'leaf';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        tmp += 'wrench';\n" .
              "        break;\n" .
              "      default:\n" .
              "        tmp += 'exclamation-triangle';\n" .
              "        break;\n" .
              "    }\n" .
              "    data.result[x][4] = tmp + '\"></i> ' + data.result[x][4].number;\n" .
              "    total_time += parseInt ( data.result[x][5]) || 0;\n" .
              "    data.result[x][5] = format_secs_to_string ( data.result[x][5]);\n" .
              "    switch ( data.result[x][7])\n" .
              "    {\n" .
              "      case 'extension':\n" .
              "        data.result[x][7] = '" . __ ( "Extension") . "';\n" .
              "        break;\n" .
              "      case 'inbound':\n" .
              "        data.result[x][7] = '" . __ ( "Received") . "';\n" .
              "        break;\n" .
              "      case 'landline':\n" .
              "        data.result[x][7] = '" . __ ( "Landline") . "';\n" .
              "        break;\n" .
              "      case 'special':\n" .
              "        data.result[x][7] = '" . __ ( "Special") . "';\n" .
              "        break;\n" .
              "      case 'mobile':\n" .
              "        data.result[x][7] = '" . __ ( "Mobile") . "';\n" .
              "        break;\n" .
              "      case 'interstate':\n" .
              "        data.result[x][7] = '" . __ ( "Interstate") . "';\n" .
              "        break;\n" .
              "      case 'international':\n" .
              "        data.result[x][7] = '" . __ ( "International") . "';\n" .
              "        break;\n" .
              "      case 'tollfree':\n" .
              "        data.result[x][7] = '" . __ ( "Toll free") . "';\n" .
              "        break;\n" .
              "      case 'services':\n" .
              "        data.result[x][7] = '" . __ ( "Services") . "';\n" .
              "        break;\n" .
              "      default:\n" .
              "        data.result[x][7] = '" . __ ( "Unknown") . "';\n" .
              "        break;\n" .
              "    }\n" .
              "    total_cost += parseFloat ( data.result[x][8].replace ( '.', '').replace ( ',', '.'));\n" .
              "    var tmp = '';\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '<abbr title=\"" . __ ( "Packet loss") . ": ' + data.result[x][11].qos.lossrx + '% " . __ ( "rx") . " / ' + data.result[x][11].qos.losstx + '% " . __ ( "tx") . "&#10;" . __ ( "Jitter") . ": ' + data.result[x][11].qos.jitterrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.jittertx + ' ms " . __ ( "tx") . "&#10;" . __ ( "Latency") . ": ' + data.result[x][11].qos.latencyrx + ' ms " . __ ( "rx") . " / ' + data.result[x][11].qos.latencytx + ' ms " . __ ( "tx") . "\">';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '<font color=\"' + data.result[x][11].qualityColor + '\">';\n" .
              "    }\n" .
              "    tmp += data.result[x][11].quality;\n" .
              "    if ( data.result[x][11].qualityColor != 'green')\n" .
              "    {\n" .
              "      tmp += '</font>';\n" .
              "    }\n" .
              "    if ( data.result[x][11].qos.hasOwnProperty ( 'lossrx'))\n" .
              "    {\n" .
              "      tmp += '</abbr>';\n" .
              "    }\n" .
              "    data.result[x][11] = tmp;\n" .
              "    $(table.row.add ( data.result[x]).node ()).addClass ( data.result[x][14]);\n" .
              "  }\n" .
              "  table.draw ();\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 1).html ( '<i class=\"fa fa-cloud\"></i> ' + sprintf ( total_calls != 1 ? '" . __ ( "%s calls") . "' : '" . __ ( "%s call") . "', total_calls));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 2).html ( format_secs_to_string ( total_time));\n" .
              "  $(table.table ().footer ()).find ( 'th').eq ( 3).html ( number_format ( total_cost, 2, '" . __ ( ".") . "', '" . __ ( ",") . "'));\n" .
              "  table.responsive.recalc ();\n" .
              "  table.processing ( false);\n" .
              "  $('#report div.ubaplayer').ubaPlayer ( { codecs: [{ name: 'MP3', codec: 'audio/mpeg;'}], flashAudioPlayerPath: '/vendors/UbaPlayer/dist/swf/player.swf'});\n" .
              "});\n" .
              "$('#report').data ( 'dt', $('#report').DataTable (\n" .
              "{\n" .
              "  columnDefs: [\n" .
              "                { orderable: false, targets: [ 12, 13, 14, 15, 16 ]},\n" .
              "                { visible: false, targets: [ 0, 2, 8, 10, 14, 15, 16 ]}\n" .
              "              ],\n" .
              "  columns: [\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export all', dataSort: 0},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'export min-tablet-l'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 8},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'export min-tablet-l', dataSort: 10},\n" .
              "             { class: 'export all'},\n" .
              "             { class: 'all', createdCell: function ( cell, cellData, rowData, rowIndex, colIndex) { $(cell).html ( '<button class=\"btn btn-xs btn-call_view btn-info ladda-button\" data-id=\"' + rowData[15] + '\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Details") . "\" role=\"button\"><i class=\"fa fa-search\"></i></button>'); }},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'},\n" .
              "             { class: 'never'}\n" .
              "           ],\n" .
              "  lengthMenu: [[ -1], [ '']],\n" .
              "  displayLength: -1,\n" .
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
              "$('#report').on ( 'click', 'button.btn-call_view', function ( event) { event && event.preventDefault (); $('#call_view').trigger ( 'show', { button: this, id: $(this).data ( 'id')}); });\n" .
              "$('#start').val ( moment ( moment().subtract ( 30, 'days')).format ( '" . __ ( "MM/DD/YYYY") . " 00:00:00'));\n" .
              "$('#end').val ( moment ().format ( '" . __ ( "MM/DD/YYYY") . " 23:59:59'));\n" .
              "$('#filters').on ( 'submit', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  $('#filters').alerts ( 'clearAll');\n" .
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
              "  $.hashForm ( 'set', { form: '#filters'});\n" .
              "  $('#report').data ( 'dt').processing ( true);\n" .
              "  $('#report').trigger ( 'update', VoIP.rest ( '/reports/made/all', 'GET', { start: $('#start').val (), end: $('#end').val ()}));\n" .
              "});\n" .
              "$.hashForm ( 'check', { onfill: function () { $('#filters').trigger ( 'submit'); }});\n" .
              "$('#end').focus ();\n");

  return $output;
}
?>
