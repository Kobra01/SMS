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

    // send Mail Functions
    public function sendVerifyMail() {

        try {

            //Recipients
            $this->mailer->setFrom('noreply@mks-software.de', 'noreply@mks-software.de');
            $this->mailer->addAddress($this->email);                        // Name is optional

            //Content
            $this->mailer->isHTML(true);                                    // Set email format to HTML
            $this->mailer->Subject = 'SMS - E-Mail bestätigen';
            $etext = '<p>   Herzlich Willkommen bei MKS-Software, <br>
                            bitte klicken sie auf diesen <a href="https://mks-software.de/sms/api/mksec/confirm_email.php?code='.$this->code.'">Link</a> <br>
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

    public function sendPasswordResetMail() {

        try {

            //Recipients
            $this->mailer->setFrom('noreply@mks-software.de', 'noreply@mks-software.de');
            $this->mailer->addAddress($this->email);                        // Name is optional

            //Content
            $this->mailer->isHTML(true);                                    // Set email format to HTML
            $this->mailer->Subject = 'SMS - Passwort zurücksetzen';
            $etext = '<p>   Guten Tag, <br>
                            Hier können sie ihr Passwort zurücksetzen. <br>
                            Bitte klicken sie auf diesen <a href="https://mks-software.de/sms/resetpassword.html?code='.$this->code.'">Link</a> <br>
                            <br>
                            oder kopieren diese URL in ihren Browser: <br>
                            https://mks-software.de/sms/resetpassword.html?code='.$this->code.' </p>';
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