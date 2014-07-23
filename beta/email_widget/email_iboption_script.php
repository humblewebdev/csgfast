<?php
/**
* Inbound option toggle email notification
* 
**/

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
//$email = $users_row['email'];

$email = "FAST_Support@csg-email.com";
$reg_title = "Inbound Option Change for $first $last";
$reg_message = "The inbound option for Farmers Agent $first $last has been changed to <b>$ibOption first</b>.
<br> Please add the appropriate disp codes and update the agents list.<br>";
$headers = 'Content-type: text/html' . "\r\n";

if(mail($email, $reg_title, $reg_message)){
	echo "$first $last's inbound option has been changed to $ibOption first. An email has been sent notifying FAST support of the change.";
}else{
	echo "email failed";
}
?>
