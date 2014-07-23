<?php
session_start(); 
$nam = $_SESSION['cn'];
$sam = $_SESSION['sam'];
$giv = $_SESSION['giv'];
$dir = $_SESSION['dir'];
$nummems = $_SESSION['num_mems'];
$memof = $_SESSION['mem_of'];

if($nam == NULL)
{
header("Location: index.php");
die();
}


	  

?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<div id="content">
	<div class="menu-trigger"></div>
	<h1>Welcome, <?php echo $nam;?></h1>
	<?php
	
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
	
	?>
</div>

<script> 
$('.logout').click(function() {
  $('#logoutform').submit();
});
</script>

<form action="index.php" method="GET" id="logoutform" >
<input type="submit" value="Logout" name="out" />
</form>
</body>