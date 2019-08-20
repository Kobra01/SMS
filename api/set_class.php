<?php

// required headers
header("Access-Control-Allow-Origin: https://www.mks-software.de/sms/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// validate authorization header
include_once 'mksec/validate.php';

// files needed to connect to database
include_once 'config/Database.php';
include_once 'objects/Student.php';
include_once 'objects/Class.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate other objects
$student = new Student($db);
$classObject = new ClassObject($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//check if it is a student
if ($jwt_decoded->data->type != 'STNT') {

    // message that this is not a student
    http_response_code(401);
    echo json_encode(array("error" => TRUE, "message" => "You are not a student."));
    die();
}

// check if data is set
if (!isset($data->class_id)) {
    
    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "ClassID is missing."));
    die();
}

// set product property values
$student->user_id = $jwt_decoded->data->id;

// check if user exist
if (!$student->studentExist()) {

    // message if user not exist
    http_response_code(404);
    echo json_encode(array("error" => TRUE, "message" => "Student does not exist."));
    die();
}

$classObject->id = $data->class_id;
$classObject->student = $student->id;

// set the class to user
if(!$classObject->addStudent()){

    // message if unable to set class
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to save class."));
    die();
}

// set response code & answer
http_response_code(201);
echo json_encode(array("error" => FALSE, "message" => "Class was saved."));

?>