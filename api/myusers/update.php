<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate myusers object
include_once '../objects/myusers.php';

$database = new Database();
$db = $database->getConnection();
  
$myusers = new Myusers($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));


// set ID property of myusers to be edited
$myusers->id = $data->id;


// set myusers property values
    $myusers->name=$data->name;
    $myusers->username=$data->username;
    $myusers->password=$data->password;
    $myusers->mobile=$data->mobile;
    $myusers->dob=$data->dob;
    $myusers->reg_date=$data->reg_date;


    //update the myusers
    if($myusers->update()){

        //set response code - 200 updated
        http_response_code(200);

        //tell the user
        echo json_encode(array("message" => "Myuser was updated"));
    }

    //if unable to update the myusers tell the user
    else{
        //set response code - 503 service unavailable
        http_response_code(503);

        //tell the user
        echo json_encode(array("message"=>"Unable to update Fakeuser"));
    }


?>