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
include_once 'objects/Subject.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate other objects
$subject = new Subject($db);

// Load subjects
if (!$subject->getSubjects()) {
    // message if unable to get subjects
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to get subjects."));
    die();
}

// set response code & answer
http_response_code(200);
echo json_encode(array(
    "error" => FALSE,
    "message" => "Found subjects.",
    "subjects" => $subject->subjects));

?>