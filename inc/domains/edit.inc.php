<?php
// erster Aufruf des Formulares
if (!$_POST['submit'])
{
   // Domaindaten holen
   $return = kas_action("kas_action=get_domains&domain_name=" . $_GET['domain']);
   $ausgabe = eingabeformular($return['returninfo']);
}
// Formular wurde abgesendet
else {
// abgesendete Formulardaten speichern
$redirect_status = $_POST['domainziel'] == 'dir' ? '0' : $_POST['redirect_code'];
$return = kas_action("kas_action=update_domain&domain_name=" . $_GET['domain'] . "&domain_path=" . $_POST['domain_homedir'] . "&redirect_status=" . $redirect_status);

   // Daten konnten erfolgreich gespeichert werden
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die &Auml;nderungen wurden &uuml;bernommen.</div>";
      include_once $includedir . 'inc/domains/start.inc.php';
   }
   // Daten konnten nicht erfolgreich gespeichert werden
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($return['returninfo'],$statusmeldung);
   }
}


######################## UNTERFUNKTIONEN ######################

// Eingabeformular
function eingabeformular($returninfo,$statusmeldung=false)
{
   
// Startwerte aus Datenbestand holen, wenn das Formular noch nicht angesendet
if (is_array($returninfo)) {
   foreach ($returninfo as $key => $val) {
      if ($val['domain_name'] == $_GET['domain']) {
         $domain_homedir = $val['domain_path'];
         $domain_redirect_status = $val['domain_redirect_status'];
      }
   }
}
// Startwerte aus $_POST-Array holen, wenn das Formular bereits abgesendet wurde
else {
   $domain_homedir = $_POST['domain_homedir'];
   $domain_redirect_status = $_POST['domainziel'] == 'dir' ? '0' : $_POST['domain_redirect_status'];
}
  
$ausgabe = "<h2>&raquo; Domain bearbeiten</h2>
".$statusmeldung."
<br />
<form name=\"add_domain\" method=\"post\" action=\"?category=domains&action=edit&domain=".$_GET['domain']."\">
<table>
   <tr>
      <td>Domainname:</td>
      <td width=\"65%\"><b>".$_GET['domain']."</b></td>
   </tr>
   <tr>
      <td colspan=\"2\">&nbsp;</td>
   </tr>
   <tr>
      <td colspan=\"2\">soll leiten auf:</td>
   </tr>
   <tr>
      <td>
         <select name=\"domainziel\" onchange=\"JavaScript:document.getElementById('ext').style.display = (this.value == 'ext') ? '' : 'none';\">
            <option value=\"dir\"".($domain_redirect_status == '0' ? ' selected' : '').">Verzeichnis</option>
            <option value=\"ext\"".($domain_redirect_status != '0' ? ' selected' : '').">externes Ziel</option>
         </select>
      </td>
      <td>
         <div style=\"float:left;\">
            <input name=\"domain_homedir\" type=\"text\" size=\"25\" value=\"".($_POST['domain_homedir'] ? $_POST['domain_homedir'] : $domain_homedir)."\" />&nbsp;&nbsp;
         </div>
         <div id=\"ext\" style=\"float:left;display:".($domain_redirect_status == '0' ? 'none' : '').";\">
         <select name=\"redirect_code\">
            <option value=\"301\"".($domain_redirect_status == '301' ? ' selected' : '').">301 - moved permanently</option>
            <option value=\"302\"".($domain_redirect_status == '302' ? ' selected' : '').">302 - found</option>
            <option value=\"307\"".($domain_redirect_status == '307' ? ' selected' : '').">307 - temporary redirect</option>
         </select>
         </div>
         <div style=\"clear:left;\"></div>
      </td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Domain bearbeiten\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=domains\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}

?>