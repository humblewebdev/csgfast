<?php
/*************************** Custom Functions Index*****************************************************

(1) assignedAgents($skillNo)
(2) findbyContactID($id)
(3) skill_GetAgentStats($skillNo)
(4) skill_GetSummary($skillNo)
(5) get_LiveContacts()
(6) get_LiveAgents()
(7) get_AgentsbyTeam($teamno)
(8) string_sanitize($s)
(9) get_ContactListbyRange($start, $end)
(10) get_AllAgents()
(11) get_SecurityProfile($id)
(12) get_SecurityProfileList()
(13) agentSkill_GetList($agentno)
(14) skill_Find($skillno)
(15) skill_GetList()

//(1)/***************************************************** /Start of get assignedAgents Function ***********************************************/
function assignedAgents($skillNo){
include 'soapconnection.php';
//Get List of CSG Agents 
$d = array($client->Agent_GetList($appIDrequest));
$b = $d[0]->Agent_GetListResult->inAgent;

//Loop Through Every Agent in List and save variables to needed properties
$target = '/^FAST/';
foreach($b as $person){

//If Agent is a part of the FAST Team put them into $anums array along with their properties
if( preg_match($target, ($person->TeamName) ) ){
$anums[] = array( "AgentNo" => $person->AgentNo, "First" => $person->FirstName, "Last" => $person->LastName );
}
}

//Loop through Each Fast Agent
foreach($anums as $agent){

//Search for all skill associated with current Agent
$find = array('applicationId' => $appID, 'agentNo' => $agent['AgentNo'], 'skillNo' => $skillNo);

try{
$result = $client->AgentSkill_Find($find)->AgentSkill_FindResult;
$agentname = $agent['First'] . " " . $agent['Last'];

$assigned_agents[] = array( 
"AgentName" => $agentname, 
"AgentNo" => $result->AgentNo, 
"SkillNo" => $result->SkillNo, 
"Proficiency" => $result->Proficiency,
"LastMod" => $result->LastModified
);


}
catch(Exception $e)
{/*No Mach Do Nothing*/}

}
return $assigned_agents;
}
//(2)/***************************************************** /End of get assignedAgents Function/ Start of findbyContactID Function***********************************************/
function findbyContactID($id){
include 'soapconnection.php';
//Search for Contact ID 

$find = array('applicationId' => $appID, 'contactID' => $id);
$result = $client->Contact_Find($find)->Contact_FindResult;

var_dump($result);
}

//(3)/***************************************************** /End of findbyContactID Function/ Start of skill_GetAgentStats Function***********************************************/

function skill_GetAgentStats($skillNo){
include 'soapconnection.php';

$find = array('applicationId' => $appID, 'skillNo' => $skillNo);
$result = $client->Skill_GetAgentStats($find)->Skill_GetAgentStatsResult;

$agent_stats[] = array( 
"LoggedIn" => $result->LoggedIn, 
"Available" => $result->Available, 
"Unavailable" => $result->Unavailable, 
"ACD" => $result->ACD,
"Outbound" => $result->Outbound
);

var_dump( $agent_stats);
}

//(4)/***************************************************** /End of skill_GetAgentStats Function/ Start of skill_GetSummary Function***********************************************/

function skill_GetSummary($skillNo){
include 'soapconnection.php';

$find = array('applicationId' => $appID, 'skillNo' => $skillNo, 'startDate' => '2013-05-17T00:00:00.000-06:00', 'endDate' => '2013-05-17T23:59:00.000-06:00');
$result = $client->Skills_Summary($find);

var_dump( $result);
}

//(5)/***************************************************** /End of skill_GetSummary Function/ Start of get_LiveContacts Function***********************************************/

function get_LiveContacts(){
include 'soapconnection.php';
$d = array($client->Contact_GetLiveList($appIDrequest));
$b = $d[0]->Contact_GetLiveListResult->inContact;

return $b;
}

//(6)/***************************************************** /End of get_LiveContacts Function/ Start of get_LiveAgents Function***********************************************/

function get_LiveAgents(){
include 'soapconnection.php';
$d = array($client->Agent_GetLiveList($appIDrequest));
$b = $d[0]->Agent_GetLiveListResult->inAgent;

return $b;
}

//(7)/***************************************************** /End of get_LiveAgents Function/ Start of get_AgentsbyTeam Function***********************************************/

function get_AgentsbyTeam($teamno){
include 'soapconnection.php';
$find = array('applicationId' => $appID, 'teamNo' => $teamno);
$d = array($client->TeamAgents_GetList($find));
$b = $d[0]->TeamAgents_GetListResult->inAgent;

return $b;
}

//(8)/***************************************************** /End get_AgentsbyTeam Function/ Start of string_sanitize Function***********************************************/

function string_sanitize($s) {
    $result = preg_replace("/[^a-zA-Z0-9 ]+/", "", html_entity_decode($s, ENT_QUOTES));
    return $result;
}

//(9)/***************************************************** /End of string_sanitize function/ Start of get_ContactListbyRange Function***********************************************/

function get_ContactListbyRange($start, $end){
include 'soapconnection.php';
$parameters = array('applicationId' => $appID, 'startDate' => $start, 'endDate' => $end);
$result = $client->Contact_GetList($parameters);
$return = $result->Contact_GetListResult->inContact;

return $return;
}

//(10)/***************************************************** /End of get_ContactListbyRange/ Start of get_AllAgents Function***********************************************/

function get_AllAgents(){
include 'soapconnection.php';
$d = array($client->Agent_GetActiveList($appIDrequest));
$b = $d[0]->Agent_GetActiveListResult->inAgent;

return $b;
}

//(11)/***************************************************** /End of get_AllAgents Function/ Start of get_SecurityProfile Function***********************************************/

function get_SecurityProfile($id){
include 'soapconnection.php';
$find = array('applicationId' => $appID, 'profileID' => $id);
$d = array($client->Profile_Find($find));
$b = $d[0]->Profile_FindResult->ProfileName;

echo $b;
}

//(12)/***************************************************** /End of get_SecurityProfile Function/ Start of get_SecurityProfileList Function***********************************************/

function get_SecurityProfileList(){
include 'soapconnection.php';
$d = array($client->Profile_GetList($appIDrequest));
$b = $d[0]->Profile_GetListResult->inProfile;

return $b;
}

//(13)/***************************************************** /End of get_SecurityProfile Function/ Start of agentSkill_GetList Function***********************************************/

function agentSkill_GetList($agentno){
include 'soapconnection.php';
$find = array('applicationId' => $appID, 'agentNo' => $agentno);
$d = array($client->AgentSkill_GetList($find));
$b = $d[0]->AgentSkill_GetListResult->inAgentSkill;

return $b;
}

//(14)/***************************************************** /End of agentSkill_GetList Function/ Start of skill_Find Function***********************************************/

function skill_Find($skillno){
include 'soapconnection.php';
$find = array('applicationId' => $appID, 'skillNo' => $skillno);
$d = array($client->Skill_Find($find));
$b = $d[0]->Skill_FindResult;

echo $b->SkillName;
}

//(15)/***************************************************** /End of skill_Find Function/ Start of skill_GetList Function***********************************************/

function skill_GetList(){
include 'soapconnection.php';
$d = array($client->Skill_GetList($appIDrequest));
$b = $d[0]->Skill_GetListResult->inSkill;

return $b;
}

/*************************** End of Custom Functions *****************************************************/


?>