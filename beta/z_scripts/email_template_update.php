<?php
/*----------------------------------------------------------
* Written by: Amber Bryson
* Date: 2/6/2014
* Description: Called from the JavaScript function in 
* admin_email.php and either inserts a new email template,
* edits an existing one, or sends an email.
*-----------------------------------------------------------*/
include "db_connect.php";
session_start();
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
session_write_close();
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE); */

$action = $_POST['action'];
$email_id = $_POST['emailid'];
$datetime = date('m/d/Y h:i:s a', time());

/*---------------------------------------------------------
-
- 			Edit the email template
-
----------------------------------------------------------*/
if($action == 'edit'){

	$temp_title = $_POST['edit_template_title'];
	$temp_desc = $_POST['edit_template_desc'];
	$temp_con =  $_POST['edit_template_content'];
	
	if(empty($temp_title)  || empty($temp_desc) || empty($temp_con)){
		echo "values cannot be blank";
		exit;
	}else{
		$edit_qry = "UPDATE email_templates SET template_title='$temp_title', template_content='$temp_con',
		template_desc='$temp_desc', template_last_updated_timestamp ='$datetime',
		template_last_updated_by_fullname ='$user_name', template_last_updated_by_id='$user_id'
		WHERE id_email_template='$email_id'";
		if(!$mysqli->query($edit_qry)){
			echo "unable to update";
			$mysqli->error;
		}else{
			echo "Email template has been updated.";
		}

	}

/*---------------------------------------------------------
-
- 				Create an email template
-
----------------------------------------------------------*/
}else if($action == 'create'){

	$temp_title = $_POST['create_template_title'];
	$temp_desc = $_POST['create_template_desc'];
	$temp_con =  $_POST['create_template_content'];

	if(empty($temp_title)  || empty($temp_desc) || empty($temp_con)){
		echo "Values cannot be blank.";
		exit;
	}else{
	
		$insert_qry = "INSERT INTO email_templates (template_title,template_content,template_desc,template_timestamp,template_created_by_id,template_created_by_fullname) 
					values('$temp_title','$temp_con','$temp_desc','$datetime','$user_id','$user_name')";
		if(!$mysqli->query($insert_qry)){
			echo "insertion of new email template failed.";
			exit;
		}else{
			echo "Email template has been created.";
		}
		
	}

/*---------------------------------------------------------
-
- 					Send an email
-
----------------------------------------------------------*/
}else if($action == 'send'){
	
	if(isset($_POST['send_to'])){
		$send_to_array = $_POST['send_to'];
	}else{
		echo "You haven't chosen a recipient";
		exit;
	}
	$send_title = $_POST['send_email_title'];
	$cc = $_POST['send_email_cc'];
	$bcc = $_POST['send_email_bcc'];
	$content = $_POST['send_email_content'];
	if($send_to_array[0] == ''  || empty($send_title) || empty($content)){
		echo "The 'Subject', and 'Email Body' fields cannot be blank.";
		exit;	
	}else{
		include '../email_widget/email_admin_send.php';
	}

}else{
	echo "There was an error in the process.";
}
?>