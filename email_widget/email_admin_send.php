<?php
/*************************************************
* Author: Amber Bryson
* Date: 2/7/2014
* Send Email script functionality for admin.
* PARENT FILE: z_scripts/email_template_update.php
**************************************************/

/* grab the user id from the parent file, email_template_update.php */
foreach ($send_to_array as $user_id){
	//echo $user_id;
	
	$user_qry = "SELECT * FROM users t
						  LEFT JOIN farm_agent_info t1
						  ON t.users_id = t1.users_id
						  LEFT JOIN farm_agent_staff_info t2
						  ON t.users_id = t2.users_id
						  LEFT JOIN farm_incontact_info t3
						  ON t.users_id = t3.users_id
						  LEFT JOIN products_ext t4
						  ON t.users_id = t4.users_id
						  WHERE t.users_id='$user_id'";
	$user_result = $mysqli->query($user_qry);
	$a_row = mysqli_fetch_array($user_result);
	
	$myfull = $user_name;
	$myemail = 'FAST_support@csg-email.com';
	
	$first = $a_row['firstname'];
	$last = $a_row['lastname'];
	$full = $a_row['full_name'];
	$un = $a_row['username'];
	$useremail = $a_row['email'];
	$poc = $a_row['poc'];
	$approvedate = $a_row['start_request_date'];
	$mainline = $a_row['mainphone'];
	$agentcode = $a_row['agent_code'];
	$regdate = $a_row['reg_date'];
	$pif = $a_row['pif'];
	$portal = $a_row['portal_update_time'];
	$lastlogin = $a_row['last_login_timestamp'];
	$lastpwd = $a_row['last_pwd_timestamp'];
	
		$headers = "From: FAST_Support@csgemail.com \r\n";
		$headers .= "Reply-To: FAST_Support@csgemail.com \r\n";
		
		if(!empty($bcc)){
			$headers .= "BCC: $bcc \r\n";
		}
		if(!empty($cc)){
			$headers .= "CC: $cc \r\n";
		}
		$headers .= "Content-Type: text/html; charset=ISO-8859-1 \r\n";
	
	$reg_message = html_entity_decode($content); //content is from parent file
		
	preg_match_all( '#\[.+\]#U', $reg_message, $shortcodes );	
	foreach($shortcodes[0] as $replace){
		$trimmed_sc = ereg_replace("[^A-Za-z0-9-]", "", $replace); //Cuts off left and right bracket to use as clean variable name
		$new_bodytext = str_replace("$replace", $$trimmed_sc, $reg_message);
		$reg_message = $new_bodytext;
	}
	/*echo "my mail = $myemail <br>
		send title = $send_title<br>
		message = $reg_message<br>
		headers = $headers<br>";*/
	if(mail($useremail, $send_title, $reg_message, $headers)){
		echo "email has been sent!";
	}else{
		echo "email failed";
	}	
}

?>
