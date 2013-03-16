<?php
// wenn das Formular noch nicht abgesendet wurde
if (!$_POST['submit'])
{
   // Daten der aktuellen Subdomain ermitteln
   $return = kas_action("kas_action=get_subdomains&subdomain_name=" . $_GET['subdomain']);
   $ausgabe = eingabeformular($return['returninfo']);
}
// Formular wurde bereits abgesendet
else {
// Subdomaindaten updaten
$redirect_status = $_POST['subdomainziel'] == 'dir' ? '0' : $_POST['redirect_code'];
$return = kas_action("kas_action=update_subdomain&subdomain_name=" . $_GET['subdomain'] . "&subdomain_path=" . $_POST['subdomain_homedir'] . "&redirect_status=" . $redirect_status);

   // wenn die Daten erfolgreich upgedated werden konnten
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die &Auml;nderungen wurden &uuml;bernommen.</div>";
      include_once $includedir . 'inc/subdomains/start.inc.php';
   }
   // die Daten konnten nicht erfolgreich upgedated werden
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($return['returninfo'],$statusmeldung);
   }
}


########################### UNTERFUNKTIONEN ####################

// Eingabeformular
function eingabeformular($returninfo,$statusmeldung=false)
{

// Pfad / Homedir der aktuellen Subdomain ermitteln  
if (is_array($returninfo)) {
   foreach ($returninfo as $key => $val) {
      if ($val['subdomain_name'] == $_GET['subdomain']) {
         $subdomain_homedir = $val['subdomain_path'];
         $subdomain_redirect_status = $val['subdomain_redirect_status'];
      }
   }  
} else {
   $subdomain_homedir = $_POST['subdomain_homedir'];
   $subdomain_redirect_status = $_POST['subdomainziel'] == 'dir' ? '0' : $_POST['subdomain_redirect_status'];
}
//Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; Subdomain bearbeiten</h2>
".$statusmeldung."
<br />
<form name=\"add_domain\" method=\"post\" action=\"?category=subdomains&action=edit&subdomain=".$_GET['subdomain']."\">
<table>
   <tr>
      <td>Subdomainname:</td>
      <td width=\"65%\"><b>".$_GET['subdomain']."</b></td>
   </tr>
   <tr>
      <td colspan=\"2\">&nbsp;</td>
   </tr>
   <tr>
      <td colspan=\"2\">soll leiten auf:</td>
   </tr>
   <tr>
      <td>
         <select name=\"subdomainziel\" onchange=\"JavaScript:document.getElementById('ext').style.display = (this.value == 'ext') ? '' : 'none';\">
            <option value=\"dir\"".($subdomain_redirect_status == '0' ? ' selected' : '').">Verzeichnis</option>
            <option value=\"ext\"".($subdomain_redirect_status != '0' ? ' selected' : '').">externes Ziel</option>
         </select>
      </td>
      <td>
         <div style=\"float:left\">
         <input name=\"subdomain_homedir\" type=\"text\" size=\"25\" value=\"".$subdomain_homedir."\" />&nbsp;&nbsp;
         </div>
         <div id=\"ext\" style=\"float:left;display:".($subdomain_redirect_status == '0' ? 'none' : '').";\">
         <select name=\"redirect_code\">
            <option value=\"301\"".($subdomain_redirect_status == '301' ? ' selected' : '').">301 - moved permanently</option>
            <option value=\"302\"".($subdomain_redirect_status == '302' ? ' selected' : '').">302 - found</option>
            <option value=\"307\"".($subdomain_redirect_status == '307' ? ' selected' : '').">307 - temporary redirect</option>
         </select>
         </div>
         <div style=\"clear:left;\"></div>
      </td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Subdomain bearbeiten\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=subdomains\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}
?>