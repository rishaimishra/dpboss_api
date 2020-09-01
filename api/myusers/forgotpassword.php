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
    !empty($data->mobile)
    
){

    //set myusers property values
    $myusers->mobile=$data->mobile;
    


    //checking if mobile number exists
    $sql_m="SELECT * FROM myguests WHERE mobile='$myusers->mobile' and status=1";

    
    //prepare query statement
    $stmt1 = $db->prepare($sql_m);

    //bind values
    $stmt1->bindParam('$myusers->mobile', $data->mobile);


    //execute query
    $stmt1->execute();

  
    //checking if user mobile exists
    if($stmt1->rowCount() > 0){


        //sms api starts
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.msg91.com/api/v5/otp?authkey=328462AhtDpV27Br5ece5f0aP1&template_id=5ece67d0d6fc0559043ddb3a&extra_param=&mobile=". $data->mobile ."",
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

            //tell the user
        echo json_encode(array("response"=>"Success","message" => "otp was created"));
       
        
          }


    
        $myusers->create();   
       
        //set response code - 201 created
        http_response_code(201);


    } else{

       //set response code - 200 request send successfully
       http_response_code(200);

       //tell the user
      
       echo json_encode(array("response"=>"Failed","message"=>"Mobile number does not exists"));
       
        //set response code - 201 created
        http_response_code(201);
          
        //tell the user
        // echo json_encode(array("message" => "Myguest was created"));
    }


   
}

else{
    //set response code - 400 bad request
    http_response_code(400);

    //tell the user
    echo json_encode(array("response"=>"Failed","message"=>"Data is incomplete"));
}
