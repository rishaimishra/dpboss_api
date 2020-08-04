<?php

//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


//include database and object files
include_once '../config/database.php';
include_once '../objects/subadmin.php';

//instantiate database and subadmin object
$database = new Database();
$db = $database->getConnection();

//initialize object
$subadmin =new Subadmin($db);

//query subadmin
$stmt=$subadmin->read();
$num = $stmt->rowCount();

//check if more than 0 record found
if ($num>0) {
	
	//subadmin array
	$subadmins_arr=array();
    $subadmins_arr["records"]=array();


    //retrieve our table contents 
    //fetch() is faster than fetchAll()
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extract row
        //this will make $row['name'] to just $name only
        extract($row);

        $subadmin_item=array(
            "subadmin_id"=>$subadmin_id,
            "fullname"=>$fullname,
            "username"=>$username,
            "mobile"=>$mobile,
            "password"=>$password
        );
        array_push($subadmins_arr["records"],$subadmin_item);
    }
    
    //set response code - 200 ok
    http_response_code(200);

    //show subadmin data in json format
    echo json_encode($subadmins_arr);

    // no subadmins found will be here


}else{
    //set response code - 404 Not found
    http_response_code(404);

    //tell the user no subadmins found
    echo json_encode(
        array("message"=>"No subadmins found")
    );
}

?>