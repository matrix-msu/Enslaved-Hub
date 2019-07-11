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
		$con=mysqli_connect($this->host,$this->user,$this->password,$this->dbName);

		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		return $con;
}
	// fetch LIMIT number of keywords starting from OFFSET
	public function get_seeds($limit,$offset)
	{
		$conn=$this->connect();
		$query = "SELECT * FROM ppj_seeds LIMIT ".$limit." OFFSET ".$offset;
		$result=$conn->query($query);
		mysqli_close($conn);
		if($result->num_rows >0){
		$texty='';
		$i=0;
			while($row = $result->fetch_array())
			{$xd=$offset+$i;
			if(substr($row['htmlURL'],-1)=="/")
				$row['htmlURL']=substr($row['htmlURL'],0,-1);
			if(substr($row['xmlURL'],-1)=="/")
				$row['xmlURL']=substr($row['xmlURL'],0,-1);
			$texty.='<div class="resultSeeds" >
		        <div class="rowSeed">
			        <span class="labelInput">Name:</span>
			        <div contenteditable="true" class="nameWeb" id="nm'.$row['id'].'">'.$row['text_name'].'</div>
			        <span class="labelInput title">Title:</span>
			        <div contenteditable="true" class="titleWeb"id="tt'.$row['id'].'">'.$row['title'].'</div>
		        </div>
				<div class="rowSeed">
			        <span class="labelInput">URL:</span>
			        <div contenteditable="true" class="urlSeed" id="ur'.$row['id'].'"><p>'.$row['htmlURL'].'</p></div>
				</div>
				<div class="rowSeed">
			        <span class="labelInput">Twitter:</span>
			        <div contenteditable="true" class="twitterWeb"id="tw'.$row['id'].'">'.$row['twitter_handle'].'</div>
			        <span class="labelInput rss">RSS:</span>
			        <div contenteditable="true" class="rssSeed" id="rs'.$row['id'].'">'.$row['xmlURL'].'</div>
		        </div>
		        <input type="button" class="update" value="UPDATE" id="us'.$row['id'].'">
				<input type="button" class="delete" value="DELETE" id="ds'.$row['id'].'">
	        </div>';
			$i++;
			}
		return $texty;
		}
		else return "no more data";
	}

	public function update_seed_info($seed_id, $name , $title, $rss, $url, $twitter)
	{
		$conn=$this->connect();
		$query = "UPDATE ppj_seeds set text_name='$name', title='$title', xmlURL='$rss', htmlURL='$url', twitter_handle='$twitter' WHERE id='$seed_id' ";
		$conn->query($query);
		mysqli_close($conn);
	}

	public function delete_seed_info($seed_id)
	{
		$conn=$this->connect();
		$query = "DELETE FROM ppj_seeds WHERE id='$seed_id' ";
		$conn->query($query);
		mysqli_close($conn);
	}

	// update entry from ppj_seeds this belongs to broken links
	public function update_seeds($old_link,$new_link)
	{
		 $conn=$this->connect();
		$query = "UPDATE ppj_seeds set htmlURL='$new_link' WHERE htmlURL='$old_link' ";
		$result=$conn->query($query);
		mysqli_close($conn);
	}
	//delete entry from ppj_seeds this belongs to broken links
	public function delete_seeds($link)
	{
		 $conn=$this->connect();
		$query = "DELETE FROM ppj_seeds WHERE htmlURL='$link'";
		$result=$conn->query($query);
		mysqli_close($conn);
	}
	//delete entry from ppj_broken_links this belongs to broken links
  public function delete_broken_links($link)
  {
	  $conn=$this->connect();
		$query = "DELETE FROM ppj_broken_links WHERE link_url='$link'";
		$result=$conn->query($query);
		mysqli_close($conn);
  }



}

?>