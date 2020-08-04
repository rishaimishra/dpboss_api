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
    !empty($data->device_id)&&                    
    !empty($data->mpin)
){

    //set deviceid property values
    $deviceid->device_id=$data->device_id;
    $deviceid->mpin=$data->mpin;
  




     $sql_n="UPDATE 
		devicestatus 
        SET mpin='$data->mpin'  WHERE device_id='$deviceid->device_id' and status=1 ";
       
        //prepare query statement
        $stmt = $db->prepare($sql_n);

		
	if($sql_n){
        	// execute query
		if($stmt->execute()){
            //set response code - 400 bad request
  http_response_code(200);

  // tell the user mpin updated
  echo json_encode(array("response"=>"Success","message" => "Mpin Updated successfully"));
      }else{
        //set response code - 400 bad request
  http_response_code(200);
      echo json_encode(array("response"=>"Failed","message" => "Invalid Credentials"));
      return false;

  }
    }

}

else{


    //set response code - 400 bad request
    http_response_code(200);

    //tell the user
    echo json_encode(array("message"=>"Data is incomplete"));
}
?>