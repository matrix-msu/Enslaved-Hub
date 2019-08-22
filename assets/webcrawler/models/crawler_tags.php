<?php

class crawler_tags {

    var $host;
	var $user;
	var $dbName;
	var $password;

	public function __construct() {
		$this->host = Host;
		$this->user = Username;
		$this->dbName = DBName;
		$this->password = Password;
	}

	public function connect(){
		$link = mysqli_connect($this->host, $this->user, $this->password, $this->dbName);

		if (mysqli_connect_errno())
			echo "Failed to connect to MySQL: " . mysqli_connect_error();

		return $link;
	}

	public function get_tags(){
		$link = $this->connect();
		$query = "SELECT id, tag_name FROM crawler_tags";
		$result = mysqli_query($link, $query);

		mysqli_close($link);

		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
}

?>
