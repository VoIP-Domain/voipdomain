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
 * VoIP Domain main framework interface module filters. This module add the
 * generic system filter calls.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Interface
 * @copyright  2016-2025 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add internal interface filters
 */
framework_add_filter ( "objects_types", "interface_objects");
framework_add_filter ( "process_call", "process_call");

/**
 * Function to add interface objects information.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Output of the found data
 */
function interface_objects ( $buffer, $parameters)
{
  return array_merge ( (array) $buffer, array (
    array ( "object" => "Local_Extension", "path" => "/extensions", "icon" => "phone", "label" => "info", "text" => array ( "singular" => __ ( "Extension"), "plural" => __ ( "Extensions"))),
    array ( "object" => "Local_Landline", "icon" => "phone", "label" => "success", "text" => array ( "singular" => __ ( "Local phone"), "plural" => __ ( "Local phones"))),
    array ( "object" => "Interstate_Landline", "icon" => "phone", "label" => "warning", "text" => array ( "singular" => __ ( "Interstate phone"), "plural" => __ ( "Interstate phones"))),
    array ( "object" => "International_Landline", "icon" => "phone", "label" => "danger", "text" => array ( "singular" => __ ( "International phone"), "plural" => __ ( "International phones"))),
    array ( "object" => "Local_Mobile", "icon" => "mobile-alt", "label" => "success", "text" => array ( "singular" => __ ( "Local mobile"), "plural" => __ ( "Local mobiles"))),
    array ( "object" => "Interstate_Mobile", "icon" => "mobile-alt", "label" => "warning", "text" => array ( "singular" => __ ( "Interstate mobile"), "plural" => __ ( "Interstate mobiles"))),
    array ( "object" => "International_Mobile", "icon" => "mobile-alt", "label" => "danger", "text" => array ( "singular" => __ ( "International mobile"), "plural" => __ ( "International mobiles"))),
    array ( "object" => "Local_Marine", "icon" => "ship", "label" => "success", "text" => array ( "singular" => __ ( "Local marine"), "plural" => __ ( "Local marines"))),
    array ( "object" => "Interstate_Marine", "icon" => "ship", "label" => "warning", "text" => array ( "singular" => __ ( "Interstate marine"), "plural" => __ ( "Interstate marines"))),
    array ( "object" => "International_Marine", "icon" => "ship", "label" => "danger", "text" => array ( "singular" => __ ( "International marine"), "plural" => __ ( "International marines"))),
    array ( "object" => "Local_Tollfree", "icon" => "piggy-bank", "label" => "success", "text" => array ( "singular" => __ ( "Local toll free"), "plural" => __ ( "Local toll frees"))),
    array ( "object" => "International_Tollfree", "icon" => "piggy-bank", "label" => "danger", "text" => array ( "singular" => __ ( "International toll free"), "plural" => __ ( "International toll frees"))),
    array ( "object" => "Local_Special", "icon" => "dollar-sign", "label" => "success", "text" => array ( "singular" => __ ( "Local special"), "plural" => __ ( "Local specials"))),
    array ( "object" => "International_Special", "icon" => "dollar-sign", "label" => "danger", "text" => array ( "singular" => __ ( "International special"), "plural" => __ ( "International specials"))),
    array ( "object" => "Local_Satellite", "icon" => "satellite", "label" => "success", "text" => array ( "singular" => __ ( "Local satellite"), "plural" => __ ( "Local satellites"))),
    array ( "object" => "International_Satellite", "icon" => "satellite", "label" => "danger", "text" => array ( "singular" => __ ( "International satellite"), "plural" => __ ( "International satellites"))),
    array ( "object" => "Local_Services", "icon" => "concierge-bell", "label" => "success", "text" => array ( "singular" => __ ( "Local service"), "plural" => __ ( "Local services"))),
    array ( "object" => "International_Services", "icon" => "concierge-bell", "label" => "danger", "text" => array ( "singular" => __ ( "International service"), "plural" => __ ( "International services")))
  ));
}

/**
 * Function to process a call CDR record to generate report output.
 *
 * @global array $_in Framework global configuration variable
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function process_call ( $buffer, $parameters)
{
  global $_in;

  // Start the output array
  $output = array ();

  // Server that processed the call:
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Servers` WHERE ID = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["server"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data = $result->fetch_assoc ();
    $output["Server"] = array (
      "ID" => (int) $data["ID"],
      "Description" => $data["Description"]
    );
  } else {
    $output["Server"] = array (
      "ID" => null,
      "Description" => ""
    );
  }

  // Call times (timestamp and ISO8601):
  $timestamp = format_db_timestamp ( $parameters["calldate"]);
  $output["CallDates"] = array (
    "Start" => array (
      "Timestamp" => $timestamp,
      "Datetime" => format_db_iso8601 ( $parameters["calldate"])
    ),
    "Answered" => array (
      "Timestamp" => $parameters["billsec"] ? $timestamp + $parameters["duration"] - $parameters["billsec"] : '',
      "Datetime" => $parameters["billsec"] ? date ( "Y-m-d\TH:i:s\Z", $timestamp + $parameters["duration"] - $parameters["billsec"]) : ''
    ),
    "Finished" => array (
      "Timestamp" => $timestamp + $parameters["duration"],
      "Datetime" => date ( "Y-m-d\TH:i:s\Z", $timestamp + $parameters["duration"])
    )
  );

  // Call flow (input or output):
  $output["Flow"] = $parameters["srcid"] ? "out" : "in";

  // Call durations (total, ring and bill):
  $output["Duration"] = (int) $parameters["duration"];
  $output["RingingTime"] = $parameters["duration"] - $parameters["billsec"];
  $output["BillingTime"] = (int) $parameters["billsec"];

  // Check for disposition:
  if ( $parameters["userfield"] == "DND")
  {
    $parameters["disposition"] = "NO ANSWER";
  }
  switch ( $parameters["disposition"])
  {
    case "ANSWERED":
      $result = "Answered";
      $resultI18N = __ ( "Answered");
      if ( $parameters["lastapp"] == "VoiceMail")
      {
        $result .= " (Voice mail)";
        $resultI18N .= " (" . __ ( "Voice mail") . ")";
      }
      break;
    case "NO ANSWER":
      $result = "Not answered";
      $resultI18N = __ ( "Not answered");
      break;
    case "BUSY":
      $result = "Busy";
      $resultI18N = __ ( "Busy");
      break;
    case "FAILED":
      $result = "Call failure";
      $resultI18N = __ ( "Call failure");
      break;
    case "CONGESTION":
      $result = "Congestion";
      $resultI18N = __ ( "Congestion");
      break;
    default:
      $result = "Unknown: " . strip_tags ( $parameters["disposition"]);
      $resultI18N = __ ( "Unknown") . ": " . strip_tags ( $parameters["disposition"]);
      break;
  }
  $output["Result"] = $result;
  $output["ResultI18N"] = $resultI18N;

  // Call costs:
  if ( $parameters["processed"])
  {
    $cost = (float) $parameters["value"];
    $costI18N = number_format ( (float) $parameters["value"], 2, __ ( "."), __ ( ","));
  } else {
    $cost = NULL;
    $costI18n = NULL;
  }
  $output["Value"] = $cost;
  $output["ValueI18N"] = $costI18N;

  // Which gateway used (if any):
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `Gateways` WHERE ID = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["gateway"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data = $result->fetch_assoc ();
    $output["Gateway"] = array (
      "ID" => (int) $data["ID"],
      "Description" => $data["Description"]
    );
  } else {
    $output["Gateway"] = array (
      "ID" => null,
      "Description" => ""
    );
  }

  // Which cost center (if any):
  if ( ! $result = @$_in["mysql"]["id"]->query ( "SELECT * FROM `CostCenters` WHERE ID = " . $_in["mysql"]["id"]->real_escape_string ( (int) $parameters["ccid"])))
  {
    header ( $_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
    exit ();
  }
  if ( $result->num_rows != 0)
  {
    $data = $result->fetch_assoc ();
    $output["CostCenter"] = array (
      "ID" => (int) $data["ID"],
      "Description" => $data["Description"],
      "Code" => $data["Code"]
    );
  } else {
    $output["CostCenter"] = array (
      "ID" => null,
      "Description" => "",
      "Code" => ""
    );
  }

  // Call source:
  if ( empty ( $parameters["sourcetype"]) && $parameters["srcid"])
  {
    $parameters["sourcetype"] = VD_CALLTYPE_LOCAL + VD_CALLENDPOINT_EXTENSION;
  }
  $type = "Unknown";
  if ( VD_CALLTYPE_LOCAL & $parameters["sourcetype"])
  {
    $type = "Local";
  }
  if ( VD_CALLTYPE_INTERSTATE & $parameters["sourcetype"])
  {
    $type = "Interstate";
  }
  if ( VD_CALLTYPE_INTERNATIONAL & $parameters["sourcetype"])
  {
    $type = "International";
  }
  $endpoint = "Unknown";
  if ( VD_CALLENDPOINT_EXTENSION & $parameters["sourcetype"])
  {
    $endpoint = "Extension";
  }
  if ( VD_CALLENDPOINT_LANDLINE & $parameters["sourcetype"])
  {
    $endpoint = "Landline";
  }
  if ( VD_CALLENDPOINT_MOBILE & $parameters["sourcetype"])
  {
    $endpoint = "Mobile";
  }
  if ( VD_CALLENDPOINT_MARINE & $parameters["sourcetype"])
  {
    $endpoint = "Marine";
  }
  if ( VD_CALLENDPOINT_TOLLFREE & $parameters["sourcetype"])
  {
    $endpoint = "Tollfree";
  }
  if ( VD_CALLENDPOINT_SPECIAL & $parameters["sourcetype"])
  {
    $endpoint = "Special";
  }
  if ( VD_CALLENDPOINT_SATELLITE & $parameters["sourcetype"])
  {
    $endpoint = "Satellite";
  }
  if ( VD_CALLENDPOINT_SERVICES & $parameters["sourcetype"])
  {
    $endpoint = "Services";
  }
  $output["Source"] = array (
    "Type" => $type,
    "TypeI18N" => __ ( $type),
    "Endpoint" => $endpoint,
    "EndpointI18N" => __ ( $endpoint),
    "ExtensionID" => $parameters["srcid"],
    "Number" => strip_tags ( $parameters["src"])
  );

  // Call destination:
  $type = "Unknown";
  if ( VD_CALLTYPE_LOCAL & $parameters["calltype"])
  {
    $type = "Local";
  }
  if ( VD_CALLTYPE_INTERSTATE & $parameters["calltype"])
  {
    $type = "Interstate";
  }
  if ( VD_CALLTYPE_INTERNATIONAL & $parameters["calltype"])
  {
    $type = "International";
  }
  $endpoint = "Unknown";
  if ( VD_CALLENDPOINT_EXTENSION & $parameters["sourcetype"])
  {
    $endpoint = "Extension";
  }
  if ( VD_CALLENDPOINT_LANDLINE & $parameters["sourcetype"])
  {
    $endpoint = "Landline";
  }
  if ( VD_CALLENDPOINT_MOBILE & $parameters["sourcetype"])
  {
    $endpoint = "Mobile";
  }
  if ( VD_CALLENDPOINT_MARINE & $parameters["sourcetype"])
  {
    $endpoint = "Marine";
  }
  if ( VD_CALLENDPOINT_TOLLFREE & $parameters["sourcetype"])
  {
    $endpoint = "Tollfree";
  }
  if ( VD_CALLENDPOINT_SPECIAL & $parameters["sourcetype"])
  {
    $endpoint = "Special";
  }
  if ( VD_CALLENDPOINT_SATELLITE & $parameters["sourcetype"])
  {
    $endpoint = "Satellite";
  }
  if ( VD_CALLENDPOINT_SERVICES & $parameters["sourcetype"])
  {
    $endpoint = "Services";
  }
  $output["Destination"] = array (
    "Type" => $type,
    "TypeI18N" => __ ( $type),
    "Endpoint" => $endpoint,
    "EndpointI18N" => __ ( $endpoint),
    "ExtensionID" => $parameters["dstid"],
    "Number" => strip_tags ( $parameters["dst"])
  );

  // Who hungup?
  $output["WhoHungUp"] = $parameters["WhoHungUp"];

  // Process call quality parameters:
  if ( ! empty ( $parameters["QOS"]))
  {
    $qos = explode_QOS ( $parameters["QOS"]);
    $mos = calculate_MOS ( $qos);
    $qualityColor = "green";
    if ( $qos["rxcount"] != 0 && $qos["txcount"] != 0)
    {
      if ( ( $qos["lp"] * 100) / $qos["rxcount"] > 0.5 || ( $qos["rlp"] * 100) / $qos["rxcount"] > 0.5)
      {
        $qualityColor = "red";
      }
      if ( $qos["txjitter"] > 30 || $qos["rxjitter"] > 30)
      {
        $qualityColor = "red";
      }
      if ( $qos["rtt"] > 200)
      {
        $qualityColor = "red";
      }
    }
  } else {
    $qos = array ();
    $mos = null;
    $qualityColor = "gray";
  }
  $output["Quality"] = array (
    "QOS" => filter_QOS ( $qos),
    "MOS" => $mos,
    "Color" => $qualityColor
  );

  // Call codec:
  $native = str_replace ( "(", "", str_replace ( ")", "", $parameters["nativecodec"]));
  $output["CODEC"] = array (
    "Native" => defined ( "VD_AUDIO_CODEC_" . strtoupper ( $native)) ? constant ( "VD_AUDIO_CODEC_" . strtoupper ( $native)) : $native,
    "Read" => defined ( "VD_AUDIO_CODEC_" . strtoupper ( $parameters["readcodec"])) ? constant ( "VD_AUDIO_CODEC_" . strtoupper ( $parameters["readcodec"])) : $parameters["readcodec"],
    "Write" => defined ( "VD_AUDIO_CODEC_" . strtoupper ( $parameters["writecodec"])) ? constant ( "VD_AUDIO_CODEC_" . strtoupper ( $parameters["writecodec"])) : $parameters["writecodec"]
  );

  // Call identificators:
  $output["UniqueID"] = $parameters["uniqueid"];
  $output["LinkedID"] = $parameters["linkedid"];
  $output["SIPID"] = $parameters["SIPID"];

  // Call recording availability:
  $output["Recorded"] = is_readable ( $_in["general"]["storagedir"] . "/recordings/" . basename ( $parameters["SIPID"]) . ".mp3");

  // Call capture availability:
  $output["Captured"] = is_readable ( $_in["general"]["storagedir"] . "/captures/" . basename ( $parameters["SIPID"]) . ".pcap");

  // Call flags:
  $output["Flags"] = explode ( "|", $parameters["flags"]);

  /**
   * Return processed record
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), $output);
}

/**
 * Add call SIP dump processing filter
 */
framework_add_filter ( "process_sipdump", "process_sipdump");

/**
 * Function to process a SIP network dump file, and return a complex structure analysis of file.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function process_sipdump ( $buffer, $parameters)
{
  /**
   * Check if required parameters are given, and if SIP dump file is readable
   */
  if ( empty ( $parameters["Filename"]) || ! is_readable ( $parameters["Filename"]))
  {
    return $buffer;
  }
  if ( ! $fp = fopen ( $parameters["Filename"], "r"))
  {
    return $buffer;
  }

  /**
   * Start processing file
   */
  $players = array ();
  $flow = array ();

  // Read global pcap header
  $buffer = unpack ( "Nmagic_number/vversion_major/vversion_minor/lthiszone/Vsigfigs/Vsnaplen/Vnetwork", fread ( $fp, 24));

  // Read each packet and process it
  $frame = 0;
  while ( $data = fread ( $fp, 16))
  {
    $frame++;
    // Read packet header
    $buffer = unpack ( "Vts_sec/Vts_usec/Vincl_len/Vorig_len", $data);

    // Read packet content
    $packetData = fread ( $fp, $buffer["incl_len"]);

    // Read ethernet frame
    $ethernet = unpack ( "H12dst_mac/H12src_mac/nethertype", $packetData);
    $decoded = array (
      "Ethernet" => array (
        "src_mac" => substr ( $ethernet["src_mac"], 0, 2) . ":" . substr ( $ethernet["src_mac"], 2, 2) . ":" . substr ( $ethernet["src_mac"], 4, 2) . ":" . substr ( $ethernet["src_mac"], 6, 2) . ":" . substr ( $ethernet["src_mac"], 8, 2) . ":" . substr ( $ethernet["src_mac"], 10, 2),
        "dst_mac" => substr ( $ethernet["dst_mac"], 0, 2) . ":" . substr ( $ethernet["dst_mac"], 2, 2) . ":" . substr ( $ethernet["dst_mac"], 4, 2) . ":" . substr ( $ethernet["dst_mac"], 6, 2) . ":" . substr ( $ethernet["dst_mac"], 8, 2) . ":" . substr ( $ethernet["dst_mac"], 10, 2),
        "type" => $ethernet["ethertype"]
      )
    );

    // Process packet data
    switch ( $ethernet["ethertype"])
    {
      // 0x0800: IP (Internet Protocol) packet data
      case 0x0800:
        process_ip_packet ( substr ( $packetData, 14), $decoded);
        break;
      // 0x0806: ARP (Address Resolution Protocol) packet data
      case 0x0806:
        process_arp_packet ( substr ( $packetData, 14), $decoded);
        break;
      // 0x8100: VLAN-tagged frame (IEEE 802.1Q) and Shortest Path Bridging IEEE 802.1aq
      case 0x8100:
        process_vlan_packet ( substr ( $packetData, 14), $decoded);
        break;
      default:
        $decoded["Ethernet"]["Unknown Payload"] = substr ( $packetData, 14);
        break;
    }

    // If it's an UDP packet, process it
    if ( $decoded["Ethernet"]["type"] == 0x0800 && $decoded["IP"]["protocol"] == 0x11)
    {
      // Check if it's a packet containing SIP information
      if ( ! preg_match ( "/^SIP\/[0-9]+\.[0-9] /", str_replace ( "\r", "", substr ( $decoded["Data"], 0, strpos ( $decoded["Data"], "\n")))) && ! preg_match ( "/ SIP\/[0-9]+\.[0-9]$/", str_replace ( "\r", "", substr ( $decoded["Data"], 0, strpos ( $decoded["Data"], "\n")))))
      {
        continue;
      }

      // It's an UDP SIP packet! Process it
      $inheaders = true;
      $pack = array ( "Type" => "", "Headers" => array (), "Body" => array ());
      foreach ( explode ( "\n", str_replace ( "\r", "", $decoded["Data"])) as $line => $data)
      {
        if ( $line == 0)
        {
          if ( preg_match ( "/ SIP\/[0-9]+\.[0-9]$/", $data))
          {
            $pack["Type"] = "Request";
            $pack["Request-Line"] = array ( "Method" => substr ( $data, 0, strpos ( $data, " ")), "URI" => substr ( $data, strpos ( $data, " ") + 1, strrpos ( $data, " ") - strpos ( $data, " ") - 1), "Version" => substr ( $data, strrpos ( $data, " ") + 1));
          } else {
            $pack["Type"] = "Response";
            $pack["Status-Line"] = array ( "Code" => substr ( $data, strpos ( $data, " ") + 1, strrpos ( $data, " ") - strpos ( $data, " ") - 1), "Result" => substr ( $data, strpos ( $data, " ") + 2 + strlen ( substr ( $data, strpos ( $data, " ") + 1, strrpos ( $data, " ") - strpos ( $data, " ") - 1))), "Version" => substr ( $data, 0, strpos ( $data, " ")));
          }
          continue;
        }
        if ( $data == "")
        {
          $inheaders = false;
          continue;
        }
        if ( $inheaders)
        {
          $type = substr ( $data, 0, strpos ( $data, ": "));
          $content = substr ( $data, strpos ( $data, ": ") + 2);
          if ( $type == "Via")
          {
            if ( ! array_key_exists ( "Via", $pack["Headers"]))
            {
              $pack["Headers"]["Via"] = array ();
            }
            $pack["Headers"]["Via"][] = $content;
          } else {
            $pack["Headers"][$type] = $content;
          }
        } else {
          $pack["Body"][] = $data;
        }
      }

      // First, check if all players are added
      if ( ! array_key_exists ( $decoded["IP"]["source_add"] . ":" . $decoded["UDP"]["source_port"], $players))
      {
        if ( array_key_exists ( "User-Agent", $pack["Headers"]))
        {
          $players[$decoded["IP"]["source_add"] . ":" . $decoded["UDP"]["source_port"]] = array ( "UA" => $pack["Headers"]["User-Agent"], "Type" => "User");
        } else {
          $players[$decoded["IP"]["source_add"] . ":" . $decoded["UDP"]["source_port"]] = array ( "UA" => $pack["Headers"]["Server"], "Type" => "Server");
        }
      } else {
        if ( empty ( $players[$decoded["IP"]["source_add"] . ":" . $decoded["UDP"]["source_port"]]["UA"]))
        {
          $players[$decoded["IP"]["source_add"] . ":" . $decoded["UDP"]["source_port"]]["UA"] = ( array_key_exists ( "User-Agent", $pack["Headers"]) ? $pack["Headers"]["User-Agent"] : $pack["Headers"]["Server"]);
          $players[$decoded["IP"]["source_add"] . ":" . $decoded["UDP"]["source_port"]]["Type"] = ( array_key_exists ( "User-Agent", $pack["Headers"]) ? "User" : "Server");
        }
      }
      if ( ! array_key_exists ( $decoded["IP"]["dest_add"] . ":" . $decoded["UDP"]["dest_port"], $players))
      {
        $players[$decoded["IP"]["dest_add"] . ":" . $decoded["UDP"]["dest_port"]] = array ( "UA" => "", "Type" => "");
      }

      $flow[] = array ( "src" => $decoded["IP"]["source_add"] . ":" . $decoded["UDP"]["source_port"], "dst" => $decoded["IP"]["dest_add"] . ":" . $decoded["UDP"]["dest_port"], "date" => array ( "Full" => date ( "Y-m-d h:i:s", $buffer["ts_sec"]) . "." . $buffer["ts_usec"], "timestamp" => $buffer["ts_sec"], "usec" => $buffer["ts_usec"]), "content" => $pack, "payload" => $decoded["Data"]);
    }
  }
  fclose ( $fp);

  // Build diagram script and text flow of conversation
  $diagram = "";
  $text = "";
  $id = 0;
  $part = array ();
  foreach ( $players as $player => $data)
  {
    $id++;
    $part[$player] = $id;
    $diagram .= "Participant " . str_replace ( ":", "|", $player) . " as " . $id . "\n";
  }
  $ts = 0;
  foreach ( $flow as $data)
  {
    $diagram .= $part[$data["src"]] . ( $data["content"]["Type"] == "Response" ? "--" : "-") . ">" . $part[$data["dst"]] . ": " . ( $data["content"]["Type"] == "Response" ? $data["content"]["Status-Line"]["Code"] . " " . $data["content"]["Status-Line"]["Result"] : $data["content"]["Request-Line"]["Method"]) . "\n";
    $diagram .= "Note left of 1: ";
    if ( $ts != 0)
    {
      $diagram .= "(+" . sprintf ( "%.06f", (float) ( $data["date"]["timestamp"] . "." . $data["date"]["usec"]) - $ts) . ") ";
    }
    $ts = (float) ( $data["date"]["timestamp"] . "." . $data["date"]["usec"]);
    $diagram .= date ( "h:i:s", $data["date"]["timestamp"]) . "." . sprintf ( "%06d", $data["date"]["usec"]) . "\n";
    $text .= date ( "M/d/Y h:i:s", $data["date"]["timestamp"]) . "." . sprintf ( "%06d", $data["date"]["usec"]) . " " . $data["src"] . " -> " . $data["dst"] . "\n";
    $text .= "--------------------------------------------------------------------------------\n";
    $text .= $data["payload"];
    $text .= "--------------------------------------------------------------------------------\n\n";
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "diagram" => $diagram, "text" => $text, "pcap" => base64_encode ( file_get_contents ( $parameters["Filename"]))));
}

/**
 * Process VLAN-tagger frame packet.
 * Reference: https://en.wikipedia.org/wiki/IEEE_802.1Q
 *
 * @param string $packet Binary string with packet content
 * @param array &$decoded Pointer to the packet information array
 * @return void
 */
function process_vlan_packet ( $packet, &$decoded)
{
  // Read packet vlan frame
  $vlan = unpack ( "ntci/nethertype", $packet);
  if ( ! array_key_exists ( "VLAN", $decoded))
  {
    $decoded["VLAN"] = array ();
  }
  $decoded["VLAN"][] = array (
    "TCI" => $vlan["tci"],
    "PCP" => $vlan["tci"] >> 13,
    "DEI" => $vlan["tci"] >> 12 & 1,
    "VID" => $vlan["tci"] & 0x0FFF,
    "type" => $vlan["ethertype"]
  );

  // Process packet data
  switch ( $vlan["ethertype"])
  {
    // 0x0800: IP (Internet Protocol) packet data
    case 0x0800:
      process_ip_packet ( substr ( $packet, 4), $decoded);
      break;
    // 0x0806: ARP (Address Resolution Protocol) packet data
    case 0x0806:
      process_arp_packet ( substr ( $packet, 4), $decoded);
      break;
    // 0x8100: VLAN-tagged frame (IEEE 802.1Q) and Shortest Path Bridging IEEE 802.1aq
    case 0x8100:
      process_vlan_packet ( substr ( $packet, 4), $decoded);
      break;
    default:
      $decoded["VLAN"][count ( $decoded["VLAN"]) - 1]["Unknown Payload"] = substr ( $packet, 4);
      break;
  }

  return;
}

/**
 * Process ARP packet.
 * Reference: http://www.tcpipguide.com/free/t_ARPMessageFormat.htm
 *
 * @param string $packet Binary string with packet content
 * @param array &$decoded Pointer to the packet information array
 * @return void
 */
function process_arp_packet ( $packet, &$decoded)
{
  // Read ARP packet
  $arp = unpack ( "nhwtype/nprottype/Chwaddrlen/Cprotaddrlen/nopcode", $packet);
  $decoded["ARP"] = array (
    // Type of hardware:
    // 1 = Ethernet
    // 6 = IEEE 802 Networks
    // 7 = ARCNET
    // 15 = Frame Relay
    // 16 = ATM (Asynchronous Transfer Mode)
    // 17 = HDLC
    // 18 = Fibre Channel
    // 19 = ATM (Asynchronous Transfer Mode)
    // 20 = Serial Line
    "hwtype" => $arp["hwtype"],
    "prttype" => $arp["prottype"],
    "hwaddrlen" => $arp["hwaddrlen"],
    "protaddrlen" => $arp["protaddrlen"],
    // OPCODE:
    // 1 = ARP Request
    // 2 = ARP Reply
    // 3 = RARP Request
    // 4 = RARP Reply
    // 5 = DRARP Request
    // 6 = DRARP Reply
    // 7 = DRARP Error
    // 8 = InARP Request
    // 9 = InARP Reply
    "opcode" => $arp["opcode"]
  );

  // Read addresses
  $addrs = unpack ( "H" . ( $arp["hwaddrlen"] * 2) . "src_hwaddr/Nsrc_ip/H" . ( $arp["hwaddrlen"] * 2) . "dst_hwaddr/Ndst_ip", substr ( $packet, 8));
  $decoded["ARP"]["src_hwaddr"] = substr ( $addrs["src_hwaddr"], 0, 2) . ":" . substr ( $addrs["src_hwaddr"], 2, 2) . ":" . substr ( $addrs["src_hwaddr"], 4, 2) . ":" . substr ( $addrs["src_hwaddr"], 6, 2) . ":" . substr ( $addrs["src_hwaddr"], 8, 2) . ":" . substr ( $addrs["src_hwaddr"], 10, 2);
  $decoded["ARP"]["src_ip"] = long2ip ( $addrs["src_ip"]);
  $decoded["ARP"]["dst_hwaddr"] = substr ( $addrs["dst_hwaddr"], 0, 2) . ":" . substr ( $addrs["dst_hwaddr"], 2, 2) . ":" . substr ( $addrs["dst_hwaddr"], 4, 2) . ":" . substr ( $addrs["dst_hwaddr"], 6, 2) . ":" . substr ( $addrs["dst_hwaddr"], 8, 2) . ":" . substr ( $addrs["dst_hwaddr"], 10, 2);
  $decoded["ARP"]["dst_ip"] = long2ip ( $addrs["dst_ip"]);

  return;
}

/**
 * Process IP packet.
 *
 * @param string $packet Binary string with packet content
 * @param array &$decoded Pointer to the packet information array
 * @return void
 */
function process_ip_packet ( $packet, &$decoded)
{
  $p = unpack ( "Cip_ver_len/Ctos/ntot_len/", $packet);
  $ip_len = $p["ip_ver_len"] & 0x0F;

  if ( $ip_len == 5)
  {
    // IP Header format for unpack
    $ip_header_fmt = "Cip_ver_len/Ctos/ntot_len/nidentification/nfrag_off/Cttl/Cprotocol/nip_checksum/Nsource_add/Ndest_add/";
    $size = 20;
  } else {
    if ( $ip_len == 6)
    {
      // IP Header format for unpack
      $ip_header_fmt = "Cip_ver_len/Ctos/ntot_len/nidentification/nfrag_off/Cttl/Cprotocol/nip_checksum/Nsource_add/Ndest_add/Noptions_padding/";
      $size = 21;
    }
  }

  // Unpack the packet
  $ip = unpack ( $ip_header_fmt, $packet);

  // Prepare the unpacked data
  $decoded["IP"] = array (
    "ip_ver" => $ip["ip_ver_len"] >> 4,
    "ip_len" => $ip["ip_ver_len"] & 0x0F,
    "tos" => $ip["tos"],
    "tot_len" => $ip["tot_len"],
    "identification" => $ip["identification"],
    "frag_off" => $ip["frag_off"],
    "ttl" => $ip["ttl"],
    "protocol" => $ip["protocol"],
    "checksum" => $ip["ip_checksum"],
    "source_add" => long2ip ( $ip["source_add"]),
    "dest_add" => long2ip ( $ip["dest_add"])
  );
  // Process sub protocol
  switch ( $ip["protocol"])
  {
    // 1 = ICMP (Internet Control Message Protocol)
    // 2 = IGMP (Internet Group Management Protocol)
    // 6 = TCP (Transmission Control Protocol)
    case "6":
      print_tcp_packet ( substr ( $packet, $size), $decoded);
      break;
    // 17 = UDP (User Datagram Protocol)
    case "17":
      print_udp_packet ( substr ( $packet, $size), $decoded);
      break;
    // 41 = ENCAP (IPv6 encapsulation)
    // 89 = OSPF (Open Shortest Path First)
    // 132 = SCTP (Stream Control Transmission Protocol)
    default:
      $decoded["IP"]["Unknown Payload"] = substr ( $packet, $size);
      break;
  }
}

/**
 * Process an UDP packet.
 *
 * @param string $packet Binary string with packet content
 * @param array &$decoded Pointer to the packet information array
 * @return void
 */
function print_udp_packet ( $packet, &$decoded)
{
  // Unpack the packet
  $udp = unpack ( "nsource_port/ndest_port/nlength/nchecksum", $packet);

  // Prepare the unpacked data
  $decoded["UDP"] = array (
    "source_port" => $udp["source_port"],
    "dest_port" => $udp["dest_port"],
    "length" => $udp["length"],
    "checksum" => $udp["checksum"] . " [0x" . dechex ( $udp["checksum"]) . "]",
  );
  $decoded["Data"] = substr ( $packet, 8);
}

/**
 * Process a TCP packet.
 *
 * @param string $packet Binary string with packet content
 * @param array &$decoded Pointer to the packet information array
 * @return void
 */
function print_tcp_packet ( $packet, &$decoded)
{
  // First, check the length of header
  $p = unpack ( "nsource_port/ndest_port/Nsequence_number/Nacknowledgement_number/Coffset_reserved/H*data", $packet);
  $tcp_header_len = $p["offset_reserved"] >> 4;

  if ( $tcp_header_len == 5)
  {
    // TCP Header Format for unpack
    $tcp_header_fmt = "nsource_port/ndest_port/Nsequence_number/Nacknowledgement_number/Coffset_reserved/Ctcp_flags/nwindow_size/nchecksum/nurgent_pointer/";
    $size = 40;
  } else {
    if ( $tcp_header_len == 6)
    {
      // TCP Header Format for unpack
      $tcp_header_fmt = "nsource_port/ndest_port/Nsequence_number/Nacknowledgement_number/Coffset_reserved/Ctcp_flags/nwindow_size/nchecksum/nurgent_pointer/Ntcp_options_padding/";
      $size = 41;
    }
  }

  // Unpack the packet finally
  $packet = unpack ( $tcp_header_fmt . "H*data", $packet);

  // Prepare the unpacked data
  $decoded["TCP"] = array (
    "source_port" => $packet["source_port"],
    "dest_port" => $packet["dest_port"],
    "sequence_number" => $packet["sequence_number"],
    "acknowledgement_number" => $packet["acknowledgement_number"],
    "tcp_header_length" => $packet["offset_reserved"] >> 4,
    "tcp_flags" => array (
      "cwr" => ($packet["tcp_flags"] & 0x80) >> 7,
      "ecn" => ($packet["tcp_flags"] & 0x40) >> 6,
      "urgent" => ($packet["tcp_flags"] & 0x20) >> 5 ,
      "ack" => ($packet["tcp_flags"] & 0x10) >>4,
      "push" => ($packet["tcp_flags"] & 0x08)>>3,
      "reset" => ($packet["tcp_flags"] & 0x04)>>2,
      "syn" => ($packet["tcp_flags"] & 0x02)>>1,
      "fin" => ($packet["tcp_flags"] & 0x01),
    ),
    "window_size" => $packet["window_size"],
    "checksum" => $packet["checksum"] . " [0x" . dechex ( $packet["checksum"]) . "]",
  );
  $decoded["Data"] = $packet["data"];
}
?>
