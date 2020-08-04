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


// set ID property of fakeuser to be edited
$fakeuser->fakeid = $data->fakeid;


// set fakeuser property values
$fakeuser->firstname = $data->firstname;
$fakeuser->lastname = $data->lastname;
$fakeuser->username = $data->username;
$fakeuser->mobile = $data->mobile;
$fakeuser->password = password_hash($data->password,PASSWORD_DEFAULT);


    //update the fakeuser
    if($fakeuser->update()){

        //set response code - 200 updated
        http_response_code(200);

        //tell the user
        echo json_encode(array("message" => "Fakeuser was updated"));
    }

    //if unable to update the fakeuser tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(503);

        //tell the user
        echo json_encode(array("message"=>"Unable to update Fakeuser"));
    }


?>