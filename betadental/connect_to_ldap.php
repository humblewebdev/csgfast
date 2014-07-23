<?php

$ldap_server = "ldap://culture.local";
$auth_user = "administrator@culture.local";
$auth_pass = "L3v1t0n5";

// Set the base dn to search the entire directory.

$base_dn = "DC=culture, DC=local";

// Show only user persons
$filter = "(&(objectClass=user)(objectCategory=person)(cn=*))";

// Enable to show only users
// $filter = "(&(objectClass=user)(cn=$*))";

// Enable to show everything
// $filter = "(cn=*)";

// connect to server

if (!($connect=@ldap_connect($ldap_server))) {
     die("Could not connect to ldap server");
}

// bind to server

if (!($bind=@ldap_bind($connect, $auth_user, $auth_pass))) {
     die("Unable to bind to server");
}

//if (!($bind=@ldap_bind($connect))) {
//     die("Unable to bind to server");
//}

// search active directory

if (!($search=@ldap_search($connect, $base_dn, $filter))) {
     die("Unable to search ldap server");
}

$number_returned = ldap_count_entries($connect,$search);
$info = ldap_get_entries($connect, $search);

echo "The number of entries returned is ". $number_returned."<p>";

for ($i=0; $i<$info["count"]; $i++) {
   echo "Name is: ". $info[$i]["name"][0]."<br>";
   echo "Display name is: ". $info[$i]["displayname"][0]."<br>";
   echo "Email is: ". $info[$i]["mail"][0]."<br>";
   echo "Telephone number is: ". $info[$i]["telephonenumber"][0]."<p>";
}
?>