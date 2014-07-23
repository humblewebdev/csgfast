<?php 

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 

include 'custom_incontact_functions.php';
set_time_limit(1200);
define ("DB_HOST", "localhost"); // set database host
define ("DB_USER", "root"); // set database user
define ("DB_PASS","QwertY4321"); // set database password
define ("DB_NAME","csg_company"); // set database name

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");



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
if($current_time > strtotime('00:01:00') && $current_time < strtotime('1:30:00')){ $timedrun = true; } else { $timedrun = false; }
//Only runs all queries if page is loaded between midnight and 2AM else only todays list is updated on load
if ($timedrun == true) {
    $table_loop[] = array( 
0 => array("StartDate" => $date1_s, "EndDate" => $date1_e, "TableName" => 'incontact_contact_list_today' ),
1 => array("StartDate" => $date2_s, "EndDate" => $date2_e, "TableName" => 'incontact_contact_list_yesterday' ),
2 => array("StartDate" => $date3_s, "EndDate" => $date3_e, "TableName" => 'incontact_contact_list_3' ),
3 => array("StartDate" => $date4_s, "EndDate" => $date4_e, "TableName" => 'incontact_contact_list_4' ),
4 => array("StartDate" => $date5_s, "EndDate" => $date5_e, "TableName" => 'incontact_contact_list_5' ),
5 => array("StartDate" => $date6_s, "EndDate" => $date6_e, "TableName" => 'incontact_contact_list_6' ),
6 => array("StartDate" => $date7_s, "EndDate" => $date7_e, "TableName" => 'incontact_contact_list_7' ));
} else {

$table_loop[] = array( 
0 => array("StartDate" => $date1_s, "EndDate" => $date1_e, "TableName" => 'incontact_contact_list_today' ));
}

foreach($table_loop[0] as $params){
try{
$tlstart = $params['StartDate'];
$tlend = $params['EndDate'];
$tlname = $params['TableName'];
mysql_query("DELETE FROM $tlname");
$return = get_ContactListbyRange($tlstart, $tlend);
}
catch(Exception $e){ echo $tlname . " Failed!";};

$i = 0;
//var_dump($return);

foreach($return as $contact){
try{

$i++;
$id = $i;
$contactid = $contact->ContactID;
$mastercontactid = $contact->MasterContactID;
$contactcode = $contact->ContactCode;
$skillno = $contact->SkillNo;
$skillname = $contact->SkillName;
$state = $contact->State;
$agentno = $contact->AgentNo;
$startdate = $contact->StartDate;
$outboundskill = $contact->OutboundSkill;
$teamno = $contact->TeamNo;
$campaignno = $contact->CampaignNo;
$to = $contact->To;
$from = $contact->From;
$mediatype = $contact->MediaType;
$iswarehoused = $contact->IsWarehoused;
$datecontactwarehoused = $contact->DateContactWarehoused;
$dateacwwarehoused = $contact->DateACWWarehoused;
$enddate = $contact->EndDate;
$hold_cnt = $contact->Hold_cnt;
$servicelevel = $contact->ServiceLevel;
$totaldurationseconds = $contact->TotalDurationSeconds;
$prequeueseconds = $contact->PreQueueSeconds;
$inqueueseconds = $contact->InQueueSeconds;
$agentseconds = $contact->AgentSeconds;
$posequeueseconds = $contact->PostQueueSeconds;
$abandonseconds = $contact->AbandonSeconds;
$releaseseconds = $contact->ReleaseSeconds;
$holdseconds = $contact->HoldSeconds;
$acwseconds = $contact->ACWSeconds;
$confseconds = $contact->ConfSeconds;
$disposition_code = $contact->Disposition_Code;
$disposition_comm = string_sanitize($contact->Disposition_Comments);
$abandoned = $contact->Abandoned;
$isshortabandon = $contact->IsShortAbandon;



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
)"or die(mysql_error()) ;

$push_template = mysql_query($sql)or die(mysql_error()) ;

}
catch(Exception $e){ };

}

echo "$tlname completed! <br>";

sleep (5);

}

/********************************************************************************************************************************************************/

$return2 = get_AllAgents();

mysql_query("DELETE FROM incontact_agent_list");
$i = 0;
//var_dump($return);

foreach($return2 as $allagents){
try{

$i++;
$id = $i;
$agentno = $allagents->AgentNo;
$password = $allagents->Password;
$teamno = $allagents->TeamNo;
$firstname = $allagents->FirstName;
$middlename = $allagents->MiddleName;
$lastname = $allagents->LastName;
$email = $allagents->Email;
$status = $allagents->Status;
$notes = $allagents->Notes;
$securityprofileid = $allagents->SecurityProfileID;
$teamname = $allagents->TeamName;
$lastlogin = $allagents->LastLogin;
$lastmodified = $allagents->LastModified;
$currentskill = $allagents->CurrentSkill;
$currentstate = $allagents->CurrentState;
$currentstationid = $allagents->CurrentStationId;
$currentskillno = $allagents->CurrentSkillNo;
$currentskillname = $allagents->CurrentSkillName;
$username = $allagents->UserName;
$usernamedomain = $allagents->UserNameDomain;
$reportto = $allagents->ReportTo;
$reporttoname = $allagents->ReportToName;
$reporttofirstname = $allagents->ReportToFirstName;
$reporttolastname = $allagents->ReportToLastName;
$createdate = $allagents->CreateDate;
$enddate = $allagents->EndDate;
$issupervisor = $allagents->IsSupervisor;




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

$push_template = mysql_query($sql)or die(mysql_error()) ;

}
catch(Exception $e){ };

}
echo "All Agents completed! <br>";

/********************************************************************************************************************************************************/
if ($timedrun == true) {
$return3 = get_SecurityProfileList();

mysql_query("DELETE FROM incontact_security_profile_list");
$i = 0;
//var_dump($return3);

foreach($return3 as $allprofiles){
try{

$i++;
$id = $i;
$profileid = $allprofiles->ProfileID;
$description = $allprofiles->Description;
$isexternalprofile = $allprofiles->IsExternalProfile;
$profilename = $allprofiles->ProfileName;

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
)"or die(mysql_error()) ;

$push_template = mysql_query($sql)or die(mysql_error()) ;

}
catch(Exception $e){ };

}
echo "All Security Profiles completed! <br>";
}

/********************************************************************************************************************************************************/
if ($timedrun == true) {
$return4 = skill_GetList();

mysql_query("DELETE FROM incontact_skill_list");
$i = 0;
//var_dump($return4);

foreach($return4 as $allskills){
try{

$i++;
$id = $i;
$skillno = $allskills->SkillNo;
$skillname = $allskills->SkillName;
$mediatype = $allskills->MediaType;
$status = $allskills->Status;
$campaignno = $allskills->CampaignNo;
$outboundskill = $allskills->OutboundSkill;
$notes = $allskills->Notes;
$description = string_sanitize($allskills->Description);
$campaignname = string_sanitize($allskills->CampaignName);
$lastmodified = $allskills->LastModified;


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
)"or die(mysql_error()) ;

$push_template = mysql_query($sql)or die(mysql_error()) ;

}
catch(Exception $e){ };

}
echo "All Skills Updated! <br><br>";
}

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 
echo "This page was created in ". (round(($totaltime/60), 2)) ." minutes"; 


?>

