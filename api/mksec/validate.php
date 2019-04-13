<?php

// required headers
header("Access-Control-Allow-Origin: https://www.mks-software.de/sms/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to decode jwt
include_once dirname(__FILE__) . '/config/core.php';
include_once dirname(__FILE__) . '/libs/php-jwt-master/src/BeforeValidException.php';
include_once dirname(__FILE__) . '/libs/php-jwt-master/src/ExpiredException.php';
include_once dirname(__FILE__) . '/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once dirname(__FILE__) . '/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;


if (!isset($_SERVER["HTTP_AUTHORIZATION"])) {

    // message if no jwt set
    http_response_code(401);
    echo json_encode(array("error" => TRUE, "message" => "No Authorization-header set"));
    die();
}

list($type, $data) = explode(" ", $_SERVER["HTTP_AUTHORIZATION"], 2);
if (strcasecmp($type, "Bearer") == 0) {

    if (!$data) {
        http_response_code(401);
        echo json_encode(array("error" => TRUE, "message" => "JWT missing"));
        die();
    }

    // if decode succeed, show user details
    try {
        // decode jwt
        $decoded = JWT::decode($data, $key, array('HS256')); 
    } 
    // if decode fails, it means jwt is invalid
    catch (Exception $e){

        // set response code
        http_response_code(401);

        // tell the user access denied  & show error message
        echo json_encode(array(
            "error" => TRUE,
            "message" => "Access denied.",
            "error-message" => $e->getMessage()
        ));

        die();
    }

} else {
	http_response_code(401);
    echo json_encode(array("error" => TRUE, "message" => "Wrong Authorization-header set"));
    die();
}

?>