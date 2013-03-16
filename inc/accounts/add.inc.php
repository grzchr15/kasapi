<?php
// Ressourcen des aktuellen Logins holen
$return = kas_action("kas_action=get_accountressources");
$returnstring = $return['returnstring'];
$returninfo_ressourcen = $return['returninfo'];

// Eingabeformular anzeigen
if (!$_POST['submit'] && $returninfo_ressourcen['max_account']['free'] != 0)
{
   $ausgabe = eingabeformular($returninfo_ressourcen);
}
// Wenn keine UNteraccounts meht angelegt werden können
elseif ($returninfo_ressourcen['max_account']['free'] == 0)
{
   $statusmeldung = "<div id=\"error\">Fehler: Es k&ouml;nnen keine Accounts mehr angelegt werden.</div>";
   include_once $includedir . 'inc/accounts/start.inc.php';
}
// Eingabe ist erfolgt und der "Speichern-Button betätigt"
else
{ 
   // Passwortprüfung
   if ($_POST['password_1'] != $_POST['password_2']) {
      $return['returnstring'] = "Die Passw&ouml;rter stimmen nicht &uuml;berein.";      
   }
   // Sind die Ressourcenwerte positive Ganzzahlen?
   elseif (!pos_ganzzahl($_POST['anzahl_accounts']) ||
           !pos_ganzzahl($_POST['anzahl_domains']) ||
           !pos_ganzzahl($_POST['anzahl_subdomains']) ||
           !pos_ganzzahl($_POST['speicherplatz']) ||
           !pos_ganzzahl($_POST['anzahl_postfaecher']) ||
           !pos_ganzzahl($_POST['anzahl_weiterleitungen']) ||
           !pos_ganzzahl($_POST['anzahl_mailinglisten']) ||
           !pos_ganzzahl($_POST['anzahl_datenbanken']) ||
           !pos_ganzzahl($_POST['anzahl_ftpnutzer'])) {
      
       $return['returnstring'] = "Falsche Eingabe. Geben Sie nur positive Ganzzahlen als Ressourcenangabe ein.";   
   }
   // der neue Unteraccount wird erstellt
   else
   {   
      $_POST['account_type'] == 'domain' ? $hostname_part2 = $_POST['tld'] : $hostname_part2 = $_POST['domainname'];
      $return = kas_action("kas_action=add_account&account_kas_password=" . $_POST['password_1'] . "&account_ftp_password=" . $_POST['password_1'] . "&hostname_art=" . $_POST['account_type'] . "&hostname_part1=" . $_POST['domain'] . "&hostname_part2=" . $hostname_part2 . "&max_account=" . $_POST['anzahl_accounts'] . "&max_domain=" . $_POST['anzahl_domains'] . "&max_subdomain=" . $_POST['anzahl_subdomains'] . "&max_webspace=" . $_POST['speicherplatz'] . "&max_mail_account=" . $_POST['anzahl_postfaecher'] . "&max_mail_forward=" . $_POST['anzahl_weiterleitungen'] . "&max_mailinglist=" . $_POST['anzahl_mailinglisten'] . "&max_database=" . $_POST['anzahl_datenbanken'] . "&max_ftpuser=" . $_POST['anzahl_ftpnutzer']);
   } 
   
   // Accounterstellung war erfolgreich  
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Der Account mit dem Login ".$return['returninfo']." wurde erstellt.</div>";
      include_once $includedir . 'inc/accounts/start.inc.php';
   }
   // Accounterstellung war nicht erfolgreich
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($returninfo_ressourcen,$statusmeldung);
   }
}


###################### UNTERFUNKTIONEN ######################

// EingabeFormular
function eingabeformular ($returninfo_ressourcen,$statusmeldung=false) {
GLOBAL $includedir;

// Liste mit allen möglichen TLDs holen   
include_once $includedir . 'inc/tld.inc.php';

// alle Domains / Cnames des aktuellen Logins holen
$return = kas_action("kas_action=get_domains");
$returnstring = $return['returnstring'];
$returninfo_domains = $return['returninfo'];
   
$ausgabe = "<h2>&raquo; Account anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_account\" method=\"post\" action=\"?category=accounts&action=add\">
<table>
   <tr>
      <td>
      anlegen als:
      <select name=\"account_type\" onChange=\"JavaScript:if (this.value == 'subdomain') {
                                                document.getElementById('subdomain').style.display = '';
                                                document.getElementById('tld').style.display = 'none'; }
                                                else { document.getElementById('subdomain').style.display = 'none';
                                                       document.getElementById('tld').style.display = ''; }\">
         <option selected value=\"domain\">Domain</option>
         <option value=\"subdomain\">Subdomain</option>
      </select>:
      </td>
      <td colspan=\"2\" nowrap>
      <input name=\"domain\" type=\"text\" value=\"".$_POST['domain']."\" />
      <span id=\"subdomain\" style=\"display:none;\">
         <select name=\"domainname\">";
           
         // Domains / Cnames
         foreach($returninfo_domains as $key => $val) {
            if ($val['in_progress'] != "TRUE") {
               $ausgabe .= "\n<option value=\"".$val['domain_name']."\">.".$val['domain_name']."</option>";
            }
         }
            
$ausgabe .= "
         </select>
      </span>
      <span id=\"tld\" style=\"display:\">
         <select name=\"tld\">";
         
         // TLDs
         foreach($tld_array as $key => $val) {            
            $ausgabe .= "\n<option value=\"".$val."\">.".$val."</option>";            
         }
         
         // -1 bedeutet "unendlich"
         foreach($returninfo_ressourcen as $key1 => $val1 ) {
            foreach( $val1 as $key2 => $val2) {
               if ($val2 == -1) {
                  $returninfo_ressourcen[$key1][$key2] = '&infin;';
               }
            }
         }
         
$ausgabe .= "
         </select>
      </span>
      </td>
   </tr>
   <tr>
      <td colspan=\"3\" height=\"10\"></td>
   </tr>
   <tr>
      <td>Account-Passwort:</td>
      <td><input name=\"password_1\" type=\"".pass_inputtype()."\" value=\"\" /></td>
      <td></td>
   </tr>
   <tr>
      <td>Account-Passwort wiederholen:</td>
      <td><input name=\"password_2\" type=\"".pass_inputtype()."\" value=\"\" /></td>
      <td></td>
   </tr>
   <tr>
      <td colspan=\"3\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"3\" id=\"commet\" style=\"text-align:center;\">Hinweis: Passw&ouml;rter m&uuml;ssen aus 6 - 20 (a-zA-Z0-9) Zeichen bestehen.</td>
   </tr>
   <tr>
      <td colspan=\"3\" height=\"20\"></td>
   </tr>
   <tr>
      <td>Anzahl (Unter-) Accounts:</td>
      <td><input name=\"anzahl_accounts\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_accounts'] ? $_POST['anzahl_accounts'] : 0)."\" /></td>
      <td id=\"commet\">(max. ".($returninfo_ressourcen['max_account']['free']-1)." m&ouml;glich)</td>
   </tr>
   <tr>
      <td>Anzahl Domains:</td>
      <td><input name=\"anzahl_domains\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_domains'] ? $_POST['anzahl_domains'] : 0)."\" /></td>
      <td id=\"commet\">(max. ".$returninfo_ressourcen['max_domain']['free']." m&ouml;glich)</td>
   </tr>
   <tr>
      <td>Anzahl Subdomains:</td>
      <td><input name=\"anzahl_subdomains\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_subdomains'] ? $_POST['anzahl_subdomains'] : 0)."\" /></td>
      <td id=\"commet\">(max. ".$returninfo_ressourcen['max_subdomain']['free']." m&ouml;glich)</td>
   </tr>
   <tr>
      <td>Speicherplatz in MB:</td>
      <td><input name=\"speicherplatz\" type=\"text\" size=\"3\" value=\"".($_POST['speicherplatz'] ? $_POST['speicherplatz'] : 0)."\" /></td>
      <td id=\"commet\">(max. ".$returninfo_ressourcen['max_webspace']['free']." m&ouml;glich)</td>
   </tr>
   <tr>
      <td>Anzahl E-Mail-Postf&auml;cher:</td>
      <td><input name=\"anzahl_postfaecher\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_postfaecher'] ? $_POST['anzahl_postfaecher'] : 0)."\" /></td>
      <td id=\"commet\">(max. ".$returninfo_ressourcen['max_mail_account']['free']." m&ouml;glich)</td>
   </tr>
   <tr>
      <td>Anzahl E-Mail-Weiterleitungen:</td>
      <td><input name=\"anzahl_weiterleitungen\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_weiterleitungen'] ? $_POST['anzahl_weiterleitungen'] : 0)."\" /></td>
      <td id=\"commet\">(max. ".$returninfo_ressourcen['max_mail_forward']['free']." m&ouml;glich)</td>
   </tr>
   <tr>
      <td>Anzahl Mailinglisten:</td>
      <td><input name=\"anzahl_mailinglisten\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_mailinglisten'] ? $_POST['anzahl_mailinglisten'] : 0)."\" /></td>
      <td id=\"commet\">(max. ".$returninfo_ressourcen['max_mailinglist']['free']." m&ouml;glich)</td>
   </tr>
   <tr>
      <td>Anzahl Datenbanken:</td>
      <td><input name=\"anzahl_datenbanken\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_datenbanken'] ? $_POST['anzahl_datenbanken'] : 0)."\" /></td>
      <td id=\"commet\">(max. ".$returninfo_ressourcen['max_database']['free']." m&ouml;glich)</td>
   </tr>
   <tr>
      <td>Anzahl FTP-Nutzer:</td>
      <td><input name=\"anzahl_ftpnutzer\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_ftpnutzer'] ? $_POST['anzahl_ftpnutzer'] : 0)."\" /></td>
      <td id=\"commet\">(max. ".$returninfo_ressourcen['max_ftpuser']['free']." m&ouml;glich)</td>
   </tr>  
   <tr>
      <td colspan=\"3\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Account anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" onMouseDown=\"JavaScript:document.getElementById('subdomain').style.display = 'none'; document.getElementById('tld').style.display = '';\"></td>
   </tr>
   <tr>
      <td colspan=\"3\" style=\"text-align:center;\"><a href=\"?category=accounts\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>
";
return $ausgabe;
}
?>