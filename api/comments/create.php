<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate comments object
include_once '../objects/comments.php';

$database = new Database();
$db = $database->getConnection();
  
$comment = new Comments($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//make sure data is not empty
if(
    !empty($data->quote)&&
    !empty($data->post_id)&&
    !empty($data->user_id)
   
  
){

    //set comment property values
    $comment->quote=$data->quote;
    $comment->post_id=$data->post_id;
    $comment->user_id=$data->user_id;
   
   

    //create the comment
    if($comment->create()){

        //set response code - 201 created
        http_response_code(201);

        //tell the user
        echo json_encode(array("message" => "Comment was created"));
    }

    //if unable to create the comment tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(503);

        //tell the user
        echo json_encode(array("message"=>"Unable to create Comment"));
    }

}

else{
    //set response code - 400 bad request
    http_response_code(400);

    //tell the user
    echo json_encode(array("message"=>"Data is incomplete"));
}
?>