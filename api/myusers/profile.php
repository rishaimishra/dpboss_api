<?php

//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


//include database and object files
include_once '../config/database.php';
include_once '../objects/myusers.php';

//instantiate database and myusers object
$database = new Database();
$db = $database->getConnection();

//initialize object
$myusers =new Myusers($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//set myusers property values
$myusers->device_id = isset($_GET['device_id']) ? $_GET['device_id'] : die();


//query myusers
$myusers->readProfile();


//check if more than 0 record found
if ($myusers->name!=null){
    // create array
    $myuser_arr = array(
        
        "name" => $myusers->name,
        "username" => $myusers->username,
        "mobile" => $myusers->mobile,
        "dob" => $myusers->dob,
        "reg_date" => $myusers->reg_date
  
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($myuser_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user product does not exist
    echo json_encode(array("response"=>"Failed","message" => "User does not exist."));
}
?>

