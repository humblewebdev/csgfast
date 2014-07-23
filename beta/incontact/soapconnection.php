
<?php
 // Below Code is Used to Capure Errors, Remove when Unneeded
 //ini_set('display_errors', 1);
 //ini_set('log_errors', 1);
 //ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
 //error_reporting(E_ALL);
/* amber commented out */ //ini_set("soap.wsdl_cache_enabled", "0");
 // Above Code is Used to Capure Errors, Remove when Unneeded

date_default_timezone_set('America/Chicago');	
$dt = new DateTime('now');
$fdt = $dt->format('Y-m-d\TH:i:sP');

// Set Header Variables
$username = "CultureServiceGrowth";
$password = "CSGPass!";
$appID = 'b0643d47-3c20-4c95-969f-caab2841f9fa';
$url = 'https://incloudws.incontact.com/inCloudWS.asmx?WSDL';
$xmlns = 'inCloudService';

 $options = array(  'soap_version' => SOAP_1_2, 
					'trace' => true, 
					'style' => SOAP_RPC,
                    'use' => SOAP_ENCODED,
					'Username' => $username, 
					'Password' => $password);

// setup a SOAP client
$client = new SOAPClient($url,$options);

// create SOAP header
$headerbody = array('Username' => $username, 'Password' => $password);
$header = new SOAPHeader($xmlns, 'AuthenticationHeader', $headerbody, false);
$client->__setSOAPHeaders($header);
$appIDrequest = array('applicationId' => $appID);

?>
