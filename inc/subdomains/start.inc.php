<?php
// KAS-Abfrage durchfuehren - alle Subdomains holen, die in dem aktuellen Login angelegt sind
$return = kas_action("kas_action=get_subdomains");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; Subomains &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=subdomains&action=add\">neue Subomain anlegen</a></p>
<br />
<p>gefundene Subomains: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {

   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">Subdomain</td>
      <td id=\"headline\">Pfad</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"zwischenzeile\"></td>
   </tr>";
   
   // Ergebnis-Array der KAS-Abfrage auswerten und ausgeben
   foreach ($return['returninfo'] as $key => $val) {
      
      $ausgabe .= "
      <tr id=\"daten\">
         <td>".str_kuerzen($val['subdomain_name'],30)."</td>
         <td>".str_kuerzen($val['subdomain_path'],30)."</td>
         <td>";
      
      // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
      // erstellt bzw. geupdated wurde   
      if ($val['in_progress'] == "TRUE")
      {         
         $ausgabe .= "in Bearbeitung...";
      }
      // nicht in Bearbeitung
      else
      {         
         $ausgabe .= "<a href=\"?category=subdomains&action=edit&subdomain=".$val['subdomain_name']."\">bearbeiten</a> | <a href=\"?category=subdomains&action=delete&subdomain=".$val['subdomain_name']."\">l&ouml;schen</a>";
      }
      
      $ausgabe .= "</td>
      </tr>
      ";      
   }   
   $ausgabe .= "
   </table>";   
}
?>