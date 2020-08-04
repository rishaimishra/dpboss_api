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





// get posted data
$data = json_decode(file_get_contents("php://input"));


 
//make sure data is not empty

   
if(
    !empty($data->mobile)&&
    !empty($data->otp)
){
    $myusers->mobile=$data->mobile;
    $myusers->otp=$data->otp;
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.msg91.com/api/v5/otp/verify?mobile=". $data->mobile."&otp=". $data->otp."&authkey=328462AhtDpV27Br5ece5f0aP1",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0,
    
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
     curl_close($curl);
    $res=json_decode($response);
    // echo $response;
    if ($res->type =='error') {
    //   echo "cURL Error #:" .$res->message;
      $query = "DELETE FROM myguests 
        WHERE mobile='$data->mobile' and status=0";
        
        //prepare query statement
        $stmt = $db->prepare($query);
        $stmt->execute();
        //set response code - 201 created
        http_response_code(400);
        echo json_encode(array("response"=>"Failed","message" => $res->message));
       
    } else {
    //query to update record

         //tell the user
         //set response code - 201 created
        http_response_code(201);
         echo json_encode(array("response"=>"Success","message" => "User registered successfully"));
        $mob=$data->mobile;
		$query = "UPDATE 
		myguests 
        SET status=1 WHERE mobile=$mob";
        
        //prepare query statement
        $stmt = $db->prepare($query);
        $stmt->execute();
       

     
    
    }
    
}


?>