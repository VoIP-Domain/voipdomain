#!/usr/bin/php -q
<?php
$prefixes = array ();
for ( $x = 1; true; $x++)
{
  $ch = curl_init ( "https://www.enacom.gob.ar/areaslocales/busqueda/" . $x);
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false);
  $content = explode ( "\n", curl_exec ( $ch));
  if ( strpos ( $content[2], "Sin resultados encontrados") !== false)
  {
    break;
  }
  foreach ( $content as $line)
  {
    $line = utf8_encode ( trim ( $line));
    if ( $line == "<tr>")
    {
      $prefix = "";
      $area = "";
      $city = "";
      continue;
    }
    if ( substr ( $line, 0, 10) == "<td style=")
    {
      if ( empty ( $prefix))
      {
        $prefix = strip_tags ( $line);
        continue;
      }
      $line = preg_replace ( "/\s+/", " ", $line);
      if ( empty ( $city))
      {
        $city = mb_convert_case ( strip_tags ( $line), MB_CASE_TITLE, "UTF-8");
        continue;
      }
      $area = mb_convert_case ( strip_tags ( $line), MB_CASE_TITLE, "UTF-8");
      continue;
    }
    if ( $line == "</tr>")
    {
      if ( strpos ( $city, " (Prov. ") !== false)
      {
        $tmp = substr ( $city, strpos ( $city, " (Prov. ") + 8);
        $tmp = substr ( $tmp, 0, strpos ( $tmp, ")"));
        if ( $tmp == $area)
        {
          $city = substr ( $city, 0, strpos ( $city, " (Prov. "));
        }
      }
      if ( $city == "Amba")
      {
        $city = "Buenos Aires Metropolitan Area (AMBA)";
      }
      if ( $area == "Amba")
      {
        $area = "Buenos Aires";
      }
      if ( array_key_exists ( $prefix, $prefixes))
      {
        $prefixes[$prefix]["city"] = "";
        if ( $prefixes[$prefix]["area"] != $area)
        {
          $prefixes[$prefix]["area"] = $prefixes[$prefix]["area"] . ", " . $area;
        }
      } else {
        $prefixes[$prefix] = array ( "city" => $city, "area" => $area);
      }
    }
  }
}
krsort ( $prefixes, SORT_NUMERIC);
foreach ( $prefixes as $prefix => $data)
{
  echo "    \"" . $prefix . "\" => array ( \"area\" => \"" . $data["area"] . "\", \"city\" => \"" . $data["city"] . "\"),\n";
}
?>
