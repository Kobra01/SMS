<?php
// 'teacher' object
class Teacher{

    // database connection and table name
    private $conn;
    private $table_name = "teachers";

    // object properties
    public $id;
    public $user_id;
    public $pub_name;
    public $short;
    public $class;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // CRUD -> Create

    //create new student
    public function create(){

        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    pub_name = :pub_name,
                    short = :short";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->pub_name=htmlspecialchars(strip_tags($this->pub_name));
        $this->short=htmlspecialchars(strip_tags($this->short));

        // bind the values
        $stmt->bindParam(':pub_name', $this->pub_name);
        $stmt->bindParam(':short', $this->short);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

}

?>