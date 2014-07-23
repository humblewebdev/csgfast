<?php 

//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE)

include 'z_scripts/db_connect.php';

page_protect(); //Protects the page against unlogged in users and sets session variables

if(checkAdmin('')){ header('Location: home_page_admin.php'); exit; }

if(checkFarmUser('')){ header('Location: home_page_farm.php'); exit; }

if(checkLdapUser('')){ header('Location: home_page_ldap.php'); exit; }
?>