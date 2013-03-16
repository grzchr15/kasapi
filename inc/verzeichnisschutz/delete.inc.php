<?php
// wenn das Formular noch nicht versendet wurde
if (!$_POST['submit'])
{
   // Ausgabe zusammenbauen
   $ausgabe ="<h2>&raquo; Verzeichnisschutz-User l&ouml;schen</h2>
   <form name=\"confirm_deleting\" method=\"post\" action=\"?category=verzeichnisschutz&action=delete&benutzer=" . $_GET['benutzer'] . "&verzeichnis=" . $_GET['verzeichnis'] . "\">
   <table>
      <tr>
         <td><b>Benutzer \"" . $_GET['benutzer'] . "\" f&uuml;r Verzeichnis \"" . $_GET['verzeichnis'] . "\" wirlklich l&ouml;schen?</b></td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td>Sollte es sich bei dem Benutzer um den letzten in diesem gesch&uuml;tzten Verzeichnis handeln, wird der komplette Verzeichnisschutz f&uuml;r dieses Verzeichnis aufgehoben.</td>
      </tr>
      <tr>
         <td><br /><br /></td>
      </tr>
      <tr>
         <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"jetzt l&ouml;schen\"></td>
      </tr>
      <tr>
         <td id=\"button\"><a href=\"?category=verzeichnisschutz\" target=\"_self\">abbrechen</a></td>
      </tr>
   </table>
   </form>
   ";
}
// das Formular wurde bereits versendet
else
{
   // Verzeichnisschutz anlegen
   $return = kas_action("kas_action=delete_directoryprotection&directory_path=" . $_GET['verzeichnis'] . "&directory_user=" . $_GET['benutzer']);;
   
   // wenn der Verzeichnisschutz erfolgreich angelegt werden konnte
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Der Benutzer " . $_GET['benutzer'] . " f&uuml;r das Verzeichnis " . $_GET['verzeichnis'] . " wurde gel&ouml;scht.</div>";
   }
   // der Verzeichnisschutz konnte nicht angelegt werden
   else
   {
      $statusmeldung = "<div id=\"error\">Fehler: Der Benutzer " . $_GET['benutzer'] . " konnte f&uuml;r das Verzeichnis " . $_GET['verzeichnis'] . " nicht gel&ouml;scht werden.</div>";
   }
   include_once $includedir . 'inc/verzeichnisschutz/start.inc.php';
}
?>
