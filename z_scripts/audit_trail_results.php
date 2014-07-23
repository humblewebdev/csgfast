<?php
include '../z_scripts/db_connect.php';
$uid = $_GET['searchval'];
$audit_qry = "SELECT * FROM audit_trail WHERE affected_user_id='$uid' OR resp_user_id='$uid'";
$audit_result = $mysqli->query($audit_qry);
?>
	<table class="table table-striped table-bordered table-hover datatable">
					<thead>
						<tr>
							<th>Audit ID</th>
							<th>Affected User's Name and ID</th>
							<th>Responsible User's Name and ID</th>
							<th>Table Affected</th>
							<th>Description</th>
							<th>IP Address</th>
							<th>Web Browser</th>
							<th>Browser Version</th>
							<th>Time</th>
						</tr>
					</thead>
					<tbody>
					
					<?php	while($row = $audit_result->fetch_assoc()){
							$id_trail = $row['id_trail'];
							$uid_affected = $row['affected_user_id'];
							$uid_resp = $row['resp_user_id'];
							$table_name = $row['name_of_table'];
							$field_name = $row['field_name'];
							$field_value = $row['field_value'];
							$ip_address = $row['ip_address'];
							$browser_type = $row['browser_type'];
							$browser_version = $row['browser_version'];
							$timestamp = $row['timestamp'];
							
							/***** Affected user query for their information *****/
							if($uid_affected > 4999){ //admin users
								$user_affected_qry = "SELECT * FROM users_ldap where users_id='$uid_affected'";
								$user_affected_result = $mysqli->query($user_affected_qry);
								$user_affected_row = mysqli_fetch_array($user_affected_result);
								$affected_name = $user_affected_row['user_full_name'];
							}else{ // farmers users
								$user_affected_qry = "SELECT * FROM users where users_id='$uid_affected'";
								$user_affected_result = $mysqli->query($user_affected_qry);
								$user_affected_row = mysqli_fetch_array($user_affected_result);
								$affected_name = $user_affected_row['full_name'];	
							}
							
							/******** Responsible user's query for their information *********/
							if($uid_resp > 4999){ //admin users
								$user_resp_qry = "SELECT * FROM users_ldap where users_id='$uid_resp'";
								$user_resp_result = $mysqli->query($user_resp_qry);
								$user_resp_row = mysqli_fetch_array($user_resp_result);
								$resp_name = $user_resp_row['user_full_name'];
							}else{ // farmers users
								$user_resp_qry = "SELECT * FROM users where users_id='$uid_resp'";
								$user_resp_result = $mysqli->query($user_resp_qry);
								$user_resp_row = mysqli_fetch_array($user_resp_result);
								$resp_name = $user_resp_row['full_name'];	
							}
								
							echo "<tr><td>$id_trail</td>
								<td>$uid_affected - $affected_name</td>
								<td>$uid_resp - $resp_name</td>
								<td>$table_name</td>
								<td>$field_name was changed to '$field_value'</td>
								<td>$ip_address</td>
								<td>$browser_type</td>
								<td>$browser_version</td>
								<td>$timestamp</td></tr>"; 
						}
						echo "</tbody>
						</table>";
?>

<script src="js/library/jquery.dataTables.js"></script>
<script src="js/datatables.js"></script>