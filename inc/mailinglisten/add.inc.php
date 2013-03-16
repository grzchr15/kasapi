<?php
// KAS-Abfrage durchführen - Ressourcen des aktuellen ´Logins ermitteld
$return = kas_action("kas_action=get_accountressources");

// wenn das Formular noch nicht versendet wurde
if (!$_POST['submit'] && $return['returninfo']['max_mailinglist']['free'] != 0)
{
   $ausgabe = eingabeformular();
}
// es können keine Mailinglisten mehr angelegt werden, da die Ressourcen nicht mehr ausreichen
elseif ($return['returninfo']['max_mailinglist']['free'] == 0)
{
   $statusmeldung = "<div id=\"error\">Fehler: Es k&ouml;nnen keine Mailinglisten mehr angelegt werden.</div>";
   include_once $includedir . 'inc/mailinglisten/start.inc.php';
}
// Ressourcen reichen aus und das Formular wurde bereits versendet
else {
      // wenn die eingegebenen Passwörter nicht übereinstimmen
      if ($_POST['password_1'] != $_POST['password_2']) {
         $return['returnstring'] = "Die Passw&ouml;rter sind ungleich.";      
      }
      else
      {   
         // Mailingliste anlegen
         $return = kas_action("kas_action=add_mailinglist&mailinglist_name=" . $_POST['mailingliste_1'] . "&mailinglist_domain=" . $_POST['mailingliste_2'] . "&mailinglist_password=" . $_POST['password_1']);
      } 
   
      // wenn die Mailingliste erfolgreich erstellt wurde
      if ($return['returnstring'] == "TRUE")
      {
         $statusmeldung = "<div id=\"success\">Die Mailingliste wurde erstellt.</div>";
         include_once $includedir . 'inc/mailinglisten/start.inc.php';
      }
      // wenn die Mailingliste nicht erstellt werden konnte
      else
      {
         // Fehlermeldungen einbinden
         include_once $includedir . 'inc/errors.inc.php';
         $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
         $ausgabe = eingabeformular ($statusmeldung);
      }
}



################################# UNTERFUNKTIONEN #############################

// Eingabeforlular
function eingabeformular($statusmeldung=false)
{

// Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; Mailingliste anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_mailinglist\" method=\"post\" action=\"?category=mailinglisten&action=add\">
<table>
   <tr>
      <td nowrap>Name der neuen Mailingliste:</td>
      <td nowrap><input name=\"mailingliste_1\" type=\"text\" size=\"25\" value=\"".$_POST['mailingliste_1']."\" />-
      <select name=\"mailingliste_2\">";

// alle Cnames des aktuellen Logins ermitteln
$return = kas_action("kas_action=get_subdomains");

// Subdomain-Liste für Select-Feld
if (sizeof($return['returninfo'])>0) {
   foreach($return['returninfo'] as $key => $val) {
      $_POST['mailingliste_2'] == $val['subdomain_name'] ? $attribut = " selected" : $attribut = "";
      $ausgabe .= "\n<option value=\"".$val['subdomain_name']."\"".$attribut.">".$val['subdomain_name']."</option>";            
   }
}

// alle Subdomains des aktuellen Logins ermitteln
$return = kas_action("kas_action=get_domains");

// Dname-Liste für Select-Feld
if (sizeof($return['returninfo'])>0) {
   foreach($return['returninfo'] as $key => $val) {
      $_POST['mailingliste_2'] == $val['domain_name'] ? $attribut = " selected" : $attribut = "";
      $ausgabe .= "\n<option value=\"".$val['domain_name']."\"".$attribut.">".$val['domain_name']."</option>";            
   }
}
      
$ausgabe .= "
      </select>
      </td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td>Passwort:</td>
      <td><input name=\"password_1\" type=\"".pass_inputtype()."\" value=\"".$_POST['password_1']."\" size=\"25\" maxlength=\"20\" /></td>
   </tr>
   <tr>
      <td>Passwort wiederholen:</td>
      <td><input name=\"password_2\" type=\"".pass_inputtype()."\" value=\"".$_POST['password_2']."\" size=\"25\" maxlength=\"20\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"commet\" style=\"text-align:center;\">Hinweis: Passw&ouml;rter m&uuml;ssen aus 6 - 20 (a-zA-Z0-9) Zeichen bestehen.</td>
   </tr>
   <tr>
      <td colspan=\"2\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Mailingliste anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=mailinglisten\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}
?>