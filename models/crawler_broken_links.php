<?php

// require_once("config.php");


class crawler_broken_links {

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
		$con = mysqli_connect($this->host,$this->user,$this->password,$this->dbName);

		// Check connection
		if (mysqli_connect_errno())
			echo("Failed to connect to MySQL: " . mysqli_connect_error());
		return $con;
	}
	// fetch LIMIT number of keywords starting from OFFSET
	public function get_broken_links($limit, $offset)
	{
		$link = $this->connect();
		$query = "SELECT DISTINCT link_url, error_code FROM broken_links LIMIT ".$limit." OFFSET ".$offset;
		$result = mysqli_query($link, $query);

		mysqli_close($link);

		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	// update entry from crawler_seeds
	public function update_seeds($old_link,$new_link)
	{
		$conn=$this->connect();
		$query = "UPDATE crawler_seeds set htmlURL='$new_link' WHERE htmlURL='$old_link' ";
		$result=$conn->query($query);
		mysqli_close($conn);
	}
	//delete entry from crawler_seeds
	public function delete_seeds($link)
	{
		$conn=$this->connect();
		$query = "DELETE FROM crawler_seeds WHERE htmlURL='$link'";
		$result=$conn->query($query);
		mysqli_close($conn);
	}
	//delete entry from broken_links
	public function delete_broken_links($link)
	{
		$conn=$this->connect();
		$query = "DELETE FROM broken_links WHERE link_url='$link'";
		$result=$conn->query($query);
		mysqli_close($conn);
	}

	public function get_count()
	{
		$conn=$this->connect();
		$query = "SELECT DISTINCT link_url, error_code FROM broken_links";
		$result=$conn->query($query);
		mysqli_close($conn);
		return $result->num_rows;
	}

}

?>
