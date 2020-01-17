<?php

class crawler_broken_links {
    var $host;
	var $user;
	var $dbName;
	var $password;
	
    //constrctor function to initialize variables
	public function __construct()
    {

		$this->host=Host;
		$this->user=Username;
		$this->dbName=DBName;
		$this->password=Password;
	}
	
    // connect to data base
	public function connect()
    {
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
		$query = 
            "SELECT 
                DISTINCT link_url, 
                error_code 
            FROM broken_links 
            LIMIT ? OFFSET ?
        ";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($link);

		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	
    // update entry from crawler_seeds
	public function update_seeds($old_link, $new_link)
	{
		$conn = $this->connect();
		$query = "UPDATE crawler_seeds set htmlURL=? WHERE htmlURL=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("ss", $new_link, $old_link);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($conn);
	}
	
    //delete entry from crawler_seeds
	public function delete_seeds($broken_link)
	{
		$link = $this->connect();
		$query = "DELETE FROM crawler_seeds WHERE htmlURL=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("s", $broken_link);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($link);
	}
	
    //delete entry from broken_links
	public function delete_broken_links($broken_link)
    {
		$link = $this->connect();
		$query = "DELETE FROM broken_links WHERE link_url=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("s", $broken_link);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($link);
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
