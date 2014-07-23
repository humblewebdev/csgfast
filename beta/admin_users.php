<?php 
/*****************************************************
* Author: Amber Bryson
* Date: 1/20/2014
* Users List
*
*
*****************************************************/

/*---------------- Query the database to get the Audit Ldap Users----------------*/
$ldap_users_qry = "SELECT * FROM users_ldap";
$ldap_users_result = $mysqli->query($ldap_users_qry);

$admin_result = $mysqli->query("SELECT * FROM users t
                              LEFT JOIN farm_agent_info t1
							  ON t.users_id = t1.users_id
							  LEFT JOIN farm_agent_staff_info t2
							  ON t.users_id = t2.users_id
							  LEFT JOIN farm_agent_staff_info t3
							  ON t.users_id = t3.users_id
							  LEFT JOIN farm_incontact_info t4
							  ON t.users_id = t4.users_id
							  LEFT JOIN products_ext t5
							  ON t.users_id = t5.users_id WHERE user_level = '5'");


?>

<!------------------------------------ Variables ---------------------------------------------->
<?php

$security = 
"<b>Level 3: LDAP User</b>This is a user who has signed in using their LDAP account. They will only be able to view
the farmer's agent list. Additional privledges will depend on if their level is upgraded.
<br><b>Level 5: Admin</b>Admin are able to approve farmers agents and their products. They are able to send emails, and update
the corrisponding templates.
<br><b>Level 7: Super Admin</b> Super admin are able to do all the functionality of LDAP and Admin, in addition to viewing this
	\"LDAP Users\" tab. They will be able to change the security level and remove other members from this list.";
	
	$securityAction = "security";
	$deleteAction = "delete";
	$deleteForm = "admin_delete_user";
	$securityForm = "admin_update_security";
	$priveleges = array(3,5,7);
	

?>


<div class="row-fluid">
	<div class="span9">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>LDAP Users</h5>
			</div>
			
			<div class="well-content no-search">
				
					<!--------------------  LDAP USERS TABLE START ------------------------
					-
					-   LDAP users list and options
					-  
					---------------------------------------------------------------------->	
						<table class="table table-striped table-bordered table-hover datatable">
						
							<thead>
								<tr>
									<th>Username</th>
									<th>Full Name</th>
									<th>Last Login</th>
									<th>Security Level</th>
									<th>Actions</th>						
								</tr>
							</thead>
							<tbody>
							
								
								<?php
									while($row = $ldap_users_result->fetch_assoc()){
									
											$user_security_form = "admin_update_security".$row['users_id'];
										echo 	"<tr><td width='20%'>".$row["user_un"]."</td>
												<td width='20%'>" .$row["user_full_name"] . "</td>
												<td width='20%'>" . $row["last_login_timestamp"] . "</td>
												<td width='15%'>". $row["user_level"]. "</td>
												<td>
													<a href='#security".$row["users_id"]."' class='orange btn-small btn' id='#security".$row["users_id"]."' data-toggle='modal' onclick='#security".$row["users_id"]."'> <i class='icon-lock'></i> Change Security Level </a>
													<a href='#delete".$row["users_id"]."' class='red btn-small btn' id='#delete".$row["users_id"]."' data-toggle='modal' onclick='#myModal".$row["users_id"]."'> <i class='icon-minus-sign'></i> Delete User </a>
												</td></tr>";
												
												/*-------------------------------------------------------------------------------------------
												* 							Modal for Change Security Level
												*
												*--------------------------------------------------------------------------------------------*/
													echo"
													
													<div id='security".$row['users_id']."' class='modal fade' style='max-height:120%;'>
															<div class='modal-header' style='background-color: #E8E8E8;'>	
																<h3><b>Change Security Level</b></h3>
															</div>

															<div class='modal-dialog'>
																<div class='modal-content'>
																	<!-- dialog body -->
																	<form id='".$user_security_form."' name='".$user_security_form."' enctype='multipart/form-data'>

																	<div class='modal-body'>
																	
																	
																		<div class='form_row'>
																		<center>
																			<select id='levelselect' name='levelselect' type='text' class='chosen'>
																				<option value=''>Select a Level</option>
																				<option value='3'>LDAP (Level 3)</option>
																				<option value='5'>Admin (Level 5)</option>
																				<option value='7'>Super Admin (Level 7)</option>
																			</select></center>
																		</div>";
																		echo $security;
																		echo "
																		<h4><center>Are you sure you want to change <b>".$row["user_full_name"]."</b>'s Security Level?<h4></center>
																		
																				
																	</div>
																	
																	<!-- dialog buttons -->
																	<div class='modal-footer' style='background-color: #E8E8E8;'>
																		<center>
																		<div class='form_row'>"; ?>
																			<button type="button" onclick="change_security_lvl('<?php echo $row['users_id']; ?>','security', '<?php echo $user_security_form; ?>')" class="btn btn-primary green">Yes</button>
																		<?php echo "<a href='#' data-dismiss='modal' aria-hidden='true' class='btn red'>No</a>
																		</div>
																		</center>	
																	</div>
																	</form>

																</div>
															</div>
													</div>
												
														";
												
												
												/*-------------------------------------------------------------------------------------------
												* 							Modal for Deleting the User
												*
												*--------------------------------------------------------------------------------------------*/
														echo"	
														<form id='admin_delete_user' name='admin_delete_user' enctype='multipart/form-data'>

															<div id='delete".$row['users_id']."' class='modal fade' style='max-height:120%;'>
															<div class='modal-header' style='background-color: #E8E8E8;'>
																<button type='button' class='close' data-dismiss='modal'>&times;</button>	
																<h3><b>Deletion Confirmation</b></h3>
															</div>

															<div class='modal-dialog'>
																<div class='modal-content'>
																<!-- dialog body -->
																	<div class='modal-body'>
																		<center><h4>Are you sure you want to delete <b>".$row["user_full_name"]."</b> from this list?</h4></center>
																		<br>
																		<font color= '#E80000'>***NOTE***</font> Deleting here will not remove the LDAP user from the database, it will only remove them from this list and remove
																		their privledges if they were admin or super admin.
																	</div>
																	
																	<!-- dialog buttons -->
																	<div class='modal-footer' style='background-color: #E8E8E8;'>
																		<div class='form_row'> "; ?>
																			<center><button type="button" onclick="delete_usr('<?php echo $row['users_id']; ?>','delete','admin_delete_user')" class="btn btn-primary green">Yes</button>
																		<?php echo "
																		<a href='#' data-dismiss='modal' aria-hidden='true' class='btn red'>No</a></div></center>
																	</div>
																</div>
															</div>
														</div>
														</form>";?>
												
								<?php	}
								
								?>
							</tbody>
						</table>
						
			</div>	<!-- well content -->
			</div><!-- well declaration --> 
		</div>
	</div>
</div>

<!--------------------------------------- Java Script ----------------------------------------->
<script src="js/jquery-1.10.2.js"></script>
<script> //custom script to update admin user
function change_security_lvl(id,action,form){
	$.ajax({
		type: 'POST',
		url: 'z_scripts/admin_user_update.php',
		data: $("#"+form).serialize()+"&ldap_users_id="+id+"&action="+action,
		success: function(data, textStatus, jqXHR){
			alert(data);
			location.reload();
		}
	});
	
}

function delete_usr(id,action){
	$.ajax({
		type: 'POST',
		url: 'z_scripts/admin_user_update.php',
		data: "ldap_users_id="+id+"&action="+action,
		success: function(data, textStatus, jqXHR){
			alert(data);
			location.reload();
		}
	});
	
}
</script>