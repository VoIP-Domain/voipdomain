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
 * VoIP Domain main framework interface filter module. This module add the
 * generic system filter calls.
 *
 * @author     Ernani José Camargo Azevedo <azevedo@intellinews.com.br>
 * @version    1.0
 * @package    VoIP Domain
 * @subpackage Interface
 * @copyright  2016-2018 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Add call report processing filter
 */
framework_add_filter ( "process_call", "process_call");

/**
 * Function to process a call CDR record to generate report output.
 *
 * @param string $buffer Buffer from plugin system if processed by other function
 *                       before
 * @param array $parameters Optional parameters to the function
 * @return array Menus structure
 */
function process_call ( $buffer, $parameters)
{
  if ( $parameters["userfield"] == "DND")
  {
    $parameters["disposition"] = "NO ANSWER";
  }
  switch ( $parameters["disposition"])
  {
    case "ANSWERED":
      $result = __ ( "Answered");
      if ( $parameters["lastapp"] == "VoiceMail")
      {
        $result .= " (" . __ ( "Voice mail") . ")";
      }
      break;
    case "NO ANSWER":
      $result = __ ( "Not answered");
      break;
    case "BUSY":
      $result = __ ( "Busy");
      break;
    case "FAILED":
      $result = __ ( "Call failure");
      break;
    default:
      $result = __ ( "Unknown") . ": " . strip_tags ( $parameters["disposition"]);
      break;
  }
  if ( strpos ( $parameters["userfield"], "(") !== false)
  {
    $parameters["userfieldextra"] = preg_replace ( "/\)$/", "", substr ( $parameters["userfield"], strpos ( $parameters["userfield"], "(") + 1));
    $parameters["userfield"] = substr ( $parameters["userfield"], 0, strpos ( $parameters["userfield"], "("));
  }
  switch ( $parameters["userfield"])
  {
    case "blacklisted":
      $notes = __ ( "Denied by blacklist!");
      break;
    case "passok":
      $notes = __ ( "Valid password!");
      break;
    case "passcache":
      $notes = __ ( "Cached password!");
      break;
    case "passfail":
      $notes = __ ( "Wrong password!");
      break;
    case "nopass":
      $notes = __ ( "Exception (call without password)");
      break;
    case "block":
      $notes = __ ( "Blocked");
      break;
    case "DND":
      $notes = __ ( "Do not disturb!");
      break;
    case "capture":
      $notes = __ ( "Picked up call");
      break;
    case "transhipment":
      $notes = __ ( "Transhiped");
      break;
    case "central":
      $notes = __ ( "Central");
      break;
    case "":
      $notes = "";
      break;
    default:
      $notes = __ ( "Unknown") . ": " . strip_tags ( $parameters["userfield"]);
      break;
  }
  if ( ! empty ( $parameters["userfieldextra"]))
  {
    $notes .= " (" . $parameters["userfieldextra"] . ")";
  }
  if ( ! $parameters["extension"] || ( $parameters["src"] == $parameters["extension"] && $parameters["src"] != $parameters["dst"]))
  {
    switch ( $parameters["calltype"])
    {
      case "1":
        $type = "extension";
        break;
      case "2":
        $type = "landline";
        break;
      case "3":
        $type = "mobile";
        break;
      case "4":
        $type = "interstate";
        break;
      case "5":
        $type = "international";
        break;
      case "6":
        $type = "special";
        break;
      case "7":
        $type = "tollfree";
        break;
      case "8":
        $type = "services";
        break;
      default:
        $type = "unknown";
        break;
    }
  } else {
    $type = "inbound";
  }
  if ( $parameters["processed"])
  {
    $cost = number_format ( $parameters["value"], 2, ",", ".");
  } else {
    $cost = __ ( "N/A");
  }
  if ( ! empty ( $parameters["QOSa"]))
  {
    $quality = number_format ( calculateMOS ( $parameters["QOSa"], $parameters["QOSb"]), 2, ",", ".");
  } else {
    $quality = __ ( "N/A");
  }
  $qualityColor = "green";
  $qosa = explodeQOS ( $parameters["QOSa"]);
  $qosb = explodeQOS ( $parameters["QOSb"]);
  if ( ! empty ( $parameters["QOSa"]) && ! empty ( $parameters["QOSb"]) && $qosa["rxcount"] != 0 && $qosb["rxcount"] != 0)
  {
    if ( ( $qosa["lp"] * 100) / $qosa["rxcount"] > 0.5 || ( $qosb["lp"] * 100) / $qosb["rxcount"] > 0.5)
    {
      $qualityColor = "red";
    }
    if ( $qosa["txjitter"] > 30 || $qosb["txjitter"] > 30)
    {
      $qualityColor = "red";
    }
    if ( $qosa["rtt"] > 200 || $qosb["rtt"] > 200)
    {
      $qualityColor = "red";
    }
  }
  $output = array ();
  $output[] = format_db_timestamp ( $parameters["calldate"]);
  $output[] = format_db_datetime ( $parameters["calldate"]);
  $output[] = ( $parameters["src"] == $parameters["extension"] && $parameters["src"] != $parameters["dst"] ? "out" : "in");
  $output[] = array ( "type" => substr ( $parameters["src"], 0, 1) == "+" ? "external" : "internal", "number" => strip_tags ( $parameters["src"]));
  $output[] = array ( "type" => $type, "number" => strip_tags ( $parameters["dst"]));
  $output[] = $parameters["billsec"];
  $output[] = $result;
  $output[] = $type;
  $output[] = number_format ( $parameters["value"], 2, __ ( "."), __ ( ","));
  $output[] = $cost;
  $output[] = str_replace ( ",", "", str_replace ( "N/D", "000", $quality));
  $output[] = array ( "qos" => ( ! empty ( $parameters["QOSa"]) && ! empty ( $parameters["QOSb"]) ? array ( "lossrx" => sprintf ( "%.2f", ( $qosa["rxcount"] != 0 ? ( $qosa["lp"] * 100) / $qosa["rxcount"] : 0)), "losstx" => sprintf ( "%.2f", ( $qosb["rxcount"] != 0 ? ( $qosb["lp"] * 100) / $qosb["rxcount"] : 0)), "jitterrx" => sprintf ( "%.2f", $qosa["txjitter"]), "jittertx" => sprintf ( "%.2f", $qosb["txjitter"]), "latencyrx" => sprintf ( "%.2f", $qosa["rtt"]), "latencytx" => sprintf ( "%.2f", $qosb["rtt"])) : array ()), "quality" => $quality, "qualityColor" => $qualityColor);
  $output[] = $notes . ( ! empty ( $notes) && ! empty ( $parameters["monitor"]) ? "<br />" : "") . ( ! empty ( $parameters["monitor"]) ? "<div class=\"ubaplayer\"></div><ul class=\"ubaplayer-controls\"><li><a class=\"ubaplayer-button\" href=\"/calls/download/" . urlencode ( $parameters["uniqueid"]) . ".mp3\"></a></li></ul>": "");
  $output[] = "";
  $class = "";
  switch ( $parameters["disposition"])
  {
    case "ANSWERED":
      if ( $parameters["userfield"] == "passfail")
      {
        $class .= " danger";
      } else {
        $class .= " success";
      }
      break;
    case "BUSY":
    case "NO ANSWER":
      $class .= " warning";
      break;
    case "FAILED":
      $class .= " danger";
      break;
    default:
      break;
  }
  $output[] = substr ( $class, 1);
  $output[] = $parameters["uniqueid"];
  $output[] = $parameters["sequence"];

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
  if ( empty ( $parameters["filename"]) || ! is_readable ( $parameters["filename"]))
  {
    return $buffer;
  }
  if ( ! $fp = fopen ( $parameters["filename"], "r"))
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
      // Check if it's a packet containing SIP informations
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
    $diagram .= "=Note left of 1: ";
    if ( $ts != 0)
    {
      $diagram .= "(+" . sprintf ( "%.06f", (float) ( $data["date"]["timestamp"] . "." . $data["date"]["usec"]) - $ts) . ") ";
    }
    $ts = (float) ( $data["date"]["timestamp"] . "." . $data["date"]["usec"]);
    $diagram .= date ( "h:i:s", $data["date"]["timestamp"]) . "." . sprintf ( "%06d", $data["date"]["usec"]) . "\n";
    $text .= date ( "d/M/Y h:i:s", $data["date"]["timestamp"]) . "." . sprintf ( "%06d", $data["date"]["usec"]) . " " . $data["src"] . " -> " . $data["dst"] . "\n";
    $text .= "--------------------------------------------------------------------------------\n";
    $text .= $data["payload"];
    $text .= "--------------------------------------------------------------------------------\n\n";
  }

  /**
   * Return structured data
   */
  return array_merge_recursive ( ( is_array ( $buffer) ? $buffer : array ()), array ( "diagram" => $diagram, "text" => $text, "pcap" => base64_encode ( file_get_contents ( $parameters["filename"]))));
}

/**
 * Process VLAN-tagger frame packet.
 * Reference: https://en.wikipedia.org/wiki/IEEE_802.1Q
 *
 * @param string $packet Binary string with packet content
 * @param array &$decoded Pointer to the packet informations array
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
 * @param array &$decoded Pointer to the packet informations array
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
 * @param array &$decoded Pointer to the packet informations array
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
 * @param array &$decoded Pointer to the packet informations array
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
 * @param array &$decoded Pointer to the packet informations array
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
