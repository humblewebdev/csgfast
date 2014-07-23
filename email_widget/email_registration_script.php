<?php
/**
* Written by Amber Bryson
* Date: 12/31/2013
* Script that handles registration emails. Two emails will be sent: One to the user,
* and the other to fast prod. In test phase will be sent to CSG IT.
**/

//include '../z_scripts/db_connect.php'; // only add when testing.

/*  ---------------------------------- User Query Section ---------------------------------- */
//Grab the logged in user's information from the parent file (z_scripts/do_signup_user.php).
$uid = $this_users_id; // add back after testing is done!
//$uid = 9; //only added when testing

$users_qry = "SELECT * FROM users c
			JOIN farm_incontact_info f 
			ON c.users_id = f.users_id 
			AND c.users_id = '$uid' 
			JOIN farm_agent_info a 
			ON a.users_id = f.users_id";
$users_info_result = $mysqli->query($users_qry);
$users_row = mysqli_fetch_array($users_info_result);
//Store the retrieved information into shorter variables.
$first = $users_row['firstname'];
$last = $users_row['lastname'];
$full = $users_row['full_name'];
$un = $users_row['username'];
$timezone = $users_row['timezone'];
$email = $users_row['email'];
$mainline = $users_row['mainphone'];
$pif = $users_row['pif'];
$agentcode = $users_row['agent_code'];
$regdate = $users_row['reg_date'];
$approvedate = $users_row['start_request_date'];

//echo $full . $username . $timezone . $email . $mainline . $pif . $agentcode . $regdate. $approvedate;

/* ---------------------------------- User Email Section  --------------------------------- */
//Grab the email template from the database.
$user_template_qry  = "SELECT * FROM email_templates WHERE id_email_template = 25";
$user_template_result = $mysqli->query($user_template_qry);
$user_template_row = mysqli_fetch_array($user_template_result);
//Store the retrieved info into shorter variables.
$reg_title = $user_template_row['template_title'];
$reg_message = html_entity_decode($user_template_row['template_content']);
$headers =	'From: FAST_Support@csg-email.com' . "\r\n" .
			'Reply-To: FAST_Support@csg-email.com' . "\r\n" .
			'BCC: amberb@csg-email.com' . "\r\n" .
			'Content-type: text/html' . "\r\n";
//echo $user_template_row['template_content'];

preg_match_all( '#\[.+\]#U', $reg_message, $shortcodes );

foreach($shortcodes[0] as $replace){
$trimmed_sc = ereg_replace("[^A-Za-z0-9-]", "", $replace); //Cuts off left and right bracket to use as clean variable name
$new_bodytext = str_replace("$replace", $$trimmed_sc, $reg_message);
$reg_message = $new_bodytext;
}

//echo $reg_message;
//Send email to user notifying they have successfully registered.
if(mail($email, $reg_title, $reg_message, $headers)){
	echo "email has been sent!";
}else{
	echo "email failed";
}

/*  --------------------------------- Staff Email Section --------------------------------- */
//Send email to fast support team notifying that a new member has registered.
$staff_template_qry  = "SELECT * FROM email_templates WHERE id_email_template = 26";
$staff_template_result = $mysqli->query($staff_template_qry);
$staff_template_row = mysqli_fetch_array($staff_template_result);
//Store the retrieved info into shorter variables.
$to = "kimberlym@csg-email.com, ruebenc@csg-email.com, tims@csg-email.com";
$reg_title = $staff_template_row['template_title'];
$reg_message = html_entity_decode($staff_template_row['template_content']);
//echo $staff_template_row['template_content'];
$headers =	'From: FAST_Support@csg-email.com' . "\r\n" .
			'Reply-To: FAST_Support@csg-email.com' . "\r\n" .
			'BCC: amberb@csg-email.com' . "\r\n" .
			'Content-type: text/html' . "\r\n";
			
preg_match_all( '#\[.+\]#U', $reg_message, $shortcodes );

foreach($shortcodes[0] as $replace){
$trimmed_sc = ereg_replace("[^A-Za-z0-9-]", "", $replace); //Cuts off left and right bracket to use as clean variable name
$new_bodytext = str_replace("$replace", $$trimmed_sc, $reg_message);
$reg_message = $new_bodytext;
}

//echo $reg_message;
//Send email to staff notifying they have successfully registered.
if(mail($to, $reg_title, $reg_message, $headers)){
	echo "email has been sent!";
}else{
	echo "email failed";
}

?>