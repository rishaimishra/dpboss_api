<?php

class Fakeuser{

	//database connection and table name
	private $conn;
	private $table_name="fakeuser";

	//object properties
	public $fakeid;
	public $firstname;
	public $lastname;
	public $username;
	public $mobile;
	public $password;
	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 
    
    //read fakeuser
    function read(){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY fakeid";

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
		(firstname,lastname,username,mobile,password) VALUES (:firstname,:lastname,:username,:mobile,:password)";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->mobile=htmlspecialchars(strip_tags($this->mobile));
        $this->password=htmlspecialchars(strip_tags($this->password));
		
	
		// bind values
		$stmt->bindParam(":firstname", $this->firstname);
		$stmt->bindParam(":lastname", $this->lastname);
		$stmt->bindParam(":username", $this->username);
		$stmt->bindParam(":mobile", $this->mobile);
		$stmt->bindParam(":password", $this->password);
		// $stmt->bindParam(":created",$this->created);
		
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

	//check if given username exist in the database
	function usernameExists(){

		// query to check if username exists
		$query = "SELECT fakeid,firstname,lastname,mobile,password FROM ". $this->table_name ." WHERE username = ? LIMIT 0,1";

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
			$this->fakeid = $row['fakeid'];
			$this->firstname = $row['firstname'];
			$this->lastname = $row['lastname'];
			$this->mobile = $row['mobile'];
			$this->password = $row['password'];
	 
			// return true because email exists in the database
			
			return true;
		}
	 
		// return false if email does not exist in the database
		return false;
	}

	//update fakeuser
	function update(){
		//query to update record
		$query = "UPDATE 
		". $this->table_name ." 
		SET firstname=:firstname,lastname=:lastname,username=:username,mobile=:mobile,password=:password WHERE fakeid=:fakeid";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->mobile=htmlspecialchars(strip_tags($this->mobile));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->fakeid=htmlspecialchars(strip_tags($this->fakeid));
		
	
		// bind values
		$stmt->bindParam(":firstname", $this->firstname);
		$stmt->bindParam(":lastname", $this->lastname);
		$stmt->bindParam(":username", $this->username);
		$stmt->bindParam(":mobile", $this->mobile);
		$stmt->bindParam(":password", $this->password);	
		$stmt->bindParam(":fakeid",$this->fakeid);
		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		return false;


	}


}

?>