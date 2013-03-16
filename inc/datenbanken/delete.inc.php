<?php
// Formular wird zu erstem mal aufgerufen
if (!$_POST['submit'])
{
   $ausgabe = eingabeformular();
}
// Eingaben wurden getätigt und Formular wurde abgesendet
else
{
   // Löschen der Datenbank
   $return = kas_action("kas_action=delete_database&database_login=" . $_GET['datenbank'] . "&database_password=" . $_POST['datenbank_passwort']);
   
   // Löschen erfolgreich
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die MySQL-Datenbank " . $_GET['datenbank'] . " wurde gel&ouml;scht.</div>";
      include_once $includedir . 'inc/datenbanken/start.inc.php';
   }
   // Löschen nicht erfolgreich
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular($statusmeldung);
   }
}


######################## UNTERFUNKTIONEN ##########################

// Eingabeformular
function eingabeformular($statusmeldung = false) {
   $ausgabe ="<h2>&raquo; MySQL-Datenbank l&ouml;schen</h2>
   ".$statusmeldung."
   <br />
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=datenbanken&action=delete&datenbank=" . $_GET['datenbank'] . "\">
   <table>
      <tr>
         <td><b>MySQL-Datenbank \"" . $_GET['datenbank'] . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td>Nach dem L&ouml;schen der MySQL-Datenbank sind alle darin enthaltenen Daten eng&uuml;ltig gel&ouml;scht.<br /> Diese sind nicht wiederherstellbar!</td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td>Geben Sie zur Best&auml;tigung des L&ouml;schvorganges bitte das Datenbankpasswort in nachfolgendes Textfeld ein:</td>
      </tr>
      <tr>
         <td id=\"button\">Datenbankpasswort: <input type=\"password\" name=\"datenbank_passwort\" size=\"20\" maxlength=\"20\"></td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=datenbanken\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
   return $ausgabe;
}

?>