<?php

class Chats{

	//database connection and table name
	private $conn;
	private $table_name="admin_question";

	//object properties
	public $admin_question_id;
	public $admin_id;
	public $user_id;
    public $question;
    public $answer;
    public $date;
    public $admin_query_status;
	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 



     //create query
	function create(){

        $query1 = "SELECT id FROM myguests inner join devicestatus on myguests.username=devicestatus.username where devicestatus.device_id='".$this->device_id."' ";
        
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->execute();
        
        $row = $stmt1->fetch(PDO::FETCH_ASSOC);
        // echo $this->device_id;die;
        
		//query to insert record
		$query = "INSERT INTO 
		". $this->table_name ." 
        (date,question,user_id,admin_id) VALUES ('".$this->date."','".$this->question."','".$row['id']."','1')";
        

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->date=htmlspecialchars(strip_tags($this->date));
        $this->question=htmlspecialchars(strip_tags($this->question));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
       
      
		
	
		// bind values
		$stmt->bindParam(":date", $this->date);
		$stmt->bindParam(":question", $this->question);
		$stmt->bindParam(":user_id", $this->user_id);
	
	
		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		return false;


    }
    
     //read one myuser
    function readProfileChats(){
  
        $query = "SELECT * FROM ". $this->table_name ." inner join myguests on admin_question.user_id=myguests.id 
        inner join devicestatus on devicestatus.username=myguests.username
        WHERE devicestatus.device_id ='".$this->device_id."' AND myguests.status=1";

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