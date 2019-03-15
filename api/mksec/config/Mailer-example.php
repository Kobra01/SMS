<?php

//Server settings
$phpmailer->SMTPDebug = 0;                                   // Enable verbose debug output
$phpmailer->isSMTP();                                        // Set mailer to use SMTP
$phpmailer->Host = 'server';                      // Specify main and backup SMTP servers
$phpmailer->SMTPAuth = true;                                 // Enable SMTP authentication
$phpmailer->Username = 'noreply@mks-software.de';            // SMTP username
$phpmailer->Password = 'password';                             // SMTP password
$phpmailer->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
$phpmailer->Port = 587;                                      // TCP port to connect to

?>