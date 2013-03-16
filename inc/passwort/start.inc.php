<?php
$statusmeldung = false;

// wenn der "speichern" - Button betätigt wurde
if (isset($_POST['submit']) && ($_POST['submit'] == "speichern")) {
   
   // Prüfung des aktuellen Passowrtes
   if (sha1($_POST['pass_alt']) != $_SESSION['kas_passwort']) {
      $return['returnstring'] = "Das eingegebene Passwort ist nicht korrekt.";
   }
   // wenn die eingegebenen Passwöter nicht übereinstimmen
   elseif ($_POST['pass_neu1'] != $_POST['pass_neu2']) {
      $return['returnstring'] = "Die Passw&ouml;rter sind ungleich.";
   }
   // Änderungen speichern
   else {
      $return = kas_action("kas_action=update_accountsettings&account_password=".$_POST['pass_neu1']);
   }
      
   // wenn die Änderngen erfolgreich gespeichert werden konnten
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die &Auml;nderungen wurden &uuml;bernommen.</div>";
      $_SESSION['kas_passwort'] = sha1($_POST['pass_neu1']);
   }
   // wenn die Änderungen nicht gespeichert werden konnten
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
   }  
}

// Eingabekormular ausgeben
$ausgabe = eingabeformular($statusmeldung);



############################# UNTERFUNKTIONEN ################

// Eingabeformular
function eingabeformular ($statusmeldung) {
return "<h2>&raquo; Passwort &auml;ndern</h2>
".$statusmeldung."
<br /><br />
<form name=\"logout\" method=\"post\" action=\"?category=passwort\">
<table>
   <tr>
      <td nowrap>aktuelles Passwort:</td>
      <td><input type=\"".pass_inputtype()."\" name=\"pass_alt\" value=\"\"></td>
   </tr>
   <tr>
      <td colspan=\"2\">&nbsp;</td>
   </tr>
   <tr>
      <td nowrap>neues Passwort:</td>
      <td><input type=\"".pass_inputtype()."\" name=\"pass_neu1\" value=\"\"></td>
   </tr>
   <tr>
      <td nowrap>neues Passwort wiederholen:</td>
      <td><input type=\"".pass_inputtype()."\" name=\"pass_neu2\" value=\"\"></td>
   </tr>
   <tr>
      <td colspan=\"2\">&nbsp;</td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"commet\" style=\"text-align:center;\">Hinweis: Passw&ouml;rter m&uuml;ssen aus 6 - 20 (a-zA-Z0-9) Zeichen bestehen.</td>
   </tr>
    <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"speichern\"><input id=\"button\" name=\"reset\" type=\"reset\" value=\"zur&uuml;cksetzen\"></td>
   </tr>
</table>
</form>
";
}
?>