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
 * VoIP Domain Discord authentication module filters. This module add the filter
 * calls related to Discord authentication queues.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Authentication Discord
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights queued.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add Discord authentication filters
 */
framework_add_filter ( "authentication_plugins", "authentication_discord_plugin");
framework_add_filter ( "authentication_subpages", "authentication_discord_subpage");

/**
 * Function to add Discord authentication object information.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function authentication_discord_plugin ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array ( "Discord" => array ( "Name" => "Discord", "AuthenticationURL" => "/api/auth/discord", "Icon" => "discord")));
}

/**
 * Function to generate the Discord authentication subpage code.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function authentication_discord_subpage ( $buffer, $parameters)
{
  /**
   * Add Discord panel
   */
  $output = "";

  // Add Discord authentication status field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_discord_status\" class=\"control-label col-xs-2\">" . __ ( "Status") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"checkbox\" name=\"Plugin_Discord_Status\" id=\"authentication_plugin_discord_status\" value=\"true\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add Discord authentication client ID field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_discord_client_id\" class=\"control-label col-xs-2\">" . __ ( "Client ID") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Plugin_Discord_Client_ID\" id=\"authentication_plugin_discord_client_id\" value=\"\" placeholder=\"" . __ ( "Discord OAuth2 Client ID") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add Discord authentication client secret field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_discord_client_secret\" class=\"control-label col-xs-2\">" . __ ( "Client secret") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"password\" name=\"Plugin_Discord_Client_Secret\" id=\"authentication_plugin_discord_client_secret\" value=\"\" placeholder=\"" . __ ( "Discord OAuth2 Client Secret") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add Discord authentication redirect URI field
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label for=\"authentication_plugin_discord_redirect_uri\" class=\"control-label col-xs-2\">" . __ ( "Redirect URI") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <input type=\"text\" name=\"Plugin_Discord_Redirect_URI\" id=\"authentication_plugin_discord_redirect_uri\" value=\"\" placeholder=\"" . __ ( "Discord OAuth2 Redirect URI (your system URI to /api/auth/discord)") . "\" class=\"form-control\" />\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";

  // Add Discord developer portal link
  $output .= "      <div class=\"form-group\">\n";
  $output .= "        <label class=\"control-label col-xs-2\">" . __ ( "Administration") . "</label>\n";
  $output .= "        <div class=\"col-xs-10\">\n";
  $output .= "          <a href=\"https://discord.com/developers/applications\" target=\"_blank\">" . __ ( "Discord Developer Portal") . "</a> <i class=\"fas fa-external-link-alt\"></i>\n";
  $output .= "        </div>\n";
  $output .= "      </div>\n";
  $buffer["tabs"]["discord"] = array ( "label" => __ ( "Discord"), "html" => $output);

  /**
   * Add form JavaScript code
   */
  $buffer["js"]["onshow"]["discord"] = "  $('#authentication_plugin_discord_status').bootstrapToggle ( data.Plugins.Discord.Status ? 'on' : 'off');\n" .
                                       "  $('#authentication_plugin_discord_client_id').val ( data.Plugins.Discord.ClientID);\n" .
                                       "  $('#authentication_plugin_discord_client_secret').val ( data.Plugins.Discord.ClientSecret);\n" .
                                       "  $('#authentication_plugin_discord_redirect_uri').val ( data.Plugins.Discord.RedirectURI);\n";
  $buffer["js"]["onfilter"]["discord"] = "  formData.Plugins.Discord = new Object ();\n" .
                                         "  formData.Plugins.Discord.Status = $('#authentication_plugin_discord_status').prop ( 'checked');\n" .
                                         "  delete ( formData.Plugin_Discord_Status);\n" .
                                         "  formData.Plugins.Discord.ClientID = $('#authentication_plugin_discord_client_id').val ();\n" .
                                         "  delete ( formData.Plugin_Discord_Client_ID);\n" .
                                         "  formData.Plugins.Discord.ClientSecret = $('#authentication_plugin_discord_client_secret').val ();\n" .
                                         "  delete ( formData.Plugin_Discord_Client_Secret);\n" .
                                         "  formData.Plugins.Discord.RedirectURI = $('#authentication_plugin_discord_redirect_uri').val ();\n" .
                                         "  delete ( formData.Plugin_Discord_Redirect_URI);\n";
  $buffer["js"]["init"][] = "$('#authentication_plugin_discord_status').on ( 'change', function ( e)\n" .
                            "                                                         {\n" .
                            "                                                           if ( ! $(this).prop ( 'checked'))\n" .
                            "                                                           {\n" .
                            "                                                             $('#authentication_plugin_discord_client_id,#authentication_plugin_discord_client_secret,#authentication_plugin_discord_redirect_uri').attr ( 'disabled', 'disabled');\n" .
                            "                                                           } else {\n" .
                            "                                                             $('#authentication_plugin_discord_client_id,#authentication_plugin_discord_client_secret,#authentication_plugin_discord_redirect_uri').removeAttr ( 'disabled');\n" .
                            "                                                             $('#authentication_plugin_discord_client_id').focus ();\n" .
                            "                                                           }\n" .
                            "                                                         });\n";

  return $buffer;
}
?>
