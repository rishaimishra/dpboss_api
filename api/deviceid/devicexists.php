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

    //set myusers property values
    $deviceid->device_id=$data->device_id;
  
    


    //checking if mobile number exists
    $sql_m="SELECT * FROM devicestatus inner join myguests on myguests.username=devicestatus.username
     WHERE device_id='$deviceid->device_id' and devicestatus.status=1";
// echo $sql_m;die;
    
    //prepare query statement
    $stmt1 = $db->prepare($sql_m);

    //bind values
    $stmt1->bindParam('$deviceid->device_id', $data->device_id);

    //execute query
    if ($stmt1->execute()) {

         //checking if user mobile exists
    if($stmt1->rowCount() > 0){
        $row = $stmt1->fetch(PDO::FETCH_ASSOC);
        $arr = str_split($row['mobile']);
        

        // echo $row['username'];
      
         //set response code - 200 request send successfully
         http_response_code(200);

         //tell the user
        
         echo json_encode(array("version"=>"1","response"=>"Success","message"=>"Hi ".$row['username']."","message1"=>"your mobile number " . $arr[3] . "*******" . $arr[11] . "" . $arr[12] . " is already registered","message2"=>"you are ready enter mpin","message3"=>"" . $arr[3] . "*******" . $arr[11] . "" . $arr[12] . ""));
 
    }elseif($stmt1->rowCount()==0){
        $sql_ex="SELECT * FROM devicestatus 
        WHERE device_id='$deviceid->device_id' and devicestatus.status=0";
        //  echo $sql_ex;die;
        
        //prepare query statement
        $stmt2 = $db->prepare($sql_ex);
        
        $stmt2->execute();
        
        
        if ($stmt2->rowCount() > 0) {
             //set response code - 200 request send successfully
             http_response_code(200);
        
             //tell the user
        
             echo json_encode(array("response"=>"Blocked","message"=>"Your Profile is Blocked"));
        
        }
        else{

            //set response code - 200 request send successfully
            http_response_code(200);
  
            //tell the user
  
            echo json_encode(array("response"=>"Failed","message"=>"Please register the device first"));
  
           
          
  
      }
            
        
        }
    
        
}

  
   
}



?>