<?php

// required headers
header("Access-Control-Allow-Origin: https://www.mks-software.de/sms/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
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
if ($jwt_decoded->data->type == 'STNT') {

    // check if data is set
    if (!isset($jwt_decoded->data->id)) {

        // message if value missed
        http_response_code(400);
        echo json_encode(array("error" => TRUE, "message" => "Some values are missing in the token."));

        die();
    }

    // set product property values
    $student->user_id = $jwt_decoded->data->id;
    if (!$student->studentExist()) {
        
        // message that this is not a student
        http_response_code(404);
        echo json_encode(array("error" => TRUE, "message" => "Student not found."));
        die();
    }

    // set product property values
    $clasObjects->year = $student->year;
    if (!$classObject->getClasses()) {
        // message if unable to get classes
        http_response_code(400);
        echo json_encode(array("error" => TRUE, "message" => "Unable to find classes."));
        die();
    }

    // set response code & answer
    http_response_code(200);
    echo json_encode(array(
        "error" => FALSE,
        "message" => "Found classes.",
        "classes" => $classObject->classes));
    die();
}

 // message that this is not a valid type
http_response_code(403);
echo json_encode(array("error" => TRUE, "message" => "This action is not allowed."));

?>