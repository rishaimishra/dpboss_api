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
  
// instantiate admin object
include_once '../objects/admin.php';

$database = new Database();
$db = $database->getConnection();
  
$admin = new Admin($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//set admin property values
$admin->admin_email = $data->admin_email;
$email_exists = $admin->emailExists();

// generate json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// var_dump($admin);
 
// generate jwt will be here
//check if email exists and if password is correct
if($email_exists && password_verify($data->admin_password, $admin->admin_password)){

    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "admin_id" => $admin->admin_id,
            "admin_name" => $admin->admin_name,
            "admin_email" => $admin->admin_email
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


