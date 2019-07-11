<?php

// require_once("config.php");


class crawler_broken_links {

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
	public function get_broken_links($offset)
	{
		$conn=$this->connect();
		$query = "SELECT DISTINCT link_url, error_code FROM ppj_broken_links";
		$result=$conn->query($query);
		mysqli_close($conn);
		if($result->num_rows >0){
		$texty='';
		$i=0;
			while($row = $result->fetch_array())
			{
				 $status='';
				 $orig=$row['link_url'];
				 if(substr($row['link_url'],-1)=="/")
				$row['link_url']=substr($row['link_url'],0,-1);
				if($row['error_code']>=300&&$row['error_code']<400&&$row['error_code']!=0) {$status="Moved"; $note="Please check if there is a new link for this website";}
				else if($row['error_code']==0) {$status="No response from server"; $note="The server is down or unable to get response from the server";}
				else {$status="Not Available"; $note="Website might be perminantly removed or there is a typo in the link";}
				$xd=$offset+$i;
				$texty.='<div class="result" id=r'.$xd.'> <div style="display:none" id=hid'.$xd.'>'.$orig.'</div> <div class="errorWeb" id=old'.$xd.'>'.$status.'<a href="'.$row['link_url'].'" target="_blank">'."&nbsp &nbsp &nbsp Check Website".'</a></div>   <div class="noteWeb">'.$note.'</div>  <div class="urlWeb" contentEditable="true" id="update'.$xd.'"><p>'.$row['link_url'].'</p>  </div>        <input type="button" class="update" value="UPDATE" id=u'.$xd.'>	 <input type="button" class="delete" value="DELETE" id=d'.$xd.'>       </div>';
				$i++;
			}
		return $texty;
		}
		else return "no more data";
	}
	// update entry from ppj_seeds
	public function update_seeds($old_link,$new_link)
	{
		 $conn=$this->connect();
		$query = "UPDATE ppj_seeds set htmlURL='$new_link' WHERE htmlURL='$old_link' ";
		$result=$conn->query($query);
		mysqli_close($conn);
	}
	//delete entry from ppj_seeds
	public function delete_seeds($link)
	{
		 $conn=$this->connect();
		$query = "DELETE FROM ppj_seeds WHERE htmlURL='$link'";
		$result=$conn->query($query);
		mysqli_close($conn);
	}
	//delete entry from ppj_broken_links
  public function delete_broken_links($link)
  {
	  $conn=$this->connect();
		$query = "DELETE FROM ppj_broken_links WHERE link_url='$link'";
		$result=$conn->query($query);
		mysqli_close($conn);
  }



}

?>