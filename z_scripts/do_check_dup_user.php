<?php
/*
* Author: Zach
* Updated by: Amber 1/12/2014
* added functionality to check email entered isnt there own.
*/ 
session_start();
if(isset($_SESSION['email'])){	
$users_email = $_SESSION['email'];
}
session_write_close(); // close session write priv. ; speeds up val check
// if(isset($_SESSION['email'])){	
	// $users_email = $_SESSION['email'];
// }
include 'db_connect.php';
$email = $_GET['email'];
if(isset($_GET['agent_code'])){
	$agent_code = $_GET['agent_code'];
}
/*ini_get('display_errors');
if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}
echo ini_get('display_errors'); */

//  Added by Tim on 1/31/14   
//  to allow registration if we have an empty agent_field in the users table...
//  proper fix is to separate dup user and dup email checks...
//   We can improve that later..
if(isset($agent_code)){
	if ($agent_code == '') {$agent_code = 'xx';}
}
if ($email == '') {$email = 'xx';}

$dup_check = 0;
$row_cnt_result = $mysqli->query("SELECT users_id FROM users WHERE email='$email' OR agent_code='$agent_code';") or die(mysqli_error($mysqli));

$row_cnt = $row_cnt_result->num_rows;
if(isset($users_email)){
	if(!strcmp($email, $users_email)){
		//do nothing
	} else if( $row_cnt > 0 ){
		$dup_check = 1;
	}
}else{
	if($row_cnt > 0){
		$dup_check = 1;
	}
}

/* check to see if user already exists */
// $row_cnt = $row_cnt_result->num_rows;
// if ($row_cnt > 0) {
 // $dup_check = 1;
// }	
//add in the check to make sure the prefilled email does not get counted as a duplicate
/*if(isset($users_email)){
 if(strcmp($email, $users_email) == 0){
	if the users email is the same as the email typed in, remove the dup check flag
	$dup_check = 0;
 }
}	*/
//echo $dup_check;
?>
<script src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var email = '<?php echo trim($email); ?>';
	var agent_code = '<?php echo trim($agent_code); ?>';
	var dup_check = <?php echo $dup_check; ?>;
	
    if(agent_code != '' && dup_check == 1){	
	document.getElementById("dup_agentcode").innerHTML = "<font color='red'>Invalid: This user is already registered.</font>";
    document.getElementById("agent_code").value = ""; //Clear Value
	}
	
	if(email != '' && dup_check == 1){	
	document.getElementById("dup_email").innerHTML = "<font color='red'>Invalid: <?php echo trim($email);?> is already in use.</font>";
    document.getElementById("email").value = ""; //Clear Value
	document.getElementById("changeemail").value = ""; //Clear Value for email change
	}
	
	});
</script>

<?php
//restart session
session_start();
$_SESSION['invalid'] = 0;
if($dup_check){
$_SESSION['invalid'] = 1;
}
?>
