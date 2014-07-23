<?php
/******************************
* 3/14/2014
* Author: Amber Bryson
* Delete farm agent script
*
*******************************/
include "db_connect.php";
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

	/*------- Query all associated information for the user -------*/
	$users_id = $_POST['users_id'];
	$skill = $_POST['skill']; // key will be used in the fast_deactivate script
	
	
	/************ Place POC back onto the available listing **********/
	
	if(isset($skill)){
		if(!empty($skill)){	
			include "../incontact/fast_deactivate.php";
		}
	}
	
	/*----------------------- DELETE QUERIES --------------------
	-
	- 	After all associated files have been removed, delete all 
	-	associated table data for the user.
	-
	-------------------------------------------------------------*/
	$delete_qry = "DELETE FROM users WHERE users_id = $users_id";
	$mysqli->query($delete_qry);
	$delete_qry = "DELETE FROM farm_agent_info WHERE users_id = $users_id";
	$mysqli->query($delete_qry);
	$delete_qry = "DELETE FROM farm_agent_staff_info WHERE users_id = $users_id";
	$mysqli->query($delete_qry);
	$delete_qry = "DELETE FROM farm_incontact_info WHERE users_id = $users_id";
	$mysqli->query($delete_qry);
	$delete_qry = "DELETE FROM products_ext WHERE users_id = $users_id";
	$mysqli->query($delete_qry);

	//echo "This functionality is still being tested, and will not actually delete this user.";
	echo "The user has been removed successfully.";
?>