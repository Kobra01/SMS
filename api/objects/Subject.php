<?php
// 'Subject' object
class Subject{

    // database connection and table name
    private $conn;
    private $table_name = "subjects";

    // object properties
    public $id;
    public $name;
    public $short;
    public $subjects;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // CRUD -> Read

    public function getSubjects(){

        // Create Query
        $query = '  SELECT
                        id, name, short
                    FROM
                        ' . $this->table_name;


        // prepare the query
        $stmt = $this->conn->prepare($query);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        // assign values to object properties
        $this->subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return true;
    }

}