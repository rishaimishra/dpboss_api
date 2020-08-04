<?php

class Admin{

	//database connection and table name
	private $conn;
	private $table_name="admin";

	//object properties
	public $admin_id;
	public $admin_email;
	public $admin_name;
	public $admin_password;
	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 
    
    //read fakeuser
    function read(){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY admin_id";

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
		(admin_email,admin_name,admin_password) VALUES (:admin_email,:admin_name,:admin_password)";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->admin_email=htmlspecialchars(strip_tags($this->admin_email));
        $this->admin_name=htmlspecialchars(strip_tags($this->admin_name));
        $this->admin_password=htmlspecialchars(strip_tags($this->admin_password));
        
		
	
		// bind values
		$stmt->bindParam(":admin_email", $this->admin_email);
		$stmt->bindParam(":admin_name", $this->admin_name);
		$stmt->bindParam(":admin_password", $this->admin_password);
		
		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		return false;


	}
	//delete fakeuser
	function delete(){
		//delete query
		$query= "DELETE FROM ". $this->table_name ." WHERE admin_id= ? ";

		//prepare query
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->id=htmlspecialchars(strip_tags($this->admin_id));
	
		//bind id of record to delete
		$stmt->bindParam(1,$this->id);
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}

	//check if given email exist in the database
	function emailExists(){

		// query to check if email exists
		$query = "SELECT admin_id,admin_name,admin_password FROM ". $this->table_name ." WHERE admin_email = ? LIMIT 0,1";

		// prepare the query
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->admin_email=htmlspecialchars(strip_tags($this->admin_email));

		// bind given email value
		$stmt->bindParam(1, $this->admin_email);
 
		// execute the query
		$stmt->execute();
	
		// get number of rows
		$num = $stmt->rowCount();
	
		 // if email exists, assign values to object properties for easy access and use for php sessions
		 if($num>0){
 
			// get record details / values
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
			// assign values to object properties
			$this->admin_id = $row['admin_id'];
			$this->admin_name = $row['admin_name'];
			$this->admin_password = $row['admin_password'];
	 
			// return true because email exists in the database
			
			return true;
		}
	 
		// return false if email does not exist in the database
		return false;
	}
}

?>