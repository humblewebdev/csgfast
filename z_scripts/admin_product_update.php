<?php
/****************************************************
* Created 4/4/2014
* 
* Script  for admin to update Product information 
* such as description, the confirmation email, and
* approval email. 
* This page is requested from home_page_admin.js, which
* is called using the admin_update_product function on 
* admin_products.php.
*****************************************************/
	session_start();
	if(isset($_SESSION['user_name'])){
		$user_logged_in = $_SESSION['user_name'];
	}
	
	if(isset($_SESSION['user_id'])){
		$user_id_logged_in = $_SESSION['user_id'];
	}
	session_write_close();

	include 'db_connect.php';	
	
	/*--------------------------------------------------------------------*
	-
	-						ADMIN PRODUCT UPDATE
	-
	*---------------------------------------------------------------------*/
	//Grab the variables from the POST
	$prodid = trim($_POST['prodid']);
	$confirm_id = trim($_POST['confirm_id']);
	$approval_id = trim($_POST['approval_id']);
	$product_short_desc = trim($mysqli->real_escape_string($_POST['product_short_desc']));
	
	//update the product short description, confirmation email, and approval email
	$product_update_qry = "UPDATE fast_products 
							SET product_short_desc='$product_short_desc', confirm_id='$confirm_id', approve_id='$approval_id' 
							WHERE product_id='$prodid';";
	if($mysqli->query($product_update_qry)){
		echo "Product $prodid has successfully updated!!";
	}else{
		echo $mysqli->error;
	}
	
	/*---------------------------------------------------------------------*
	-
	- 						AUDIT TRAIL UPDATE
	-
	*----------------------------------------------------------------------*/
	
	$current_time = date("Y-m-d H:i:s");
	$browserinfo = getBrowser();
	$browser_type = $browserinfo['name'];
	$browser_version = $browserinfo['version'];
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$productchange = "confirm_id = $confirm_id and approve_id = $approval_id";
	$productchange =  trim($mysqli->real_escape_string($productchange));
	$audit_insert= "INSERT INTO audit_trail 
					(affected_user_id, resp_user_id, name_of_table, field_name, field_value, ip_address, browser_type, browser_version, timestamp) 
					values('$user_id_logged_in','$user_id_logged_in','fast_products','description confirm_id and approve_id have changed for product $prodid','$productchange','$ip_address','$browser_type','$browser_version','$current_time')";
	if($mysqli->query($audit_insert)){

	}else{
		echo $mysqli->error;
	}

?>