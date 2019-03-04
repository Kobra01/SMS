<?php

// 'Email' Object
class Email {

    //File with Strings/Texts
    include_once 'config/strings.php';
    
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

        $from = "From: MKS - Software <noreply@mks-software.de>";
        $text = DE_EVERIFY_TEXT."\r\n \r\n https://mks-software.de/sms/api/mksec/confirm_email.php?code=".$code;
 
        mail($this->email, DE_EVERIFY_OBJECT, DE_EVERIFY_TEXT, $from);

        return;

    }

}
