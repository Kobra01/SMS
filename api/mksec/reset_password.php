<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

// get posted data
$data = json_decode(file_get_contents("php://input"));

// check if data is set
if (!isset($data->code) or
        !isset($data->password)) {
    
    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Some values are missed."));

    die();
}

// set product property values
$code->type = '2';
$code->code = $data->code;

// verify code
if (!$code->verifyCode()) {

    // failed to confirm the email
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Failed to confirm code."));

    die();
}

$user->id = $code->user_id;
$user->password = $data->password;

if (!$user->resetPassword()) {
    
    // failed to confirm the email
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Failed to change password."));
    die();
}
    
// set response code & answer
http_response_code(200);
echo json_encode(array("error" => FALSE, "message" => "Password succesfull changed."));

?>