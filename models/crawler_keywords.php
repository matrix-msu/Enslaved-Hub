<?php

class crawler_keywords {

    var $host;
	var $user;
	var $dbName;
	var $password;

    //constrctor function to initialize variables
	public function __construct() {
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

    public function add_keyword($keyword, $url)
    {
        $link = $this->connect();
        if ($stmt = mysqli_prepare($link, "INSERT IGNORE INTO crawler_keywords (keyword, url) VALUES ( ?, ? )")) {
            mysqli_stmt_bind_param($stmt, "ss", $keyword, $url);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
    }

    // fetch LIMIT number of keywords starting from OFFSET
	public function get_keywords($limit, $offset, $sort, $terms='', $tagIds=[]){
        $sort = ($sort == "ASC") ? "ASC" : "DESC";

        $search = "";
        if ($terms) {
            $terms = "%$terms%";
            $search = " AND (ck.keyword LIKE ? OR ck.url LIKE ?)";
        }

        $filter = "";
        if (count($tagIds) > 0) {
            $filter = " AND ct.tag_id IN (" . implode(', ', $tagIds) . ")";
        }

		$link = $this->connect();

        $query =
            "SELECT
                ck.keyword_id,
                ck.keyword,
                ck.url
            FROM
                crawler_keywords ck
            LEFT JOIN crawler_keyword_tags_assoc ckta ON ck.keyword_id = ckta.keyword_id
            LEFT JOIN crawler_tags ct ON ct.tag_id = ckta.tag_id
            WHERE
                NOT EXISTS (
                    SELECT dk.keyword
                    FROM deleted_keywords dk
                    WHERE ck.keyword = dk.keyword
                )" . $search . $filter .
            " ORDER BY ck.date_created " . $sort .
            " LIMIT ? OFFSET ?
        ";

        $stmt = mysqli_prepare($link, $query);

        if ($terms)
            $stmt->bind_param("ssii", $terms, $terms, $limit, $offset);
        else
            $stmt->bind_param("ii", $limit, $offset);

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        mysqli_close($link);
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}

    // get keywords with dates
    public function get_keywords_date($limit, $offset, $cur_date) {
        $date = date_format(date_create($cur_date),"Y-m-d");

        $link = $this->connect();
        $query =
            "SELECT
                DISTINCT keyword,
                url
            FROM
                crawler_keywords
            WHERE
                NOT EXISTS (
                    SELECT keyword
                    FROM deleted_keywords
                    WHERE crawler_keywords.keyword = deleted_keywords.keyword
                )
                and keyword_date=?
            LIMIT ?
            OFFSET ?
        ";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("sii", $cur_date, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        mysqli_close($link);

        if($result->num_rows >0) {
            $texty = '';
            $i = 0;

            while($row = $result->fetch_array()) {
                $xd=$offset + $i;

                if(substr($row["url"],-1)=="/")
                    $row["url"] = substr($row["url"],0,-1);

                $texty .= "
                    <div class=\"result\" id=\"r$xd\">
                        <div class=\"keywordWeb\" id=\"k$xd\">
                            <a href=\"https://www.google.com/search?hl=en&num=100&q={$row['keyword']}\" target=\"_blank\">{$row['keyword']}</a>
                        </div>
                        <div class=\"linkWeb\" contentEditable=\"false\">
                            <p><a target=\"_blank\" href=\"{$row['url']}\">{$row['url']}</a></p>
                        </div>
                        <input type=\"button\" class=\"delete\" value=\"DELETE\" id=\"$xd\">
                    </div>
                ";

                $i++;
            }

            return $texty;

        } else {
            return "no more data";
        }
    }

	// get unique dates from database
	public function get_dates(){
		$conn=$this->connect();
		$query = "SELECT DISTINCT keyword_date from crawler_keywords order by keyword_date desc;";
		$result=$conn->query($query);
		mysqli_close($conn);
		if($result->num_rows >0){
			while($row = mysqli_fetch_assoc($result))
			{
			$res[]=$row;
			}
		return $res;
		}
		else return "no keywords";
	}

	public function get_count(){
		$conn=$this->connect();
		$query = "SELECT DISTINCT keyword, url FROM crawler_keywords WHERE NOT EXISTS (SELECT keyword FROM deleted_keywords WHERE crawler_keywords.keyword = deleted_keywords.keyword)";
		$result=$conn->query($query);
		mysqli_close($conn);
		return $result->num_rows;
	}

  public function update_keyword($keyword_id, $keyword){
    $link = $this->connect();
		$query = "UPDATE crawler_keywords set keyword=? WHERE keyword_id=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("si", $keyword, $keyword_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($conn);
  }
  public function update_link($keyword_id, $url){
    $link = $this->connect();
		$query = "UPDATE crawler_keywords set url=? WHERE keyword_id=?";
        $stmt = mysqli_prepare($link, $query);
        $stmt->bind_param("si", $url, $keyword_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
		mysqli_close($conn);
  }

    // fetch LIMIT number of keywords starting from OFFSET where visible
	public function get_keywords_visible($limit=12, $offset=0, $sort='desc', $terms='', $tagIds=[]){
		$search = $filter = "";
		if($terms)
			$search = " AND ck.keyword LIKE '%".$terms."%' OR ck.url LIKE '%".$terms."%'";
        if (count($tagIds) > 0)
			$filter = " AND ct.tag_id IN (" . implode(', ', $tagIds) . ")";
        $link = $this->connect();
        $query = "SELECT*FROM(SELECT ck.keyword_id,ck.keyword,ck.url,ck.date_created AS date_sort FROM crawler_keywords ck LEFT OUTER JOIN crawler_keyword_tags_assoc ckta ON ck.keyword_id=ckta.keyword_id LEFT JOIN crawler_tags ct ON ct.tag_id=ckta.tag_id WHERE ct.tag_name!='No Display' AND NOT EXISTS(SELECT dk.keyword FROM deleted_keywords dk WHERE ck.keyword=dk.keyword)".$search.$filter.")AS a UNION SELECT*FROM(SELECT ck.keyword_id,ck.keyword,ck.url,ck.date_created FROM crawler_keywords ck WHERE ck.keyword_id NOT IN(SELECT ckta.keyword_id FROM crawler_keyword_tags_assoc ckta)AND NOT EXISTS(SELECT dk.keyword FROM deleted_keywords dk WHERE ck.keyword=dk.keyword)".$search.")AS b ORDER BY date_sort ".$sort." LIMIT ".$limit." OFFSET ".$offset;
		$result = mysqli_query($link, $query);
		mysqli_close($link);
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}

    public function get_count_visible(){
		$conn=$this->connect();
        $query = "SELECT*FROM(SELECT ck.keyword_id,ck.keyword,ck.url,ck.date_created AS date_sort FROM crawler_keywords ck LEFT OUTER JOIN crawler_keyword_tags_assoc ckta ON ck.keyword_id=ckta.keyword_id LEFT JOIN crawler_tags ct ON ct.tag_id=ckta.tag_id WHERE ct.tag_name!='No Display' AND NOT EXISTS(SELECT dk.keyword FROM deleted_keywords dk WHERE ck.keyword=dk.keyword))AS a UNION SELECT*FROM(SELECT ck.keyword_id,ck.keyword,ck.url,ck.date_created FROM crawler_keywords ck WHERE ck.keyword_id NOT IN(SELECT ckta.keyword_id FROM crawler_keyword_tags_assoc ckta)AND NOT EXISTS(SELECT dk.keyword FROM deleted_keywords dk WHERE ck.keyword=dk.keyword))AS b";
		$result=$conn->query($query);
		mysqli_close($conn);
		return $result->num_rows;
	}

}

?>
