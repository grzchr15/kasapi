<?php
// Ressourcen des aktuellen Logins holen
$return = kas_action("kas_action=get_accountressources");

// wenn das Formular noch nicht abgesendet wurde
if (!$_POST['submit'] && $return['returninfo']['max_mail_forward']['free'] != 0)
{
   $ausgabe = eingabeformular();
}
// wenn keine Weiterleitungen mehr angelegt werden können, weil die Ressourcen nicht ausreichen
elseif ($return['returninfo']['max_mail_forward']['free'] == 0)
{
   $statusmeldung = "<div id=\"error\">Fehler: Es k&ouml;nnen keine E-Mail-Weiterleitungen mehr angelegt werden.</div>";
   include_once $includedir . 'inc/emailweiterleitungen/start.inc.php';
}
// das Formular wurde abgesendet und es können noch Weiterleitungen angelegt werden
else {
      $targetnr = 0;
      // Daten aus dem $_POST-Array holen und KAS-Query zusammenbauen
      for ($i=1;$i<=10;$i++) {
        if ($_POST['ziel_'.$i]) {
         $query .= "&target_" . $targetnr . "=" . $_POST['ziel_'.$i];
         $targetnr++;
        }       
      }
   
      //Weiterleitung anlegen
      $return = kas_action("kas_action=add_mailforward&local_part=" . $_POST['email_1'] . "&domain_part=" . $_POST['email_2'] . $query); 
      
      // Weiterleitung konnte erfolgreich angelegt werden
      if ($return['returnstring'] == "TRUE")
      {
         $statusmeldung = "<div id=\"success\">Die E-Mail-Weiterleitung wurde erstellt.</div>";
         include_once $includedir . 'inc/emailweiterleitungen/start.inc.php';
      }
      // Weiterleitung konnte nicht angelegt werden
      else
      {
         // Fehlermeldungen einbinden
         include_once $includedir . 'inc/errors.inc.php';
         $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
         $ausgabe = eingabeformular ($statusmeldung);
      }
}



############################ UNTERFUNKTIONEN ######################

// Eingabeformular
function eingabeformular($statusmeldung=false)
{
   
$targetnr = 1;
// Leere Eingabezeilen entfernen (visualisiert betrachtet)
for ($i=1;$i<=10;$i++) {
   if ($_POST['ziel_'.$i]) {
      $_POST['ziel_'.$targetnr] = $_POST['ziel_'.$i];
      if ($targetnr != $i) {
         unset($_POST['ziel_'.$i]);
      }
      $targetnr++;
   }       
} 
  
// Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; E-Mail-Weiterleitung anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_mailforward\" method=\"post\" action=\"?category=emailweiterleitungen&action=add\">
<table>
   <tr>
      <td nowrap>Weiterleitungsadresse:</td>
      <td nowrap><input name=\"email_1\" type=\"text\" size=\"25\" value=\"".$_POST['email_1']."\" />@
      <select name=\"email_2\">";

// Alle Cnames die in dem Aktuellen Account angelegt sind holen
$return = kas_action("kas_action=get_domains");
     
// Select-Feld zusammenbauen
if (sizeof($return['returninfo'])>0) {
   foreach($return['returninfo'] as $key => $val) {
      $_POST['email_2'] == $val['domain_name'] ? $attribut = " selected" : $attribut = "";
      $ausgabe .= "\n<option value=\"".$val['domain_name']."\"".$attribut.">".$val['domain_name']."</option>";            
   }
}

// Alle Subdomains die in dem Aktuellen Account angelegt sind holen
$return = kas_action("kas_action=get_subdomains");
     
// Select-Feld zusammenbauen
if (sizeof($return['returninfo'])>0) {
   foreach($return['returninfo'] as $key => $val) {
      $_POST['email_2'] == $val['subdomain_name'] ? $attribut = " selected" : $attribut = "";
      $ausgabe .= "\n<option value=\"".$val['subdomain_name']."\"".$attribut.">".$val['subdomain_name']."</option>";            
   }
}
      
$ausgabe .= "
      </select>
      </td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>";

// 10 Weiterleitungsziele ausgeben
for ($i=1; $i <=10; $i++) {
   $ausgabe .= "   
   <tr>
      <td>Weiterleitungsziel " . $i . ":</td>
      <td><input name=\"ziel_" . $i . "\" type=\"text\" value=\"".$_POST['ziel_' . $i]."\" size=\"40\" /></td>
   </tr>";
}
   
$ausgabe .= "
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"E-Mail-Weiterleitung anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=emailweiterleitungen\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}
?>