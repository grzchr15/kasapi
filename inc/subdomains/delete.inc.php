<?php
// wenn das Formular noch nicht abgesendet wurde
if (!$_POST['submit'])
{
   // Ausgabe zusammenbauen
   $ausgabe ="<h2>&raquo; Subdomain l&ouml;schen</h2>
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=subdomains&action=delete&subdomain=" . $_GET['subdomain'] . "\">
   <table>
      <tr>
         <td><b>Subdomain \"" . $_GET['subdomain'] . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td>Bitte beachten Sie, dass alle zu der Subdomain geh&ouml;renden E-Mail-Accounts und E-Mail-Weiterleitungen unwiederbringlich gel&ouml;scht werden.</td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=subdomains\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
}
// das Formular wurde bereits versendet
else
{
   // Subdomain löschen
   $return = kas_action("kas_action=delete_subdomain&subdomain_name=" . $_GET['subdomain']);
   
   // wenn das Löschen der Subdomain erfolgreich war
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die Subdomain " . $_GET['subdomain'] . " wurde gel&ouml;scht.</div>";
   }
   // das Löschen der Subdomain war nicht erfolgreich
   else
   {
      $statusmeldung = "<div id=\"error\">Fehler: Die Subdomain " . $_GET['subdomain'] . " konnte nicht gel&ouml;scht werden.</div>";
   }
   include_once $includedir . 'inc/subdomains/start.inc.php';
}
?>
