<?php
// wenn das Formular noch nicht versendet wurde
if (!$_POST['submit'])
{
   $ausgabe = eingabeformular();
}
// wenn das Formular versendet wurde
else
{
   // FTP-User löschen
   $return = kas_action("kas_action=delete_ftpuser&ftp_login=" . $_GET['ftpuser']);
   
   // FTP-User konnte erfolgreich entfernt werden
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Der FTP-User " . $_GET['ftpuser'] . " wurde gel&ouml;scht.</div>";
      include_once $includedir . 'inc/ftpuser/start.inc.php';
   }
   // FTP-User konnte nicht entfernz werden
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular($statusmeldung);
   }
}



############################## UNTERFUNKTIONEN #############################

// Eingabeformular
function eingabeformular($statusmeldung = false) {
   $ausgabe ="<h2>&raquo; FTP-User l&ouml;schen</h2>
   ".$statusmeldung."
   <br />
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=ftpuser&action=delete&ftpuser=" . $_GET['ftpuser'] . "\">
   <table>
      <tr>
         <td><b>FTP-User \"" . $_GET['ftpuser'] . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=ftpuser\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
   return $ausgabe;
}
?>