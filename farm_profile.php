<?php 
/**
* Updated by: Amber Bryson
* 1/3/2013
**/
?>

<div class="row-fluid">
	<div class="span10">
		<div class="well <?php echo $info_ui_color;?>">
			
			<!--------------- Well Header --------------->
			<div class="well-header">
				<h5><?php echo "Agency Profile"; ?> </h5>
			</div>
		
			<!--------------- Well Content --------------->
			<div class="well-content no-search">
				<!----------------- Add more tab names here ----------------->
				<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
					<li class="active"><a href="#basicinfo" data-toggle="tab"><strong>Basic Info</strong></a></li>
					<li><a href="#csgview" data-toggle="tab"><strong>What CSG Sees</strong></a></li>
					<li><a href="#urgentnotes" data-toggle="tab"><strong>Urgent Notes</strong></a></li>
					<li><a href="#yourstaff" data-toggle="tab"><strong>Your Staff</strong></a></li>
					<li><a href="#hoursoperation" data-toggle="tab"><strong>Hours of Operation</strong></a></li>
					<li><a href="#changepassword" data-toggle="tab"><strong>Change Your Password</strong></a></li>
				</ul>
				
				<!----------------------- Tab Content ----------------------
				-   Display all agency information in series of tabs
				-   logically displaying general information and product specific information
				-   
				-   Make the first tab be the most frequently updated info.
				 ---------------------------------------------->
				<div id="my-tab-content" class="tab-content">
					
					
					<!--------------  BASIC INFO TAB --------------
					-
					-   Show agecy staff members and call routing options
					-  
					----------------------------------------------->
					<div class="tab-pane active" id="basicinfo"> 		
						<div class="row-fluid"> <!-- Row Fluid -->
							<form id="editprof1" enctype="multipart/form-data">
								
								<div class="span6">
							
									<!--  ***NO Longer Upload picture*** 
									-
									- Your Profile Image
									-
									-->
									<div class="form_row">
										<label class="field_name align_left"><strong>Profile Image:</strong></label>
										<br>
										<div class="field">
											<img src="profile_pics/<?php echo $info_profile_pic; ?>" onError="this.src='http://farmersagent.com/Images/FarmersLogo_placements.jpg';"/>
										</div>
										
										<!-- User Farmer's website image selection option -->
									</div>
								
								
									<!--
									-
									- Unchangable Information
									-
									-
									-->

									<div class="form_row control-group error">
										<label class="field_name"><strong>Agent Name:</strong></label>
										<div class="field">
											<span class="field_value"><?php echo $info_firstname . " " . $info_lastname; ?></span>
										</div>
									</div>
									<div class="form_row control-group error">
										<label class="field_name"><strong>Agency Name:</strong></label>
										<div class="field">
											<span class="field_value"><?php echo $info_agencyname; ?></span>
										</div>
									</div>
									<div class="form_row control-group error">
										<label class="field_name"><strong>Reg. Date:</strong></label>
										<div class="field">
											<span class="field_value"><?php echo $info_reg_date; ?></span>
										</div>
									</div>
									<div class="form_row control-group error">
										<label class="field_name"><strong>Agent Code:</strong></label>
										<div class="field">
											<span class="field_value"><?php echo $info_agent_code; ?></span>
										</div>
									</div>		

									
									
										<!-- 
										- 
										- 
										- Phone numbers/ fax
										-
										--->
										<div class="form_row">
											<label class="field_name align_left"><strong>Fax Number:</strong></label>
											<div class="field">
												<input class="span5 input-medium mask-phone tendigits" id="fax" value="<?php echo $info_fax; ?>" name="fax--tosql_farm_agent_info" type="text" AUTOCOMPLETE=ON>
											</div>
										</div>
										<div class="form_row">
											<label class="field_name align_left"><strong>Main Business Phone:</strong><span class="required">*</span></label>
											<div class="field">
												<input class="span5 required mask-phone" id="mainphone" value="<?php echo $info_mainphone; ?>" name="mainphone--tosql_farm_incontact_info" type="text" AUTOCOMPLETE=ON>
												<input id="rback" style="display: none;" name="rback--tosql_farm_incontact_info" type="text" AUTOCOMPLETE=ON>
												<!-- The above input field gets the value of the mainphone input field dynamically -->
											</div>
										</div>
										<!-- 
										-
										- End of added information
										-
										--->
									


								</div> <!-- span 6 1-->

							
							
								<!-- 
								- 
								- Updatable Information
								- 
								-->
								<div class="span5">
									
										<div class="form_row">
										<label class="field_name align_left"><strong>Email:</strong><span class="required">*</span></label>
										<div class="field">
											<input class="span8 required email" id="changeemail" type="text" value="<?php echo $info_email; ?>" name="email--tosql_users">
											<label for="changeemail" id="dup_email"></label>
											<!--<input class="span8 required email usernamemirror" type="text" value="</?php echo $info_username; ?>" name="username--tosql_users" style="display: none;">-->
										</div>
										</div>
															
										<div class="form_row">
											<label class="field_name align_left"><strong>Address:</strong><span class="required">*</span></label>
											<div class="field">
												<input class="span8 requiredmin" type="text" value="<?php echo $info_address; ?>" name="address--tosql_farm_agent_info">
											</div>
										</div>
										<div class="form_row">
											<label class="field_name align_left"><strong>City:</strong><span class="required">*</span></label>
											<div class="field">
												<input class="span8 requiredmin" type="text" value="<?php echo $info_city; ?>" name="city--tosql_farm_agent_info">
											</div>
										</div>
														<div class="form_row">
                                                            <label class="field_name align_left"><strong>State</strong><span class="required">*</span></label>
                                                            <div class="field">
                                                                <div class="span4 no-search">
                                                                    <select class="required" id="stateselect" name="state--tosql_farm_agent_info">
                                                                      
																		<option value="AL" <?php if($info_state == "AL"){echo "selected";}?>>Alabama</option>
																		<option value="AK" <?php if($info_state == "AK"){echo "selected";}?>>Alaska</option>
																		<option value="AZ" <?php if($info_state == "AZ"){echo "selected";}?>>Arizona</option>
																		<option value="AR" <?php if($info_state == "AR"){echo "selected";}?>>Arkansas</option>
																		<option value="CA" <?php if($info_state == "CA"){echo "selected";}?>>California</option>
																		<option value="CO" <?php if($info_state == "CO"){echo "selected";}?>>Colorado</option>
																		<option value="CT" <?php if($info_state == "CT"){echo "selected";}?>>Connecticut</option>
																		<option value="DE" <?php if($info_state == "DE"){echo "selected";}?>>Delaware</option>
																		<option value="FL" <?php if($info_state == "FL"){echo "selected";}?>>Florida</option>
																		<option value="GA" <?php if($info_state == "GA"){echo "selected";}?>>Georgia</option>
																		<option value="HI" <?php if($info_state == "HI"){echo "selected";}?>>Hawaii</option>
																		<option value="ID" <?php if($info_state == "ID"){echo "selected";}?>>Idaho</option>
																		<option value="IL" <?php if($info_state == "IL"){echo "selected";}?>>Illinois</option>
																		<option value="IN" <?php if($info_state == "IN"){echo "selected";}?>>Indiana</option>
																		<option value="IA" <?php if($info_state == "IA"){echo "selected";}?>>Iowa</option>
																		<option value="KS" <?php if($info_state == "KS"){echo "selected";}?>>Kansas</option>
																		<option value="KY" <?php if($info_state == "KY"){echo "selected";}?>>Kentucky</option>
																		<option value="LA" <?php if($info_state == "LA"){echo "selected";}?>>Louisiana</option>
																		<option value="ME" <?php if($info_state == "ME"){echo "selected";}?>>Maine</option>
																		<option value="MD" <?php if($info_state == "MD"){echo "selected";}?>>Maryland</option>
																		<option value="MA" <?php if($info_state == "MA"){echo "selected";}?>>Massachusetts</option>
																		<option value="MI" <?php if($info_state == "MI"){echo "selected";}?>>Michigan</option>
																		<option value="MN" <?php if($info_state == "MN"){echo "selected";}?>>Minnesota</option>
																		<option value="MS" <?php if($info_state == "MS"){echo "selected";}?>>Mississippi</option>
																		<option value="MO" <?php if($info_state == "MO"){echo "selected";}?>>Missouri</option>
																		<option value="MT" <?php if($info_state == "MT"){echo "selected";}?>>Montana</option>
																		<option value="NE" <?php if($info_state == "NE"){echo "selected";}?>>Nebraska</option>
																		<option value="NV" <?php if($info_state == "NV"){echo "selected";}?>>Nevada</option>
																		<option value="NH" <?php if($info_state == "NH"){echo "selected";}?>>New Hampshire</option>
																		<option value="NJ" <?php if($info_state == "NJ"){echo "selected";}?>>New Jersey</option>
																		<option value="NM" <?php if($info_state == "NM"){echo "selected";}?>>New Mexico</option>
																		<option value="NY" <?php if($info_state == "NY"){echo "selected";}?>>New York</option>
																		<option value="NC" <?php if($info_state == "NC"){echo "selected";}?>>North Carolina</option>
																		<option value="ND" <?php if($info_state == "ND"){echo "selected";}?>>North Dakota</option>
																		<option value="OH" <?php if($info_state == "OH"){echo "selected";}?>>Ohio</option>
																		<option value="OK" <?php if($info_state == "OK"){echo "selected";}?>>Oklahoma</option>
																		<option value="OR" <?php if($info_state == "OR"){echo "selected";}?>>Oregon</option>
																		<option value="PA" <?php if($info_state == "PA"){echo "selected";}?>>Pennsylvania</option>
																		<option value="RI" <?php if($info_state == "RI"){echo "selected";}?>>Rhode Island</option>
																		<option value="SC" <?php if($info_state == "SC"){echo "selected";}?>>South Carolina</option>
																		<option value="SD" <?php if($info_state == "SD"){echo "selected";}?>>South Dakota</option>
																		<option value="TN" <?php if($info_state == "TN"){echo "selected";}?>>Tennessee</option>
																		<option value="TX" <?php if($info_state == "TX"){echo "selected";}?>>Texas</option>
																		<option value="UT" <?php if($info_state == "UT"){echo "selected";}?>>Utah</option>
																		<option value="VT" <?php if($info_state == "VT"){echo "selected";}?>>Vermont</option>
																		<option value="VA" <?php if($info_state == "VA"){echo "selected";}?>>Virginia</option>
																		<option value="WA" <?php if($info_state == "WA"){echo "selected";}?>>Washington</option>
																		<option value="WV" <?php if($info_state == "WV"){echo "selected";}?>>West Virginia</option>
																		<option value="WI" <?php if($info_state == "WI"){echo "selected";}?>>Wisconsin</option>
																		<option value="WY" <?php if($info_state == "WY"){echo "selected";}?>>Wyoming</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div> 
										<div class="form_row">
											<label class="field_name align_lef"><strong>Zip / Postal Code:</strong></label>
											<div class="field">
												<input class="span8 mask-zip" type="text" value="<?php echo $info_zipcode; ?>" name="zipcode--tosql_farm_agent_info">
											</div>
										</div>
										<div class="form_row">
											<label class="field_name align_left"><strong>County:</strong></label>
											<div class="field">
												<input class="span8 requiredmin" type="text" value="<?php echo $info_county; ?>" name="county--tosql_farm_agent_info">
											</div>
										</div>
										<div class="form_row">
											<label class="field_name align_left"><strong>Time Zone:</strong><span class="required">*</span></label>
											<div class="field">
												<div class="span4 no-search">
													<select class="chosen" name="timezone--tosql_farm_agent_info">
														<option><?php echo $info_timezone ?></option>
														<option value="Eastern">Eastern</option>
														<option value="Central">Central</option>
														<option value="Mountain">Mountain</option>
														<option value="MountainNoDST">Mountain (no DST)</option>
														<option value="Pacific">Pacific</option>
													</select>
												</div>
											</div>
										</div>
										<div class="form_row">
												<label class="field_name align_left"><strong>District Manager:</strong></label>
												<div class="field">
													<div class="span4">
														<select class="chosen required" name="dm_id--tosql_farm_agent_info">
															<?php 
															$dm_req1 = "SELECT * from dm_list WHERE id_dm = '$info_dm_id' LIMIT 1;";
															$dm_query1 = $mysqli->query($dm_req1)or die($mysqli->error); 
															$dm_row1 = $dm_query1->fetch_assoc();
															$dmname = $dm_row1['firstname'] . " " . $dm_row1['lastname'] . " | " . $dm_row1['state_office'];
																
															?>
															<option value="<?php echo $info_dm_id; ?>"><?php echo $dmname; ?></option>
															<?php
															$dm_req2 = "SELECT * from dm_list;";
															$dm_query2 = $mysqli->query($dm_req2)or die($mysqli->error); 

																while($dm_row2 = $dm_query2->fetch_assoc())
																{
																  echo "<option value='{$dm_row2['id_dm']}' >{$dm_row2['firstname']} {$dm_row2['lastname']} | {$dm_row2['state_office']}</option>";
																} $mysqli->close();
															?>
														 </select>
													</div>
												</div>
											</div> 
									
										<div class="form_row">
											<label class="field_name"><strong>Spanish Speaking:</strong></label>
											<div class="field">
												<label class="radio">
													<input type="radio" class="uniform" name="spanish--tosql_farm_agent_info" value="Yes" <?php if($info_spanish=="Yes"){echo "checked";} ?>> Yes
												</label>
												<label class="radio">
													<input type="radio" class="uniform" name="spanish--tosql_farm_agent_info" value="No" <?php if($info_spanish=="No"){echo "checked";} ?>> No
												</label>
											</div>
										</div>		


																			
									
								</div><!-- 2nd span 6-->
								
								<!-- Update button-->
								<div class="span11">
									<div class="form_row">
											<br>
											<a href="#" class="btn btn-block update_user_data" formid="editprof1"><i class="icon-share"></i> Update</a>
									</div>
								</div>
							</form>	<!-- End of form -->				
						</div> <!-- End of Row fluid-->
					</div> <!-- End of Basic Info Tab -->
					
					
					<!---------------------------------
					-
					- What CSG sees tab
					-
					------------------------------------>
					<div class="tab-pane" id="csgview">
						<?php include 'ipage.php'; ?>
					</div><!-- End of What CSG Sees Tab -->

					
					
				<!--------------------- URGENT NOTES TAB --------------------
				-
				- 				URGENT NOTES FOR ALL USERS
				-
				------------------------------------------------------------->
				<div class="tab-pane" id="urgentnotes">
					<div class="row-fluid">
						<div class="span6">
							<div class="well blue">
								<div class="well-header">
									<h5>Urgent Notes</h5>
								</div>

								<div class="well-content no-search">
									<form id="addednotes">
										<textarea class="textarea"  name="livestatus--tosql_farm_agent_info" placeholder="Enter text(200 chars max)..." style="width: 100%; height: 300px"><?php echo $info_livestatus;?></textarea>
									</form>
									<div class="form_row">
										<br><a href="#" class="btn btn-block update_user_data" formid="addednotes" dorefresh="1"><i class="icon-share"></i> Update</a>
									</div>
								</div>
							</div>
						</div>
					</div><!-- urgent notes row fluid -->
				</div><!-- end of urgent notes tab---->

				
				
				<!--------------------  YOUR STAFF TAB ----------
					-
					-   Show agecy staff members and call routing options
					-  
					----------------------------------------------->							
					<div class="tab-pane" id="yourstaff"> 
					<form id='stafflist'>
						<?php
								for($staff = 1; $staff <= 6; $staff++){ 
									//if(${"info_staffname".$staff} == ""){ //if it's null, put blank information there
										
										echo "<strong><h4> Staff Member ".$staff."<br></h4></strong>";
										
										//gets the values from the info get variables.
										$current_staff_name = ${"info_staffname".$staff};
										$current_staff_open = ${"info_staff".$staff."open"};
										$current_staff_close = ${"info_staff".$staff."close"};
										$current_staff_phone = ${"info_staffphone".$staff};
										$current_staff_email = ${"info_staffemail".$staff};
										$current_staff_position = ${"info_staffposition".$staff};
										
										//stores the column name info into temp variables. 
										$column_name = "staffname".$staff;
										$column_open = "staff".$staff."open";
										$column_close = "staff".$staff."close";
										$column_phone = "staffphone".$staff;
										$column_email = "staffemail".$staff;
										$column_position = "staffposition".$staff;
										
										//Open and Close time
										echo "		
											<div class='form_row'>	
												<div class='input-append bootstrap-timepicker field'>	
													<input value='$current_staff_open' name='$column_open--tosql_farm_agent_staff_info' type='text' class='input-small timepicker3'>
													<span class='add-on' style='width: 50px;'><i class='icon-time'></i> In</span>
												</div>
												<div class='input-append bootstrap-timepicker'>
													<input value='$current_staff_close' name='$column_close--tosql_farm_agent_staff_info' type='text' class='input-small timepicker3'>
													<span class='add-on' style='width: 50px;'><i class='icon-time'></i> Out</span>
												</div>
											</div>
											<div class='form_row'>
												<label class='field_name'><strong>Name:</strong></label>
												<div class='field'>
													<input value='$current_staff_name' name='$column_name--tosql_farm_agent_staff_info' type='text' class=''>
												</div>
											</div>
											<div class='form_row'>
												<label class='field_name'><strong>Position:</strong></label>
												<div class='field'>
													<input value='$current_staff_position' name='$column_position--tosql_farm_agent_staff_info' type='text' class=''>
												</div>
											</div>
											<div class='form_row'>
												<label class='field_name'><strong>Phone:</strong></label>
												<div class='field'>
													<input value='$current_staff_phone' name='$column_phone--tosql_farm_agent_staff_info' type='text' class='mask-phone'>
												</div>
											</div>
											<div class='form_row'>
												<label class='field_name'><strong>Email:</strong></label>
												<div class='field'>
													<input value='$current_staff_email' name='$column_email--tosql_farm_agent_staff_info' type='text' class='email'>
												</div>
											</div>
										";		
								}
						?>
						</form>		
						<!-- Update staff button -->
						 <div class="form_row">
							<br><a href="#" class="btn btn-block update_user_data" formid="stafflist" dorefresh="1"><i class="icon-share"></i> Update </a>
						</div>
						
						
					</div> <!--End of Your Staff Tab -->
				



				
					<!---------Hours of Op TAB -------------------
					-
					-   Hours of Operation Tab
					-  
					----------------------------------------------->			
					<div class="tab-pane" id="hoursoperation"><!-- start of Hours of Operation Tab -->
						<div class="">
							<form id="hoursofop2">
							<table class="table">
							   <thead>
									<tr>
									  <th> Days </th>
									  <th> Open </th>
									  <th> Close </th>
									  <th> Open/Closed</th>
									</tr>
							   </thead>
							   <tbody>
									<tr>
										<td>Monday</td>
										<td>
											<!--<div class="input-append bootstrap-timepicker">
												<input readonly class="input-small timepicker3" name="mopen--tosql_farm_agent_info" type="text" value="</?php echo $info_mopen; ?>"/>
												<span class="add-on"><i class="icon-time"></i></span> add this format back if Tim doesn't like it!
													**Removed "input-append" out of class declaration, and 
											</div> -->
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="mopen--tosql_farm_agent_info" type="text" <?php if($info_m_status == 0){echo "disabled";}else{ echo "value='$info_mopen'"; } ?>/>
												</span> 
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="mclose--tosql_farm_agent_info" type="text" <?php if($info_m_status == 0){echo "disabled";}else{ echo "value='$info_mclose'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="m_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="m_status--tosql_farm_agent_info" value="1" <?php if($info_m_status == 1){echo "checked";} ?>>
												
											</div>
										</td>
									</tr>
									<tr>
										<td>Tuesday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="topen--tosql_farm_agent_info" type="text" <?php if($info_t_status == 0){echo "disabled";}else{ echo "value='$info_topen'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class=" bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="tclose--tosql_farm_agent_info" type="text" <?php if($info_t_status == 0){echo "disabled";}else{ echo "value='$info_tclose'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="t_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="t_status--tosql_farm_agent_info" value="1" <?php if($info_t_status == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Wednesday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="wopen--tosql_farm_agent_info" type="text" <?php if($info_w_status == 0){echo "disabled";}else{ echo "value='$info_wopen'"; } ?>/>
												<!--<i class="icon-time"></i>--></span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="wclose--tosql_farm_agent_info" type="text" <?php if($info_w_status == 0){echo "disabled";}else{ echo "value='$info_wclose'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="w_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="w_status--tosql_farm_agent_info" value="1" <?php if($info_w_status == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Thursday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="ropen--tosql_farm_agent_info" type="text" <?php if($info_r_status == 0){echo "disabled";}else{ echo "value='$info_ropen'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class=" bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="rclose--tosql_farm_agent_info" type="text" <?php if($info_r_status == 0){echo "disabled";}else{ echo "value='$info_rclose'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="r_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="r_status--tosql_farm_agent_info" value="1" <?php if($info_r_status == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Friday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="fopen--tosql_farm_agent_info" type="text" <?php if($info_f_status == 0){echo "disabled";}else{ echo "value='$info_fopen'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="fclose--tosql_farm_agent_info" type="text" <?php if($info_f_status == 0){echo "disabled";}else{ echo "value='$info_fclose'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
											<input name="f_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="f_status--tosql_farm_agent_info" value="1" <?php if($info_f_status == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Saturday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="saopen--tosql_farm_agent_info" type="text" <?php if($info_sa_status == 0){echo "disabled";}else{ echo "value='$info_saopen'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="saclose--tosql_farm_agent_info" type="text" <?php if($info_sa_status == 0){echo "disabled";}else{ echo "value='$info_saclose'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="sa_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="sa_status--tosql_farm_agent_info" value="1" <?php if($info_sa_status == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Sunday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="suopen--tosql_farm_agent_info" type="text" <?php if($info_su_status == 0){echo "disabled";}else{ echo "value='$info_suopen'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="suclose--tosql_farm_agent_info" type="text" <?php if($info_su_status == 0){echo "disabled";}else{ echo "value='$info_suclose'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="su_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="su_status--tosql_farm_agent_info" value="1" <?php if($info_su_status == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									
									<tr>
										<td>Lunch</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="lunch_open--tosql_farm_agent_info" type="text" value="<?php echo $info_lunch_open; ?>"/>
												</span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="lunch_close--tosql_farm_agent_info" type="text" value="<?php echo $info_lunch_close; ?>"/>
												</span>
											</div>
										</td>
										<td>
										</td>
									</tr>
							   </tbody>
							</table>
							</form>
							<div class="form_row">
									<br>
									<a href="#" class="btn btn-block update_user_data" formid="hoursofop2" dorefresh="1"><i class="icon-share"></i> Update</a>
							</div>
						</div>
									
						
						
						
					</div> <!--End of Hours of Operation Tab -->
					
					<!----------------------------------------
					-
					- Start of change your password tab
					-
					----------------------------------------->
					<div class="tab-pane" id="changepassword">
					
						<form id="change_password_form" name="login_form" enctype="multipart/form-data">
							<div class="form_row">
							<input type="hidden" name="email_hidden" id="email_hidden" value="<?php echo $_SESSION['email']; ?>">
							</div>
							<div class="form_row">
								<label class="field_name align_left"><strong>Current Password:</strong></label>
								<div class="field">
									<input class="span6 passwordcheck input-medium" id="old_password" name="old_password" type="password">
								</div>
							</div>
							<div class="form_row">
								<label class="field_name align_left"><strong>New Password:</strong></label>
								<div class="field">
									<input class="span6 passcomplex input-medium" id="new_password" name="new_password" type="password">
								</div>
							</div>
							<div class="form_row">
								<label class="field_name align_left"><strong>Confirm  New Password:</strong></label>
								<div class="field">
									<input class="span6 input-medium" id="confirm_password" name="confirm_password" type="password">
								</div>
							</div>
							
							<div id="modal_notify_msg" style="padding: 15px;"></div>
						
							<div class="form_row">
								<br>
								<a href="#" class="btn btn-block update_pass" id="changeyourpassword"><i class="icon-share"></i>Update</a>
							</div>
						</form>

					</div><!--End of Change Your Password -->
					
				</div><!--End of Tab Content -->
			</div><!-- End of Well Content-->		
		</div><!-- End of Well declaration -->
	 </div> <!-- End of span11 -->
</div> <!-- End of row-fluid -->



<!--------------------------------------- JavaScript -------------------------------------------->				
<script src="js/jquery-1.10.2.js"></script>	
<script>
function phonelist(e, name) { //Funtion to append phone number values to routeback list in real time
	window[name] = e.value;

	var eval = e.value;	
	var evalinner = e.value.replace(/\D/g,'');	//Strip all except numeric

	if(eval) {
		$("#routeback_phones option[name='"+name+"']").html('<option value="'+evalinner+'" name="'+name+'">' + name + ": " +eval+'</option>');
	}
}; 
</script>
	
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
	
				