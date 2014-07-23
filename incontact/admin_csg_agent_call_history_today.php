<?php
/**
* Made by Amber Bryson
* 1/2/2013
* table which will populate with the logged in agent's call history.
**/
//session_start();
include('custom_incontact_functions.php');
//include('D:\websites\csgfast.com\beta\z_scripts\db_connect.php');
include '../z_scripts/db_connect.php';
$skillno = $_GET['skillno'];
//echo "skillno = ". $skillno;
//$skillno = 148654;

	/*//grab today's count
	$count_result = $mysqli->query("SELECT COUNT(skillno) FROM csg_fast_prod.incontact_contact_list_today WHERE skillno='$skillno'");
	$count_today = mysqli_fetch_array($count_result);
	echo "Number of calls today: " . $count_today[0];*/
	
	//grab today's history
	$results = $mysqli->query("SELECT * FROM csg_fast_prod.incontact_contact_list_today WHERE skillno='$skillno'") or die($mysqli->error);
 
 /*echo "<th width='16%'><a href='#$agentno' rel='popover' data-placement='left' data-content='Vivamus sagittis lacus vel augue laoreet rutrum faucibus.' title='Popover on left' class='btn blue'>Call Description</a><th>"; */
echo"<table class='table'>
	<tbody>";
		while($row = mysqli_fetch_array($results)){
		
			$agentno = $row['agentno'];
			$agent_qry = $mysqli->query("SELECT firstname, lastname, agentno FROM csg_company.incontact_agent_list WHERE agentno='$agentno'") or die($mysqli->error);
			$agent_info = mysqli_fetch_array($agent_qry);
			
			//get minutes on the call
			$total_time = $row['totaldurationseconds']/60.0;
			$total_time = number_format($total_time,2); 
			
			//get the start and end time into a more readable format
			$start_date = new DateTime($row['startdate']);
			$end_date = new DateTime($row['enddate']);
			$start_date = $start_date->format("m/d/y g:i A");
			$end_date = $end_date->format("m/d/y g:i A");
			
			//grab call description and check if empty as to decide what to display
			$call_description = $row['disposition_comm'];	
			
			
			/*echo"<div id='notes-view-7day$agentno' class='modal container hide fade' tabindex='-1'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-hidden='true'><i class='icon-remove'></i></button>
						<h3>Call Description</h3>
					</div>
					
					<div class='modal-body'>".$call_description."</div>
					
					<div class='modal-footer'>
						<a href='#' data-dismiss='modal' aria-hidden='true' class='btn grey'>Close</a>
					</div>
				</div> ";*/
			
		echo"<tr>
				<th width='10%'>".$row['contactid']."</th>
				<th width='17%'>".$row['skillname']."</th>
				<th width='14%'>".$agent_info['firstname']." ".$agent_info['lastname']."</th>
				<th width='14%'>".$start_date."</th>
				<th width='16%'>".$end_date."</th>
				<th width='14%'>".$total_time."</th>";
				if($call_description){
					/*echo "<th width ='16%'><a href='#notes-view-7day$agentno' data-toggle='modal'><i class='icon-list-alt'></i></a></i></th>";*/
					/*echo "<th width='16%'><a href='#$agentno' rel='popover' data-placement='left' data-content='Vivamus sagittis lacus vel augue laoreet rutrum faucibus.' title='$call_description' class='btn blue'>Call Description</a><th>"; */
					echo "<th width='16%'>$call_description<th>";
					/*echo "<th width='16%'><a href='#$agentno' rel='popover' data-placement='right' data-content='hello' title='$call_description'><i class='icon-list-alt'></i></a><th>"; */
					
				}else{
					echo "<th width ='16%'><font color='red'>No notes for this call.</font></th>";
					/*echo "<th width ='16%'>No notes for this call.</th>";*/
				}
			echo"</tr>";			
		}	
	echo"</tbody>
</table>";
?>