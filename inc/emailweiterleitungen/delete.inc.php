<?php
// wenn das Formular noch nicht abgesendet wurde
if (!$_POST['submit'])
{
   $ausgabe ="<h2>&raquo; E-Mail-Weiterleitung l&ouml;schen</h2>
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=emailweiterleitungen&action=delete&weiterleitung=" . $_GET['weiterleitung'] . "\">
   <table>
      <tr>
         <td><b>E-Mail-Weiterleitung \"" . $_GET['weiterleitung'] . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=emailweiterleitungen\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
}
// wenn das Formular bereits abgesendet wurde
else
{
   // Weiterleitung löschen
   $return = kas_action("kas_action=delete_mailforward&mail_forward=" . $_GET['weiterleitung']);
   
   // wenn die Weiterleitung erfolgreich gelöscht werden konnte
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die E-Mail-Weiterleitung " . $_GET['weiterleitung'] . " wurde gel&ouml;scht.</div>";
   }
   // wenn die Weiterleitung nicht gelöscht werden konnte
   else
   {
      $statusmeldung = "<div id=\"error\">Fehler: Die E-Mail-Weiterleitung " . $_GET['weiterleitung'] . " konnte nicht gel&ouml;scht werden.</div>";
   }
   include_once $includedir . 'inc/emailweiterleitungen/start.inc.php';
}
?>