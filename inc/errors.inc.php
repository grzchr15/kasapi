<?php
// Definition der Fehlermeldungen

switch ($return['returnstring']) {
      
   /* GENERELLE FEHLERMELDUNGEN */
   case "got_no_login_data":                       $return['returnstring'] = "Eingabe unvollst&auml;ndig."; break;
   case "ip_blocked":                              $return['returnstring'] = "Ihre IP ist f&uuml;r ".$return['returninfo']." Sekunden gesperrt!"; break;
   case "missing_parameter":                       $return['returnstring'] = "Eingaben unvollst&auml;ndig. Bitte &uuml;berpr&uuml;fen Sie Ihre Eingaben!"; break;
   case "kas_login_not_found":                     $return['returnstring'] = "KAS Login wurde nicht gefunden.";  break;
   case "kas_password_incorrect":                  $return['returnstring'] = "KAS Passwort ist falsch.";  break;
   case "kas_access_forbidden":                    $return['returnstring'] = "KAS Zugang ist gesperrt.";  break;
   case "kas_password_syntax_incorrect":           $return['returnstring'] = "Syntax des KAS Passswortes stimmt nicht.";  break;
   case "kas_login_syntax_incorrect":              $return['returnstring'] = "Syntax des KAS Logins stimmt nicht.";  break;
   case "password_syntax_incorrect":               $return['returnstring'] = "Passw&ouml;rter m&uuml;ssen aus 6 - 20 (a-zA-Z0-9) Zeichen bestehen."; break;
   case "nothing_to_do":                           $return['returnstring'] = "Es haben keine &Auml;nderungen stattgefunden..";  break;
   
   /* ACCOUNT ANLEGEN UND BEARBEITEN */
   case "account_kas_password_syntax_incorrect":   $return['returnstring'] = "Das Account-Passwort muss aus 6 bis 20 Zeichen bestehen.";  break;
   case "account_ftp_password_syntax_incorrect":   $return['returnstring'] = "Das FTP-Passwort muss aus 6 bis 20 Zeichen bestehen.";  break;
   case "hostname_art_syntax_incorrect":           $return['returnstring'] = "Art des Hostnamens muss 'domain' oder 'subdomain' sein.";  break;
   case "hostname_syntax_incorrect":               $return['returnstring'] = "falsche Hostnamen-Syntax.";  break;
   case "domain_syntax_incorrect":                 $return['returnstring'] = "falsche Domain-Syntax.";  break;
   case "domain_tld_not_allowed":                  $return['returnstring'] = "Diese ToplevelDomain ist nicht zul&aumml;ssig.";  break;
   case "max_account_reached":                     $return['returnstring'] = "Die maximale Anzahl an zu reservierenden Unteraccounts ist erreicht."; break;
   case "max_domain_reached":                      $return['returnstring'] = "Die maximale Anzahl an zu reservierenden Domains ist erreicht."; break;
   case "max_subdomain_reached":                   $return['returnstring'] = "Die maximale Anzahl an zu reservierenden Subdomains ist erreicht."; break;
   case "max_webspace_reached":                    $return['returnstring'] = "Das maximale Volumen an zu reservierendem Webspace ist erreicht."; break;
   case "max_mail_account_reached":                $return['returnstring'] = "Die maximale Anzahl an zu reservierenden E-Mail-Accounts ist erreicht."; break;
   case "max_mail_forward_reached":                $return['returnstring'] = "Die maximale Anzahl an zu reservierenden E-Mail-Weiterleitungen ist erreicht."; break;
   case "max_mailinglist_reached":                 $return['returnstring'] = "Die maximale Anzahl an zu reservierenden Mailinglisten ist erreicht."; break;
   case "max_database_reached":                    $return['returnstring'] = "Die maximale Anzahl an zu reservierenden MySQL-Datenbanken ist erreicht."; break;
   case "max_ftpuser_reached":                     $return['returnstring'] = "Die maximale Anzahl an zu reservierenden FTP-Usern ist erreicht."; break;
      
   /* DOMAIN / SUBDOMAINANLEGEN UND BEARBEITEN */
   case "hostname_exists_as_domain":               $return['returnstring'] = "Diese Domain wird bereits von den Nameservern als Domain verwaltet."; break;
   case "hostname_exists_as_account":              $return['returnstring'] = "Diese Domain wird bereits von den Nameservern als Account verwaltet."; break;
   case "domain_has_active_fpse":                  $return['returnstring'] = "F&uuml;r diese Domain sind die FrontPage Servererweiterungen aktiviert. Ein Bearbeiten ist daher nicht m&ouml;glich."; break;
   case "subdomain_path_syntax_incorrect":         $return['returnstring'] = "Die Angabe des Zieles der Subdomain ist inkorrekt."; break;
   case "domain_path_syntax_incorrect":            $return['returnstring'] = "Die Angabe des Zieles der Domain ist inkorrekt."; break; 
     
      
   /* MySQL-DATENBANKEN ANLEGEN UND BEARBEITEN */
   case "database_password_incorrect":             $return['returnstring'] = "Das angegebene Datenbankpasswort ist nicht korrekt.."; break;
   
   /* E-MAILACCOUNT ANLEGEN UND BEARBEITEN */
   case "email_already_exists":                    $return['returnstring'] = "Diese E-Mailadresse ist bereits als Account oder Weiterleitung in Verwendeung."; break;
   case "responder_text_is_empty":                 $return['returnstring'] = "Es wurde kein Autorespondertext eingegeben."; break;
      
   /* E-MAILWEITERLEITUNGEN ANLEGEN UND BEARBEITEN */
   case "target_mail_dublicate":                   $return['returnstring'] = "Ein oder mehrere Ziele wurden mehrfach angegeben."; break;
   case "mail_forward_exists_as_forward":          $return['returnstring'] = "Eine Weiterleitung mit dieser Adresse existiert bereits."; break;
   case "mail_forward_exists_as_emailaccount":     $return['returnstring'] = "Ein E-Mail-Account mit dieser Adresse existiert bereits."; break;
   case "target_mail_syntax_incorrect":            $return['returnstring'] = "Mindestens ein Weiterleitungsziel ist keine g&uuml;ltige E-Mailadresse."; break;
   case "mail_forward_adress_syntax_incorrect":    $return['returnstring'] = "Mindestens ein Weiterleitungsziel ist keine g&uuml;ltige E-Mailadresse."; break;
      
   /* FMAILINGLISTEN ANLEGEN */
   case "mailinglist_already_exists":              $return['returnstring'] = "Eine Mailingliste mit diesem Namen existiert bereits."; break;
   case "mailinglist_syntax_incorrect":            $return['returnstring'] = "Ung&uuml;ltige Zeichen bei der Eingabe des Mailinglistennamens."; break;
   
   /* CRONJOBS */
   case "http_url_syntax_incorrect":               $return['returnstring'] = "Die eingegebene URL ist inkorrekt."; break;
   case "http_password_syntax_incorrect":          $return['returnstring'] = "Inkorrekte Eingabe im Feld \"Passwort\"."; break;
   case "mail_adress_syntax_incorrect":            $return['returnstring'] = "Keine g&uuml;ltige E-Mailadresse eingegeben."; break;
      
   /* VERZEICHISSCHUTZ ANLEGEN UND BEARBEITEN */
   case "directory_user_count_neq_passcount":      $return['returnstring'] = "Es wurde kein Passwort angegeben."; break;
   case "duplicate_directory_user":                $return['returnstring'] = "F&uuml;r diesen Verzeichnisschutz existiert bereits der eingegebene Login."; break;
   case "directory_password_syntax_incorrect":     $return['returnstring'] = "Passw&ouml;rter m&uuml;ssen aus 6 - 20 (a-zA-Z0-9) Zeichen bestehen."; break;

   default :                                       $return['returnstring'] = $return['returnstring']; break;
}
?>