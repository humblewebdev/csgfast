<!-------------------------------------------------------------------
-
- Shows a farmers agent their products and the status, and 
- allows them to sign up for additional products if eligible.
-
-  Last Updated: 1/10/2014
-
-------------------------------------------------------------------->

<div class="row-fluid">
	<div class="span11">
	
		<!------------------------------------------------
		-	*** PRODUCTS SIGNED UP FOR AND STATUS ****
		-     This well contains the Products the user
		-	  has signed up for. It also shows the status.
		-
		-------------------------------------------------->
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Products Signed Up for and Status</h5>
			</div>
			<div class="well-content no-search">
				<div class="accordion" id="accordion2">
				<?php foreach($products_signedup as $psu){ ?> <!-- loops through and displays each of thr products signed up. Determined by set_php_info_vars.php -->
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#<?php echo "pi".$psu['product_id']; ?>">
								<?php 
									echo "<h4><i class='icon-plus'></i> &nbsp;<strong>" . $psu['product_name'] ."</strong>";
									//check to see if product is pending or not
									$approved = 0; //flag to see if it has been approved
									foreach($products_approved as $pa){
										if($psu['product_name'] == $pa['product_name']){
										echo " -- Approved <i class='icon-ok-sign' style='color: green;'></i> ";
											$approved = 1;
											break;
										}
									}
									if(!$approved){
										echo " -- Pending <i class='icon-time' style='color: red;'></i>";
									}
									
									echo "</h4>"; 							
								?>
							</a>
						</div><!-- Accordion heading -->
						<div id="<?php echo "pi".$psu['product_id']; ?>" class="accordion-body collapse">
							<div class="accordion-inner">
								<?php echo "" . $psu['product_short_desc'] . "";?>
								
	<!-- remove this later --><!-- 	<br><div class="btn red" style="float:right;" onclick="farm_user_update_prod(</?php echo $psu['product_id']; ?>)"> <i class="icon-remove-circle"></i> Remove Product? </div><br><br> -->
							</div> 
						</div> <!-- Accordion body w/ product info-->
					</div> <!-- Accordion group -->
				<?php } //End Fetch for signed up products ?>
				</div> <!-- accordion -->	
				
				<?php
				if(!$products_signedup){
					 echo "<h4><center>You have not signed up for any products yet!</center></h4>";
				}
				?> 
			</div> <!-- Well content-->
		</div><!-- Well declaration -->
	
		<!----------------------------------------------
		-			*** OTHER CSG PRODUCTS ***
		-     This well contains the products the user
		-	  has yet to sign up for.
		-
		------------------------------------------------>
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Other CSG Products</h5>
			</div>
			<div class="well-content no-search">
				<div class="accordion" id="accordion2">
				<?php foreach($products_other as $po){ ?>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#<?php echo "po".$po['product_id']; ?>">
								<?php echo "<h4><i class='icon-plus'></i> &nbsp;" . $po['product_name'] ."</h4>"; ?>
							</a>
						</div>
						<div id="<?php echo "po".$po['product_id']; ?>" class="accordion-body collapse">
							<div class="accordion-inner">
								<?php echo "" . $po['product_short_desc'] . ""; ?>
								<!-- farm_user_update_prod(</?php echo $po['product_id']; ?>, 'add') -->
								<br><a href="<?php echo "#termsandcond".$po['product_id']; ?>" class="btn green" id= "<?php echo "#termsandcond".$po['product_id']; ?>" data-toggle="modal" style="float:right;" onclick="<?php echo "#termsandcond".$po['product_id']; ?>"> <i class="icon-plus-sign"></i> Add Product? </a><br><br>
							</div>
						</div>
					</div>
					
<!------------------------------------------------------------- Registration modal ------------------------------------------------------------->
					<div id="<?php echo "myModal".$po['product_id']; ?>" class="modal fade" style="max-height:120%;">
					  <div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>	
					  <h3> Sign up for <?php echo $po['product_name'];?> </h3>
					  </div>
					  
					  <div class="modal-dialog">
						<div class="modal-content">
						  <!-- dialog body -->
						  <div class="modal-body">
							
								
					<!-------------------------------------------------------------------------------------------------- 
					-
					- 									INBOUND SERVICES SECTION
					-	
					--------------------------------------------------------------------------------------------------->
								<?php
									if($po['product_id'] == 1){ //Product is Inbound
									
										echo "
											<form id='product_update1' name='product_update1' enctype='multipart/form-data'>

										
												<div class='form_row added_fields prod_id_1'>
													<label class='field_name align_right'>Secondary Phone</label>
													<div class='field'>
														<input class='span2 mask-phone' name='secondphone--tosql_farm_incontact_info' type='text' AUTOCOMPLETE=ON>
													</div>
												</div>
												<div class='form_row added_fields prod_id_1'>
													<label class='field_name align_right'>Route Back Number (Kept Private)</label>
													<div class='field'>
														<input class='span2 mask-phone' id='cellphone' name='cellphone--tosql_farm_incontact_info' type='text' AUTOCOMPLETE=ON>
													</div>
												</div>
												<div class='form_row added_fields prod_id_1'>
													<label class='field_name align_right'>Forwarding Lines Capable?</label>
													<div class='field'>
														<label class='radio'>
															<div class='radio'><input type='radio' class='uniform' name='forwardinglines--tosql_farm_agent_info' checked value='Yes'></div> Yes
														</label>
														<label class='radio'>
															<div class='radio'><input type='radio' class='uniform' name='forwardinglines--tosql_farm_agent_info' value='No'></div> No
														</label>
														<span class='help'><a href='#' rel='popover' data-trigger='hover' data-placement='right' data-content='Do you currently have call forwarding capabilities?' title='Help' class='btn orange'>?</a></span>
													</div>
												</div>
												<div class='form_row'>
													<label class='field_name align_right'>Inbound Option?</label>														
													<div class='field'>
														<label class='radio'>
															<div class='radio'><input type='radio' class='uniform' name='inbound_option--tosql_products_ext' checked value='agent'></div> Agent First
														</label>
														<label class='radio'>
															<div class='radio'><input type='radio' class='uniform' name='inbound_option--tosql_products_ext' value='csg'></div> CSG First
														</label>
														<span class='help'><a href='#' rel='popover' data-trigger='hover' data-placement='right' data-content='Do you want CSG to answer 100 Percent of your calls? Choose the 'CSG First' option. If you'd like us to only take calls if you're unable to answer, choose the 'Agent First' option.' title='Help' class='btn orange'>?</a></span>
													</div>
												</div> 
												<div class='form_row'>
											<label class='field_name align_left'><strong>Additional Email Recipients for Voicemails</strong></label>
											<div class='field'>
												<textarea class='autosize' name='vmemails--tosql_farm_incontact_info' cols='63' rows='3' style='resize: vertical; height: 88px;'></textarea>
											</div>
										</div>
												
											</form>
										";
										
					/*-------------------------------------------------------------------------------------------------- 
					-
					- 									FFR OUTBOUND SERVICES SECTION
					-
					---------------------------------------------------------------------------------------------------*/
									}else if($po['product_id'] == 2){ //Product is FFR Outbound ?>
									 
									<div class="well <?php echo $info_ui_color; ?>">
									<div class="well-header">
										<h5>FFR Outbound Services</h5>
									</div>

									<div class="well-content no-search"> 


										<form id="product_update2" name="product_update2" enctype="multipart/form-data">
										<!-- New row fluid --> <div class="row-fluid">
										<!-- new span 8 div--> <div class="span8">
										<div class="form_row added_fields prod_id_2 prod_id_3">
										<label class="field_name align_right">Set my appts with</label>
											<div class="field">
												<div class="span8">
													<select id="ffr_where_to_set_appts" name="where_to_set_appts--tosql_products_ext" type="text" class="chosen">
														<option value="Agent" <?php if($info_where_to_set_appts=="Agent"){echo "selected";}?>>Myself</option>
														<?php for($staff = 1; $staff <= 6; $staff++){ if(${"info_staffname".$staff} == ""){ $nostaff =TRUE; } else {$nostaff=FALSE;}?>
														<?php if(!$nostaff){ ?><option value="<?php echo ${"info_staffname".$staff}; ?>" <?php if($info_where_to_set_appts==${"info_staffname".$staff}){echo "selected";}?>><?php echo ${"info_staffname".$staff}; ?></option><?php } else ?>

														<?php } ?>					
													</select>
												<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="With whom should we set your appts with?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div> 
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Max day appts</label>
											<div class="field">
												<div class="span8">
													<input class="mask-digits" type="text" name="max_day_appts--tosql_products_ext" value="<?php echo $info_max_day_appts; ?>"/>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Please provide the max appointments you want in a day" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Max week appts</label>
											<div class="field">
												<div class="span8">
													<input class="mask-digits" type="text" name="max_week_appts--tosql_products_ext" value="<?php echo $info_max_week_appts; ?>"/>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Please provide the max appointments you want in a week" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>													
										<div class="form_row added_fields prod_id_2 prod_id_3">
											<label class="field_name align_right">Primary Appt. Preference</label>
											<div class="field">
												<div class="span8">
													<select class="chosen" name="primary_appt_pref--tosql_products_ext">
														<option></option>
														<option value="In Office" <?php if($info_primary_appt_pref=="In Office"){ echo "selected"; }?>>In Office</option>
														<option value="Home Visits" <?php if($info_primary_appt_pref=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
														<option value="Phone Appts" <?php if($info_primary_appt_pref=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
													</select>
												</div>

											</div>
										</div> 
										
										<!-- end of  new span --></div>
										
										<!-- Start of second span --> <div class="span8">
										<div class="form_row added_fields prod_id_2 prod_id_3">
											<label class="field_name align_right">Secondary Appt. Preference</label>
											<div class="field">
												<div class="span8">
													<select class="chosen" name="secondary_appt_pref--tosql_products_ext">
														<option></option>
														<option value="In Office" <?php if($info_secondary_appt_pref=="In Office"){ echo "selected"; }?>>In Office</option>
														<option value="Home Visits" <?php if($info_secondary_appt_pref=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
														<option value="Phone Appts" <?php if($info_secondary_appt_pref=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
													</select>
												</div>
												
											</div>
										</div> 
										<div class="form_row added_fields prod_id_2 prod_id_3">
											<label class="field_name align_right">Appt. length</label>
											<div class="field">
												<div class="span8">
													<select class="chosen" name="initial_phone_appt_length--tosql_products_ext">
														<option></option>
														<option value="15 minutes" <?php if($info_initial_phone_appt_length=="15 minutes"){ echo "selected"; }?>>15 minutes</option>
														<option value="30 minutes" <?php if($info_initial_phone_appt_length=="30 minutes"){ echo "selected"; }?>>30 minutes</option>
														<option value="60 minutes" <?php if($info_initial_phone_appt_length=="60 minutes"){ echo "selected"; }?>>60 minutes</option>
													</select>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="What length of time would you like your appts set for?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Appt. Seperation</label>
											<div class="field">
												<div class="span8">
													<select class="chosen" name="timeframe_between_appts--tosql_products_ext">
														<option></option>
														<option value="15 minutes" <?php if($info_timeframe_between_appts=="15 minutes"){ echo "selected"; }?>>15 minutes</option>
														<option value="30 minutes" <?php if($info_timeframe_between_appts=="30 minutes"){ echo "selected"; }?>>30 minutes</option>
														<option value="60 minutes" <?php if($info_timeframe_between_appts=="60 minutes"){ echo "selected"; }?>>60 minutes</option>
														<option value="90 minutes" <?php if($info_timeframe_between_appts=="90 minutes"){ echo "selected"; }?>>90 minutes</option>
														<option value="120 minutes" <?php if($info_timeframe_between_appts=="120 minutes"){ echo "selected"; }?>>120 minutes</option>
													</select>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="How long would you like in-between appts?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Appt. Range</label>
											<div class="field">
												<div class="span8">
													<select class="chosen" name="how_far_out_by_week--tosql_products_ext">
														<option></option>
														<option value="1 week out" <?php if($info_how_far_out_by_week=="1 week out"){ echo "selected"; }?>>1 week out</option>
														<option value="2 weeks out" <?php if($info_how_far_out_by_week=="2 weeks out"){ echo "selected"; }?>>2 weeks out</option>
														<option value="3 weeks out" <?php if($info_how_far_out_by_week=="3 weeks out"){ echo "selected"; }?>>3 weeks out</option>
													</select>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="How many weeks out do you want your appts scheduled?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
											
										</div>
										<div class="form_row added_fields prod_id_2 prod_id_3">
											<label class="field_name align_right">Special Details</label>
											<div class="field">
												<div class="span8">
													<textarea class="autosize" name="special_details--tosql_products_ext" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $info_special_details; ?></textarea>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Do you have any special details we need to be aware of?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Other Services</label>
											<div class="field">
												<div class="span8">
													 <textarea class="autosize" name="other_services_provided--tosql_products_ext" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $info_other_services_provided; ?></textarea>
													 <span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="What other services does your office provide?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Allow Eprint?</label>
											<div class="field">
												<div class="span8">
													<select class="chosen" name="send_eprint--tosql_products_ext">
														<option></option>
														<option value="Yes" <?php if($info_send_eprint=="Yes"){ echo "selected"; }?>>Yes</option>
														<option value="No" <?php if($info_send_eprint=="No"){ echo "selected"; }?>>No</option>
													</select>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="In the event there is incorrect contact info for one of your clients, would you like us to send an EPRINT letter on your behalf?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										</form>
									<!-- End of second span8--></div>
									<!-- Row fluid --></div>
									</div>
								</div>
					<!-------------------------------------------------------------------------------------------------- 
					-
					- 									ALERTS SERVICES SECTION
					-
					--------------------------------------------------------------------------------------------------->						
						<?php	}else{ //If the Product is Alerts--> ?>
									
										<div class="well <?php echo $info_ui_color; ?>">
												<div class="well-header">
													<h5>Alerts Services</h5>
												</div>
												<div class="well-content no-search"> 
													<form id="product_update3" name="product_update3" enctype="multipart/form-data">
													<div class="form_row added_fields prod_id_2 prod_id_3">
													<label class="field_name align_right">Set my appts with</label>
														<div class="field">
															<div class="span8">
																<select id="ffr_where_to_set_appts" name="alerts_where_to_set_appts--tosql_products_ext" type="text" class="chosen">
																	<option value="Agent" <?php if($info_where_to_set_appts=="Agent"){echo "selected";}?>>Myself</option>
																	<?php for($staff = 1; $staff <= 6; $staff++){ if(${"info_staffname".$staff} == ""){ $nostaff =TRUE; } else {$nostaff=FALSE;}?>
																	<?php if(!$nostaff){ ?><option value="<?php echo ${"info_staffname".$staff}; ?>" <?php if($info_where_to_set_appts==${"info_staffname".$staff}){echo "selected";}?>><?php echo ${"info_staffname".$staff}; ?></option><?php } else ?>

																	<?php } ?>					
																</select>
															<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="With whom should we set your appts with?" title="Help" class="btn orange">?</a></span>
															</div>
															
														</div>
													</div> 
																								
													<div class="form_row added_fields prod_id_2 prod_id_3">
														<label class="field_name align_right">Primary Appt. Preference</label>
														<div class="field">
															<div class="span8">
																<select class="chosen" name="alerts_primary_appt_pref--tosql_products_ext">
																	<option></option>
																	<option value="In Office" <?php if($info_alerts_primary_appt_pref=="In Office"){ echo "selected"; }?>>In Office</option>
																	<option value="Home Visits" <?php if($info_alerts_primary_appt_pref=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
																	<option value="Phone Appts" <?php if($info_alerts_primary_appt_pref=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
																</select>
															</div>

														</div>
													</div> 
													<div class="form_row added_fields prod_id_2 prod_id_3">
														<label class="field_name align_right">Secondary Appt. Preference</label>
														<div class="field">
															<div class="span8">
																<select class="chosen" name="alerts_secondary_appt_pref--tosql_products_ext">
																	<option></option>
																	<option value="In Office" <?php if($info_alerts_secondary_appt_pref=="In Office"){ echo "selected"; }?>>In Office</option>
																	<option value="Home Visits" <?php if($info_alerts_secondary_appt_pref=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
																	<option value="Phone Appts" <?php if($info_alerts_secondary_appt_pref=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
																</select>
															</div>
															
														</div>
													</div> 
													<div class="form_row added_fields prod_id_2 prod_id_3">
														<label class="field_name align_right">Appt. length</label>
														<div class="field">
															<div class="span8">
																<select class="chosen" name="initial_phone_appt_length--tosql_products_ext">
																	<option></option>
																	<option value="15 minutes" <?php if($info_alerts_initial_phone_appt_length=="15 minutes"){ echo "selected"; }?>>15 minutes</option>
																	<option value="30 minutes" <?php if($info_alerts_initial_phone_appt_length=="30 minutes"){ echo "selected"; }?>>30 minutes</option>
																	<option value="60 minutes" <?php if($info_alerts_initial_phone_appt_length=="60 minutes"){ echo "selected"; }?>>60 minutes</option>
																</select>
																<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="What length of time would you like your appts set for?" title="Help" class="btn orange">?</a></span>
															</div>
															
														</div>
													</div>

													<div class="form_row added_fields prod_id_2 prod_id_3">
														<label class="field_name align_right">Special Details</label>
														<div class="field">
															<div class="span8">
																<textarea class="autosize" name="alerts_special_details--tosql_products_ext" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $info_alerts_special_details; ?></textarea>
																<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Do you have any special details we need to be aware of?" title="Help" class="btn orange">?</a></span>
															</div>
															
														</div>
													</div>
													</form>
												</div>
											</div>					

						
					<?php	}	?> <!-- End of products alerts -->
						  </div>
						  <!-- dialog buttons -->
						  <div class="modal-footer"><center><a href="#submissionmodal<?php echo $po['product_id']; ?>" data-toggle="modal" class="btn btn-primary green"> Add <?php echo $po['product_name']; ?> Product</a></center></div>
						</div>
					  </div>
					</div>
<!---------------------------------------------------------- End of Registration modal ----------------------------------------------------------->



				<!------------------------------- Modal for submission ---------------------------------------->
				<div id="submissionmodal<?php echo $po['product_id']; ?>" class="modal hide fade" data-backdrop="static">
					<div class="modal-header">
					  <h3>Product Confirmation</h3>
					</div>
					<div class="modal-body">
					  <h4><center><p>You are about to add the <?php echo $po['product_name']; ?> product.</p>
					  <p>Do you want to proceed?</p></h4></center>
					</div>
					<div class="modal-footer">
					 <center> <button type="button" onclick="farm_user_update_prod(<?php echo $po['product_id']; ?>)" class="btn btn-primary green">Yes</button>
					   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn red">No</a></center>
					</div>
				</div>
				<!------------------------------ End of Modal for submission ----------------------------------->
				
				
				
				<!------------------------------- Modal for Terms and Conditions ---------------------------------------->
				<div id="termsandcond<?php echo $po['product_id']; ?>" class="modal hide fade" data-backdrop="static">
					<div class="modal-header">
					  <h3><?php echo $po['product_name']; ?> Terms and Conditions</h3>
					</div>
					<div class="modal-body">
						<?php
							echo "<center>".$po['product_terms']."</center>";
						?>
					  
					</div>
					<div class="modal-footer">
					<center><a href="<?php echo "#myModal".$po['product_id']; ?>" class="btn green" id= "<?php echo "#myModal".$po['product_id']; ?>" data-toggle="modal"  onclick="<?php echo "#myModal".$po['product_id']; ?>" data-dismiss="modal">Agree to Terms and Conditions </a>
					<a href="#" data-dismiss="modal" aria-hidden="true" class="btn red">Cancel</a></center>
					</div>
				</div>
				<!------------------------------ End of Modal for Terms and Conditions ----------------------------------->


				<?php } //End Fetch for signed up products ?>
				</div> 	<!-- 2nd accordion -->		
			</div>	<!-- 2nd well content -->
		</div>	<!-- 2nd well declaration -->

	</div><!-- Span 10-->
</div>	<!-- Row fluid -->	




<!---------------------------------- Java Script -------------------------------------------->
<script src="js/jquery-1.10.2.js"></script>

<script>

function farm_user_update_prod(id){
	
		if(id == 1){ //if the product is Inbound, no additional fields needed
		
			var currentprods = '<?php echo $info_fast_products; ?>';
			var cprodarry = currentprods.split('#');
			
			cprodarry.push(id);

			var newstring = cprodarry.toString();
			var readystring = newstring.replace(/\,/g, '#');

			//alert("The new String after " +id+" is added is " + readystring);
			
			//var sendData = $("#product_update1").serialize() + "&users_id=<?php echo $info_users_id; ?>&fast_products--tosql_farm_agent_info="+readystring;
			
			$.ajax({
				type: 'POST',
				url: 'z_scripts/farm_user_agent_update.php?type=submit_product',
				data: $("#product_update1").serialize()+"&product_chosen="+id+"&users_id=<?php echo $info_users_id; ?>&fast_products--tosql_farm_agent_info="+readystring,
				success: function(data, textStatus, jqXHR){
					//alert(data);
					location.reload();
				}
			});
			
		}else if(id == 2){ // else the product ID is Outbound and needs additional info
		
			var currentprods = '<?php echo $info_fast_products; ?>';
			var cprodarry = currentprods.split('#');
			
			cprodarry.push(id);

			var newstring = cprodarry.toString();
			var readystring = newstring.replace(/\,/g, '#');

			//alert("The new String after " +id+" is added is " + readystring);
			
			var sendData = $("#product_update2").serialize()+"&product_chosen="+id+"&users_id=<?php echo $info_users_id; ?>&fast_products--tosql_farm_agent_info="+readystring;
			
			$.ajax({
				type: 'POST',
				url: 'z_scripts/farm_user_agent_update.php?type=submit_product',
				data: sendData,
				success: function(data, textStatus, jqXHR){
					//alert(data);
					location.reload();
				}
			});
		
		}else{ //ID is equal to 3
		
			var currentprods = '<?php echo $info_fast_products; ?>';
			var cprodarry = currentprods.split('#');
			
			cprodarry.push(id);

			var newstring = cprodarry.toString();
			var readystring = newstring.replace(/\,/g, '#');
			var users_id = <?php echo $info_users_id; ?>;

			//alert("The new String after " +id+" is added is " + readystring);
			
			//var sendData = $("#product_update3").serialize() + "&users_id=<?php echo $info_users_id; ?>&fast_products--tosql_farm_agent_info="+readystring;
			
			$.ajax({
				type: 'POST',
				url: 'z_scripts/farm_user_agent_update.php?type=submit_product',
				data: $("#product_update3").serialize()+"&product_chosen="+id+"&users_id="+users_id+"&fast_products--tosql_farm_agent_info="+readystring,
				success: function(data, textStatus, jqXHR){
					//alert(data);
					location.reload();
				}
			});

		}	
}
</script>