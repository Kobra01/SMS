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

//check if it is a student
if ($jwt_decoded->data->type != 'STNT') {

    // message that this is not a student
    http_response_code(401);
    echo json_encode(array("error" => TRUE, "message" => "You are not a student."));
    die();
}

// set product property values
$student->user_id = $jwt_decoded->data->id;

// get the user data
if(!$student->getStudentData()){

    // message if unable to create user
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to get student data."));
    die();
}

if ($student->id == 0) {
    // set response code & answer
    http_response_code(201);
    echo json_encode(array(
        "error" => TRUE,
        "error_code" => 1,
        "message" => "Student not exist."));
    die();
}

// set response code & answer
http_response_code(201);
echo json_encode(array(
    "error" => FALSE,
    "error_code" => 0,
    "message" => "Student data was found.",
    "pub_name" => $student->pub_name,
    "year" => $student->year,
    "class" => $student->class));

?>