<?php 
/**
* Author: Zach & Updated by Amber Bryson
* 12/24/2013
* Updated script to include db connect.
* Updated depreciated MYSQL statements to mysqli format.
* Altered algorithm to try to solve 
**/
include 'custom_incontact_functions.php';
include '../z_scripts/db_connect.php';

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 

set_time_limit(1200);

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mail("amberb@csg-email.com, ericr@csg-email.com", "CSG DATABASE IS DOWN!!!!!!!", "Lost connection to Database!! Check Linux Servers! " . mysql_error(), "From: csgit@csgemail.com"));
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");

try{

//Foreach Loop to Provide all Active Users info
$date1_s = date("Y-m-d\T00:01:00");
$date1_e = date("Y-m-d\TH:i:s", strtotime("now"));
$date2_s = date("Y-m-d\T00:01:00", strtotime("now") - 24 * 3600);
$date2_e = date("Y-m-d\T20:00:00", strtotime("now") - 24 * 3600);
$date3_s = date("Y-m-d\T00:01:00", strtotime("now") - 48 * 3600);
$date3_e = date("Y-m-d\T20:00:00", strtotime("now") - 48 * 3600);
$date4_s = date("Y-m-d\T00:01:00", strtotime("now") - 72 * 3600);
$date4_e = date("Y-m-d\T22:00:00", strtotime("now") - 72 * 3600);
$date5_s = date("Y-m-d\T00:01:00", strtotime("now") - 96 * 3600);
$date5_e = date("Y-m-d\T22:00:00", strtotime("now") - 96 * 3600);
$date6_s = date("Y-m-d\T00:01:00", strtotime("now") - 120 * 3600);
$date6_e = date("Y-m-d\T22:00:00", strtotime("now") - 120 * 3600);
$date7_s = date("Y-m-d\T00:01:00", strtotime("now") - 144 * 3600);
$date7_e = date("Y-m-d\T22:00:00", strtotime("now") - 144 * 3600);

$current_time = strtotime('now');
if($current_time > strtotime('00:01:00') && $current_time < strtotime('01:30:00')){ $timedrun = true; } else { $timedrun = false; }
//Only runs all queries if page is loaded between midnight and 2AM else only todays list is updated on load
if ($timedrun == true) {
    $table_loop = array( 
0 => array("StartDate" => $date1_s, "EndDate" => $date1_e, "TableName" => 'incontact_contact_list_today' ),
1 => array("StartDate" => $date2_s, "EndDate" => $date2_e, "TableName" => 'incontact_contact_list_yesterday' ),
2 => array("StartDate" => $date3_s, "EndDate" => $date3_e, "TableName" => 'incontact_contact_list_3' ),
3 => array("StartDate" => $date4_s, "EndDate" => $date4_e, "TableName" => 'incontact_contact_list_4' ),
4 => array("StartDate" => $date5_s, "EndDate" => $date5_e, "TableName" => 'incontact_contact_list_5' ),
5 => array("StartDate" => $date6_s, "EndDate" => $date6_e, "TableName" => 'incontact_contact_list_6' ),
6 => array("StartDate" => $date7_s, "EndDate" => $date7_e, "TableName" => 'incontact_contact_list_7' ));
} else {
$table_loop = array( 
0 => array("StartDate" => $date1_s, "EndDate" => $date1_e, "TableName" => 'incontact_contact_list_today' ));
}

foreach($table_loop as $params){
$tlstart = $params['StartDate'];
$tlend = $params['EndDate'];
$tlname = $params['TableName'];

$return = get_ContactListbyRange($tlstart, $tlend);
echo $tlstart . " to ";
echo $tlend;
echo "<br>";
$mysqli->query("DELETE FROM $tlname");
$i = 0;

foreach($return as $contact){
try{

$i++;
$id = $i;
$contactid = mysql_real_escape_string ($contact->ContactID);
$mastercontactid = mysql_real_escape_string ($contact->MasterContactID);
$contactcode = mysql_real_escape_string ($contact->ContactCode);
$skillno = mysql_real_escape_string ($contact->SkillNo);
$skillname = mysql_real_escape_string ($contact->SkillName);
$state = mysql_real_escape_string ($contact->State);
$agentno = mysql_real_escape_string ($contact->AgentNo);
$startdate = mysql_real_escape_string ($contact->StartDate);
$outboundskill = mysql_real_escape_string ($contact->OutboundSkill);
$teamno = mysql_real_escape_string ($contact->TeamNo);
$campaignno = mysql_real_escape_string ($contact->CampaignNo);
$to = mysql_real_escape_string ($contact->To);
$from = mysql_real_escape_string ($contact->From);
$mediatype = mysql_real_escape_string ($contact->MediaType);
$iswarehoused = mysql_real_escape_string ($contact->IsWarehoused);
$datecontactwarehoused = mysql_real_escape_string ($contact->DateContactWarehoused);
$dateacwwarehoused = mysql_real_escape_string ($contact->DateACWWarehoused);
$enddate = mysql_real_escape_string ($contact->EndDate);
$hold_cnt = mysql_real_escape_string ($contact->Hold_cnt);
$servicelevel = mysql_real_escape_string ($contact->ServiceLevel);
$totaldurationseconds = mysql_real_escape_string ($contact->TotalDurationSeconds);
$prequeueseconds = mysql_real_escape_string ($contact->PreQueueSeconds);
$inqueueseconds = mysql_real_escape_string ($contact->InQueueSeconds);
$agentseconds = mysql_real_escape_string ($contact->AgentSeconds);
$posequeueseconds = mysql_real_escape_string ($contact->PostQueueSeconds);
$abandonseconds = mysql_real_escape_string ($contact->AbandonSeconds);
$releaseseconds = mysql_real_escape_string ($contact->ReleaseSeconds);
$holdseconds = mysql_real_escape_string ($contact->HoldSeconds);
$acwseconds = mysql_real_escape_string ($contact->ACWSeconds);
$confseconds = mysql_real_escape_string ($contact->ConfSeconds);
$disposition_code = mysql_real_escape_string ($contact->Disposition_Code);
$disposition_comm = mysql_real_escape_string (string_sanitize($contact->Disposition_Comments));
$abandoned = mysql_real_escape_string ($contact->Abandoned);
$isshortabandon = mysql_real_escape_string ($contact->IsShortAbandon);



$sql="
INSERT INTO $tlname
(
id_incontact,
contactid,
mastercontactid,
contactcode,
skillno,
skillname,
state,
agentno,
startdate,
outboundskill,
teamno,
campaignno,
to_num,
from_num,
mediatype,
iswarehoused,
datecontactwarehoused,
dateacwwarehoused,
enddate,
hold_cnt,
servicelevel,
totaldurationseconds,
prequeueseconds,
inqueueseconds,
agentseconds,
postqueueseconds,
abandonseconds,
releaseseconds,
holdseconds,
acwseconds,
confseconds,
disposition_code,
disposition_comm,
abandoned,
isshortabandon,
input_timestamp
)
VALUES
(
'$id',
'$contactid',
'$mastercontactid',
'$contactcode',
'$skillno',
'$skillname',
'$state',
'$agentno',
'$startdate',
'$outboundskill',
'$teamno',
'$campaignno',
'$to',
'$from',
'$mediatype',
'$iswarehoused',
'$datecontactwarehoused',
'$dateacwwarehoused',
'$enddate',
'$hold_cnt',
'$servicelevel',
'$totaldurationseconds',
'$prequeueseconds',
'$inqueueseconds',
'$agentseconds',
'$postqueueseconds',
'$abandonseconds',
'$releaseseconds',
'$holdseconds',
'$acwseconds',
'$confseconds',
'$disposition_code',
'$disposition_comm',
'$abandoned',
'$isshortabandon',
 NOW()
)"or die($mysqli->error) ;

$push_template = $mysqli->query($sql)or die($mysqli->error);

}
catch(Exception $e){ echo $e; };

}
echo "$tlname completed! <br>";

}

/********************************************************************************************************************************************************/

$return2 = get_AllAgents();

$mysqli->query("DELETE FROM incontact_agent_list");
$i = 0;
var_dump($return);

foreach($return2 as $allagents){
try{

$i++;
$id = $i;
$agentno = mysql_real_escape_string ($allagents->AgentNo);
$password = mysql_real_escape_string ($allagents->Password);
$teamno = mysql_real_escape_string ($allagents->TeamNo);
$firstname = mysql_real_escape_string ($allagents->FirstName);
$middlename = mysql_real_escape_string ($allagents->MiddleName);
$lastname = mysql_real_escape_string ($allagents->LastName);
$email = mysql_real_escape_string ($allagents->Email);
$status = mysql_real_escape_string ($allagents->Status);
$notes = mysql_real_escape_string ($allagents->Notes);
$securityprofileid = mysql_real_escape_string ($allagents->SecurityProfileID);
$teamname = mysql_real_escape_string ($allagents->TeamName);
$lastlogin = mysql_real_escape_string ($allagents->LastLogin);
$lastmodified = mysql_real_escape_string ($allagents->LastModified);
$currentskill = mysql_real_escape_string ($allagents->CurrentSkill);
$currentstate = mysql_real_escape_string ($allagents->CurrentState);
$currentstationid = mysql_real_escape_string ($allagents->CurrentStationId);
$currentskillno = mysql_real_escape_string ($allagents->CurrentSkillNo);
$currentskillname = mysql_real_escape_string ($allagents->CurrentSkillName);
$username = mysql_real_escape_string ($allagents->UserName);
$usernamedomain = mysql_real_escape_string ($allagents->UserNameDomain);
$reportto = mysql_real_escape_string ($allagents->ReportTo);
$reporttoname = mysql_real_escape_string ($allagents->ReportToName);
$reporttofirstname = mysql_real_escape_string ($allagents->ReportToFirstName);
$reporttolastname = mysql_real_escape_string ($allagents->ReportToLastName);
$createdate = mysql_real_escape_string ($allagents->CreateDate);
$enddate = mysql_real_escape_string ($allagents->EndDate);
$issupervisor = mysql_real_escape_string ($allagents->IsSupervisor);


$sql="
INSERT INTO incontact_agent_list
(
id_incontact,
agentno,
password,
teamno,
firstname,
middlename,
lastname,
email,
status,
notes,
securityprofileid,
teamname,
lastlogin,
lastmodified,
currentstate,
currentstationid,
currentskillno,
currentskillname,
username,
usernamedomain,
reportto,
reporttoname,
reporttofirstname,
reporttolastname,
createdate,
enddate,
issupervisor,
input_timestamp)
VALUES
(
'$id',
'$agentno',
'$password',
'$teamno',
'$firstname',
'$middlename',
'$lastname',
'$email',
'$status',
'$notes',
'$securityprofileid',
'$teamname',
'$lastlogin',
'$lastmodified',
'$currentstate',
'$currentstationid',
'$currentskillno',
'$currentskillname',
'$username',
'$usernamedomain',
'$reportto',
'$reporttoname',
'$reporttofirstname',
'$reporttolastname',
'$createdate',
'$enddate',
'$issupervisor',
NOW()
)"or die(mysql_error()) ;

$push_template = $mysqli->query($sql)or die($mysqli->error) ;

}
catch(Exception $e){ echo $e; };

}
echo "All Agents completed! <br>";

/********************************************************************************************************************************************************/
if ($timedrun == true) {
$return3 = get_SecurityProfileList();

$mysqli->query("DELETE FROM incontact_security_profile_list");
$i = 0;

foreach($return3 as $allprofiles){
try{

$i++;
$id = $i;
$profileid = mysql_real_escape_string ($allprofiles->ProfileID);
$description = mysql_real_escape_string ($allprofiles->Description);
$isexternalprofile = mysql_real_escape_string ($allprofiles->IsExternalProfile);
$profilename = mysql_real_escape_string ($allprofiles->ProfileName);

$sql="
INSERT INTO incontact_security_profile_list
(
id_incontact,
profileid,
description,
isexternalprofile,
profilename,
input_timestamp)
VALUES
(
'$id',
'$profileid',
'$description',
'$isexternalprofile',
'$profilename',
NOW()
)"or die($mysqli->error);

$push_template = $mysqli->query($sql)or die($mysqli->error) ;

}
catch(Exception $e){ echo $e; };

}
echo "All Security Profiles completed! <br>";
}

/********************************************************************************************************************************************************/
if ($timedrun == true) {
$return4 = skill_GetList();

$mysqli->query("DELETE FROM incontact_skill_list");
$i = 0;
var_dump($return4);

foreach($return4 as $allskills){
try{

$i++;
$id = $i;
$skillno = mysql_real_escape_string ($allskills->SkillNo);
$skillname = mysql_real_escape_string (str_replace("'", '"', $allskills->SkillName));
$mediatype = mysql_real_escape_string ($allskills->MediaType);
$status = mysql_real_escape_string ($allskills->Status);
$campaignno = mysql_real_escape_string ($allskills->CampaignNo);
$outboundskill = mysql_real_escape_string ($allskills->OutboundSkill);
$notes = mysql_real_escape_string ($allskills->Notes);
$description = mysql_real_escape_string (string_sanitize($allskills->Description));
$campaignname = mysql_real_escape_string (string_sanitize($allskills->CampaignName));
$lastmodified = mysql_real_escape_string ($allskills->LastModified);


$sql="
INSERT INTO incontact_skill_list
(
id_incontact,
skillno,
skillname,
mediatype,
status,
campaignno,
outboundskill,
notes,
description,
campaignname,
lastmodified,
input_timestamp
)
VALUES
(
'$id',
'$skillno',
'$skillname',
'$mediatype',
'$status',
'$campaignno',
'$outboundskill',
'$notes',
'$description',
'$campaignname',
'$lastmodified',
NOW()
)"or die($mysqli->error) ;

$push_template = $mysqli->query($sql)or die($mysqli->error) ;

}
catch(Exception $e){ echo $e; };

}
echo "All Skills Updated! <br>";
}

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 
echo "This page was created in ". (round(($totaltime/60), 2)) ." minutes"; 

 }
 catch(Exception $e){ echo $e; };
?>

