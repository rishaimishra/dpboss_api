<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate notification object
include_once '../objects/notification.php';

$database = new Database();
$db = $database->getConnection();
  
$notification = new Notification($db);


// get posted data
$data = json_decode(file_get_contents("php://input"));

//set notification property values
$notification->device_id = isset($_GET['device_id']) ? $_GET['device_id'] : die();


//query notification
$stmt=$notification->readProfileNotifications();

$num = $stmt->rowCount();


//check if more than 0 record found
if ($num>0) {
	
	//admin array
	$admin_arr=array();
    $admin_arr["records"]=array();


    //retrieve our table contents 
    //fetch() is faster than fetchAll()
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extract row
        //this will make $row['name'] to just $name only
        extract($row);

        $admin_item=array(
            "notify_title"=>$notify_title,
            "notify_text"=>$notify_text,
            "notify_time"=>$notify_time,
        );
        array_push($admin_arr["records"],$admin_item);
    }
    
    //set response code - 200 ok
    http_response_code(200);

    //show admin data in json format
    echo json_encode($admin_arr);

    // no admins found will be here


}
  
else{
    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user product does not exist
    echo json_encode(array("response"=>"Failed","message" => "Chats does not exist."));
}

?>