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
  
// instantiate myusers object
include_once '../objects/myusers.php';

$database = new Database();
$db = $database->getConnection();
  
$myusers = new Myusers($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//set myusers property values
$myusers->username = $data->username;
$username_exists = $myusers->usernameExists();

// generate json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// var_dump($username_exists);




 
// generate jwt will be here
//check if username exists and if password is correct
if($username_exists && crypt($data->password, $myusers->password)){
   
    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "id" => $myusers->id,
            "name" => $myusers->name,
            "dob" => $myusers->dob,
            "mobile" => $myusers->mobile,
            "username" => $myusers->username,
           "reg_date" => $myusers->reg_date
            
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


