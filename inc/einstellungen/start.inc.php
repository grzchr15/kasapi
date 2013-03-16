<?php
// Statusmeldung ist standardmässig leer
$statusmeldung = false;

// Formular wurde abgesendet
if (isset($_POST['submit']) && ($_POST['submit'] == "speichern")) {
   
   // Einstellungen speichern
   $return = kas_action("kas_action=update_accountsettings&show_password=".$_POST['show_password']."&logging=".$_POST['accesslog']);
        
   // Einstellungen wurden erfolgreich gespeichert
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Die &Auml;nderungen wurden &uuml;bernommen.</div>";
      
      // Sessionwerte updaten
      $_SESSION[$_SESSION['kas_login']]['show_password'] = $_POST['show_password'];
      $_SESSION[$_SESSION['kas_login']]['logging'] = $_POST['accesslog'];
   }
   // Einstellungen konnten nicht gespeichert werden
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
   }  
}

// Formular ausgeben
$ausgabe = eingabeformular($statusmeldung);



##################### UNTERFUNKTIONEN #########################

// Eingabeformular
function eingabeformular ($statusmeldung) {
$nein_selected="";
$ja_selected="";
$voll_selected = "";
$ohneip_selected = "";
$keine_selected = "";
// Einstellungsdaten zum aktuellen Account aus der Session holen
if($_SESSION[$_SESSION['kas_login']]['show_password'] == 'Y'){
	$ja_selected = " selected";
	$nein_selected="";
}else{
	$ja_selected = "";
	$nein_selected = " selected";
}
$_SESSION[$_SESSION['kas_login']]['logging'] == 'voll' ? $voll_selected = " selected" : $voll_selected = "";
$_SESSION[$_SESSION['kas_login']]['logging'] == 'kurz' ? $kurz_selected = " selected" : $kurz_selected = "";
$_SESSION[$_SESSION['kas_login']]['logging'] == 'ohneip' ? $ohneip_selected = " selected" : $ohneip_selected = "";
$_SESSION[$_SESSION['kas_login']]['logging'] == 'keine' ? $keine_selected = " selected" : $keine_selected = "";

// Ausgabe zusammenbauen   
$ausgabe = "<h2>&raquo; Einstellungen</h2>
".$statusmeldung."
<br /><br />
<form name=\"einstellungen\" method=\"post\" action=\"?category=einstellungen\">
<table>
   <tr>
      <td id=\"headline\" style=\"width:50%;\">Passw&ouml;rter im Klartext anzeigen:</td>
      <td id=\"headline\">
      <select name=\"show_password\">
         <option value=\"N\"".$nein_selected.">Nein</option>
         <option value=\"Y\"".$ja_selected.">Ja</option>
      </select>
      </td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"commet\" style=\"text-align:left; height: 25px;\">Legen Sie fest, ob Passw&ouml;rter entweder im Klartext oder maskiert angezeigt werden sollen.</td>
   </tr>
   <tr>
      <td colspan=\"2\">&nbsp;</td>
   </tr>
   <tr>
      <td id=\"headline\" style=\"width:50%;\">Accesslog-Einstellungen:</td>
      <td id=\"headline\">
      <select name=\"accesslog\">
         <option value=\"voll\"".$voll_selected.">komplett</option>
         <option value=\"kurz\"".$kurz_selected.">verk&uuml;rzt</option>
         <option value=\"ohneip\"".$ohneip_selected.">ohne IP</option>
         <option value=\"keine\"".$keine_selected.">keine</option>
      </select>
      </td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"commet\" style=\"text-align:left; height: 25px;\">Treffen Sie hier Einstellungen, in welchem Umfang die t&auml;glichen Access-Logfiles erzeugt werden sollen.</td>
   </tr>
   <tr>
      <td colspan=\"2\">&nbsp;</td>
   </tr>
   <tr>
      <td colspan=\"2\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"speichern\"><input id=\"button\" name=\"reset\" type=\"reset\" value=\"zur&uuml;cksetzen\"></td>
   </tr>
</table>
</form>
";
return $ausgabe;
}
?>