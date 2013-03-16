<?php
// wenn das Formular noch nicht abgeschickt wurde
if (!$_POST['submit'])
{
   $ausgabe = eingabeformular();
}
// Formukar wurde abgeschickt
else {
      // Verzeichnisschutz anlegen
      $return = kas_action("kas_action=add_directoryprotection&directory_user=" . $_POST['benutzer'] . "&directory_password=" . $_POST['password'] . "&directory_path=" . $_POST['verzeichnis']);
   
      // der Verzeichnisschutz konnte erfolgreich angelegt werden
      if ($return['returnstring'] == "TRUE")
      {
         $statusmeldung = "<div id=\"success\">Der Verzeichnisschutz wurde erstellt.</div>";
         include_once $includedir . 'inc/verzeichnisschutz/start.inc.php';
      }
      // der Verzeichnisschutz konnte nicht angelegt werden
      else
      {
         // Datei mit Fehlermeldungen einbinden
         include_once $includedir . 'inc/errors.inc.php';
         $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
         $ausgabe = eingabeformular ($statusmeldung);
      }
}



############################### UNTERFUNKTIONEN ########################

// Eingabeformular
function eingabeformular($statusmeldung=false)
{

// Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; Verzeichnisschutz anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_mailinglist\" method=\"post\" action=\"?category=verzeichnisschutz&action=add\">
<table>
   <tr>
      <td>Verzeichnis:</td>
      <td><input type=\"text\" size=\"35\" name=\"verzeichnis\" value=\"" . $_POST['verzeichnis'] . "\"></td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td>Benutzer:</td>
      <td><input name=\"benutzer\" type=\"text\" value=\"".$_POST['benutzer']."\" size=\"25\" maxlength=\"20\" /></td>
   </tr>
   <tr>
      <td>Passwort:</td>
      <td><input name=\"password\" type=\"".pass_inputtype()."\" value=\"".$_POST['password']."\" size=\"25\" maxlength=\"20\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Verzeichnisschutz anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=verzeichnisschutz\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}
?>