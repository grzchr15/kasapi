<?php
// Ressourcen des aktuellen Logins holen
$return = kas_action("kas_action=get_accountressources");

// erster Aufruf des Formulares
if (!$_POST['submit'] && $return['returninfo']['max_domain']['free'] != 0)
{
   $ausgabe = eingabeformular();
}
// es können keine Domains mehr angelegt werden, weil keine Ressourcen mehr da sind
elseif ($return['returninfo']['max_domain']['free'] == 0)
{
   $statusmeldung = "<div id=\"error\">Fehler: Es k&ouml;nnen keine Domains mehr angelegt werden.</div>";
   include_once $includedir . 'inc/domains/start.inc.php';
}
// Formular wurde abgesendet
else {
   // Domain anlegen
   $redirect_status = $_POST['domainziel'] == 'dir' ? '0' : $_POST['redirect_code'];
   $return = kas_action("kas_action=add_domain&domain_name=" . $_POST['domain_name'] . "&domain_tld=" . $_POST['tld'] . "&domain_path=" . $_POST['domain_homedir'] . "&redirect_status=" . $redirect_status);
   
   // Domain konnte erfolgreich angelegt werden
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die Domain ".$return['returninfo']." wurde erstellt.</div>";
      include_once $includedir . 'inc/domains/start.inc.php';
   }
   // Domain konnte nicht angelegt werden
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($statusmeldung);
   }
}


########################### UNTERFUNKTIONEN #######################

// Eingabeformular
function eingabeformular($statusmeldung=false) {
GLOBAL $includedir;

// Liste der möglichen TLDs holen
include_once $includedir . 'inc/tld.inc.php';

// Ausgabe zusammenbauen  
$ausgabe = "<h2>&raquo; Domain anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_domain\" method=\"post\" action=\"?category=domains&action=add\">
<table>
   <tr>
      <td>Domainname (ohne www.):</td>
      <td width=\"65%\" nowrap><input name=\"domain_name\" type=\"text\" size=\"25\" value=\"".$_POST['domain_name']."\" />.
      <select name=\"tld\">";

// TLD - Ausgabe      
foreach($tld_array as $key => $val) {            
   $ausgabe .= "\n<option value=\"".$val."\">.".$val."</option>";
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
         <input name=\"domain_homedir\" type=\"text\" size=\"25\" value=\"".$_POST['domain_homedir']."\" />&nbsp;&nbsp;
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
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Domain anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" ></td>
   </tr>
   <tr>
      <td colspan=\"2\" style=\"text-align:center;\"><a href=\"?category=domains\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>";
return $ausgabe;
}
?>