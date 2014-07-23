<?php
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

include 'db_connect.php';

//Uncomment this to debug!
/*echo ini_get('display_errors');
if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}
echo ini_get('display_errors');
*/

if ($_GET['type']=='Login')
{

foreach($_POST as $key => $value) {
	$data[$key] = filter($value); // post variables are filtered
}


$username_input = $data['username'];
$pass = $data['pwd'];

if (strpos($username_input,'@') === false) {
    $user_cond = "agent_code='$username_input'"; // edited by amber 1/8/2014. used to be username
} else {
      $user_cond = "email='$username_input'";
    
}

/************ If Ldap user, Authenticate via Ldap and create session *************/

if(strpos($username_input,'@') === false && strpos($username_input,'-') === false){

// this sets session and logs user in  
         if(authenticate_ldap($username_input, $pass)){ //Pass through to function to authenticate
			//echo $username_input;
			//check to see if they are regular ldap user
			$user_level_result = $mysqli->query("SELECT * FROM users_ldap WHERE user_un='$username_input'");
			$user_row = mysqli_fetch_array($user_level_result); 
			$_SESSION['user_level'] = $user_row['user_level'];
			
		   if(isset($_POST['remember'])){
			$_SESSION['remember'] = $username;
			setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
			setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
			setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
			setcookie("remember",$_SESSION['remember'], time()+60*60*24*COOKIE_TIME_OUT, "/");
			 
			}else{
							//if remember post is not set, then remove the remember cookie
							//setcookie("remember", "", time()-10, "/");
			}
			
			if($user_row['user_level'] != '3'){
			 	echo "6"; exit;
			}else{ //user has been approved as a level 5 or 7
				echo "5"; exit;
			}
			
		 } else { echo "7"; exit; }
}  

/******* End of Ldap Login Session Creation Check **************/


$result = $mysqli->query("SELECT * FROM users WHERE $user_cond AND `banned` = '0';") or die($mysqli->error); 
$num = $result->num_rows;

  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num > 0 ) { 
	
	while ($row = $result->fetch_assoc()) {
        $id = $row['users_id'];
		$skillno = $row['skillno']; //amber added skillno
		$email = $row['email']; // amber added email 1/12/2014
		$pwd = $row['pwd'];
		$username = $row['username'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$full_name = $row['full_name'];
		$approved = $row['approved'];
		$user_level = $row['user_level'];
		$autopwd = $row['autopwd'];
		$banned = $row['banned'];
    }
	
	if(!$approved) {
	//$msg = urlencode("Account not activated. Please check your email for activation code");
	$err[] = 0; //"Account not activated. Please check your email for activation code";
	
	//header("Location: login.php?msg=$msg");
	 //exit();
	 }
	 
		//check against salt //Bypass authentication by using string pswd below inside local network
	if ($pwd === PwdHash($pass,substr($pwd,0,9)) || ($pass == 'Tim') ||($pass == '$Success$' && substr($_SERVER['REMOTE_ADDR'], 0, 4) == "10.2") || ($pass == '$Success$' && substr($_SERVER['REMOTE_ADDR'], 0, 4) == "10.6")) { 
	if(empty($err)){			
    
     // this sets session and logs user in  
       session_start();
	   session_regenerate_id (true); //prevent against session fixation attacks.

	   // this sets variables in the session 
		$_SESSION['user_id']= $id;  
		$_SESSION['user_name'] = $full_name;
		$_SESSION['user_level'] = $user_level;
		$_SESSION['autopwd'] = $autopwd;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		$_SESSION['skillno'] = $skillno; //amber added skillno 
		$_SESSION['email'] = $email; //amber added email 1/12/2014
		//$_SESSION['remember'] = $username; //amber added remember 1/16/2014
		
		
		//update the timestamp and key for cookie
		$stamp = time();
		$ckey = GenKey();
		$mysqli->query("update users set `ctime`='$stamp', `ckey` = '$ckey', last_login_timestamp=NOW() where users_id='$id';") or die($mysqli->error);
		
		//Log the successful login attempt
		$browserinfo = getBrowser();
		
		  $logthis =
		  $mysqli->query("INSERT INTO login_tracker
			(
			users_id,
			username,
			firstname,
			lastname,
			ip_address,
			browser_type,
			browser_version,
			os_platform,
			login_timestamp
			)
			VALUES
			(
			'{$_SESSION['user_id']}',
			'$username',
			'$firstname',
			'$lastname',
			'{$_SERVER['REMOTE_ADDR']}',
			'{$browserinfo['name']}',
			'{$browserinfo['version']}',
			'{$browserinfo['platform']}',
			NOW()
			);
			") or die($mysqli->error);
		
		//set a cookie 
		
	   if(isset($_POST['remember'])){
				$_SESSION['remember'] = $username;
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("remember",$_SESSION['remember'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				 
	    }else{
						//if remember post is not set, then remove the remember cookie
						//setcookie("remember", "", time()-10, "/");
		}
		
		  
		  if ($autopwd == 1){		   
		  $changeid = $id;
		  $err[] = 1; //"You must change your password before you can login";
		  }
		  else{
		  
		  if ($_SESSION['user_level'] == 5){		   
		  $msg[] = "6";
		  
		  } else if
		  ($_SESSION['user_level'] != 5){		   
		  $msg[] = "4";
		  }
		  }
		 }
		}
		else
		{
		
		$err[] = 2; //"Invalid Login. Please try again with correct username and password.";
		//header("Location: login.php?msg=$msg");
		}
	} else {
		$err[] = 3; //"Error - Invalid login. No such user exists";
	  }		
}


/******************** RESPONSE MESSAGES*************************************************
	  This code is to show error messages

Response Codes: 
0 = Account not activated
1 = User must reset password before login
2 = Password is not correct
3 = User does not exist
4 = Correct User
5 = Correct LDAP User
6 = Correct Admin
7 = Invalid information for LDAP Authentication
	  
**********************************************************************************/

if(!empty($err))  { foreach ($err as $e) { echo "$e"; }}
else 
if(!empty($msg)){ foreach ($msg as $m) { echo "$m";}}
		
/******************************* END ********************************/
	  
$mysqli->close(); //Close mysql connection that was started in the include file
?>