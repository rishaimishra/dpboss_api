<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// include database object
include_once '../config/database.php';
  
// instantiate comments object
include_once '../objects/comments.php';

//get database connection
$database = new Database();
$db = $database->getConnection();
  
$comments = new Comments($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set comments id to be deleted
$comments->comment_id[] = $data->comment_id;

// delete the comments
if($comments->MultiDelete()){

    //set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Comment was deleted"));
}
//if unable to delete the comments
else{
    //set http response 503 service unavailable
     http_response_code(503);

     //tell the user
     echo json_encode(array("message" =>"Unable to delete the comments"));
}

