<?php
// KAS-Abfrage durchfuehren - alle Domains / Cnames des aktuellen Logins holen
$return = kas_action("kas_action=get_domains");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; Domains &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=domains&action=add\">neue Domain anlegen</a></p>
<br />
<p>gefundene Domains: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {

   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">Domain</td>
      <td id=\"headline\">Ziel</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"zwischenzeile\"></td>
   </tr>";
   
   // Ergebnis-Array der KAS-Abfrage auswerten und ausgeben
   foreach ($return['returninfo'] as $key => $val) {
      
      $ausgabe .= "
      <tr id=\"daten\">
         <td>".str_kuerzen($val['domain_name'],30)."</td>
         <td>".str_kuerzen($val['domain_path'],30)."</td>
         <td>";
         
      // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
      // erstellt bzw. geupdated wurde
      if ($val['in_progress'] == "TRUE")
      {         
         $ausgabe .= "in Bearbeitung...";
      }
      else
      {         
         $ausgabe .= "<a href=\"?category=domains&action=edit&domain=" . $val['domain_name'] . "\">bearbeiten</a> | <a href=\"?category=domains&action=delete&domain=" . $val['domain_name'] . "\">l&ouml;schen</a>";
      }
      
      $ausgabe .= "</td>
      </tr>
      ";      
   }   
   $ausgabe .= "
   </table>";
}
?>