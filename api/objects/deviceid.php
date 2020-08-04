<?php

class Deviceid{

	//database connection and table name
	private $conn;
	private $table_name="devicestatus";

	//object properties
	public $mpin_id;
	public $myguests_id;
	public $device_id;
	public $mpin;
	public $generation_time;
	public $status;
	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 
    
    //read device status
    function read(){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY mpin_id";

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();
        return $stmt;
	}
	
	//create device id and mpin
	function create(){
		//query to insert record
		$query = "INSERT INTO 
		". $this->table_name ." 
		(username,device_id,mpin) VALUES (:username,:device_id,:mpin)";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->device_id=htmlspecialchars(strip_tags($this->device_id));
		$this->mpin=htmlspecialchars(strip_tags($this->mpin));
		
        
		// bind values
		$stmt->bindParam(":username", $this->username);
		$stmt->bindParam(":device_id", $this->device_id);
		$stmt->bindParam(":mpin", $this->mpin);
		
		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		
		return false;


	}

	function delete(){
		//delete query
		$query= "DELETE FROM ". $this->table_name ." WHERE fakeid= ? ";

		//prepare query
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->id=htmlspecialchars(strip_tags($this->fakeid));
	
		//bind id of record to delete
		$stmt->bindParam(1,$this->id);
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}

	//check if given mpin exist in the database
	function mpinExists(){
		
		$query1 = "UPDATE 
		devicestatus 
        SET fb_token='".$this->fb_token."' WHERE device_id='".$this->device_id."'";
        
        //prepare query statement
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->execute();
       
		
		// query to check if mpin exists
		$query = "SELECT mpin_id,username,generation_time,status FROM ". $this->table_name ." WHERE device_id ='".$this->device_id."' and mpin='".$this->mpin."' and status=1 ";
		
		// prepare the query
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->device_id=htmlspecialchars(strip_tags($this->device_id));
		$this->mpin=htmlspecialchars(strip_tags($this->mpin));

	
 
		// execute the query
		$stmt->execute();
	
		// get number of rows
		$num = $stmt->rowCount();
		 
		 // if email exists, assign values to object properties for easy access and use for php sessions
		 if($num>0){
			
			// get record details / values
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
			// assign values to object properties
			$this->mpin_id = $row['mpin_id'];
			$this->username = $row['username'];
			$this->generation_time = $row['generation_time'];
			$this->status = $row['status'];
	 
			// return true because email exists in the database
		
			return true;
		}
	 
		// return false if email does not exist in the database
		return false;
	}



	function deviceblock(){
		//update query
		$query= "UPDATE 
		". $this->table_name ." 
		SET status=0 WHERE mpin_id=:mpin_id";

		//prepare query
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->mpin_id=htmlspecialchars(strip_tags($this->mpin_id));
	
		// bind values
		$stmt->bindParam(":mpin_id",$this->mpin_id);
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}

	

	


}

?>





