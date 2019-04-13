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

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate other objects
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//decide login by email or username
if (isset($data->email)) {

    // set product property values
    $user->email = $data->email;
    if(!$user->getUserByEmail()){

        // message if unable to find user
        http_response_code(400);
        echo json_encode(array("error" => TRUE, "message" => "User does not exist."));
        die();
    }

} elseif (isset($data->username) and isset($data->school)) {
    // set product property values
    $user->username = $data->username;
    $user->school = $data->school;
    if(!$user->getUserByUsername()){

        // message if unable to find user
        http_response_code(400);
        echo json_encode(array("error" => TRUE, "message" => "User does not exist."));
        die();
    }
} else {
    // message if unable to find user
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Some values are missing."));
    die();
}




if ($user->state != 2) {
    // message if blocked for loggin in
    http_response_code(401);
    echo json_encode(array("error" => TRUE, "message" => "User is actually blocked."));
    die();
}

// generate json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// check if email exists and if password is correct
if(password_verify($data->password, $user->password)){

    $token = array(
            /*   "iss" => $iss,
            "aud" => $aud,
            "iat" => $iat,
            "nbf" => $nbf,*/
        "data" => array(
            "id" => $user->id,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "email" => $user->email,
            "username" => $user->username,
            "school" => $user->school,
            "type" => $user->type,
            "state" => $user->state,
            "modified" => $user->modified
        )
    );

    // set response code
    http_response_code(200);

    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
            array(
                "error" => FALSE,
                "message" => "Successful login.",
                "jwt" => $jwt
            )
        );
    die();
}

// set response code
http_response_code(401);
echo json_encode(array("error" => TRUE, "message" => "Login failed."));

?>