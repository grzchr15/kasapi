<?php
// Formular wurd zum erstem mal aufgerufem
if (!$_POST['submit'])
{
   $ausgabe ="<h2>&raquo; Account l&ouml;schen</h2>
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=accounts&action=delete&account=" . $_GET['account'] . "&pass= " . $_GET['pass'] . "\">
   <table>
      <tr>
         <td><b>Account \"" . $_GET['account'] . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td>Bitte beachten Sie, dass alle in dem Account erstellten Unteraccounts, Datenbanken, Webspace (FTP), E-Mailaccounts und E-Mailweiterleitungen unwiederbringlich gel&ouml;scht werden.</td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=accounts\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
}
// "Löschen" - Button wurde betätigt
else
{
   // Unteraccount Löschen wird ausgeführt
   $return = kas_action("kas_action=delete_account&account_login=" . $_GET['account'] . "&account_kas_password=" . $_GET['pass']);
    
   // Löschvorgang war erfolgreich
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Der Account " . $_GET['account'] . " wurde gel&ouml;scht.</div>";
   }
   // Löschvorgang war nicht erfolgreich
   else
   {
      $statusmeldung = "<div id=\"error\">Fehler: Der Account " . $_GET['account'] . " konnte nicht gel&ouml;scht werden.</div>";
   }
   include_once $includedir . 'inc/accounts/start.inc.php';
}
?>