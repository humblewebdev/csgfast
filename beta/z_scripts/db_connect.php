<?php
//conection:

define ("DB_HOST", "localhost"); // set database host
define ("DB_USER", "root"); // set database user
define ("DB_PASS","QwertY4321"); // set database password
define ("DB_NAME","csg_fast_prod"); // set database name

date_default_timezone_set("America/Chicago");

$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("Error " . mysqli_error($mysqli));

define("COOKIE_TIME_OUT", 10); //specify cookie timeout in days (default is 10 days)
define('SALT_LENGTH', 9); // salt for password

/* Specify user levels */
define ("ADMIN_LEVEL", 5);
define ("FARM_USER_LEVEL", 1);
define ("LDAP_USER_LEVEL", 3);
define ("SUPER_ADMIN", 7);


//define ("GUEST_LEVEL", 0);

/**** PAGE PROTECT CODE  ********************************
This code protects pages to only logged in users. If users have not logged in then it will redirect to login page.
If you want to add a new page and want to login protect, COPY this from this to END marker.
Remember this code must be placed on very top of any html or php page.
********************************************************/

function page_protect() {
session_start();

global $db; 

/* Secure against Session Hijacking by checking user agent */
if (isset($_SESSION['HTTP_USER_AGENT']))
{
    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
    {
        
		logout();
		
        exit;
    }
}

// before we allow sessions, we need to check authentication key - ckey and ctime stored in database

/* If session not set, check for cookies set by Remember me */
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) ) 
{
	if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])){
	/* we double check cookie expiry time against stored in database */
	
	$cookie_user_id  = filter($_COOKIE['user_id']);
	$rs_ctime = $mysqli->query("select ckey, ctime from users where users_id='$cookie_user_id'") or die($mysqli->error);
	list($ckey,$ctime) = $rs_ctime->fetch_assoc($rs_ctime);
	// coookie expiry
	if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) {
        
		logout();
		}
/* Security check with untrusted cookies - dont trust value stored in cookie. 		
/* We also do authentication check of the `ckey` stored in cookie matches that stored in database during login*/

	 if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  ) {
	 	  session_regenerate_id(); //against session fixation attacks.
	
		  $_SESSION['user_id'] = $_COOKIE['user_id'];
		  $_SESSION['user_name'] = $_COOKIE['user_name'];
		/* query user level from database instead of storing in cookies */	
		  $userlevel = $mysqli->query("select user_level from users where users_id='$_SESSION[user_id]'");
		  list($user_level) = $userlevel->fetch_assoc();

		  $_SESSION['user_level'] = $user_level;
		  $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		  
		  
		  
	   } else {
	   
	   logout();
	   }

  } else {
	header("Location: login.php?bye=nice_try_;)");
	exit();
	}
}
}

function filter($data) {
	$data = trim(htmlentities(strip_tags($data)));
	
	if (get_magic_quotes_gpc())
		$data = stripslashes($data);
	
	//$data = $mysqli->real_escape_string($data);
	
	return $data;
}

function EncodeURL($url)
{
$new = strtolower(ereg_replace(' ','_',$url));
return($new);
}

function DecodeURL($url)
{
$new = ucwords(ereg_replace('_',' ',$url));
return($new);
}

function ChopStr($str, $len) 
{
    if (strlen($str) < $len)
        return $str;

    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);

    return $str . "...";
}	

function isEmail($email){
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function isURL($url) 
{
	if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
		return true;
	} else {
		return false;
	}
} 

function GenPwd($length = 7)
{
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}

function GenKey($length = 7)
{
  $password = "";
  $possible = "0123456789abcdefghijkmnopqrstuvwxyz"; 
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}

function logout($scriptlocation)
{
global $db;
session_start();

$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("Error " . mysqli_error($mysqli));

$sess_user_id = strip_tags($mysqli->real_escape_string($_SESSION['user_id']));
$cook_user_id = strip_tags($mysqli->real_escape_string($_COOKIE['user_id']));

if(isset($sess_user_id) || isset($cook_user_id)) {
$mysqli->query("update `users` 
			set `ckey`= '', `ctime`= '' 
			where `users_id`='$sess_user_id' OR  `users_id` = '$cook_user_id'") or die(mysqli_error($mysqli));
}		

/************ Delete the sessions****************/
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_level']);
unset($_SESSION['HTTP_USER_AGENT']);

unset($_SESSION['cn']);
unset($_SESSION['sam']);
unset($_SESSION['giv']);
unset($_SESSION['mem_of']);
unset($_SESSION['num_mems']);

session_unset();
session_destroy(); 

/* Delete the cookies*******************/
setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");

$mysqli->close();
header("Location: " . $scriptlocation . "login.php?logout=successful");
}

// Password and salt generation
function PwdHash($pwd, $salt = null)
{
    if ($salt === null)     {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else     {
        $salt = substr($salt, 0, SALT_LENGTH);
    }
    return $salt . sha1($pwd . $salt);
}

function checkAdmin($islogout) {

if($_SESSION['user_level'] > LDAP_USER_LEVEL) {
	return 1;
}else{ 

if($islogout == "logout"){ logout(); }
	return 0;
}
  return 0;
}

function checkFarmUser($islogout) {

if($_SESSION['user_level'] == FARM_USER_LEVEL) {
	return 1;
} else { 

if($islogout == "logout"){ logout(); }
	return 0;
}
    return 0;
}

function checkLdapUser($islogout) {

if($_SESSION['user_level'] == LDAP_USER_LEVEL) {
return 1;
} else { 

if($islogout == "logout"){ logout(); }
	return 0;
}
    return 0;
}

//Function to Get Browser Info
function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

/**************************************************
  Bind to an Active Directory LDAP server and look
  something up. 
***************************************************/
function authenticate_ldap($user, $password){
  
  $mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("Error " . mysqli_error($mysqli));

  $SearchFor=$user;               //What string do you want to find?
  $SearchField="samaccountname";   //In what Active Directory field do you want to search for the string?

  $LDAPHost = "10.2.8.26";       //Your LDAP server DNS Name or IP Address
  $dn = "DC=culture,DC=local"; //Put your Base DN here
  $LDAPUserDomain = "@culture.local";  //Needs the @, but not always the same as the LDAP server domain
  $LDAPUser = $user;        //A valid Active Directory login
  $LDAPUserPassword = $password;
  $LDAPFieldsToFind = array("cn", "givenname", "samaccountname", "homedirectory", "telephonenumber", "mail", "memberof");
   
  $access = 0;
  $access_admin = 0;
  $cnx = ldap_connect($LDAPHost) or die("Could not connect to LDAP");
  ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
  ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
  $ldapbind = ldap_bind($cnx,$LDAPUser.$LDAPUserDomain,$LDAPUserPassword);
  error_reporting (E_ALL ^ E_NOTICE);   //Suppress some unnecessary messages
  $filter="($SearchField=$SearchFor)"; //Wildcard is * Remove it if you want an exact match
  $sr=ldap_search($cnx, $dn, $filter, $LDAPFieldsToFind);
  $info = ldap_get_entries($cnx, $sr);
  //var_dump($info);
  for ($x=0; $x<$info["count"]; $x++) {
    $ldap_sam=$info[$x]['samaccountname'][0];
    $ldap_giv=$info[$x]['givenname'][0];
	$ldap_sn=$info[$x]['sn'][0];
    $ldap_tel=$info[$x]['telephonenumber'][0];
    $ldap_email=$info[$x]['mail'][0];
    $ldap_cn=$info[$x]['cn'][0];
    $ldap_dir=$info[$x]['homedirectory'][0];
    $ldap_dir=strtolower($ldap_dir);
    $pos=strpos($dir,"home");
    $pos=$pos+5;
	$ldap_memof=$info[$x]['memberof'];
	$ldap_nummems = count($memof);
	if($ldap_nummems  > 0){$ldap_nummems  = $ldap_nummems  - 1;}

   if($ldap_sam != "" || $ldap_sam != NULL){ 
   
   /*********** Check if record has been created for this user, if not create one ************/
    
	$search_sql_user = $mysqli->query("SELECT users_id FROM users_ldap where user_un='$ldap_sam';");
    $search_row_cnt = $search_sql_user->num_rows;
	
	$searchresult = $search_sql_user->fetch_row();
	
	if($search_row_cnt < 1){
	    $row_cnt_result = $mysqli->query('SELECT users_id FROM users_ldap ORDER BY users_id DESC LIMIT 1;');
		/* determine the id to assign to this new user based on the latest id used in the users table +1 */
		$row_cnt = $row_cnt_result->num_rows;

		$num_of_rows = $row_cnt_result->fetch_row();
		$this_users_id = $num_of_rows[0] + 1;  //Here is where the user id gets assigned
		
		$mysqli->query("INSERT INTO users_ldap (users_id, user_un, user_full_name, user_level) VALUES ('$this_users_id', '$ldap_sam', '$ldap_giv $ldap_sn', '3')") or die($mysqli->error);
	} else {$this_users_id = $searchresult[0];}
	
   
	/*********** End of User Creation ************/
   
   session_start();
   session_regenerate_id (true); //prevent against session fixation attacks.

   // this sets variables in the session 
   $_SESSION['user_id']= $this_users_id;  
   $_SESSION['user_name'] = $ldap_sam;
   $_SESSION['user_level'] = '3';
   $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
	
   $_SESSION['cn'] = $ldap_nam;
   $_SESSION['sam'] = $ldap_sam;
   $_SESSION['giv'] = $ldap_giv;
   $_SESSION['mem_of'] = $ldap_memof;
   $_SESSION['num_mems'] = $ldap_nummems;
   
   //update the timestamp and key for cookie
   $stamp = time();
   $ckey = GenKey();
	
   $mysqli->query("update users_ldap set ctime='$stamp', ckey = '$ckey', last_login_timestamp=NOW() where user_un='$ldap_sam';") or die($mysqli->error);
   
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
		'$this_users_id',
		'$ldap_sam',
		'$ldap_giv',
		'$ldap_sn',
		'{$_SERVER['REMOTE_ADDR']}',
		'{$browserinfo['name']}',
		'{$browserinfo['version']}',
		'{$browserinfo['platform']}',
		NOW()
		);
		") or die($mysqli->error);
		
	if(isset($_POST['remember'])){
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
	    }
   $mysqli->close();  
   
   return true; 
   } else { $mysqli->close(); return false; }
   
  }   

}//End of Function 

function formatPhone($phone)
{
    $phone = preg_replace("/[^0-9]/", "", $phone);

    if(strlen($phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
    else
        return $phone;
}
?>
