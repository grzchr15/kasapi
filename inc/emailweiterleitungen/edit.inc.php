<?php
// wenn das Formular noch nicht abgesendet wurde
if (!$_POST['submit'])
{
   // Daten der Weiterleitung holen
   $return = kas_action("kas_action=get_mailforwards&mail_forward=" . $_GET['weiterleitung']);
   $ausgabe = eingabeformular($return['returninfo']);
}
// wenn das Formular bereits abgesendet wurde
else {
   $targetnr = 0;
   // Daten aus dem $_POST-Array holen und KAS-Query zusammenbauen
   for ($i=1;$i<=10;$i++) {
      if ($_POST['ziel_'.$i]) {
      $query .= "&target_" . $targetnr . "=" . $_POST['ziel_'.$i];
      $targetnr++;
      }       
   }

   // Änderungen speichern
   $return = kas_action("kas_action=update_mailforward&mail_forward=" . $_GET['weiterleitung'] . $query);  

   // Änderungen konnten erfolgreich gespeichert werden
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die &Auml;nderungen wurden &uuml;bernommen.</div>";
      include_once $includedir . 'inc/emailweiterleitungen/start.inc.php';
   }
   // Änderungen konnten nicht gespeichert werden
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($return['returninfo'],$statusmeldung);
   }
}


############################ UNTERFUNKTIONEN ####################

// Eingabeformular
function eingabeformular($returninfo,$statusmeldung=false)
{
$targetnr = 1;
// wurde das Formular bereits abgesendet, hole die Daten aus dem $_POST-Array
if ($_POST['submit']) {   
   for ($i=0;$i<=10;$i++) {
      if ($_POST['ziel_'.$i]) {
         $weiterleitung_ziel[$targetnr] = $_POST['ziel_'.$i];
         if ($targetnr != $i) {
            unset($_POST['ziel_'.$i]);
         }
         $targetnr++;
      }
   }
}
// wurde das Formular nochnicht abgesendet, hole dei Daten aus dem Datenbestand
else {
   if (is_array($returninfo)) {
      foreach ($returninfo as $key => $val) {
         if ($val['mail_forward_adress'] == $_GET['weiterleitung']) {
            $zielearray = explode(",",$val['mail_forward_targets']);
            for ($i=0;$i<sizeof($zielearray);$i++) {
               $weiterleitung_ziel[$i+1] = $zielearray[$i];
            }
         }
      }  
   }
}

// Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; E-Mail-Weiterleitung bearbeiten</h2>
".$statusmeldung."
<br />
<form name=\"add_mailforward\" method=\"post\" action=\"?category=emailweiterleitungen&action=edit&weiterleitung=" . $_GET['weiterleitung'] . "\">
<table>
   <tr>
      <td nowrap>Weiterleitungsadresse:</td>
      <td nowrap>" . $_GET['weiterleitung'] . "</td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>";

// 10 Weiterleitungsziele ausgeben
for ($i=1; $i <=10; $i++) {
   $ausgabe .= "   
   <tr>
      <td>Weiterleitungsziel " . $i . ":</td>
      <td><input name=\"ziel_" . $i . "\" type=\"text\" value=\"".$weiterleitung_ziel[$i]."\" size=\"40\" /></td>
   </tr>";
}
   
$ausgabe .= "
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"E-Mail-Weiterleitung bearbeiten\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=emailweiterleitungen\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}
?>