<?php
// required headers
header("Access-Control-Allow-Origin: https://www.mks-software.de/sms/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// files needed to connect to database
include_once 'config/Database.php';
include_once 'objects/User.php';
include_once 'objects/Email.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$user = new User($db);
$email = new Email($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->type = $data->type;
$user->username = $data->username;
$user->school = $data->school;
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;

 
// create the user
if(!$user->create()){
 
    // // message if unable to create user
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to create user."));
    
} else {

    // Prepare Email verify
    $email->email = $data->email;
    $email->user_id = $user->id;

    if(!$email->verify_email()){
        // message if unable to send email

        http_response_code(400);
        echo json_encode(array("error" => TRUE, "message" => "Unable to verify email."));

    } else {
 
        // set response code & answer
        http_response_code(200);
        echo json_encode(array("error" => FALSE, "message" => "User was created. Check your Emails."));
    }
}
?>