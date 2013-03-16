<?php
// das Formlar wurde noch nicht versendet
if (!$_POST['submit'])
{
   // Ausgabe zusammenbauen
   $ausgabe ="<h2>&raquo; Mailingliste l&ouml;schen</h2>
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=mailinglisten&action=delete&mailingliste=" . $_GET['mailingliste'] . "\">
   <table>
      <tr>
         <td><b>Mailingliste \"" . $_GET['mailingliste'] . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=mailinglisten\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
}
// das Formular wurde bereits abgeschickt
else
{
   // Mailingliste löschen
   $return = kas_action("kas_action=delete_mailinglist&mailinglist_name=" . $_GET['mailingliste']);
   
   // wenn die Mailingliste erfolgreich gelöscht wurde
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die Mailingliste " . $_GET['mailingliste'] . " wurde gel&ouml;scht.</div>";
   }
   // die Mailingliste konnte nicht gelöscht werden
   else
   {
      $statusmeldung = "<div id=\"error\">Fehler: Die Mailingliste " . $_GET['mailingliste'] . " konnte nicht gel&ouml;scht werden.</div>";
   }
   include_once $includedir . 'inc/mailinglisten/start.inc.php';
}
?>
