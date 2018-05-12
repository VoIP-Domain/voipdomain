<?php
// Download latest Chile numbering plan XLSX file at https://www.subtel.gob.cl/inicio-concesionario/servicios-de-telecomunicaciones/servicios-publicos/,
// convert to CSV, saving to tabla_numeracion_ido_idd.csv and execute this script
// to convert.

// Open CSV file
if ( ! $fp = fopen ( "tabla_numeracion_ido_idd.csv", "r"))
{
  echo "Error: Cannot open \"tabla_numeracion_ido_idd.csv\" to read!\n";
  exit ();
}

// Discard header
$line = fgetcsv ( $fp, 1000, ",");

// Start looping till end of file
echo "Loading data...\n";
$data = array ();
while ( $line = fgetcsv ( $fp, 1000, ","))
{
  $prefix = str_replace ( " ", "", str_replace ( "X", "", $line[0]));
  switch ( $line[3])
  {
    case "Servicio Público Móvil de Radiocomunicaciones Especializado":
      $type = "VD_PHONETYPE_MARINERADIO";
      break;
    case "Servicio Público Telefonía Móvil por Satélite":
      $type = "VD_PHONETYPE_VSAT";
      break;
    case "Servicio Público Telefónico":
      $type = "VD_PHONETYPE_LANDLINE";
      break;
    case "Servicio Público Telefónico Local":
      $type = "VD_PHONETYPE_LANDLINE";
      break;
    case "Servicio Público Telefónico Móvil":
      $type = "VD_PHONETYPE_MOBILE";
      break;
    case "Servicio Público Telefónico Móvil Digital Avanzado":
      $type = "VD_PHONETYPE_MOBILE";
      break;
    case "Servicio Público de Telefonía Móvil":
      $type = "VD_PHONETYPE_MOBILE";
      break;
    case "Servicio Público de Telefonía Móvil Digital 1900":
      $type = "VD_PHONETYPE_MOBILE";
      break;
    case "Servicio Público de Voz sobre Internet":
      $type = "VD_PHONETYPE_VOIP";
      break;
    default:
      $type = "VD_PHONETYPE_UNKNOWN";
      break;
  }
  $company = str_replace ( " Tv ", " TV ", str_replace ( " S,a,", " S.A.", str_replace ( "Vtr ", "VTR ",  str_replace ( "Gtd ", "GTD ", str_replace ( " Pcs ", " PCS ", str_replace ( " Spa", " S.P.A.", str_replace ( " Spa.", " S.P.A.", str_replace ( " S.p.a.", " S.P.A.", str_replace ( " S.a.", " S.A.", trim ( mb_convert_case ( $line[4], MB_CASE_TITLE, "UTF-8")))))))))));
  if ( ! array_key_exists ( $type, $data))
  {
    $data[$type] = array ();
  }
  $data[$type][$prefix] = $company;
}

// Resume prefixes to reduce data
echo "Reducing data...\n";
do
{
  $reduced = false;
  foreach ( $data as $type => $prefixes)
  {
    foreach ( $prefixes as $prefix => $operator)
    {
      $prefix = substr ( $prefix, 0, strlen ( $prefix) - 1);
      if ( array_key_exists ( $prefix . "0", $prefixes) && $prefixes[$prefix . "0"] == $operator &&
           array_key_exists ( $prefix . "1", $prefixes) && $prefixes[$prefix . "1"] == $operator &&
           array_key_exists ( $prefix . "2", $prefixes) && $prefixes[$prefix . "2"] == $operator &&
           array_key_exists ( $prefix . "3", $prefixes) && $prefixes[$prefix . "3"] == $operator &&
           array_key_exists ( $prefix . "4", $prefixes) && $prefixes[$prefix . "4"] == $operator &&
           array_key_exists ( $prefix . "5", $prefixes) && $prefixes[$prefix . "5"] == $operator &&
           array_key_exists ( $prefix . "6", $prefixes) && $prefixes[$prefix . "6"] == $operator &&
           array_key_exists ( $prefix . "7", $prefixes) && $prefixes[$prefix . "7"] == $operator &&
           array_key_exists ( $prefix . "8", $prefixes) && $prefixes[$prefix . "8"] == $operator &&
           array_key_exists ( $prefix . "9", $prefixes) && $prefixes[$prefix . "9"] == $operator)
      {
        unset ( $data[$type][$prefix . "0"]);
        unset ( $data[$type][$prefix . "1"]);
        unset ( $data[$type][$prefix . "2"]);
        unset ( $data[$type][$prefix . "3"]);
        unset ( $data[$type][$prefix . "4"]);
        unset ( $data[$type][$prefix . "5"]);
        unset ( $data[$type][$prefix . "6"]);
        unset ( $data[$type][$prefix . "7"]);
        unset ( $data[$type][$prefix . "8"]);
        unset ( $data[$type][$prefix . "9"]);
        $data[$type][$prefix] = $operator;
        $reduced = true;
      }
    }
  }
} while ( $reduced == true);

// Sort data
foreach ( $data as $type => $prefixes)
{
  krsort ( $data[$type], SORT_NUMERIC);
}

// Dump each type:
foreach ( $data as $type => $prefixes)
{
  echo "Data for " . $type . ":\n";
  foreach ( $data[$type] as $prefix => $operator)
  {
    echo "    \"" . $prefix . "\" => \"" . $operator . "\",\n";
  }
  echo "\n\n";
}
?>
