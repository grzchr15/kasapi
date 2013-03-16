<?php
// Wenn das Formular noch nicht versendet wurde
if (!$_POST['submit'])
{
   $ausgabe = eingabeformular();
}
// das Formular wurde versendet
else
{
  // die eingegebenen Passwörter stimmen nicht überein
  if ($_POST['password_1'] != $_POST['password_2'])
  {
   $return['returnstring'] = "Die Passw&ouml;rter sind ungleich";      
  }
  // die Änderungen werden gespeichert
  else
  {
   $return = kas_action("kas_action=update_ftpuser&ftp_login=" . $_GET['ftpuser'] . "&ftp_new_password=" . $_POST['password_1'] . "&ftp_comment=" . $_POST['kommentar'] . "&ftp_path=" . urlencode($_POST['pfad']));
  }
   
   // die Änderungen konnten erfolgreich gespeichert werden
   if ($return['returnstring'] == "TRUE")
   {
     $statusmeldung = "<div id=\"success\">Die &Auml;nderungen wurden &uuml;bernommen.</div>";
     include_once $includedir . 'inc/ftpuser/start.inc.php';
   }
   // die Änderungen konnten nicht gespeichert werden
   else
   {
     // Fehlermeldungen einbinden
     include_once $includedir . 'inc/errors.inc.php';
     $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
     $ausgabe = eingabeformular ($statusmeldung);
   }

}



############################## UNTERFUNKTIONEN #############################

// Eingabeformular
function eingabeformular($statusmeldung=false)
{
   // Alle FTP-User des aktuellen Logins ermitteln
   $return = kas_action("kas_action=get_ftpusers&ftp_login=" . $_GET['ftpuser']);

   // Zutreffende Werte zuweisen
   $_POST['pfad'] ? $pfad = $_POST['pfad'] : $pfad = $return['returninfo'][0]['ftp_path'];
   $_POST['kommentar'] ? $kommentar = $_POST['kommentar'] : $kommentar = $return['returninfo'][0]['ftp_comment'];
   $_POST['password_1'] ? $password_1 = $_POST['password_1'] : $password_1 = $return['returninfo'][0]['ftp_passwort'];
   $_POST['password_2'] ? $password_2 = $_POST['password_2'] : $password_2 = $return['returninfo'][0]['ftp_passwort'];
 

// Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; FTP-User bearbeiten</h2>
".$statusmeldung."
<br />
<form name=\"edit_ftpuser\" method=\"post\" action=\"?category=ftpuser&action=edit&ftpuser=".$_GET['ftpuser']."\">
<table>
   <tr>
      <td>Pfad:</td>
      <td><input name=\"pfad\" type=\"text\" value=\"".$pfad."\" size=\"45\" /></td>
   </tr>
   <tr>
      <td>Kommentar:</td>
      <td><input name=\"kommentar\" type=\"text\" value=\"".$kommentar."\" size=\"45\" maxlength=\"100\" /></td>
   </tr>
   <tr>
      <td>Passwort:</td>
      <td><input name=\"password_1\" type=\"".pass_inputtype()."\" value=\"".$password_1."\" size=\"25\" maxlength=\"20\" /></td>
   </tr>
   <tr>
      <td>Passwort wiederholen:</td>
      <td><input name=\"password_2\" type=\"".pass_inputtype()."\" value=\"".$password_2."\" size=\"25\" maxlength=\"20\" /></td>
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
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"FTP-User bearbeiten\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=ftpuser\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>
";
return $ausgabe;
}
?>