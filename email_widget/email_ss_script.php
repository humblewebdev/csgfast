<?php
include '../dbc.php';
page_protect();
$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$login_path = @ereg_replace('admin','',dirname($_SERVER['PHP_SELF']));
$path   = rtrim($login_path, '/\\');

// Collects currently logged on user data and stores it in user_info array
 
 $iid_x = mysql_real_escape_string($_SESSION['user_id']);
 $data_x = mysql_query("SELECT * FROM users where id = '$iid_x';") 
 
 
 or die(mysql_error()); 
 $info_x = mysql_fetch_array( $data_x ); 

// filter GET values
foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

foreach($_POST as $key => $value) {
	$post[$key] = filter($value);
}

//$tolist = explode(",", $post[sendto]);
$bcclist = trim($post[bcc], " ");
$subject = $post[subject];
$bodytext = $_POST['bodytext'];
/********************This Code Below Sends an email out to the Agent with their Approval Notification and Incontact Info**************************/


foreach($_POST['sendto'] as $id_key){

$intid = (int) $id_key;
$a_result = mysql_query("SELECT * FROM users WHERE id= '$id_key';");
		if (!$a_result) {
		echo 'Could not run query: ' . mysql_error();
		exit;
		} else{
		$a_row = mysql_fetch_array($a_result);
		$approve_check = $a_row['approved'];
		$approve_email = $a_row['user_email'];
		$approve_poc_raw = $a_row['poc'];
		$approve_poc = "(".substr($approve_poc_raw,0,3).") ".substr($approve_poc_raw,3,3)."-".substr($approve_poc_raw,6);
		$approve_first = $a_row['firstname'];
		$approve_last = $a_row['lastname'];
		$approve_startdate = $a_row['start_request_date'];
		$approve_mainline_raw = $a_row['tel'];
		$approve_mainline = "(".substr($approve_mainline_raw,0,3).") ".substr($approve_mainline_raw,3,3)."-".substr($approve_mainline_raw,6);
		$approve_username = $a_row['user_name'];
		
		$myfirst = $info_x['firstname'];
		$mylast = $info_x['lastname'];
		$myemail = $info_x['user_email'];
		
		$first = $a_row['firstname'];
		$useremail = $a_row['user_email'];
		$last = $a_row['lastname'];
		$full = $a_row['firstname'] . " " . $a_row['lastname'];
		$approvedate = $a_row['start_request_date'];
		$un = $a_row['user_name'];
		$poc = $a_row['poc'];
		$mainline = $a_row['tel'];
		$agentcode = $a_row['agent_code'];
		$regdate = $a_row['date'];
		$pif = $a_row['pifsize'];
		$portal = $a_row['portal_update_time'];
		$lastlogin = $a_row['last_login_timestamp'];
		$lastpwd = $a_row['last_pwd_timestamp'];
		
		preg_match_all( '#\[.+\]#U', $bodytext, $shortcodes ); // Matches all shortcodes being sent and puts them into $shortcodes array

		
		//Grabs each shortcode item in array and replaces it with matching variable item pointed to database
		foreach($shortcodes[0] as $replace){
		$trimmed_sc = ereg_replace("[^A-Za-z0-9-]", "", $replace); //Cuts off left and right bracket to use as clean variable name
		$new_bodytext = str_replace("$replace", $$trimmed_sc, $bodytext);
        $bodytext = $new_bodytext;
		}
		
		$a_message = $new_bodytext;

		$a_to = "zachr@csg-email.com";
		$a_subject = $subject;
		$a_headers = "From: FAST_Support@csgemail.com \r\n";
		$a_headers .= "Reply-To: FAST_Support@csgemail.com \r\n";
		$a_headers .= "BCC: $bcclist \r\n";
		$a_headers .= "MIME-Version: 1.0\r\n";
		$a_headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		if (mail($a_to, $a_subject, $a_message, $a_headers)){
		 //echo "Email Sent Successfully! for id '$id_key'<br><br>";
		 $success_emails[] = $id_key;
		 $ecount = count($success_emails);
       } else {
	     $ecount = 0;
         //echo "[B]Email Could NOT be Sent![/B]";
       }
	   
	    $bodytext = $_POST['bodytext']; //resets bodytext variable for next item in array
		
		
		
		}
		}
		
		
		$url = "../email_sender.php?success_email_count=$ecount";
        header('Location: ' . $url);
        
		/******************** End of Email Generation and Page Now Redirects back to Admin Page **************************/
		
		?>
		
