<?php 
/**
* One time script to add status toggles to the open/close status of each day, for each user
**/

include 'db_connect.php';


$select_qry = "SELECT * FROM farm_agent_info";
				
$status_result = $mysqli->query($select_qry);

while($row = mysqli_fetch_array($status_result)){
	$uid = $row['users_id'];
		if(strcmp($row['mopen'],$row['mclose']) == 0){
			$mysqli->query("UPDATE farm_agent_info SET  m_status = 0 WHERE users_id='$uid'");
		}else{
			$mysqli->query("UPDATE farm_agent_info SET  m_status = 1 WHERE users_id='$uid'");
		}
		
		if($row['topen'] == $row['tclose']){
			$mysqli->query("UPDATE farm_agent_info SET  t_status = 0 WHERE users_id='$uid'");
		}else{
			$mysqli->query("UPDATE farm_agent_info SET  t_status = 1 WHERE users_id='$uid'");
		}
		
		if($row['wopen'] == $row['wclose']){
			$mysqli->query("UPDATE farm_agent_info SET  w_status =  0 WHERE users_id='$uid'");
		}else{
			$mysqli->query("UPDATE farm_agent_info SET  w_status = 1 WHERE users_id='$uid'");
		}
		
		if($row['ropen'] == $row['rclose']){
			$mysqli->query("UPDATE farm_agent_info SET  r_status = 0 WHERE users_id='$uid'");
		}else{
			$mysqli->query("UPDATE farm_agent_info SET  r_status = 1 WHERE users_id='$uid'");
		}
		
		if($row['fopen'] == $row['fclose']){
			$mysqli->query("UPDATE farm_agent_info SET  f_status = 0 WHERE users_id='$uid'");
		}else{
			$mysqli->query("UPDATE farm_agent_info SET  f_status = 1 WHERE users_id='$uid'");
		}
		
		if($row['saopen'] == $row['saclose']){
			$mysqli->query("UPDATE farm_agent_info SET  sa_status = 0 WHERE users_id='$uid'");
		}else{
			$mysqli->query("UPDATE farm_agent_info SET  sa_status = 1 WHERE users_id='$uid'");
		}
		
		if($row['suopen'] == $row['suclose']){
			$mysqli->query("UPDATE farm_agent_info SET  su_status = 0 WHERE users_id='$uid'");
		}else{
			$mysqli->query("UPDATE farm_agent_info SET  su_status = 1 WHERE users_id='$uid'");
		} 
		
		echo "user id = " . $uid;
		echo "<br>monday = ". $row['m_status'];
		echo "<br> tuesday = ". $row['t_status'];
		echo "<br> wednesday = ".$row['w_status'];
		echo "<br> thursday = ".$row['r_status'];
		echo "<br> friday = ".$row['f_status'];
		echo "<br> saturday = ".$row['sa_status'];
		echo "<br> sunday = ".$row['su_status'];
		echo"<br><br><br>";
		

}

?>