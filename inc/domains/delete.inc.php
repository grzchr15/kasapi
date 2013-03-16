<?php
// erster Aufruf des Formulares
if (!$_POST['submit'])
{
   // Ausgabe zusammenbauen
   $ausgabe ="<h2>&raquo; Domain l&ouml;schen</h2>
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=domains&action=delete&domain=" . $_GET['domain'] . "\">
   <table>
      <tr>
         <td><b>Domain \"" . $_GET['domain'] . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td>Bitte beachten Sie, dass alle zu der Domain geh&ouml;renden E-Mail-Accounts, E-Mail-Weiterleitungen, Subdomains sowie Mailinglisten unwiederbringlich gel&ouml;scht werden.</td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=domains\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
}
// Formular wurde abgesendet
else
{
   // Löschen der Domain / Cname durchführen
   $return = kas_action("kas_action=delete_domain&domain_name=" . $_GET['domain']);
   
   // Löschvorgang erfolgreich
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Der Domain " . $_GET['domain'] . " wurde gel&ouml;scht.</div>";
   }
   // Löschvorgang nicht erfolgreich
   else
   {
      $statusmeldung = "<div id=\"error\">Fehler: Die Domain " . $_GET['domain'] . " konnte nicht gel&ouml;scht werden.</div>";
   }
   include_once $includedir . 'inc/domains/start.inc.php';
}
?>