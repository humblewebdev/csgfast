<?php
include 'db_connect.php';
include '../incontact/soapconnection.php'; //added
include 'custom_classes.php'; //new
session_start();
$user_id_logged_in = $_SESSION['user_id'];
$admin_name = $_SESSION['user_name'];
session_write_close();


//grab the option & uid from the post variable in admin_farm_agent_editor.php
$ibOption = $_POST['inbound_option'];
$uid = $_POST['users_id'];

//echo "inbound option = $ibOption<br>";
//echo "uid = $uid<br>";

$pocQuery = $mysqli->query("SELECT * FROM farm_incontact_info WHERE users_id='$uid'");
$row = mysqli_fetch_array($pocQuery);
$ccode = $row['poc_contactcode'];

// /* call poc_find to get contact code */
try {
$parameters = array('applicationId' => $appID, 'contactCode' => $ccode);
$find = array($client->PointOfContact_Find($parameters));
$find_result = $find[0]->PointOfContact_FindResult;
} catch (Exception $e){
	$error_msg = $error_msg . $e->getMessage() . "</b><br>";
}

if(strcmp($ibOption,"csg") == 0){
$find_result->ScriptName = 'FAST';
$find_result->Notes = "Changed Script to FAST (CSGFirst) by $admin_name";
} else {
$find_result->ScriptName = 'FAST_AgentFirst';
$find_result->Notes = "Changed Script to FAST_AgentFirst (AgentFirst) by $admin_name";
}

/* call poc_update using prev found contact code */
try {
$parameters = array('applicationId' => $appID, 'pointOfContact' => $find_result);
$pocadd = array($client->PointOfContact_Update($parameters));
} catch (Exception $e){
	$error_msg = $error_msg . $e->getMessage() . "</b><br>";
}

//Update the user's inbound option
$mysqli->query("UPDATE products_ext SET inbound_option ='$ibOption' WHERE users_id='$uid'");

//Added to audit trail 2/14/2014
/**-------------------- update the AUDIT TRAIL ---------------------**/
$current_time = date("Y-m-d H:i:s");
$audit_insert= "INSERT INTO audit_trail (affected_user_id, resp_user_id, name_of_table, field_name, field_value, timestamp) values('$uid','$user_id_logged_in','products_ext','inbound_option','$ibOption','$current_time')";
if($mysqli->query($audit_insert)){

}else{
	echo $mysqli->error;
}
/** --------------- end of updating audit trail --------------------**/

/**------- email section notifying WFM of inbound option change -------**/
include '../email_widget/email_iboption_script.php';
		
?>