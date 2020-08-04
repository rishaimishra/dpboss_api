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

//make sure data is not empty
if(
    !empty($data->body)&&
    !empty($data->author)
){

    //set posts property values
    $posts->body=$data->body;
    $posts->author=$data->author;
   
  

    //create the posts
    if($posts->create()){

        //set response code - 201 created
        http_response_code(201);

        //tell the user
        echo json_encode(array("message" => "Post was created"));
    }

    //if unable to create the posts tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(503);

        //tell the user
        echo json_encode(array("message"=>"Unable to create Post"));
    }

}elseif( !empty($data->body)&&
!empty($data->fakeuser)){

     //set posts property values
     $posts->body=$data->body;
     $posts->fakeuser=$data->fakeuser;
    
   
 
     //create the posts
     if($posts->create()){
 
         //set response code - 201 created
         http_response_code(201);
 
         //tell the user
         echo json_encode(array("message" => "Post was created"));
     }
 
     //if unable to create the posts tell the user
     else{
         //set response code - 503 service unavailable
         http_response_code(503);
 
         //tell the user
         echo json_encode(array("message"=>"Unable to create Post"));
     }

}
elseif( !empty($data->body)&&
!empty($data->admin_id)){

     //set posts property values
     $posts->body=$data->body;
     $posts->admin_id=$data->admin_id;
    
   
 
     //create the posts
     if($posts->create()){
 
         //set response code - 201 created
         http_response_code(201);
 
         //tell the user
         echo json_encode(array("message" => "Post was created"));
     }
 
     //if unable to create the posts tell the user
     else{
         //set response code - 503 service unavailable
         http_response_code(503);
 
         //tell the user
         echo json_encode(array("message"=>"Unable to create Post"));
     }

}

elseif( !empty($data->body)&&
!empty($data->subadmin_id)){

     //set posts property values
     $posts->body=$data->body;
     $posts->subadmin_id=$data->subadmin_id;
    
   
 
     //create the posts
     if($posts->create()){
 
         //set response code - 201 created
         http_response_code(201);
 
         //tell the user
         echo json_encode(array("message" => "Post was created"));
     }
 
     //if unable to create the posts tell the user
     else{
         //set response code - 503 service unavailable
         http_response_code(503);
 
         //tell the user
         echo json_encode(array("message"=>"Unable to create Post"));
     }

}

else{
    //set response code - 400 bad request
    http_response_code(400);

    //tell the user
    echo json_encode(array("message"=>"Data is incomplete"));
}
?>