<?php
// Formular wurde noch nicht abgesendet
if (!isset($_POST['submit']))
{
   // Daten des aktuellen Mail-Logins holen
   $return = kas_action("kas_action=get_mailaccounts&mail_login=" . $_GET['maillogin']);
   $ausgabe = eingabeformular($return['returninfo']);
}
// Formular wurde bereits abgesendet
else {
   // die eingegebenen Passwörter stimmen überein
   if ($_POST['password_1'] == $_POST['password_2']) {
      // Eingaben speichern
      $return = kas_action("kas_action=update_mailaccount&mail_login=" . $_GET['maillogin'] . "&mail_new_password=" . $_POST['password_1'] . "&responder=" . $_POST['autoresponder_aktiv'] . "&responder_text=" . urlencode($_POST['autoresponder_text'])); 
   }
   // die eingegebenen Passwörter stimmen nicht überein
   else {
      $return['returnstring'] = "Die Passw&ouml;rter sind ungleich";
   }

   // Eingaben wurden erfolgreich gespeichert
   if ($return['returnstring']== "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die &Auml;nderungen wurden &uuml;bernommen.</div>";
      include_once $includedir . 'inc/emailaccounts/start.inc.php';
   }
   // Eingaben wurden nicht gespeichert
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($return['returninfo'],$statusmeldung);
   }
}



######################## UNTERFUNKTIONEN #####################

// Eingabeformular
function eingabeformular($returninfo,$statusmeldung=false)
{

// Formular-Startwerte aus dem $_POST-Array holen, wenn
// das Formular bereits abgesendet wurde
if (isset($_POST['submit'])) {   
   $email_password_1 = $_POST['password_1'];
   $email_password_2 = $_POST['password_2'];
   $email_responder = $_POST['autoresponder_aktiv'];
   $email_respondertext = $_POST['autoresponder_text'];   
}
// andernfalls werden die Formular-Startwerte aus dem
// Datenbestand geholt
else {
   if (is_array($returninfo)) {
      foreach ($returninfo as $key => $val) {
         if ($val['mail_login'] == $_GET['maillogin']) {
            $email_password_1 = $val['mail_password'];
            $email_password_2 = $val['mail_password'];
			if(isset($val['mail_responder'])){
				$email_responder="Y";
			}else{
				$email_responder="N";
			}
            $email_responder = $val['mail_responder'];
            $email_respondertext = $val['mail_respondertext'];
         }
      }  
   }
}
  
$email_accountlogin = $_GET['maillogin'];

// "selected" - HTML-Attribut für das Select-Feld "Autosesponder" definieren  
if ($email_responder == "Y") {
   $checked_an = " checked";
   $checked_aus = "";

} else {
   $checked_an = "";
   $checked_aus = " checked";
 
}

// Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; E-Mail-Account bearbeiten</h2>
".$statusmeldung."
<br />
<form name=\"add_mailaccount\" method=\"post\" action=\"?category=emailaccounts&action=edit&maillogin=" . $email_accountlogin . "\">
<table>
   <tr>
      <td nowrap>E-Mailaccount (Login / Beutzername):</td>
      <td nowrap>".$email_accountlogin."</td>
   </tr>
   <tr>
      <td>Passwort:</td>
      <td><input name=\"password_1\" type=\"".pass_inputtype()."\" value=\"".$email_password_1."\" size=\"25\" maxlength=\"20\" /></td>
   </tr>
   <tr>
      <td>Passwort wiederholen:</td>
      <td><input name=\"password_2\" type=\"".pass_inputtype()."\" value=\"".$email_password_2."\" size=\"25\" maxlength=\"20\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"commet\" style=\"text-align:center;\">Hinweis: Passw&ouml;rter m&uuml;ssen aus 6 - 20 (a-zA-Z0-9) Zeichen bestehen.</td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td>Autoresponder aktiv:</td>
      <td><input name=\"autoresponder_aktiv\" type=\"radio\" value=\"Y\"".$checked_an." /> ja <input name=\"autoresponder_aktiv\" type=\"radio\" value=\"N\"".$checked_aus." /> nein</td>
   </tr>
   <tr>
      <td valign=\"top\">Autoresponder Text:</td>
      <td><textarea name=\"autoresponder_text\" cols=\"33\" rows=\"4\">".$email_respondertext."</textarea></td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\">Weitere Einstellungen zu jedem E-Mailaccount wie z.B. Spamfilter usw. k&ouml;nnen anschliessend mit Hilfe der Webmail-Oberfl&auml;che get&auml;tigt werden.</td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"E-Mail-Account bearbeiten\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=emailaccounts\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}
?>