<?php

// 'Code' Object
class Code {
    
    // database connection and table name
    private $conn;
    private $table_name = "codes";

    // object properties
    public $id;
    public $code;
    public $user_id;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    //create new E-Mail verify code
    public function createCode($codetype) {

        // Create Query
        $query = 'INSERT INTO ' . $this->table_name . '
                SET
                    user = :user,
                    code = :code,
                    type = :type';


        // prepare the query
        $stmt = $this->conn->prepare($query);

        $this->code = substr(md5(time().$this->user_id), 0, 32);

        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->type=htmlspecialchars(strip_tags($this->codetype));

        $stmt->bindParam(':user', $this->user_id);
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':type', $this->codetype);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;

    }

    

}
