<?php
ini_set("display_errors",1);

class Subadmin{

	//database connection and table name
	private $conn;
	private $table_name="subadmin";

	//object properties
	public $subadmin_id;
	public $fullname;
	public $username;
	public $mobile;
	public $password;
	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 
    
    //read subadmins
    function read(){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY subadmin_id";

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();
        return $stmt;
	}
	
	//create subadmin
	function create(){
		//query to insert record
		$query="INSERT INTO ". $this->table_name ." SET fullname=:fullname,username=:username,mobile=:mobile,password=:password";

		//prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->fullname=htmlspecialchars(strip_tags($this->fullname));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->mobile=htmlspecialchars(strip_tags($this->mobile));
        $this->password=htmlspecialchars(strip_tags($this->password));
		// $this->created=htmlspecialchars(strip_tags($this->created));
		
		// bind values
		$stmt->bindParam(":fullname",$this->fullname);
		$stmt->bindParam(":username",$this->username);
		$stmt->bindParam(":mobile",$this->mobile);
		$stmt->bindParam(":password",$this->password);
		// $stmt->bindParam(":created",$this->created);

		// execute query
		if($stmt->execute()){
			return true;
		}
		return false;


	}

	function delete(){
		//delete query
		$query= "DELETE FROM ". $this->table_name ." WHERE subadmin_id= ? ";

		//prepare query
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->id=htmlspecialchars(strip_tags($this->subadmin_id));
	
		//bind id of record to delete
		$stmt->bindParam(1,$this->id);
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}

	//check if given username exist in the database
	function usernameExists(){

		// query to check if username exists
		$query = "SELECT subadmin_id,fullname,mobile,password FROM ". $this->table_name ." WHERE username = ? LIMIT 0,1";

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
			$this->subadmin_id = $row['subadmin_id'];
			$this->fullname = $row['fullname'];
			$this->mobile = $row['mobile'];
			$this->password = $row['password'];
	 
			// return true because email exists in the database
			
			return true;
		}
	 
		// return false if email does not exist in the database
		return false;
	}


	//Update subadmin
	function update(){
		//query to insert record
		$query="UPDATE ". $this->table_name ." SET fullname=:fullname,username=:username,mobile=:mobile,password=:password WHERE subadmin_id=:subadmin_id";

		//prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->fullname=htmlspecialchars(strip_tags($this->fullname));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->mobile=htmlspecialchars(strip_tags($this->mobile));
        $this->password=htmlspecialchars(strip_tags($this->password));
		$this->subadmin_id=htmlspecialchars(strip_tags($this->subadmin_id));
		
		// bind values
		$stmt->bindParam(":fullname",$this->fullname);
		$stmt->bindParam(":username",$this->username);
		$stmt->bindParam(":mobile",$this->mobile);
		$stmt->bindParam(":password",$this->password);
		$stmt->bindParam(":subadmin_id",$this->subadmin_id);

		// execute query
		if($stmt->execute()){
			return true;
		}
		return false;


	}
}



?>