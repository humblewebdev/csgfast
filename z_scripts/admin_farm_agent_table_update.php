<?php 
/**
* Updated 2/3/2014 - removed incontact script info from here and moved it to
* admin_farm_agent_product_update.php. This script will simply allow the user
* to login to their account
*
* Updated 12/27/2013 by Amber Bryson
* Added functionality from Eric's "csgfast.com/fast_create.php"
**/
include 'db_connect.php';
$type=$_GET['type'];

session_start();
$user_id_logged_in = $_SESSION['user_id'];
session_write_close();

if($type=="login_status"){
	if($_POST['approve'] == 1){
		$uid = $_POST['users_id'];
		//echo "uid = $uid";
		$mysqli->query("UPDATE users SET approved='1', banned='0' WHERE users_id='$uid';") or die($mysqli->error);
		echo "User $uid Approved!";
		
		//Email the User notifying they have been approved.
		include '../email_widget/email_agent_approval_script.php';
	
	}else{
		
		$uid = $_POST['users_id'];
		//echo "uid = $uid";
		$mysqli->query("update users SET approved='0', banned='1' WHERE users_id='$uid';") or die($mysqli->error);
		echo "User $uid Locked!";
	}
}

if($type=="bulk_update_approve"){ 
	foreach($_POST['bulk_action_id'] as $uid){
		//approve the user by setting the approve to 1 and ban to 0. 
		$mysqli->query("UPDATE users SET approved='1', banned='0' WHERE users_id='$uid';") or die($mysqli->error);
		
		//add audit trail
		/**-------------------- update the AUDIT TRAIL ---------------------**/
		$current_time = date("Y-m-d H:i:s");
		
		$browserinfo = getBrowser();
		$browser_type = $browserinfo['name'];
		$browser_version = $browserinfo['version'];
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		$audit_insert= "INSERT INTO audit_trail (affected_user_id, resp_user_id, name_of_table, field_name, field_value, ip_address, browser_type, browser_version, timestamp) values('$uid','$user_id_logged_in','users','approved','1','$ip_address','$browser_type','$browser_version','$current_time')";
		if($mysqli->query($audit_insert)){

		}else{
			echo $mysqli->error;
		}
		/** --------------- end of updating audit trail --------------------**/
		
		//Email the User notifying they have been approved.
		include '../email_widget/email_agent_approval_script.php';
	}
}

if($type=="bulk_update_lock"){ 
	foreach($_POST['bulk_action_id'] as $uid){
     $mysqli->query("update users set banned='1' where users_id='$uid';") or die($mysqli->error);
	/**-------------------- update the AUDIT TRAIL ---------------------**/
	$current_time = date("Y-m-d H:i:s");
	$browserinfo = getBrowser();
	$browser_type = $browserinfo['name'];
	$browser_version = $browserinfo['version'];
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$audit_insert= "INSERT INTO audit_trail (affected_user_id, resp_user_id, name_of_table, field_name, field_value, ip_address, browser_type, browser_version, timestamp) 
					values('$uid','$user_id_logged_in','users','banned','1','$ip_address','$browser_type','$browser_version','$current_time')";
	if($mysqli->query($audit_insert)){

	}else{
		echo $mysqli->error;
	}
	/** --------------- end of updating audit trail --------------------**/
	 echo $uid . " Locked! \n";
	}
}
?>