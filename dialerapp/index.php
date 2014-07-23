
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
	
	$msg[] = "Logout successful";
}
 
// check to see if login form has been submitted
if(isset($_POST['userLogin'])){
    // run information through authenticator
    if(authenticate_ldap($_POST['userLogin'],$_POST['userPassword']))
    {
	    
        // authentication passed
        header("Location: main");
        die();
    } else {
        // authentication failed
        $error = 1;
    }
}
 
// output error to user
if (isset($error)) $msg[] = "Login failed: Incorrect user name, password, or rights";
 
session_start(); 
$nam = $_SESSION['cn']; 
 
if($nam != NULL)
{
header("Location: main.php");
die();
}

?>
<!doctype html>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
function closemsg(){
$("#notify_msg").slideUp();
}
</script>
<head>

	<!-- Basics -->
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Login</title>

	<!-- CSS -->
	
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/login.css">
	
</head>

	<!-- Main HTML -->
	
<body>
	
	<!-- Begin Page Content -->
	
	<?php if($msg != NULL){ ?>
	<div id="notify_msg" >
	<?php
	foreach($msg as $err){
	
	echo "<p>" . $err . "</p>";
	}
	?>
	<a onclick="closemsg()">X</a>
	</div>
	<?php } ?>
	
	<div id="container">
		
		<form method="post" action="">
		
		<label for="name">Username</label>
		
		<input type="name" name="userLogin">
		
		<label for="username">Password</label>
		
		<!--<p><a href="#">Forgot your password?</a>-->
		
		
		<input type="password" name="userPassword">
		
		<div id="lower">
		
		<input type="checkbox"><label class="check" for="checkbox">Keep me logged in</label>
		
		<input type="submit" name="submit" value="Login">
		
		</div>
		
		</form>
		
	</div>
	
	
	<!-- End Page Content -->
	
</body>

</html>
	
	
	
	
	
		
	