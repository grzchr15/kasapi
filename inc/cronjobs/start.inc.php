<?php
// KAS-Abfrage durchfuehren - alle E-Mailaccounts des aktuellen Logins holen
$return = kas_action("kas_action=get_cronjobs");

// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; Cronjobs &Uuml;bersicht</h2>

".$statusmeldung."

<p style=\"text-align:right;\"><a href=\"?category=cronjobs&action=add\">neuen Cronjob anlegen</a></p>
<br />
<p>gefundene Cronjobs: ".sizeof($return['returninfo'])."</p>";

// Wurde mindestens ein Ergebnis der Abfrage zurueckgegeben, werden diese ausgegeben
if (sizeof($return['returninfo']) > 0) {

   $ausgabe .= "
   <br />
   
   <table cellspacing=\"0\">
   <tr>
      <td id=\"headline\">Cronjob</td>
      <td id=\"headline\">Art</td>
      <td id=\"headline\">Zeit</td>
      <td id=\"headline\" style=\"width:130px;\">Aktion</td>
   </tr>
   <tr>
      <td colspan=\"4\" id=\"zwischenzeile\"></td>
   </tr>";
   
   // Ergebnis-Array der KAS-Abfrage auswerten und ausgeben
   if (is_array($return['returninfo'])) {
      ksort($return['returninfo']);
      foreach ($return['returninfo'] as $key => $val) {
         
         
         ## herausfinden der "Art"... also stündlich, wöchentlich etc			
			// wöchentlich
			if($val['day_of_week'] != '*'){
				$art = 'w&ouml;chentl.';				
				$zeit = $wochentage[$val['day_of_week']]." um ".substr("00".$val['hour'],-2).":".substr("00".$val['minute'],-2)." Uhr";
			}
			// monatlich
			elseif($val['day_of_month'] != '*'){
				$art = 'monatl.';
				$zeit = "am ".$val['day_of_month'].". Tag um ".substr("00".$val['hour'],-2).":".substr("00".$val['minute'],-2)." Uhr";
			}
			// täglich
			elseif($val['hour'] != '*'){
				$art = 't&auml;gl.';
				$zeit = "um ".substr("00".$val['hour'],-2).":".substr("00".$val['minute'],-2)." Uhr";
			}
			// stündlich
			else{
				$art = 'st&uuml;ndl.';
				$zeit = "zur ".substr("00".$val['minute'],-2)." Minute";
			}
			
         
         $ausgabe .= "
         <tr id=\"daten\"".($val['is_active'] != 'Y' ? ' style="color:#bbbbbb;"' : '').">
            <td><span title=\"http://".$val['http_url']."\">".str_kuerzen($val['cronjob_comment'],30)."</span></td>
            <td>".$art."</td>
            <td>".$zeit."</td>
            <td>";
            
         // pruefen, ob ein Element noch in Bearbeutung ist, weil die erst kuerzlich
         // erstellt bzw. geupdated wurde
         if (isset($val['in_progress']) && $val['in_progress'] == "TRUE")
         {         
            $ausgabe .= "in Bearbeitung...";
         }
         else
         {         
            $ausgabe .= "<a href=\"?category=cronjobs&action=edit&cronjob_id=".$val['cronjob_id']."\">bearbeiten</a> | <a href=\"?category=cronjobs&action=delete&cronjob_id=".$val['cronjob_id']."&comment=".base64_encode($val['cronjob_comment'])."\">l&ouml;schen</a>";
         }
         
         $ausgabe .= "</td>
         </tr>
         ";      
      }
   }
   $ausgabe .= "
   </table>";
   }
?>