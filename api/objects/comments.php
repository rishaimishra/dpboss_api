<?php
ini_set("display_errors",1);
class Comments{

	//database connection and table name
	private $conn;
	private $table_name="comments";

	//object properties
	public $comment_id;
	public $quote;
    public $post_id;
    public $user_id;
    public $publication_status;

	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 

    //read posts
    function read(){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY comment_id";

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
		(quote,post_id,user_id) VALUES (:quote,:post_id,:user_id)";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->quote=htmlspecialchars(strip_tags($this->quote));
        $this->post_id=htmlspecialchars(strip_tags($this->post_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
       
      
		
	
		// bind values
		$stmt->bindParam(":quote", $this->quote);
		$stmt->bindParam(":post_id", $this->post_id);
		$stmt->bindParam(":user_id", $this->user_id);
	
	
		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		return false;


	}

	function delete(){
		//delete query
		$query= "DELETE FROM ". $this->table_name ." WHERE comment_id=? ";

		//prepare query
		$stmt = $this->conn->prepare($query);

		//sanitize
		$this->comment_id=htmlspecialchars(strip_tags($this->comment_id));
	
		//bind comment_id of record to delete
		$stmt->bindParam(1,$this->comment_id);
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}

//Multiple Delete Comments
	function MultiDelete(){
		// echo "<pre>";
		// print_r($this->comment_id);die;
		// $ids=implode("','",$this->comment_id);

		// $all_id=(array)$this->comment_id;
		
		// echo array_count_values(".$ids.");
		//delete query
		$query= "DELETE FROM ". $this->table_name ." WHERE comment_id IN ( " . $this->comment_id[0] . ") ";

		//prepare query
		$stmt = $this->conn->prepare($query);

	
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}


	//update Comments
	function update(){
		//query to update record
		$query = "UPDATE 
		". $this->table_name ." 
		SET quote=:quote,post_id=:post_id,user_id=:user_id WHERE comment_id=:comment_id";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->quote=htmlspecialchars(strip_tags($this->quote));
        $this->post_id=htmlspecialchars(strip_tags($this->post_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->comment_id=htmlspecialchars(strip_tags($this->comment_id));
		
	
		// bind values
		$stmt->bindParam(":quote", $this->quote);
		$stmt->bindParam(":post_id", $this->post_id);
		$stmt->bindParam(":user_id", $this->user_id);
		$stmt->bindParam(":comment_id",$this->comment_id);
		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		return false;


	}


}