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
    !empty($data->device_id)
){

   //set deviceid property values
   $deviceid->device_id=$data->device_id;

  
    $query_mobile="SELECT mobile FROM `myguests` inner join devicestatus 
    on devicestatus.username=myguests.username where devicestatus.device_id='$deviceid->device_id' 
    and devicestatus.status=1";

    //prepare query statement
    $stmt3 = $db->prepare($query_mobile);

    //execute query
    $stmt3->execute();
   $row_mobile=$stmt3->fetch(PDO::FETCH_ASSOC);

  
   
     //checking if mobile number exists
     $sql_m="SELECT mobile FROM myguests WHERE mobile='". $row_mobile['mobile']."'";

     //prepare query statement
     $stmt1 = $db->prepare($sql_m);
 
 
     //execute query
     $stmt1->execute();
     $row_mobile2=$stmt1->fetch(PDO::FETCH_ASSOC);
     //checking if user device exists
     if($stmt1->rowCount() > 0){
       
          //sms api starts
          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.msg91.com/api/v5/otp?authkey=328462AhtDpV27Br5ece5f0aP1&template_id=5ece67d0d6fc0559043ddb3a&extra_param=&mobile=". $row_mobile2['mobile'] ."",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
              "content-type: application/json"
            ),
          ));
          
          $response = curl_exec($curl);
          $err = curl_error($curl);
          
          curl_close($curl);
  
          if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              $arr = str_split($row_mobile['mobile']);
  
              //tell the user
          echo json_encode(array("response"=>"Success","message" => "otp sent to " . $arr[3] . "*******" . $arr[11] . "" . $arr[12] . " registered mobile number"));
         
          
            }
  
 
     }


}

else{


    //set response code - 400 bad request
    http_response_code(400);

    //tell the user
    echo json_encode(array("response"=>"Failed","message"=>"Data is incomplete or mobile number does not exits"));
}
?>