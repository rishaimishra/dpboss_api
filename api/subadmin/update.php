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

// set ID property of subadmin to be edited
$subadmin->subadmin_id = $data->subadmin_id;
  
// set subadmin property values
$subadmin->fullname = $data->fullname;
$subadmin->username = $data->username;
$subadmin->mobile = $data->mobile;
$subadmin->password = password_hash($data->password, PASSWORD_DEFAULT);

if($subadmin->update()){

  
        //set response code - 200 created
        http_response_code(200);

        //tell the user
        echo json_encode(array("message" => "Subadmin was updated"));


}

else{
    //set response code - 503 bad request
    http_response_code(503);

    //tell the user
    echo json_encode(array("message"=>"Unable to update subadmin"));
}
?>