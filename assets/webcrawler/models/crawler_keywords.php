<?php

// require_once("config.php");

class crawler_keywords {

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
	// fetch LIMIT number of keywords starting from OFFSET
	public function get_keywords($limit, $offset)
	{
		$conn=$this->connect();
		$query = "SELECT DISTINCT keyword, url FROM ppj_crawler_keywords WHERE NOT EXISTS (SELECT keyword FROM ppj_deleted_keywords WHERE ppj_crawler_keywords.keyword = ppj_deleted_keywords.keyword) LIMIT ".$limit." OFFSET ".$offset;
		$result=$conn->query($query);
		mysqli_close($conn);
		if($result->num_rows >0){
			$texty='';
			$i=0;
			while($row = $result->fetch_array())
			{
				$xd=$offset+$i;
				if(substr($row['url'],-1)=="/")
					$row['url']=substr($row['url'],0,-1);
				$texty .= <<<HTML
<div class="result" id="r$xd">
	<div class="link-name">
		<a href="https://www.google.com/search?hl=en&num=100&q=$row[keyword]" target="_blank">$row[keyword]</a>
	</div>
	<div class="link-wrap">
		<a class="link" target="_blank" href="$row[url]">$row[url]</a>
		<div class="trash crawler-modal-open" id="delete-link">
			<img class="trash-icon" src="./assets/images/Delete.svg">
		</div>
	</div>
</div>
HTML;
// 				$texty.='<div class="result" id=r'.$xd.'><div class="keywordWeb" id="k'.$xd.'"><a href="https://www.google.com/search?hl=en&num=100&q='.$row['keyword'].'" target="_blank">'.$row['keyword'].'</a> </div> <div class="linkWeb" contentEditable="false"><p><a target="_blank" href='.$row['url'].
// '>'.$row['url'].'</a></p> </div> <input type="button" class="delete" value="DELETE" id='.$xd.'>   </div>';
				$i++;
			}
		return $texty;
		}
		else return "no more data";
	}

// get keywords with dates
public function get_keywords_date($limit, $offset, $cur_date)
	{
		$date=date_format(date_create($cur_date),"Y-m-d");
		$conn=$this->connect();
		$query = "SELECT DISTINCT keyword, url FROM ppj_crawler_keywords WHERE NOT EXISTS (SELECT keyword FROM ppj_deleted_keywords WHERE ppj_crawler_keywords.keyword = ppj_deleted_keywords.keyword) and keyword_date='$date' LIMIT ".$limit." OFFSET ".$offset;
		$result=$conn->query($query);
		mysqli_close($conn);
		if($result->num_rows >0){
		$texty='';
		$i=0;
			while($row = $result->fetch_array())
			{$xd=$offset+$i;
			if(substr($row['url'],-1)=="/")
				$row['url']=substr($row['url'],0,-1);
			$texty.='<div class="result" id=r'.$xd.'><div class="keywordWeb" id="k'.$xd.'"><a href="https://www.google.com/search?hl=en&num=100&q='.$row['keyword'].'" target="_blank">'.$row['keyword'].'</a> </div> <div class="linkWeb" contentEditable="false"><p><a target="_blank" href='.$row['url'].
'>'.$row['url'].'</a></p> </div> <input type="button" class="delete" value="DELETE" id='.$xd.'>   </div>';
			$i++;
			}
		return $texty;
		}
		else return "no more data";
	}
// get unique dates from database
public function get_dates()
	{
		$conn=$this->connect();
		$query = "SELECT DISTINCT keyword_date from ppj_crawler_keywords order by keyword_date desc;";
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


}

?>