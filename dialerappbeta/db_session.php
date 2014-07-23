<?php 
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

date_default_timezone_set('America/Chicago');
session_start(); 
$nam = $_SESSION['cn'];
$sam = $_SESSION['sam'];
$giv = $_SESSION['giv'];
//$dir = $_SESSION['dir'];
$nummems = $_SESSION['num_mems'];
$memof = $_SESSION['mem_of'];
array_shift($memof);
foreach($memof as $memval){ $mev = explode(",",$memval); $mev2 = explode("=", $mev[0]); $memberlist[] = $mev2[1]; }

if($nam == NULL)
{
header("Location: index.php");
die();
}



function formatPhone($num) {
    $num = preg_replace('/[^0-9]/', '', $num);
    $len = strlen($num);

    if($len == 7) $num = preg_replace('/([0-9]{2})([0-9]{2})([0-9]{3})/', '$1 $2 $3', $num);
    elseif($len == 8) $num = preg_replace('/([0-9]{3})([0-9]{2})([0-9]{3})/', '$1 - $2 $3', $num);
    elseif($len == 9) $num = preg_replace('/([0-9]{3})([0-9]{2})([0-9]{2})([0-9]{2})/', '$1 - $2 $3 $4', $num);
    elseif($len == 10) $num = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3 ', $num);

    return $num;
}

function birthDate($date) {
$bday = substr($date,0,2)."/".substr($date,2,2)."/".substr($date,4,4);
return $bday;
}

	  
include 'dbc.php'; 

$search_profiles = mysql_query("SELECT * FROM dialer_list_user_profiles WHERE user_un='$sam'") or die(mysql_error());
$prof_count = mysql_num_rows($search_profiles);

if($prof_count == 0 && $sam != NULL){
$add_user_profile = mysql_query("
	INSERT INTO dialer_list_user_profiles
	(`user_un`,`user_full_name`,`user_member_of`,`user_last_activity`)

	VALUES 
	(
	'$sam',
	'$nam',
    '$sam',
	NOW()
	)
	;");
} else { $update_profile = mysql_query("UPDATE dialer_list_user_profiles SET user_last_activity=NOW() WHERE user_un='$sam'"); }
?>