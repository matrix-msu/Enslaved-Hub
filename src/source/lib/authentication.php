<?php
/*
  Model for Login and registration.
  Matrix Template 2018  
*/

//include here require for config file or another class

//class Registration and login
class Authentication{
	
	var $host;
	var $user;
	var $dbName;
	var $password;
	
	 //find a userid within a username
   //use bindparams for it
    public function findIdByUser($username){
			
	}


	//return the password from a user table in the db 
	//the password should be hash or encrypted
	public function obtain_password_user(){
		$con=$this->conexion();
		//select statement using bind params
		$sql=$con-> prepare("");
		$sql->bind_param();
		$sql->execute();
		$sql->bind_result($password);
		$sql->fetch();
		$sql->close();
		return $password;
	}

	public function validate_user($username,$pwd){
		//try to find user or email and 
	    if (strpos($username, '@') !== false) {
	        
        }
        else {
        }
        
		$verifyPass= $this->obtain_password_user();
		if(!empty($verifyPass) && $this->validate_password($pwd,$verifyPass)){
			//start session here with the username if unique if not save it with some variable unique.
			$_SESSION['sess_username'] = $username;
			//optional update the time that the user is login
			$this->updateLastLogin($userid);
			
			return true;
		}
		else return false;
	}
	//create the encryption for the password
	public function createHash($password){
		
		$options = [
			'cost' => 11,
		//	'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
		];
		return password_hash($password, PASSWORD_BCRYPT, $options);
		
	}
	//password validation
	private function validate_password($password,$hash){
		
		if (password_verify($password, $hash)) {
			// Password Is Correct
			//echo "password is correct";
			return true;
		} else {
			// Password Is Not Correct
			//echo "password is not correct";
			return false;
		}
	}
	//return true if the a user exists under a username and is verified
	public function isUserExistsVer($username){
		
		if(empty ($this->obtain_password_user() ) ){
			return false;
		}else{
			return true;
		}
	}

    //return true if the a user exists under a username
    public function isUser($username){
        if(empty ($this->findIdByUser($username) ) ){
            return false;
        }else{
            return true;
        }
    }
    //return username finding user with email 
     public function findUsernameByEmail($email) {
       
        $con = $this->conexion();
        //sql statement to find in user table an username from an email field.
        $stmt = $con->prepare("");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $username = false;
        while($row = $result->fetch_array(MYSQLI_NUM)){
            foreach($row as $r) {
                $username = $r;
            }
        }
        $this->username = $username;
        return $username;
    }
    //return true if the a user exists under a username
    public function isEmail($email){
       
        $username = $this->findUsernameByEmail($email);

        if(empty ($username ) || $username === false){
            return false;
        }else{
            return true;
        }
    }
	//include a new record for an user
	public function insert(){
	}
	//Send email to create a new user
	public function newUser($user_array){
		//include here subject for the email
		$subject='';
		$this->insert($userarray);
		$this->sendEmail($user_array['token'],$user_array['email'],$subject);
	}
	
	//sql statement to update user table to remove token and set a verified field to 1
	
	public function updateActivation($userId){
		$con=$this->conexion();
	}

	
	//update database for activate an account
	public function activateAccount($token){
		
		$actuser=$this->findByToken($token);
		 if(!empty($actuser)){
			 //find id of the user
			$id=
			
			return $this->updateActivation($id);
		 }
	}
	
	
	//send email for activation account
	private function sendEmail($token,$email,$subject){
		
				$url=BASE_URL."/?token=".$token;
		$message = "
			<html>
				<head>
				<title>Activation Mail</title>
				</head>
				<body>
				<p><a href='$url'>Click here to activate your account</a></p>
				</body>
			</html>
			";

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		//finish this with is from, changing for every project.
		$headers .= 'From: Noreply <noreply@msu.edu>' . "\r\n".
					"Reply-To: noreply@msu.com" . "\r\n" .
					"X-Mailer: PHP/" . phpversion();
		mail($email,$subject,$message,$headers);

	}
	
	//function to find a user within a username
	//return all fields related to that user
	public function findByUserName($username){
		$con=$this->conexion();
		
	}
	//updating user table to reset password, includes a new toke and set verified field to 0
	public function updateResetPwd($idUser,$token){
		$con=$this->conexion();
		return  $results;
	}

	//function to reset the password and send email 
	//reset token again
	public function resetPassword1($username,$email){
		
		$user=$this->findByUserName($username);
		if(!empty($user)){
			//assumming here that field is called verification_token
			$user['verification_token']=md5($email.time());
			$this->updateResetPwd($user['idUser'],$user['verification_token']);
			$this->sendEmailPassword($user['verification_token'],$email,$username,$subject);
			return true;
		}
		return false;
		
	}
	
	// Added new password on database. Update statement to remove token, update password and set verified field to 1
	public function updatePassword($userId, $pwd){
		$con=$this->conexion();
		return  $results;
	}
	//update database account with new password
	public function passwordReset($token, $password){
		$actuser=$this->findByToken($token);
		 if(!empty($actuser)){
			$pwd = $this->createHash($password);
			return $this->updatePassword($id, $pwd);
		 }
	}	
	//reseting email 
	private function sendEmailPassword($token,$email,$username,$subject){
		
		//url for sending to the user to reset password
		$url="".$token."&username=".$username;
		$message = "
			<html>
				<head>
				<title>Reset Password Mail</title>
				</head>
				<body>
				<p><a href='$url'>Click here to reset your password</a></p>
				</body>
			</html>
			";

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		//needs to include email comning from
		$headers .= 
				mail($email,$subject,$message,$headers);

	}
	//conection with db
	private function conexion(){
		// Create connection

		$con=mysqli_connect($this->host,$this->user,$this->password,$this->dbName);

		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		return $con;
	}
	//find the token for that user
	private function findByToken($token){
		
	}


}
?>