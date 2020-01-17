<?php

class crawler_seeds {

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
		$query = "SELECT * FROM crawler_seeds LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        mysqli_close($link);
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}

	public function get_all_urls()
	{
		$link = $this->connect();
		$query = "SELECT htmlURL FROM crawler_seeds";
		$result = mysqli_query($link, $query);
		mysqli_close($link);
		for ($set = array(); $row = $result->fetch_assoc(); $set[] = $row['htmlURL']);
		mysqli_free_result($result);
		return $set;
	}

	public function update_seed_info($seed_id, $name , $title, $rss, $url, $twitter)
	{
		$link=$this->connect();
		$query = "UPDATE crawler_seeds set text_name=?, title=?, xmlURL=?, htmlURL=?, twitter_handle=? WHERE id=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("ssssss", $name, $title, $rss, $url, $twitter, $seed_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($link);
	}

	public function delete_seed_info($seed_id)
	{
		$link = $this->connect();
		$query = "DELETE FROM crawler_seeds WHERE id=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("s", $seed_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($link);
	}

	// update entry from crawler_seeds this belongs to broken links
	public function update_seeds($old_link, $new_link)
	{
		$link = $this->connect();
		$query = "UPDATE crawler_seeds set htmlURL=? WHERE htmlURL=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("ss", $new_link, $old_link);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($link);
	}
	
    //delete entry from crawler_seeds this belongs to broken links
	public function delete_seeds($broken_link)
	{
		$link=$this->connect();
		$query = "DELETE FROM crawler_seeds WHERE htmlURL=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("s", $broken_link);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($link);
	}
	
    //delete entry from broken_links this belongs to broken links
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
		$link = $this->connect();
		$query = "SELECT * FROM crawler_seeds";
		$result = $link->query($query);
		mysqli_close($link);
		return $result->num_rows;
	}
    
    //adding seed for URL with Name/Title fields automatically updated
    public function add_seed($name, $title, $url)
    {
        $link = $this->connect();
        $query = "SELECT * FROM crawler_seeds WHERE htmlURL = '$url'";
        $validationResult = $link->query($query);
        //insert seed only if url not duplicate
        if( !$validationResult || mysqli_num_rows($validationResult) == 0 ){
            if ($stmt = mysqli_prepare($link, "INSERT INTO crawler_seeds (text_name, title, htmlURL) VALUES ( ?, ?, ? )")) {
                mysqli_stmt_bind_param($stmt, "sss", $name, $title, $url);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        mysqli_close($link);
        }
    }

}

?>
