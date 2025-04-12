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
 * VoIP Domain extensions phones module API. This module add the API calls
 * related to extensions phones.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Extensions Phones
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * API hook to extend extensions addition of phone type
 */
framework_add_function_documentation (
  "extensions_search",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "phone"),
          "example" => "phone"
        )
      )
    ),
    "response" => array (
      200 => array (
        "schema" => array (
          "items" => array (
            "properties" => array (
              "Type" => array (
                "enum" => array ( "phone"),
                "example" => "phone"
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "extensions_view_phone",
  "extensions_view_phone"
);
framework_add_function_documentation (
  "extensions_view",
  array (
    "response" => array (
      200 => array (
        "schema" => array (
          "properties" => array (
            "Type" => array (
              "enum" => array ( "phone"),
              "example" => array ( "phone")
            ),
            "oneOf" => array (
              array (
                "type" => "object",
                "description" => __ ( "An object with phone information."),
                "properties" => array (
                  "Email" => array (
                    "type" => "string",
                    "description" => __ ( "The email associated to extension phone."),
                    "example" => "johndoe@example.com"
                  ),
                  "Group" => array (
                    "type" => "object",
                    "properties" => array (
                      "ID" => array (
                        "type" => "integer",
                        "description" => __ ( "The system group internal identifier of extension phone."),
                        "example" => 1
                      ),
                      "Description" => array (
                        "type" => "string",
                        "description" => __ ( "The system group description of extension phone."),
                        "example" => __ ( "IT Team")
                      )
                    )
                  ),
                  "Captures" => array (
                    "type" => "array",
                    "description" => __ ( "An array containing the groups that can capture this extension phone calls."),
                    "items" => array (
                      "type" => "object",
                      "properties" => array (
                        "ID" => array (
                          "type" => "integer",
                          "description" => __ ( "The system group internal identified that can capture this phone calls."),
                          "example" => 1
                        ),
                        "Description" => array (
                          "type" => "string",
                          "description" => __ ( "The system group description that can capture this phone calls."),
                          "example" => __ ( "IT Team")
                        )
                      )
                    )
                  ),
                  "Permissions" => array (
                    "type" => "object",
                    "description" => __ ( "The custom permissions for this extension phone."),
                    "properties" => array (
                      "Landline" => array (
                        "type" => "object",
                        "description" => __ ( "Landline calls system maximum permissions."),
                        "properties" => array (
                          "Local" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to local landline call."),
                            "example" => "y"
                          ),
                          "Interstate" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to interstate landline call."),
                            "example" => "y"
                          ),
                          "International" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to international landline call."),
                            "example" => "y"
                          )
                        ),
                      ),
                      "Mobile" => array (
                        "type" => "object",
                        "description" => __ ( "Mobile calls system maximum permissions."),
                        "properties" => array (
                          "Local" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to local mobile call."),
                            "example" => "y"
                          ),
                          "Interstate" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to interstate mobile call."),
                            "example" => "y"
                          ),
                          "International" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to international mobile call."),
                            "example" => "y"
                          )
                        )
                      ),
                      "Marine" => array (
                        "type" => "object",
                        "description" => __ ( "Marine calls system maximum permissions."),
                        "properties" => array (
                          "Local" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to local marine call."),
                            "example" => "y"
                          ),
                          "Interstate" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to interstate marine call."),
                            "example" => "y"
                          ),
                          "International" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to international marine call."),
                            "example" => "y"
                          )
                        )
                      ),
                      "Tollfree" => array (
                        "type" => "object",
                        "description" => __ ( "Toll free calls system maximum permissions."),
                        "properties" => array (
                          "Local" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to local toll free call."),
                            "example" => "y"
                          ),
                          "International" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to international toll free call."),
                            "example" => "y"
                          )
                        )
                      ),
                      "PRN" => array (
                        "type" => "object",
                        "description" => __ ( "Premium rate numbers calls system maximum permissions."),
                        "properties" => array (
                          "Local" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to local premium rate number free call."),
                            "example" => "y"
                          ),
                          "International" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to international premium rate number call."),
                            "example" => "y"
                          )
                        )
                      ),
                      "Satellite" => array (
                        "type" => "object",
                        "description" => __ ( "Satellite calls system maximum permissions."),
                        "properties" => array (
                          "Local" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to local satellite call."),
                            "example" => "y"
                          ),
                          "International" => array (
                            "type" => "string",
                            "enum" => array ( "y", "p", "n"),
                            "description" => __ ( "Permission to international satellite call."),
                            "example" => "y"
                          )
                        )
                      )
                    )
                  ),
                  "VoiceMail" => array (
                    "type" => "boolean",
                    "description" => __ ( "If the extension phone has a voicemail activated."),
                    "example" => true
                  ),
                  "Password" => array (
                    "type" => "string",
                    "format" => "password",
                    "description" => __ ( "A six digit password for restricted calls for this extension phone."),
                    "example" => "123456"
                  ),
                  "Transhipments" => array (
                    "type" => "array",
                    "description" => __ ( "An array containing the extensions that should tranship this extension phone."),
                    "items" => array (
                      "type" => "object",
                      "properties" => array (
                        "ID" => array (
                          "type" => "integer",
                          "description" => __ ( "The system unique identifier of transhipment extension."),
                          "example" => 1
                        ),
                        "Number" => array (
                          "type" => "integer",
                          "description" => __ ( "The number of the transhipment extension."),
                          "example" => 1000
                        ),
                        "Description" => array (
                          "type" => "string",
                          "description" => __ ( "The description of the transhipment extension."),
                          "example" => __ ( "John Doe")
                        )
                      )
                    )
                  ),
                  "Accounts" => array (
                    "type" => "array",
                    "description" => __ ( "An array containing all extension phone accounts."),
                    "items" => array (
                      "type" => "object",
                      "properties" => array (
                        "ID" => array (
                          "type" => "integer",
                          "description" => __ ( "The system unique identifier of extension account."),
                          "example" => 1
                        ),
                        "Type" => array (
                          "type" => "object",
                          "properties" => array (
                            "ID" => array (
                              "type" => "integer",
                              "description" => __ ( "The system unique identifier of equipment type for the account."),
                              "example" => 1
                            ),
                            "Description" => array (
                              "type" => "string",
                              "description" => __ ( "The system equipment description for the account."),
                              "example" => __ ( "VoIP IP Phone")
                            )
                          )
                        ),
                        "oneOf" => array (
                          array (
                            "type" => "object",
                            "description" => __ ( "An object with extension account MAC address."),
                            "properties" => array (
                              "MAC" => array (
                                "type" => "string",
                                "description" => __ ( "The extension account equipment MAC address."),
                                "example" => "11:22:33:44:55:66"
                              )
                            )
                          ),
                          array (
                            "type" => "object",
                            "description" => __ ( "An object with extension account username and password."),
                            "properties" => array (
                              "Username" => array (
                                "type" => "string",
                                "description" => __ ( "The SIP username for this account."),
                                "example" => "u1000-0"
                              ),
                              "Password" => array (
                                "type" => "string",
                                "format" => "password",
                                "description" => __ ( "The SIP password for this account."),
                                "example" => __ ( "Av3rYS3cUR3p4SsW0Rd")
                              )
                            )
                          )
                        )
                      )
                    )
                  ),
                  "CostCenter" => array (
                    "type" => "object",
                    "nullable" => true,
                    "properties" => array (
                      "ID" => array (
                        "type" => "integer",
                        "description" => __ ( "The system cost center internal identified for this extension phone."),
                        "example" => 1
                      ),
                      "Description" => array (
                        "type" => "string",
                        "description" => __ ( "The system cost center description for this extension phone."),
                        "example" => __ ( "IT Team")
                      ),
                      "Code" => array (
                        "type" => "string",
                        "description" => __ ( "The system cost center code for this extension phone."),
                        "example" => "10000"
                      )
                    )
                  ),
                  "Monitor" => array (
                    "type" => "boolean",
                    "description" => __ ( "If the calls from and to this extension phone must be recorded."),
                    "example" => true
                  ),
                  "VolRX" => array (
                    "type" => "integer",
                    "description" => __ ( "The receive volume gain for this extension phone."),
                    "example" => 0
                  ),
                  "VolTX" => array (
                    "type" => "integer",
                    "description" => __ ( "The transmit volume gain for this extension phone."),
                    "example" => 0
                  ),
                  "Hints" => array (
                    "type" => "array",
                    "description" => __ ( "An array containing all hints for this extension phone."),
                    "items" => array (
                      "type" => "object",
                      "properties" => array (
                        "ID" => array (
                          "type" => "integer",
                          "description" => __ ( "The system unique identifier of hint extension."),
                          "example" => 1
                        ),
                        "Number" => array (
                          "type" => "integer",
                          "description" => __ ( "The number of the hint extension."),
                          "example" => "1000"
                        ),
                        "Description" => array (
                          "type" => "string",
                          "description" => __ ( "The description of the hint extension."),
                          "example" => __ ( "John Doe")
                        )
                      )
                    )
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "extensions_add_phone_sanitize",
  "extensions_phone_sanitize"
);
framework_add_hook (
  "extensions_edit_phone_sanitize",
  "extensions_phone_sanitize"
);
framework_add_hook (
  "extensions_add_phone_validate",
  "extensions_phone_validate"
);
framework_add_hook (
  "extensions_edit_phone_validate",
  "extensions_phone_validate"
);
framework_add_hook (
  "extensions_add_phone_post",
  "extensions_add_phone_post"
);
framework_add_hook (
  "extensions_edit_phone_post",
  "extensions_edit_phone_post"
);
framework_add_hook (
  "extensions_add_phone_audit",
  "extensions_add_phone_audit"
);
framework_add_hook (
  "extensions_edit_phone_audit",
  "extensions_edit_phone_audit"
);
framework_add_function_documentation (
  "extensions_add",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "phone"),
          "example" => "phone"
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "Email" => array (
                "type" => "email",
                "description" => __ ( "The email associated to this extension phone. **Note**: This field is required only when `voicemail` are enabled."),
                "required" => false
              ),
              "Group" => array (
                "type" => "integer",
                "description" => __ ( "The system group unique identifier associated to this extension phone."),
                "required" => true
              ),
              "Captures" => array (
                "type" => "array",
                "description" => __ ( "An array with the system group unique identifier that has permission to capture calls to this extension phone."),
                "required" => true,
                "items" => array (
                  "type" => "integer"
                ),
                "minItems" => 1
              ),
              "Password" => array (
                "type" => "password",
                "pattern" => "/^[0-9]{6}$/",
                "description" => __ ( "A six digit password to use protected services from this extension phone."),
                "required" => true
              ),
              "Transhipments" => array (
                "type" => "array",
                "description" => __ ( "An array with the system extension unique identifier that will be transhipped when extension phone didn't answer the call.", true, false),
                "items" => array (
                  "type" => "integer"
                )
              ),
              "Permissions" => array (
                "type" => "object",
                "required" => true,
                "properties" => array (
                  "Landline" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local landline maximum system permission."),
                        "required" => true
                      ),
                      "Interstate" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The interstate landline maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international landline maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "Mobile" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local mobile maximum system permission."),
                        "required" => true
                      ),
                      "Interstate" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The interstate mobile maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international mobile maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "Marine" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local marine maximum system permission."),
                        "required" => true
                      ),
                      "Interstate" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The interstate marine maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international marine maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "Tollfree" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local toll free maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international toll free maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "PRN" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local premium rate number maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international premium rate number maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "Satellite" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local satellite maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international satellite maximum system permission."),
                        "required" => true
                      )
                    )
                  )
                )
              ),
              "Accounts" => array (
                "type" => "array",
                "description" => __ ( "An array containing all extension phone accounts."),
                "items" => array (
                  "type" => "object",
                  "properties" => array (
                    "Reference" => array (
                      "type" => "integer",
                      "description" => __ ( "The reference number to phone account. This is used to report any account type/MAC error."),
                      "required" => true
                    ),
                    "Type" => array (
                      "type" => "integer",
                      "description" => __ ( "The system unique identifier of equipment type for the account."),
                      "required" => true
                    ),
                    "MAC" => array (
                      "type" => "string",
                      "pattern" => "/^[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}$/",
                      "description" => __ ( "The MAC address of phone account."),
                      "required" => false
                    )
                  )
                )
              ),
              "CostCenter" => array (
                "type" => "integer",
                "description" => __ ( "The system cost center unique identifier for this extension phone. If not provided, will use the group cost center."),
                "nullable" => true
              ),
              "VoiceMail" => array (
                "type" => "boolean",
                "description" => __ ( "If the extension phone will have voice mail if didn't answer calls.", true, false),
                "required" => true
              ),
              "Monitor" => array (
                "type" => "boolean",
                "description" => __ ( "If the calls to this extension phone are recoreded or not."),
                "required" => true
              ),
              "VolTX" => array (
                "type" => "integer",
                "description" => __ ( "The transmit volume gain for this extension phone."),
                "required" => true
              ),
              "VolRX" => array (
                "type" => "integer",
                "description" => __ ( "The receive volume gain for this extension phone."),
                "required" => true
              )
            )
          )
        )
      )
    ),
    "response" => array (
      422 => array (
        "schema" => array (
          "properties" => array (
            "anyOf" => array (
              array (
                "type" => "object",
                "properties" => array (
                  "Group" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The selected group is invalid.")
                  ),
                  "Captures" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "At least one capture group is required.")
                  ),
                  "Email" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The email is required when voice mail selected.")
                  ),
                  "Password" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The password must have 6 digits.")
                  ),
                  "Landline_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Landline_Interstate" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Landline_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Mobile_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Mobile_Interstate" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Mobile_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Marine_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Marine_Interstate" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Marine_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Tollfree_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Tollfree_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "PRN_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "PRN_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Satellite_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Satellite_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Transhipments" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "One or more informed transhipment are invalid.")
                  ),
                  "Hints" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "One or more informed hint are invalid.")
                  ),
                  "Account_X_Type" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Equipment type is invalid.")
                  ),
                  "Account_X_MAC" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Please inform the equipment MAC address.")
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_function_documentation (
  "extensions_edit",
  array (
    "requests" => array (
      "properties" => array (
        "Type" => array (
          "enum" => array ( "phone"),
          "example" => "phone"
        ),
        "oneOf" => array (
          array (
            "type" => "object",
            "required" => true,
            "properties" => array (
              "Email" => array (
                "type" => "email",
                "description" => __ ( "The email associated to this extension phone. **Note**: This field is required only when `voicemail` are enabled."),
                "required" => false
              ),
              "Group" => array (
                "type" => "integer",
                "description" => __ ( "The system group unique identifier associated to this extension phone."),
                "required" => true
              ),
              "Captures" => array (
                "type" => "array",
                "description" => __ ( "An array with the system group unique identifier that has permission to capture calls to this extension phone."),
                "required" => true,
                "items" => array (
                  "type" => "integer"
                ),
                "minItems" => 1
              ),
              "Password" => array (
                "type" => "password",
                "pattern" => "/^[0-9]{6}$/",
                "description" => __ ( "A six digit password to use protected services from this extension phone."),
                "required" => true
              ),
              "Transhipments" => array (
                "type" => "array",
                "description" => __ ( "An array with the system extension unique identifier that will be transhipped when extension phone didn't answer the call.", true, false),
                "items" => array (
                  "type" => "integer"
                )
              ),
              "Permissions" => array (
                "type" => "object",
                "required" => true,
                "properties" => array (
                  "Landline" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local landline maximum system permission."),
                        "required" => true
                      ),
                      "Interstate" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The interstate landline maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international landline maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "Mobile" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local mobile maximum system permission."),
                        "required" => true
                      ),
                      "Interstate" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The interstate mobile maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international mobile maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "Marine" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local marine maximum system permission."),
                        "required" => true
                      ),
                      "Interstate" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The interstate marine maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international marine maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "Tollfree" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local toll free maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international toll free maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "PRN" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local premium rate number maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international premium rate number maximum system permission."),
                        "required" => true
                      )
                    )
                  ),
                  "Satellite" => array (
                    "type" => "object",
                    "required" => true,
                    "properties" => array (
                      "Local" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The local satellite maximum system permission."),
                        "required" => true
                      ),
                      "International" => array (
                        "type" => "string",
                        "enum" => array ( "y", "p", "n"),
                        "description" => __ ( "The international satellite maximum system permission."),
                        "required" => true
                      )
                    )
                  )
                )
              ),
              "Accounts" => array (
                "type" => "array",
                "description" => __ ( "An array containing all extension phone accounts."),
                "items" => array (
                  "type" => "object",
                  "properties" => array (
                    "Reference" => array (
                      "type" => "integer",
                      "description" => __ ( "The reference number to phone account. This is used to report any account type/MAC error."),
                      "required" => true
                    ),
                    "Type" => array (
                      "type" => "integer",
                      "description" => __ ( "The system unique identifier of equipment type for the account."),
                      "required" => true
                    ),
                    "MAC" => array (
                      "type" => "string",
                      "pattern" => "/^[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}$/",
                      "description" => __ ( "The MAC address of phone account."),
                      "required" => false
                    )
                  )
                )
              ),
              "CostCenter" => array (
                "type" => "integer",
                "description" => __ ( "The system cost center unique identifier for this extension phone. If not provided, will use the group cost center."),
                "nullable" => true
              ),
              "VoiceMail" => array (
                "type" => "boolean",
                "description" => __ ( "If the extension phone will have voice mail if didn't answer calls.", true, false),
                "required" => true
              ),
              "Monitor" => array (
                "type" => "boolean",
                "description" => __ ( "If the calls to this extension phone are recoreded or not."),
                "required" => true
              ),
              "VolTX" => array (
                "type" => "integer",
                "description" => __ ( "The transmit volume gain for this extension phone."),
                "required" => true
              ),
              "VolRX" => array (
                "type" => "integer",
                "description" => __ ( "The receive volume gain for this extension phone."),
                "required" => true
              )
            )
          )
        )
      )
    ),
    "response" => array (
      422 => array (
        "schema" => array (
          "properties" => array (
            "anyOf" => array (
              array (
                "type" => "object",
                "properties" => array (
                  "Group" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The selected group is invalid.")
                  ),
                  "Captures" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "At least one capture group is required.")
                  ),
                  "Email" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The email is required when voice mail selected.")
                  ),
                  "Password" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "The password must have 6 digits.")
                  ),
                  "Landline_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Landline_Interstate" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Landline_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Mobile_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Mobile_Interstate" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Mobile_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Marine_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Marine_Interstate" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Marine_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Tollfree_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Tollfree_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "PRN_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "PRN_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Satellite_Local" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Satellite_International" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Invalid value.")
                  ),
                  "Transhipments" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "One or more informed transhipment are invalid.")
                  ),
                  "Hints" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "One or more informed hint are invalid.")
                  ),
                  "Account_X_Type" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Equipment type is invalid.")
                  ),
                  "Account_X_MAC" => array (
                    "type" => "string",
                    "description" => __ ( "The text description of this field error."),
                    "example" => __ ( "Please inform the equipment MAC address.")
                  )
                )
              )
            )
          )
        )
      )
    )
  )
);
framework_add_hook (
  "extensions_remove_phone_pre",
  "extensions_remove_phone_pre"
);
framework_add_hook (
  "extensions_remove_phone_post",
  "extensions_remove_phone_post"
);

/**
 * Function to extend extensions with phone information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_view_phone ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "ExtensionPhone", "Groups", "CostCenters", "PhoneAccounts", "Equipments"));

  /**
   * Search extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `ExtensionPhone`.*, `Groups`.`Description` AS `GroupDescription`, `Groups`.`ID` AS `GroupID`, `CostCenters`.`Code` AS `CostCenterCode`, `CostCenters`.`Description` AS `CostCenterDescription` FROM `ExtensionPhone` INNER JOIN `Groups` ON `ExtensionPhone`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " AND `ExtensionPhone`.`Group` = `Groups`.`ID` LEFT JOIN `CostCenters` ON `ExtensionPhone`.`CostCenter` = `CostCenters`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $phone = $result->fetch_assoc ();
  $options = json_decode ( $phone["Options"], true);

  /**
   * Search capture groups for the extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Groups`.`ID`, `Groups`.`Description` FROM `PhoneCapture` LEFT JOIN `Groups` ON `PhoneCapture`.`Group` = `Groups`.`ID` WHERE `PhoneCapture`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $captures = array ();
  while ( $capture = $result->fetch_assoc ())
  {
    $captures[] = array ( "ID" => $capture["ID"], "Description" => $capture["Description"]);
  }

  /**
   * Search transhipments for the extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Description`, `Extensions`.`Number` FROM `PhoneTranshipment` LEFT JOIN `Extensions` ON `PhoneTranshipment`.`Transhipment` = `Extensions`.`ID`  WHERE `PhoneTranshipment`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $transhipments = array ();
  while ( $transhipment = $result->fetch_assoc ())
  {
    $transhipments[] = array ( "ID" => $transhipment["ID"], "Description" => $transhipment["Description"], "Number" => $transhipment["Number"]);
  }

  /**
   * Search hints for the extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Description`, `Extensions`.`Number` FROM `PhoneHint` LEFT JOIN `Extensions` ON `PhoneHint`.`Hint` = `Extensions`.`ID`  WHERE `PhoneHint`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $hints = array ();
  while ( $hint = $result->fetch_assoc ())
  {
    $hints[] = array ( "ID" => $hint["ID"], "Description" => $hint["Description"], "Number" => $hint["Number"]);
  }

  /**
   * Search accounts for the extension
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `PhoneAccounts`.*, `Equipments`.`Type`, CONCAT(`Equipments`.`Vendor`,' ',`Equipments`.`Model`) AS `Description` FROM `PhoneAccounts` LEFT JOIN `Equipments` ON `PhoneAccounts`.`Equipment` = `Equipments`.`ID` WHERE `PhoneAccounts`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " ORDER BY `PhoneAccounts`.`Username`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $accounts = array ();
  while ( $account = $result->fetch_assoc ())
  {
    if ( $account["Type"] == "SOFTPHONE")
    {
      $accounts[] = array ( "Type" => array ( "ID" => $account["Equipment"], "Description" => $account["Description"]), "ID" => $account["ID"], "Username" => $account["Username"], "Password" => $account["Password"]);
    } else {
      $accounts[] = array ( "Type" => array ( "ID" => $account["Equipment"], "Description" => $account["Description"]), "ID" => $account["ID"], "MAC" => ( $account["MAC"] != "" ? substr ( $account["MAC"], 0, 2) . ":" . substr ( $account["MAC"], 2, 2) . ":" . substr ( $account["MAC"], 4, 2) . ":" . substr ( $account["MAC"], 6, 2) . ":" . substr ( $account["MAC"], 8, 2) . ":" . substr ( $account["MAC"], 10, 2) : ""));
    }
  }

  /**
   * Format data
   */
  $buffer["Email"] = $phone["Email"];
  $buffer["Group"] = array ( "ID" => $phone["GroupID"], "Description" => $phone["GroupDescription"]);
  $buffer["Captures"] = $captures;
  $buffer["Permissions"] = json_decode ( $phone["Permissions"], true);
  $buffer["VoiceMail"] = $options["VoiceMail"];
  $buffer["Password"] = $phone["Password"];
  $buffer["Transhipments"] = $transhipments;
  $buffer["Accounts"] = $accounts;
  $buffer["CostCenter"] = array ( "ID" => $phone["CostCenter"], "Description" => $phone["CostCenterDescription"], "Code" => $phone["CostCenterCode"]);
  $buffer["Monitor"] = $options["Monitor"];
  $buffer["VolRX"] = $options["VolRX"];
  $buffer["VolTX"] = $options["VolTX"];
  $buffer["Hints"] = $hints;

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions addition/edition sanitize of phone type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_phone_sanitize ( $buffer, $parameters)
{
  global $_in;

  /**
   * Sanitize parameters
   */
  $buffer["Group"] = (int) $parameters["Group"];
  $buffer["Email"] = preg_replace ( "/ ( )+/", " ", trim ( strip_tags ( $parameters["Email"])));
  $buffer["Password"] = preg_replace ( "/[^0-9]/", "", $parameters["Password"]);
  foreach ( $buffer["Captures"] as $key => $value)
  {
    $buffer["Captures"][$key] = (int) $value;
  }
  foreach ( $buffer["Transhipments"] as $key => $value)
  {
    $buffer["Transhipments"][$key] = (int) $value;
  }
  foreach ( $buffer["Hints"] as $key => $value)
  {
    $buffer["Hints"][$key] = (int) $value;
  }

  /**
   * Filter all accounts
   */
  $accounts = 0;
  foreach ( $parameters["Accounts"] as $index => $account)
  {
    $buffer["Accounts"][$index]["Reference"] = (int) $account["Reference"];
    $buffer["Accounts"][$index]["Type"] = (int) $account["Type"];
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $account["Type"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 1)
    {
      $equipment = $result->fetch_assoc ();
    } else {
      $equipment = array ();
    }
    $buffer["Accounts"][$index]["MAC"] = preg_replace ( "/[^0-9A-F]/", "", strtoupper ( $account["MAC"]));
    $buffer["Accounts"][$index]["UID"] = $equipment["UID"];
    $buffer["Accounts"][$index]["Username"] = "u" . $buffer["Number"] . "-" . $accounts++;
    $buffer["Accounts"][$index]["Password"] = random_password ( 8);
  }

  /**
   * Check for provided group
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Group"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    $buffer["GroupReg"] = array ();
  } else {
    $buffer["GroupReg"] = $result->fetch_assoc ();
  }

  /**
   * Check for profile information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Profiles`.*, `Countries`.`ISO3166-2` FROM `Profiles` LEFT JOIN `Countries` ON `Profiles`.`Country` = `Countries`.`Code` WHERE `Profiles`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $buffer["GroupReg"]["Profile"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    $buffer["ProfileReg"] = array ();
  } else {
    $buffer["ProfileReg"] = $result->fetch_assoc ();
  }

  /**
   * Check for provided transhipment extensions
   */
  $buffer["TranshipmentsNumbers"] = array ();
  foreach ( $buffer["Transhipments"] as $transhipment)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number` FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $transhipment)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $buffer["TranshipmentsNumbers"][$transhipment] = $result->fetch_assoc ()["Number"];
    }
  }

  /**
   * Check for hint extensions
   */
  $buffer["HintsNumbers"] = array ();
  foreach ( $buffer["Hints"] as $hint)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number` FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
      $buffer["HintsNumbers"][$hint] = $result->fetch_assoc ()["Number"];
    }
  }

  /**
   * Create options data
   */
  $buffer["Options"] = array (
    "VoiceMail" => (boolean) $parameters["VoiceMail"],
    "VolRX" => (int) ( $parameters["VolRX"] >= -10 && $parameters["VolRX"] <= 10 ? $parameters["VolRX"] : 0),
    "VolTX" => (int) ( $parameters["VolTX"] >= -10 && $parameters["VolTX"] <= 10 ? $parameters["VolTX"] : 0),
    "Monitor" => (boolean) $parameters["Monitor"]
  );

  /**
   * If it's an edition, fetch old extension
   */
  if ( array_key_exists ( "ORIGINAL", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionPhone` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 1)
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
      exit ();
    }
    $data = $result->fetch_assoc ();
    $data["Permissions"] = json_decode ( $data["Permissions"], true);
    $data["Options"] = json_decode ( $data["Options"], true);
    $data["Accounts"] = array ();
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `PhoneAccounts`.*, `Equipments`.`UID` FROM `PhoneAccounts` LEFT JOIN `Equipments` ON `PhoneAccounts`.`Equipment` = `Equipments`.`ID` WHERE `PhoneAccounts`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $account = $result->fetch_assoc ())
    {
      $data["Accounts"][] = $account;
    }
    $data["Captures"] = array ();
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Group` FROM `PhoneCapture` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $capture = $result->fetch_assoc ())
    {
      $data["Captures"][] = $capture["Group"];
    }
    $data["Hints"] = array ();
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Hint` FROM `PhoneHint` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $hint = $result->fetch_assoc ())
    {
      $data["Hints"][] = $hint["Hint"];
    }
    $data["Transhipments"] = array ();
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Transhipment` FROM `PhoneTranshipment` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $transhipment = $result->fetch_assoc ())
    {
      $data["Transhipments"][] = $transhipment["Transhipment"];
    }
    $buffer["ORIGINAL"] = array_merge_recursive ( ( is_array ( $buffer["ORIGINAL"]) ? $buffer["ORIGINAL"]: array ()), $data);

    /**
     * Fix extension account usernames
     */
    $accountsadd = array ();
    $accountsremove = array ();
    $accountsusername = array ();
    foreach ( $data["Accounts"] as $account)
    {
      $accountsremove[$account["ID"]] = $account;
      $accountusernames[] = $account["Username"];
    }
    foreach ( $buffer["Accounts"] as $id => $account)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `PhoneAccounts` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " AND `Equipment` = " . $_in["mysql"]["id"]->real_escape_string ( $account["Type"]) . " AND `MAC` = '" . ( $account["MAC"] ? $_in["mysql"]["id"]->real_escape_string ( $account["MAC"]) : "") . "'"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows == 0)
      {
        $buffer["Accounts"][$id]["Username"] = "";
        continue;
      }
      $dbentry = $result->fetch_assoc ();
      unset ( $accountsremove[$dbentry["ID"]]);
      $buffer["Accounts"][$id]["Username"] = $dbentry["Username"];
      $buffer["Accounts"][$id]["Password"] = $dbentry["Password"];
    }
    foreach ( $accountsremove as $account)
    {
      if ( ( $key = array_search ( $account["Username"], $accountusernames)) !== false)
      {
        unset ( $accountusernames[$key]);
      }
    }
    foreach ( $buffer["Accounts"] as $id => $account)
    {
      if ( $account["Username"] == "")
      {
        for ( $x = 0; $x < 10; $x++)
        {
          if ( array_search ( "u" . $parameters["Number"] . "-" . $x, $accountusernames) === false)
          {
            $buffer["Accounts"][$id]["Username"] = "u" . $parameters["Number"] . "-" . $x;
            $accountusernames[] = "u" . $parameters["Number"] . "-" . $x;
            break;
          }
        }
      }
    }
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions addition/edition validate of phone type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_phone_validate ( $buffer, $parameters)
{
  global $_in;

  /**
   * Validate parameters
   */
  if ( $parameters["Group"] != (int) $parameters["Group"])
  {
    $buffer["Group"] = __ ( "The selected group is invalid.");
  }
  if ( empty ( $parameters["Group"]))
  {
    $buffer["Group"] = __ ( "The group is required.");
  }
  if ( sizeof ( $parameters["Captures"]) == 0)
  {
    $buffer["Captures"] = __ ( "At least one capture group is required.");
  }
  if ( ! empty ( $parameters["Email"]) && ! validate_email ( $parameters["Email"]))
  {
    $buffer["Email"] = __ ( "The informed email is invalid.");
  }
  if ( $parameters["Options"]["VoiceMail"] == true && empty ( $parameters["Email"]))
  {
    $buffer["Email"] = __ ( "The email is required when voice mail selected.");
  }
  if ( empty ( $parameters["Password"]))
  {
    $buffer["Password"] = __ ( "The password is required.");
  } else {
    if ( strlen ( $parameters["Password"]) != 6)
    {
      $buffer["Password"] = __ ( "The password must have 6 digits.");
    }
  }

  /**
   * Check call permissions
   */
  if ( ! in_array ( $parameters["Permissions"]["Landline"]["Local"], array ( "y", "p", "n")))
  {
    $buffer["Landline_Local"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Landline"]["Interstate"], array ( "y", "p", "n")))
  {
    $buffer["Landline_Interstate"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Landline"]["International"], array ( "y", "p", "n")))
  {
    $buffer["Landline_International"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Mobile"]["Local"], array ( "y", "p", "n")))
  {
    $buffer["Mobile_Local"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Mobile"]["Interstate"], array ( "y", "p", "n")))
  {
    $buffer["Mobile_Interstate"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Mobile"]["International"], array ( "y", "p", "n")))
  {
    $buffer["Mobile_International"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Marine"]["Local"], array ( "y", "p", "n")))
  {
    $buffer["Marine_Local"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Marine"]["Interstate"], array ( "y", "p", "n")))
  {
    $buffer["Marine_Interstate"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Marine"]["International"], array ( "y", "p", "n")))
  {
    $buffer["Marine_International"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Tollfree"]["Local"], array ( "y", "p", "n")))
  {
    $buffer["Tollfree_Local"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Tollfree"]["International"], array ( "y", "p", "n")))
  {
    $buffer["Tollfree_International"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["PRN"]["Local"], array ( "y", "p", "n")))
  {
    $buffer["PRN_Local"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["PRN"]["International"], array ( "y", "p", "n")))
  {
    $buffer["PRN_International"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Satellite"]["Local"], array ( "y", "p", "n")))
  {
    $buffer["Satellite_Local"] = __ ( "Invalid value.");
  }
  if ( ! in_array ( $parameters["Permissions"]["Satellite"]["International"], array ( "y", "p", "n")))
  {
    $buffer["Satellite_International"] = __ ( "Invalid value.");
  }

  /**
   * Get system permissions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Config` WHERE `Key` = 'Permissions'"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $permissions = json_decode ( $result->fetch_assoc ()["Data"], true);

  /**
   * Check if system permissions are respected
   */
  foreach ( $permissions as $type => $permission)
  {
    foreach ( $permission as $area => $minimum)
    {
      switch ( $minimum)
      {
        case "p":
          if ( $parameters["Permissions"][$type][$area] == "y")
          {
            $buffer[$type . "_" . $area] = __ ( "Invalid value.");
          }
          break;
        case "n":
          if ( $parameters["Permissions"][$type][$area] == "y" || $parameters["Permissions"][$type][$area] == "p")
          {
            $buffer[$type . "_" . $area] = __ ( "Invalid value.");
          }
          break;
      }
    }
  }

  /**
   * Check if provided group exists
   */
  if ( ! array_key_exists ( "Group", $buffer))
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["Group"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $buffer["Group"] = __ ( "The informed group is invalid.");
    }
  }

  /**
   * Check if capture groups exists
   */
  if ( ! array_key_exists ( "Captures", $buffer))
  {
    foreach ( $parameters["Captures"] as $capture)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Groups` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $capture)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows != 1)
      {
        $buffer["Captures"] = __ ( "One or more informed capture groups are invalid.");
        break;
      }
    }
  }

  /**
   * Check if transhipment extensions exists
   */
  foreach ( $parameters["Transhipments"] as $transhipment)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number` FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $transhipment)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $buffer["Transhipments"] = __ ( "One or more informed transhipment are invalid.");
    }
  }

  /**
   * Check if hint extensions exists
   */
  foreach ( $parameters["Hints"] as $hint)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Number` FROM `Extensions` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $buffer["Hints"] = __ ( "One or more informed hint are invalid.");
    }
  }

  /**
   * Validate each account
   */
  foreach ( $parameters["Accounts"] as $account)
  {
    if ( empty ( $account["Type"]))
    {
      $buffer["Account_" . $account["Reference"] . "_Type"] = __ ( "Please select the equipment type.");
      continue;
    }
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Equipments` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $account["Type"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $buffer["Account_" . $account["Reference"] . "_Type"] = __ ( "Equipment type is invalid.");
      continue;
    }
    $equipment = $result->fetch_assoc ();
    if ( $equipment["AutoProvision"] == "Y")
    {
      if ( empty ( $account["MAC"]))
      {
        $buffer["Account_" . $account["Reference"] . "_MAC"] = __ ( "Please inform the equipment MAC address.");
        continue;
      }
      if ( strlen ( $account["MAC"]) != 12)
      {
        $buffer["Account_" . $account["Reference"] . "_MAC"] = __ ( "Invalid MAC address.");
        continue;
      }
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `PhoneAccounts` WHERE `Equipment` = " . $_in["mysql"]["id"]->real_escape_string ( $equipment["ID"]) . " AND `MAC` = '" . $_in["mysql"]["id"]->real_escape_string ( $account["MAC"]) . "'"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      if ( $result->num_rows != 0)
      {
        $buffer["Account_" . $account["Reference"] . "_MAC"] = __ ( "Equipment already in use.");
        continue;
      }
    }
  }

  /**
   * Check number of accounts
   */
  if ( sizeof ( $parameters["Accounts"] > 10))
  {
    $x = 0;
    foreach ( $parameters["Accounts"] as $account)
    {
      $x++;
      if ( $x > 10)
      {
        if ( $buffer["Account_" . $account["Reference"] . "_Type"])
        {
          $buffer["Account_" . $account["Reference"] . "_Type"] .= "<br />";
        }
        $buffer["Account_" . $account["Reference"] . "_Type"] .= __ ( "An extension can not have more than 10 accounts.");
      }
    }
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions post addition of phone type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_phone_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Start database transaction
   */
  if ( ! @$_in["mysql"]["id"]->query ( "START TRANSACTION"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Add new extension record
   */
  if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `ExtensionPhone` (`Extension`, `Email`, `Group`, `Password`, `Permissions`, `Options`, `CostCenter`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Email"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $parameters["GroupReg"]["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Password"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Permissions"])) . "', '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Options"])) . "', " . $_in["mysql"]["id"]->real_escape_string ( ( $parameters["CostCenter"] != "" ? $parameters["CostCenter"] : "null")) . ")"))
  {
    @$_in["mysql"]["id"]->query ( "ROLLBACK");
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Add each extension capture group
   */
  foreach ( $parameters["Captures"] as $capture)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `PhoneCapture` (`Extension`, `Group`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $capture) . ")"))
    {
      @$_in["mysql"]["id"]->query ( "ROLLBACK");
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Add each extension transhipment extensions
   */
  foreach ( $parameters["Transhipments"] as $transhipment)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `PhoneTranshipment` (`Extension`, `Transhipment`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $transhipment) . ")"))
    {
      @$_in["mysql"]["id"]->query ( "ROLLBACK");
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Add each extension hint extensions
   */
  foreach ( $parameters["Hints"] as $hint)
  {
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `PhoneHint` (`Extension`, `Hint`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $hint) . ")"))
    {
      @$_in["mysql"]["id"]->query ( "ROLLBACK");
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Add each phone account
   */
  $accounts = array ();
  $aps = array ();
  foreach ( $parameters["Accounts"] as $account)
  {
    /**
     * Add account
     */
    if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `PhoneAccounts` (`Extension`, `Username`, `Password`, `Equipment`, `MAC`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $account["Username"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $account["Password"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $account["Type"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $account["MAC"]) . "')"))
    {
      @$_in["mysql"]["id"]->query ( "ROLLBACK");
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }

    /**
     * Add account to notify later
     */
    $notify = array ( "Number" => $parameters["Number"], "Username" => $account["Username"], "Password" => $account["Password"], "Type" => $account["UID"]);
    if ( framework_has_hook ( "extensions_phone_account_add_notify"))
    {
      $notify = framework_call ( "extensions_phone_account_add_notify", $parameters, false, $notify);
    }
    $accounts[] = $notify;

    /**
     * Add auto provisioning to notify later if supported
     */
    if ( ! empty ( $account["MAC"]))
    {
      $notify = array ( "Number" => $parameters["Number"], "Username" => $account["Username"], "Password" => $account["Password"], "Name" => $parameters["Description"], "MAC" => $account["MAC"], "Type" => $account["UID"], "Domain" => $parameters["ProfileReg"]["Domain"], "NTP" => $parameters["Range"]["NTP"], "Country" => $parameters["ProfileReg"]["ISO3166-2"], "TimeZone" => $parameters["ProfileReg"]["TimeZone"], "Offset" => $parameters["ProfileReg"]["Offset"], "Prefix" => $parameters["ProfileReg"]["Prefix"], "EmergencyShortcut" => (boolean) $parameters["ProfileReg"]["EmergencyShortcut"], "Hints" => array_values ( $parameters["HintsNumbers"]));
      if ( framework_has_hook ( "extensions_phone_ap_add_notify"))
      {
        $notify = framework_call ( "extensions_phone_ap_add_notify", $parameters, false, $notify);
      }
      $aps[] = $notify;
    }
  }

  /**
   * Finish database transaction
   */
  if ( ! @$_in["mysql"]["id"]->query ( "COMMIT"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Add new extension at Asterisk servers
   */
  $notify = array ( "Number" => $parameters["Number"], "UID" => $parameters["ID"], "Password" => $parameters["Password"], "Group" => $parameters["GroupReg"]["ID"], "Captures" => $parameters["Captures"], "Name" => $parameters["Description"], "Transhipments" => $parameters["TranshipmentsNumbers"], "CostCenter" => ( $parameters["CostCenter"] != "" ? $parameters["CostCenter"] : $parameters["GroupReg"]["CostCenter"]), "Permissions" => $parameters["Permissions"], "VoiceMail" => $parameters["Options"]["VoiceMail"], "Email" => $parameters["Email"], "VolTX" => $parameters["Options"]["VolTX"], "VolRX" => $parameters["Options"]["VolRX"], "Monitor" => $parameters["Options"]["Monitor"]);
  if ( framework_has_hook ( "extensions_phone_add_notify"))
  {
    $notify = framework_call ( "extensions_phone_add_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["Range"]["Server"], "extension_phone_add", $notify);

  /**
   * Add each extension account at Asterisk servers
   */
  foreach ( $accounts as $account)
  {
    notify_server ( $parameters["Range"]["Server"], "account_add", $account);
  }

  /**
   * Add each auto provision configuration at Asterisk servers
   */
  foreach ( $aps as $ap)
  {
    notify_server ( $parameters["Range"]["Server"], "ap_add", $ap);
  }

  /**
   * Create hint configuration if needed
   */
  foreach ( $parameters["Hints"] as $hint)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number`, COUNT(*) AS `Total` FROM `PhoneHint` LEFT JOIN `Extensions` ON `PhoneHint`.`Hint` = `Extensions`.`ID` WHERE `PhoneHint`.`Hint` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $tmp = $result->fetch_assoc ();
    if ( $tmp["Total"] == 1)
    {
      $notify = array ( "Number" => $tmp["Number"]);
      if ( framework_has_hook ( "extensions_phone_hint_add_notify"))
      {
        $notify = framework_call ( "extensions_phone_hint_add_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["Range"]["Server"], "hint_add", $notify);
    }
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions audit addition of phone type.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_add_phone_audit ( $buffer, $parameters)
{
  /**
   * Expand audit data
   */
  $buffer["Email"] = $parameters["Email"];
  $buffer["Group"] = $parameters["Group"]["ID"];
  $buffer["Password"] = $parameters["Password"];
  $buffer["Permissions"] = $parameters["Permissions"];
  $buffer["Options"] = $parameters["Options"];
  $buffer["CostCenter"] = ( $parameters["CostCenter"] != "" ? $parameters["CostCenter"] : "");
  $buffer["Transhipments"] = $parameters["Transhipments"];
  $buffer["Captures"] = $parameters["Captures"];
  $buffer["Accounts"] = $parameters["Accounts"];
  $buffer["Hints"] = $parameters["Hints"];

  /**
   * Return data
   */
  return $buffer;
}

/**
 * API call to get phone extension account information
 */
framework_add_hook (
  "extensions_phones_account_view",
  "extensions_phones_account_view",
  IN_HOOK_NULL,
  array (
    "response" => array (
      200 => array (
        "description" => __ ( "An object containing information about the extension account."),
        "schema" => array (
          "type" => "object",
          "properties" => array (
            "Domain" => array (
              "type" => "string",
              "description" => __ ( "The domain of the extension account."),
              "example" => "voipdomain.io"
            ),
            "ServerIP" => array (
              "type" => "string",
              "description" => __ ( "The server IP address of the extension account."),
              "example" => "192.168.0.1"
            ),
            "ServerPort" => array (
              "type" => "integer",
              "description" => __ ( "The server IP port of the extension account."),
              "minimum" => 0,
              "maximum" => 65535,
              "example" => 5060
            ),
            "Name" => array (
              "type" => "string",
              "description" => __ ( "The name of the extension account."),
              "example" => __ ( "John Doe")
            ),
            "Username" => array (
              "type" => "string",
              "description" => __ ( "The account username of the extension account."),
              "example" => "u1000-0"
            ),
            "Password" => array (
              "type" => "string",
              "description" => __ ( "The password of the extension account."),
              "example" => __ ( "A_v3ry.sECure,p4ssw0rD")
            )
          )
        )
      )
    )
  )
);
framework_add_permission ( "extensions_phones_account_view", __ ( "View phone extensions accounts"));
framework_add_api_call (
  "/extensions/:EID/account/:ID",
  "Read",
  "extensions_phones_account_view",
  array (
    "permissions" => array ( "user", "extensions_phones_account_view"),
    "title" => __ ( "View extension accounts"),
    "description" => __ ( "Get a system extension account information."),
    "parameters" => array (
      array (
        "name" => "EID",
        "type" => "integer",
        "description" => __ ( "The extension internal system unique identifier."),
        "example" => 1
      ),
      array (
        "name" => "ID",
        "type" => "integer",
        "description" => __ ( "The extension account internal system unique identifier."),
        "example" => 1
      )
    )
  )
);

/**
 * Function to generate extension phone account information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_phones_account_view ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check for modifications time
   */
  check_table_modification ( array ( "Extensions", "ExtensionPhone", "PhoneAccounts", "Ranges", "Servers"));

  /**
   * Check basic parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Search extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Profiles`.`Domain`, `Servers`.`Address`, `Servers`.`Port`, `Extensions`.`Description`, `PhoneAccounts`.`Username`, `PhoneAccounts`.`Password` FROM `Extensions` LEFT JOIN `PhoneAccounts` ON `PhoneAccounts`.`Extension` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Ranges`.`ID` = `Extensions`.`Range` LEFT JOIN `Servers` ON `Servers`.`ID` = `Ranges`.`Server` LEFT JOIN `ExtensionPhone` ON `ExtensionPhone`.`Extension` = `Extensions`.`ID` LEFT JOIN `Groups` ON `Groups`.`ID` = `ExtensionPhone`.`Group` LEFT JOIN `Profiles` ON `Profiles`.`ID` = `Groups`.`Profile` WHERE `Extensions`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["EID"]) . " AND `PhoneAccounts`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    exit ();
  }
  $extension = $result->fetch_assoc ();

  /**
   * Format data
   */
  $data = array ();
  $data["Domain"] = $extension["Domain"];
  $data["ServerIP"] = $extension["Address"];
  $data["ServerPort"] = $extension["Port"];
  $data["Name"] = $extension["Description"];
  $data["Username"] = $extension["Username"];
  $data["Password"] = $extension["Password"];

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $data);
}

/**
 * Function to extend extensions post edition of phone type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_phone_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check difference between accounts
   */
  $accountsadd = array ();
  $accountsremove = array ();
  $accountskeep = array ();
  foreach ( $parameters["ORIGINAL"]["Accounts"] as $account)
  {
    $accountsremove[$account["ID"]] = $account;
  }
  foreach ( $parameters["Accounts"] as $account)
  {
    $account["MAC"] = strtoupper ( $account["MAC"]);
    if ( ! $result = @$_in["mysql"]["id"]->query ( "`PhoneAccounts`.*, `Equipments`.`UID` AS `Type` FROM `PhoneAccounts` LEFT JOIN `Equipments` ON `PhoneAccounts`.`Equipment` = `Equipments`.`ID` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . " AND `Equipment` = " . $_in["mysql"]["id"]->real_escape_string ( $account["Type"]) . " AND `MAC` = '" . ( $account["MAC"] ? $_in["mysql"]["id"]->real_escape_string ( $account["MAC"]) : "") . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $accountsadd[] = $account;
      continue;
    }
    $dbentry = $result->fetch_assoc ();
    unset ( $accountsremove[$dbentry["ID"]]);
    $accountskeep[$dbentry["ID"]] = $dbentry;
  }

  /**
   * Update extension database record if something changed
   */
  if ( $parameters["Password"] != $parameters["ORIGINAL"]["Password"] || $parameters["Email"] != $parameters["ORIGINAL"]["Email"] || $parameters["Group"] != $parameters["ORIGINAL"]["Group"] || ! array_compare_with_keys ( $parameters["Permissions"], $parameters["ORIGINAL"]["Permissions"]) || ! array_compare_with_keys ( $parameters["Options"], $parameters["ORIGINAL"]["Options"])|| $parameters["CostCenter"] != $parameters["ORIGINAL"]["CostCenter"])
  {
    if ( ! @$_in["mysql"]["id"]->query ( "UPDATE `ExtensionPhone` SET `Password` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Password"]) . "', `Email` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Email"]) . "', `Group` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["Group"]) . ", `Password` = '" . $_in["mysql"]["id"]->real_escape_string ( $parameters["Password"]) . "', `Permissions` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parametewrs["Permissions"])) . "', `Options` = '" . $_in["mysql"]["id"]->real_escape_string ( json_encode ( $parameters["Options"])) . "', `CostCenter` = " . $_in["mysql"]["id"]->real_escape_string ( ( $parameters["CostCenter"] != "" ? $parameters["CostCenter"] : "null")) . " WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }

  /**
   * Update extension capture group records if something changed
   */
  if ( ! array_compare ( $parameters["ORIGINAL"]["Captures"], $parameters["Captures"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `PhoneCapture` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    foreach ( $parameters["Captures"] as $capture)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `PhoneCapture` (`Extension`, `Group`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $capture) . ")"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
    }
  }

  /**
   * Update extension transhipment records if something changed
   */
  if ( ! array_compare ( $parameters["ORIGINAL"]["Transhipments"], $parameters["Transhipments"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `PhoneTranshipment` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    foreach ( $parameters["Transhipments"] as $transhipment)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `PhoneTranshipment` (`Extension`, `Transhipment`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $transhipment) . ")"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
    }
  }

  /**
   * Update extension hint records if something changed
   */
  if ( ! array_compare ( $parameters["ORIGINAL"]["Hints"], $parameters["Hints"]))
  {
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `PhoneHint` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    foreach ( $parameters["Hints"] as $hint)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `PhoneHint` (`Extension`, `Hint`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", " . $_in["mysql"]["id"]->real_escape_string ( $hint) . ")"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
    }
  }

  /**
   * If there's any account to remove, do it
   */
  if ( sizeof ( $accountsremove) != 0)
  {
    foreach ( $accountsremove as $account)
    {
      if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `PhoneAccounts` WHERE `ID` = " . $_in["mysql"]["id"]->real_escape_string ( $account["ID"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }

      /**
       * If account has auto provisioning, remove it first
       */
      if ( ! empty ( $account["MAC"]))
      {
        $notify = array ( "MAC" => $account["MAC"], "Type" => $account["UID"]);
        if ( framework_has_hook ( "extensions_phone_ap_remove_notify"))
        {
          $notify = framework_call ( "extensions_phone_ap_remove_notify", $parameters, false, $notify);
        }
        notify_server ( $parameters["RangeReg"]["Server"], "ap_remove", $notify);
      }

      /**
       * Notify server to remove account
       */
      $notify = array ( "Number" => $parameters["ORIGINAL"]["Number"], "Username" => $account["Username"]);
      if ( framework_has_hook ( "extensions_phone_account_remove_notify"))
      {
        $notify = framework_call ( "extensions_phone_account_remove_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["ORIGINAL"]["Range"]["Server"], "account_remove", $notify);
    }
  }

  /**
   * If there's any account to be added, do it
   */
  if ( sizeof ( $accountsadd) != 0)
  {
    foreach ( $accountsadd as $account)
    {
      /**
       * Add account
       */
      if ( ! @$_in["mysql"]["id"]->query ( "INSERT INTO `PhoneAccounts` (`Extension`, `Username`, `Password`, `Equipment`, `MAC`) VALUES (" . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $account["Username"]) . "', '" . $_in["mysql"]["id"]->real_escape_string ( $account["Password"]) . "', " . $_in["mysql"]["id"]->real_escape_string ( $account["Type"]) . ", '" . $_in["mysql"]["id"]->real_escape_string ( $account["MAC"]) . "')"))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }

      /**
       * Add new account to Asterisk server
       */
      $notify = array ( "Number" => $parameters["Number"], "Username" => $account["Username"], "Password" => $account["Password"], "Type" => $account["Type"]);
      if ( framework_has_hook ( "extensions_phone_account_add_notify"))
      {
        $notify = framework_call ( "extensions_phone_account_add_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["RangeReg"]["Server"], "account_add", $notify);

      /**
       * Add auto provisioning file if supported
       */
      if ( ! empty ( $account["MAC"]))
      {
        $notify = array ( "Number" => $parameters["Number"], "Username" => $account["Username"], "Password" => $account["Password"], "Name" => $parameters["Description"], "MAC" => $account["MAC"], "Type" => $account["UID"], "Domain" => $parameters["ProfileReg"]["Domain"], "NTP" => $parameters["Range"]["NTP"], "Country" => $parameters["ProfileReg"]["ISO3166-2"], "TimeZone" => $parameters["ProfileReg"]["TimeZone"], "Offset" => $parameters["ProfileReg"]["Offset"], "Prefix" => $parameters["ProfileReg"]["Prefix"], "EmergencyShortcut" => (boolean) $parameters["ProfileReg"]["EmergencyShortcut"], "Hints" => array_values ( $parameters["HintsNumbers"]));
        if ( framework_has_hook ( "extensions_phone_ap_add_notify"))
        {
          $notify = framework_call ( "extensions_phone_ap_add_notify", $parameters, false, $notify);
        }
        notify_server ( $parameters["RangeReg"]["Server"], "ap_add", $notify);
      }
    }
  }

  /**
   * Check if there's any change with hints
   */
  if ( ! array_compare ( $parameters["ORIGINAL"]["Hints"], $parameters["Hints"]))
  {
    /**
     * Check if need to remove a hint
     */
    foreach ( array_diff ( $parameters["ORIGINAL"]["Hints"], $parameters["Hints"]) as $hint)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number`, COUNT(*) AS `Total` FROM `PhoneHint` LEFT JOIN `Extensions` ON `PhoneHint`.`Hint` = `Extensions`.`ID` WHERE `Extensions`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $tmp = $result->fetch_assoc ();
      if ( $tmp["Total"] == 0)
      {
        $notify = array ( "Number" => $tmp["Number"]);
        if ( framework_has_hook ( "extensions_phone_hint_remove_notify"))
        {
          $notify = framework_call ( "extensions_phone_hint_remove_notify", $parameters, false, $notify);
        }
        notify_server ( $parameters["RangeReg"]["Server"], "hint_remove", $notify);
      }
    }

    /**
     * Check if need to add a hint
     */
    foreach ( array_diff ( $parameters["Hints"], $parameters["ORIGINAL"]["Hints"]) as $hint)
    {
      if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number`, COUNT(*) AS `Total` FROM `PhoneHint` LEFT JOIN `Extensions` ON `PhoneHint`.`Hint` = `Extensions`.`ID` WHERE `Extensions`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $hint)))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $tmp = $result->fetch_assoc ();
      if ( $tmp["Total"] == 1)
      {
        $notify = array ( "Number" => $tmp["Number"]);
        if ( framework_has_hook ( "extensions_phone_hint_add_notify"))
        {
          $notify = framework_call ( "extensions_phone_hint_add_notify", $parameters, false, $notify);
        }
        notify_server ( $parameters["RangeReg"]["Server"], "hint_add", $notify);
      }
    }
  }

  /**
   * Check if any auto provision need to be updated
   */
  foreach ( $accountskeep as $id => $account)
  {
    if ( $account["MAC"] && ( $parameters["Description"] != $parameters["ORIGINAL"]["Description"] || ! array_compare ( $parameters["ORIGINAL"]["Hints"], $parameters["Hints"])))
    {
      $notify = array ( "Number" => $parameters["Number"], "Username" => $account["Username"], "Password" => $account["Password"], "Name" => $parameters["Description"], "MAC" => $account["MAC"], "Type" => $account["UID"], "Domain" => $parameters["ProfileReg"]["Domain"], "NTP" => $parameters["Range"]["NTP"], "Country" => $parameters["ProfileReg"]["ISO3166-2"], "TimeZone" => $parameters["ProfileReg"]["TimeZone"], "Offset" => $parameters["ProfileReg"]["Offset"], "Prefix" => $parameters["ProfileReg"]["Prefix"], "EmergencyShortcut" => (boolean) $parameters["ProfileReg"]["EmergencyShortcut"], "Hints" => array_values ( $parameters["HintsNumbers"]));
      if ( framework_has_hook ( "extensions_phone_ap_change_notify"))
      {
        $notify = framework_call ( "extensions_phone_ap_change_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["RangeReg"]["Server"], "ap_change", $notify);
    }
  }

// **TODO**: Check if anything changed at the phone that need to request task "extension_phone_change"

  /**
   * Check if any other extension need to update Asterisk configurations
   */
  if ( $parameters["Number"] != $parameters["ORIGINAL"]["Number"])
  {
    /**
     * First, check if extension exist at any other extension transhipment
     */
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number`, `Extensions`.`ID`, `Ranges`.`Server` FROM `PhoneTranshipment` LEFT JOIN `Extensions` ON `PhoneTranshipment`.`Extension` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `PhoneTranshipment`.`Transhipment` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $data = $result->fetch_assoc ())
    {
      if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number` FROM `PhoneTranshipment` LEFT JOIN `Extensions` ON `PhoneTranshipment`.`Transhipment` = `Extensions`.`ID` WHERE `PhoneTranshipment`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $data["ID"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $notify = array ();
      $notify["Number"] = $data["Number"];
      $notify["Transhipments"] = array ();
      while ( $tmp = $result2->fetch_assoc ())
      {
        $notify["Transhipments"][] = $tmp["Number"];
      }
      notify_server ( $data["Server"], "transhipment_change", $notify);
// **TODO**: Precisa implementar esta tarefa!
    }

    /**
     * Second, check if extension exist at any other extension hint
     */
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number`, `Extensions`.`ID`, `Ranges`.`Server` FROM `PhoneHint` LEFT JOIN `Extensions` ON `PhoneHint`.`Extension` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `PhoneHint`.`Hint` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows != 0)
    {
// **TODO**: Adicionar chamada de hook de notify!!!
      notify_server ( $parameters["ORIGINAL"]["RangeReg"]["Server"], "hint_remove", array ( "Number" => $extension["Number"]));
      notify_server ( $parameters["RangeReg"]["Server"], "hint_add", array ( "Number" => $parameters["Number"]));
    }
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to extend extensions audit edition of phone type.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_edit_phone_audit ( $buffer, $parameters)
{
  global $_in;

  /**
   * Expand audit data
   */
  if ( $parameters["Email"] != $parameters["ORIGINAL"]["Email"])
  {
    $buffer["Email"] = array ( "Original" => $parameters["ORIGINAL"]["Email"], "New" => $parameters["Email"]);
  }
  if ( $parameters["Group"] != $parameters["ORIGINAL"]["Group"])
  {
    $buffer["Group"] = array ( "Original" => $parameters["ORIGINAL"]["Group"], "New" => $parameters["Group"]);
  }
  if ( ! array_compare ( $parameters["ORIGINAL"]["Captures"], $parameters["Captures"]))
  {
    $buffer["Captures"] = array ( "Original" => $parameters["ORIGINAL"]["Captures"], "New" => $parameters["Captures"]);
  }
  if ( $parameters["Password"] != $parameters["ORIGINAL"]["Password"])
  {
    $buffer["Password"] = array ( "Original" => $parameters["ORIGINAL"]["Password"], "New" => $parameters["Password"]);
  }
  if ( ! array_compare ( $parameters["ORIGINAL"]["Transhipments"], $parameters["Transhipments"]))
  {
    $buffer["Transhipments"] = array ( "Original" => $parameters["ORIGINAL"]["Transhipments"], "New" => $parameters["Transhipments"]);
  }
  if ( ! array_compare_with_keys ( $parameters["ORIGINAL"]["Permissions"], $parameters["Permissions"]))
  {
    $buffer["Permissions"] = array ( "Original" => $parameters["ORIGINAL"]["Permissions"], "New" => $parameters["Permissions"]);
  }
  if ( ! array_compare_with_keys ( $parameters["ORIGINAL"]["Options"], $parameters["Options"]))
  {
    $buffer["Options"] = array ( "Original" => $parameters["ORIGINAL"]["Options"], "New" => $parameters["Options"]);
  }
  if ( $parameters["CostCenter"] != $parameters["ORIGINAL"]["CostCenter"])
  {
    $buffer["CostCenter"] = array ( "Original" => $parameters["ORIGINAL"]["CostCenter"], "New" => $parameters["CostCenter"]);
  }
  if ( ! array_compare ( $parameters["ORIGINAL"]["Hints"], $parameters["Hints"]))
  {
    $buffer["Hints"] = array ( "Original" => $parameters["ORIGINAL"]["Hints"], "New" => $parameters["Hints"]);
  }

  /**
   * Check difference between accounts
   */
  $accountsadd = array ();
  $accountsremove = array ();
  $accountskeep = array ();
  foreach ( $parameters["ORIGINAL"]["Accounts"] as $account)
  {
    $accountsremove[$account["ID"]] = $account;
  }
  foreach ( $parameters["Accounts"] as $account)
  {
    $account["MAC"] = strtoupper ( $account["MAC"]);
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `PhoneAccounts` WHERE `Equipment` = " . $_in["mysql"]["id"]->real_escape_string ( $account["Type"]) . " AND `MAC` = '" . $_in["mysql"]["id"]->real_escape_string ( $account["MAC"]) . "'"))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    if ( $result->num_rows == 0)
    {
      $accountsadd[] = $account;
      continue;
    }
    $dbentry = $result->fetch_assoc ();
    unset ( $accountsremove[$dbentry["ID"]]);
    $accountskeep[$dbentry["ID"]] = $dbentry;
  }
  if ( sizeof ( $accountsadd) != 0 || sizeof ( $accountsremove) != 0)
  {
    $buffer["Accounts"] = array ( "Add" => $accountsadd, "Remove" => $accountsremove, "Keep" => $accountskeep);
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to remove an existing extension phone.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove_phone_pre ( $buffer, $parameters)
{
  global $_in;

  /**
   * Get extension phone information
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `ExtensionPhone` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 1)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $tmp = $result->fetch_assoc ();
  $buffer["ORIGINAL"] = array_merge_recursive ( $buffer["ORIGINAL"], $tmp);
  $buffer["ORIGINAL"]["Permissions"] = json_decode ( $buffer["ORIGINAL"]["Permissions"], true);
  $buffer["ORIGINAL"]["Options"] = json_decode ( $buffer["ORIGINAL"]["Options"], true);

  /**
   * Get extension capture groups
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `PhoneCapture` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $buffer["ORIGINAL"]["Captures"] = array ();
  while ( $capture = $result->fetch_assoc ())
  {
    $buffer["ORIGINAL"]["Captures"][] = $capture["Group"];
  }

  /**
   * Get extension transhipment extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `PhoneTranshipment` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $buffer["ORIGINAL"]["Transhipments"] = array ();
  while ( $transhipment = $result->fetch_assoc ())
  {
    $buffer["ORIGINAL"]["Transhipments"][] = $transhipment["Transhipment"];
  }

  /**
   * Get extension hint extensions
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `PhoneHint` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $buffer["ORIGINAL"]["Hints"] = array ();
  while ( $hint = $result->fetch_assoc ())
  {
    $buffer["ORIGINAL"]["Hints"][] = $hint["Hint"];
  }

  /**
   * Get extension accounts
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `PhoneAccounts` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows == 0)
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  $buffer["ORIGINAL"]["Accounts"] = array ();
  while ( $account = $result->fetch_assoc ())
  {
    $buffer["ORIGINAL"]["Accounts"][] = $account;
  }

  /**
   * Remove extension from other extensions hint
   */
  if ( ! $count = @$_in["mysql"]["id"]->query ( "DELETE FROM `PhoneHint` WHERE `Hint` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( intval ( $count->affected_rows) != 0)
  {
    $notify = array ( "Number" => $parameters["ORIGINAL"]["Number"]);
    if ( framework_has_hook ( "extensions_phone_hint_remove_notify"))
    {
      $notify = framework_call ( "extensions_phone_hint_remove_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["ORIGINAL"]["Server"], "hint_remove", $notify);
  }

  /**
   * Remove unique hints from extension
   */
  foreach ( $buffer["ORIGINAL"]["Hints"] as $hint)
  {
    if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number`, `Ranges`.`Server`, COUNT(*) AS `Total` FROM `PhoneHint` LEFT JOIN `Extensions` ON `PhoneHint`.`Hint` = `Extensions`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `PhoneHint`.`Hint` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $hint)))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $hintdata = $result->fetch_assoc ();
    if ( $hintdata["Total"] == 1)
    {
      $notify = array ( "Number" => $hintdata["Number"]);
      if ( framework_has_hook ( "extensions_phone_hint_remove_notify"))
      {
        $notify = framework_call ( "extensions_phone_hint_remove_notify", $parameters, false, $notify);
      }
      notify_server ( $hintdata["Server"], "hint_remove", $notify);
    }
  }
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `PhoneHint` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove extension from other extensions transhipment
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number`, `Extensions`.`ID`, `Ranges`.`Server` FROM `PhoneTranshipment` LEFT JOIN `Extensions` ON `Extensions`.`ID` = `PhoneTranshipment`.`Extension` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `PhoneTranshipment`.`Transhipment` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $transhipment = $result->fetch_assoc ())
  {
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number` FROM `PhoneTranshipment` LEFT JOIN `Extensions` ON `PhoneTranshipment`.`Transhipment` = `Extensions`.`ID` WHERE `PhoneTranshipment`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $transhipment["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $transhipmentdata = array ();
    while ( $tmp = $result2->fetch_assoc ())
    {
      $transhipmentdata[] = $tmp["Number"];
    }
    $notify = array ( "Number" => $transhipment["Number"], "Transhipments" => $transhipmentdata);
    if ( framework_has_hook ( "extensions_phone_transhipment_change_notify"))
    {
      $notify = framework_call ( "extensions_phone_transhipment_change_notify", $parameters, false, $notify);
    }
    notify_server ( $transhipment["Server"], "transhipment_change", $notify);
    if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `PhoneTranshipment` WHERE `Transhipment` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
  }
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `PhoneTranshipment` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove any extension capture
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `PhoneCapture` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Remove extension phone entry
   */
  if ( ! @$_in["mysql"]["id"]->query ( "DELETE FROM `ExtensionPhone` WHERE `Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $parameters["ID"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Function to execute post remove of an extension phone.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_remove_phone_post ( $buffer, $parameters)
{
  global $_in;

  /**
   * Notify servers about change
   */
  $notify = array ( "Number" => $parameters["ORIGINAL"]["Number"]);
  if ( framework_has_hook ( "extensions_phone_remove_notify"))
  {
    $notify = framework_call ( "extensions_phone_remove_notify", $parameters, false, $notify);
  }
  notify_server ( $parameters["ORIGINAL"]["Server"], "extension_phone_remove", $notify);

  /**
   * Return data
   */
  return $buffer;
}

/**
 * Hook call to intercept new server and server rebuild
 */
framework_add_hook ( "servers_add_post", "extensions_phones_server_reconfig");
framework_add_hook ( "servers_rebuild_config", "extensions_phones_server_reconfig");

/**
 * Function to notify server to include all extensions phones.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return string Output of the generated page
 */
function extensions_phones_server_reconfig ( $buffer, $parameters)
{
  global $_in;

  /**
   * Check basic parameters
   */
  $parameters["ID"] = (int) $parameters["ID"];

  /**
   * Fetch all extensions and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`ID`, `Extensions`.`Number`, `Extensions`.`Description`, `ExtensionPhone`.`Email`, `ExtensionPhone`.`Password`, `ExtensionPhone`.`Group`, `ExtensionPhone`.`Permissions`, `ExtensionPhone`.`Options`, `CostCenters`.`Code` AS `CostCenter`, GROUP_CONCAT(`PhoneCapture`.`Group` SEPARATOR ',') AS `Captures`, `Profiles`.`Domain`, `Profiles`.`Prefix`, `Countries`.`ISO3166-2`, `Profiles`.`TimeZone`, `Profiles`.`Offset`, `Profiles`.`EmergencyShortcut`, `Servers`.`NTP` FROM `Extensions` LEFT JOIN `ExtensionPhone` ON `Extensions`.`ID` = `ExtensionPhone`.`Extension` LEFT JOIN `Groups` ON `ExtensionPhone`.`Group` = `Groups`.`ID` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` LEFT JOIN `Servers` ON `Ranges`.`Server` = `Servers`.`ID` LEFT JOIN `Profiles` ON `Groups`.`Profile` = `Profiles`.`ID` LEFT JOIN `Countries` ON `Profiles`.`Country` = `Countries`.`Code` LEFT JOIN `CostCenters` ON `ExtensionPhone`.`CostCenter` = `CostCenters`.`ID` LEFT JOIN `PhoneCapture` ON `PhoneCapture`.`Extension` = `Extensions`.`ID` WHERE `Extensions`.`Type` = 'phone' AND `Ranges`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]) . " GROUP BY `Extensions`.`ID`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $extension = $result->fetch_assoc ())
  {
    /**
     * Explode capture groups and permissions
     */
    $extension["Captures"] = explode ( ",", $extension["Captures"]);
    $extension["Permissions"] = json_decode ( $extension["Permissions"], true);
    $extension["Options"] = json_decode ( $extension["Options"], true);
    $extension["NTP"] = json_decode ( $extension["NTP"], true);

    /**
     * Fetch extension transhipments
     */
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`Extensions`.`Number` SEPARATOR ',') AS `Transhipments` FROM `PhoneTranshipment` LEFT JOIN `Extensions` ON `PhoneTranshipment`.`Transhipment` = `Extensions`.`ID` WHERE `PhoneTranshipment`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $extension["Transhipments"] = explode ( ",", $result2->fetch_assoc ()["Transhipments"]);
    if ( sizeof ( $extension["Transhipments"]) == 1 && $extension["Transhipments"][0] == "")
    {
      unset ( $extension["Transhipments"][0]);
    }

    /**
     * Fetch extension hints
     */
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT GROUP_CONCAT(`Extensions`.`Number` SEPARATOR ',') AS `Hints` FROM `PhoneHint` LEFT JOIN `Extensions` ON `PhoneHint`.`Hint` = `Extensions`.`ID` WHERE `PhoneHint`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    $extension["Hints"] = explode ( ",", $result2->fetch_assoc ()["Hints"]);
    if ( sizeof ( $extension["Hints"]) == 1 && $extension["Hints"][0] == "")
    {
      unset ( $extension["Hints"][0]);
    }

    /**
     * If extension doesn't has cost center, get group default cost center
     */
    if ( $extension["CostCenter"] == "")
    {
      if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT `CostCenters`.`Code` FROM `Groups` LEFT JOIN `CostCenters` ON `Groups`.`CostCenter` = `CostCenters`.`ID` WHERE `Groups`.`ID` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["Group"])))
      {
        header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        exit ();
      }
      $extension["CostCenter"] = $result2->fetch_assoc ()["Code"];
    }

    /**
     * Add extension at Asterisk server
     */
    $notify = array ( "Number" => $extension["Number"], "UID" => $extension["ID"], "Password" => $extension["Password"], "Group" => $extension["Group"], "Captures" => $extension["Captures"], "Name" => $extension["Description"], "Transhipments" => $extension["Transhipments"], "CostCenter" => $extension["CostCenter"], "Permissions" => $extension["Permissions"], "VoiceMail" => $extension["Options"]["VoiceMail"], "Email" => $extension["Email"], "VolTX" => $extension["Options"]["VolTX"], "VolRX" => $extension["Options"]["VolRX"], "Monitor" => $extension["Options"]["Monitor"]);
    if ( framework_has_hook ( "extensions_phone_add_notify"))
    {
      $notify = framework_call ( "extensions_phone_add_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["ID"], "extension_phone_add", $notify);

    /**
     * Fetch all accounts from extension
     */
    if ( ! $result2 = @$_in["mysql"]["id"]->query ( "SELECT `PhoneAccounts`.`ID`, `PhoneAccounts`.`Username`, `PhoneAccounts`.`Password`, `PhoneAccounts`.`MAC`, `Equipments`.`UID`, `Equipments`.`Type`, `Equipments`.`AutoProvision` FROM `PhoneAccounts` LEFT JOIN `Equipments` ON `PhoneAccounts`.`Equipment` = `Equipments`.`ID` WHERE `PhoneAccounts`.`Extension` = " . $_in["mysql"]["id"]->real_escape_string ( $extension["ID"])))
    {
      header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
      exit ();
    }
    while ( $account = $result2->fetch_assoc ())
    {
      /**
       * Add extension at Asterisk server
       */
      $notify = array ( "Number" => $extension["Number"], "Username" => $account["Username"], "Password" => $account["Password"], "Type" => $account["UID"]);
      if ( framework_has_hook ( "extensions_phone_add_account_notify"))
      {
        $notify = framework_call ( "extensions_phone_add_account_notify", $parameters, false, $notify);
      }
      notify_server ( $parameters["ID"], "account_add", $notify);

      /**
       * Add auto provisioning file if supported
       */
      if ( ! empty ( $account["MAC"]))
      {
        $notify = array ( "Number" => $extension["Number"], "Username" => $account["Username"], "Password" => $account["Password"], "Name" => $extension["Description"], "MAC" => $account["MAC"], "Type" => $account["UID"], "Domain" => $extension["Domain"], "NTP" => $extension["NTP"], "Country" => $extension["ISO3166-2"], "TimeZone" => $extension["TimeZone"], "Offset" => $extension["Offset"], "Prefix" => $extension["Prefix"], "EmergencyShortcut" => (boolean) $extension["EmergencyShortcut"], "Hints" => $extension["Hints"]);
        if ( framework_has_hook ( "extensions_add_ap_notify"))
        {
          $notify = framework_call ( "extensions_add_ap_notify", $parameters, false, $notify);
        }
        notify_server ( $parameters["ID"], "ap_add", $notify);
      }
    }
  }

  /**
   * Fetch all hints and send to server
   */
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT `Extensions`.`Number` FROM `PhoneHint` LEFT JOIN `Extensions` ON `Extensions`.`ID` = `PhoneHint`.`Hint` LEFT JOIN `Ranges` ON `Extensions`.`Range` = `Ranges`.`ID` WHERE `Ranges`.`Server` = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ID"]) . " GROUP BY `Extensions`.`Number`"))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  while ( $hint = $result->fetch_assoc ())
  {
    $notify = array ( "Number" => $hint["Number"]);
    if ( framework_has_hook ( "extensions_phone_add_hint_notify"))
    {
      $notify = framework_call ( "extensions_phone_add_hint_notify", $parameters, false, $notify);
    }
    notify_server ( $parameters["ID"], "hint_add", $notify);
  }

  /**
   * Return buffer
   */
  return $buffer;
}
?>
