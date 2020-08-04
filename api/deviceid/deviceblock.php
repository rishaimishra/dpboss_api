<?php

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

// set deviceid mpin_id to be updated
$deviceid->mpin_id = $data->mpin_id;

// update status of user device
if($deviceid->deviceblock()){

    //set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("response"=>"Success","message" => "Your Profile is Blocked"));
}
//if unable to update the device status
else{
    //set http response 503 service unavailable
     http_response_code(503);

     //tell the user
     echo json_encode(array("response"=>"Failed","message" =>"Unable to block the device"));
}

