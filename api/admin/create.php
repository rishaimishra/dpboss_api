<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate admin object
include_once '../objects/admin.php';

$database = new Database();
$db = $database->getConnection();
  
$admin = new Admin($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//make sure data is not empty
if(
    !empty($data->admin_name)&&
    !empty($data->admin_email)&&
    !empty($data->admin_password)
){

    //set admin property values
    $admin->admin_name=$data->admin_name;
    $admin->admin_email=$data->admin_email;
    $admin->admin_password=password_hash($data->admin_password, PASSWORD_DEFAULT);
    // $admin->create=date('Y-m-d H:i:s');

    //create the admin
    if($admin->create()){

        //set response code - 201 created
        http_response_code(201);

        //tell the user
        echo json_encode(array("message" => "Admin was created"));
    }

    //if unable to create the fakeuser tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(503);

        //tell the user
        echo json_encode(array("message"=>"Unable to create admin"));
    }

}

else{
    //set response code - 400 bad request
    http_response_code(400);

    //tell the user
    echo json_encode(array("message"=>"Data is incomplete"));
}
?>