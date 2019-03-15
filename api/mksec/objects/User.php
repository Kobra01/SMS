<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "user";
 
    // object properties
    public $id;
    public $type;
    public $username;
    public $school;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $state;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 

    // create new user record
    public function create(){
 
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    pwhash = :pwhash,
                    school = :school,
                    username = :username,
                    type = :type";
 
        // prepare the query
        $stmt = $this->conn->prepare($query);
 
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->school=htmlspecialchars(strip_tags($this->school));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->type=htmlspecialchars(strip_tags($this->type));
    
        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':school', $this->school);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':type', $this->type);
    
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':pwhash', $password_hash);
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
    }

    //update state of User
    public function updateState(){

        // Create Query
        $query = '  UPDATE
                        ' . $this->table_name . '
                    SET
                        state = :state 
                    WHERE
                        id = :id';


        // prepare the query
        $stmt = $this->conn->prepare($query);

        $this->code=htmlspecialchars(strip_tags($this->state));
        $this->type=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':id', $this->id);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;
    }
 
// emailExists() method will be here
}

?>