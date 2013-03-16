<?php

/*
 * Funktion zum Kürzen zu langer Zeichenketten
 */
function str_kuerzen($str,$maxlen) {
   if (strlen($str) > $maxlen) {
      $str = substr($str,0,$maxlen-3)."...";
   }
   return $str;
}

/*
 * Funktion um KAS-Aktionen auszuführen. Werden 2 Actions zu schnell hintereinander
 * ausgeführt, wird der Aufruf etwas verzögert, da sonst die flood_protection greift.
 */  
function kas_action($kas_actionquery,$login=false,$pass=false) {
     
   if ( $login === false && $pass === false ) {
     $login = $_SESSION['kas_login'];
     $pass = $_SESSION['kas_passwort'];
   }   
   
   // Floodschutz durch Sessionhandling
   list($action) = explode("&",$kas_actionquery,2);
   $action = substr($action,11);
   //echo "kas_action=".$action."<br/>";
   //echo " $_SESSION=". print_r($_SESSION,1)."<br/>";
   
   if(isset($_SESSION[$login]['flood_protection'][$action])){
		$time_to_wait = $_SESSION[$login]['flood_protection'][$action] - mktime();
	}else{
		$time_to_wait = 0;
	}
   if( $time_to_wait >= 0 ) {
      usleep(intval($time_to_wait*1000000));
   }
   
   $url=KAS_SCHNITTSTELLE . "?kas_login=" . $login . "&kas_password=" . $pass . "&" . $kas_actionquery;
   
   // Ausführen des Schnittstellenqueries
   if (ini_get('allow_url_include')) {
		
		error_log("kas_action calling include_once=".$url);
      include_once $url;
   }
   else {
		error_log("kas_action calling implode(\"\", file(url))=".$url);
		$kas_action_str="?>" . implode("", file($url)) . "<?";
		error_log("kas_action calling eval(kas_action_str=".$kas_action_str);
      @eval($kas_action_str);
   }
   
   // Speichern des Timestamps für den Floodschutz: +0,1 Sekunden, zur Sicherheit
   $_SESSION[$login]['flood_protection'][$action] = mktime() + $kas_flood_delay + 0.1;
   
   return array('returnstring' => $returnstring, 'returninfo' => $returninfo);
}

/*
 * Zeigt entweder das Passwort im Klartext oder maskiert an, je nach Benutzereinstellung
 */
function passwords($pass) {
   if ($_SESSION[$_SESSION['kas_login']]['show_password'] == 'Y') {
      return $pass;
   } else {
      $sterne = '';
      for ($i=0;$i<strlen($pass);$i++) {
         $sterne .= '*';
      }
      return $sterne;
   }
}

/*
 * Zeigt Passworteingaben entweder im Klartext oder maskiert an, je nach Benutzereinstellung
 */
function pass_inputtype() {
   $_SESSION[$_SESSION['kas_login']]['show_password'] == 'Y' ? $return = 'text' : $return = 'password';
   return $return;
}

/*
 * überprüft einen Wert, ob er eine positive Ganzzahl ist
 */
function pos_ganzzahl($wert) {
   if (is_numeric($wert) && $wert >=0) {
      return true;
   } else {
      return false;
   }
}
?>