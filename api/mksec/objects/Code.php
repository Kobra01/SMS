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
    public $type;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    //create new E-Mail verify code
    public function createCode() {

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
        $this->type=htmlspecialchars(strip_tags($this->type));

        $stmt->bindParam(':user', $this->user_id);
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':type', $this->type);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;

    }

    public function verifyCode(){

        // Create Query
        $query = '  SELECT
                        id, user
                    FROM
                        ' . $this->table_name . '
                    WHERE
                        code = :code
                        AND
                        type = :type
                        AND
                        created > :created
                    LIMIT
                        0,1';


        // prepare the query
        $stmt = $this->conn->prepare($query);

        $createTime = strtotime('-24 hours');
    	$timestamp = date('Y-m-d H:i:s', $createTime);


        $this->code=htmlspecialchars(strip_tags($this->code));
        $this->type=htmlspecialchars(strip_tags($this->type));
        $this->type=htmlspecialchars(strip_tags($timestamp));

        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':type', $timestamp);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }
        
        if (!$stmt->rowCount() > 0) {
            return false;
        }

        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->user_id = $row['user'];

        return true;
    }

}

?>