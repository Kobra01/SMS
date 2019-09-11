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
include_once 'objects/EGN-Substitution.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate other objects
$sub = new EGNSubstitution($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// check if data is set
if (!isset($data->substitution_json)) {
    
    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Substitution is missing."));
    die();
}

$substitution_array = json_decode($data->substitution_json);

foreach ($substitution_array as $key => $substitute) {

    $substitute = (array) $substitute;
    
    $sub->error = (isset($substitute['error'])) ? $substitute['error'] : TRUE ;
    $sub->date = (isset($substitute['date'])) ? $substitute['date'] : '01.01.2000';
    $sub->year = (isset($substitute['year'])) ? $substitute['year'] : '1';
    $sub->class = (isset($substitute['class'])) ? $substitute['class'] : '';
    $sub->course = (isset($substitute['course'])) ? $substitute['course'] : '';
    $sub->time = (isset($substitute['time'])) ? $substitute['time'] : '0';
    $sub->subject_old = (isset($substitute['subject_old'])) ? $substitute['subject_old'] : '';
    $sub->teacher_old = (isset($substitute['teacher_old'])) ? $substitute['teacher_old'] : '';
    $sub->room_old = (isset($substitute['room_old'])) ? $substitute['room_old'] : '';
    $sub->subject = (isset($substitute['subject'])) ? $substitute['subject'] : '';
    $sub->teacher = (isset($substitute['teacher'])) ? $substitute['teacher'] : '';
    $sub->room = (isset($substitute['room'])) ? $substitute['room'] : '';
    $sub->info = (isset($substitute['info'])) ? $substitute['info'] : '';

    // insert the substitute
    if(!$sub->create()){

        // message if unable to set settings
        http_response_code(400);
        echo json_encode(array("error" => TRUE, "message" => "Error while inserting."));
        die();
    }
}

// set response code & answer
http_response_code(201);
echo json_encode(array("error" => FALSE, "message" => "Substitution was inserted."));

?>