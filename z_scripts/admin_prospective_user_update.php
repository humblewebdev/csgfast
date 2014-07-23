<?php
/**
* Prospective user update
*
*/
include "db_connect.php";

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

/*if($_POST['action'] == 'create'){
	$name = $_POST['agent_name'];
	$acode = $_POST['agent_code'];
	$phone = $_POST['agent_phone'];
	$email = $_POST['agent_email'];
	$notes = $_POST['agent_notes'];
	$date = $_POST['date_reminder'];
	$pif = $_POST['agent_pif'];
	//echo "$name<br>";
	//echo "i got here!";
	//insertion query
	$insert_qry = "INSERT INTO users_prospect
					(agent_name,pif,agent_code,phone,date_reminder,notes,email,prospect_flag)
					values('$name','$pif','$acode','$phone','$date','$notes','$email','1')";
	$mysqli->query($insert_qry);
} */

if($_POST['action'] == 'update'){

	echo "Hello, this is the update portion of the script";
	$uid = $_POST['userid'];
	echo "uid = $uid <br>";

	if(isset($_POST['update_date_reminder'])){
		$date = $_POST['update_date_reminder'];
		echo "date = $date<br>";
		$update_date = "UPDATE users_prospect SET date_reminder='$date' WHERE prospect_id='$uid'";
		if($mysqli->query($update_date)){
				echo" it worked";
		}else{
			$mysqli->error;
		}
	}
	
	/*if(isset($_POST["prospect_status_change"])){
		$echo "flag = $flag";
		$flag = $_POST["prospect_status_change"];
		$update_flag = "UPDATE users_prospect SET prospect_flag='$flag' WHERE prospect_id='$uid'";
		$mysqli->query($update_flag);
	}*/
	

}

/*if($_POST['action' == 'status'){

	echo "made it to status!";
}*/
?>

