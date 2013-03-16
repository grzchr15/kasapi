<?php
// KAS-Abfrage durchfuehren - alle MySQL-Datenbanken des aktuellen Logins holen
$return = kas_action("kas_action=get_databases");
   
// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; MySQL-Datenbanken &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=datenbanken&action=add\">neue MySQL-Datenbank anlegen</a></p>
<br />
<p>gefundene MySQL-Datenbanken: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {

   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">DB-Name/Login</td>
      <td id=\"headline\">DB-Passwort</td>
      <td id=\"headline\">DB-Kommentar</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"4\" id=\"zwischenzeile\"></td>
   </tr>";
   
   // Ergebnis-Array der KAS-Abfrage auswerten und ausgeben
   foreach ($return['returninfo'] as $key => $val) {
      
      $ausgabe .= "
      <tr id=\"daten\">
         <td>".str_kuerzen($val['database_name'],10)."</td>
         <td>".passwords(str_kuerzen($val['database_password'],20))."</td>
         <td>".str_kuerzen($val['database_comment'],20)."</td>
         <td>";
         
      // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
      // erstellt bzw. geupdated wurde
      if (isset($val['in_progress']) && $val['in_progress'] == "TRUE")
      {         
         $ausgabe .= "in Bearbeitung...";
      }
      else
      {         
         $ausgabe .= "<a href=\"?category=datenbanken&action=edit&datenbank=".$val['database_name']."\">bearbeiten</a> | <a href=\"?category=datenbanken&action=delete&datenbank=".$val['database_name']."\">l&ouml;schen</a>";
      }
      
      $ausgabe .= "</td>
      </tr>
      ";      
   }   
   $ausgabe .= "
   </table>";
}
?>