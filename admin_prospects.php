<?php
/******************************
* Author: Amber Bryson
* Date: 2/22/2014
* Prospective agents info 
****************************/
// comment out when debugging is finished. 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
// comment out when debugging is finished.

?>
<!----------------------- Prospective Users Table ------------------------>
<div class="row-fluid">
	<div class="span12">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Prospective Users</h5>
			</div>
			
			<div class="well-content no-search">
				<table class="table table-striped table-bordered table-hover datatable">
					<thead>
						<tr>
							<th>Prospect's Name</th>
							<th>Agent Code</th>
							<th>Phone Number</th>
							<th>Email Address</th>
							<th>PIF</th>
							<th>Date Reminder</th>
							<th>Additional Notes</th>
							<th>Still a Prospect?</th>
							<!--<th>Update Reminder</th>
							<th>Update Prospect Status</th> -->
						</tr>
					</thead>
					
					<tbody>
						<!--<form id="prospective_user_form_update" name="prospective_user_form_update" enctype="multipart/form-data"> -->
						<?php
						$prospect_result = $mysqli->query("SELECT * FROM users_prospect");
							while($row = mysqli_fetch_array($prospect_result)){
									$uid= $row['prospect_id'];
									$name = $row['agent_name'];
									$acode = $row['agent_code'];
									$phone = $row['phone'];
									$email = $row['email'];
									$notes = $row['notes'];
									$date = $row['date_reminder'];
									$pif = $row['pif'];
									$prospect_flag = $row['prospect_flag'];
									$prospect_status_change = "prospect_status_change_$uid";
									//$prospect_status_change2 = "prospect_status_change2_$uid";
									$update_date_reminder = "update_date_reminder$uid";
									$update_date_id = "id$uid";
									$prospective_user_form_update = "prospective_user_form_update$uid";
							
									//echo $prospect_status_change;
									
								echo"<tr>
									
									<td>$name</td>
									<td>$acode</td>
									<td>$phone</td>
									<td>$email</td>
									<td>$pif</td>
									<td>$date</td>
									<td>$notes</td>";
									if($prospect_flag == 1){
										echo"<td>Yes</td>";
									}else{
										echo"<td>No</td>";
									}
									
									
									/*echo"
									<form id='$prospective_user_form_update' name='$prospective_user_form_update' enctype='multipart/form-data'>
									
									<td width='20%'>
										<div class='form_row'>
										<label class='field_name align_right'><strong>Change Reminder Date?</strong></label>
										<div class='field'>
										<div class='input-append date dynamic_date' id='$update_date_id' onclick='markActiveLink(this);event.stopPropagation();'>
											<input size='16' style='align:right;' class='span8 test' type='text' name='update_date_reminder' readonly>
											<span class='add-on'><i class='icon-calendar'></i></span>
										</div>
										</div>
										</div>
										</form>
										
									";*/?>
									<!--<div class="form_row">
										<button type="button" class="btn btn-block" onclick="prospective_user_update('update','<?php echo $prospective_user_form_update; ?>','<?php echo $uid;?>')"><i class="icon-share"></i>Update</button>
									</div>	
									</td>
									
									<td>
											
													<div class="radio"><input type="radio" class="uniform status_update" id="</?php echo $prospect_status_change; ?>" usersid="1" name="</?php echo $prospect_status_change; ?>" </?php if($prospect_flag == 1){ echo "checked"; } ?> value="1"></div> Prospect
										
										
													<div class="radio"><input type="radio" class="uniform status_update" id="</?php echo $prospect_status_change; ?>" usersid="0" name="</?php echo $prospect_status_change; ?>" value="0" </?php if($prospect_flag == 0){ echo "checked"; } ?> ></div> No longer a Prospect
											</td>-->
							
									
										
							
							<?php echo"
									
									</tr>";
							
							} 
						?>
				
					</tbody>
				</table>		
			</div>
		</div>
	</div>
</div>
<!-------------------------- Add a new prospective User ---------------------->
<div class="row-fluid">	
	<div class="span12">
		<div class="well <?php echo $info_ui_color;?>">
			<div class="well-header">
				<h5>Add a New Prospective User</h5>
			</div>
			
			<div class="well-content no-search">
				<form id="prospective_user_form" name="prospective_user_form" enctype="multipart/form-data">
					<table class="table table-striped table-bordered">
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Agent Name:</strong></label>
							<div class="field">
								<input class="required span8" name="agent_name" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Agent Code:</strong></label>
							<div class="field">
								<input class="required span8" name="agent_code" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>PIF:</strong></label>
							<div class="field">
								<input class="required span8" name="agent_pif" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Phone Number:</strong></label>
							<div class="field">
								<input class="required span8" name="agent_phone" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Email Address:</strong></label>
							<div class="field">
								<input class="required span8" name="agent_email" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td><div class="form_row">
							<label class="field_name align_left"><strong>Notes:</strong></label>
							<div class="field">
								<input class="required span8" name="agent_notes" type="text" AUTOCOMPLETE=ON>
							</div>
						</div></td></tr>
						
						<tr><td>
							<div class="form_row">
							<label class="field_name align_left"><strong>Date Reminder:</strong></label>
							<div class="field input-append date"  id="dp">
								<input size="16" class="span8" type="text" readonly name="date_reminder">
								<span class="add-on"><i class="icon-calendar"></i></span>
							</div>
						</div></tr><td>
						
						<tr><td>
							<div class="form_row">
									<button type="button" class="btn btn-block" onclick="prospective_user_update('create','prospective_user_form','none','none')"><i class="icon-share"></i>Create</button>
							</div>
						</td></tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	
</div>

<!------------------------------------ Java Script Area -------------------------------------------->
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script>
function prospective_user_update(action,formid,userid,flag){
	/*if(formid != 'prospective_user_form'){
		var pflag = $(this).attr('pflag');
		alert("pflag= "+pflag);
	} */
	
	//alert("action = "+action+" formid= "+formid+" userid= "+userid);
	//alert("form post values = "+$("#"+formid).serialize());
	$.ajax({
		type: 'POST',
		url: 'z_scripts/admin_prospective_user_update.php',
		data: $("#"+formid).serialize()+"&action="+action+"&userid="+userid,
		success: function(data, textStatus, jqXHR){
			alert(data);
			location.reload();
		}
		
	});
}



</script>

<script>
/*var update_date_id = <?php echo $update_date_id; ?>
alert(update_date_id);
$("#"+update_date_id).datepicker();

//$('#dp'+match).datepicker();*/
</script>

<script>
//window.onload = function(){
/*$('body').on('click',".dynamic_date", function(){
	var id = document.getElementById('id');
	alert(id);
    $("#dp2").datepicker();
	//$(this).datepicker();
});​
//}*/
// $('.dynamic_date').each(function(){
					// $("").datepicker();
				// });
				
				
function dynamic_date(userid){
	alert(userid);
	$("#"+userid).datepicker();
});​
</script>

<script>
$(".status_update").click(function(){
	if(confirm("Are you sure you want to change this user's prospect status?")){
	
		//var usersid = $(this).attr(usersid);
		//var  value = $(this).attr(value);
		var id = document.getElementById('usersid');
		alert("usersid="+id);
		//alert("value="+value);
		
		/*var url = "'z_scripts/admin_prospective_user_update.php'";
		
		$.ajax({
		   type: "POST",
		   url: url,
		   data: "action=status"+"&users_id="+usersid+"&value="+value, // serializes the form's elements.
		   success: function(data)
		   {
			   alert(data); // show response from the php script.
			   //if(doreload=='1'){ location.reload(); }   
		   }
		});
		
		return false;*/
	}
	location.reload();
});
</script>