<?php 
/*
*
* Email product approval email
* date: 1/31/2014
*
*/

/*---------------------------------1: Gather user information ---------------------------------*/

//use the uid from the parent file: admin_farm_agent_product_update to query user info
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
$poc = $users_row['poc'];

/*-------------------------------- 2: Gather Script information --------------------------------*/
//user the prodid variable from the parent file admin_farm_agent_product_update to determine the correct product

// 1: grab the confirm email from the appropriate product chosen. product chosen is from post value
$approve_id = $mysqli->query("SELECT approve_id FROM fast_products WHERE product_id='$prodid'")->fetch_object()->approve_id;

//echo $approve_id;
//Grab the email template from the database.
$user_template_qry  = "SELECT * FROM email_templates WHERE id_email_template = $approve_id";
$user_template_result = $mysqli->query($user_template_qry);
$user_template_row = mysqli_fetch_array($user_template_result);
//Store the retrieved info into shorter variables.
$reg_title = $user_template_row['template_title'];
$reg_message = html_entity_decode($user_template_row['template_content']);
$headers =	'From: FAST_Support@csg-email.com' . "\r\n" .
			'Reply-To: FAST_Support@csg-email.com' . "\r\n" .
			'BCC: kimberlym@csg-email.com, ruebenc@csg-email.com, amberb@csg-email.com' . "\r\n" .
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



?>