<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// include database object
include_once '../config/database.php';
  
// instantiate myusers object
include_once '../objects/myusers.php';

//get database connection
$database = new Database();
$db = $database->getConnection();
  
$myusers = new Myusers($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set myusers id to be deleted
$myusers->id = $data->id;

// delete the myuser
if($myusers->delete()){

    //set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Myuser was deleted"));
}
//if unable to delete the Myusers
else{
    //set http response 503 service unavailable
     http_response_code(503);

     //tell the user
     echo json_encode(array("message" =>"Unable to delete the Myusers"));
}

