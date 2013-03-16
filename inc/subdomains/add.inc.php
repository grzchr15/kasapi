<?php
// KAS-Abfrage - Ressourcen des aktuellen Logins ermitteln
$return = kas_action("kas_action=get_accountressources");

// wenn das Formuar nochnicht abgesendet wurde
if (!$_POST['submit'] && $return['returninfo']['max_subdomain']['free'] != 0)
{
   $ausgabe = eingabeformular();
}
// es können keine Subdomains mehr angelegt werdenl, weil keine Ressourcen mehr frei sind
elseif ($return['returninfo']['max_subdomain']['free'] == 0)
{
   $statusmeldung = "<div id=\"error\">Fehler: Es k&ouml;nnen keine Subdomains mehr angelegt werden.</div>";
   include_once $includedir . 'inc/subdomains/start.inc.php';
}
else {
   // neue Subdomain anlegen
   $redirect_status = $_POST['domainziel'] == 'dir' ? '0' : $_POST['redirect_code'];
   $return = kas_action("kas_action=add_subdomain&subdomain_name=" . $_POST['subdomain_1'] . "&domain_name=" . $_POST['subdomain_2'] . "&subdomain_path=" . $_POST['subdomain_homedir'] . "&redirect_status=" . $redirect_status);

   // wenn die neue Subdomain erfolgreich angelegt werden konnte
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die Subdomain ".$return['returninfo']." wurde erstellt.</div>";
      include_once $includedir . 'inc/subdomains/start.inc.php';
   }
   // die neue Subdomain konnte nicht angelegt werden
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($statusmeldung);
   }
}



########################### UNTERFUNKTIONEN ###########################

// Eingabeformular
function eingabeformular($statusmeldung=false)
{
   
// Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; Subdomain anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_domain\" method=\"post\" action=\"?category=subdomains&action=add\">
<table>
   <tr>
      <td>Subdomainname:</td>
      <td nowrap><input name=\"subdomain_1\" type=\"text\" size=\"25\" value=\"\" />.
      <select name=\"subdomain_2\">";

// Alle Cnames ermitteln, die unter dem aktuellen Login angelegt sind
$return = kas_action("kas_action=get_domains");
   
// Select-Auswahlliste mit allen Cnames bauen
if (sizeof($return['returninfo'])>0) {
   foreach($return['returninfo'] as $key => $val) {            
      $ausgabe .= "\n<option value=\"".$val['domain_name']."\">".$val['domain_name']."</option>";            
   }
}

// Alle Subdomains ermitteln, die unter dem aktuellen Login angelegt sind
$return = kas_action("kas_action=get_subdomains");
   
// Select-Auswahlliste mit allen Subdomains bauen
if (sizeof($return['returninfo'])>0) {
   foreach($return['returninfo'] as $key => $val) {            
      $ausgabe .= "\n<option value=\"".$val['subdomain_name']."\">".$val['subdomain_name']."</option>";            
   }
}
      
$ausgabe .= "
      </select>
      </td>
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
            <option value=\"dir\">Verzeichnis</option>
            <option value=\"ext\">externes Ziel</option>
         </select>
      </td>
      <td>
         <div style=\"float:left\">
         <input name=\"subdomain_homedir\" type=\"text\" size=\"25\" value=\"\" />&nbsp;&nbsp;
         </div>
         <div id=\"ext\" style=\"float:left;display:none;\">
         <select name=\"redirect_code\">
            <option value=\"301\">301 - moved permanently</option>
            <option value=\"302\">302 - found</option>
            <option value=\"307\">307 - temporary redirect</option>
         </select>
         </div>
         <div style=\"clear:left;\"></div>
      </td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Subdomain anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=subdomains\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}
?>