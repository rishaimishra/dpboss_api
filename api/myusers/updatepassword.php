<?php

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


// set ID property of myusers to be edited
$myusers->mobile = $data->mobile;


// set myusers property values
    
    $myusers->password=$data->password;


    //update the myusers
    if($myusers->updatePassword()){

        //set response code - 200 updated
        http_response_code(200);

        //tell the user
        echo json_encode(array("response"=> "Success","message" => "Password was updated"));
    }

    //if unable to update the myusers tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(200);

        //tell the user
        echo json_encode(array("response"=> "Failed","message"=>"Unable to update password"));
    }


?>