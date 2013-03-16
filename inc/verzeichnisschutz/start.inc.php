<?php
// KAS-Abfrage durchfuehren - alle geschützten Verzeichnisse des aktuellen Logins ermitteln
$return = kas_action("kas_action=get_directoryprotection");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; Verzeichnisschutz &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=verzeichnisschutz&action=add\">neuen Verzeichnisschutz anlegen</a></p>
<br />
<p>bisher angelegte User f&uuml;r gesch&uuml;tzte Verzeichnisse: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {

   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">Verzeichnis</td>
      <td id=\"headline\">Benutzer</td>
      <td id=\"headline\">Passwort</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"4\" id=\"zwischenzeile\"></td>
   </tr>";
   
   if (is_array($return['returninfo'])) {
      // Ergebnis-Array der KAS-Abfrage auswerten und zeilenweise ausgeben
      foreach ($return['returninfo'] as $key => $val) {
         
         $ausgabe .= "
         <tr id=\"daten\">
            <td>".str_kuerzen($val['directory_path'],25)."</td>
            <td>".str_kuerzen($val['directory_user'],20)."</td>
            <td>".passwords(str_kuerzen($val['directory_password'],20))."</td>
            <td>";
         
         // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
         // erstellt bzw. geupdated wurde   
         if ($val['in_progress'] == "TRUE")
         {         
            $ausgabe .= "in Bearbeitung...";
         }
         else
         {         
            $ausgabe .= "<a href=\"?category=verzeichnisschutz&action=delete&benutzer=" . $val['directory_user'] . "&verzeichnis=" . $val['directory_path'] . "\">l&ouml;schen</a>";
         }
         
         $ausgabe .= "</td>
         </tr>
         ";      
      }
   }
   $ausgabe .= "
   </table>";   
}
?>