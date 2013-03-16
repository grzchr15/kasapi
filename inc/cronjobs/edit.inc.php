<?php
// Formular wird zum ersten mal aufgerufen
if (!$_POST['submit'])
{
   $ausgabe = eingabeformular();
}
// Eingaben wurden getätigt und das Eingabeformular abgesendet
else
{
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
   
   // Speichern der Änderungen
   $return = kas_action("kas_action=update_cronjob&cronjob_id=".$_GET['cronjob_id']."&http_url=".$_POST['url']."&is_active=".$_POST['aktiv']."&minute=".$minute."&hour=".$stunde."&day_of_month=".$tag."&day_of_week=".$wochentag."&month=".$monat.$optional);
   
   // Änderungen wurden erfolgreich gespeichert
   if ($return['returnstring'] == "TRUE")
   {
     $statusmeldung = "<div id=\"success\">Die &Auml;nderungen wurden &uuml;bernommen.</div>";
     include_once $includedir . 'inc/cronjobs/start.inc.php';
   }
   // Änderungen konnten nicht gespeichert werden
   else
   {
      // Fehlermeldungen einbinden
     include_once $includedir . 'inc/errors.inc.php';
     $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
     $ausgabe = eingabeformular($statusmeldung);
   }

}


##################################### UNTERFUNKTIONEN ################

// Eingabeformular
function eingabeformular($statusmeldung=false)
{

   if (!$_POST['submit']) {
      // Daten des betreffenden Cronjobs holen
      $return = kas_action("kas_action=get_cronjobs&cronjob_id=".$_GET['cronjob_id']);
         
      if (is_array($return['returninfo'])) {
         foreach ($return['returninfo'] AS $key => $val) {
            if ($val['cronjob_id'] == $_GET['cronjob_id']) {
               $url = $val['http_url'];
               $kommentar = $val['cronjob_comment'];
               $benutzer = $val['http_user'];
               $passwort = $val['http_password'];
               $email = $val['mail_adress'];
               $aktiv = $val['is_active'];
               	
               if($val['day_of_week'] != '*'){
                  $art = "woechentlich";
                  ${wochentag_.$art} = $val['day_of_week'];
               }
               elseif($val['day_of_month'] != '*'){
                  $art = "monatlich";
                  ${tag_.$art} = $val['day_of_month'];
               }
               elseif($val['hour'] != '*'){
                  $art = "taeglich";
               }
               else{
                  $art = "stuendlich";
               }
               
               ${minute_.$art} = $val['minute'];
               ${stunde_.$art} = $val['hour'];
            }
         }
      }
   } else {
      $url = $_POST['url'];
      $kommentar = $_POST['kommentar'];
      $benutzer = $_POST['benutzer'];
      $passwort = $_POST['passwort'];
      $email = $_POST['email'];
      $aktiv = $_POST['aktiv'];
      $art = $_POST['art'];
      ${minute_.$art} = $_POST['minute_'.$art];
      ${stunde_.$art} = $_POST['stunde_'.$art];
      ${wochentag_.$art} = $_POST['wochentag_'.$art];
      ${tag_.$art} = $_POST['tag_'.$art];
   }
      

// Ausgabe zusammenbauen
$ausgabe = "<h2>&raquo; Cronjob bearbeiten</h2>
".$statusmeldung."
<br />
<form name=\"edit_cronjob\" method=\"post\" action=\"?category=cronjobs&action=edit&cronjob_id=".$_GET['cronjob_id']."\">
<table>
   <tr>
      <td id=\"headline\" colspan=\"4\"><b>Cronjob anlegen</b></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Url zum Script <b>(ohne http://)</b>:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"url\" type=\"text\" value=\"".$url."\" size=\"40\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Kommentar / Beschreibung:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"kommentar\" type=\"text\" value=\"".$kommentar."\" size=\"40\" maxlength=\"50\" /></td>
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
      <td align=\"left\"><input type=\"radio\" name=\"art\" value=\"monatlich\"".($art == 'monatlich' ? ' checked' : '')." />&nbsp;monatlich</td>
      <td>
         <div style=\"padding-left:20px;\">
         <select name=\"tag_monatlich\" onchange=\"document.edit_cronjob.art[0].checked=true;\">";
         for ($i=1; $i<=31; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($tag_monatlich == $i ? ' selected' : '').">zum ".$i.".</option>";
         }  
$ausgabe .="
         </select>
      </div>
      </td>
      <td align=\"center\">
         <select name=\"stunde_monatlich\" onchange=\"document.edit_cronjob.art[0].checked=true;\">";
         for ($i=0; $i<=23; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($stunde_monatlich == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
      <td align=\"center\">
         <select name=\"minute_monatlich\" onchange=\"document.edit_cronjob.art[0].checked=true;\">";
         for ($i=0; $i<=59; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($minute_monatlich == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
   </tr>
   <tr>
      <td align=\"left\"><input type=\"radio\" name=\"art\" value=\"woechentlich\"".($art == 'woechentlich' ? ' checked' : '')." />&nbsp;w&ouml;chentlich</td>
      <td>
      <div style=\"padding-left:20px;\">
         <select name=\"wochentag_woechentlich\" onchange=\"document.edit_cronjob.art[1].checked=true;\">
            <option value=\"0\"".($wochentag_woechentlich == '0' ? ' selected' : '').">sonntags</option>
            <option value=\"1\"".($wochentag_woechentlich == '1' ? ' selected' : '').">montags</option>
            <option value=\"2\"".($wochentag_woechentlich == '2' ? ' selected' : '').">dienstags</option>
            <option value=\"3\"".($wochentag_woechentlich == '3' ? ' selected' : '').">mittwochs</option>
            <option value=\"4\"".($wochentag_woechentlich == '4' ? ' selected' : '').">donnerstags</option>
            <option value=\"5\"".($wochentag_woechentlich == '5' ? ' selected' : '').">freitags</option>
            <option value=\"6\"".($wochentag_woechentlich == '6' ? ' selected' : '').">samstags</option>
         </select>
      </div>
      </td>
      <td align=\"center\">
         <select name=\"stunde_woechentlich\" onchange=\"document.edit_cronjob.art[1].checked=true;\">";
         for ($i=0; $i<=23; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($stunde_woechentlich == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
      <td align=\"center\">
         <select name=\"minute_woechentlich\" onchange=\"document.edit_cronjob.art[1].checked=true;\">";
         for ($i=0; $i<=59; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($minute_woechentlich == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
   </tr>
   <tr>
      <td align=\"left\"><input type=\"radio\" name=\"art\" value=\"taeglich\"".($art == 'taeglich' ? ' checked' : '')." />&nbsp;t&auml;glich</td>
      <td></td>
      <td align=\"center\">
         <select name=\"stunde_taeglich\" onchange=\"document.edit_cronjob.art[2].checked=true;\">";
         for ($i=0; $i<=23; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($stunde_taeglich == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
      <td align=\"center\">
         <select name=\"minute_taeglich\" onchange=\"document.edit_cronjob.art[2].checked=true;\">";
         for ($i=0; $i<=59; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($minute_taeglich == $i ? ' selected' : '').">".$i."</option>";
         }  
$ausgabe .="
         </select>
      </td>
   </tr>
   <tr>
      <td align=\"left\"><input type=\"radio\" name=\"art\" value=\"stuendlich\"".($art == 'stuendlich' ? ' checked' : '')." />&nbsp;st&uuml;ndlich</td>
      <td></td>
      <td></td>
      <td align=\"center\">
         <select name=\"minute_stuendlich\" onchange=\"document.edit_cronjob.art[3].checked=true;\">";
         for ($i=0; $i<=59; $i++) {
$ausgabe .="
            <option value=\"".$i."\"".($minute_stuendlich == $i ? ' selected' : '').">".$i."</option>";
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
      <td colspan=\"2\" align=\"left\"><input name=\"benutzer\" type=\"text\" value=\"".$benutzer."\" size=\"25\" maxlength=\"25\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Passwort*:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"passwort\" type=\"text\" value=\"".$passwort."\" size=\"25\" maxlength=\"25\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Best&auml;tigung E-Mailadresse:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"email\" type=\"text\" value=\"".$email."\" size=\"25\" maxlength=\"25\" /></td>
   </tr>
   <tr>
      <td colspan=\"2\" align=\"left\">Cronjob aktiv:</td>
      <td colspan=\"2\" align=\"left\"><input name=\"aktiv\" type=\"radio\" value=\"Y\"".($aktiv == 'N' ? '' : ' checked')." /> Ja&nbsp;&nbsp;<input name=\"aktiv\" type=\"radio\" value=\"N\"".($aktiv == 'N' ? ' checked' : '')." /> Nein</td>
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
      <td colspan=\"4\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Cronjob bearbeiten\"><input type=\"reset\" name=\"reset\" value=\"zur&uuml;cksetzen\" /></td>
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