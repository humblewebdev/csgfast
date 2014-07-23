<?php
/*----------------------------------------------------------
* Written by: Amber Bryson
* Date: 1/22/2014
* Description: Called from the javascript function in 
* admin_users.php and completes the delete, reset or change
* security level of the LDAP user. 
*-----------------------------------------------------------*/
include "db_connect.php";
/*echo ini_get('display_errors');
if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}
echo ini_get('display_errors');*/

$action = $_POST["action"];
$ldap_id = $_POST["ldap_users_id"];
	
	if($action == 'security'){
		$level = $_POST["levelselect"];
		$update_security_qry = "UPDATE users_ldap SET user_level='$level' WHERE users_id='$ldap_id'";
		$mysqli->query($update_security_qry);
	
	}
	
	if($action == 'delete'){
	
		$delete_ldap_query = "DELETE FROM users_ldap WHERE users_id='$ldap_id'";
		$mysqli->query($delete_ldap_query);
	
	}
?>