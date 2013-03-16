<?php
// KAS-Abfrage - Speicherdaten auslesen
$return = kas_action("kas_action=get_space");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; Speicherbelegung</h2>\n";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben
if ($return['returnstring'] == "TRUE") {
   
   // Gesamtspecherplatz
   $gesamtspeicherplatz_verfuegbar = $return['returninfo'][0]['max_webspace'] / 1024;
   $gesamtspeicherplatz_belegt = $return['returninfo'][0]['used_webspace'] / 1024;
   $gesamtspeicherplatz_frei = $gesamtspeicherplatz_verfuegbar - ($return['returninfo'][0]['used_webspace'] / 1024);
   
   $speicherplatz_htdocs = $return['returninfo'][0]['used_htdocs_space'] / 1024;
   $speicherplatz_email = $return['returninfo'][0]['used_mailaccount_space'] / 1024;
   $speicherplatz_datenbanken = $return['returninfo'][0]['used_database_space'] / 1024;
   
   
   // Ausgabe zusammenbauen
   $ausgabe .= "
   <br />
   
<table cellspacing=\"0\">
   <tr>
      <td colspan=\"2\" id=\"headline\" style=\"width:40%\">Gesamtspeicherplatzbelegung</td>
      <td id=\"headline\" style=\"width:60%\"></td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"zwischenzeile\"></td>
   </tr>
   <tr>
      <td style=\"width:100px;\">verf&uuml;gbar:</td>
      <td>".number_format($gesamtspeicherplatz_verfuegbar, 2,',','.')." MB</td>
      <td align=\"center\" rowspan=\"3\"><img src=\"graph.php?max=".$gesamtspeicherplatz_verfuegbar."&belegt=".$gesamtspeicherplatz_belegt."\" border=\"0\" /></td>
   </tr>
   <tr>
      <td>belegt:</td>
      <td>".number_format($gesamtspeicherplatz_belegt, 2,',','.')." MB</td>
   </tr>
   <tr>
      <td>frei:</td>
      <td>".number_format($gesamtspeicherplatz_frei, 2,',','.')." MB</td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"zwischenzeile\"><br /><br /></td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"headline\">Verteilung des belegten Speicherplatzes</td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"zwischenzeile\"></td>
   </tr>
   <tr>
      <td style=\"width:100px;\">htdocs (FTP):</td>
      <td>".number_format($speicherplatz_htdocs, 2,',','.')." MB</td>
      <td align=\"center\" rowspan=\"3\"><img src=\"graph.php?gesamtbelegt=".$gesamtspeicherplatz_belegt."&htdocs=".$speicherplatz_htdocs."&email=".$speicherplatz_email."&mysql=".$speicherplatz_datenbanken."\" border=\"0\" /></td>
   </tr>
   <tr>
      <td>E-Mail:</td>
      <td>".number_format($speicherplatz_email, 2,',','.')." MB</td>
   </tr>
   <tr>
      <td>MySQL:</td>
      <td>".number_format($speicherplatz_datenbanken, 2,',','.')." MB</td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"zwischenzeile\"></td>
   </tr>
</table>";   
} else {
   $ausgabe .= "
      <br /><br /><br />
      <div style=\"text-align:center;font-weight:bold;\">Zur Zeit (noch) keine Daten verf&uuml;gbar.</div><br />
      <div style=\"text-align:center;\">M&ouml;glicherweise befindet sich dieser Account noch nicht mindestens<br />
      24 Stunden auf diesem Server. Die Daten der Speicherbelegung werden nur alle 24 Stunden erfasst / aktualisiert.</div>
   ";
}
?>