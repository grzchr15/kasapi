<?php
// KAS-Abfrage durchfuehren - Alle FTP-User des Accountes holen
$return = kas_action("kas_action=get_ftpusers");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; FTP-User &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=ftpuser&action=add\">neuen FTP-User anlegen</a></p>
<br />
<p>gefundene FTP-User: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {

   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">Benutzername</td>
      <td id=\"headline\">Passwort</td>
      <td id=\"headline\">Verzeichnis</td>
      <td id=\"headline\">Kommentar</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"5\" id=\"zwischenzeile\"></td>
   </tr>";
   
   // Ergebnis-Array der KAS-Abfrage auswerten und Zeilenweise ausgeben
   foreach ($return['returninfo'] as $key => $val) {
      
      $ausgabe .= "
      <tr id=\"daten\">
         <td>".str_kuerzen($val['ftp_login'],10)."</td>
         <td>".passwords(str_kuerzen($val['ftp_passwort'],20))."</td>
         <td>".str_kuerzen($val['ftp_path'],15)."</td>
         <td>".str_kuerzen($val['ftp_comment'],15)."</td>
         <td>";
         
      // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
      // erstellt bzw. geupdated wurde
      if ($val['in_progress'] == "TRUE")
      {         
         $ausgabe .= "in Bearbeitung...";
      }
      elseif($val['ftp_comment'] == "main user") // der Hauptnutzer ist nicht löschbar
      {
         $ausgabe .= "<a href=\"?category=ftpuser&action=edit&ftpuser=" . $val['ftp_login'] . "\">bearbeiten</a>";
      }
      else
      {
         $ausgabe .= "<a href=\"?category=ftpuser&action=edit&ftpuser=" . $val['ftp_login'] . "\">bearbeiten</a> | <a href=\"?category=ftpuser&action=delete&ftpuser=" . $val['ftp_login'] . "\">l&ouml;schen</a>";
      }
      
      $ausgabe .= "</td>
      </tr>
      ";      
   }   
   $ausgabe .= "
   </table>";
}
?>