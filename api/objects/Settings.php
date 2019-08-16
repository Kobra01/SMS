<?php
// 'Settings' object
class Settings{

    // database connection and table name
    private $conn;
    private $table_name = "settings";

    // object properties
    public $uid;
    public $subject_settings;

    // constructor
    public function __construct($db){
        $this->conn = $db;
        $this->subject_settings = '{}';
    }

    // CRUD -> Create

    private function createSettings(){
        
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    uid = :uid,
                    subject = '{}'";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->uid=htmlspecialchars(strip_tags($this->uid));

        // bind the values
        $stmt->bindParam(':uid', $this->uid);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // CRUD -> Read

    public function getSettings(){

        // Create Query
        $query = '  SELECT
                        subject
                    FROM
                        ' . $this->table_name . '
                    WHERE
                        uid = :uid';


        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->uid=htmlspecialchars(strip_tags($this->uid));

        // bind the values
        $stmt->bindParam(':uid', $this->uid);

        // exit if failed
        if(!$stmt->execute()){
            return $this->createSettings();
        }
        
        if ($stmt->rowCount() < 1) {
            return $this->createSettings();
        }

        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // assign values to object properties
        $this->subject_settings = $row['subject'];
        return true;
    }

    // CRUD -> Update

    // update settings
    public function updateSettings(){
        
        // insert query
        $query = 'UPDATE 
                    ' . $this->table_name . '
                SET
                    subject = :subject
                WHERE
                    uid = :uid';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->uid=htmlspecialchars(strip_tags($this->uid));
        $this->subject_settings=htmlspecialchars(strip_tags($this->subject_settings));

        // bind the values
        $stmt->bindParam(':uid', $this->uid);
        $stmt->bindParam(':subject', $this->subject_settings);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;
    }

}