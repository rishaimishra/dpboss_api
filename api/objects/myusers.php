<?php
ini_set("display_errors",1);
class Myusers{

	//database connection and table name
	private $conn;
	private $table_name="myguests";

	//object properties
	public $id;
	public $name;
	public $username;
    public $password;
    public $mobile;
    public $dob;
    public $reg_date;
    public $f_token;
	public $publication_status;
	public $otp;
	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 

    //read myusers
    function read(){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY id";

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();
        return $stmt;
	}
	
	
	//read one myuser
    function readProfile(){
        $query = "SELECT * FROM ". $this->table_name ." inner join devicestatus on devicestatus.username=myguests.username WHERE device_id ='".$this->device_id."' AND myguests.status=1 LIMIT 0,1";

        //prepare query statement
		$stmt = $this->conn->prepare($query);
		
		// bind device_id of myuser to be updated
		$stmt->bindParam(1, $this->device_id);
  
		// execute query
		$stmt->execute();
	  
		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		
	  
		if($row>0){
			// set values to object properties
		$this->name = $row['name'];
		$this->username = $row['username'];
		$this->mobile = $row['mobile'];
		$this->dob = $row['dob'];
		$this->reg_date = $row['reg_date'];
		}
		else{
			return false;
		}
	
	}
	
	
    //create Myguest
	function create(){
		//query to insert record
		$query = "INSERT INTO 
		". $this->table_name ." 
		(name,username,mobile,password,dob,reg_date,f_token) VALUES (:name,:username,:mobile,:password,:dob,:reg_date,:f_token)";

	
		//prepare query statement
		$stmt = $this->conn->prepare($query);
		
	
      

		// bind values
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":username", $this->username);
		$stmt->bindParam(":mobile", $this->mobile);
		$stmt->bindParam(":password", $this->password);
		$stmt->bindParam(":dob", $this->dob);
		$stmt->bindParam(":reg_date", $this->reg_date);
		$stmt->bindParam(":f_token", $this->f_token);
	
		

		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		
		return false;
		

	}
	
	//check if given username exist in the database
	function usernameExists(){

		// query to check if username exists
		$query = "SELECT id,name,dob,mobile,password,reg_date FROM ". $this->table_name ." WHERE username = ? AND status=1 LIMIT 0,1";

		// prepare the query
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->username=htmlspecialchars(strip_tags($this->username));

		// bind given email value
		$stmt->bindParam(1, $this->username);
 
		// execute the query
		$stmt->execute();
	
		// get number of rows
		$num = $stmt->rowCount();
	
		 // if email exists, assign values to object properties for easy access and use for php sessions
		 if($num>0){
 
			// get record details / values
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
			// assign values to object properties
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->dob = $row['dob'];
			$this->mobile = $row['mobile'];
			$this->password = $row['password'];
			$this->reg_date = $row['reg_date'];
	 
			// return true because email exists in the database
			
			return true;
		}
	 
		// return false if email does not exist in the database
		return false;
	}

	function delete(){
		//delete query
		$query= "DELETE FROM ". $this->table_name ." WHERE id= ? ";

		//prepare query
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));
	
		//bind id of record to delete
		$stmt->bindParam(1,$this->id);
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}
	
	
	
	function myuserblock(){
		//delete query
		$query= "UPDATE 
		". $this->table_name ." 
		SET status=0 WHERE id=:id";

		//prepare query
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));
	
		// bind values
		$stmt->bindParam(":id",$this->id);
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}



	//update Myguest
	function update(){
		
			 //query to insert record
			 $query = "UPDATE 
			 ". $this->table_name ." 
			 SET name=:name,username=:username,mobile=:mobile,password=:password,dob=:dob,reg_date=:reg_date WHERE id=:id";
	 
			 //prepare query statement
			 $stmt = $this->conn->prepare($query);
			
		 
			 // sanitize
			 $this->name=htmlspecialchars(strip_tags($this->name));
			 $this->username=htmlspecialchars(strip_tags($this->username));
			 $this->mobile=htmlspecialchars(strip_tags($this->mobile));
			 $this->password=htmlspecialchars(strip_tags($this->password));
			 $this->dob=htmlspecialchars(strip_tags($this->dob));
			 $this->reg_date=htmlspecialchars(strip_tags($this->reg_date));
			 
			
		 
			 // bind values
			 $stmt->bindParam(":name", $this->name);
			 $stmt->bindParam(":username", $this->username);
			 $stmt->bindParam(":mobile", $this->mobile);
			 $stmt->bindParam(":password", $this->password);
			 $stmt->bindParam(":dob", $this->dob);
			 $stmt->bindParam(":reg_date", $this->reg_date);
			 $stmt->bindParam(":id",$this->id);
	 
			
			 
			 // execute query
			 if($stmt->execute()){
				 
				 return true;
			 }
			
			 return false;
	 
		 }
		 
		 
		 
		 //update Myguest Password
	function updatePassword(){
		// echo $this->mobile;die;
			 //query to insert record
			 $query = "UPDATE 
			 ". $this->table_name ." 
			 SET password=:password WHERE mobile=:mobile";
	 
			 //prepare query statement
			 $stmt = $this->conn->prepare($query);
			
		 
			 // sanitize
			 $this->password=htmlspecialchars(strip_tags($this->password));
			 $this->mobile=htmlspecialchars(strip_tags($this->mobile));
			 
			
		 
			 // bind values
			 $stmt->bindParam(":password", $this->password);
			 $stmt->bindParam(":mobile", $this->mobile);
	 
			 
			 // execute query
			 if($stmt->execute()){
				 
				 return true;
			 }
			
			 return false;
	 
		 }


		  //create Myguest with deviceid
	function createWithDeviceId(){
		echo $this->device_id;
		echo $this->username;
		//query to insert record
		$query = "INSERT INTO 
		". $this->table_name ." 
		(name,username,mobile,password,dob,reg_date,f_token) VALUES (:name,:username,:mobile,:password,:dob,:reg_date,:f_token)";


		$query2 = "INSERT INTO 
		devicestatus 
		(username,device_id) VALUES (:username,:device_id)";

	
		//prepare query statement
		$stmt = $this->conn->prepare($query);
		$stmt2 = $this->conn->prepare($query2);
		
	
      

		// bind values
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":username", $this->username);
		$stmt->bindParam(":mobile", $this->mobile);
		$stmt->bindParam(":password", $this->password);
		$stmt->bindParam(":dob", $this->dob);
		$stmt->bindParam(":reg_date", $this->reg_date);
		$stmt->bindParam(":f_token", $this->f_token);
		$stmt2->bindParam(":device_id", $this->device_id);
		$stmt2->bindParam(":username", $this->username);
	
		
echo "check2";
		
		// execute query
		if($stmt->execute()){

			echo "check";
			$stmt2->execute();
			return true;
		}
		
		return false;
		

	}

}


