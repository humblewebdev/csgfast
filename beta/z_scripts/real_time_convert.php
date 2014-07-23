<?php
include 'db_connect.php';

$sql = "SELECT * FROM farm_agent_info WHERE users_id='10'";

$query = $mysqli->query($sql);

$result = mysqli_fetch_assoc($query);

foreach( $result as $key => $value){
	if (preg_match("/[mtwrfsusa]open|[mtwrfsusa]close/", $key)){
		
	}
	switch(true){
		case (preg_match("/mopen/", $key)):
			echo "Monday Open: $value<br>";
			break;
		case (preg_match("/mclose/", $key)):
			echo "Monday Close: $value<br>";
			break;
		case (preg_match("/mopen/", $key)):
			echo "Monday Open<br>";
			break;
	}
}



?>