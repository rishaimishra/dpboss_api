<?php
// echo 'Current PHP version: ' . phpversion();
ini_set("display_errors",1);

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate deviceid object
include_once '../objects/deviceid.php';
 
$database = new Database();
$db = $database->getConnection();
  
$deviceid = new Deviceid($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//set deviceid property values
$deviceid->device_id = $data->device_id;
$deviceid->mpin = $data->mpin;
$deviceid->fb_token = $data->fb_token;
$mpin_exists = $deviceid->mpinExists();

// generate json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// var_dump($mpin_exists);




 
// generate jwt will be here
//check if mpin exists and if mpin is correct
if($mpin_exists && crypt($data->mpin, $deviceid->mpin)){
   
    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "mpin_id" => $deviceid->mpin_id,
            "username" => $deviceid->username,
            "device_id" => $deviceid->device_id,
            "generation_time" => $deviceid->generation_time,
            "status" => $deviceid->status
         
            
        )
     );
 

     // set response code
     http_response_code(200);
     
     // generate jwt
     $jwt = JWT::encode($token, $key);
     echo json_encode(
             array(
                "response"=>"Success",
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
    echo json_encode(array("response"=>"Failed","message" => "Login failed."));
}
?>


