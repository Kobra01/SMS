<?php
// 'student' object
class Student{

    // database connection and table name
    private $conn;
    private $table_name = "students";
    private $class_table = "classes";

    // object properties
    public $id;
    public $user_id;
    public $class;
    public $year;
    public $pub_name;

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
                    uid = :uid,
                    year = :year,
                    pub_name = :pub_name";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->year=htmlspecialchars(strip_tags($this->year));
        $this->pub_name=htmlspecialchars(strip_tags($this->pub_name));

        // bind the values
        $stmt->bindParam(':uid', $this->user_id);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':pub_name', $this->pub_name);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    //CRUD -> Read

    //check if user already exist
    public function studentExist(){

        // Create Query
        $query = 'SELECT
                    id
                FROM
                    ' . $this->table_name . '
                WHERE
                    uid = :uid';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        // bind the values
        $stmt->bindParam(':uid', $this->user_id);

        // exit if failed
        if(!$stmt->execute()){
            return true;
        }
        
        if ($stmt->rowCount() > 0) {

            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // assign values to object properties
            $this->id = $row['id'];

            return true;
        }

        return false;
    }

    //get User data
    public function getStudentData(){

        // Create Query
        $query = 'SELECT
                    s.id, c.name, c.year, s.pub_name
                FROM
                    ' . $this->table_name.' s, ' . $this->class_table.' c
                WHERE
                    s.class = c.id
                AND
                    uid = :uid';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        // bind the values
        $stmt->bindParam(':uid', $this->user_id);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }
        
        if ($stmt->rowCount() > 0) {

            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // assign values to object properties
            $this->id = $row['id'];
            $this->year = $row['year'];
            $this->class = $row['name'];
            $this->pub_name = $row['pub_name'];

            return true;
        }

        $this->id = 0;
        $this->year = 0;
        $this->class = "null";
        $this->pub_name = "null";
        return true;
    }

}

?>