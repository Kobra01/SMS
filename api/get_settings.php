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
include_once 'objects/Settings.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate other objects
$settings = new Settings($db);


// check if data is set
if (!isset($jwt_decoded->data->id)) {

    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Some values are missing in the token."));

    die();
}

// Load color settings for subjects
$settings->uid = $jwt_decoded->data->id;
if (!$settings->getSettings()) {
    // message if unable to get settings
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to get settings."));
    die();
}

// set response code & answer
http_response_code(200);
echo json_encode(array(
    "error" => FALSE,
    "message" => "Found settings.",
    "subject_settings" => $settings->subject_settings));

?>