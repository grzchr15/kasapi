<?php
// KAS-Abfrage durchfuehren - alle angelegten Mailforwards ermitteln
$return =  kas_action("kas_action=get_mailforwards");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; E-Mail-Weiterleitungen &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=emailweiterleitungen&action=add\">neue E-Mail-Weiterleitung anlegen</a></p>
<br />
<p>gefundene E-Mail-Weiterleitungen: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {

   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">Weiterleitungsadresse</td>
      <td id=\"headline\">Weiterleitungsziel</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"zwischenzeile\"></td>
   </tr>";
   
   // Ergebnis-Array der KAS-Abfrage auswerten und ausgeben
   foreach ($return['returninfo'] as $key => $val) {
      
      $ausgabe .= "
      <tr id=\"daten\">
         <td>".str_kuerzen($val['mail_forward_adress'],30)."</td>
         <td>".str_kuerzen($val['mail_forward_targets'],30)."</td>
         <td>";
         
      // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
      // erstellt bzw. geupdated wurde
      if ($val['in_progress'] == "TRUE")
      {         
         $ausgabe .= "in Bearbeitung...";
      }
      else
      {         
         $ausgabe .= "<a href=\"?category=emailweiterleitungen&action=edit&weiterleitung=" . $val['mail_forward_adress'] . "\">bearbeiten</a> | <a href=\"?category=emailweiterleitungen&action=delete&weiterleitung=" . $val['mail_forward_adress'] . "\">l&ouml;schen</a>";
      }
      
      $ausgabe .= "</td>
      </tr>
      ";      
   }   
   $ausgabe .= "
   </table>";
}
?>