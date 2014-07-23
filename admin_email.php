<?php 
/*****************************************************
* Author: Amber Bryson
* Date: 1/20/2014
* Admin Email
*
*
*****************************************************/

/*---------------- Query the database to get the Audit list----------------*/
$email_qry = "SELECT * FROM email_templates";
$email_result = $mysqli->query($email_qry);

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

/*---- url for when the user chooses to edit a template ---------*/
if(isset($_GET['temp_id'])){

	$temp_id = $_GET['temp_id'];
	$edit_temp_qry  = "SELECT * FROM email_templates WHERE id_email_template ='$temp_id'";
	
	$edit_temp_result = $mysqli->query($edit_temp_qry);
	
	$edit_row = mysqli_fetch_array($edit_temp_result);

	$edit_desc = $edit_row['template_desc'];
	$edit_title = $edit_row['template_title'];
	$edit_content = $edit_row['template_content'];
		
}

/*----------- url for when the user chooses to send a template -----------*/
if(isset($_GET['send_id'])){

	$send_id = $_GET['send_id'];
	$send_temp_qry  = "SELECT * FROM email_templates WHERE id_email_template ='$send_id'";
	
	$send_temp_result = $mysqli->query($send_temp_qry);
	
	$send_row = mysqli_fetch_array($send_temp_result);

	$send_desc = $send_row['template_desc'];
	$send_title = $send_row['template_title'];
	$send_content = $send_row['template_content'];
		
}

?>


<div class="row-fluid">
	<div class="span12">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Email Templates</h5>
			</div>
			
			<div class="well-content no-search">
			<table class="table table-striped table-bordered table-hover datatable">
			<thead>
				<tr>
					<th>Template Title</th>
					<th>Description</th>
					<th>Creation Date</th>
					<th>Maker</th>
					<th>Last Change</th>
					<th>Last Updated By</th>
					<th>Options</th>				
				</tr>
			</thead>
			<tbody>
				<?php
				while($row = $email_result->fetch_assoc()){
					$edit_temp = "#edit_temp".$row['id_email_template'];
					$edit_url = "home_page_admin.php?ic=admin_email&temp_id=".$row['id_email_template'];
					$send_url = "home_page_admin.php?ic=admin_email&send_id=".$row['id_email_template'];
				echo "<tr>
						<td>". $row["template_title"] ."</td>
						<td>". $row["template_desc"] ."</td>
						<td>". $row["template_timestamp"] ."</td>
						<td>". $row["template_created_by_fullname"] ."</td>
						<td>". $row["template_last_updated_timestamp"] ."</td>
						<td>". $row["template_last_updated_by_fullname"] ."</td>
						<td width='12%'><a href='".$edit_url."' id='".$edit_temp."' class='blue btn btn-primary'><i class='icon-pencil'></i>Edit</a>
						<a href='".$send_url."' id='".$edit_temp."' class='green btn btn-primary'><i class='icon-envelope'></i>Send</a>
						</td>
					</tr>"; ?>
				
			<?php	}//end of email template loop ?>
			</tbody>
			</table>
			</div> <!-- well content -->
		</div> <!-- well declaration -->
	</div> <!-- End of  1st span 12 -->
</div> <!-- End of Row-fluid -->

<!------------------------ Start of Template Options area -------------------------------------------->
	<div class="row-fluid">
	<div class="span12">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Template Options</h5>
			</div>
			<div class="well-content no-search">
				<div class="row-fluid">
				<div class="span9">
				<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
					<li <?php if((isset($send_id)) || $temp_id == ''){ echo 'class=active'; } ?>><a href="#sendemail" data-toggle="tab"><strong>Send Email</strong></a></li>
					<li <?php if(isset($temp_id)){ echo 'class=active'; } ?>><a href="#edittemp" data-toggle="tab"><strong>Edit Existing Template</strong></a></li>
					<li><a href="#newtemp" data-toggle="tab"><strong>Create New Template</strong></a></li>
				</ul>
			
				<div id="my-tab-content" class="tab-content">
					<!------------------------------------ 
					- 
					- 			SEND EMAIL TAB
					-
					-------------------------------------->
					<div class="tab-pane <?php if((isset($send_id)) || $temp_id == ''){ echo 'active'; } ?>" id="sendemail">
						<form id='send_email_form' name='send_email_form' enctype='multipart/form-data'>
						<table class="table table-striped table-bordered">
						
						<tr><td>
						<a href='home_page_admin.php?ic=admin_email' class='red btn btn-primary btn-block'><i class='icon-remove-sign'></i>Clear Email Template</a>
						</tr></td>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>To: </strong>(Multiple selections allowed)</label>
							<div class="field">
								<select class="chosen span9" name="send_to[]" type="text" multiple>
								<?php
									$agent_qry = "SELECT firstname,lastname,email,users_id FROM users;";
									$agent_list = $mysqli->query($agent_qry);
									
								/*	$ldap_qry = "SELECT firstname,lastname,email,users_id FROM users_ldap;";
									$ldap_list = $mysqli->query($ldap_qry); */
									while($agent_row = mysqli_fetch_array($agent_list)){
										echo "<option value='{$agent_row['users_id']}'>{$agent_row['firstname']} {$agent_row['lastname']} | {$agent_row['email']}</option>";
									}
									/*while($agent_row = mysqli_fetch_array($agent_list)){
										echo "<option value='{$agent_row['users_id']}'>{$agent_row['firstname']} {$agent_row['lastname']} | {$agent_row['email']}</option>";
									}*/
								?>
								</select>	
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>CC:</strong></label>
							<div class="field">
								<input class="required span9" name="send_email_cc"  type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>BCC:</strong></label>
							<div class="field">
								<input class="required span9" name="send_email_bcc"  type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Subject:</strong></label>
							<div class="field">
								<input class="required span9" value="<?php if(isset($send_title)){echo $send_title; } ?>" name="send_email_title" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Email Body:</strong></label>
							<div class="field">
								<textarea class="textarea required" name="send_email_content" style="width: 100%; height: 300px">
								<?php
									if(isset($send_content)){
										echo $send_content;
									}
								?>
								</textarea>
							</div>
						</div></td></tr>
						
						<tr><td>
						
						</td></tr>
						</table>
					</form>
					<div class="form_row">
						<button type="button" class="green btn btn-block" onclick="update_email_template('send','send_email_form','<?php if(isset($send_id)){ echo $send_id; }else{ echo 'none';} ?>')"><i class="icon-share"></i> Send Email</button>
					</div>
					</div><!-- End of Send email Tab-->
					
					
					
					<!------------------------------------ 
					- 
					- EDIT EMAIL TEMPLATE TAB
					-
					-------------------------------------->
					<div class="tab-pane <?php if(isset($temp_id)){ echo 'active'; } ?>" id="edittemp">
					<form id='edit_template_form' name='edit_template_form' enctype='multipart/form-data'>
						<table class="table table-striped table-bordered">
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Title of Template:</strong></label>
							<div class="field">
								<input class="required span8" value="<?php if(isset($edit_title)){echo $edit_title; } ?>" placeholder="Please choose one of the templates above to edit..." name="edit_template_title" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Description:</strong></label>
							<div class="field">
								<input class="required span8" value="<?php if(isset($edit_desc)){ echo $edit_desc; } ?>" placeholder="Please choose one of the templates above to edit..." name="edit_template_desc" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Template Content:</strong></label>
							<div class="field">
								<textarea class="textarea required" name="edit_template_content" placeholder="Please choose one of the templates above to edit..." style="width: 100%; height: 300px">
								<?php
									if(isset($edit_content)){
										echo $edit_content;
									}
								?>
								</textarea>
							</div>
						</div></td></tr>
						
						<tr><td>
						
						</td></tr>
						</table>
					</form>
					<div class="form_row">
						<button type="button" class="btn btn-block" onclick="update_email_template('edit','edit_template_form','<?php if(isset($temp_id)){ echo $temp_id; } ?>')"><i class="icon-share"></i> Update</button>
					</div>
					</div> <!-- end of edit tab -->
					
					<!------------------------------------ 
					- 
					- CREATE NEW TEMPLATE TAB
					-
					-------------------------------------->
					<div class="tab-pane" id="newtemp">
						<form id='new_template_form' name='new_template_form' enctype='multipart/form-data'>
						<table class="table table-striped table-bordered">
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Email Subject:</strong></label>
							<div class="field">
								<input class="required span8" name="create_template_title" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Description:</strong></label>
							<div class="field">
								<input class="required span8" name="create_template_desc" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Template Content:</strong></label>
							<div class="field">
								<textarea class="textarea" name="create_template_content" placeholder="Enter the email content here" style="width: 100%; height: 300px"></textarea>
							</div>
						</div></td></tr>
						
						<tr><td>
						
						</td></tr>
						</table>
					</form>
					<div class="form_row">
						<!-- <br><a href="#" class="btn btn-block update_email_template" dorefresh="1"><i class="icon-share"></i> Update</a> -->
						<button type="button" class="btn btn-block" onclick="update_email_template('create','new_template_form','none')"><i class="icon-share"></i>Create</button>
					</div>
					</div>
				</div><!-- End of tab content -->
				</div><!--End of span 9 -->
				
				<!---------------------------
				- Short Code Table Area
				----------------------------->
				<div class="span3">
					<u><strong>Shortcode Variables</strong></u><br><br>
						<table class="table-striped table-bordered" style="width: 300px;">
						<tr><td><b>First Name</b></td><td>[first]</td></tr>
						<tr><td><b>Last Name</b></td><td>[last]</td></tr>
						<tr><td><b>Full Name</b></td><td>[full]</td></tr>
						<tr><td><b>User Name</b></td><td>[un]</td></tr>
						<tr><td><b>User Email</b></td><td>[useremail]</td></tr>
						<tr><td><b>POC</td><td>[poc]</td></tr>
						<tr><td><b>Approve Start Date&nbsp;&nbsp;</b></td><td>[approvedate]</td></tr>
						<tr><td><b>Agents Mainline</b></td><td>[mainline]</td></tr>
						<tr><td><b>Agent Code</b></td><td>[agentcode]</td></tr>
						<tr><td><b>Reg. Date</b></td><td>[regdate]</td></tr>
						<tr><td><b>PIF Size</b></td><td>[pif]</td></tr>
						<tr><td><b>Last Portal Update</b></td><td>[portal]</td></tr>
						<tr><td><b>Last Login</b></td><td>[lastlogin]<br>
						<tr><td><b>Last PWD Change</b></td><td>[lastpwd]</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td><b>Your Full Name</b></td><td>[myfull]</td></tr>
						<tr><td><b>Your Email</b></td><td>[myemail]</td></tr>
						</table>
				</div> <!-- End of span 4 -->
				</div><!-- end of inner row fluid -->
			</div><!-- End of well content -->
		</div><!-- End of well delcaration -->
	</div> <!-- end of 2nd span 12 -->
</div><!-- End of 2nd Row Fluid-->


<!------------------------------------ Java Script Area -------------------------------------------->
<script src="js/jquery-1.10.2.js"></script>
<script>
function update_email_template(action,formid,emailid){    
	
	if(emailid == ''){
		alert("Please choose a template to update");
	}else{	
		$.ajax({
			type: 'POST',
			url: 'z_scripts/email_template_update.php',
			data: $("#"+formid).serialize()+"&action="+action+"&emailid="+emailid,
			success: function(data, textStatus, jqXHR){
			    alert(data);
				location.reload();
			}
		});
	}
}
</script>