<?php

// require_once("config.php");

class crawler_seeds {

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
			echo "Failed to connect to MySQL: " . mysqli_connect_error();

		return $con;
	}

	// fetch LIMIT number of keywords starting from OFFSET
	public function get_seeds($limit, $offset)
	{
		$link = $this->connect();
		$query = "SELECT * FROM crawler_seeds LIMIT ".$limit." OFFSET ".$offset;
		$result = mysqli_query($link, $query);
		mysqli_close($link);
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}

	public function update_seed_info($seed_id, $name , $title, $rss, $url, $twitter)
	{
		$conn=$this->connect();
		$query = "UPDATE crawler_seeds set text_name='$name', title='$title', xmlURL='$rss', htmlURL='$url', twitter_handle='$twitter' WHERE id='$seed_id' ";
		$conn->query($query);
		mysqli_close($conn);
	}

	public function delete_seed_info($seed_id)
	{
		$conn=$this->connect();
		$query = "DELETE FROM crawler_seeds WHERE id='$seed_id' ";
		$conn->query($query);
		mysqli_close($conn);
	}

	// update entry from crawler_seeds this belongs to broken links
	public function update_seeds($old_link,$new_link)
	{
		 $conn=$this->connect();
		$query = "UPDATE crawler_seeds set htmlURL='$new_link' WHERE htmlURL='$old_link' ";
		$result=$conn->query($query);
		mysqli_close($conn);
	}
	//delete entry from crawler_seeds this belongs to broken links
	public function delete_seeds($link)
	{
		 $conn=$this->connect();
		$query = "DELETE FROM crawler_seeds WHERE htmlURL='$link'";
		$result=$conn->query($query);
		mysqli_close($conn);
	}
	//delete entry from broken_links this belongs to broken links
  public function delete_broken_links($link)
  {
	  $conn=$this->connect();
		$query = "DELETE FROM broken_links WHERE link_url='$link'";
		$result=$conn->query($query);
		mysqli_close($conn);
  }


	public function get_count()
	{
		$conn = $this->connect();
		$query = "SELECT * FROM crawler_seeds";
		$result = $conn->query($query);
		mysqli_close($conn);
		return $result->num_rows;
	}

	public function add_seed($link)
	{
		$conn = $this->connect();
		$query = "INSERT INTO crawler_seeds (htmlURL) VALUES ('$link')";
		$result = $conn->query($query);
		mysqli_close($conn);
	}

}

?>
