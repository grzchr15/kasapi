<?php
// Bild erzeugen
$image = ImageCreate(310, 80);

// Farben setzen
$white    = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
$gray     = imagecolorallocate($image, 0xEA, 0xEA, 0xEA);
$darkgray = imagecolorallocate($image, 0xDA, 0xDA, 0xDC);
$gray2    = imagecolorallocate($image, 0xCA, 0xCA, 0xCC);
$darkgray2= imagecolorallocate($image, 0xB5, 0xB5, 0xB7);
$red      = imagecolorallocate($image, 0x99, 0x99, 0x99);
$darkred  = imagecolorallocate($image, 0x66, 0x66, 0x66);

// Verteilungsdes belegten Speicherplatzes
if ($_GET['gesamtbelegt']) {

     $gesamtbelegt = trim($_GET['gesamtbelegt']);
     $htdocs = trim($_GET['htdocs']);
     $email = trim($_GET['email']);
     $mysql = trim($_GET['mysql']);  
    
     // Prozentangaben berechnen
     $prozent_htdocs = round($htdocs / $gesamtbelegt * 100, 1);
     $prozent_email = round($email / $gesamtbelegt * 100, 1);
     $prozent_mysql =  round($mysql / $gesamtbelegt * 100, 1);
     
     // Winkel berechnen
     $angel_htdocs = 360 * $htdocs / $gesamtbelegt;
     $angel_email = $angel_htdocs + (360 * $email / $gesamtbelegt);
     $angel_mysql = $angel_email + (360 * $mysql / $gesamtbelegt);
     
   
     // 3D-Kuchen-Effekt
     for ($i = 49; $i > 29; $i--) {
          imagefilledarc($image, 100, $i, 200, 60, 360, $angel_htdocs, $darkgray2, IMG_ARC_PIE);
          imagefilledarc($image, 100, $i, 200, 60, $angel_htdocs, $angel_email, $darkred, IMG_ARC_PIE);
          imagefilledarc($image, 100, $i, 200, 60, $angel_email, $angel_mysql, $darkgray, IMG_ARC_PIE);
     }
     
     // 2D-Oberfläche
     imagefilledarc($image, 100, 29, 200, 60, 360, $angel_htdocs, $gray2, IMG_ARC_PIE);
     imagefilledarc($image, 100, 29, 200, 60, $angel_htdocs, $angel_email, $red, IMG_ARC_PIE);
     imagefilledarc($image, 100, 29, 200, 60, $angel_email, $angel_mysql, $gray, IMG_ARC_PIE);
     
     
     // Legende - htdocs
     imagefilledrectangle($image,210,30,220,40,$darkgray2);
     imagestring($image,2,230,29,$prozent_htdocs . " % htdocs",$darkred);
     
     // Legende - email
     imagefilledrectangle($image,210,45,220,55,$darkred);
     imagestring($image,2,230,44,$prozent_email . " % E-Mail",$darkred);
     
     // Legende - mysql
     imagefilledrectangle($image,210,60,220,70,$darkgray);
     imagestring($image,2,230,59,$prozent_mysql . " % MySQL",$darkred);  

}

// Gesamtspeicherplatzbelegung
else {
     
     $max = trim($_GET['max']);
     $belegt = trim($_GET['belegt']);
     
     $angel_belegt = (360 * $belegt) / $max;
     
     // Prozentangaben berechnen
     $prozent_belegt = round($belegt / $max * 100, 1);
     $prozent_frei = round(($max - $belegt) / $max * 100, 1);
     
     // 3D-Kuchen-Effekt
     for ($i = 49; $i > 29; $i--) {
          imagefilledarc($image, 100, $i, 200, 60, $angel_belegt, 360 , $darkgray, IMG_ARC_PIE);
          imagefilledarc($image, 100, $i, 200, 60, 0, $angel_belegt , $darkred, IMG_ARC_PIE);
     }
     
     // 2D-Oberfläche
     imagefilledarc($image, 100, 29, 200, 60, 0, $angel_belegt , $red, IMG_ARC_PIE);
     imagefilledarc($image, 100, 29, 200, 60, $angel_belegt, 360 , $gray, IMG_ARC_PIE);
     
     // Legende - frei
     imagefilledrectangle($image,210,45,220,55,$darkgray);
     imagestring($image,2,230,44,$prozent_frei . " % frei",$darkred);
     
     // Legende - belegt
     imagefilledrectangle($image,210,60,220,70,$darkred);
     imagestring($image,2,230,59,$prozent_belegt . " % belegt",$darkred);  
     
}

// Hintergrund (weiss) transparent
imagecolortransparent($image, $white);

// Bild ausgeben
header("Expires: Sun, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: maxage=1");
header("Content-Transfer-Encoding:­ binary"); 
header("Content-type: image/gif");
imagegif($image);
imagedestroy($image);
?>
