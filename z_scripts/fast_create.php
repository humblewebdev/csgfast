<?php
/*-------------------------------------------------------------------------------------*/
/* Name: 		fast_create.php												           */
/* Author:		Eric Ruiz & updated by Amber Bryson 12/27/2013														   */
/* Company:		CSG																	   */
/* Date:		January 2013														   */							
/* Usage:		upon portal approval auto creates campaign, skill, and poc			   */
/*-------------------------------------------------------------------------------------*/

include 'db_connect.php'; //added
include "../incontact/soapconnection.php"; //updated
include 'custom_classes.php'; //new

date_default_timezone_set('America/Chicago');
$dt = new DateTime('now');
$fdt = $dt->format('Y-m-d\TH:i:sP');

/* declaration error variables */
$error_msg;
$pocFail = 0;

$pvar = $_GET['approveid'];
//$pvar = $_POST['idpost'];  // for testing ONLY
$sql= mysql_query("SELECT * FROM users where id = $pvar") or die (mysql_error());

$row = mysql_fetch_array($sql) or die (mysql_error());

$first = $row['firstname'];
$last = $row['lastname'];
$usr = $row['user_name'];
$first = substr("$first",0,10);
$last = substr("$last",0,3);
$campName = "FAST " . $first . "_" . $last . "_" . $usr;
echo "$campName<br>";

/* Campaign Object */
echo "hello";
$newCamp = new campaign();
echo "world";
var_dump($newCamp);
$newCamp->CampaignNo = 0;
echo "campaign number = " . $newCamp->CampaignNo;
$newCamp->CampaignName = "$campName";  // custom
$newCamp->Status = 'Active';
$newCamp->Description = '';
$newCamp->Notes = "Created by fast_create.php API script $fdt";
$newCamp->LastModified = $fdt;

try {

$parameters = array('applicationId' => $appID, 'campaign' => $newCamp);

$campadd = array($client->Campaign_Add($parameters));
$CampNo = $campadd[0]->Campaign_AddResult;

} catch (Exception $e){
	$pocFail = 1;
	$error_msg = $error_msg . $e->getMessage() . "</b><br>";
}

/* Skill Object */
$newSkill = new skill();
$newSkill->SkillNo = 0;
$newSkill->SkillName = "$campName";	// custom
$newSkill->MediaType = 'PhoneCall';
$newSkill->Status = 'Active';
$newSkill->CampaignNo = $CampNo;
$newSkill->OutboundSkill = false;
$newSkill->SLASeconds = 30;
$newSkill->SLAPercent = 80;
$newSkill->Notes = "Created by fast_create.php API script $fdt";
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
$newSkill->CustomScreenPopApp = "http://csgfast.com/ipages/ipage.php?un=$usr";	// custom
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
} catch (Exception $e){
	$pocFail = 2;
	$error_msg = $error_msg . $e->getMessage() . "</b><br>";
}

$psql= mysql_query("SELECT * FROM poc_list") or die (mysql_error());
$row = mysql_fetch_array($psql) or die (mysql_error());
$assign = $row['assigned'];
$t = $row['poc'];
$number = (string)$t;
$ccode = $row['contact_code'];
echo "$ccode<br>$number<br>";


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
$find_result->ContactDescription = "$campName";
$find_result->Status = 'Active';
$find_result->Notes = "Created by fast_create.php API script $fdt";
$find_result->ScriptName = 'FAST_Menu';
$find_result->DefaultSkillNo = $SkillNo; // custom
$find_result->MediaType = 'PhoneCall';
$find_result->OutboundSkill = false;
$find_result->SLASeconds = 30;
$find_result->CampaignNo = 0;
$find_result->CampaignName = "$campName"; //custom
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
$newPoc->ContactDescription = "$campName"; // custom
$newPoc->Status = 'Active';
$newPoc->Notes = "Created by fast_create.php API script $fdt";
$newPoc->ScriptName = 'FAST_Menu';
$newPoc->DefaultSkillNo = $SkillNo; // custom
$newPoc->MediaType = 'PhoneCall';
$newPoc->PhoneNumber = "$number"; //custom
$newPoc->EmailAddress = '';
$newPoc->ChatName = '';
$newPoc->OutboundSkill = false;
$newPoc->SLASeconds = 30;
$newPoc->CampaignNo = 0;
$newPoc->CampaignName = "$campName"; //custom
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

/* if poc creation didnt fail del record, otherwise email someone */
switch ($pocFail){
	case 0:
		$upd = mysql_query("UPDATE users SET poc=$number, skill='$SkillNo - $campName' WHERE id=$pvar");
		$del = mysql_query("DELETE FROM poc_list WHERE poc = '$t'");
		break;
	case 1:
		mail("amberb@csg-email.com", "error fast_create.php at campaign_add()", $error_msg);
		break;
	case 2:
		mail("amberb@csg-email.com", "error fast_create.php at skill_add()", $error_msg);
		break;
	case 3:
		mail("amberb@csg-email.com", "error fast_create.php at poc_find()", $error_msg);
		break;
	case 4:
		mail("amberb@csg-email.com", "error fast_create.php at poc_update()", $error_msg);
		break;
	case 5:
		mail("amberb@csg-email.com", "error fast_create.php at poc_add()", $error_msg);
		break;
}

header("Location: admin.php?doSearch=Search#");
 ?>