<?php
include 'db_connect.php';

if($_GET['command'] == 'setui'){

$users_id = $_GET['users_id'];
$color = $_GET['color'];
$tablename = $_GET['tablename'];

$updateui = $mysqli->query("UPDATE $tablename set ui_color='$color' where users_id='$users_id';") or die($mysqli->error);
$mysqli->close();

//echo "success";
}

?>