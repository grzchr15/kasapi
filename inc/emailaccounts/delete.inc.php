<?php
// Formular wurde noch nicht abgesenet
if (!$_POST['submit'])
{
   // Ausgabe zusammenbauen
   $ausgabe ="<h2>&raquo; E-Mail-Account l&ouml;schen</h2>
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=emailaccounts&action=delete&maillogin=" . $_GET['maillogin'] . "\">
   <table>
      <tr>
         <td><b>E-Mail-Account \"" . $_GET['maillogin'] . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td>Bitte beachten Sie, dass alle zur Zeit in diesem E-Mail-Account befindlichen Daten unwiederbringlich gel&ouml;scht werden.</td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=emailaccounts\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
}
// Formular wurde bereits abgesendet
else
{
   // E-Mail-Account löschen
   $return = kas_action("kas_action=delete_mailaccount&mail_login=" . $_GET['maillogin']);
   
   // E-Mail-Account konnte erfolgreich gelöscht werden
   if ($return['returnstring'])
   {
      $statusmeldung = "<div id=\"success\">Der E-Mail-Account " . $_GET['maillogin'] . " wurde gel&ouml;scht.</div>";
   }
   // E-Mail-Account konnte nicht gelöscht werden
   else
   {
      $statusmeldung = "<div id=\"error\">Fehler: Der E-Mail-Account " . $_GET['mailaccount'] . " konnte nicht gel&ouml;scht werden.</div>";
   }
   include_once $includedir . 'inc/emailaccounts/start.inc.php';
}
?>
