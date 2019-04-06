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
include_once 'objects/Code.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate objects
$user = new User($db);
$code = new Code($db);

// set product property values
$code->type = '1';
$code->code = isset($_GET['code']) ? $_GET['code'] : die();

// verify code
if (!$code->verifyCode()) {

    // failed to confirm the email
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Failed to confirm code."));
    die();
}

$user->id = $code->user_id;
$user->state = '1';

if (!$user->updateState()) {
    
    // failed to confirm the email
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Failed to change user state."));
    die();
}
    
// set response code & answer
http_response_code(200);
echo json_encode(array("error" => FALSE, "message" => "Email address succesfull confirmed."));


?>