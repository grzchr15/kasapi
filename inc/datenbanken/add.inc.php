<?php
// Accountressourcen des aktuellen Accountes auslesen
$return = kas_action("kas_action=get_accountressources");

// Das Formular wird das erste mal aufgerufen
if (!$_POST['submit'] && $return['returninfo']['max_database']['free'] != 0)
{
   $ausgabe = eingabeformular();
}
// Es sind im aktuellen Account keine Ressourcen mehr frei
elseif ($return['returninfo']['max_database']['free'] == 0)
{
   $statusmeldung = "<div id=\"error\">Fehler: Es k&ouml;nnen keine MySQL-Datenbanken mehr angelegt werden.</div>";
   include_once $includedir . 'inc/datenbanken/start.inc.php';
}
// Eingaben wurden getätigt
else
{ 
   // eingegebene Passwörter stimmen nicht überein
   if ($_POST['password_1'] != $_POST['password_2']) {
      $return['returnstring'] = "Die Passw&ouml;rter sind ungleich.";      
   }
   // Datenbank wird angelegt
   else
   {   
      $return = kas_action("kas_action=add_database&database_password=" . $_POST['password_1'] . "&database_comment=" . ereg_replace(" ","%20",$_POST['kommentar']));
   } 
   
   // Datenbank wurde erfolgreich angelegt  
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die MySQL-Datenbank mit dem Login ".$return['returninfo']." wurde erstellt.</div>";
      include_once $includedir . 'inc/datenbanken/start.inc.php';
   }
   
   // Datenbank wurde nicht erfolgreich angelegt 
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($statusmeldung);
   }
}


###################### Unterfunktionen #######################

// Eingabformular
function eingabeformular ($statusmeldung=false) {
  
$ausgabe = "<h2>&raquo; MySQL-Datenbank anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_database\" method=\"post\" action=\"?category=datenbanken&action=add\">
<table>
   <tr>
      <td>Kommentar zu der neuen MySQL-Datenbank:</td>
      <td><input name=\"kommentar\" type=\"text\" value=\"".$_POST['kommentar']."\" size=\"45\" maxlength=\"25\" /></td>
      <td></td>
   </tr>
   <tr>
      <td>Datenbank-Passwort:</td>
      <td><input name=\"password_1\" type=\"".pass_inputtype()."\" value=\"".$_POST['password_1']."\" size=\"25\" maxlength=\"20\" /></td>
      <td></td>
   </tr>
   <tr>
      <td>Datenbank-Passwort wiederholen:</td>
      <td><input name=\"password_2\" type=\"".pass_inputtype()."\" value=\"".$_POST['password_2']."\" size=\"25\" maxlength=\"20\" /></td>
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
      <td colspan=\"3\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"MySQL-Datenbank anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" /></td>
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