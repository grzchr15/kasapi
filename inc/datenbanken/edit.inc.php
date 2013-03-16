<?php
// Formular wird zum ersten mal aufgerufen
if (!$_POST['submit'])
{
   $ausgabe = eingabeformular();
}
// Eingaben wurden getätigt und das Eingabeformular abgesendet
else
{   
  // die eingegebenen Passwörter stimmen nicht überein
  if ($_POST['password_1'] != $_POST['password_2'])
  {
   $return['returnstring'] = "Die Passw&ouml;rter sind ungleich";      
  }
  // Speichern der Änderungen
  else
  {
   $return = kas_action("kas_action=update_database&database_login=" . $_GET['datenbank'] . "&database_new_password=" . $_POST['password_1'] . "&database_comment=" . ereg_replace(" ","%20",$_POST['kommentar']));
  }
   
   // Änderungen wurden erfolgreich gespeichert
   if ($return['returnstring'] == "TRUE")
   {
     $statusmeldung = "<div id=\"success\">Die &Auml;nderungen wurden &uuml;bernommen.</div>";
     include_once $includedir . 'inc/datenbanken/start.inc.php';
   }
   // Änderungen konnten nicht gespeichert werden
   else
   {
      // Fehlermeldungen einbinden
     include_once $includedir . 'inc/errors.inc.php';
     $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
     $ausgabe = eingabeformular($statusmeldung);
   }

}


##################################### UNTERFUNKTIONEN ################

// Eingabeformular
function eingabeformular($statusmeldung=false)
{
   // Daten der betreffenden Datenbank holen
   $return = kas_action("kas_action=get_databases&database_login=".$_GET['datenbank']);
      
   // Werte zuweisen
   $_POST['kommentar'] ? $kommentar = $_POST['kommentar'] : $kommentar = $return['returninfo'][0]['database_comment'];
   $_POST['password_1'] ? $password_1 = $_POST['password_1'] : $password_1 = $return['returninfo'][0]['database_password'];
   $_POST['password_2'] ? $password_2 = $_POST['password_2'] : $password_2 = $return['returninfo'][0]['database_password'];


// Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; MySQL-Datenbank bearbeiten</h2>
".$statusmeldung."
<br />
<form name=\"add_database\" method=\"post\" action=\"?category=datenbanken&action=edit&datenbank=" . $_GET['datenbank'] . "\">
<table>
   <tr>
      <td>neuer Kommentar der MySQL-Datenbank:</td>
      <td><input name=\"kommentar\" type=\"text\" value=\"".$kommentar."\" size=\"45\" maxlength=\"25\" /></td>
      <td></td>
   </tr>
   <tr>
      <td>neues Datenbank-Passwort:</td>
      <td><input name=\"password_1\" type=\"".pass_inputtype()."\" value=\"".$password_1."\" size=\"25\" maxlength=\"20\" /></td>
      <td></td>
   </tr>
   <tr>
      <td>neues Datenbank-Passwort wiederholen:</td>
      <td><input name=\"password_2\" type=\"".pass_inputtype()."\" value=\"".$password_2."\" size=\"25\" maxlength=\"20\" /></td>
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
      <td colspan=\"3\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"MySQL-Datenbank bearbeiten\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" /></td>
   </tr>
   <tr>
      <td colspan=\"3\" style=\"text-align:center;\"><a href=\"?category=datenbanken\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>
";
return $ausgabe;
}
?>