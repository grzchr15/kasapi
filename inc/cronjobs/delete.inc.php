<?php
// Formular wurde noch nicht abgesenet
if (!$_POST['submit'])
{
   // Ausgabe zusammenbauen
   $ausgabe ="<h2>&raquo; Cronjob l&ouml;schen</h2>
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=cronjobs&action=delete&cronjob_id=" . $_GET['cronjob_id'] . "&comment=".$_GET['comment']."\">
   <table>
      <tr>
         <td><b>Cronjob \"" . base64_decode($_GET['comment']) . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td>Bitte beachten Sie, dass dieser Cronjob nun unwiederruflich gel&ouml;scht wird.<br /><br />Dieser kann jedoch jederzeit wieder neu angelegt werden. Sollten Sie den Cronjob nur vorr&uuml;bergehend aussetzen wollen, k&ouml;nnen Sie dies auch unter dem Punkt 'Cronjobs' - 'bearbeiten' tun, indem Sie den betreffenden Cronjob deaktivieren. Dieser kann anschliessend jederzeit einfach wieder aktiviert werden.</td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=cronjobs\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
}
// Formular wurde bereits abgesendet
else
{
   // E-Mail-Account löschen
   $return = kas_action("kas_action=delete_cronjob&cronjob_id=" . $_GET['cronjob_id']);
   
   // E-Mail-Account konnte erfolgreich gelöscht werden
   if ($return['returnstring'])
   {
      $statusmeldung = "<div id=\"success\">Der Cronjob \"" . base64_decode($_GET['comment']) . "\" wurde gel&ouml;scht.</div>";
   }
   // E-Mail-Account konnte nicht gelöscht werden
   else
   {
      $statusmeldung = "<div id=\"error\">Fehler: Der Cronjob \"" . base64_decode($_GET['comment']) . "\" konnte nicht gel&ouml;scht werden.</div>";
   }
   include_once $includedir . 'inc/cronjobs/start.inc.php';
}
?>
