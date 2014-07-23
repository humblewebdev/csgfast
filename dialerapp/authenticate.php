<?php
/*
function authenticate($user, $password) {
    // Active Directory server
    $ldap_host = "culture.local";
 
    // Active Directory DN
    $ldap_dn = "CN=Users,DC=culture,DC=local";
 
    // Active Directory user group
    $ldap_user_group = "Admins";
 
    // Active Directory manager group
    $ldap_manager_group = "Users";
 
    // Domain, for purposes of constructing $user
    $ldap_usr_dom = "@culture.local";
 
    // connect to active directory
    $ldap = ldap_connect($ldap_host);
 
    // verify user and password
    if($bind = @ldap_bind($ldap, $user . $ldap_usr_dom, $password)) {
        // valid
        // check presence in groups
        $filter = "(sAMAccountName=" . $user . ")";
        $attr = array("memberof");
        $result = ldap_search($ldap, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
        $entries = ldap_get_entries($ldap, $result);
        ldap_unbind($ldap);
        $user_info = $entries[0]['dn'];
		echo $user_info;
		
        // check groups
        foreach($entries[0]['memberof'] as $grps) {
            // is manager, break loop
            if (strpos($grps, $ldap_manager_group)) { $access = 2; break; }
 
            // is user
            if (strpos($grps, $ldap_user_group)) $access = 1;
        }
 
        if ($access != 0) {
            // establish session variables
            $_SESSION['user'] = $user;
            $_SESSION['access'] = $access;
            return true;
        } else {
            // user has no rights
            return false;
        }
 
    } else {
        // invalid name or password
        return false;
    }
}

*/
/**************************************************
  Bind to an Active Directory LDAP server and look
  something up. 
***************************************************/
function authenticate_ldap($user, $password){
  $SearchFor=$_POST['userLogin'];               //What string do you want to find?
  $SearchField="samaccountname";   //In what Active Directory field do you want to search for the string?

  $LDAPHost = "10.2.8.26";       //Your LDAP server DNS Name or IP Address
  $dn = "DC=culture,DC=local"; //Put your Base DN here
  $LDAPUserDomain = "@culture.local";  //Needs the @, but not always the same as the LDAP server domain
  $LDAPUser = $_POST['userLogin'];        //A valid Active Directory login
  $LDAPUserPassword = $_POST['userPassword'];
  $LDAPFieldsToFind = array("cn", "givenname", "samaccountname", "homedirectory", "telephonenumber", "mail", "memberof");
   
  $access = 0;
  $access_admin = 0;
  $cnx = ldap_connect($LDAPHost) or die("Could not connect to LDAP");
  ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
  ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
  $ldapbind = ldap_bind($cnx,$LDAPUser.$LDAPUserDomain,$LDAPUserPassword);
  error_reporting (E_ALL ^ E_NOTICE);   //Suppress some unnecessary messages
  $filter="($SearchField=$SearchFor)"; //Wildcard is * Remove it if you want an exact match
  $sr=ldap_search($cnx, $dn, $filter, $LDAPFieldsToFind);
  $info = ldap_get_entries($cnx, $sr);
  //var_dump($info);
  for ($x=0; $x<$info["count"]; $x++) {
    $sam=$info[$x]['samaccountname'][0];
    $giv=$info[$x]['givenname'][0];
    $tel=$info[$x]['telephonenumber'][0];
    $email=$info[$x]['mail'][0];
    $nam=$info[$x]['cn'][0];
    $dir=$info[$x]['homedirectory'][0];
    $dir=strtolower($dir);
    $pos=strpos($dir,"home");
    $pos=$pos+5;
	$memof=$info[$x]['memberof'];
	$nummems = count($memof);
	if($nummems > 0){$nummems = $nummems - 1;}
	/*
    if (stristr($sam, "$SearchFor") && (strlen($giv) >= 0)) {
	  print "Active Directory says that...<br>";
	  print "<table border='1' padding='5px'>";
      print "<tr><td>CN is: </td><td>$nam</td></tr>";
      print "<tr><td>SAMAccountName is: </td><td>$sam</td></tr>";
      print "<tr><td>Given Name is: </td><td>$giv</td></tr>";
      print "<tr><td>Telephone is: </td><td>$tel</td></tr>";
      print "<tr><td>Home Directory is: </td><td>$dir</td></tr>";
	  print "<tr><td>Member of: </td><td><u>$nummems Total Groups</u><br>";
	  foreach(array_slice($memof,1) as $memi)
	  {
	  $memi0 = explode("=", $memi);
	  $memi1 = explode(",", $memi0[1]);
	  if($memi1[0] == "BetaDental"){$access = 1;}
	  if($memi1[0] == "BetaDental Supervisor"){$access_admin = 1;}
	  if($memi1[0] == "BetaDental Supervisor"){$access = 2;}
	  echo $memi1[0] . "<br>";
	  }
	  print "</td></tr>";
	  print "</table><br>";
    }
	*/
   session_start();
   $_SESSION['user'] = $user;
   $_SESSION['access'] = $access + $access_admin;
   $_SESSION['cn'] = $nam;
   $_SESSION['sam'] = $sam;
   $_SESSION['giv'] = $giv;
   $_SESSION['mem_of'] = $memof;
   $_SESSION['num_mems'] = $nummems;
   return true;	
  }   
  //if ($x==0) { $msg[] = "Oops, that username or password is invalid..."; }

}//End of Function
?>