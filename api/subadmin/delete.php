<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// include database object
include_once '../config/database.php';
  
// instantiate subadmin object
include_once '../objects/subadmin.php';

//get database connection
$database = new Database();
$db = $database->getConnection();
  
$subadmin = new Subadmin($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set subadmin id to be deleted
$subadmin->subadmin_id = $data->id;

// delete the subadmin
if($subadmin->delete()){

    //set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Subadmin was deleted"));
}
//if unable to delete the subadmin
else{
    //set http response 503 service unavailable
     http_response_code(503);

     //tell the user
     echo json_encode(array("message" =>"Unable to delete the subadmin"));
}

