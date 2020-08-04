<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate fakeuser object
include_once '../objects/fakeuser.php';

$database = new Database();
$db = $database->getConnection();
  
$fakeuser = new Fakeuser($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//make sure data is not empty
if(
    !empty($data->firstname)&&
    !empty($data->lastname)&&
    !empty($data->username)&&
    !empty($data->mobile)&&
    !empty($data->password)
){

    //set fakeuser property values
    $fakeuser->firstname=$data->firstname;
    $fakeuser->lastname=$data->lastname;
    $fakeuser->username=$data->username;
    $fakeuser->mobile=$data->mobile;
    $fakeuser->password=password_hash($data->password,PASSWORD_DEFAULT);
    // $fakeuser->create=date('Y-m-d H:i:s');

    //create the fakeuser
    if($fakeuser->create()){

        //set response code - 201 created
        http_response_code(201);

        //tell the user
        echo json_encode(array("message" => "Fakeuser was created"));
    }

    //if unable to create the fakeuser tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(503);

        //tell the user
        echo json_encode(array("message"=>"Unable to create Fakeuser"));
    }

}

else{
    //set response code - 400 bad request
    http_response_code(400);

    //tell the user
    echo json_encode(array("message"=>"Data is incomplete"));
}
?>