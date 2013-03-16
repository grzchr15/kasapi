<?php
// Formulat mit den zu bearbeitenden Werten ausgeben
if (!$_POST['submit']) {
   $ausgabe = eingabeformular();
}
// Eingaben wurden getätigt und der "Speichern"-Button betätigt
else {
   // Wurden zwei Passwörter eingegeben und stimmen diese überein?
   if ((!$_POST['password_1'] || !$_POST['password_2']) || ($_POST['password_1'] != $_POST['password_2'])) {
      $return['returnstring'] = "&Uuml;berpr&uuml;fen Sie die Eingaben in den Passworteingabefeldern!";      
   }
   // Sind die eingegebenen Ressourcenwerte positive Ganzzahlen?
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
   // Änderungen speichern - Unteraccount ändern
   else
   {
      $return = kas_action("kas_action=update_account&account_login=" . $_GET['account'] . "&account_kas_password=" . $_POST['password_1'] . "&max_account=" . $_POST['anzahl_accounts'] . "&max_domain=" . $_POST['anzahl_domains'] . "&max_subdomain=" . $_POST['anzahl_subdomains'] . "&max_webspace=" . $_POST['speicherplatz'] . "&max_mail_account=" . $_POST['anzahl_postfaecher'] . "&max_mail_forward=" . $_POST['anzahl_weiterleitungen'] . "&max_mailinglist=" . $_POST['anzahl_mailinglisten'] . "&max_database=" . $_POST['anzahl_datenbanken'] . "&max_ftpuser=" . $_POST['anzahl_ftpnutzer']);
   }
   
   // Änderungen erfolgreich gespeichert
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die vorgenommenen &Auml;nderungen wurden erfolgreich &uuml;bernommen.</div>";
      include_once $includedir . 'inc/accounts/start.inc.php';
   }
   // Fehler bei der Änderung
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($statusmeldung);
   }
}

###################### UNTERFUNKTIONEN ########################

// Eingabeformular für die Bearbeitung der Accounteinstellungen
function eingabeformular ($statusmeldung=false) {

// Ressourcen des Unteraccounts ermitteln
$return = kas_action("kas_action=get_accountressources",$_GET['account'],$_GET['pass']);
$returnstring = $return['returnstring'];
$returninfo_accountressourcen = $return['returninfo'];

// Ressourcen des Hauptaccountes ermitteln
$return = kas_action("kas_action=get_accountressources");
$returninfo_hauptressourcen = $return['returninfo'];

// erster Aufruf den Formulares
if (!$_POST['submit']) {
   // Ressourcenliste des Unteraccountes holen
   $return = kas_action("kas_action=get_accounts&account_login=".$_GET['account']);

   foreach ($return['returninfo'] as $key => $val) {
      if ($val['account_login'] == $_GET['account']) {
         $current_password1 = $current_password2 = $val['account_password'];
      }
   }
   
   // Werte festlegen
   $max_account = $returninfo_accountressourcen['max_account']['max'];
   $max_domain = $returninfo_accountressourcen['max_domain']['max'];
   $max_subdomain = $returninfo_accountressourcen['max_subdomain']['max'];
   $max_webspace = $returninfo_accountressourcen['max_webspace']['max'];
   $max_mail_account = $returninfo_accountressourcen['max_mail_account']['max'];
   $max_mail_forward = $returninfo_accountressourcen['max_mail_forward']['max'];
   $max_mailinlist = $returninfo_accountressourcen['max_mailinglist']['max'];
   $max_database = $returninfo_accountressourcen['max_database']['max'];
   $max_ftpuser = $returninfo_accountressourcen['max_ftpuser']['max'];
     
   unset($returninfo);
}
// Das Formular wurde bereits abgesendet, es ist aber ein Fehler aufgetreten
else
{   
   $current_password1 = $_POST['password_1'];
   $current_password2 = $_POST['password_2'];
   $max_account = $_POST['anzahl_accounts'];
   $max_domain = $_POST['anzahl_domains'];
   $max_subdomain = $_POST['anzahl_subdomains'];
   $max_webspace = $_POST['speicherplatz'];
   $max_mail_account = $_POST['anzahl_postfaecher'];
   $max_mail_forward = $_POST['anzahl_weiterleitungen'];
   $max_mailinlist = $_POST['anzahl_mailinglisten'];
   $max_database = $_POST['anzahl_datenbanken'];
   $max_ftpuser = $_POST['anzahl_ftpnutzer'];
}


 // -1 bedeutet "unendlich"
foreach($returninfo_hauptressourcen as $key1 => $val1 ) {
   foreach( $val1 as $key2 => $val2) {
      if ($val2 == -1) {
         $returninfo_hauptressourcen[$key1][$key2] = '&infin;';
      }
   }
}

// Prüfen ob die Ressourcenwerte numerisch sind oder das Symbol für UNENDLICH "&infin;" gesetzt wurde
is_numeric($returninfo_hauptressourcen['max_account']['free']) ? $accounts_moeglich = $returninfo_hauptressourcen['max_account']['free'] + $returninfo_accountressourcen['max_account']['max'] : $accounts_moeglich = $returninfo_hauptressourcen['max_account']['free'];
is_numeric($returninfo_hauptressourcen['max_domain']['free']) ? $domains_moeglich = $returninfo_hauptressourcen['max_domain']['free'] + $returninfo_accountressourcen['max_domain']['max'] : $domains_moeglich = $returninfo_hauptressourcen['max_domain']['free'];
is_numeric($returninfo_hauptressourcen['max_subdomain']['free']) ? $subdomains_moeglich = $returninfo_hauptressourcen['max_subdomain']['free'] + $returninfo_accountressourcen['max_subdomain']['max'] : $subdomains_moeglich = $returninfo_hauptressourcen['max_subdomain']['free'];
is_numeric($returninfo_hauptressourcen['max_webspace']['free']) ? $webspace_moeglich = $returninfo_hauptressourcen['max_webspace']['free'] + $returninfo_accountressourcen['max_webspace']['max'] : $webspace_moeglich = $returninfo_hauptressourcen['max_webspace']['free'];
is_numeric($returninfo_hauptressourcen['max_mail_account']['free']) ? $mail_accounts_moeglich = $returninfo_hauptressourcen['max_mail_account']['free'] + $returninfo_accountressourcen['max_mail_account']['max'] : $mail_accounts_moeglich = $returninfo_hauptressourcen['max_mail_account']['free'];
is_numeric($returninfo_hauptressourcen['max_mail_forward']['free']) ? $mail_forwards_moeglich = $returninfo_hauptressourcen['max_mail_forward']['free'] + $returninfo_accountressourcen['max_mail_forward']['max'] : $mail_forwards_moeglich = $returninfo_hauptressourcen['max_mail_forward']['free'];
is_numeric($returninfo_hauptressourcen['max_mailinglist']['free']) ? $mailinglist_moeglich = $returninfo_hauptressourcen['max_mailinglist']['free'] + $returninfo_accountressourcen['max_mailinglist']['max'] : $mailinglist_moeglich = $returninfo_hauptressourcen['max_mailinglist']['free'];
is_numeric($returninfo_hauptressourcen['max_database']['free']) ? $databases_moeglich = $returninfo_hauptressourcen['max_database']['free'] + $returninfo_accountressourcen['max_database']['max'] : $databases_moeglich = $returninfo_hauptressourcen['max_database']['free'];
is_numeric($returninfo_hauptressourcen['max_ftpuser']['free']) ? $ftpuser_moeglich = $returninfo_hauptressourcen['max_ftpuser']['free'] + $returninfo_accountressourcen['max_ftpuser']['max'] : $ftpuser_moeglich = $returninfo_hauptressourcen['max_ftpuser']['free'];

// Formularausgabe zusammenbauen
$ausgabe = "<h2>&raquo; Account bearbeiten</h2>
".$statusmeldung."
<br />
<form name=\"add_account\" method=\"post\" action=\"?category=accounts&action=edit&account=" . $_GET['account'] . "&pass=" . $_GET['pass'] . "\">
<table>
   <tr>
      <td>Login des Accountes</td>
      <td colspan=\"2\"><b>".$_GET['account']."</b></td>
   </tr>
   <tr>
      <td colspan=\"3\" height=\"10\"></td>
   </tr>
   <tr>
      <td>Account-Passwort:</td>
      <td><input name=\"password_1\" type=\"".pass_inputtype()."\" size=\"20\" value=\"".$current_password1."\" /></td>
      <td></td>
   </tr>
   <tr>
      <td nowrap>Account-Passwort wiederholen:</td>
      <td><input name=\"password_2\" type=\"".pass_inputtype()."\" size=\"20\" value=\"".$current_password2."\" /></td>
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
      <td><input name=\"anzahl_accounts\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_accounts'] ? $_POST['anzahl_accounts'] : $max_account)."\" /></td>
      <td id=\"commet\">(max. ".$accounts_moeglich." m&ouml;glich, noch ".$returninfo_hauptressourcen['max_account']['free']." frei)</td>
   </tr>
   <tr>
      <td>Anzahl Domains:</td>
      <td><input name=\"anzahl_domains\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_domains'] ? $_POST['anzahl_domains'] : $max_domain)."\" /></td>
      <td id=\"commet\">(max. ".$domains_moeglich." m&ouml;glich, noch ".$returninfo_hauptressourcen['max_domain']['free']." frei)</td>
   </tr>
   <tr>
      <td>Anzahl Subdomains:</td>
      <td><input name=\"anzahl_subdomains\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_subdomains'] ? $_POST['anzahl_subdomains'] : $max_subdomain)." \" /></td>
      <td id=\"commet\">(max. ".$subdomains_moeglich." m&ouml;glich, noch ".$returninfo_hauptressourcen['max_subdomain']['free']." frei)</td>
   </tr>
   <tr>
      <td>Speicherplatz in MB:</td>
      <td><input name=\"speicherplatz\" type=\"text\" size=\"3\" value=\"".($_POST['speicherplatz'] ? $_POST['speicherplatz'] : $max_webspace)."\" /></td>
      <td id=\"commet\">(max. ".$webspace_moeglich." m&ouml;glich, noch ".$returninfo_hauptressourcen['max_webspace']['free']." frei)</td>
   </tr>
   <tr>
      <td>Anzahl E-Mail-Postf&auml;cher:</td>
      <td><input name=\"anzahl_postfaecher\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_postfaecher'] ? $_POST['anzahl_postfaecher'] : $max_mail_account)."\" /></td>
      <td id=\"commet\">(max. ".$mail_accounts_moeglich." m&ouml;glich, noch ".$returninfo_hauptressourcen['max_mail_account']['free']." frei)</td>
   </tr>
   <tr>
      <td>Anzahl E-Mail-Weiterleitungen:</td>
      <td><input name=\"anzahl_weiterleitungen\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_weiterleitungen'] ? $_POST['anzahl_weiterleitungen'] : $max_mail_forward)."\" /></td>
      <td id=\"commet\">(max. ".$mail_forwards_moeglich." m&ouml;glich, noch ".$returninfo_hauptressourcen['max_mail_forward']['free']." frei)</td>
   </tr>
   <tr>
      <td>Anzahl Mailinglisten:</td>
      <td><input name=\"anzahl_mailinglisten\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_mailinglisten'] ? $_POST['anzahl_mailinglisten'] : $max_mailinlist)."\" /></td>
      <td id=\"commet\">(max. ".$mailinglist_moeglich." m&ouml;glich, noch ".$returninfo_hauptressourcen['max_mailinglist']['free']." frei)</td>
   </tr>
   <tr>
      <td>Anzahl Datenbanken:</td>
      <td><input name=\"anzahl_datenbanken\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_datenbanken'] ? $_POST['anzahl_datenbanken'] : $max_database)."\" /></td>
      <td id=\"commet\">(max. ".$databases_moeglich." m&ouml;glich, noch ".$returninfo_hauptressourcen['max_database']['free']." frei)</td>
   </tr>
   <tr>
      <td>Anzahl FTP-Nutzer:</td>
      <td><input name=\"anzahl_ftpnutzer\" type=\"text\" size=\"3\" value=\"".($_POST['anzahl_ftpnutzer'] ? $_POST['anzahl_ftpnutzer'] : $max_ftpuser)."\" /></td>
      <td id=\"commet\">(max. ".$ftpuser_moeglich." m&ouml;glich, noch ".$returninfo_hauptressourcen['max_ftpuser']['free']." frei)</td>
   </tr>  
   <tr>
      <td colspan=\"3\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"&Auml;nderungen speichern\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" onMouseDown=\"JavaScript:document.getElementById('subdomain').style.display = 'none'; document.getElementById('tld').style.display = '';\"></td>
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