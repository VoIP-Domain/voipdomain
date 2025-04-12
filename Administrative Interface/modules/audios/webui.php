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
 * VoIP Domain audio module WebUI. This module add the resource to manipulate
 * audio files that will be used at many resources of whole system.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Audio
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_path ( "/audios", "audios_search_page");
framework_add_hook ( "audios_search_page", "audios_search_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/audios/add", "audios_add_page");
framework_add_hook ( "audios_add_page", "audios_add_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/audios/:id/clone", "audios_clone_function");
framework_add_hook ( "audios_clone_function", "audios_clone_function", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/audios/:id/view", "audios_view_page");
framework_add_hook ( "audios_view_page", "audios_view_page", IN_HOOK_INSERT_FIRST);

framework_add_path ( "/audios/:id/edit", "audios_edit_page");
framework_add_hook ( "audios_edit_page", "audios_edit_page", IN_HOOK_INSERT_FIRST);

/**
 * Function to generate the main audio page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_search_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Audio"));
  sys_set_subtitle ( __ ( "audio files"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Audio"))
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
  sys_addjs ( array ( "name" => "accent-neutralise", "src" => "/js/accent-neutralise.js", "dep" => array ( "dataTables")));
  sys_addjs ( array ( "name" => "mark", "src" => "/vendors/mark/dist/jquery.mark.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "dataTables-mark", "src" => "/vendors/datatables-mark/dist/datatables.mark.js", "dep" => array ( "mark")));
  sys_addjs ( array ( "name" => "stickytableheaders", "src" => "/vendors/StickyTableHeaders/js/jquery.stickytableheaders.js", "dep" => array ()));
  sys_addjs ( array ( "name" => "download", "src" => "/vendors/download/download.js", "dep" => array ()));

  /**
   * Audio search table
   */
  $output = "<table id=\"datatables\" class=\"table table-striped table-bordered\" cellspacing=\"0\" width=\"100%\"></table>\n";

  /**
   * Add audio remove modal code
   */
  $output .= "<div id=\"audio_delete\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"audio_delete\" aria-hidden=\"true\">\n";
  $output .= "  <div class=\"modal-dialog\">\n";
  $output .= "    <div class=\"modal-content\">\n";
  $output .= "      <div class=\"modal-header\">\n";
  $output .= "        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n";
  $output .= "        <h3 class=\"modal-title\">" . __ ( "Remove audio file") . "</h3>\n";
  $output .= "      </div>\n";
  $output .= "      <div class=\"modal-body\"><p>" . sprintf ( __ ( "Are sure you want to remove the audio %s (%s)?"), "<span id=\"audio_delete_description\"></span>", "<span id=\"audio_delete_filename\"></span>") . "</p><input type=\"hidden\" id=\"audio_delete_id\" value=\"\"></div>\n";
  $output .= "      <div class=\"modal-footer\">\n";
  $output .= "        <button class=\"btn\" data-dismiss=\"modal\">" . __ ( "Cancel") . "</button>\n";
  $output .= "        <button class=\"btn btn-primary del ladda-button\" data-style=\"expand-left\">" . __ ( "Remove") . "</button>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>\n";

  /**
   * Add search table JavaScript code
   */
  sys_addjs ( "$('#datatables').data ( 'dt', $('#datatables').DataTable (\n" .
              "{\n" .
              "  columns: [\n" .
              "             { data: 'Description', title: '" . __ ( "Description") . "', width: '50%', class: 'export all'},\n" .
              "             { data: 'Filename', title: '" . __ ( "Filename") . "', width: '30%', class: 'export min-tablet-l'},\n" .
              "             { data: 'Uses', title: '" . __ ( "Uses") . "', width: '10%', class: 'export min-tablet-l'},\n" .
              "             { data: 'NULL', title: '" . __ ( "Actions") . "', width: '10%', class: 'all', render: function ( data, type, row, meta) { return '<span class=\"btn-group\"><a class=\"btn btn-xs btn-info\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "View") . "\" role=\"button\" title=\"\" href=\"/audios/' + row.ID + '/view\"><i class=\"fa fa-search\"></i></a><a class=\"btn btn-xs btn-warning\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Edit") . "\" role=\"button\" title=\"\" href=\"/audios/' + row.ID + '/edit\"><i class=\"fa fa-pencil-alt\"></i></a><a class=\"btn btn-xs btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Clone") . "\" role=\"button\" title=\"\" href=\"/audios/' + row.ID + '/clone\" data-nohistory=\"nohistory\"><i class=\"fa fa-clone\"></i></a><button class=\"btn btn-xs btn-success btn-play ladda-button\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Listen") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\"><i class=\"fa fa-play\"></i></button><button class=\"btn btn-xs btn-default ladda-button\" data-style=\"zoom-in\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Download") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\"><i class=\"fa fa-download\"></i></button><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . __ ( "Remove") . "\" role=\"button\" title=\"\" data-id=\"' + row.ID + '\" data-description=\"' + row.Description + '\" data-filename=\"' + row.Filename + '\"' + ( row.Uses != 0 ? ' disabled=\"disabled\"' : '') + '><i class=\"fa fa-times\"></i></button></span>'; }, orderable: false, searchable: false}\n" .
              "           ]\n" .
              "}));\n" .
              "VoIP.dataTablesUpdate ( { path: '/audios', fields: 'ID,Description,Filename,Uses,NULL'}, $('#datatables').data ( 'dt'));\n" .
              "$('#addbutton').html ( '<a class=\"btn btn-success\" href=\"/audios/add\"><i class=\"fa fa-asterisk\"></i> " . __ ( "New") . "</a>').css ( 'margin-right', '5px');\n" .
              "$('#datatables').on ( 'click', 'a.btn-primary', function ( e)\n" .
              "{\n" .
              "  if ( $(this).attr ( 'disabled') == 'disabled')\n" .
              "  {\n" .
              "    e && e.preventDefault ();\n" .
              "    e && e.stopPropagation ();\n" .
              "  }\n" .
              "});\n" .
              "$('#datatables').on ( 'click', 'button.btn-danger', function ( e)\n" .
              "{\n" .
              "  e && e.preventDefault ();\n" .
              "  $('#audio_delete_id').data ( 'dtrow', $('#datatables').data ( 'dt').rows ( { filter: 'applied'}).row ( $(this).parents ( 'tr').get ( 0)));\n" .
              "  $('#audio_delete button.del').prop ( 'disabled', false);\n" .
              "  $('#audio_delete_id').val ( $(this).data ( 'id'));\n" .
              "  $('#audio_delete_description').html ( $(this).data ( 'description'));\n" .
              "  $('#audio_delete_filename').html ( $(this).data ( 'filename'));\n" .
              "  $('#audio_delete').modal ( 'show');\n" .
              "});\n" .
              "$('#datatables').on ( 'click', 'button.btn-default', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/audios/' + encodeURIComponent ( $(this).data ( 'id')) + '/download', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    download ( atob ( data.Filecontent), data.Filename, data.Mimetype);\n" .
              "    new PNotify ( { title: '" . __ ( "Audio file download") . "', text: '" . __ ( "Audio file downloaded successfully!") . "', type: 'success'});\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Audio file download") . "', text: '" . __ ( "Error downloading audio file!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n" .
              "$('#datatables').on ( 'click', 'button.btn-play', function ( event)\n" .
              "{\n" .
              "  if ( $(this).find ( 'audio').length == 0)\n" .
              "  {\n" .
              "    var l = Ladda.create ( this);\n" .
              "    l.start ();\n" .
              "    var that = this;\n" .
              "    VoIP.rest ( '/audios/' + encodeURIComponent ( $(this).data ( 'id')) + '/download', 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "    {\n" .
              "      var audio = atob ( data.Filecontent);\n" .
              "      var audioArray = new Uint8Array ( new ArrayBuffer ( audio.length));\n" .
              "      for ( let x = 0; x < audio.length; x++)\n" .
              "      {\n" .
              "        audioArray[x] = audio.charCodeAt ( x);\n" .
              "      }\n" .
              "      var player = new Audio ();\n" .
              "      player.load ();\n" .
              "      player.src = URL.createObjectURL ( new Blob ( [audioArray], { type: data.Mimetype}));\n" .
              "      $(player).on ( 'ended', function ()\n" .
              "      {\n" .
              "        $(that).find ( 'i').removeClass ( 'fa-pause').addClass ( 'fa-play');\n" .
              "      }).appendTo ( that);\n" .
              "      player.play ();\n" .
              "      $(that).find ( 'i').removeClass ( 'fa-play').addClass ( 'fa-pause');\n" .
              "      new PNotify ( { title: '" . __ ( "Audio file play") . "', text: '" . __ ( "Audio file downloaded successfully!") . "', type: 'success'});\n" .
              "    }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "    {\n" .
              "      new PNotify ( { title: '" . __ ( "Audio file play") . "', text: '" . __ ( "Error downloading audio file!") . "', type: 'error'});\n" .
              "    }).always ( function ()\n" .
              "    {\n" .
              "      l.stop ();\n" .
              "    });\n" .
              "    return;\n" .
              "  }\n" .
              "  var player = $(this).find ( 'audio')[0];\n" .
              "  if ( player.paused)\n" .
              "  {\n" .
              "    player.play ();\n" .
              "    $(this).find ( 'i').removeClass ( 'fa-play').addClass ( 'fa-pause');\n" .
              "  } else {\n" .
	            "    player.pause ();\n" .
              "    $(this).find ( 'i').removeClass ( 'fa-pause').addClass ( 'fa-play');\n" .
              "  }\n" .
              "});\n" .
              "$('#audio_delete button.del').on ( 'click', function ( event)\n" .
              "{\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  VoIP.rest ( '/audios/' + encodeURIComponent ( $('#audio_delete_id').val ()), 'DELETE').done ( function ( data, textStatus, jqXHR)\n" .
              "  {\n" .
              "    $('#audio_delete').modal ( 'hide');\n" .
              "    new PNotify ( { title: '" . __ ( "Audio file removal") . "', text: '" . __ ( "Audio file removed successfully!") . "', type: 'success'});\n" .
              "    $('#audio_delete_id').data ( 'dtrow').remove ().draw ();\n" .
              "  }).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Audio file removal") . "', text: '" . __ ( "Error removing audio file!") . "', type: 'error'});\n" .
              "  }).always ( function ()\n" .
              "  {\n" .
              "    l.stop ();\n" .
              "  });\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the audio add page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_add_page ( $buffer, $parameters)
{
  /**
   * Set page title
   */
  sys_set_title ( __ ( "Audio"));
  sys_set_subtitle ( __ ( "audio file addition"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Audio"), "link" => "/audios"),
    2 => array ( "title" => __ ( "Add"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"audio_add_form\">\n";

  // Add audio description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"audio_add_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"audio_add_description\" placeholder=\"" . __ ( "Audio description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add audio file field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"audio_add_file\" class=\"control-label col-xs-2\">" . __ ( "File") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group\">\n";
  $output .= "        <label for=\"audio_add_file\" class=\"input-group-btn\"><span class=\"btn btn-primary\">" . __ ( "Choose file") . "</span></label>\n";
  $output .= "        <input type=\"file\" name=\"File\" id=\"audio_add_file\" style=\"display: none\" accept=\"audio/x-wav,audio/mpeg\" />\n";
  $output .= "        <input type=\"text\" name=\"Filename\" class=\"form-control\" id=\"audio_add_filename\" placeholder=\"" . __ ( "No file selected.") . "\" readonly=\"readonly\" />\n";
  $output .= "        <input type=\"hidden\" name=\"Filecontent\" id=\"audio_add_filecontent\" />\n";
  $output .= "        <span class=\"input-group-btn\"><span class=\"btn btn-default\" id=\"audio_add_remove\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Clear file") . "\"><i class=\"fa fa-times\"></i></span></span>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/audios\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <div class=\"btn-group\">\n";
  $output .= "        <button class=\"btn btn-primary add ladda-button\" data-style=\"expand-left\">" . __ ( "Add") . "</button>\n";
  $output .= "        <button class=\"btn btn-primary dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">\n";
  $output .= "          <span class=\"caret\"><span>\n";
  $output .= "          <span class=\"sr-only\">" . __ ( "Toggle options") . "</span>\n";
  $output .= "        </button>\n";
  $output .= "        <ul class=\"dropdown-menu\">\n";
  $output .= "          <li><a class=\"dropdown-item add-new\" href=\"#\">" . __ ( "and create new") . "</a></li>\n";
  $output .= "          <li><a class=\"dropdown-item add-duplicate\" href=\"#\">" . __ ( "and duplicate content") . "</a></li>\n";
  $output .= "        </ul>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add add form JavaScript code
   */
  sys_addjs ( "$('#audio_add_file').on ( 'change', function ( e)\n" .
              "{\n" .
              "  var filename = $(this).val ();\n" .
              "  if ( filename.substr ( filename.lastIndexOf ( '.') + 1).toLowerCase () != 'wav' && filename.substr ( filename.lastIndexOf ( '.') + 1).toLowerCase () != 'mp3')\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Audio file addition") . "', text: '" . __ ( "Selected file extension not valid. Only allowed 'wav' or 'mp3'.") . "', type: 'error'});\n" .
              "    $(this).val ( '');\n" .
              "    return false;\n" .
              "  }\n" .
              "  $('#audio_add_filename').val ( filename.lastIndexOf ( '\\\\') ? filename.substring ( filename.lastIndexOf ( '\\\\') + 1) : filename);\n" .
              "  var fr = new FileReader ();\n" .
              "  fr.onload = function ()\n" .
              "  {\n" .
              "    $('#audio_add_filecontent').val ( btoa ( fr.result));\n" .
              "  }\n" .
              "  fr.readAsBinaryString ( this.files[0]);\n" .
              "});\n" .
              "$('#audio_add_remove').on ( 'click', function ( e)\n" .
              "{\n" .
              "  $('#audio_add_file,#audio_add_filename,#audio_add_filecontent').val ( '');\n" .
              "});\n" .
              "$('#audio_add_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    focus: $('#audio_add_description'),\n" .
              "    URL: '/audios',\n" .
              "    method: 'POST',\n" .
              "    button: $('button.add'),\n" .
              "    title: '" . __ ( "Audio file addition") . "',\n" .
              "    fail: '" . __ ( "Error adding audio!") . "',\n" .
              "    success: '" . __ ( "Audio file added sucessfully!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/audios', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}

/**
 * Function to generate the audio clone function code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_clone_function ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set module type to function
   */
  $_in["page"]["type"] = "function";

  /**
   * Add clone form JavaScript code
   */
  sys_addjs ( "VoIP.rest ( '/audios/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  VoIP.path.call ( '/audios/add', true, function ()\n" .
              "  {\n" .
              "    $('#audio_add_description').val ( data.Description);\n" .
              "    $('#audio_add_filename').val ( data.Filename);\n" .
              "  });\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Audio cloning") . "', text: '" . __ ( "Error requesting audio data!") . "', type: 'error'});\n" .
              "});\n");
}

/**
 * Function to generate the audio view page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_view_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Audio"));
  sys_set_subtitle ( __ ( "audio file view"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Audio"), "link" => "/audios"),
    2 => array ( "title" => __ ( "View"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"audio_view_form\">\n";

  // Add audio description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"audio_view_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"audio_view_description\" placeholder=\"" . __ ( "Audio description") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add audio filename field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"audio_view_filename\" class=\"control-label col-xs-2\">" . __ ( "File") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Filename\" class=\"form-control\" id=\"audio_view_filename\" placeholder=\"" . __ ( "Audio filename") . "\" disabled=\"disabled\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/audios\" alt=\"\">" . __ ( "Return") . "</a>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add view form JavaScript code
   */
  sys_addjs ( "$('#audio_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#audio_view_description').val ( data.Description);\n" .
              "  $('#audio_view_filename').val ( data.Filename);\n" .
              "});\n" .
              "VoIP.rest ( '/audios/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#audio_view_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Audio file view") . "', text: '" . __ ( "Error viewing audio file!") . "', type: 'error'});\n" .
              "});\n");

  return $output;
}

/**
 * Function to generate the audio edit page code.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function audios_edit_page ( $buffer, $parameters)
{
  global $_in;

  /**
   * Set page title
   */
  sys_set_title ( __ ( "Audio"));
  sys_set_subtitle ( __ ( "audio editing"));
  sys_set_path ( array (
    1 => array ( "title" => __ ( "Audio"), "link" => "/audios"),
    2 => array ( "title" => __ ( "Edit"))
  ));

  /**
   * Create page code
   */
  $output = "<form class=\"form-horizontal\" id=\"audio_edit_form\">\n";

  // Add audio description field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"audio_edit_description\" class=\"control-label col-xs-2\">" . __ ( "Description") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <input type=\"text\" name=\"Description\" class=\"form-control\" id=\"audio_edit_description\" placeholder=\"" . __ ( "Audio description") . "\" />\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Add audio file field
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <label for=\"audio_edit_file\" class=\"control-label col-xs-2\">" . __ ( "File") . "</label>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <div class=\"input-group\">\n";
  $output .= "        <label for=\"audio_edit_file\" class=\"input-group-btn\"><span class=\"btn btn-primary\">" . __ ( "Choose file") . "</span></label>\n";
  $output .= "        <input type=\"file\" name=\"File\" id=\"audio_edit_file\" style=\"display: none\" accept=\"audio/x-wav,audio/mpeg\" />\n";
  $output .= "        <input type=\"text\" name=\"Filename\" class=\"form-control\" id=\"audio_edit_filename\" placeholder=\"" . __ ( "No file selected.") . "\" readonly=\"readonly\" />\n";
  $output .= "        <input type=\"hidden\" name=\"Filecontent\" id=\"audio_edit_filecontent\" />\n";
  $output .= "        <span class=\"input-group-btn\"><span class=\"btn btn-default\" id=\"audio_edit_remove\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"" . __ ( "Clear file") . "\"><i class=\"fa fa-times\"></i></span></span>\n";
  $output .= "      </div>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Form buttons
  $output .= "  <div class=\"form-group\">\n";
  $output .= "    <div class=\"col-xs-2\"></div>\n";
  $output .= "    <div class=\"col-xs-10\">\n";
  $output .= "      <a class=\"btn btn-default\" href=\"/audios\" alt=\"\">" . __ ( "Cancel") . "</a>\n";
  $output .= "      <button class=\"btn btn-primary edit ladda-button\" data-style=\"expand-left\">" . __ ( "Change") . "</button>\n";
  $output .= "    </div>\n";
  $output .= "  </div>\n";

  // Finish form
  $output .= "</form>\n";

  /**
   * Add edit form JavaScript code
   */
  sys_addjs ( "$('#audio_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  $('#audio_edit_description').val ( data.Description);\n" .
              "  $('#audio_edit_filename').val ( data.Filename);\n" .
              "  $('#audio_edit_description').focus ();\n" .
              "});\n" .
              "VoIP.rest ( '/audios/' + encodeURIComponent ( VoIP.parameters.id), 'GET').done ( function ( data, textStatus, jqXHR)\n" .
              "{\n" .
              "  $('#audio_edit_form').trigger ( 'fill', data);\n" .
              "}).fail ( function ( jqXHR, textStatus, errorThrown)\n" .
              "{\n" .
              "  new PNotify ( { title: '" . __ ( "Audio file edition") . "', text: '" . __ ( "Error requesting audio file data!") . "', type: 'error'});\n" .
              "});\n" .
              "$('#audio_edit_file').on ( 'change', function ( e)\n" .
              "{\n" .
              "  var filename = $(this).val ();\n" .
              "  if ( filename.substr ( filename.lastIndexOf ( '.') + 1).toLowerCase () != 'wav' && filename.substr ( filename.lastIndexOf ( '.') + 1).toLowerCase () != 'mp3')\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "Audio file addition") . "', text: '" . __ ( "Selected file extension not valid. Only allowed 'wav' or 'mp3'.") . "', type: 'error'});\n" .
              "    $(this).val ( '');\n" .
              "    return false;\n" .
              "  }\n" .
              "  $('#audio_edit_filename').val ( filename.lastIndexOf ( '\\\\') ? filename.substring ( filename.lastIndexOf ( '\\\\') + 1) : filename);\n" .
              "  var fr = new FileReader ();\n" .
              "  fr.onload = function ()\n" .
              "  {\n" .
              "    $('#audio_edit_filecontent').val ( btoa ( fr.result));\n" .
              "  }\n" .
              "  fr.readAsBinaryString ( this.files[0]);\n" .
              "});\n" .
              "$('#audio_edit_remove').on ( 'click', function ( e)\n" .
              "{\n" .
              "  $('#audio_edit_file,#audio_edit_filename,#audio_edit_filecontent').val ( '');\n" .
              "});\n" .
              "$('#audio_edit_form').alerts ( 'form',\n" .
              "{\n" .
              "  form:\n" .
              "  {\n" .
              "    URL: '/audios/' + VoIP.parameters.id,\n" .
              "    method: 'PATCH',\n" .
              "    button: $('button.edit'),\n" .
              "    title: '" . __ ( "Audio file edition") . "',\n" .
              "    fail: '" . __ ( "Error changing audio file!") . "',\n" .
              "    success: '" . __ ( "Audio file sucessfully changed!") . "',\n" .
              "    onsuccess: function ()\n" .
              "               {\n" .
              "                 VoIP.path.call ( '/audios', true);\n" .
              "               }\n" .
              "  }\n" .
              "});");

  return $output;
}
?>
