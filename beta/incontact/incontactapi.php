
<?php
 // Below Code is Used to Capure Errors, Remove when Unneeded
 //ini_set('display_errors', 1);
 //ini_set('log_errors', 1);
 //ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
 //error_reporting(E_ALL);
 //ini_set("soap.wsdl_cache_enabled", "0");
 // Above Code is Used to Capure Errors, Remove when Unneeded

 date_default_timezone_set('America/Chicago');	

// Set Header Variables
$username = "zachr@csgemail.com";
$password = "American210";
$url = 'https://api.incontact.com/InContactAuthorizationServer/Token';
$xmlns = 'inCloudService';
$auth = 'basic UmVhbHRpbWVfRkFTVF9BUElAQ1NHX0xMQzpPVEV5TXpVd01EazVNVFpqTkdKak1tSTFZalEyTVRWbFpHSXdNV0ZpTXpBPQ==';
$grant = 'password';
$scope = 'Admin RealTime';

 $options = array(  'soap_version' => SOAP_1_2, 
					'trace' => true, 
					'style' => SOAP_RPC,
                    'use' => SOAP_ENCODED,
					'Authorization' => $auth);

// setup a SOAP client
$client = new SOAPClient($url,$options);

// create SOAP header
$headerbody = array('grant_type' = > $grant, 'username' => $username, 'password' => $password, 'scope' => $scope);
$header = new SOAPHeader($xmlns, 'Authorization', $headerbody, false);

$client->__setSOAPHeaders($header);

//$appIDrequest = array('applicationId' => $appID);
/*$a = array('applicationId' => $appID, 'agentNo' => 133320);
$c = array($client->Agent_Find($a));
$d = array($client->Agent_GetActiveList($appIDrequest));
$e = array($client->Agent_GetActiveList($appIDrequest)->Agent_GetActiveListResult->inAgent);
*/
// client calls
?>
<?php
//$testconn = array($client->TestConnection());
//$testauth = array($client->TestAuthenticationConnection());
//$testid = array($client->TestApplicationConnection($appIDrequest));
//$testget = array($client->Agent_GetList($appIDrequest));

//var_dump($testconn);
//var_dump($testauth);
//var_dump($testid);
//var_dump($testget);


?>