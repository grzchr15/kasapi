<?php
// Ressourcen des aktuellen Logins holen
$return = kas_action("kas_action=get_accountressources");

// Das Formular wird das erste mal aufgerufen
if (!$_POST['submit'] && $return['returninfo']['max_cronjobs']['free'] != 0)
{
   $ausgabe = eingabeformular();
}
// Es sind im aktuellen Account keine Ressourcen mehr frei
elseif ($return['returninfo']['max_cronjobs']['free'] == 0)
{
   $statusmeldung = "<div id=\"error\">Fehler: Es k&ouml;nnen keine Cronjobs mehr angelegt werden.</div>";
   include_once $includedir . 'inc/cronjobs/start.inc.php';
}
// Eingaben wurden getätigt
else
{
   unset($return);
   
   // optionale Parameter
   $optional = "";
   if ($_POST['benutzer'] != "") { $optional .= "&http_user=" . $_POST['benutzer']; }
   if ($_POST['passwort'] != "") { $optional .= "&http_password=" . $_POST['passwort']; }
   if ($_POST['email'] != "") { $optional .= "&mail_adress=" . $_POST['email']; }
   
   // zur Übersicht tragen wir die Script-URL in den Kommentar ein, falls dieser vom User leer gelassen wird
   $optional .= "&cronjob_comment=" . ($_POST['kommentar'] == "" ? $_POST['url'] : urlencode($_POST['kommentar']));
   
   // Zeiten in Cron-Format wandeln
   $minute = $_POST["minute_".$_POST['art']];
	$stunde = $_POST["stunde_".$_POST['art']];
	$tag = $_POST["tag_".$_POST['art']];
	$monat = $_POST["monat_".$_POST['art']];
	$wochentag = $_POST["wochentag_".$_POST['art']];
	 
	if(!is_numeric($stunde)) { $stunde = "*"; }
	if(!is_numeric($tag)) { $tag = "*"; }
	if(!is_numeric($monat)) { $monat = "*"; }
	if(!is_numeric($wochentag)) { $wochentag = "*"; }
   
   // Cronjob wird angelegt
   $return = kas_action("kas_action=add_cronjob&http_url=".$_POST['url']."&is_active=".$_POST['aktiv']."&minute=".$minute."&hour=".$stunde."&day_of_month=".$tag."&day_of_week=".$wochentag."&month=".$monat.$optional);
   
   // Cronjob wurde erfolgreich angelegt  
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = "<div id=\"success\">Der Cronjob wurde erstellt.</div>";
      include_once $includedir . 'inc/cronjobs/start.inc.php';
   }
   
   // Cronjob wurde nicht erfolgreich angelegt 
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular ($statusmeldung);
   }
}


###################### Unterfunktionen #######################

// Eingabformular
function eingabeformular ($statusmeldung=false) {
  
$ausgabe = "<h2>&raquo; Cronjob anlegen</h2>
".$statusmeldung."
<br />
<form name=\"add_cronjob\" method=\"post\" action=\"?category=cronjobs&action=add\">
<table>
   <tr>
      <td id=\"headline\" colspan=\"4\"><b>Cronjob anlegen</b></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Url zum Script <b>(ohne http://)</b>:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"url\" type=\"text\" value=\"".$_POST['url']."\" size=\"40\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Kommentar / Beschreibung:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"kommentar\" type=\"text\" value=\"".$_POST['kommentar']."\" size=\"40\" maxlength=\"50\" /></td>
   </tr>
   <tr>
      <td colspan=\"4\"><br /><br /></td>
   </tr>
   <tr>
      <td id=\"headline\" colspan=\"4\"><b>Zeitpunkt festlegen</b></td>
   </tr>
   <tr>
      <td align=\"left\"><b>Art</b></td>
      <td></td>
      <td align=\"center\"><b>Stunde</b></td>
      <td align=\"center\"><b>Minute</b></td>
   </tr>
   <tr>
      <td align=\"left\"><input type=\"radio\" name=\"art\" value=\"monatlich\"".(!$_POST || $_POST['art'] == 'monatlich' ? ' checked' : '')." />&nbsp;monatlich</td>
      <td>
         <div style=\"padding-left:20px;\">
         <select name=\"tag_monatlich\" onchange=\"document.add_cronjob.art[0].checked=true;\">";
         for ($i=1; $i<=31; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($_POST['tag_monatlich'] == $i ? ' selected' : '').">zum ".$i.".</option>";
         }  
$ausgabe .="
         </select>
      </div>
      </td>
      <td align=\"center\">
         <select name=\"stunde_monatlich\" onchange=\"document.add_cronjob.art[0].checked=true;\">";
         for ($i=0; $i<=23; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($_POST['stunde_monatlich'] == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
      <td align=\"center\">
         <select name=\"minute_monatlich\" onchange=\"document.add_cronjob.art[0].checked=true;\">";
         for ($i=0; $i<=59; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($_POST['minute_monatlich'] == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
   </tr>
   <tr>
      <td align=\"left\"><input type=\"radio\" name=\"art\" value=\"woechentlich\"".($_POST['art'] == 'woechentlich' ? ' checked' : '')." />&nbsp;w&ouml;chentlich</td>
      <td>
      <div style=\"padding-left:20px;\">
         <select name=\"wochentag_woechentlich\" onchange=\"document.add_cronjob.art[1].checked=true;\">
            <option value=\"0\"".($_POST['wochentag_woechentlich'] == '0' ? ' selected' : '').">sonntags</option>
            <option value=\"1\"".($_POST['wochentag_woechentlich'] == '1' ? ' selected' : '').">montags</option>
            <option value=\"2\"".($_POST['wochentag_woechentlich'] == '2' ? ' selected' : '').">dienstags</option>
            <option value=\"3\"".($_POST['wochentag_woechentlich'] == '3' ? ' selected' : '').">mittwochs</option>
            <option value=\"4\"".($_POST['wochentag_woechentlich'] == '4' ? ' selected' : '').">donnerstags</option>
            <option value=\"5\"".($_POST['wochentag_woechentlich'] == '5' ? ' selected' : '').">freitags</option>
            <option value=\"6\"".($_POST['wochentag_woechentlich'] == '6' ? ' selected' : '').">samstags</option>
         </select>
      </div>
      </td>
      <td align=\"center\">
         <select name=\"stunde_woechentlich\" onchange=\"document.add_cronjob.art[1].checked=true;\">";
         for ($i=0; $i<=23; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($_POST['stunde_woechentlich'] == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
      <td align=\"center\">
         <select name=\"minute_woechentlich\" onchange=\"document.add_cronjob.art[1].checked=true;\">";
         for ($i=0; $i<=59; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($_POST['minute_woechentlich'] == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
   </tr>
   <tr>
      <td align=\"left\"><input type=\"radio\" name=\"art\" value=\"taeglich\"".($_POST['art'] == 'taeglich' ? ' checked' : '')." />&nbsp;t&auml;glich</td>
      <td></td>
      <td align=\"center\">
         <select name=\"stunde_taeglich\" onchange=\"document.add_cronjob.art[2].checked=true;\">";
         for ($i=0; $i<=23; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($_POST['stunde_taeglich'] == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
      <td align=\"center\">
         <select name=\"minute_taeglich\" onchange=\"document.add_cronjob.art[2].checked=true;\">";
         for ($i=0; $i<=59; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($_POST['minute_taeglich'] == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
   </tr>
   <tr>
      <td align=\"left\"><input type=\"radio\" name=\"art\" value=\"stuendlich\"".($_POST['art'] == 'stuendlich' ? ' checked' : '')." />&nbsp;st&uuml;ndlich</td>
      <td></td>
      <td></td>
      <td align=\"center\">
         <select name=\"minute_stuendlich\" onchange=\"document.add_cronjob.art[3].checked=true;\">";
         for ($i=0; $i<=59; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($_POST['minute_stuendlich'] == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
   </tr>
   <tr>
      <td colspan=\"4\"><br /><br /></td>
   </tr>
   <tr>
      <td id=\"headline\" colspan=\"4\"><b>weitere Einstellungen (optional)</b></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Benutzer*:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"benutzer\" type=\"text\" value=\"".$_POST['benutzer']."\" size=\"25\" maxlength=\"25\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Passwort*:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"passwort\" type=\"text\" value=\"".$_POST['passwort']."\" size=\"25\" maxlength=\"25\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Best&auml;tigung E-Mailadresse:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"email\" type=\"text\" value=\"".$_POST['email']."\" size=\"25\" maxlength=\"25\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Cronjob aktiv:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"aktiv\" type=\"radio\" value=\"Y\"".($_POST['aktiv'] == 'N' ? '' : ' checked')." /> Ja&nbsp;&nbsp;<input name=\"aktiv\" type=\"radio\" value=\"N\"".($_POST['aktiv'] == 'N' ? ' checked' : '')." /> Nein</td>
   </tr>
   <tr>
      <td colspan=\"4\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"4\" id=\"commet\" style=\"text-align:center;\">* Benutzername und Passwort sind nur einzutragen, sollte das auszuf&uuml;hrende Script in einem durch Verzeichnisschutz (.htaccess) gesch&uuml;tzten Verzeichnis liegen.</td>
   </tr>
   <tr>
      <td colspan=\"4\" height=\"20\"></td>
   </tr>
   <tr>
      <td colspan=\"4\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Cronjob anlegen\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" /></td>
   </tr>
   <tr>
      <td colspan=\"4\" style=\"text-align:center;\"><a href=\"?category=cronjobs\" target=\"_self\">abbrechen</a></td>
   </tr>
</table>
</form>
";
return $ausgabe;
}
?>