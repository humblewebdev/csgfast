<?php
include ("db_session.php");

$un = $_POST['un'];
$btype = $_POST['bucketype'];
$recid = $_POST['recid'];

if($un != NULL && $btype != NULL){
$update_user = mysql_query("UPDATE dialer_list_user_profiles SET user_current_bucket = '$btype' WHERE user_un = '$un'") or die(mysql_error());

if($btype == 'none'){
echo "$un is no longer assigned to any buckets!";
} else {
echo "$un has now been assigned to the ". ucwords($btype) . " bucket!";
}
}

if($un != NULL && $recid != NULL){
$users_link_list = mysql_query("SELECT * FROM dialer_list_user_profiles WHERE user_current_record='$recid'");
$users_link_list_name = mysql_fetch_array($users_link_list);
$fullname = $users_link_list_name['users_full_name'];
$count_assigned_users = mysql_num_rows($users_link_list); 

if($count_assigned_users == 0 || $un == $sam || $un != $sam){
$update_user = mysql_query("UPDATE dialer_list_user_profiles SET user_current_record = '$recid' WHERE user_un = '$un'") or die(mysql_error());
}

}

?>