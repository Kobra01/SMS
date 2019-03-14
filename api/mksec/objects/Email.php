<?php

// 'Email' Object
class Email {
    
    // database connection and table name
    private $conn;
    private $mailer;
    private $table_name = "codes";
    private $type = "1";

    // object properties
    public $id;
    public $code;
    public $email;
    public $user_id;
 
    // constructor
    public function __construct($db, $mail){
        $this->conn = $db;
        $this->mailer = $mail;
    }

    //create new E-Mail verify code
    public function verify_email() {

        // Create Query
        $query = 'INSERT INTO ' . $this->table_name . '
                SET
                    user = :user,
                    code = :code,
                    type = :type';


        // prepare the query
        $stmt = $this->conn->prepare($query);

        $this->code = substr(md5(time().$email), 0, 32);

        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->type=htmlspecialchars(strip_tags($this->type));

        $stmt->bindParam(':user', $this->user_id);
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':type', $this->type);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        if(!$this->sendMail()){
            return false;
        }

        return true;

    }

    // Private Function
    private function sendMail() {

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
                            bitte klicken sie auf diesen <a href="https://mks-software.de/sms/api/mksec/confirm_email.php?code="'.$this->code.'>Link</a> <br>
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
