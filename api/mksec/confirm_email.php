<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// files needed to connect to database
include_once 'config/Database.php';
include_once 'objects/User.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate other objects
$user = new User($db);

// set product property values
$user->code = $_GET['code'];

// confirm the email
if (!$user->confirmEmail()) {

    // failed to confirm the email
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Failed to confirm email address."));

} else {

    // set response code & answer
    http_response_code(200);
    echo json_encode(array("error" => FALSE, "message" => "Email address succesfull confirmed."));

}


?>