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
include_once 'objects/Course.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate other objects
$student = new Student($db);
$course = new Course($db);

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
    // get the user data
    if(!$student->getStudentData()){

        // message if unable to get user data
        http_response_code(400);
        echo json_encode(array("error" => TRUE, "message" => "Unable to get student data."));
        die();
    }

    // set product property values
    $course->year = $student->year;
    if (!$course->getCourses()) {
        // message if unable to get courses
        http_response_code(400);
        echo json_encode(array("error" => TRUE, "message" => "Unable to find courses."));
        die();
    }

    // set response code & answer
    http_response_code(200);
    echo json_encode(array(
        "error" => FALSE,
        "message" => "Found courses.",
        "courses" => $course->courses));
    die();
}

 // message that this is not a valid type
http_response_code(403);
echo json_encode(array("error" => TRUE, "message" => "This action is not allowed."));

?>