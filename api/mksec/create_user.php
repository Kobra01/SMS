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
include_once 'objects/Mailer.php';
include_once 'objects/Code.php';

// files and uses for sending email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/phpmailer/src/Exception.php';
require 'libs/phpmailer/src/PHPMailer.php';
require 'libs/phpmailer/src/SMTP.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate mail object
$phpmailer = new PHPMailer(true);                              // Passing `true` enables exceptions
 
// instantiate other objects
$user = new User($db);
$mailer = new Mailer($phpmailer);
$code = new Code($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// check if data is set
if (!isset($data->type) or
        !isset($data->username) or
            !isset($data->school) or
                !isset($data->firstname) or
                    !isset($data->lastname) or
                        !isset($data->email) or
                            !isset($data->password)) {
    
    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Some values are missed."));

    die();
}

// set product property values
$user->type = $data->type;
$user->username = $data->username;
$user->school = $data->school;
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;

// check if user already exist
if ($user->userExist()) {

    // message if user exist
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "User already exist."));

    die();
}

// create the user
if(!$user->create()){
 
    // // message if unable to create user
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to create user."));
    
    die();
}

// Prepare Verify Code
$code->user_id = $user->id;
$code->type = '1';

if (!$code->createCode()) {
      
    // message if unable to create user
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to create verify code."));

    die();
}

// Prepare Email verify
$mailer->email = $data->email;
$mailer->code = $code->code;

if (!$mailer->sendVerifyMail()) {

    // message if unable to send email
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to send email."));

    die();
}

// set response code & answer
http_response_code(200);
echo json_encode(array("error" => FALSE, "message" => "User was created. Check your Emails."));

?>