<?php


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate myusers object
include_once '../objects/myusers.php';



$database = new Database();
$db = $database->getConnection();
  
$myusers = new Myusers($db);

if (is_uploaded_file($_FILES)) {
   
}


?>






