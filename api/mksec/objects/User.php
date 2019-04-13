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
    public $modified;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // CRUD -> Create

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

    //CRUD -> Read

    //check if user already exist
    public function userExist(){
        
        // Create Query
        $query = '  SELECT
                        id
                    FROM
                        ' . $this->table_name . '
                    WHERE
                        ( username = :username AND school = :school )
                    OR
                        email = :email
                    OR
                        ( firstname = :firstname AND lastname = :lastname )';


        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->school=htmlspecialchars(strip_tags($this->school));
        $this->username=htmlspecialchars(strip_tags($this->username));

        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':school', $this->school);
        $stmt->bindParam(':username', $this->username);

        // exit if failed
        if(!$stmt->execute()){
            return true;
        }
        
        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;

    }

    // load user data by email
    public function getUserByEmail(){
        
        // Create Query
        $query = '  SELECT
                        id, type, firstname, lastname, pwhash, username, school, state, modified
                    FROM
                        ' . $this->table_name . '
                    WHERE
                        email = :email
                    LIMIT
                        0,1';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));

        // bind the values
        $stmt->bindParam(':email', $this->email);

        // exit if execute failed
        if(!$stmt->execute()){
            return false;
        }

        // if email exists, assign values to object properties for easy access and use for php sessions
        if (!$stmt->rowCount() > 0) {
            return false;
        }

        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // assign values to object properties
        $this->id = $row['id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->password = $row['pwhash'];
        $this->type = $row['type'];
        $this->username = $row['username'];
        $this->school = $row['school'];
        $this->state = $row['state'];
        $this->modified = $row['modified'];

        return true;
    }

    // load user data by username
    public function getUserByUsername(){
        
        // Create Query
        $query = '  SELECT
                        id, type, firstname, lastname, pwhash, email, state, modified
                    FROM
                        ' . $this->table_name . '
                    WHERE
                        ( username = :username AND school = :school )
                    LIMIT
                        0,1';

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->school=htmlspecialchars(strip_tags($this->school));

        // bind the values
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':school', $this->school);

        // exit if execute failed
        if(!$stmt->execute()){
            return false;
        }

        // if email exists, assign values to object properties for easy access and use for php sessions
        if (!$stmt->rowCount() > 0) {
            return false;
        }

        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // assign values to object properties
        $this->id = $row['id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->password = $row['pwhash'];
        $this->type = $row['type'];
        $this->email = $row['email'];
        $this->state = $row['state'];
        $this->modified = $row['modified'];

        return true;
    }

    //CRUD -> Update

    //update state of user
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

    //reset password of user
    public function resetPassword(){

        // Create Query
        $query = '  UPDATE
                        ' . $this->table_name . '
                    SET
                        pwhash = :pwhash 
                    WHERE
                        id = :id';


        // prepare the query
        $stmt = $this->conn->prepare($query);

        $this->code=htmlspecialchars(strip_tags($this->password));
        $this->type=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':pwhash', $password_hash);

        // exit if failed
        if(!$stmt->execute()){
            return false;
        }

        return true;
    }

    //CRUD -> Delete

}

?>