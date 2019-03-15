<?php

// 'Email' Object
class Mailer {
    
    // database connection and table name
    private $mailer;
    private $table_name = "codes";

    // object properties
    public $email;
    public $code;
 
    // constructor
    public function __construct($phpmail){
        $this->mailer = $phpmail;
    }

    // Private Function
    public function sendVerifyMail() {

        try {
            //Server settings
            $this->mailer->SMTPDebug = 0;                                   // Enable verbose debug output
            $this->mailer->isSMTP();                                        // Set mailer to use SMTP
            $this->mailer->Host = 'mx2f80.netcup.net';                      // Specify main and backup SMTP servers
            $this->mailer->SMTPAuth = true;                                 // Enable SMTP authentication
            $this->mailer->Username = 'noreply@mks-software.de';            // SMTP username
            $this->mailer->Password = 'secret';                             // SMTP password
            $this->mailer->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
            $this->mailer->Port = 587;                                      // TCP port to connect to

            //Recipients
            $this->mailer->setFrom('noreply@mks-software.de', 'noreply@mks-software.de');
            $this->mailer->addAddress($this->email);                        // Name is optional

            //Content
            $this->mailer->isHTML(true);                                    // Set email format to HTML
            $this->mailer->Subject = 'SMS - E-Mail bestätigen';
            $etext = '<p>   Herzlich Willkommen bei MKS-Software, <br>
                            bitte klicken sie auf diesen <a href="https://mks-software.de/sms/api/mksec/confirm_email.php?code='.$this->code.'>Link</a> <br>
                            <br>
                            oder kopieren diese URL in ihren Browser: <br>
                            https://mks-software.de/sms/api/mksec/confirm_email.php?code='.$this->code.'</p>';
            $this->mailer->Body    = $etext;
            $this->mailer->AltBody = strip_tags($etext);

            $this->mailer->send();
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

}

?>