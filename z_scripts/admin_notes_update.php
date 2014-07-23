<?php
/**************************
* Author: Amber Bryson
* 2/20/2014
* Admin notes update script
*
***************************/
include 'db_connect.php';

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
*/

$content = $_POST['notes_content'];
$admin_id = $_POST['admin_id'];
$users_id = $_POST['users_id'];
$timestamp = date("Y-m-d H:i:s");
//echo "admin id = ".$admin_id;
//echo "<br> users id = ". $users_id;

$insert_qry = "INSERT INTO admin_notes 
				(users_id,admin_id,content,note_timestamp) 
				values('$users_id','$admin_id','$content','$timestamp')";
$mysqli->query($insert_qry);

		//update the audit trail
		$current_time = date("Y-m-d H:i:s");
		
		$browserinfo = getBrowser();
		$browser_type = $browserinfo['name'];
		$browser_version = $browserinfo['version'];
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		$audit_insert= "INSERT INTO audit_trail (affected_user_id, resp_user_id, name_of_table, field_name, field_value, ip_address, browser_type, browser_version, timestamp) values('$users_id','$admin_id','admin_notes','content','$content', '$ip_address','$browser_type','$browser_version','$current_time')";
		if($mysqli->query($audit_insert)){

		}else{
			echo $mysqli->error;
		}
				

?>