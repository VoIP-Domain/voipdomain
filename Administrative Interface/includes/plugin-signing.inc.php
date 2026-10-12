<?php
/**   ___ ___       ___ _______     ______                        __
 *   |   Y   .-----|   |   _   |   |   _  \ .-----.--------.---.-|__.-----.
 *   |.  |   |  _  |.  |.  1   |   |.  |   \|  _  |        |  _  |  |     |
 *   |.  |   |_____|.  |.  ____|   |.  |    |_____|__|__|__|___._|__|__|__|
 *   |:  1   |     |:  |:  |       |:  1    /
 *    \:.. ./      |::.|::.|       |::.. . /
 *     `---'       `---`---'       `------'
 *
 * Copyright (C) 2016-2026 Ernani José Camargo Azevedo
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
 * VoIP Domain plugin signing and verification library. Implements X.509
 * certificate-based manifest signing for plugin integrity and authenticity
 * verification using RSA-SHA256 signatures.
 *
 * Signing model: Each plugin zip contains a _signature/ directory with:
 *   - MANIFEST: SHA-256 hashes of all plugin files (sorted, excluding _signature/)
 *   - MANIFEST.sig: RSA-SHA256 detached signature by the developer (base64-encoded)
 *   - developer.pem: Developer's self-signed X.509 certificate
 *   - MANIFEST.official.sig: (optional) RSA-SHA256 signature by VoIP Domain master key
 *
 * Trust model:
 *   - VoIP Domain master key (voipdomain.pem) is distributed via RPM and the git
 *     repository. Plugins signed with this key get an "official" badge.
 *   - Developer certificates are self-signed and manually installed in the system
 *     keyring (/etc/voipdomain/keyring/developers/) by the administrator.
 *   - A plugin signed by a recognized developer is "trusted". If it also carries
 *     a valid MANIFEST.official.sig, it is "official".
 *
 * @author     Ernani José Camargo Azevedo <azevedo@voipdomain.io>
 * @version    2.0
 * @package    VoIP Domain
 * @subpackage Core
 * @copyright  2016-2026 Ernani José Camargo Azevedo. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * Get the system keyring directory path.
 *
 * @return string
 */
function plugin_signing_keyring_path ()
{
  return "/etc/voipdomain/keyring/";
}

/**
 * Load the signing policy from the keyring policy.conf file.
 *
 * @return string One of: "block", "warn", "allow_unsigned"
 */
function plugin_get_signing_policy ()
{
  $policyfile = plugin_signing_keyring_path () . "policy.conf";
  if ( ! is_readable ( $policyfile))
  {
    return "block";
  }
  $config = parse_ini_file ( $policyfile, true);
  if ( is_array ( $config) && array_key_exists ( "signing", $config) && array_key_exists ( "policy", $config["signing"]))
  {
    $policy = strtolower ( trim ( $config["signing"]["policy"]));
    if ( in_array ( $policy, array ( "block", "warn", "allow_unsigned")))
    {
      return $policy;
    }
  }
  return "block";
}

/**
 * Check if embedded (untrusted) certificates should be accepted during
 * verification when the developer certificate is not found in the keyring.
 *
 * @return boolean
 */
function plugin_signing_trust_embedded ()
{
  $policyfile = plugin_signing_keyring_path () . "policy.conf";
  if ( ! is_readable ( $policyfile))
  {
    return false;
  }
  $config = parse_ini_file ( $policyfile, true);
  if ( is_array ( $config) && array_key_exists ( "signing", $config) && array_key_exists ( "trust_embedded_keys", $config["signing"]))
  {
    return filter_var ( $config["signing"]["trust_embedded_keys"], FILTER_VALIDATE_BOOLEAN);
  }
  return false;
}

/**
 * Load the VoIP Domain master public key from the system keyring.
 *
 * @return OpenSSLAsymmetricKey|false Public key resource or false
 */
function plugin_load_master_key ()
{
  $path = plugin_signing_keyring_path () . "voipdomain.pem";
  if ( ! is_readable ( $path))
  {
    return false;
  }
  $pem = file_get_contents ( $path);
  $cert = openssl_x509_read ( $pem);
  if ( $cert === false)
  {
    return false;
  }
  return openssl_pkey_get_public ( $cert);
}

/**
 * Search the system keyring for a developer certificate matching the given
 * fingerprint. Scans /etc/voipdomain/keyring/developers/*.pem.
 *
 * @param string $fingerprint SHA-256 fingerprint of the developer certificate
 * @return OpenSSLAsymmetricKey|false Public key if found, false otherwise
 */
function plugin_find_developer_in_keyring ( $fingerprint)
{
  $keyring = plugin_signing_keyring_path ();
  $searchpaths = array ();

  $rootfiles = glob ( $keyring . "*.pem");
  if ( is_array ( $rootfiles))
  {
    $searchpaths = $rootfiles;
  }

  $devdir = $keyring . "developers/";
  if ( is_dir ( $devdir))
  {
    $devfiles = glob ( $devdir . "*.pem");
    if ( is_array ( $devfiles))
    {
      $searchpaths = array_merge ( $searchpaths, $devfiles);
    }
  }

  foreach ( $searchpaths as $file)
  {
    $pem = file_get_contents ( $file);
    $cert = openssl_x509_read ( $pem);
    if ( $cert === false)
    {
      continue;
    }
    $fp = openssl_x509_fingerprint ( $cert, "sha256");
    if ( strtolower ( $fp) === strtolower ( $fingerprint))
    {
      $pubkey = openssl_pkey_get_public ( $cert);
      if ( $pubkey !== false)
      {
        return $pubkey;
      }
    }
  }
  return false;
}

/**
 * Compute the SHA-256 fingerprint of an X.509 certificate in PEM format.
 *
 * @param string $cert_pem Certificate in PEM format
 * @return string|false Hex fingerprint or false on error
 */
function plugin_cert_fingerprint ( $cert_pem)
{
  $cert = openssl_x509_read ( $cert_pem);
  if ( $cert === false)
  {
    return false;
  }
  return openssl_x509_fingerprint ( $cert, "sha256");
}

/**
 * Generate the MANIFEST content for a plugin directory. Computes SHA-256 hashes
 * of all files, sorted alphabetically, excluding the _signature/ directory.
 *
 * @param string $plugindir Full path to extracted plugin directory
 * @param string $pluginname Plugin directory name
 * @param string $developer Developer identifier (for manifest header)
 * @return string MANIFEST file content
 */
function plugin_generate_manifest ( $plugindir, $pluginname, $developer = "")
{
  $basedir = rtrim ( $plugindir, "/") . "/" . $pluginname;
  if ( ! is_dir ( $basedir))
  {
    $basedir = rtrim ( $plugindir, "/");
  }
  $files = plugin_signing_scan_files ( $basedir);
  sort ( $files);

  $lines = array ();
  $lines[] = "#!voipdomain-manifest-v1";
  $lines[] = "# Developer: " . ( $developer != "" ? $developer : "unknown");
  $lines[] = "# Signed: " . date ( "c");
  $lines[] = "# Files: " . sizeof ( $files);
  foreach ( $files as $relpath)
  {
    $fullpath = $basedir . "/" . $relpath;
    $hash = hash_file ( "sha256", $fullpath);
    $lines[] = "SHA256:" . $hash . "  " . $relpath;
  }

  return implode ( "\n", $lines) . "\n";
}

/**
 * Recursively scan a plugin directory for files, excluding _signature/.
 *
 * @param string $basedir Base directory to scan
 * @param string $prefix Current relative path prefix
 * @return array List of relative file paths
 */
function plugin_signing_scan_files ( $basedir, $prefix = "")
{
  $files = array ();
  $dir = $prefix == "" ? $basedir : $basedir . "/" . $prefix;
  foreach ( scandir ( $dir) as $entry)
  {
    if ( $entry == "." || $entry == "..")
    {
      continue;
    }
    $relpath = $prefix == "" ? $entry : $prefix . "/" . $entry;
    if ( $entry == "_signature" && $prefix == "")
    {
      continue;
    }
    $fullpath = $basedir . "/" . $relpath;
    if ( is_dir ( $fullpath))
    {
      $files = array_merge ( $files, plugin_signing_scan_files ( $basedir, $relpath));
    } else {
      $files[] = $relpath;
    }
  }
  return $files;
}

/**
 * Verify the cryptographic signature of an extracted plugin. Checks the
 * developer signature against the keyring, and optionally verifies the
 * VoIP Domain official signature for the "official" badge.
 *
 * @param string $extractdir Directory where the plugin was extracted (contains {name}/ subdir)
 * @param string $pluginname Plugin directory name
 * @return array Result: [valid => bool, developer => string, trusted => bool, official => bool, unsigned => bool, errors => []]
 */
function plugin_verify_signature ( $extractdir, $pluginname)
{
  $result = array (
    "valid" => false,
    "developer" => "",
    "trusted" => false,
    "official" => false,
    "unsigned" => false,
    "errors" => array ()
  );

  $plugindir = rtrim ( $extractdir, "/") . "/" . $pluginname;
  $sigdir = $plugindir . "/_signature";

  if ( ! is_dir ( $sigdir))
  {
    $result["unsigned"] = true;
    $result["errors"][] = "Plugin is not signed (no _signature/ directory).";
    return $result;
  }

  $manifestpath = $sigdir . "/MANIFEST";
  $sigpath = $sigdir . "/MANIFEST.sig";
  $certpath = $sigdir . "/developer.pem";

  if ( ! is_readable ( $manifestpath))
  {
    $result["errors"][] = "Missing _signature/MANIFEST file.";
    return $result;
  }
  if ( ! is_readable ( $sigpath))
  {
    $result["errors"][] = "Missing _signature/MANIFEST.sig file.";
    return $result;
  }
  if ( ! is_readable ( $certpath))
  {
    $result["errors"][] = "Missing _signature/developer.pem certificate.";
    return $result;
  }

  $manifest_content = file_get_contents ( $manifestpath);
  $sig_b64 = trim ( file_get_contents ( $sigpath));
  $sig_raw = base64_decode ( $sig_b64, true);

  if ( $sig_raw === false)
  {
    $result["errors"][] = "Invalid signature format in MANIFEST.sig (not valid base64).";
    return $result;
  }

  if ( preg_match ( '/^# Developer: (.+)$/m', $manifest_content, $m))
  {
    $result["developer"] = trim ( $m[1]);
  }

  $dev_cert_pem = file_get_contents ( $certpath);
  $dev_cert = openssl_x509_read ( $dev_cert_pem);
  if ( $dev_cert === false)
  {
    $result["errors"][] = "Invalid developer certificate format.";
    return $result;
  }

  $cert_info = openssl_x509_parse ( $dev_cert);
  if ( is_array ( $cert_info))
  {
    if ( isset ( $cert_info["validTo_time_t"]) && $cert_info["validTo_time_t"] < time ())
    {
      $result["errors"][] = "Developer certificate has expired.";
      return $result;
    }
    if ( $result["developer"] === "" || $result["developer"] === "unknown")
    {
      $result["developer"] = $cert_info["subject"]["CN"] ?? "unknown";
    }
  }

  $fingerprint = plugin_cert_fingerprint ( $dev_cert_pem);
  $pubkey = false;

  if ( $fingerprint !== false)
  {
    $pubkey = plugin_find_developer_in_keyring ( $fingerprint);
  }

  if ( $pubkey !== false)
  {
    $result["trusted"] = true;
  } else {
    if ( plugin_signing_trust_embedded ())
    {
      $pubkey = openssl_pkey_get_public ( $dev_cert);
      if ( $pubkey !== false)
      {
        $result["errors"][] = "Using embedded certificate (not in system keyring) — authenticity cannot be guaranteed.";
      }
    }
    if ( $pubkey === false)
    {
      $result["errors"][] = "Developer certificate not found in keyring (fingerprint: " . ( $fingerprint !== false ? substr ( $fingerprint, 0, 16) . "..." : "unknown") . ").";
      return $result;
    }
  }

  $verify = openssl_verify ( $manifest_content, $sig_raw, $pubkey, OPENSSL_ALGO_SHA256);
  if ( $verify !== 1)
  {
    if ( $verify === 0)
    {
      $result["errors"][] = "Signature verification FAILED — manifest was tampered or wrong key.";
    } else {
      $result["errors"][] = "Signature verification error: " . openssl_error_string ();
    }
    return $result;
  }

  $hash_errors = plugin_verify_manifest_hashes ( $plugindir, $manifest_content);
  if ( sizeof ( $hash_errors) > 0)
  {
    $result["errors"] = array_merge ( $result["errors"], $hash_errors);
    return $result;
  }

  $result["valid"] = true;

  $officialpath = $sigdir . "/MANIFEST.official.sig";
  if ( is_readable ( $officialpath))
  {
    $official_b64 = trim ( file_get_contents ( $officialpath));
    $official_raw = base64_decode ( $official_b64, true);
    if ( $official_raw !== false)
    {
      $master_key = plugin_load_master_key ();
      if ( $master_key !== false)
      {
        $official_verify = openssl_verify ( $manifest_content, $official_raw, $master_key, OPENSSL_ALGO_SHA256);
        if ( $official_verify === 1)
        {
          $result["official"] = true;
        }
      }
    }
  }

  return $result;
}

/**
 * Verify that every file listed in the MANIFEST has the correct SHA-256 hash,
 * and that no extra files exist outside the manifest.
 *
 * @param string $plugindir Full path to the plugin directory
 * @param string $manifest_content Raw MANIFEST file content
 * @return array List of error messages (empty if all good)
 */
function plugin_verify_manifest_hashes ( $plugindir, $manifest_content)
{
  $errors = array ();
  $manifest_files = array ();

  $lines = explode ( "\n", $manifest_content);
  foreach ( $lines as $line)
  {
    $line = trim ( $line);
    if ( $line == "" || $line[0] == "#")
    {
      continue;
    }
    if ( preg_match ( '/^SHA256:([0-9a-f]{64})\s\s(.+)$/', $line, $m))
    {
      $expected_hash = $m[1];
      $relpath = $m[2];
      $manifest_files[$relpath] = $expected_hash;

      $fullpath = $plugindir . "/" . $relpath;
      if ( ! is_readable ( $fullpath))
      {
        $errors[] = "File listed in manifest but missing: " . $relpath;
        continue;
      }
      $actual_hash = hash_file ( "sha256", $fullpath);
      if ( $actual_hash !== $expected_hash)
      {
        $errors[] = "Hash mismatch for: " . $relpath . " (expected " . substr ( $expected_hash, 0, 12) . "..., got " . substr ( $actual_hash, 0, 12) . "...)";
      }
    }
  }

  $actual_files = plugin_signing_scan_files ( $plugindir);
  foreach ( $actual_files as $relpath)
  {
    if ( ! array_key_exists ( $relpath, $manifest_files))
    {
      $errors[] = "File not listed in manifest (possible injection): " . $relpath;
    }
  }

  return $errors;
}

/**
 * Apply signing policy to a verification result. Returns error message if the
 * plugin should be rejected, or null if installation should proceed.
 *
 * @param array $verification Result from plugin_verify_signature()
 * @return string|null Error message if blocked, null if allowed
 */
function plugin_apply_signing_policy ( $verification)
{
  $policy = plugin_get_signing_policy ();

  if ( $policy === "allow_unsigned")
  {
    return null;
  }

  if ( $verification["unsigned"])
  {
    if ( $policy === "block")
    {
      return __ ( "Plugin is not signed. System policy requires signed plugins. Contact administrator to change signing policy.");
    }
    return null;
  }

  if ( ! $verification["valid"])
  {
    if ( $policy === "block" || $policy === "warn")
    {
      return __ ( "Plugin signature verification failed: ") . implode ( "; ", $verification["errors"]);
    }
    return null;
  }

  return null;
}
?>
