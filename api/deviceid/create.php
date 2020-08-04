<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate deviceid object
include_once '../objects/deviceid.php';

$database = new Database();
$db = $database->getConnection();
  
$deviceid = new Deviceid($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

//make sure data is not empty
if(
    !empty($data->username)&&
    !empty($data->device_id)&&
    !empty($data->mpin)
   
){

    //set deviceid property values
    $deviceid->username=$data->username;
    $deviceid->device_id=$data->device_id;
    $deviceid->mpin=$data->mpin;
   


     //checking if username number exists
     $sql_m="SELECT * FROM devicestatus WHERE device_id='$deviceid->device_id'";

     //prepare query statement
     $stmt1 = $db->prepare($sql_m);
 
     //bind values 
     $stmt1->bindParam('$deviceid->device_id', $data->device_id);
 
     //execute query
     $stmt1->execute();
 
     //checking if user device exists
     if($stmt1->rowCount() > 0){
       
          //set response code - 400 bad request
          http_response_code(400);
 
          //tell the user
          echo json_encode(array("response"=>"Failed","message"=>"Device Id already exists"));
 
     }

     elseif ($deviceid->create()) {
        
    
    //set response code - 201 created
    http_response_code(201);

    //tell the user
    echo json_encode(array("response"=>"Success","message" => "mpin was created"));
     }



    //if unable to create the deviceid tell the user
    else{
        //set response code - 400 service unavailable
        http_response_code(400);

        //tell the user
        echo json_encode(array("response"=>"Failed","message"=>"Unable to create deviceid"));
    }

}

else{


    //set response code - 400 bad request
    http_response_code(400);

    //tell the user
    echo json_encode(array("response"=>"Failed","message"=>"Data is incomplete"));
}
?>



