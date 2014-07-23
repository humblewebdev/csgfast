<?php
include("authenticate.php");
 
// check to see if user is logging out
if(isset($_GET['out'])) {
    session_start();
    // destroy session
    session_unset();
    $_SESSION = array();
    unset($_SESSION['user'],$_SESSION['access'],$_SESSION['cn'],$_SESSION['sam'],$_SESSION['giv'],$_SESSION['dir'],$_SESSION['num_mems'],$_SESSION['mem_of']);
    session_destroy();
	
	echo "Logout successful<br />";
}
 
// check to see if login form has been submitted
if(isset($_POST['userLogin'])){
    // run information through authenticator
    if(authenticate_ldap($_POST['userLogin'],$_POST['userPassword']))
    {
	    
        // authentication passed
        header("Location: home_secure.php");
        die();
    } else {
        // authentication failed
        $error = 1;
    }
}
 
// output error to user
if (isset($error)) echo "Login failed: Incorrect user name, password, or rights<br />";
 

?>
 
<form method="post" action="login.php">
    <table>
	<tr>
    <td>User: </td><td><input type="text" name="userLogin" /></td>
	</tr>
	<tr>
    <td>Password: </td><td><input type="password" name="userPassword" /></td>
	</tr>
	</table>
	<input type="submit" name="submit" value="Submit" />
</form>