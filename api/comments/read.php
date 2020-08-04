<?php

//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


//include database and object files
include_once '../config/database.php';
include_once '../objects/comments.php';

//instantiate database and comments object
$database = new Database();
$db = $database->getConnection();

//initialize object
$comments =new comments($db);

//query comments
$stmt=$comments->read();
$num = $stmt->rowCount();

//check if more than 0 record found
if ($num>0) {
	
	//comments array
	$comments_arr=array();
    $comments_arr["records"]=array();


    //retrieve our table contents 
    //fetch() is faster than fetchAll()
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extract row
        //this will make $row['quote'] to just $quote only
        extract($row);

        $comments_item=array(
            "comment_id"=>$comment_id,
            "quote"=>$quote,
            "post_id"=>$post_id,
            "user_id"=>$user_id,
            "publication_status"=>$publication_status,
            "cmnt_time"=>$cmnt_time
            
        );
        array_push($comments_arr["records"],$comments_item);
    }
    
    //set response code - 200 ok
    http_response_code(200);

    //show comments data in json format
    echo json_encode($comments_arr);

    // no comments found will be here


}else{
    //set response code - 404 Not found
    http_response_code(404);

    //tell the user no comment found
    echo json_encode(
        array("message"=>"No comment found")
    );
}

?>