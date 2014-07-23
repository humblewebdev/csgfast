<?php 
include 'db_connect.php';
include '../incontact/soapconnection.php'; //added
include 'custom_classes.php'; //new
session_start();
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE); */
$user_id_logged_in = $_SESSION['user_id'];
$type=$_GET['type'];

if($type=="approve_product"){ 
     $uid = $_POST['uid'];
	 $prodid = trim($_POST['prodid']);
     $getuserdata = $mysqli->query("SELECT * FROM farm_agent_info where users_id='$uid';") or die($mysqli->error);
	 $userdata = $getuserdata->fetch_object();
	 $prevprods = $userdata->fast_products_approved;
	 
        //echo "Current Approved: " . $prevprods . "\n";
		$curappexp = explode("#", $prevprods);
		
		// Search
		$pos = array_search($prodid, $curappexp);
        
		if($pos != NULL){
		echo "Error: Cannot approve product because it was already approved.";
		exit;
        }
		
/*----------------------------2/3/2014 added incontact information to this page--------------------------------*/
		if($prodid == '1'){//if the product is inbound, then add incontact script
		
		
			//$uid = 9; //testing only
		
			/* Grab timezone information from the farm_agent_info table */
			$farmAgentQry = "SELECT * FROM farm_agent_info WHERE users_id='$uid'";	
			$farmAgentResult = $mysqli->query($farmAgentQry);
			$row = mysqli_fetch_array($farmAgentResult);
			$timezone = $row['timezone'];
			
			/* grab/define inbound option csg or agent first for poc */
			$ibquery = "SELECT * FROM products_ext WHERE users_id='$uid'";
			$ibresult = $mysqli->query($ibquery);
			$ibrow = mysqli_fetch_array($ibresult);
			$ibOption = $ibrow['inbound_option'];
			
			//switch statement to set the default time for update
			switch ($timezone) {
				case "Central":
					date_default_timezone_set('America/Chicago');
					break;
				case "Eastern":
					date_default_timezone_set('America/New_York');
					break;
				case "Mountain":
					date_default_timezone_set('America/Denver');
					break;
				case "Mountain_no_dst":
					date_default_timezone_set('America/Phoenix');
					break;
				case "Pacific":
					date_default_timezone_set('America/Los_Angeles');
					break;
				}
			$dt = new DateTime('now');
			$fdt = $dt->format('Y-m-d\TH:i:sP');

			/* declaration error variables */
			$error_msg = "";
			$pocFail = 0;
			
			$userInfoResult = $mysqli->query("SELECT * FROM users WHERE users_id='$uid';");
			$row = mysqli_fetch_array($userInfoResult);
			
			$firstname = $row['firstname'];
			$lastname = $row['lastname'];
			$agent_code = $row['agent_code']; // was user_name in nssoluti database
			$first = substr("$firstname",0,10);
			$last = substr("$lastname",0,3);
			$agentName = "FAST " . $first . "_" . $last . "_" . $agent_code;

					
			/*--------------------------------- Skill Object --------------------------------- */
			$newSkill = new skill();
			$newSkill->SkillNo = 0;
			$newSkill->SkillName = "$agentName";	// custom
			$newSkill->MediaType = 'PhoneCall';
			$newSkill->Status = 'Active';
			$newSkill->CampaignNo = 67833; // set to FAST campaign 
			$newSkill->OutboundSkill = false;
			$newSkill->SLASeconds = 30;
			$newSkill->SLAPercent = 80;
			$newSkill->Notes = "Created by admin_farm_agent_table_update.php API script $fdt";
			$newSkill->Description = '';
			$newSkill->Interruptible = false;
			$newSkill->FromEmailEditable = false;
			$newSkill->FromEmailAddress = '';
			$newSkill->UseDispositions = true;	
			$newSkill->RequireDispositions = true;
			$newSkill->DispositionTimer = 0;
			$newSkill->QueueInitPriority = 0;
			$newSkill->QueueAcceleration = 1;
			$newSkill->QueueFunction = 'Linear';
			$newSkill->QueueMaxPriority = 1000;
			$newSkill->ActiveMinWorkTime = 30;
			$newSkill->OverrideCallerID = false;
			$newSkill->CallerIDNumber = '';
			$newSkill->UseScreenPops = false;
			$newSkill->UseCustomScreenPops = false;
			$newSkill->CustomScreenPopApp = "http://csgfast.com/ipages/ipage.php?un=";	// no specified URL currently
			$newSkill->CampaignName = '';
			$newSkill->LastModified = $fdt;
			$newSkill->ShortAbandonThreshold = 15;
			$newSkill->UseShortAbandonThreshold = true;
			$newSkill->IncludeShortAbandons = false;
			$newSkill->IncludeOtherAbandons = true;
			$newSkill->CustomScriptID = 0;
			$newSkill->CustomScriptName = '';
			$newSkill->IsDialer = false;
			$newSkill->EnableBlending = false;
			
			try {
				$parameters = array('applicationId' => $appID, 'skill' => $newSkill);
				$skilladd = array($client->Skill_Add($parameters));
				$SkillNo = $skilladd[0]->Skill_AddResult;
				echo "SkillNo: $SkillNo<br>";
			} catch (Exception $e){
				$pocFail = 2;
				$error_msg = $error_msg . $e->getMessage() . "</b><br>";
				echo $error_msg;
			}
			
			/* POC Create */
			$psql= $mysqli->query("SELECT * FROM poc_list") or die ($mysqli->error());
			$row = mysqli_fetch_array($psql) or die ($mysqli->error());
			$assign = $row['assigned'];
			$t = $row['poc'];
			$number = (string)$t;
			$ccode = $row['contact_code'];
			//echo "$ccode<br>$number<br>";


			/* call poc_find to get contact code */
			if ( $assign == 1 ){ // if poc has been used and is now available

			try {
			$parameters = array('applicationId' => $appID, 'contactCode' => $ccode);
			$find = array($client->PointOfContact_Find($parameters));
			$find_result = $find[0]->PointOfContact_FindResult;
			} catch (Exception $e){
				$pocFail = 3;
				$error_msg = $error_msg . $e->getMessage() . "</b><br>";
			}

			/* POC Object */
			$find_result = new poc();
			$find_result->ContactCode = $ccode; // custom
			$find_result->ContactDescription = "$agentName";
			$find_result->Status = 'Active';
			$find_result->Notes = "Created by fast_create.php API script $fdt";
			
			if(strcmp($ibOption,"csg") == 0){
			$find_result->ScriptName = 'FAST';
			} else {
			$find_result->ScriptName = 'FAST_AgentFirst';
			}
			
			$find_result->DefaultSkillNo = $SkillNo; // custom
			$find_result->MediaType = 'PhoneCall';
			$find_result->OutboundSkill = false;
			$find_result->SLASeconds = 30;
			$find_result->CampaignNo = 67833;
			$find_result->CampaignName = "FAST"; //custom
			$find_result->LastModified = $fdt;

			/* call poc_update using prev found contact code */
			try {

			$parameters = array('applicationId' => $appID, 'pointOfContact' => $find_result);
			$pocadd = array($client->PointOfContact_Update($parameters));

			} catch (Exception $e){
				$pocFail = 4;
				$error_msg = $error_msg . $e->getMessage() . "</b><br>";
			}

			} else { // else create new poc object and call poc_add

			/* POC Object */
			$newPoc = new poc();
			$newPoc->ContactCode = 0;
			$newPoc->ContactDescription = "$agentName"; // custom
			$newPoc->Status = 'Active';
			$newPoc->Notes = "Created by fast_create.php API script $fdt";
			
			if(strcmp($ibOption,"csg") == 0){
			$find_result->ScriptName = 'FAST';
			} else {
			$find_result->ScriptName = 'FAST_AgentFirst';
			}
			
			$newPoc->DefaultSkillNo = $SkillNo; // custom
			$newPoc->MediaType = 'PhoneCall';
			$newPoc->PhoneNumber = "$number"; //custom
			$newPoc->EmailAddress = '';
			$newPoc->ChatName = '';
			$newPoc->OutboundSkill = false;
			$newPoc->SLASeconds = 30;
			$newPoc->CampaignNo = 67833;
			$newPoc->CampaignName = "FAST"; //custom
			$newPoc->LastModified = $fdt;

			try {

			$parameters = array('applicationId' => $appID, 'pointOfContact' => $newPoc);
			$pocadd = array($client->PointOfContact_Add($parameters));
			$ContactCode = $pocadd[0]->PointOfContact_AddResult;

			} catch (Exception $e){
				$pocFail = 5;
				$error_msg = $error_msg . $e->getMessage() . "</b><br>";
			}

			}
		
			/*--------------------------------------- Update Users & Email Notification ---------------------------------------*/
			
			if (!$pocFail){
				//put newly created skill number in session variable
				//$_SESSION["skillno$uid"] = $SkillNo;
				
				//update the user's table with the newly created skill number, and change the approval to true
				$mysqli->query("UPDATE users SET approved='1', banned='0', skillno='$SkillNo' WHERE users_id='$uid';") or die($mysqli->error);
				
				//update farm_incontact_info w/ the skill newly created skillname
				$mysqli->query("UPDATE farm_incontact_info SET path_name='$firstname$lastname', skill='$SkillNo - $agentName', poc_contactcode='$ccode', poc='$number' WHERE users_id='$uid';") or die($mysqli->error);	
				
				// del poc from poc_list
				$mysqli->query("DELETE FROM poc_list WHERE poc = '$number'");
			}			
		} //end of check for inbound 
		
		
		
		
		
		
		// Add to array
		array_push($curappexp,$prodid);
		$pound_separated = implode("#",$curappexp);
		
		$mysqli->query("UPDATE farm_agent_info SET fast_products_approved='$pound_separated' where users_id='$uid';") or die($mysqli->error);
		echo "Product ID: $prodid has now been added to this user";
		
		//2/14/2014 added in audit trail
		$current_time = date("Y-m-d H:i:s");
		
		$browserinfo = getBrowser();
		$browser_type = $browserinfo['name'];
		$browser_version = $browserinfo['version'];
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		$audit_insert= "INSERT INTO audit_trail (affected_user_id, resp_user_id, name_of_table, field_name, field_value, ip_address, browser_type, browser_version, timestamp) values('$uid','$user_id_logged_in','farm_agent_info','fast_products_approved','$pound_separated', '$ip_address','$browser_type','$browser_version','$current_time')";
		if($mysqli->query($audit_insert)){

		}else{
			echo $mysqli->error;
		}
		
		//add in email script to notify user of approval
		include "../email_widget/email_product_approval_script.php";
   
}

/*if($type=="remove_product"){ 
    $uid = $_POST['uid'];
	 $prodid = $_POST['prodid'];
     $getuserdata = $mysqli->query("SELECT * FROM farm_agent_info where users_id='$uid';") or die($mysqli->error);
	 $userdata = $getuserdata->fetch_object();
	 $prevprods = $userdata->fast_products_approved;
	 
        //echo "Current Approved: " . $prevprods . "\n";
		$curappexp = explode("#", $prevprods);
		
		// Search
		$pos = array_search($prodid, $curappexp);
        if($pos != NULL){
		//echo "$prodid found at: " . $pos . "\n";

		//2/14/2014 added in audit trail
		$current_time = date("Y-m-d H:i:s");
		
		// Remove from array
		unset($curappexp[$pos]);
		$pound_separated = implode("#", $curappexp);
		//echo "After Approved: " . $pound_separated . "\n";
		$mysqli->query("UPDATE farm_agent_info SET fast_products_approved='$pound_separated' where users_id='$uid';") or die($mysqli->error);
		$browserinfo = getBrowser();
		$browser_type = $browserinfo['name'];
		$browser_version = $browserinfo['version'];
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		$audit_insert= "INSERT INTO audit_trail (affected_user_id, resp_user_id, name_of_table, field_name, field_value, ip_address, browser_type, browser_version, timestamp) values('$uid','$user_id_logged_in','farm_agent_info','fast_products_approved','$pound_separated', '$ip_address','$browser_type','$browser_version','$current_time')";
		if($mysqli->query($audit_insert)){

		}else{
			echo $mysqli->error;
		}
		
		echo "Product ID: $prodid has now been removed from this user";
		} else { echo "Error: Cannot un-approve product since it wasn't approved in the first place."; }
} */

?>