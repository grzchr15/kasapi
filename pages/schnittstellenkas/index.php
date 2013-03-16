<?php
// Session starten
session_start();

// das Verzeichnis festlegen, in welchem sich das 'inc'-Verzeichnis befindet
// Hinweis: Dieses muss sich ausserhalb des DOCUMENT-ROOT der Domain / Subdomain befinden!
// Angabe mit endenden Slash (das inc-Verzeichnis selbst nicht mit angeben!)
$includedir = dirname( __FILE__ ) . '/../../';

// Globale Parameter und Funktionen einbinden
require_once($includedir . 'inc/config.inc.php');
require_once($includedir . 'inc/functions.inc.php');

// Login
$return_login=array(
	);
$return_login['returnstring']=null;
$return_login['returninfo']=null;
$ausgabe="";
echo "<pre>";
print_r($_SESSION);
print_r($_POST);
echo "</pre>";
if ( 	isset($_POST['submit']) && 
		isset($_POST['kas_login']) && 
		isset($_POST['kas_passwort']) && 
		($_POST['submit'] == "Login"))
	{   
		$login = $_POST['kas_login'];
		$pass = sha1($_POST['kas_passwort']);
		
		if(!isset($_SESSION['kas_login']) && !isset($_SESSION['kas_passwort'])){
			$return_login = kas_action("kas_action=get_accountressources",$login,$pass);
		
		   // Login erfolgreich
		   if ($return_login['returnstring'] == "TRUE") {   
			  $statusmeldung_login = "<div id=\"success\">Login erfolgreich.</div>";
		   
			  // speichern der Logindaten in dir Session
			  $_SESSION['kas_login'] = $login;
			  $_SESSION['kas_passwort'] = $pass;
				 
			  $return_accountsettings = kas_action("kas_action=get_accountsettings");
			   
			  $_SESSION[$login]['logintime'] = mktime();
			  $_SESSION[$login]['show_password'] = $return_accountsettings['returninfo']['settings']['show_password'];
			  $_SESSION[$login]['logging'] = $return_accountsettings['returninfo']['settings']['logging'];
			  if(isset($return_accountsettings['returninfo']['settings']['statistic_language'])){
				$_SESSION[$login]['statistic_language'] = $return_accountsettings['returninfo']['settings']['statistic_language'];
			  }else{
				$_SESSION[$login]['statistic_language'] = "de";
			  }
			  if(isset($return_accountsettings['returninfo']['settings']['statistic_version'])){
				$_SESSION[$login]['statistic_version'] = $return_accountsettings['returninfo']['settings']['statistic_version'];
			  }else{
				$_SESSION[$login]['statistic_version'] = "???"; //TODO statistic_version options
			  }
			  
			  $_SESSION[$login]['dns_settings'] = $return_accountsettings['returninfo']['settings']['dns_settings'];
		   }
		}
}

// Logout
if (isset($_POST['submit']) && 
	isset($_GET['category']) && 
	($_POST['submit'] == "Logout") && 
	($_GET['category'] == 'logout')) {
   // globale Sessiondaten löschen
   $_SESSION = array();
   $_GET = array();
   $_POST = array();
}

// wenn ein Layout userseits gewählt wurde, wird dieses verwendet
if (isset($_GET['layout'])) {
	$layout_path=dirname( __FILE__ ) . '/css/'. $_GET['layout'];
	if (file_exists($layout_path)){
		$_SESSION['layout'] = $_GET['layout'];
	}else{
		$_SESSION['layout']=$default_layout;
	}
}

// Werte im POST-Array trimmen
foreach ($_POST as $key => $val)
{
   $_POST[$key] = trim($val);
}

// Werte im GET-Array trimmen
foreach ($_GET as $key => $val)
{
   $_GET[$key] = trim($val);
}

// wurde kein Layout explizit angegeben, wird das Standardlayout verwendet
!isset($_SESSION['layout']) ? $layout = $default_layout : $layout = $_SESSION['layout'];

// HTML-Header erstellen
$html_header = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
<meta http-equiv=\"expires\" content=\"0\">
<meta http-equiv=\"Pragma\" content=\"no-cache\">
<title>KundenAdministrationsSystem</title>
<link href=\"css/".$layout."/layout.css\" rel=\"stylesheet\" type=\"text/css\" />
</head>
";

// HTML-Body erstellen
$html_body = "
<body>
   <!-- BEGINN gesamter Anzeigebereich //-->
   <table id=\"screen\">
      <tr>
      <td align=\"center\" valign=\"middle\">
   
      <!-- BEGINN Inhalt //-->
      <div id=\"content\">
      
         <!-- BEGINN Kopfteil //-->
         <div id=\"head\">
            <h1>Schnittstellen - Demo</h1><br />
            <h4>Status: ";

// im eingeloggten Zustand wird der Benutzername im Kopfbereich angezeigt            
if (isset($_SESSION['kas_login']) && $_SESSION['kas_passwort']) {
   $html_body .= "eingeloggt als <i>".$_SESSION['kas_login']."</i>";
}
// im nicht eingeloggten Zustand erscheint eine entsprechende meldung im Kopfbereich
else {
   $html_body .= "nicht eingeloggt!";
}

$html_body .= "</h4>            
         </div>
         <!-- ENDE Kopfteil //-->         
         <!-- BEGINN Menue //-->
         <div id=\"menue\">";
 
// im eingeloggten Zustand wird das Menue links angezeigt         
if (isset($_SESSION['kas_login']) && isset($_SESSION['kas_passwort'])) {
$html_body .= "
         <a href=\"?category=uebersicht\"><p>&Uuml;bersicht</p></a>
         <a href=\"?category=accounts\"><p>Accounts</p></a>
         <a href=\"?category=domains\"><p>Domains</p></a>
         <a href=\"?category=subdomains\"><p>Subdomains</p></a>
         <a href=\"?category=datenbanken\"><p>Datenbanken</p></a>
         <a href=\"?category=ftpuser\"><p>FTP-User</p></a>
         <a href=\"?category=emailaccounts\"><p>E-Mail-Accounts</p></a>
         <a href=\"?category=emailweiterleitungen\"><p>E-Mail-Forwards</p></a>
         <a href=\"?category=mailinglisten\"><p>Mailinglisten</p></a>
         <a href=\"?category=cronjobs\"><p>Cronjobs</p></a>
         <a href=\"?category=verzeichnisschutz\"><p>Verzeichnisschutz</p></a>
         <a href=\"?category=speicherbelegung\"><p>Speicherbelegung</p></a>
         <a href=\"?category=einstellungen\"><p>Einstellungen</p></a>
         <a href=\"?category=passwort\"><p>Passwort &auml;ndern</p></a>
         <br /><br />\n<a href=\"?category=logout\"><p>Logout</p></a>\n";
} else {
$html_body .= "\n<a href=\"index.php\"><p>Login</p></a>\n";   
}

$html_body .="
         </div>
         <!-- ENDE Menue //-->
         <!-- BEGINN Inhalt //-->
         <div id=\"main\">";
         
         // Prüfen, ob sich das 'inc' - Verzeichnis wirklich ausserhalb des Document-Roots befindet
         if (strstr("/".trim($includedir, "/")."/", "/".trim($_SERVER['DOCUMENT_ROOT'],"/")."/"))
         {
               // Fehlermeldung, wenn sich das Verzeichnis 'inc' per HTTP errecihbar ist
               $html_body .= "
               <div id=\"error\">
               <h2>Unzureichende Sicherheiteinstellungen.</h2>
               <h4>Platzieren Sie das Verzeichnis 'inc' unbedingt ausserhalb des Document-Roots.</h4>
               <h4>In einem Verzeichnis also, welches nicht per HTTP / Domainaufruf erreichbar ist.</h4>
               <h4>Sollte Ihr AUTOKAS direkt &uuml;ber eine Domain bzw. Subdomain erreichbar sein gen&uuml;gt es, das Verzeichnis 'inc' eine Hierarchieebene &uuml;ber der 'index.php' zu platzieren.</h4>
               <h4>Routen Sie andernfalls Ihre Domain / Subdomain direkt auf das Verzeichnis, in welchem sich Ihre AUTOKAS-Installation mit der 'index.php' befindet und platzieren Sie das 'inc'-Verzeichnis ausserhalb dieses AUTOKAS-Installationsordners.</h4>
               <h4>Wird die Verzeichnisstruktur nicht genau so eingehalten, wird AUTOKAS nicht lauff&auml;hig sein.</h4>
               </div>";
         }
         else
         {      
            if (isset($_GET['category']) && isset($_SESSION['kas_login']) )
            {            
               // zu includende Dateien festlegen
               if (isset($_GET['action']))
               {
                  $include_path = $includedir . "inc/" . $_GET['category'] . "/" . $_GET['action'] . ".inc.php";
               }
               else
               {
                  $include_path = $includedir . "inc/" . $_GET['category'] . "/start.inc.php";
               }            
            }
            else
            {
               $include_path = $includedir . "inc/login.inc.php";
            }
            
            // wenn die zu inkludierende Datei exisitiert, wird diese auch eingebunden
            if (file_exists($include_path))
            {
               $statusmeldung = NULL;
               include($include_path);
               $html_body .= $ausgabe;
            }
            else
            {
               // Wenn angeforderte Seite nicht verfügbar ist, entsprechenden Fehler ausgeben
               header("HTTP/1.0 404 Not Found");
               $html_body .= "
               <div id=\"error\">
               <h2>Fehler 404<h2>
               <h4>Die angeforderte Seite ist nicht verf&uuml;gbar.</h4>
               </div>";
            }           
         }
         
$html_body .= "
         </div>
         <!-- ENDE Inhalt//-->         
         <div id=\"clearer\"></div>         
      </div>
      <!-- ENDE Inhalt //-->";
      // wenn in der inc/confic.inc.php angegeben wurde, dass der
      // Layoutschalter angezeigt werden soll, so wird das hier getan
      if ($layoutschalter) {
	  if(isset($_GET['category'])){
		$category=$_GET['category'];
		}else{
			$category=null;
		}
$html_body .= "         
      <br />
      <!-- BEGINN LAYOUTSCHALTER //-->
      <form name=\"layoutschalter\" method=\"post\" action=\"?category=".$category."\">
      Layout:&nbsp;
      <select name=\"layout\" onChange=\"location.href='?category=".$category."&layout='+this.value\">";
      // verfügbare Layouts im Layout-Verzeichnis auslesen und zur Auswahl anbieten
      if ($handle = opendir(substr($_SERVER['SCRIPT_FILENAME'],0,-strlen('index.php'))."css/")) {
         while ($file = readdir($handle)) {
            if ($file != "." && $file != "..") {
               $file == $layout ? $selected = " selected" : $selected = "";
               $html_body .= "\n<option value=\"".$file."\"".$selected.">".$file."</option>";
            }
         }
      }
$html_body .= "
      </select>
      <!-- ENDE LAYOUTSCHALTER //-->";
      }
$html_body .= "
      </td>
      </tr>
   </table>
   <!-- ENDE gesamter Anzeigebereich //-->
</body>
</html>
";

// Ausgabe der fertigen HTML-Seite
echo $html_header . $html_body;
?>