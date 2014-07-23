<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

if(checkAdmin('') != 0){
$searchinfo = $mysqli->query("SELECT * FROM users_ldap where users_id='{$_SESSION['user_id']}';");
$userinfo = $searchinfo->fetch_assoc();

foreach($userinfo as $field => $value){
	${"info_$field"} = $value;
}
}

if(checkFarmUser('') != 0){

/**** Select all data for the current user accross multiple tables that include the users_id column******/
$searchinfo = $mysqli->query("SELECT * FROM users t
                              LEFT JOIN farm_agent_info t1
							  ON t.users_id = t1.users_id
							  LEFT JOIN farm_agent_staff_info t2
							  ON t.users_id = t2.users_id
							  LEFT JOIN farm_incontact_info t3
							  ON t.users_id = t3.users_id
							  LEFT JOIN products_ext t4
							  ON t.users_id = t4.users_id
                              WHERE t.users_id='{$_SESSION['user_id']}';");

$userinfo = $searchinfo->fetch_assoc();


foreach($userinfo as $field => $value){
	${"info_$field"} = $value;
}


$productids = explode("#", $info_fast_products);
$productids_appr = explode("#", $info_fast_products_approved);

$qproducts = $mysqli->query("SELECT * FROM fast_products WHERE product_id IN ('" . implode("','", $productids) . "');") or die($mysqli->error);
$qproducts_pend = $mysqli->query("SELECT * FROM fast_products WHERE product_id NOT IN ('" . implode("','", $productids_appr) . "') AND product_id IN ('" . implode("','", $productids) . "');") or die($mysqli->error);
$qproducts_appr = $mysqli->query("SELECT * FROM fast_products WHERE product_id IN ('" . implode("','", $productids_appr) . "') AND product_id IN ('" . implode("','", $productids) . "');") or die($mysqli->error);
$qproducts_oth = $mysqli->query("SELECT * FROM fast_products WHERE product_id NOT IN ('" . implode("','", $productids) . "');") or die($mysqli->error);

$products_signedup = array(); //Associative array of signed up items
$products_pending = array(); //Associative array of pending items
$products_approved = array(); //Associative array of approved items
$products_other = array(); // Associative array of items not yet signed up for

//Build Associative arrays that can be used throughout the program
$idpinfo = 1;
$idpinfop = 1;
$idpinfoa = 1;
$idpinfoo = 1;
while ($pinfo = $qproducts->fetch_assoc()) {
  $products_signedup[$idpinfo] = $pinfo; 
  $idpinfo++;
}

while ($pinfop = $qproducts_pend->fetch_assoc()) {
  $products_pending[$idpinfop] = $pinfop; 
  $idpinfop++;
}

while ($pinfoa = $qproducts_appr->fetch_assoc()) {
  $products_approved[$idpinfoa] = $pinfoa; 
  $idpinfoa++;
}

while ($pinfoo = $qproducts_oth->fetch_assoc()) {
  $products_other[$idpinfoo] = $pinfoo; 
  $idpinfoo++;
}


}

if(checkLdapUser('') != 0){
$searchinfo = $mysqli->query("SELECT * FROM users_ldap where users_id='{$_SESSION['user_id']}';");
$userinfo = $searchinfo->fetch_assoc();

foreach($userinfo as $field => $value){
	${"info_$field"} = $value;
}
}

?>