<?php
ini_set("display_errors",1);
class Posts{

	//database connection and table name
	private $conn;
	private $table_name="posts";

	//object properties
	public $post_id;
	public $body;
	public $author;
    public $fakeuser;
    public $admin_id;
    public $subadmin_id;
    public $repost_id;
	public $post_time;

	

	//constructor with $db as database connection
	public function __construct($db){
		$this->conn=$db;
    } 

    //read posts
    function read(){
        $query = "SELECT * FROM ". $this->table_name ." ORDER BY post_id";

        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //execute query
        $stmt->execute();
        return $stmt;
    }

    //create Post
	function create(){


if(!empty($this->body) && !empty($this->author)){


    //query to insert record
$query = "INSERT INTO 
". $this->table_name ." 
(body,author) VALUES (:body,:author)" ;

//prepare query statement
$stmt = $this->conn->prepare($query);
	
// sanitize
$this->body=htmlspecialchars(strip_tags($this->body));
$this->author=htmlspecialchars(strip_tags($this->author));

// bind values
$stmt->bindParam(":author", $this->author);
$stmt->bindParam(":body", $this->body);
}


elseif(!empty($this->body) && !empty($this->fakeuser)){
//query to insert record
$query = "INSERT INTO 
". $this->table_name ." 
(body,fakeuser) VALUES (:body,:fakeuser)";

//prepare query statement
$stmt = $this->conn->prepare($query);
	
// sanitize
$this->body=htmlspecialchars(strip_tags($this->body));
$this->fakeuser=htmlspecialchars(strip_tags($this->fakeuser));

// bind values
$stmt->bindParam(":fakeuser", $this->fakeuser);
$stmt->bindParam(":body", $this->body);

}


elseif(!empty($this->body) && !empty($this->admin_id)){
//query to insert record
$query = "INSERT INTO 
". $this->table_name ." 
(body,admin_id) VALUES (:body,:admin_id)";

//prepare query statement
$stmt = $this->conn->prepare($query);
	
// sanitize
$this->body=htmlspecialchars(strip_tags($this->body));
$this->admin_id=htmlspecialchars(strip_tags($this->admin_id));

// bind values
$stmt->bindParam(":admin_id", $this->admin_id);
$stmt->bindParam(":body", $this->body);

}


elseif(!empty($this->body) && !empty($this->subadmin_id)){
//query to insert record
$query = "INSERT INTO 
". $this->table_name ." 
(body,subadmin_id) VALUES (:body,:subadmin_id)";

//prepare query statement
$stmt = $this->conn->prepare($query);
	
// sanitize
$this->body=htmlspecialchars(strip_tags($this->body));
$this->subadmin_id=htmlspecialchars(strip_tags($this->subadmin_id));

// bind values
$stmt->bindParam(":subadmin_id", $this->subadmin_id);
$stmt->bindParam(":body", $this->body);

}




 	// execute query
		if($stmt->execute()){
			
			return true;
		}
		
		return false;

    }
    
	

	//update fakeuser
	function update(){
	
	
		//query to update record
		if(!empty($this->body)&&
		 !empty($this->author)&&
		  !empty($this->post_id)){
			$query = "UPDATE 
		". $this->table_name ." 
		SET body=:body,author=:author WHERE post_id=:post_id";

		

		//prepare query statement
		$stmt = $this->conn->prepare($query);
		
	
	
        // sanitize
        
        $this->body=htmlspecialchars(strip_tags($this->body));
		$this->author=htmlspecialchars(strip_tags($this->author));
		$this->post_id=htmlspecialchars(strip_tags($this->post_id));


	
		
		// bind values
		$stmt->bindParam(":post_id", $this->post_id);
		$stmt->bindParam(":body", $this->body);
		$stmt->bindParam(":author", $this->author);

		
	

		}else if(!empty($this->body) && !empty($this->fakeuser) && !empty($this->post_id)){
			$query = "UPDATE 
		". $this->table_name ." 
		SET body=:body,fakeuser=:fakeuser WHERE post_id=:post_id";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
		// sanitize
		$stmt->bindParam(":post_id", $this->post_id);
        $this->body=htmlspecialchars(strip_tags($this->body));
		$this->fakeuser=htmlspecialchars(strip_tags($this->fakeuser));
		
		// bind values
		$stmt->bindParam(":post_id", $this->post_id);
		$stmt->bindParam(":body", $this->body);
		$stmt->bindParam(":fakeuser", $this->fakeuser);
		}
		else if(!empty($this->body) && !empty($this->admin_id) && !empty($this->post_id)){
			$query = "UPDATE 
		". $this->table_name ." 
		SET body=:body,admin_id=:admin_id WHERE post_id=:post_id";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->body=htmlspecialchars(strip_tags($this->body));
		$this->admin_id=htmlspecialchars(strip_tags($this->admin_id));
		
		// bind values
		$stmt->bindParam(":body", $this->body);
		$stmt->bindParam(":admin_id", $this->admin_id);
		}


		else if(!empty($this->body) && !empty($this->subadmin_id) && !empty($this->post_id)){
			$query = "UPDATE 
		". $this->table_name ." 
		SET body=:body,subadmin_id=:subadmin_id WHERE post_id=:post_id";

		//prepare query statement
        $stmt = $this->conn->prepare($query);
	
        // sanitize
        $this->body=htmlspecialchars(strip_tags($this->body));
		$this->subadmin_id=htmlspecialchars(strip_tags($this->subadmin_id));
		
		// bind values
		$stmt->bindParam(":body", $this->body);
		$stmt->bindParam(":subadmin_id", $this->subadmin_id);
		}
		

		
		
		// execute query
		if($stmt->execute()){
			
			return true;
		}
		return false;


	}

	//Delete Post
    function delete(){
	

		// Try to delete all the childrens first
		$res = $this->conn->query("SELECT comment_id FROM comments WHERE post_id=". $this->post_id ."");
	   //  print_r($res->fetch(PDO::FETCH_ASSOC));
		while($row = $res->fetch(PDO::FETCH_ASSOC)){
		   echo $row['comment_id'];
		   $query= "DELETE FROM comments WHERE comment_id IN ( " . $row['comment_id'] . ") ";
		   $stmt = $this->conn->prepare($query);
		   $stmt->execute();
		}

		
	   //delete query
	   $query1= "DELETE FROM ". $this->table_name ." WHERE post_id =". $this->post_id ." ";

	   echo $query1;

	   //prepare query
	   $stmt1 = $this->conn->prepare($query1);

   
   
	   //execute query
	   if($stmt1->execute()){
	   
		   return true;
	   }
	   return false;
   }


	//Multiple Delete Posts along with comments
	function MultiDelete(){
		
		$post=""; 
		foreach($this->post_id as $var){
			
			echo $var." suravi ";
			$post = explode(',',$var);
			print_r($post);
			for ($x = 0; $x < count($post); $x++) {
				$newPost_id="".$post[$x]."";
				
}
			
			  $ids=implode(',',$post);
		}

		 // Try to delete all the childrens first
		 $res = $this->conn->query("SELECT comment_id FROM comments WHERE post_id IN (" .$ids. ")");
		 var_dump($res);
		 
		
		 while($row = $res->fetch(PDO::FETCH_ASSOC)){
		
			$query= "DELETE FROM comments WHERE comment_id IN ( " . $row['comment_id'] . ") ";
			var_dump($query);
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
		 }
	
		// delete query
		$query= "DELETE FROM ". $this->table_name ." WHERE post_id IN ( " . $this->post_id[0] . ") ";

	
	
		//prepare query
		$stmt = $this->conn->prepare($query);

	
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}




	//Multiple Delete Posts along with comments using date interval
	function DeleteByTime(){
		
		print_r($this->strt_time);
		print_r($this->end_time);
	
		
	
		// Try to delete all the childrens first
		$res = $this->conn->query("SELECT post_id FROM ". $this->table_name ." WHERE post_time BETWEEN '$this->strt_time' and '$this->end_time' ");
		print_r($res);
		

		while($row = $res->fetch(PDO::FETCH_ASSOC)){
		
			$query= "DELETE FROM comments WHERE post_id IN ( " . $row['post_id'] . ") ";
			var_dump($query);
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
		 }
	
			 
		 // delete query
		$query= "DELETE FROM ". $this->table_name ." WHERE post_time BETWEEN '$this->strt_time' and '$this->end_time' ";
	
		//prepare query
		$stmt = $this->conn->prepare($query);

	
	
		//execute query
		if($stmt->execute()){
		
			return true;
		}
		return false;
	}



    
}
?>