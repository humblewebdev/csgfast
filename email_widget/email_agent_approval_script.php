<?php
/**
* Written by Amber Bryson
* Date: 1/3/2013
* Script that handles approval notification emails. Two emails will be sent: One to the user,
* and the other to fast prod. In test phase will be sent to CSG IT.
**/

//include '../z_scripts/db_connect.php'; // only add when testing.

/*  ---------------------------------- User Query Section ---------------------------------- */
$users_qry = "SELECT * FROM users c
			JOIN farm_incontact_info f 
			ON c.users_id = f.users_id 
			AND c.users_id = '$uid' 
			JOIN farm_agent_info a 
			ON a.users_id = f.users_id"; //The $uid is grabbed from the parent file (z_scripts/admin_farm_agent_table_update.php)
$users_info_result = $mysqli->query($users_qry);
$users_row = mysqli_fetch_array($users_info_result);
//Store the retrieved information into shorter variables.
$first = $users_row['firstname'];
$last = $users_row['lastname'];
$poc = $users_row['poc'];
$un = $users_row['username'];
$mainline = $users_row['mainphone'];
$approvedate = $users_row['start_request_date'];
$email = $users_row['email'];

//echo $full . $username . $timezone . $email . $mainline . $pif . $agentcode . $regdate. $approvedate;

/* ---------------------------------- User Email Section  --------------------------------- */
//Grab the email template from the database.
$user_template_qry  = "SELECT * FROM email_templates WHERE id_email_template = 20";
$user_template_result = $mysqli->query($user_template_qry);
$user_template_row = mysqli_fetch_array($user_template_result);
//Store the retrieved info into shorter variables.
$reg_title = $user_template_row['template_title'];
$reg_message = html_entity_decode($user_template_row['template_content']);
$headers =	'From: FAST_Support@csg-email.com' . "\r\n" .
			'Reply-To: FAST_Support@csg-email.com' . "\r\n" .
			'BCC: csgit@csg-email.com' . "\r\n" .
			'Content-type: text/html' . "\r\n";
//echo $user_template_row['template_content'];
//echo "<br><br><br>";
//echo "email = $email<br>";
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

?>