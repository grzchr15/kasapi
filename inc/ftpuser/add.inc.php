<?php
// KAS-Afrage - Ressourcen des aktuellen Logins lesen
$return = kas_action("kas_action=get_accountressources");

// wenn das Formular noch nicht abgesendet wurde
if (!$_POST['submit'] && $return['returninfo']['max_ftpuser']['free'] != 0)
{
   $ausgabe = eingabeformular();
}
// es können keine FTP-User mehr angelegt werden, weil die Ressourcen nicht mehr ausreichen
elseif ($return['returninfo']['max_ftpuser']['free'] == 0)
{
   $statusmeldung = "<div id=\"error\">Fehler: Es k&ouml;nnen keine FTP-User mehr angelegt werden.</div>";
   include_once $includedir . 'inc/ftpuser/start.inc.php';
}
// das Formular wurde abgesendet und die Ressourcen reichen aus
else
{ 
   // die eingegenen Passwörter stimmen nicht überein
   if ($_POST['password_1'] != $_POST['password_2']) {
      $return['returnstring'] = "Die Passw&ouml;rter sind ungleich.";      
   }
   // Anlegen des FTP-Users
   else
   {   
      $return = kas_action("kas_action=add_ftpuser&ftp_password=" . $_POST['password_1'] . "&ftp_path=" . $_POST['pfad'] . "&ftp_comment=" . urlencode($_POST['kommentar']));
   } 
   
   // wenn der FTP-User erfolgreich angelegt werden konnte
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Der FTP-User mit dem Login ".$return['returninfo']." wurde erstellt.</div>";
      include_once $includedir . 'inc/ftpuser/start.inc.php';
   }
   // wenn der FTP-User nicht angelegt werden konnte
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($statusmeldung);
   }
}



####################### UNTERFUNKTIONEN ####################

// Eingabeformular
function eingabeformular ($statusmeldung=false) {

// Ausgabe zusammenbauen    
$ausgabe = "<h2>&raquo; FTP-User anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_ftpuser\" method=\"post\" action=\"?category=ftpuser&action=add\">
<table>
   <tr>
      <td>Pfad:</td>
      <td><input name=\"pfad\" type=\"text\" value=\"".$_POST['pfad']."\" size=\"45\" /></td>
   </tr>
   <tr>
      <td>Kommentar:</td>
      <td><input name=\"kommentar\" type=\"text\" value=\"".$_POST['kommentar']."\" size=\"45\" maxlength=\"100\" /></td>
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
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"FTP-User anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" /></td>
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