<?php
// KAS-Abfrage durchfuehren - alle im aktuellen Login angelegten Mailinglisten lesen
$return = kas_action("kas_action=get_mailinglists");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; Mailinglisten &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=mailinglisten&action=add\">neue Mailingliste anlegen</a></p>
<br />
<p>gefundene Mailinglisten: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {

   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">Mailinglistenname</td>
      <td id=\"headline\">Passwort</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"zwischenzeile\"></td>
   </tr>";
   
   // Ergebnis-Array der KAS-Abfrage auswerten und zeilenweise ausgeben
   foreach ($return['returninfo'] as $key => $val) {
      
      $ausgabe .= "
      <tr id=\"daten\">
         <td>".str_kuerzen($val['mailinglist_name'],30)."</td>
         <td>".passwords(str_kuerzen($val['mailinglist_passwort'],30))."</td>
         <td>";
         
      // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
      // erstellt bzw. geupdated wurde
      if ($val['in_progress'] == "TRUE")
      {         
         $ausgabe .= "in Bearbeitung...";
      }
      else
      {         
         $ausgabe .= "<a href=\"?category=mailinglisten&action=delete&mailingliste=" . $val['mailinglist_name'] . "\">l&ouml;schen</a>";
      }
      
      $ausgabe .= "</td>
      </tr>
      ";      
   }   
   $ausgabe .= "
   </table>";
}
?>