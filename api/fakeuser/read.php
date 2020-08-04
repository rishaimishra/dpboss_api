<?php

//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


//include database and object files
include_once '../config/database.php';
include_once '../objects/fakeuser.php';

//instantiate database and fakeuser object
$database = new Database();
$db = $database->getConnection();

//initialize object
$fakeuser =new Fakeuser($db);

//query fakeuser
$stmt=$fakeuser->read();
$num = $stmt->rowCount();

//check if more than 0 record found
if ($num>0) {
	
	//fakeuser array
	$fakeuser_arr=array();
    $fakeuser_arr["records"]=array();


    //retrieve our table contents 
    //fetch() is faster than fetchAll()
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extract row
        //this will make $row['name'] to just $name only
        extract($row);

        $fakeuser_item=array(
            "fakeid"=>$fakeid,
            "firstname"=>$firstname,
            "lastname"=>$lastname,
            "username"=>$username,
            "mobile"=>$mobile,
            "password"=>$password
        );
        array_push($fakeuser_arr["records"],$fakeuser_item);
    }
    
    //set response code - 200 ok
    http_response_code(200);

    //show fakeuser data in json format
    echo json_encode($fakeuser_arr);

    // no fakeusers found will be here


}else{
    //set response code - 404 Not found
    http_response_code(404);

    //tell the user no fakeusers found
    echo json_encode(
        array("message"=>"No fakeusers found")
    );
}

?>