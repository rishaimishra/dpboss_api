<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate subadmin object
include_once '../objects/subadmin.php';

$database = new Database();
$db = $database->getConnection();
  
$subadmin = new Subadmin($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//make sure data is not empty
if(
    !empty($data->fullname)&&
    !empty($data->username)&&
    !empty($data->mobile)&&
    !empty($data->password)
){

    //set subadmin property values
    $subadmin->fullname=$data->fullname;
    $subadmin->username=$data->username;
    $subadmin->mobile=$data->mobile;
    $subadmin->password=password_hash($data->password, PASSWORD_DEFAULT);
    // $subadmin->create=date('Y-m-d H:i:s');

    //create the subadmin
    if($subadmin->create()){

        //set response code - 201 created
        http_response_code(201);

        //tell the user
        echo json_encode(array("message" => "Subadmin was created"));
    }

    //if unable to create the subadmin tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(503);

        //tell the user
        echo json_encode(array("message"=>"Unable to create Subadmin"));
    }

}

else{
    //set response code - 400 bad request
    http_response_code(400);

    //tell the user
    echo json_encode(array("message"=>"Data is incomplete"));
}
?>