<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate advicetoadmin object
include_once '../objects/advicetoadmin.php';

$database = new Database();
$db = $database->getConnection();
  
$advicetoadmin = new Advicetoadmin($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//make sure data is not empty
if(
    !empty($data->device_id)&&
    !empty($data->advice)
    
){

    //set advicetoadmin property values
    $advicetoadmin->device_id=$data->device_id;
    $advicetoadmin->advice=$data->advice;
    
    
    //create the advicetoadmin
    if($advicetoadmin->create()){

        //set response code - 201 created
        http_response_code(201);

        //tell the user
        echo json_encode(array("response"=>"Success","message" => "Advice has sent to admin"));
    }

    //if unable to create the advicetoadmin tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(200);

        //tell the user
        echo json_encode(array("response"=>"Failed","message"=>"Unable to sent advice"));
    }

}

else{
    //set response code - 400 bad request
    http_response_code(200);

    //tell the user
    echo json_encode(array("response"=>"Failed","message"=>"Data is incomplete"));
}
?>