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
include_once 'objects/Settings.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate other objects
$settings = new Settings($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// check if data is set
if (!isset($data->subject_settings)) {
    
    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Settings are missing."));
    die();
}

// set userid for settings
$settings->uid = $jwt_decoded->data->id;
$settings->subject_settings = $data->subject_settings;

// set the course to user
if(!$settings->updateSettings()){

    // message if unable to set settings
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to save settings."));
    die();
}

// set response code & answer
http_response_code(201);
echo json_encode(array("error" => FALSE, "message" => "Settings were saved."));

?>