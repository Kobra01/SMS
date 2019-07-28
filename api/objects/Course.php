<?php
// 'course' object
class Course{

    // database connection and table name
    private $conn;
    private $table_name = "courses";
    private $table_member = "course_member";

    // object properties
    public $id;
    public $name;
    public $student;

    // constructor
    public function __construct($db){
        $this->conn = $db;
        $this->class = null;
    }

    // CRUD -> Create
    
    //create new course
    public function create(){

        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    name = :name";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));

        // bind the values
        $stmt->bindParam(':name', $this->name);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }
    
    // add student to course
    public function addStudent(){
        
        // insert query
        $query = 'INSERT INTO ' . $this->table_member . '
                SET
                    student = :student,
                    course = :course';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->student=htmlspecialchars(strip_tags($this->student));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind the values
        $stmt->bindParam(':student', $this->student);
        $stmt->bindParam(':course', $this->id);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;
    }


    // CRUD -> Read

    // get courses of the student
    public function getCoursesOfStudent(){

        // Create Query
        $query = '  SELECT
                        name
                    FROM
                        ' . $this->table_member . ', ' . $this->table_name . '
                    WHERE
                        student = :student
                    AND
                        course = id';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->student=htmlspecialchars(strip_tags($this->emastudentil));

        // bind the values
        $stmt->bindParam(':student', $this->student);

        // exit if execute failed
        if(!$stmt->execute()){
            return false;
        }

        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // assign values to object properties
        /*$this->id = $row['id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->password = $row['pwhash'];
        $this->type = $row['type'];
        $this->username = $row['username'];
        $this->school = $row['school'];
        $this->state = $row['state'];
        $this->modified = $row['modified'];*/

        return true;
    }
}

?>
