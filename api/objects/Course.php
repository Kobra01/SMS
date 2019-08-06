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
    public $year;
    public $student;
    public $courses;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // CRUD -> Create
    
    //create new course
    public function create(){

        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    name = :name,
                    year = :year";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->year=htmlspecialchars(strip_tags($this->year));

        // bind the values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':year', $this->year);

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
                        id, name
                    FROM
                        ' . $this->table_member . ', ' . $this->table_name . '
                    WHERE
                        student = :student
                    AND
                        course = id';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->student=htmlspecialchars(strip_tags($this->student));

        // bind the values
        $stmt->bindParam(':student', $this->student);

        // exit if execute failed
        if(!$stmt->execute()){
            return false;
        }

        // get record details / values
        $this->courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return true;
    }

    // CRUD -> Delete

    // remove student from course
    public function removeStudent(){

        // Create Query
        $query = '  DELETE FROM
                        ' . $this->table_member . '
                    WHERE
                        student = :student';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        $this->student = htmlspecialchars(strip_tags($this->student));

        $stmt->bindParam(':student', $this->student);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;
    }

    // delete course
    public function delete(){

        // Create Query
        $query = '  DELETE FROM
                        ' . $this->table_name . '
                    WHERE
                        id = :id';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;
    }

}

?>
