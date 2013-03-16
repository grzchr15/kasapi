<?php
// KAS-Abfrage durchfuehren - alle E-Mailaccounts des aktuellen Logins holen
$return = kas_action("kas_action=get_mailaccounts");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; E-Mail-Accounts &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=emailaccounts&action=add\">neuen E-Mail-Account anlegen</a></p>
<br />
<p>gefundene E-Mail-Accounts: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {

   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">E-Mailadressen</td>
      <td id=\"headline\">Benutzername</td>
      <td id=\"headline\">Passwort</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"4\" id=\"zwischenzeile\"></td>
   </tr>";
   
   // Ergebnis-Array der KAS-Abfrage auswerten und ausgeben
   if (is_array($return['returninfo'])) {
      foreach ($return['returninfo'] as $key => $val) {
         
         $ausgabe .= "
         <tr id=\"daten\">
            <td>".str_kuerzen($val['mail_adresses'],30)."</td>
            <td>".str_kuerzen($val['mail_login'],10)."</td>
            <td>".passwords(str_kuerzen($val['mail_password'],20))."</td>
            <td>";
            
         // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
         // erstellt bzw. geupdated wurde
         if ($val['in_progress'] == "TRUE")
         {         
            $ausgabe .= "in Bearbeitung...";
         }
         else
         {         
            $ausgabe .= "<a href=\"?category=emailaccounts&action=edit&maillogin=".$val['mail_login']."\">bearbeiten</a> | <a href=\"?category=emailaccounts&action=delete&maillogin=".$val['mail_login']."\">l&ouml;schen</a>";
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