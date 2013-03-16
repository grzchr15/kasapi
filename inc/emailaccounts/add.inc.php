<?php
// Ressourcen des aktuellen Logins ermitteln
$return = kas_action("kas_action=get_accountressources");

// Wenn das Formular nich nicht abgesendet wurde
if (!$_POST['submit'] && $return['returninfo']['max_mail_account']['free'] != 0)
{
   $ausgabe = eingabeformular();
}
// es kann kein E-Mailpostfach angelegt werden, da nicht genügend Ressourcen
// im aktuellen Account zur Verfügung stehen
elseif ($return['returninfo']['max_mail_account']['free'] == 0)
{
   $statusmeldung = "<div id=\"error\">Fehler: Es k&ouml;nnen keine E-Mail-Accounts mehr angelegt werden.</div>";
   include_once $includedir . 'inc/emailaccounts/start.inc.php';
}
else {
   // die eingegebnen Passwörter stimmen überein
   if ($_POST['password_1'] == $_POST['password_2']) {
       // E-Mail-Account wird angelegt
       $return = kas_action("kas_action=add_mailaccount&local_part=" . $_POST['email_1'] . "&domain_part=" . $_POST['email_2'] . "&mail_password=" . $_POST['password_1'] . "&responder=" . $_POST['autoresponder_aktiv'] . "&responder_text=" . urlencode($_POST['autoresponder_text']));
   }
   // die eingegebenen Passwörter sind ungleich
   else {
      $return['returnstring'] = "Die Passw&ouml;rter sind ungleich";
   }
   
      // der E-Mailaccount konnte erfolgreich angelegt werden
      if ($return['returnstring'] == "TRUE")
      {
         $statusmeldung = "<div id=\"success\">Das E-Mailpostfach mit dem Login ".$return['returninfo']." wurde erstellt.</div>";
         include_once $includedir . 'inc/emailaccounts/start.inc.php';
      }
      // der E-Mailaccount konnte nicht angelegt werden
      else
      {
         // Fehlermeldungen einbinden
         include_once $includedir . 'inc/errors.inc.php';
         $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
         $ausgabe = eingabeformular ($statusmeldung);
      }
}


########################### UNTERFUNKTIONEN ######################


// Eingabeformular
function eingabeformular($statusmeldung=false)
{
// Ausgabe zusammenbauen  
$ausgabe = "<h2>&raquo; E-Mail-Account anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_mailaccount\" method=\"post\" action=\"?category=emailaccounts&action=add\">
<table>
   <tr>
      <td nowrap width=\"25%\">E-Mailadresse:</td>
      <td nowrap><input name=\"email_1\" type=\"text\" size=\"25\" value=\"".$_POST['email_1']."\" />@
      <select name=\"email_2\">";

// Alle im aktuellen Account agelegten Cnames ermittels
$return = kas_action("kas_action=get_domains");

// Optionsliste für das Select-Feld zusammenbauen
if (sizeof($return['returninfo'])>0) {
   foreach($return['returninfo'] as $key => $val) {
      $_POST['email_2'] == $val['domain_name'] ? $attribut = " selected" : $attribut = "";
      $ausgabe .= "\n<option value=\"".$val['domain_name']."\"".$attribut.">".$val['domain_name']."</option>";            
   }
}

// Alle im aktuellen Account agelegten Subdomains ermittels
$return = kas_action("kas_action=get_subdomains");

// Optionsliste für das Select-Feld zusammenbauen
if (sizeof($return['returninfo'])>0) {
   foreach($return['returninfo'] as $key => $val) {
      $_POST['email_2'] == $val['subdomain_name'] ? $attribut = " selected" : $attribut = "";
      $ausgabe .= "\n<option value=\"".$val['subdomain_name']."\"".$attribut.">".$val['subdomain_name']."</option>";            
   }
}

// "selected" - HTML-Attribut für das Select-Feld "Autosesponder" definieren        
if ($_POST['autoresponder_aktiv'] == "Y") {
   $checked_an = " checked";
   $checked_aus = "";
} else {
   $checked_an = "";
   $checked_aus = " checked";
}

// Ausgabe zusammenbauen
$ausgabe .= "
      </select>
      </td>
   </tr>
   <tr>
      <td>Passwort:</td>
      <td><input name=\"password_1\" type=\"".pass_inputtype()."\" value=\"".$_POST['password_1']."\" size=\"25\" maxlength=\"20\" /></td>
   </tr>
   <tr>
      <td>Passwort wiederholen:</td>
      <td><input name=\"password_2\" type=\"".pass_inputtype()."\" value=\"".$_POST['password_2']."\" size=\"25\" maxlength=\"20\" /></td>
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
      <td><textarea name=\"autoresponder_text\" cols=\"33\" rows=\"4\">".$_POST['autoresponder_text']."</textarea></td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\">Weitere Einstellungen zu jedem E-Mailaccount wie z.B. Spamfilter usw. k&ouml;nnen anschliessend mit Hilfe der Webmail-Oberfl&auml;che get&auml;tigt werden.</td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"E-Mail-Account anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=emailaccounts\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}
?>