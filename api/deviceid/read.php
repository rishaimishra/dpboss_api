<?php

//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


//include database and object files
include_once '../config/database.php';
include_once '../objects/deviceid.php';

//instantiate database and deviceid object
$database = new Database();
$db = $database->getConnection();

//initialize object
$deviceid =new Deviceid($db);

//query deviceid
$stmt=$deviceid->read();
$num = $stmt->rowCount();

//check if more than 0 record found
if ($num>0) {
	
	//deviceid array
	$deviceid_arr=array();
    $deviceid_arr["records"]=array();


    //retrieve our table contents 
    //fetch() is faster than fetchAll()
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extract row
        //this will make $row['myguests_id'] to just $myguests_id only
        extract($row);

        $deviceid_item=array(
            "mpin_id"=>$mpin_id,
            "myguests_id"=>$myguests_id,
            "device_id"=>$device_id,
            "mpin"=>$mpin,
            "status"=>$status,
            "generation_time"=>$generation_time
            
        );
        array_push($deviceid_arr["records"],$deviceid_item);
    }
    
    //set response code - 200 ok
    http_response_code(200);

    //show deviceid data in json format
    echo json_encode($deviceid_arr);

    // no deviceid found will be here


}else{
    //set response code - 404 Not found
    http_response_code(404);

    //tell the user no comment found
    echo json_encode(
        array("message"=>"No records found")
    );
}

?>