<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// include database object
include_once '../config/database.php';
  
// instantiate post object
include_once '../objects/post.php';

//get database connection
$database = new Database();
$db = $database->getConnection();
  
$post = new Posts($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set post id to be deleted
$post->post_id[] = $data->post_id;

// delete the post
if($post->MultiDelete()){

    //set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Posts were deleted"));
}
//if unable to delete the post
else{
    //set http response 503 service unavailable
     http_response_code(503);

     //tell the user
     echo json_encode(array("message" =>"Unable to delete the post"));
}

