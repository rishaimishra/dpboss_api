<?php
echo 'Current PHP version: ' . phpversion();

ini_set("display_errors",1);

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate subadmin object
include_once '../objects/subadmin.php';

$database = new Database();
$db = $database->getConnection();
  
$subadmin = new Subadmin($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//set subadmin property values
$subadmin->username = $data->username;
$username_exists = $subadmin->usernameExists();

// generate json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// var_dump($admin);
 
// generate jwt will be here
//check if username exists and if password is correct
if($username_exists && password_verify($data->password, $subadmin->password)){

    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "subadmin_id" => $subadmin->subadmin_id,
            "fullname" => $subadmin->fullname,
            "username" => $subadmin->username
        )
     );
 
     // set response code
     http_response_code(200);
     
     // generate jwt
     $jwt = JWT::encode($token, $key);
     echo json_encode(
             array(
                 "message" => "Successful login.",
                 "jwt" => $jwt
             )
         );
  
 }
  
 // login failed
else{
 
    // set response code
    http_response_code(401);
 
    // tell the user login failed
    echo json_encode(array("message" => "Login failed."));
}
?>


