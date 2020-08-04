<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate posts object
include_once '../objects/post.php';

$database = new Database();
$db = $database->getConnection();
  
$posts = new Posts($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));



if(!empty($data->post_id)&&
!empty($data->body)&&
!empty($data->author)){
   
// set ID property of posts to be edited
$posts->post_id = $data->post_id;


// set posts property values
$posts->body = $data->body;
$posts->author = $data->author;  

 //update the posts
 if($posts->update()){

    //set response code - 200 updated
    http_response_code(200);

    //tell the user
    echo json_encode(array("message" => "Post was updated"));
}

//if unable to update the fakeuser tell the user
else{
    //set response code - 503 service unavailable
    http_response_code(503);

    //tell the user
    echo json_encode(array("message"=>"Unable to update Post"));
}




}else if(!empty($data->post_id)&&
!empty($data->body)&&
!empty($data->fakeuser)){

// set ID property of posts to be edited
$posts->post_id = $data->post_id;


// set posts property values
$posts->body = $data->body;
$posts->author = $data->fakeuser; 

//update the posts
if($posts->update()){

    //set response code - 200 updated
    http_response_code(200);

    //tell the user
    echo json_encode(array("message" => "Post was updated"));
}

//if unable to update the fakeuser tell the user
else{
    //set response code - 503 service unavailable
    http_response_code(503);

    //tell the user
    echo json_encode(array("message"=>"Unable to update Post"));
}

}
else if(!empty($data->post_id)&&
!empty($data->body)&&
!empty($data->admin_id)){
    
// set ID property of posts to be edited
$posts->post_id = $data->post_id;


// set posts property values
$posts->body = $data->body;
$posts->author = $data->admin_id; 

//update the posts
if($posts->update()){

    //set response code - 200 updated
    http_response_code(200);

    //tell the user
    echo json_encode(array("message" => "Post was updated"));
}

//if unable to update the fakeuser tell the user
else{
    //set response code - 503 service unavailable
    http_response_code(503);

    //tell the user
    echo json_encode(array("message"=>"Unable to update Post"));
}

}
else if(!empty($data->post_id)&&
!empty($data->body)&&
!empty($data->subadmin_id)){
// set ID property of posts to be edited
$posts->post_id = $data->post_id;


// set posts property values
$posts->body = $data->body;
$posts->author = $data->subadmin_id; 

//update the posts
if($posts->update()){

    //set response code - 200 updated
    http_response_code(200);

    //tell the user
    echo json_encode(array("message" => "Post was updated"));
}

//if unable to update the fakeuser tell the user
else{
    //set response code - 503 service unavailable
    http_response_code(503);

    //tell the user
    echo json_encode(array("message"=>"Unable to update Post"));
}

}


  

?>