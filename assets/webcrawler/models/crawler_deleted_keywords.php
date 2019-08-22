<?php

// require_once("config.php");

class crawler_deleted_keywords {

    var $host;
	var $user;
	var $dbName;
	var $password;
	//constrctor function to initialize variables
	public function __construct(  ) {

		$this->host=Host;
		$this->user=Username;
		$this->dbName=DBName;
		$this->password=Password;
	}
	// connect to data base
	public function connect(){
		// Create connection
		$con=mysqli_connect($this->host,$this->user,$this->password,$this->dbName);

		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		return $con;
	}
	//delete a keyword
	public function add_to_deleted ($deleted_keyword)
	{
		$conn = $this->connect();
		// $tmp = $conn->query("SELECT * FROM deleted_keywords where keyword=".$deleted_keyword);
		// if($tmp->num_rows == 0)
			$conn->query("INSERT IGNORE INTO deleted_keywords (keyword) VALUES ('$deleted_keyword')");
		mysqli_close($conn);
	}

}

?>
