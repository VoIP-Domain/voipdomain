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
 * Plugin to add authentication page avatar, as user view and edit pages avatar
 * management.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage User Avatar
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add basic framework hooks, with the relative function.
 */
framework_add_hook ( "login_page_generate", "avatar_login");
framework_add_hook ( "users_view_page", "avatar_users_view_page");
framework_add_hook ( "users_edit_page", "avatar_users_edit_page");

/**
 * Function to generate the user view page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function avatar_users_view_page ( $buffer, $parameters)
{
  /**
   * Add extra CSS styling
   */
  sys_addcss ( ".profile-picture\n" .
               "{\n" .
               "  background-color: #ffffff;\n" .
               "  border: 1px solid #cccccc;\n" .
               "  box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.15);\n" .
               "  box-sizing: border-box;\n" .
               "  display: inline-block;\n" .
               "  width: 215px;\n" .
               "  height: 215px;\n" .
               "  padding: 4px;\n" .
               "}\n");

  /**
   * Add user avatar image
   */
  $buffer = substr_replace ( $buffer, "\n  <div>\n    <div class=\"col-lg-9 col-md-8 col-sm-7 col-xs-6\">", strpos ( $buffer, "\n", strpos ( $buffer, "<form id=\"user_view_form\"")), 0);
  $buffer = substr_replace ( $buffer, "\n    </div>\n    <div class=\"col-lg-3 col-md-4 col-sm-5 col-xs-6 center\">\n      <div class=\"profile-picture\" style=\"background: url('/img/avatars/profile-default.jpg')\"></div>\n  </div>", strrpos ( $buffer, "\n", strpos ( strrev ( $buffer), strrev ( "</form>")) * -1), 0);

  /**
   * Add javascript code
   */
  sys_addjs ( "$('#user_view_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  if ( data.avatarid != '')\n" .
              "  {\n" .
              "    $('.profile-picture').css ( 'background-image', 'url(/img/avatars/profile-' + data.avatarid + '.jpg)');\n" .
              "  }\n" .
              "});\n");

  return $buffer;
}

/**
 * Function to generate the user edit page code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function avatar_users_edit_page ( $buffer, $parameters)
{
  /**
   * Add extra CSS styling
   */
  sys_addcss ( ".profile-picture\n" .
               "{\n" .
               "  background-color: #ffffff;\n" .
               "  border: 1px solid #cccccc;\n" .
               "  box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.15);\n" .
               "  box-sizing: border-box;\n" .
               "  display: inline-block;\n" .
               "  width: 215px;\n" .
               "  height: 215px;\n" .
               "  padding: 4px;\n" .
               "}\n" .
               ".avatar-overlay\n" .
               "{\n" .
               "  background: rgba(0, 0, 0, .75);\n" .
               "  text-align: center;\n" .
               "  padding-top: 30px;\n" .
               "  opacity: 0;\n" .
               "  width: 100%;\n" .
               "  height: 100%;\n" .
               "  display: block;\n" .
               "  cursor: pointer;\n" .
               "  overflow: hidden;\n" .
               "  -webkit-transition: opacity .25s ease;\n" .
               "  -moz-transition: opacity .25s ease;\n" .
               "}\n" .
               ".profile-picture:hover .avatar-overlay\n" .
               "{\n" .
               "  opacity: 1;\n" .
               "}\n" .
               ".avatar-overlay-text\n" .
               "{\n" .
               "  font-family: Helvetica;\n" .
               "  font-weight: 500;\n" .
               "  color: rgba(255, 255, 255, .85);\n" .
               "  font-size: 48px;\n" .
               "}\n" .
               ".avatar-label\n" .
               "{\n" .
               "  width: 100%;\n" .
               "  display: block;\n" .
               "  margin-top: 8px;\n" .
               "}\n" .
               ".avatar-remove\n" .
               "{\n" .
               "  width: 215px;\n" .
               "}\n");

  /**
   * Add user avatar image
   */
  $buffer = substr_replace ( $buffer, "\n  <div>\n    <div class=\"col-lg-9 col-md-8 col-sm-7 col-xs-6\">", strpos ( $buffer, "\n", strpos ( $buffer, "<form id=\"user_edit_form\"")), 0);
  $buffer = substr_replace ( $buffer, "\n    </div>\n    <div class=\"col-lg-3 col-md-4 col-sm-5 col-xs-6 center\">\n      <div class=\"profile-picture\" style=\"background: url('/img/avatars/profile-default.jpg')\"><span class=\"avatar-overlay\"><span class=\"avatar-overlay-text\">" . __ ( "Change image") . "</span></span></div>\n    <button class=\"btn btn-warning avatar-remove ladda-button\" disabled=\"disabled\">" . __ ( "Remove avatar") . "</button>\n    </div>\n    <input type=\"file\" name=\"avatar\" id=\"user_edit_avatar\" class=\"hidden\" />\n  </div>", strrpos ( $buffer, "\n", strpos ( strrev ( $buffer), strrev ( "</form>")) * -1), 0);

  /**
   * Add javascript code
   */
  sys_addjs ( array ( "name" => "base64", "src" => "/vendors/jquery.base64/jquery.base64.js", "dep" => array ()));
  sys_addjs ( "$('#user_edit_form').on ( 'fill', function ( event, data)\n" .
              "{\n" .
              "  if ( data.avatarid != '')\n" .
              "  {\n" .
              "    $('.profile-picture').css ( 'background-image', 'url(/img/avatars/profile-' + data.avatarid + '.jpg)');\n" .
              "    $('.avatar-remove').removeAttr ( 'disabled');\n" .
              "  }\n" .
              "});\n" .
              "$('.profile-picture').on ( 'click', 'span.avatar-overlay', function ( event)\n" .
              "{\n" .
              "  $('#user_edit_avatar').trigger ( 'click');\n" .
              "});\n" .
              "$('#user_edit_avatar').on ( 'change', function ( event)\n" .
              "{\n" .
              "  var file = this.files[0];\n" .
              "  if ( file.name.length < 1)\n" .
              "  {\n" .
              "    return false;\n" .
              "  }\n" .
              "  if ( file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg')\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "User edition") . "', text: '" . __ ( "The selected file type is not valid.") . "', type: 'error'});\n" .
              "    return false;\n" .
              "  }\n" .
              "  if ( file.size > 1048576)\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "User edition") . "', text: '" . __ ( "The selected file size is too big.") . "', type: 'error'});\n" .
              "    return false;\n" .
              "  }\n" .
              "  $('span.avatar-overlay').css ( 'width', 0);\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var reader = new FileReader ();\n" .
              "  reader.readAsBinaryString ( file);\n" .
              "  reader.onload = function ( event)\n" .
              "  {\n" .
              "    var data = VoIP.rest ( '/users/avatar/' + VoIP.parameters.id, 'PATCH', { 'avatar': $.base64.encode ( event.target.result), 'type': file.type});\n" .
              "    if ( data.API.status == 'ok')\n" .
              "    {\n" .
              "      new PNotify ( { title: '" . __ ( "User edition") . "', text: '" . __ ( "Image changed sucessfully!") . "', type: 'success'});\n" .
              "      $('.profile-picture').css ( 'background-image', 'url(/img/avatars/profile-' + data.result.avatarid + '.jpg)');\n" .
              "      if ( VoIP.parameters.id == VoIP.getUID ())\n" .
              "      {\n" .
              "        $('aside .img-circle,header .user-image,header .img-circle').attr ( 'src', '/img/avatars/profile-' + data.result.avatarid + '.jpg');\n" .
              "      }\n" .
              "    } else {\n" .
              "      new PNotify ( { title: '" . __ ( "User edition") . "', text: '" . __ ( "Error changing image!") . "', type: 'error'});\n" .
              "    }\n" .
              "    l.stop ();\n" .
              "    $('span.avatar-overlay').css ( 'width', '100%');\n" .
              "  };\n" .
              "});\n" .
              "$('button.avatar-remove').on ( 'click', function ( event)\n" .
              "{\n" .
              "  event && event.preventDefault ();\n" .
              "  var l = Ladda.create ( this);\n" .
              "  l.start ();\n" .
              "  var data = VoIP.rest ( '/users/avatar/' + VoIP.parameters.id, 'DELETE', {});\n" .
              "  if ( data.API.status == 'ok')\n" .
              "  {\n" .
              "    new PNotify ( { title: '" . __ ( "User edition") . "', text: '" . __ ( "Image removed sucessfully!") . "', type: 'success'});\n" .
              "    $('.profile-picture').css ( 'background-image', 'url(/img/avatars/profile-default.jpg)');\n" .
              "    if ( VoIP.parameters.id == VoIP.getUID ())\n" .
              "    {\n" .
              "      $('aside .img-circle,header .user-image,header .img-circle').attr ( 'src', '/img/avatars/profile-default.jpg');\n" .
              "    }\n" .
              "  } else {\n" .
              "    new PNotify ( { title: '" . __ ( "User edition") . "', text: '" . __ ( "Error removing image!") . "', type: 'error'});\n" .
              "  }\n" .
              "  l.stop ();\n" .
              "});\n");

  return $buffer;
}

/**
 * Function to generate the system authentication page.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function avatar_login ( $buffer, $parameters)
{
  global $_in;

  /**
   * Add jQuery bindWithDelay dependency
   */
  $buffer = substr_replace ( $buffer, "\n<script type=\"text/javascript\" src=\"/vendors/jquery-bindwithdelay/bindWithDelay" . ( $_in["general"]["debug"] === false ? ".min" : "") . ".js\"></script>", strpos ( $buffer, "\n", strpos ( $buffer, "src=\"/vendors/jquery/jquery")), 0);

  /**
   * Add image at login form
   */
  $buffer = substr_replace ( $buffer, "\n    <img src=\"/img/avatars/profile-default.jpg\" alt=\"\" class=\"avatar\" />", strpos ( $buffer, "\n", strpos ( $buffer, "class=\"auth-block\"")), 0);

  /**
   * Add avatar login CSS code
   */
  $buffer = substr_replace ( $buffer, "\n" .
                                      "    .avatar\n" .
                                      "    {\n" .
                                      "      position: relative;\n" .
                                      "      left: 50%;\n" .
                                      "      border-radius: 50%;\n" .
                                      "      -webkit-border-radius: 50%;\n" .
                                      "      -o-border-radius: 50%;\n" .
                                      "      -moz-border-radius: 50%;\n" .
                                      "      border: 6px solid rgba(221, 218, 215, 0.23);\n" .
                                      "      width: 192px;\n" .
                                      "      height: 192px;\n" .
                                      "      margin-left: -96px;\n" .
                                      "      margin-top: -96px;\n" .
                                      "      margin-bottom: 15px;\n" .
                                      "    }\n" .
                                      "    @media (max-width: 1024px)\n" .
                                      "    {\n" .
                                      "      .avatar\n" .
                                      "      {\n" .
                                      "        width: 128px;\n" .
                                      "        height: 128px;\n" .
                                      "        margin-left: -64px;\n" .
                                      "        margin-top: -64px;\n" .
                                      "      }\n" .
                                      "    }\n" .
                                      "    @media (max-width: 640px)\n" .
                                      "    {\n" .
                                      "      .avatar\n" .
                                      "      {\n" .
                                      "        width: 96px;\n" .
                                      "        height: 96px;\n" .
                                      "        margin-left: -43px;\n" .
                                      "        margin-top: -53px;\n" .
                                      "      }\n" .
                                      "    }\n" .
                                      "    @media (max-width: 320px)\n" .
                                      "    {\n" .
                                      "      .avatar\n" .
                                      "      {\n" .
                                      "        width: 64px;\n" .
                                      "        height: 64px;\n" .
                                      "        margin-left: -32px;\n" .
                                      "        margin-top: -32px;\n" .
                                      "      }\n" .
                                      "    }", strpos ( $buffer, "\n", strpos ( $buffer, "style type=\"text/css\"")), 0);

  /**
   * Add avatar javascript code to login page
   */
  $buffer = substr_replace ( $buffer, "\n" .
                                      "    function checkAvatar ()\n" .
                                      "    {\n" .
                                      "      $.ajax (\n" .
                                      "      {\n" .
                                      "        type: 'GET',\n" .
                                      "        url: '/api/users/avatar/' + $('#user').val (),\n" .
                                      "        headers:\n" .
                                      "        {\n" .
                                      "          'X-INFramework': 'api',\n" .
                                      "          'X-HTTP-Method-Override': 'GET',\n" .
                                      "          'Accept': 'application/json'\n" .
                                      "        },\n" .
                                      "        contentType: 'application/json; charset=utf-8',\n" .
                                      "        dataType: 'json',\n" .
                                      "        success: function ( data)\n" .
                                      "                 {\n" .
                                      "                   if ( data.result == true)\n" .
                                      "                   {\n" .
                                      "                     var image = data.id;\n" .
                                      "                   } else {\n" .
                                      "                     var image = 'default';\n" .
                                      "                   }\n" .
                                      "                   if ( $('img.avatar').attr ( 'src') == '/img/avatars/profile-' + image + '.jpg')\n" .
                                      "                   {\n" .
                                      "                     return;\n" .
                                      "                   }\n" .
                                      "                   $('img.avatar').fadeTo ( 'slow', 0.3, function ()\n" .
                                      "                   {\n" .
                                      "                     $(this).attr ( 'src', '/img/avatars/profile-' + image + '.jpg');\n" .
                                      "                   }).fadeTo ( 'slow', 1);\n" .
                                      "                 }\n" .
                                      "      });\n" .
                                      "    }\n" .
                                      "    if ( $('#user').val () != '')\n" .
                                      "    {\n" .
                                      "      checkAvatar ();\n" .
                                      "    }\n" .
                                      "    $('#user').bindWithDelay ( 'keyup', checkAvatar, 250);", strrpos ( $buffer, "\n", strpos ( strrev ( $buffer), strrev ( "image = new Image")) * -1), 0);

  return $buffer;
}
?>
