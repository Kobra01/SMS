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

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate other objects
$student = new Student($db);

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
if (!isset($data->year)) {
    
    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Year is missing."));
    die();
}

// set product property values
$student->user_id = $jwt_decoded->data->id;
$student->year = $data->year;
$student->pub_name = $jwt_decoded->data->firstname + ' ' + $jwt_decoded->data->lastname[0] + '.';

// check if user already exist
if ($student->studentExist()) {

    // message if user exist
    http_response_code(403);
    echo json_encode(array("error" => TRUE, "message" => "Student already exist."));
    die();
}

// create the user
if(!$student->create()){

    // message if unable to create user
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to create student."));
    die();
}

// set response code & answer
http_response_code(201);
echo json_encode(array("error" => FALSE, "message" => "Student was created."));

?>