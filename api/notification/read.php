<?php

//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


//include database and object files
include_once '../config/database.php';
include_once '../objects/notification.php';

//instantiate database and notification object
$database = new Database();
$db = $database->getConnection();

//initialize object
$notification =new Notification($db);

//query notification
$stmt=$notification->read();
$num = $stmt->rowCount();

//check if more than 0 record found
if ($num>0) {
	
	//notification array
	$notification_arr=array();
    $notification_arr["records"]=array();


    //retrieve our table contents 
    //fetch() is faster than fetchAll()
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extract row
        //this will make $row['name'] to just $name only
        extract($row);

        $notification_item=array(
            "notify_title"=>$notify_title,
            "notify_text"=>$notify_text,
            "notify_time"=>$notify_time
        );
        array_push($notification_arr["records"],$notification_item);
    }
    
    //set response code - 200 ok
    http_response_code(200);

    //show notification data in json format
    echo json_encode($notification_arr);

    // no notifications found will be here


}else{
    //set response code - 404 Not found
    http_response_code(200);

    //tell the user no notifications found
    echo json_encode(
        array("response"=>"Failed","message"=>"No notifications found")
    );
}

?>