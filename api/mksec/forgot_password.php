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
include_once 'config/Mailer.php';
 
// instantiate other objects
$user = new User($db);
$mailer = new Mailer($phpmailer);
$code = new Code($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// check if data is set
if (!isset($data->email)) {
    
    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Email is missing."));

    die();
}

// set product property values
$user->email = $data->email;
$user->email = $data->email;
if(!$user->getUserByEmail()){

    // message if unable to find user
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "User does not exist."));

    die();
}

// Prepare Verify Code
$code->user_id = $user->id;
$code->type = '2';

if (!$code->createCode()) {
      
    // message if unable to create code
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to create verify code."));

    die();
}

// Prepare sending Email
$mailer->email = $data->email;
$mailer->code = $code->code;

if (!$mailer->sendPasswordResetMail()) {

    // message if unable to send email
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to send email."));

    die();
}

// set response code & answer
http_response_code(201);
echo json_encode(array("error" => FALSE, "message" => "Email was send."));

?>