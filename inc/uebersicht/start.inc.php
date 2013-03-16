<?php
// KAS-Abfrage durchfuehren

// alle Ressourcen des aktuellen Logins ermitteln, falls diese noch nicht da sind
if(!$return) {
   $return = kas_action("kas_action=get_accountressources");
}

// Belegten Speicherplatz ermitteln
$return_space = kas_action("kas_action=get_space");
$gesamt_speicher_belegt = $return_space['returnstring'] == "TRUE" ? $return_space['returninfo'][0]['used_webspace'] : "keine Daten";
unset($return_space);
 
// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; &Uuml;bersicht</h2>

".$statusmeldung;

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {
     
   // -1 bedeutet "unendlich"
   foreach( $return['returninfo'] as $key1 => $val1 ) {
      foreach( $val1 as $key2 => $val2) {
         if ($val2 < 0) {
            $return['returninfo'][$key1][$key2] = '&infin;';
         }
      }
   }
   
   // Ausgabe zusammenbauen
   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">Ressourcen&uuml;bersicht</td>
      <td id=\"headline\" style=\"text-align:center;\">bereits in Benutzung</td>
      <td id=\"headline\" style=\"text-align:center;\">Unteraccounts zugewiesen</td>
      <td id=\"headline\" style=\"text-align:center;\">maximal m&ouml;glich</td>
   </tr>
   <tr>
      <td colspan=\"4\" id=\"zwischenzeile\"></td>
   </tr>
   <tr id=\"daten\">
      <td>Webspace in MB</td>
      <td id=\"uebersicht_werte\">".number_format($gesamt_speicher_belegt/1024,2,',','.')." MB</td>
      <td id=\"uebersicht_werte\">".number_format($return['returninfo']['max_webspace']['reserved'],2,',','.')." MB</td>
      <td id=\"uebersicht_werte\">".number_format($return['returninfo']['max_webspace']['max'],2,',','.')." MB</td>      
   </tr>
   <tr id=\"daten\">
      <td>(Unter-) Accounts</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_account']['created']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_account']['reserved']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_account']['max']."</td>      
   </tr>
   <tr id=\"daten\">
      <td>Domains</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_domain']['created']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_domain']['reserved']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_domain']['max']."</td>      
   </tr>
   <tr id=\"daten\">
      <td>Subdomains</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_subdomain']['created']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_subdomain']['reserved']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_subdomain']['max']."</td>      
   </tr>
   <tr id=\"daten\">
      <td>MySQL-Datenbanken</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_database']['created']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_database']['reserved']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_database']['max']."</td>      
   </tr>
   <tr id=\"daten\">
      <td>FTP-User</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_ftpuser']['created']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_ftpuser']['reserved']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_ftpuser']['max']."</td>      
   </tr>
   <tr id=\"daten\">
      <td>E-Mail-Accounts</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_mail_account']['created']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_mail_account']['reserved']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_mail_account']['max']."</td>      
   </tr>
   <tr id=\"daten\">
      <td>E-Mail-Weiterleitungen</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_mail_forward']['created']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_mail_forward']['reserved']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_mail_forward']['max']."</td>      
   </tr>
   <tr id=\"daten\">
      <td>Mailinglisten</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_mailinglist']['created']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_mailinglist']['reserved']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_mailinglist']['max']."</td>      
   </tr>
   <tr id=\"daten\">
      <td>Cronjobs</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_cronjobs']['created']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_cronjobs']['reserved']."</td>
      <td id=\"uebersicht_werte\">".$return['returninfo']['max_cronjobs']['max']."</td>      
   </tr>
</table>";   
}
?>
