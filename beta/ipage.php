<?php
if($info_firstname == NULL){

}

 $fast_prod_array = explode('#', $info_fast_products);
?>  
   
	
    <head>
	<title><?php Print "Agent:".$info_full_name  . " ";  ?></title>


	 <script src="js/jquery-1.10.2.js"></script>
	</head>
<div class="row-fluid">
<div class="span6">


	<!---------------------------------------------------------------------------------
	-
	- 								Agent Info
	-
	------------------------------------------------------------------------------------>
	<div class="">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5><?php echo $info_firstname . " " . $info_lastname;?></h5>
			</div>

			<div class="well-content no-search">
				<?php
			 
				// Print out the contents of the entry 

				Print "<img src='$info_profile_pic' align='left' style='margin: 5px; width: 89px; height: 109px; border: 3px solid #fff;'	/>";
				Print "<br><b>DO NOT GIVE OUT   </b> "; 
				Print "</br><b>Route Back #</b> ". formatPhone($info_rback)  . ""; 
				
				?>
				<br><br><a href="<?php Print "".$info_website  . "";?>" class="btn blue" target="_blank" ><i class="icon-globe"></i> &nbsp;Farmers Website Link</a><br>
			</div>
		</div>
	</div>
	
	<!---------------------------------------------------------------------------------
	-
	- 								Last Updated Area
	-
	------------------------------------------------------------------------------------>
	<div class="">			
		<?php 
			$fromMYSQL = $info_portal_update_time ; 
			echo "<center><b>Last Updated: </b>";
			if($info_portal_update_time){ // if the time is not null
				Print $info_portal_update_time; 
			}else{
				Print "Portal has not been updated."; 
			}
			echo "</center><br>";
		?>	
	</div>
	   
	
	<!---------------------------------------------------------------------------------
	-
	- 								Agency Information
	-
	------------------------------------------------------------------------------------>
	<div class="">
		<div class="well <?php echo $info_ui_color; ?>">
			
			<div class="well-header">
				<h5>Agency Info</h5>
			</div>

			<div class="well-content no-search">
				<table class="table_hours" border="0" width="100%" style="background-color:#FFF;  z-index: 100; font-size: 11px;" cellpadding="2" cellspacing="3">
					<tr><td><strong>AGENT CODE: </strong><?php Print "".$info_agent_code  . " ";   ?>
					</td></tr>
					<tr><td><strong>EMAIL: </strong><?php Print "".$info_email  . " ";   ?>
					</td></tr>
					<tr><td><strong>ADDRESS: </strong><?php Print "".$info_address  . ", " . $info_city . ", " . $info_state . ", " . $info_zipcode;   ?>
					</td></tr>
					<tr><td><strong>FAX: </strong><?php Print "".formatPhone($info_fax)  . " ";   ?>
					</td></tr>
					<tr><td><strong>TIME ZONE: </strong><?php Print "".($info_timezone)  . " ";   ?>
					</td></tr>
				</table>
			</div>
		</div>
	</div>
	
	
	<!---------------------------------------------------------------------------------
	-
	- 								Hours of Operation
	-
	------------------------------------------------------------------------------------>
	<div class="">
			<div class="well <?php echo $info_ui_color; ?>">
				<div class="well-header">
					<div id="ipagehoo" style="cursor: pointer;">
					<h5>Hours of operation</h5>
					</div>
				</div>

				<div class="well-content no-search">
					<!--<div class="ipagehoo"  style="display: none;"> -->
					<table class="table table-striped table-hover">
					<tr>
						<td>Monday</td>
						<td><?php if($info_m_status == 0){ echo "Closed"; } else{ Print "".$info_mopen  . " ";  }    ?></td>
						<td>to</td>
						<td><?php if($info_m_status == 0){ echo "Closed"; } else{ Print "".$info_mclose  . " ";  }    ?></td>
					</tr>
					<tr>
						<td>Tuesday</td>
						<td><?php if($info_t_status == 0){ echo "Closed"; } else{ Print "".$info_topen  . " ";  }    ?></td>
						<td>to</td>
						<td><?php if($info_t_status == 0){ echo "Closed"; } else{ Print "".$info_tclose  . " ";  }   ?></td>
					</tr>
					<tr>
						<td>Wednesday</td>
						<td><?php if($info_w_status == 0){ echo "Closed"; } else{ Print "".$info_wopen  . " ";  }    ?></td>
						<td>to</td>
						<td><?php if($info_w_status == 0){ echo "Closed"; } else{ Print "".$info_wclose  . " ";  }   ?></td>
					</tr>
					<tr>
						<td>Thursday</td>
						<td><?php if($info_r_status == 0){ echo "Closed"; } else{ Print "".$info_ropen  . " ";  }   ?></td>
						<td>to</td>
						<td><?php if($info_r_status == 0){ echo "Closed"; } else{ Print "".$info_rclose  . " ";  }   ?></td>
					</tr>
					<tr>
						<td>Friday</td>
						<td><?php if($info_f_status == 0){ echo "Closed"; } else{ Print "".$info_fopen  . " ";  }  ?></td>
						<td>to</td>
						<td><?php if($info_f_status == 0){ echo "Closed"; } else{ Print "".$info_fclose  . " ";  }   ?></td>
					</tr>
					<tr>
						<td>Saturday</td>
						<td><?php if($info_sa_status == 0){ echo "Closed"; } else{ Print "".$info_saopen  . " ";  } ?></td>
						<td>to</td>
						<td><?php if($info_sa_status == 0){ echo "Closed"; } else{ Print "".$info_saclose  . " ";  }  ?></td>
					</tr>
					<tr>
						<td>Sunday</td>
						<td><?php if($info_suopen == $info_suclose){ echo "Closed"; } else{ Print "".$info_suopen  . " ";  } ?></td>
						<td>to</td>
						<td><?php if($info_suopen == $info_suclose){ echo "Closed"; } else{ Print "".$info_suclose  . " ";  } ?></td>
					</tr>
					
				</table>
				<!--</div> -->

			</div>
		</div>
	</div>
	
	
		<!---------------------------------------------------------------------------------
	-
	- 								Staff  Information
	-
	------------------------------------------------------------------------------------>
	<div class="">
		<div class="well <?php echo $info_ui_color; ?>" >
			<div class="well-header" >
			    <div id="ipagestaff" style="cursor: pointer;">
				<h5>Staff</h5>
				</div>
			</div>

			<div class="well-content no-search">
				<!--<div class="ipagestaff"  style="display: none;"> -->
				
				<table class="table_hours" border="1" width="100%" style="background-color:#FFF;  z-index: 100; font-size: 11px;" cellpadding="2" cellspacing="3">
				<?php for($istaff = 1; $istaff <=6; $istaff++){ if(${"info_staffname".$istaff} != ""){?>
				
				<tr >
					<td style="border-bottom: 1px solid #23628d;">
					    <table>
						<?php if(${"info_staffname".$istaff} != NULL){ ?><tr><td><strong>NAME: </strong></td><td><?php Print "".${"info_staffname".$istaff}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staffposition".$istaff} != NULL){ ?><tr><td><strong>POSITION: </strong></td><td><?php Print "".${"info_staffposition".$istaff}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staffphone".$istaff} != NULL){ ?><tr><td><strong>PHONE: </strong></td><td><?php Print "".formatPhone(${"info_staffphone".$istaff})  . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staffemail".$istaff} != NULL){ ?><tr><td><strong>EMAIL: </strong></td><td><?php Print "".${"info_staffemail".$istaff}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staff".$istaff."open"} != NULL){ ?><tr><td><strong>IN: </strong></td><td><?php Print "".${"info_staff".$istaff."open"}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staff".$istaff."close"} != NULL){ ?><tr><td><strong>OUT: </strong></td><td><?php Print "".${"info_staff".$istaff."close"}  . " ";   ?></td></tr><?php } ?>
				        </table>
				</tr>
				<?php }} ?>
				
				</table>
				
				<!--</div> -->
			</div>
		</div>
	</div>
	
</div><!-- End of first half -->	





<!--------------------------------------------- Second Half --------------------------------------------->
<div class="span6">

	<!---------------------------------------------------------------------------------
	-
	- 								Urgent Notes
	-
	------------------------------------------------------------------------------------>
    <div class="">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Urgent Notes</h5>
			</div>

			<div class="well-content no-search">

				<?php
				Print " ".$info_livestatus  . " <br>";
				?>
			</div>
		</div>
	</div>
	
	
	<?php if(in_array("1",$fast_prod_array)){ ?>
	<!---------------------------------------------------------------------------------
	-
	- 								Call Routing
	-
	------------------------------------------------------------------------------------>
	<div class="prod_id_1 hide">
	
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Call Route Location</h5>
			</div>

			<div class="well-content no-search">
				<?php $callchoice = explode('#', $info_calltypes_notes );?>

				<table class="calltypes"  style="border: 1px solid #9AC0CD;" width="100%">
				<tr>
					<th>Farmers</th>
					<th>My Office</th>
					<th>Call Types</th>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn0" value="0_seamless" <?php if($info_calltypes_notes  == NULL || $callchoice[0] == '0_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn0" value="0_office" <?php if($callchoice[0] == '0_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Cancellations</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn1" value="1_seamless" <?php if($info_calltypes_notes  == NULL || $callchoice[1] == '1_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn1" value="1_office" <?php if($callchoice[1] == '1_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Coverage Changes</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn2" value="2_seamless" <?php if($info_calltypes_notes  == NULL || $callchoice[2] == '2_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn2" value="2_office" <?php if($callchoice[2] == '2_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Claims</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn3" value="3_seamless" <?php if($info_calltypes_notes  == NULL || $callchoice[3] == '3_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn3" value="3_office" <?php if($callchoice[3] == '3_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">New Business</td>
				</tr>

				</table>
				<?php $callchoice2 = explode('#', $info_calltypes_notes2 );?>
			</div>
		</div>
		
	</div>

	<!---------------------------------------------------------------------------------
	-
	- 								Call Routing - After Hours
	-
	------------------------------------------------------------------------------------>
    <div class="prod_id_1 hide">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Call Routing - Outside Business Hours</h5>
			</div>

			<div class="well-content no-search">
				<table class="calltypes"  style="border: 1px solid #9AC0CD;" width="100%">
				<tr>
					<th>Farmers</th>
					<th>My Office</th>
					<th>Call Types</th>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn20" value="0_seamless" <?php if($info_calltypes_notes2  == NULL || $callchoice2[0] == '0_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn20" value="0_office" <?php if($callchoice2[0] == '0_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Cancellations</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn21" value="1_seamless" <?php if($info_calltypes_notes2  == NULL || $callchoice2[1] == '1_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn21" value="1_office" <?php if($callchoice2[1] == '1_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Coverage Changes</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn22" value="2_seamless" <?php if($info_calltypes_notes2  == NULL || $callchoice2[2] == '2_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn22" value="2_office" <?php if($callchoice2[2] == '2_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Claims</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn23" value="3_seamless" <?php if($info_calltypes_notes2  == NULL || $callchoice2[3] == '3_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn23" value="3_office" <?php if($callchoice2[3] == '3_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">New Business</td>
				</tr>

				</table>
			</div>
		</div>
	</div>	
	<?php } ?>
	
	
	<!---------------------------------------------------------------------------------
	-
	- 								FFR Outbound
	-
	------------------------------------------------------------------------------------>
	<?php if(in_array("2",$fast_prod_array)){ ?>
	<div class="">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>FFR Outbound</h5>
			</div>

			<div class="well-content no-search">	
				<table width="100%" style="">
		 	<tr><td><strong>Appts. are set with:</strong></td><td style="text-align: right;"><?php echo $info_where_to_set_appts ; ?></td></tr>
			<tr><td><strong>1st Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $info_primary_appt_pref ; ?></td></tr>
			<tr><td><strong>2nd Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $info_secondary_appt_pref ; ?></td></tr>
			<tr><td><strong>Length of Appts:</strong></td><td style="text-align: right;"><?php echo $info_initial_phone_appt_length; ?></td></tr>
			<tr><td><strong>Time in-between Appts:</strong></td><td style="text-align: right;"><?php echo $info_timeframe_between_appts ; ?></td></tr>
			<tr><td><strong>Max in Day:</strong></td><td style="text-align: right;"><?php echo $info_max_day_appts ; ?></td></tr>
			<tr><td><strong>Max in Week:</strong></td><td style="text-align: right;"><?php echo $info_max_week_appts; ?></td></tr>
			<tr><td><strong>Other Services:</strong></td><td style="text-align: left;"><?php echo $info_other_services_provided; ?></td></tr>
			<tr><td><strong>Special Details:</strong></td><td style="text-align: left;"><?php echo $info_special_details; ?></td></tr>
		</table>
		<hr></hr>
		<table width="100%" style="">
			<tr><td><strong>Travel Capable:</strong></td><td style="text-align: right;"><?php echo $info_travel_capable ; ?></td></tr>
			<tr><td><strong>How far out Appts. set:</strong></td><td style="text-align: right;"><?php echo $info_how_far_out_by_week  . " weeks"; ?></td></tr>
		</table>
		<hr></hr>
		<table width="100%" style="">
			<tr><td><strong>Send EPrints on behalf of agent?</strong></td><td style="text-align: right;"><?php echo $info_send_eprint ; ?></td></tr>
		</table>
			</div>
		</div>
	</div>
	<?php } ?>
	
	
	<!---------------------------------------------------------------------------------
	-
	- 								Alerts Only
	-
	------------------------------------------------------------------------------------>
	<?php if(in_array("3",$fast_prod_array)){ ?>
	<div class="">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Alerts Only</h5>
			</div>

			<div class="well-content no-search">	
				 <table width="100%">
					<tr><td><strong>Appts. are set with:</strong></td><td style="text-align: right;"><?php echo $info_alerts_where_to_set_appts ; ?></td></tr>
				<tr><td><strong>1st Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $info_alerts_primary_appt_pref ; ?></td></tr>
				<tr><td><strong>2nd Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $info_alerts_secondary_appt_pref ; ?></td></tr>
				<tr><td><strong>Length of Appts:</strong></td><td style="text-align: right;"><?php echo $info_alerts_initial_phone_appt_length ; ?></td></tr>
				<tr><td><strong>Special Details:</strong></td><td style="text-align: left;"><?php echo $info_alerts_special_details; ?></td></tr>
				</table>
			</div>
		</div>
	</div>
	<?php } ?>
</div>	
</div>