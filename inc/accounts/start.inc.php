<?php
// KAS-Abfrage durchfuehren - alle Unteraccounts des aktuellen Logins
$return = kas_action("kas_action=get_accounts");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; Account &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=accounts&action=add\">neuen Account anlegen</a></p>
<br />   
<p>gefundene Accounts: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {
   $ausgabe .= "
   <br />
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">Login</td>
      <td id=\"headline\">Kommentar</td>
      <td id=\"headline\">Passwort</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"4\" id=\"zwischenzeile\"></td>
   </tr>";
   
   
   // Ergebnis-Array der KAS-Abfrage auswerten und ausgeben
   foreach ($return['returninfo'] as $key => $val) {   
      $ausgabe .= "
      <tr id=\"daten\">
         <td>".str_kuerzen($val['account_login'],8)."</td>
         <td>".str_kuerzen($val['account_comment'],30)."</td>
         <td>".passwords(str_kuerzen($val['account_password'],20))."</td>
         <td>";
      
      // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
      // erstellt bzw. geupdated wurde   
      if ($val['in_progress'] == "TRUE")
      {         
         $ausgabe .= "in Bearbeitung...";         
      }
      else
      {         
         $ausgabe .= "<a href=\"?category=accounts&action=edit&account=".$val['account_login']."&pass=".sha1($val['account_password'])."\">bearbeiten</a> | <a href=\"?category=accounts&action=delete&account=".$val['account_login']."&pass=".$val['account_password']."\">l&ouml;schen</a>";
      }
     
      $ausgabe .= "</td>
      </tr>
      ";      
   }   
   $ausgabe .= "
   </table>";
}
?>