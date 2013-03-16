<?php
// keine Logindaten in der Session und kein Loginversuch - Formular wurde nicht versendet
if (isset($_POST['submit']) && isset($_POST['kas_login']) && isset($_POST['kas_passwort']) && 
	$_POST['submit'] != "Login" && !$_SESSION['kas_login'] && !$_SESSION['kas_passwort'])
{   
   $ausgabe = eingabeformular();
}
else {
   // $return_login wird in der index.php gesetzt 
   $return = $return_login;
   
   // Login erfolgreich - Paswort und Username existierun und stimmen überein
   if ($return['returnstring'] == "TRUE")
   {
      $statusmeldung = $statusmeldung_login;
      include_once $includedir . 'inc/uebersicht/start.inc.php';
   }
   // Login nicht erfolgreich
   else
   {
      // Fehlermeldungen einbinden
      include_once $includedir . 'inc/errors.inc.php';
      $statusmeldung = "<div id=\"error\">Fehler: " . $return['returnstring'] . "</div>";
      $ausgabe = eingabeformular($statusmeldung,$return['returninfo']);
   }
}



########################### UNTERFUNKTIONEN #######################

// Loginformular
function eingabeformular($statusmeldung=false,$buttoncountdown=-1)
{  
   // Ausgabe zusammenbauen
   $ausgabe = "<h2>&raquo; Login</h2>
   ".$statusmeldung."
   <br />
   <form name=\"login_form\" method=\"post\" action=\"index.php\">
   <table>
      <tr>
         <td>Login:</td>
         <td align=\"center\" ><input name=\"kas_login\" type=\"text\" size=\"25\" value=\"\" /></td>
         <td width=\"30%\"></td>
      </tr>
      <tr>
         <td>Passwort:</td>
         <td align=\"center\" ><input name=\"kas_passwort\" type=\"password\" size=\"25\" value=\"\" /></td>
         <td></td>
      </tr>
      <tr>
         <td></td>
         <td align=\"center\" id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Login\"><input type=\"reset\" name=\"reset\" value=\"Zur&uuml;cksetzen\" ></td>
         <td></td>
      </tr>
   </table>
   </form>
   <script type=\"text/javascript\" language=\"javascript\">
      var zeit = ".intval($buttoncountdown).";
      function buttonsperre() {
          document.login_form.submit.disabled = true
          document.login_form.submit.value = \"Weiter in \" + zeit + \" Sek.\"
          if(zeit > 0) {
            zeit--
            window.setTimeout('buttonsperre()',1000)
          } else {
            document.login_form.submit.disabled = false
            document.login_form.submit.value = \"Login\"
          }
      }
      buttonsperre()      
   </script>
   ";
   return $ausgabe;
}

?>