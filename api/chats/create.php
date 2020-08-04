<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate chats object
include_once '../objects/chats.php';

$database = new Database();
$db = $database->getConnection();
  
$chats = new Chats($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//make sure data is not empty
if(
    !empty($data->device_id)&&
    !empty($data->date)&&
    !empty($data->question)
){

    //set chats property values
    $chats->device_id=$data->device_id;
    $chats->date=$data->date;
    $chats->question=$data->question;
    

   

    //query the chats
    if($chats->create()){

        //set response code - 201 created
        http_response_code(201);

        //tell the user
        echo json_encode(array("response"=>"Success","message" => "Query sent to admin"));
    }

    //if unable to query the chats tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(201);

        //tell the user
        echo json_encode(array("response"=>"Failed","message"=>"Unable to query admin"));
    }

}

else{
    //set response code - 400 bad request
    http_response_code(201);

    //tell the user
    echo json_encode(array("response"=>"Failed","message"=>"Data is incomplete"));
}
?>