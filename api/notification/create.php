<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate notification object
include_once '../objects/notification.php';

$database = new Database();
$db = $database->getConnection();
  
$notification = new Notification($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//make sure data is not empty
if(
    !empty($data->notify_title)&&
    !empty($data->notify_text)&&
    !empty($data->device_id)
){

    //set notification property values
    $notification->notify_title=$data->notify_title;
    $notification->notify_text=$data->notify_text;
    $notification->device_id=$data->device_id;
    
    //create the notification
    if($notification->create()){

        //set response code - 201 created
        http_response_code(201);

        //tell the user
        echo json_encode(array("response"=>"Success","message" => "Notifications was created"));
    }

    //if unable to create the notification tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(200);

        //tell the user
        echo json_encode(array("response"=>"Failed","message"=>"Unable to create Notification"));
    }

}

else{
    //set response code - 400 bad request
    http_response_code(200);

    //tell the user
    echo json_encode(array("response"=>"Failed","message"=>"Data is incomplete"));
}
?>