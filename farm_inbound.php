<?php 
	/**
	* Author: Amber Bryson
	* Created: 1/7/2014
	* Farmers Agent inbound products page.
	**/
	error_reporting(0);
	page_protect();
	checkFarmUser("logout");
	//session_start();
	$skillno = $_SESSION['skillno'];


	//grab count for last 7 days
	$count_result = $mysqli->query("SELECT 
	(SELECT COUNT(skillno) FROM csg_fast_prod.incontact_contact_list_today WHERE skillno='$skillno') +
	(SELECT COUNT(skillno) FROM csg_fast_prod.incontact_contact_list_yesterday WHERE skillno='$skillno') +
	(SELECT COUNT(skillno) FROM csg_fast_prod.incontact_contact_list_3 WHERE skillno='$skillno') +
	(SELECT COUNT(skillno) FROM csg_fast_prod.incontact_contact_list_4 WHERE skillno='$skillno') +
	(SELECT COUNT(skillno) FROM csg_fast_prod.incontact_contact_list_5 WHERE skillno='$skillno') +
	(SELECT COUNT(skillno) FROM csg_fast_prod.incontact_contact_list_6 WHERE skillno='$skillno') +
	(SELECT COUNT(skillno) FROM csg_fast_prod.incontact_contact_list_7 WHERE skillno='$skillno') ;");
	$count_7days = mysqli_fetch_array($count_result);

	//grab today's count
	$count_result = $mysqli->query("SELECT COUNT(skillno) FROM csg_fast_prod.incontact_contact_list_today WHERE skillno='$skillno'");
	$count_today = mysqli_fetch_array($count_result);
?>

<div class="row-fluid">
	<div class="span12">
		<div class="well <?php echo $info_ui_color;?>"> <!-- Inbound Well declaration -->
			
			<!--------------- Inbound Well Header --------------->
			<div class="well-header">
				<h5>Inbound Services</h5>
			</div>
		
			<!--------------- Inbound Well Content --------------->
			<div class="well-content no-search">
				
				<!----------------- Add more tab names here ----------------->
				<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
					<li class="active"><a href="#notesinbound" data-toggle="tab"><strong>Inbound Options</strong></a></li>
					<li><a href="#callhistorytoday" data-toggle="tab"><strong>Call History: Today</strong> (Total: <?php echo $count_today[0];?> )</a></li>
					<li><a href="#callhistory7days" data-toggle="tab"><strong>Call History: Last 7 days</strong> (Total: <?php echo $count_7days[0];?> )</a></li>
					<!--<li><a href="#inboundoptions" data-toggle="tab"><strong>Inbound Options</strong></a></li>-->
								
				</ul>
				
				<!----------------------- Tab Content ----------------------
				-   Display all agency information in series of tabs
				-   logically displaying general information and product specific information
				-   
				-   Make the first tab be the most frequently updated info.
				 ---------------------------------------------->
				<div id="my-tab-content" class="tab-content">
				
				

					<!-------------------- INBOUND OPTIIONS TAB ----------
					-
					-   Show agent notes & their options for inbound prodct
					-  
					------------------------------------------------------------------->			
			<div class="tab-pane active" id="notesinbound">
				 <div class="row-fluid">
										
								
						
						<form id="prodid1">
						<?php $callchoice = explode('#', $info_calltypes_notes);?>
						<?php $callchoice2 = explode('#', $info_calltypes_notes2);?>
							
							<div class="well span4 <?php echo $info_ui_color; ?>">
								<div class="well-header">
									<h5>Call Routing</h5>
								</div>
								<div class="well-content"> 
								<table class="table table-striped table-bordered">
								   <thead>
									  <tr>
										<th><h4>Call Types</h4></th>
										 <th><h4>Farmers</h4></th>
										 <th><h4>My Office</h4></th>
										 
									  </tr>
								   </thead>
								   <tbody>
										<tr>
											<td>Cancellations</td>
											<td><input type="radio" class="uniform cn0 calltypes" name="cn0" value="0_seamless" <?php if($info_calltypes_notes == NULL || $callchoice[0] == '0_seamless'){echo "checked";} ?>/></td>
											<td><input type="radio" class="uniform cn0 calltypes" name="cn0" value="0_office" <?php if($callchoice[0] == '0_office'){echo "checked";} ?>/></td>
											
										</tr>
										<tr>
											<td>Coverage Changes</td>
											<td align="center"><input type="radio" class="uniform cn1 calltypes" name="cn1" value="1_seamless" <?php if($info_calltypes_notes == NULL || $callchoice[1] == '1_seamless'){echo "checked";} ?>/></td>
											<td align="center"><input type="radio" class="uniform cn1 calltypes" name="cn1" value="1_office" <?php if($callchoice[1] == '1_office'){echo "checked";} ?>/></td>
											
										</tr>
										<tr>
											<td>Claims</td>
											<td align="center"><input type="radio" class="uniform cn2 calltypes" name="cn2" value="2_seamless" <?php if($info_calltypes_notes == NULL || $callchoice[2] == '2_seamless'){echo "checked";} ?>/></td>
											<td align="center"><input type="radio" class="uniform cn2 calltypes" name="cn2" value="2_office" <?php if($callchoice[2] == '2_office'){echo "checked";} ?>/></td>
											
										</tr>
										<tr>
											<td>New Business</td>
											<td align="center"><input type="radio" class="uniform cn3 calltypes" name="cn3" value="3_seamless" <?php if($info_calltypes_notes == NULL || $callchoice[3] == '3_seamless'){echo "checked";} ?>/></td>
											<td align="center"><input type="radio" class="uniform cn3 calltypes" name="cn3" value="3_office" <?php if($callchoice[3] == '3_office'){echo "checked";} ?>/></td>
											
										</tr>
								   </tbody>
								</table>
								<!--<div class="form_row">
								<br>
								<a href="#" class="btn update_user_data btn-block" formid="prodid1"><i class="icon-share"></i> Update</a>
								</div> -->
								</div>
							</div>
							
							<div class="well span4 <?php echo $info_ui_color; ?>">
								<div class="well-header">
									<h5>Call Routing - Outside Business Hours</h5>
								</div>
								<div class="well-content no-search"> 
								<table class="table table-striped table-bordered">
								   <thead>
									  <tr>
										<th><h4>Call Types</h4></th>
										 <th><h4>Farmers</h4></th>
										 <th><h4>My Office</h4></th>
										 
									  </tr>
								   </thead>
								   <tbody>
										<tr>
											<td>Cancellations</td>
											<td align="center"><input type="radio" name="cnao0" class="cnao0 uniform calltypes" value="0_seamless" <?php if($info_calltypes_notes2 == NULL || $callchoice2[0] == '0_seamless'){echo "checked";} ?> /></td>
											<td align="center"><input type="radio" name="cnao0" class="cnao0 uniform calltypes" value="0_office" <?php if($callchoice2[0] == '0_office'){echo "checked";} ?> /></td>
											
										</tr>
										<tr>
											<td>Coverage Changes</td>
											<td align="center"><input type="radio" name="cnao1" class="cnao1 uniform calltypes" value="1_seamless" <?php if($info_calltypes_notes2 == NULL || $callchoice2[1] == '1_seamless'){echo "checked";} ?> /></td>
											<td align="center"><input type="radio" name="cnao1" class="cnao1 uniform calltypes" value="1_office" <?php if($callchoice2[1] == '1_office'){echo "checked";} ?> /></td>
											
										</tr>
										<tr>
											<td>Claims</td>
											<td align="center"><input type="radio" name="cnao2" class="cnao2 uniform calltypes" value="2_seamless" <?php if($info_calltypes_notes2 == NULL || $callchoice2[2] == '2_seamless'){echo "checked";} ?> /></td>
											<td align="center"><input type="radio" name="cnao2" class="cnao2 uniform calltypes" value="2_office" <?php if($callchoice2[2] == '2_office'){echo "checked";} ?> /></td>
											
										</tr>
										<tr>
											<td>New Business</td>
											<td align="center"><input type="radio" name="cnao3" class="cnao3 uniform calltypes" value="3_seamless" <?php if($info_calltypes_notes2 == NULL || $callchoice2[3] == '3_seamless'){echo "checked";} ?> /></td>
											<td align="center"><input type="radio" name="cnao3" class="cnao3 uniform calltypes" value="3_office" <?php if($callchoice2[3] == '3_office'){echo "checked";} ?> /></td>
											
										</tr>
								   </tbody>
								</table>
								<!--<div class="form_row">
								<br>
								<a href="#" class="btn update_user_data btn-block" formid="prodid1"><i class="icon-share"></i> Update</a>
								</div> -->
								</div>
							</div>	
							<input name="calltypes_notes--tosql_products_ext" type="text" id="calltypes" style="display: none;"/>
							<input name="calltypes_notes2--tosql_products_ext" type="text" id="calltypes2" style="display: none;"/>
						
						
						
							<!--Inbound Call Options -->
						
							<div class="well span4 <?php echo $info_ui_color; ?>">
								<div class="well-header">
									<h5>Alternate Numbers & Call Option</h5>
								</div>
								
								<div class="well-content no-search">
									<div class="form_row">
										<label class="field_name align_left"><strong>Secondary Phone:</strong></label>
											<div class="field">
												<input class="mask-phone" value="<?php echo $info_secondphone; ?>" name="secondphone--tosql_farm_incontact_info" type="text" AUTOCOMPLETE=ON>
											</div>
										</div>
									<div class="form_row">
										<label class="field_name align_left"><strong>Route Back Number (Kept Private): <span class="required">*</span></strong></label>
										<div class="field">
											<input class="mask-phone required" id="rback" value="<?php echo $info_rback; ?>" name="rback--tosql_farm_incontact_info" type="text" AUTOCOMPLETE=ON>
											</div>
										</div>
									<div class="form_row added_fields prod_id_1">
											<label class="field_name align_left"><strong>Call forwarding capable?</strong></label>
											<div class="field">
												<label class="radio">
													<div class="radio"><input type="radio" class="uniform" name="forwardinglines--tosql_farm_agent_info" <?php if( $info_forwardinglines == 'Yes'){ echo "checked";}?> value= "Yes"></div> Yes
												</label>
												<label class="radio">
													<div class="radio"><input type="radio" class="uniform" name="forwardinglines--tosql_farm_agent_info" <?php if( $info_forwardinglines == 'No'){ echo "checked";}?> value="No"></div> No
												</label>
											</div>
										</div>
										
									<div class="form_row">
											<label class="field_name align_left"><strong>Inbound Option?</strong></label>														
											<div class="field">
												<label class="radio">
													<div class="radio"><input type="radio" class="uniform" name="inbound_option--tosql_products_ext" value="agent" <?php if( $info_inbound_option == 'agent'){ echo "checked";}?> disabled></div> Agent First
												</label>
												<label class="radio">
													<div class="radio"><input type="radio" class="uniform" name="inbound_option--tosql_products_ext" value="csg" <?php if( $info_inbound_option == 'csg'){ echo "checked";}?> disabled></div> CSG First
												</label>
												<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="top" data-content="How your inbound calls are handled.  Your office answers first with CSG taking calls that are not answered in 20 seconds or CSG answers first.  You must contact your account manager to change this option." title="Help" class="btn orange">Help?</a></span>
											</div>
										</div> 
										
									<div class="form_row">
											<label class="field_name align_left"><strong>Additional Email Recipients for Voicemails:</strong></label>
											<div class="field">
												<textarea class="autosize" name="vmemails--tosql_farm_incontact_info" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $info_vmemails; ?></textarea>
											</div>
										</div>				
								</div>
							</div><!-- End of inbound call options-->
							</form>
					
				</div><!-- outer row-fluid end -->	
				<div class="form_row">
						<br>
						<a href="#" class="btn update_user_data btn-block  notempty" formid="prodid1" dorefresh="1"><i class="icon-share"></i> Update</a>
					</div>						
			</div> <!-- End of URGENT Notes / Inbound options Tab -->
					
					
					
					
					
					<!----- Show call History Today TAB ------------
					-
					-         Show Call History:today
					-  
					----------------------------------------------->	
					<div class="tab-pane" id="callhistorytoday">			
						<table class='table'>
							<thead>
								<tr>
									<th width='14%'>Contact ID</th>
									<th width='14%'>Skill Name</th>
									<th width='14%'>Agent Name</th>
									<th width='14%'>Start</th>
									<th width='14%'>End</th>
									<th width='14%'>Total Time<br>(Minutes)</th>
									<th width ='16%'>Description</th>
								</tr>
							</thead>
						</table>
						<div class="responsive_table_scroll">
							<div id="todaycontainer">
							This should fill with data in a few seconds...
							</div>
						</div>
					</div> <!-- end of call history:today -->
					
					
					
					
					<!-----------------------------------------------------
					-
					- Show call History: 7 days
					-
					-
					------------------------------------------------------>
					<div class="tab-pane" id="callhistory7days"> 
						<table class='table'>
							<thead>
								<tr>
									<th width='14%'>Contact ID</th>
									<th width='14%'>Skill Name</th>
									<th width='14%'>Agent Name</th>
									<th width='14%'>Start</th>
									<th width='14%'>End</th>
									<th width='14%'>Total Time<br>(Minutes)</th>
									<th width ='16%'>Description</th>
								</tr>
							</thead>
						</table>
						<div class="responsive_table_scroll">
							<div id="sevencontainer">
								This should fill with data in a few seconds...
							</div>
						</div><!-- -->
					
					</div><!-- End of Call History 7 days Tab-->				
					
				</div><!--End of Tab Content -->
			</div><!-- End of Well Content-->		
		</div><!-- End of Well declaration -->
	</div> <!-- End of span11 -->
</div> <!-- End of row-fluid -->



<!----------------------------------------------- Java Script  ----------------------------------------------->
<script src="js/jquery-1.10.2.js"></script>		
<!-- CSG Live Agent List (InContact API) -->
<script>
   $(document).ready(function() {
   $("#todaycontainer").load("incontact/csg_agent_call_history_today.php");
   var refreshId = setInterval(function() {
   $("#todaycontainer").load('incontact/csg_agent_call_history_today.php?randval='+ Math.random());
   }, 5000);
   $.ajaxSetup({ cache: false });
   });
</script>

<script>
   $(document).ready(function() {
   $("#sevencontainer").load("incontact/csg_agent_call_history_7days.php");
   var refreshId = setInterval(function() {
   $("#sevencontainer").load('incontact/csg_agent_call_history_7days.php?randval='+ Math.random());
   }, 5000);
   $.ajaxSetup({ cache: false });
   });
</script>
<!-- /CSG Live Agent List (InContact API) -->



<script>
$('.calltypes').on('change', function(){
	var calltypes = $('input[name=cn0]:checked', '#prodid1').val() + "#" + $('input[name=cn1]:checked', '#prodid1').val() + "#" + $('input[name=cn2]:checked', '#prodid1').val() + "#" + $('input[name=cn3]:checked', '#prodid1').val();
	var calltypes2 = $('input[name=cnao0]:checked', '#prodid1').val() + "#" + $('input[name=cnao1]:checked', '#prodid1').val() + "#" + $('input[name=cnao2]:checked', '#prodid1').val() + "#" + $('input[name=cnao3]:checked', '#prodid1').val();

	$("#calltypes").val(calltypes);
	$("#calltypes2").val(calltypes2);

});

$(document).ready(function(){
    
	var calltypes = $('input[name=cn0]:checked', '#prodid1').val() + "#" + $('input[name=cn1]:checked', '#prodid1').val() + "#" + $('input[name=cn2]:checked', '#prodid1').val() + "#" + $('input[name=cn3]:checked', '#prodid1').val();
	var calltypes2 = $('input[name=cnao0]:checked', '#prodid1').val() + "#" + $('input[name=cnao1]:checked', '#prodid1').val() + "#" + $('input[name=cnao2]:checked', '#prodid1').val() + "#" + $('input[name=cnao3]:checked', '#prodid1').val();

	$("#calltypes").val(calltypes);
	$("#calltypes2").val(calltypes2);

});
</script>