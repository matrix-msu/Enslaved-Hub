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
			{
				$xd=$offset+$i;
				if(substr($row['htmlURL'],-1)=="/")
					$row['htmlURL']=substr($row['htmlURL'],0,-1);
				if(substr($row['xmlURL'],-1)=="/")
					$row['xmlURL']=substr($row['xmlURL'],0,-1);
				
				$texty.= <<<HTML
<div class="result" id="r$xd">
	<div class="link-wrap">
		<p><span>URL:</span><a class="link" id="$row[id]" href="$row[htmlURL]" target="_blank">$row[htmlURL]</a></p>
		<div class="right">
			<div class="trash crawler-modal-open" id="delete-seed">
				<img class="trash-icon" src="./assets/images/Delete.svg">
			</div>
			<div class="update crawler-modal-open" id="update-seed">
				<p>Update Seed</p>
			</div>
		</div>
	</div>
	<div class="details">
		<div class="detail-row">
			<div class="cell">
				<p><span class="label">NAME:</span>$row[text_name]</p>
			</div>
			<div class="cell">
				<p><span class="label">TITLE:</span>$row[title]</p>
			</div>
		</div>
		<div class="detail-row">
			<div class="cell">
				<p><span class="label">TWITTER:</span><a href="" target="_blank">$row[twitter_handle]</a></p>
			</div>
			<div class="cell">
				<p><span class="label">RSS:</span><a href="" target="_blank">$row[xmlURL]</a></p>
			</div>
		</div>
	</div>
</div>
HTML;
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


	public function get_count()
	{
		$conn = $this->connect();
		$query = "SELECT * FROM ppj_seeds";
		$result = $conn->query($query);
		mysqli_close($conn);
		return $result->num_rows;
	}

	public function add_seed($link)
	{
		$conn = $this->connect();
		$query = "INSERT INTO ppj_seeds (htmlURL) VALUES ('$link')";
		$result = $conn->query($query);
		mysqli_close($conn);
	}

}

?>