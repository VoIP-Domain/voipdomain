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
 * VoIP Domain OpenID authentication module filters. This module add the filter
 * calls related to OpenID authentication queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Authentication OpenID
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights queued.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add OpenID authentication filters
 */
framework_add_filter ( "authentication_plugins", "authentication_openid_plugin");
framework_add_filter ( "authentication_subpages", "authentication_openid_subpage");

/**
 * Function to add OpenID authentication object information.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function authentication_openid_plugin ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( "OpenID" => array ( "Name" => "OpenID", "AuthenticationURL" => "/api/auth/openid", "Icon" => "openid")));
}

/**
 * Function to generate the OpenID authentication subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_openid_subpage ( $buffer, $parameters)
{
  /**
   * Add OpenID panel
   */
  $output = "";

  // Add OpenID authentication status field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_openid_status\" class=\"control-label col-xs-2\">" . __ ( "Status") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Plugin_OpenID_Status\" id=\"authentication_plugin_openid_status\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add OpenID authentication client ID field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_openid_client_id\" class=\"control-label col-xs-2\">" . __ ( "Client ID") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Plugin_OpenID_Client_ID\" id=\"authentication_plugin_openid_client_id\" value=\"\" placeholder=\"" . __ ( "OpenID Client ID") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add OpenID authentication client secret field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_openid_client_secret\" class=\"control-label col-xs-2\">" . __ ( "Client secret") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"password\" name=\"Plugin_OpenID_Client_Secret\" id=\"authentication_plugin_openid_client_secret\" value=\"\" placeholder=\"" . __ ( "OpenID Client Secret") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add OpenID authentication redirect URI field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_openid_redirect_uri\" class=\"control-label col-xs-2\">" . __ ( "Redirect URI") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Plugin_OpenID_Redirect_URI\" id=\"authentication_plugin_openid_redirect_uri\" value=\"\" placeholder=\"" . __ ( "OpenID Redirect URI (your system URI to /api/auth/openid)") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add OpenID server URI field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_openid_server_uri\" class=\"control-label col-xs-2\">" . __ ( "Server URI") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Plugin_OpenID_Server_URI\" id=\"authentication_plugin_openid_server_uri\" value=\"\" placeholder=\"" . __ ( "OpenID server base URI") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add OpenID realm field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_openid_realm\" class=\"control-label col-xs-2\">" . __ ( "Realm") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Plugin_OpenID_Realm\" id=\"authentication_plugin_openid_realm\" value=\"\" placeholder=\"" . __ ( "OpenID realm") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["openid"] = array ( "label" => __ ( "OpenID"), "html" => $output);

  /**
   * Add form JavaScript code
   */
  $buffer["js"]["onshow"]["openid"] = "  $('#authentication_plugin_openid_status').bootstrapToggle ( data.Plugins.OpenID.Status ? 'on' : 'off');\n" .
                                      "  $('#authentication_plugin_openid_client_id').val ( data.Plugins.OpenID.ClientID);\n" .
                                      "  $('#authentication_plugin_openid_client_secret').val ( data.Plugins.OpenID.ClientSecret);\n" .
                                      "  $('#authentication_plugin_openid_redirect_uri').val ( data.Plugins.OpenID.RedirectURI);\n" .
                                      "  $('#authentication_plugin_openid_server_uri').val ( data.Plugins.OpenID.ServerURI);\n" .
                                      "  $('#authentication_plugin_openid_realm').val ( data.Plugins.OpenID.Realm);\n";
  $buffer["js"]["onfilter"]["openid"] = "  formData.Plugins.OpenID = new Object ();\n" .
                                        "  formData.Plugins.OpenID.Status = $('#authentication_plugin_openid_status').prop ( 'checked');\n" .
                                        "  delete ( formData.Plugin_OpenID_Status);\n" .
                                        "  formData.Plugins.OpenID.ClientID = $('#authentication_plugin_openid_client_id').val ();\n" .
                                        "  delete ( formData.Plugin_OpenID_Client_ID);\n" .
                                        "  formData.Plugins.OpenID.ClientSecret = $('#authentication_plugin_openid_client_secret').val ();\n" .
                                        "  delete ( formData.Plugin_OpenID_Client_Secret);\n" .
                                        "  formData.Plugins.OpenID.RedirectURI = $('#authentication_plugin_openid_redirect_uri').val ();\n" .
                                        "  delete ( formData.Plugin_OpenID_Redirect_URI);\n" .
                                        "  formData.Plugins.OpenID.ServerURI = $('#authentication_plugin_openid_server_uri').val ();\n" .
                                        "  delete ( formData.Plugin_OpenID_Server_URI);\n" .
                                        "  formData.Plugins.OpenID.Realm = $('#authentication_plugin_openid_realm').val ();\n" .
                                        "  delete ( formData.Plugin_OpenID_Realm);\n";
  $buffer["js"]["init"][] = "$('#authentication_plugin_openid_status').on ( 'change', function ( e)\n" .
                            "                                                         {\n" .
                            "                                                           if ( ! $(this).prop ( 'checked'))\n" .
                            "                                                           {\n" .
                            "                                                             $('#authentication_plugin_openid_client_id,#authentication_plugin_openid_client_secret,#authentication_plugin_openid_redirect_uri,#authentication_plugin_openid_server_uri,#authentication_plugin_openid_realm').attr ( 'disabled', 'disabled');\n" .
                            "                                                           } else {\n" .
                            "                                                             $('#authentication_plugin_openid_client_id,#authentication_plugin_openid_client_secret,#authentication_plugin_openid_redirect_uri,#authentication_plugin_openid_server_uri,#authentication_plugin_openid_realm').removeAttr ( 'disabled');\n" .
                            "                                                             $('#authentication_plugin_openid_client_id').focus ();\n" .
                            "                                                           }\n" .
                            "                                                         });\n";

  return $buffer;
}
?>
