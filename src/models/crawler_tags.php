<?php

class crawler_tags {

    var $host;
	var $user;
	var $dbName;
	var $password;

	public function __construct()
    {
		$this->host = Host;
		$this->user = Username;
		$this->dbName = DBName;
		$this->password = Password;
	}

	public function connect()
    {
		$link = mysqli_connect($this->host, $this->user, $this->password, $this->dbName);

		if (mysqli_connect_errno())
			echo "Failed to connect to MySQL: " . mysqli_connect_error();

		return $link;
	}

	public function get_tags()
    {
		$link = $this->connect();
		$query = "SELECT tag_id, tag_name FROM crawler_tags";
		$result = mysqli_query($link, $query);

		mysqli_close($link);

		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}

	public function get_tag_name_per_keyword_ids($ids)
    {
		$link = $this->connect();
		$assoc = [];
		foreach ($ids as $id) {
			$query =
                "SELECT
                    ct.tag_id,
                    ct.tag_name
                    FROM
                        crawler_tags ct
                        INNER JOIN crawler_keyword_tags_assoc ckta ON ct.tag_id = ckta.tag_id
                    WHERE
                        ckta.keyword_id = ?
            ";

            $stmt = mysqli_prepare($link, $query);

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

			$assoc[$id] = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		mysqli_close($link);

		return $assoc;
	}

	public function update_keyword_tags($keywordId, $tagIds)
    {
		$link = $this->connect();

		if ($stmt = mysqli_prepare($link, "DELETE FROM crawler_keyword_tags_assoc WHERE keyword_id=?")) {
			mysqli_stmt_bind_param($stmt, "s", $keywordId);
    		mysqli_stmt_execute($stmt);
    		mysqli_stmt_close($stmt);
		}

		$types = $values = '';
        $params = [];

        for ($i=0; $i < count($tagIds); $i++) {
            array_push($params, $keywordId);
            array_push($params, $tagIds[$i]);

            $types .= 'ss';
            $values .= '(?, ?)';
            if ($i < count($tagIds) - 1)
                $values .= ', ';
        }

        if ($stmt = mysqli_prepare($link, "INSERT INTO crawler_keyword_tags_assoc(keyword_id, tag_id) VALUES " . $values)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
    		mysqli_stmt_close($stmt);
        }

		mysqli_close($link);
	}

    public function get_count()
    {
        $link = $this->connect();
        $query = "SELECT * FROM crawler_tags";
        $result = $link->query($query);
        mysqli_close($link);
        return $result->num_rows;
    }

    //adding seed for URL with Name/Title fields automatically updated
    public function add_tag($name)
    {
        $link = $this->connect();
        $query = "SELECT * FROM crawler_tags WHERE tag_name = '$name'";
        $validationResult = $link->query($query);
        //insert seed only if url not duplicate
        if( !$validationResult || mysqli_num_rows($validationResult) == 0 ){
            if ($stmt = mysqli_prepare($link, "INSERT IGNORE INTO crawler_tags (tag_name) VALUES ( ? )")) {
                mysqli_stmt_bind_param($stmt, "s", $name);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        mysqli_close($link);
        }
    }

    public function update_tag($id, $new_name)
    {
        $link = $this->connect();
        $query = "UPDATE crawler_tags set tag_name=? WHERE tag_id=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("ss", $new_name, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        mysqli_close($link);
    }

    public function delete_tag($id, $name)
    {
        $link=$this->connect();
        $query = "DELETE FROM crawler_tags WHERE tag_name=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("s", $name);
        $stmt->execute();

        if ($stmt = mysqli_prepare($link, "DELETE FROM crawler_keyword_tags_assoc WHERE tag_id=?")) {
			mysqli_stmt_bind_param($stmt, "s", $id);
    		mysqli_stmt_execute($stmt);
    		mysqli_stmt_close($stmt);
		}

        $result = $stmt->get_result();
        $stmt->close();
        mysqli_close($link);
    }
}

?>
