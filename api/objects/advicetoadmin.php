<?php
ini_set("display_errors",1);
class Advicetoadmin{

	//database connection and table name
	private $conn;
	private $table_name="admin_advice";

	//object properties
	public $admin_advice_id;
	public $device_id;
    public $advice;
    public $advice_status;
	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 

    //read posts
    function read(){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY admin_advice_id";

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();
        return $stmt;
    }

    //create advice to admin
	function create(){
		//query to insert record
		$query = "INSERT INTO 
		". $this->table_name ." 
		(device_id,advice) VALUES (:device_id,:advice)";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->device_id=htmlspecialchars(strip_tags($this->device_id));
        $this->advice=htmlspecialchars(strip_tags($this->advice));
        
      
		
	
		// bind values
		$stmt->bindParam(":device_id", $this->device_id);
		$stmt->bindParam(":advice", $this->advice);
		
	
		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		return false;


	}

	
	

}