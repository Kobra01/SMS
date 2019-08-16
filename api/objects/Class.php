<?php
// 'class' object
class Class{

    // database connection and table name
    private $conn;
    private $table_name = "classes";
    private $table_students = "students";
    private $table_teachers = "teachers";

    // object properties
    public $id;
    public $name;
    public $year;

    public $classes;

    public $student;
    public $teacher;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // CRUD -> Create
    
    //create new class
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

    // CRUD -> Read

    // get all classes for one year
    public function getClasses(){

        // Create Query
        $query = '  SELECT
                        id, name
                    FROM
                        ' . $this->table_name . '
                    WHERE
                        year = :year';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->year=htmlspecialchars(strip_tags($this->year));

        // bind the values
        $stmt->bindParam(':year', $this->year);

        // exit if execute failed
        if(!$stmt->execute()){
            return false;
        }

        // get record details / values
        $this->classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return true;
    }

    // CRUD -> Update

    // add student to class
    public function addStudent(){
        
        // insert query
        $query = 'UPDATE 
                    ' . $this->table_students . '
                SET
                    class = :class
                WHERE
                    id = :id';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->student=htmlspecialchars(strip_tags($this->student));

        // bind the values
        $stmt->bindParam(':class', $this->id);
        $stmt->bindParam(':id', $this->student);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;
    }

    // add teacher to class
    public function addTeacher(){
        
        // insert query
        $query = 'UPDATE 
                    ' . $this->table_teachers . '
                SET
                    class = :class
                WHERE
                    id = :id';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->teacher=htmlspecialchars(strip_tags($this->teacher));

        // bind the values
        $stmt->bindParam(':class', $this->id);
        $stmt->bindParam(':id', $this->teacher);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;
    }

    // CRUD -> Delete

    // delete class
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