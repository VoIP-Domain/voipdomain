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
 * VoIP Domain Facebook authentication module filters. This module add the filter
 * calls related to Facebook authentication queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Authentication Facebook
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights queued.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add Facebook authentication filters
 */
framework_add_filter ( "authentication_plugins", "authentication_facebook_plugin");
framework_add_filter ( "authentication_subpages", "authentication_facebook_subpage");

/**
 * Function to add Facebook authentication object information.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function authentication_facebook_plugin ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( "Facebook" => array ( "Name" => "Facebook", "AuthenticationURL" => "/api/auth/facebook", "Icon" => "facebook")));
}

/**
 * Function to generate the Facebook authentication subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_facebook_subpage ( $buffer, $parameters)
{
  /**
   * Add Facebook panel
   */
  $output = "";

  // Add Facebook authentication status field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_facebook_status\" class=\"control-label col-xs-2\">" . __ ( "Status") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Plugin_Facebook_Status\" id=\"authentication_plugin_facebook_status\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add Facebook authentication client ID field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_facebook_client_id\" class=\"control-label col-xs-2\">" . __ ( "Client ID") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Plugin_Facebook_Client_ID\" id=\"authentication_plugin_facebook_client_id\" value=\"\" placeholder=\"" . __ ( "Facebook OAuth2 Client ID") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add Facebook authentication client secret field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_facebook_client_secret\" class=\"control-label col-xs-2\">" . __ ( "Client secret") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"password\" name=\"Plugin_Facebook_Client_Secret\" id=\"authentication_plugin_facebook_client_secret\" value=\"\" placeholder=\"" . __ ( "Facebook OAuth2 Client Secret") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add Facebook authentication redirect URI field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_facebook_redirect_uri\" class=\"control-label col-xs-2\">" . __ ( "Redirect URI") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Plugin_Facebook_Redirect_URI\" id=\"authentication_plugin_facebook_redirect_uri\" value=\"\" placeholder=\"" . __ ( "Facebook OAuth2 Redirect URI (your system URI to /api/auth/facebook)") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add Meta for Developers console link
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Administration") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <a href=\"https://developers.facebook.com/apps/\" target=\"_blank\">" . __ ( "Meta for Developers Console") . "</a> <i class=\"fas fa-external-link-alt\"></i>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["facebook"] = array ( "label" => __ ( "Facebook"), "html" => $output);

  /**
   * Add form JavaScript code
   */
  $buffer["js"]["onshow"]["facebook"] = "  $('#authentication_plugin_facebook_status').bootstrapToggle ( data.Plugins.Facebook.Status ? 'on' : 'off');\n" .
                                        "  $('#authentication_plugin_facebook_client_id').val ( data.Plugins.Facebook.ClientID);\n" .
                                        "  $('#authentication_plugin_facebook_client_secret').val ( data.Plugins.Facebook.ClientSecret);\n" .
                                        "  $('#authentication_plugin_facebook_redirect_uri').val ( data.Plugins.Facebook.RedirectURI);\n";
  $buffer["js"]["onfilter"]["facebook"] = "  formData.Plugins.Facebook = new Object ();\n" .
                                          "  formData.Plugins.Facebook.Status = $('#authentication_plugin_facebook_status').prop ( 'checked');\n" .
                                          "  delete ( formData.Plugin_Facebook_Status);\n" .
                                          "  formData.Plugins.Facebook.ClientID = $('#authentication_plugin_facebook_client_id').val ();\n" .
                                          "  delete ( formData.Plugin_Facebook_Client_ID);\n" .
                                          "  formData.Plugins.Facebook.ClientSecret = $('#authentication_plugin_facebook_client_secret').val ();\n" .
                                          "  delete ( formData.Plugin_Facebook_Client_Secret);\n" .
                                          "  formData.Plugins.Facebook.RedirectURI = $('#authentication_plugin_facebook_redirect_uri').val ();\n" .
                                          "  delete ( formData.Plugin_Facebook_Redirect_URI);\n";
  $buffer["js"]["init"][] = "$('#authentication_plugin_facebook_status').on ( 'change', function ( e)\n" .
                            "                                                         {\n" .
                            "                                                           if ( ! $(this).prop ( 'checked'))\n" .
                            "                                                           {\n" .
                            "                                                             $('#authentication_plugin_facebook_client_id,#authentication_plugin_facebook_client_secret,#authentication_plugin_facebook_redirect_uri').attr ( 'disabled', 'disabled');\n" .
                            "                                                           } else {\n" .
                            "                                                             $('#authentication_plugin_facebook_client_id,#authentication_plugin_facebook_client_secret,#authentication_plugin_facebook_redirect_uri').removeAttr ( 'disabled');\n" .
                            "                                                             $('#authentication_plugin_facebook_client_id').focus ();\n" .
                            "                                                           }\n" .
                            "                                                         });\n";

  return $buffer;
}
?>
