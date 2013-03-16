<?php
// wurde der "LOGOUT-Button" betätigt
if (isset($_POST['submit']) && $_POST['submit'] == "Logout") {  
   // Loginformular aufrufen
   include_once $includedir . 'inc/login.inc.php';   
} else {
// Ausgabe Seitenueberschrift
$ausgabe = "<h2>&raquo; Logout</h2>
<form name=\"logout\" method=\"post\" action=\"?category=logout\">
<table>
   <tr>
      <td style=\"text-align:center;vertical-align:middle;height:100px;\"><b>M&ouml;chten Sie sich wirklich aus dem System ausloggen?</b></td>
   </tr>
    <tr>
      <td id=\"button\"><input type=\"submit\" name=\"submit\" value=\"Logout\"></td>
   </tr>
</table>
</form>
";
}
?>