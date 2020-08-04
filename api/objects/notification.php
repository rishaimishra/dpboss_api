<?php

class Notification{

	//database connection and table name
	private $conn;
	private $table_name="notify";

	//object properties
	public $notify_id;
	public $device_id;
	public $notify_text;
	public $notify_status;
	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 
    
    //read fakeuser
    function read(){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY notify_id DESC";

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();
        return $stmt;
	}
	
	//create fakeuser
	function create(){
		//query to insert record
		$query = "INSERT INTO 
		". $this->table_name ." 
		(device_id,notify_text,notify_title) VALUES (:device_id,:notify_text,:notify_title)";
        // echo $query;
		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->device_id=htmlspecialchars(strip_tags($this->device_id));
		$this->notify_text=htmlspecialchars(strip_tags($this->notify_text));
		$this->notify_title=htmlspecialchars(strip_tags($this->notify_title));
        
	
		// bind values
		$stmt->bindParam(":device_id", $this->device_id);
		$stmt->bindParam(":notify_text", $this->notify_text);
		$stmt->bindParam(":notify_title", $this->notify_title);
		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		return false;


	}

	function delete(){
		//delete query
		$query= "DELETE FROM ". $this->table_name ." WHERE notify_id= ? ";

		//prepare query
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->id=htmlspecialchars(strip_tags($this->notify_id));
	
		//bind id of record to delete
		$stmt->bindParam(1,$this->id);
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
    }
    

    //read one myuser
    function readProfileNotifications(){
  
        $query = "SELECT * FROM ". $this->table_name ."
        WHERE device_id ='".$this->device_id."' AND notify_status=1";

        // echo $query;

        //prepare query statement
		$stmt = $this->conn->prepare($query);
		
		// bind device_id of myuser to be updated
		$stmt->bindParam(1, $this->device_id);
  
		// execute query
		if ($stmt->execute()) {
            return $stmt;
        }
        return false;
	  
		
        
	
	}

	



}

?>