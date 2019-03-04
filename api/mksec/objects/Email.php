<?php

// 'Email' Object
class Email {
    
    // database connection and table name
    private $conn;
    private $table_name = "codes";

    // object properties
    public $id;
    public $code;
    public $email;
    public $user_id;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
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

        $code = substr(md5(time().$email), 0, 32);

        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(':user', $this->user_id);
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':type', '1');

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        send_mail();

        return true;

    }

    // Private Function
    private function send_mail() {

        $object = "SMS - E-Mail bestätigen";
        $from = "From: MKS - Software <noreply@mks-software.de>";
        $text = "Um Ihre E-Mail zu bestätigen und damit ihren Account freizuschalten, klicken sie bitte auf den nachfolgenden Link oder kopieren diesen in ihren Browser:
                \r\n
                \r\n https://mks-software.de/sms/api/mksec/confirm_email.php?code=".$code;
 
        mail($this->email, $object, $text, $from);

        return true;

    }

}
