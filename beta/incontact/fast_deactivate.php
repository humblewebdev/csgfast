<?php
/*-------------------------------------------------------------------------------------*/
/* Name: 		get_agents.php												           */
/* Author:		Eric Ruiz															   */
/* Company:		CSG																	   */
/* Date:		January 2013														   */						
/* Usage:		Deactivates campaign, skill, poc 										*/
/* Updated 3/18 to work for new site, remove campain portion							*/
/*-------------------------------------------------------------------------------------*/

include "soapconnection.php";
//include "../z_scripts/db_connect.php";

//$key = "/^FAST Testfirst_Tes_33-33-33$/";
$slen = strlen($skill);
$key = substr($skill, 9, $slen);
$final_key = "/$key/";
$active = "/Active/";
$deactivate = "InActive";

date_default_timezone_set('America/Chicago');
$dt = new DateTime('now');
$fdt = $dt->format('Y-m-d\TH:i:sP');

/* FIND SKILL */
$skillcall = array($client->Skill_GetList($appIDrequest));
$skill_list = $skillcall[0]->Skill_GetListResult->inSkill;

foreach ( $skill_list as $skill ){
	if ( preg_match($final_key, ($skill->SkillName)) && preg_match($active, ($skill->Status)) ){
		//echo $poc->ContactDescription . " " . $poc->ScriptName . "<br>";
		$skill_code = $skill->SkillNo;
	}
}

/* FIND POC */
$poccall = array($client->PointOfContact_GetList($appIDrequest));
$poclist = $poccall[0]->PointOfContact_GetListResult->inPointOfContact;

/* Creates array of available #'s */
foreach ( $poclist as $poc ){
	if ( preg_match($final_key, ($poc->ContactDescription)) && preg_match($active, ($poc->Status)) ){
		//echo $poc->ContactDescription . " " . $poc->ScriptName . "<br>";
		$poc_code = $poc->ContactCode;
	}
}

//echo "$camp_code<br>$skill_code<br>$poc_code";
	
	/* DEACTIVATE SKILL */
	$skill_params = array('applicationId' => $appID, 'skillNo' => $skill_code);
	$findSkill = array($client->Skill_Find($skill_params));
	$skillObject = $findSkill[0]->Skill_FindResult;
	
	$skillObject->Status = $deactivate;
	
	$skill_parameters = array('applicationId' => $appID, 'skill' => $skillObject);
	$updateSkill = array($client->Skill_Update($skill_parameters));
	
	/* DEACTIVATE POC */
	$poc_params = array('applicationId' => $appID, 'contactCode' => $poc_code);
	$findPoc = array($client->PointOfContact_Find($poc_params));
	$pocObject = $findPoc[0]->PointOfContact_FindResult;

	$pocObject->ContactDescription = "AVAILABLE";
	
	$poc_parameters = array('applicationId' => $appID, 'pointOfContact' => $pocObject);
	$updatePoc = array($client->PointOfContact_Update($poc_parameters));

?>