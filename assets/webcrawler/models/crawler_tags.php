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
		$query = "SELECT tag_id, tag_name FROM crawler_tags";
		$result = mysqli_query($link, $query);

		mysqli_close($link);

		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}

	public function get_tag_name_per_keyword_ids($ids){
		$link = $this->connect();
		$assoc = [];
		foreach ($ids as $id) {
			$query = "SELECT ct.tag_id, ct.tag_name FROM crawler_tags ct INNER JOIN crawler_keyword_tags_assoc ckta ON
			ct.tag_id = ckta.tag_id WHERE ckta.keyword_id =" . $id;
			$result = mysqli_query($link, $query);
			$assoc[$id] = mysqli_fetch_all($result, MYSQLI_ASSOC);
			mysqli_free_result($result);
		}

		mysqli_close($link);

		return $assoc;
	}

	public function update_keyword_tags($keywordId, $tagIds){
		$link = $this->connect();

		// Assume failure
		$result = 'failed';

		if ($stmt = mysqli_prepare($link, "DELETE FROM crawler_keyword_tags_assoc WHERE keyword_id=?")) {
			mysqli_stmt_bind_param($stmt, "s", $keywordId);
    		mysqli_stmt_execute($stmt);
    		mysqli_stmt_close($stmt);
		} else {
			return $result;
		}

		// TODO::refactor to prepared statement
		$stmt = "INSERT INTO crawler_keyword_tags_assoc(keyword_id, tag_id) VALUES ";
		for ($i=0; $i < count($tagIds); $i++) {
			$stmt .= "(".$keywordId.", ".$tagIds[$i].")";
			if($i != count($tagIds) - 1) {
				$stmt .= ', ';
			}
		}

		if(mysqli_query($link, $stmt) === TRUE){
			$result = 'success';
		}

		mysqli_close($link);

		return $result;
	}
}

?>