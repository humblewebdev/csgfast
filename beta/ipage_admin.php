<?php
if($currentuser['firstname'] == NULL){


} 
$fast_prod_array = explode('#', $currentuser['fast_products']);
?>  
   
	
    <head>
	<title><?php Print "Agent:".$currentuser['full_name']  . " ";  ?></title>


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
				<h5><?php echo $currentuser['firstname'] . " " . $currentuser['lastname'];?></h5>
			</div>

			<div class="well-content no-search">
				<?php
			 
				// Print out the contents of the entry 
				$info_profile_pic = $currentuser['profile_pic'];
			/*	Print "<img src='$info_profile_pic' align='left' style='margin: 5px; width: 89px; height: 109px; border: 3px solid #fff;'	/>"; */
				Print "<br><b>DO NOT GIVE OUT   </b> "; 
				Print "</br><b>Route Back #</b> ". formatPhone($currentuser['rback'])  . ""; 
				
				?>
				<br><br><a href="<?php Print "".$currentuser['website']  . "";?>" class="btn blue" target="_blank" ><i class="icon-globe"></i> &nbsp;Farmers Website Link</a><br>
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
			$fromMYSQL = $currentuser['portal_update_time'] ; 
			echo "<center><b>Last Updated: </b>";
			if($currentuser['portal_update_time']){ // if the time is not null
				Print $fromMYSQL; 
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
					<tr><td><strong>AGENT CODE: </strong><?php Print "".$currentuser['agent_code']  . " ";   ?></td></tr>
					<tr><td><strong>EMAIL: </strong><?php Print "".$currentuser['email']  . " ";   ?></td></tr>
					<tr>
					<td><strong>ADDRESS: </strong><?php Print "".$currentuser['address']  . ", " . $currentuser['city'] . ", " . $currentuser['state']. ", " . $currentuser['zipcode'];   ?></td>
					<!--<td align="right"><a href="map_address.php?users_id=</?php echo $currentuser['users_id']; ?>" class="btn blue" target="_blank" ><i class="icon-map-marker"></i> &nbsp;View Map</a></td> -->
					<td align="right"><a href="#" onclick="window.open('map_address.php?users_id=<?php echo $currentuser['users_id']; ?>', 'newwindow', 'width=500, height=500'); return false" class="btn blue"><i class="icon-map-marker"></i> &nbsp;View Map</a></td>
					
					</tr>
					<tr><td><strong>FAX: </strong><?php Print "".formatPhone($currentuser['fax'])  . " ";   ?></td></tr>
					<tr><td><strong>TIME ZONE: </strong><?php Print "".($currentuser['timezone'])  . " ";   ?></td></tr>
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
				<table class="table table-striped table-hover">
					<tr>
						<td>Monday</td>
						<td><?php if(isset($currentuser['m_status']) && $currentuser['m_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['mopen']  . " ";  }    ?></td>
						<td>to</td>
						<td><?php if($currentuser['m_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['mclose']  . " ";  }    ?></td>
					</tr>
					<tr>
						<td>Tuesday</td>
						<td><?php if($currentuser['t_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['topen']  . " ";  }    ?></td>
						<td>to</td>
						<td><?php if($currentuser['t_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['tclose']  . " ";  }   ?></td>
					</tr>
					<tr>
						<td>Wednesday</td>
						<td><?php if($currentuser['w_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['wopen']  . " ";  }    ?></td>
						<td>to</td>
						<td><?php if($currentuser['w_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['wclose'] . " ";  }   ?></td>
					</tr>
					<tr>
						<td>Thursday</td>
						<td><?php if($currentuser['r_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['ropen']  . " ";  }   ?></td>
						<td>to</td>
						<td><?php if($currentuser['r_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['rclose']  . " ";  }   ?></td>
					</tr>
					<tr>
						<td>Friday</td>
						<td><?php if($currentuser['f_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['fopen']  . " ";  }  ?></td>
						<td>to</td>
						<td><?php if($currentuser['f_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['fclose']  . " ";  }   ?></td>
					</tr>
					<tr>
						<td>Saturday</td>
						<td><?php if($currentuser['sa_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['saopen']  . " ";  } ?></td>
						<td>to</td>
						<td><?php if($currentuser['sa_status'] == 0){ echo "Closed"; } else{ Print "".$currentuser['saclose']  . " ";  }  ?></td>
					</tr>
					<tr>
						<td>Sunday</td>
						<td><?php if($currentuser['suopen'] == $currentuser['suclose']){ echo "Closed"; } else{ Print "".$currentuser['suopen']  . " ";  } ?></td>
						<td>to</td>
						<td><?php if($currentuser['suopen'] == $currentuser['suclose']){ echo "Closed"; } else{ Print "".$currentuser['suclose']  . " ";  } ?></td>
					</tr>
				</table>
				
				<table class="table_hours" border="0" style="background-color:#FFF" width="100%" cellpadding="2" cellspacing="3">
				<tr>
					<td>Lunch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td><?php Print "".$currentuser['lunch_open'] . " ";   ?></td>
					<td>to</td>
					<td><?php Print "".$currentuser['lunch_close'] . " ";   ?></td>
				</tr>
				</table>
			</div>
		</div>
	</div>
	
	
	<!---------------------------------------------------------------------------------
	-
	- 								Staff Information
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
				
				<?php 
				/*------------- quick and ugly fix for staff issue ----------------------*/
					$admin_staffname1 = $currentuser['staffname1'];
					$admin_staffname2 = $currentuser['staffname2'];
					$admin_staffname3 = $currentuser['staffname3'];
					$admin_staffname4 = $currentuser['staffname4'];
					$admin_staffname5 = $currentuser['staffname5'];
					$admin_staffname6 = $currentuser['staffname6'];
					
					$admin_staffposition1 = $currentuser['staffposition1'];
					$admin_staffposition2 = $currentuser['staffposition2'];
					$admin_staffposition3 = $currentuser['staffposition3'];
					$admin_staffposition4 = $currentuser['staffposition4'];
					$admin_staffposition5 = $currentuser['staffposition5'];
					$admin_staffposition6 = $currentuser['staffposition6'];
					
					$admin_staffphone1 = $currentuser['staffphone1'];
					$admin_staffphone2 = $currentuser['staffphone2'];
					$admin_staffphone3 = $currentuser['staffphone3'];
					$admin_staffphone4 = $currentuser['staffphone4'];
					$admin_staffphone5 = $currentuser['staffphone5'];
					$admin_staffphone6 = $currentuser['staffphone6'];
					
					$admin_staffemail1 = $currentuser['staffemail1'];
					$admin_staffemail2 = $currentuser['staffemail2'];
					$admin_staffemail3 = $currentuser['staffemail3'];
					$admin_staffemail4 = $currentuser['staffemail4'];
					$admin_staffemail5 = $currentuser['staffemail5'];
					$admin_staffemail6 = $currentuser['staffemail6'];
					
					$admin_staffemail1 = $currentuser['staffemail1'];
					$admin_staffemail2 = $currentuser['staffemail2'];
					$admin_staffemail3 = $currentuser['staffemail3'];
					$admin_staffemail4 = $currentuser['staffemail4'];
					$admin_staffemail5 = $currentuser['staffemail5'];
					$admin_staffemail6 = $currentuser['staffemail6'];
					
					$admin_staff1open = $currentuser['staff1open'];
					$admin_staff2open = $currentuser['staff2open'];
					$admin_staff3open = $currentuser['staff3open'];
					$admin_staff4open = $currentuser['staff4open'];
					$admin_staff5open = $currentuser['staff5open'];
					$admin_staff6open = $currentuser['staff6open'];
					
					$admin_staff1close = $currentuser['staff1close'];
					$admin_staff2close = $currentuser['staff2close'];
					$admin_staff3close = $currentuser['staff3close'];
					$admin_staff4close = $currentuser['staff4close'];
					$admin_staff5close = $currentuser['staff5close'];
					$admin_staff6close = $currentuser['staff6close'];
				
				?>
				
				<?php for($istaff = 1; $istaff <=6; $istaff++){ if(${"admin_staffname".$istaff} != ""){?>
				
				<tr >
					<td style="border-bottom: 1px solid #23628d;">
					    <table>
						<?php if(${"admin_staffname".$istaff} != NULL){ ?><tr><td><strong>NAME: </strong></td><td><?php Print "".${"admin_staffname".$istaff}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"admin_staffposition".$istaff} != NULL){ ?><tr><td><strong>POSITION: </strong></td><td><?php Print "".${"admin_staffposition".$istaff}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"admin_staffphone".$istaff} != NULL){ ?><tr><td><strong>PHONE: </strong></td><td><?php Print "".formatPhone(${"admin_staffphone".$istaff})  . " ";   ?></td></tr><?php } ?>
						<?php if(${"admin_staffemail".$istaff} != NULL){ ?><tr><td><strong>EMAIL: </strong></td><td><?php Print "".${"admin_staffemail".$istaff}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"admin_staff".$istaff."open"} != NULL){ ?><tr><td><strong>IN: </strong></td><td><?php Print "".${"admin_staff".$istaff."open"}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"admin_staff".$istaff."close"} != NULL){ ?><tr><td><strong>OUT: </strong></td><td><?php Print "".${"admin_staff".$istaff."close"}  . " ";   ?></td></tr><?php } ?>
				        </table>
				</tr>
				<?php }} ?>
				
				</table>
				
				<!--</div> -->
			</div>
		</div>
	</div>
	
		


</div><!-- end of first half span 6 -->

<!------------------------------------------ SECOND HALF -------------------------------------->
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
				Print " ".$currentuser['livestatus']  . " <br>";
				?>
			</div>
		</div>
	</div>
	
	
	
	<!---------------------------------------------------------------------------------
	-
	- 								Call Routing
	-
	------------------------------------------------------------------------------------>
		
		<?php if(in_array("1",$fast_prod_array)){ ?>
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Call Route Location</h5>
			</div>

			<div class="well-content no-search">
				<?php $callchoice = explode('#', $currentuser['calltypes_notes'] );?>

				<table class="calltypes"  style="border: 1px solid #9AC0CD;" width="100%">
				<tr>
					<th>Farmers</th>
					<th>My Office</th>
					<th>Call Types</th>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn0" value="0_seamless" <?php if($currentuser['calltypes_notes']  == NULL || $callchoice[0] == '0_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn0" value="0_office" <?php if($callchoice[0] == '0_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Cancellations</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn1" value="1_seamless" <?php if($currentuser['calltypes_notes']  == NULL || $callchoice[1] == '1_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn1" value="1_office" <?php if($callchoice[1] == '1_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Coverage Changes</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn2" value="2_seamless" <?php if($currentuser['calltypes_notes'] == NULL || $callchoice[2] == '2_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn2" value="2_office" <?php if($callchoice[2] == '2_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Claims</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn3" value="3_seamless" <?php if($currentuser['calltypes_notes']  == NULL || $callchoice[3] == '3_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn3" value="3_office" <?php if($callchoice[3] == '3_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">New Business</td>
				</tr>

				</table>
				<?php $callchoice2 = explode('#', $currentuser['calltypes_notes2'] );?>
			</div>
		</div>

	
	<!---------------------------------------------------------------------------------
	-
	- 								After Hours Call Routing
	-
	------------------------------------------------------------------------------------>
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
					<td align="center"><input type="radio" class="calltypes" name="cn20" value="0_seamless" <?php if($currentuser['calltypes_notes2']  == NULL || $callchoice2[0] == '0_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn20" value="0_office" <?php if($callchoice2[0] == '0_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Cancellations</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn21" value="1_seamless" <?php if($currentuser['calltypes_notes2']  == NULL || $callchoice2[1] == '1_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn21" value="1_office" <?php if($callchoice2[1] == '1_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Coverage Changes</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn22" value="2_seamless" <?php if($currentuser['calltypes_notes2']  == NULL || $callchoice2[2] == '2_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn22" value="2_office" <?php if($callchoice2[2] == '2_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">Claims</td>
				</tr>
				<tr>
					<td align="center"><input type="radio" class="calltypes" name="cn23" value="3_seamless" <?php if($currentuser['calltypes_notes2']  == NULL || $callchoice2[3] == '3_seamless'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center"><input type="radio" class="calltypes" name="cn23" value="3_office" <?php if($callchoice2[3] == '3_office'){echo "checked";} ?> disabled='disabled'/></td>
					<td align="center">New Business</td>
				</tr>

				</table>
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
					<tr><td><strong>Appts. are set with:</strong></td><td style="text-align: right;"><?php echo $currentuser['where_to_set_appts'] ; ?></td></tr>
					<tr><td><strong>1st Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $currentuser['primary_appt_pref'] ; ?></td></tr>
					<tr><td><strong>2nd Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $currentuser['secondary_appt_pref'] ; ?></td></tr>
					<tr><td><strong>Length of Appts:</strong></td><td style="text-align: right;"><?php echo $currentuser['initial_phone_appt_length'] ; ?></td></tr>
					<tr><td><strong>Time in-between Appts:</strong></td><td style="text-align: right;"><?php echo $currentuser['timeframe_between_appts'] ; ?></td></tr>
					<tr><td><strong>Max in Day:</strong></td><td style="text-align: right;"><?php echo $currentuser['max_day_appts'] ; ?></td></tr>
					<tr><td><strong>Max in Week:</strong></td><td style="text-align: right;"><?php echo $currentuser['max_week_appts']; ?></td></tr>
					<tr><td><strong>Other Services:</strong></td><td style="text-align: left;"><?php echo $currentuser['other_services_provided']; ?></td></tr>
					<tr><td><strong>Special Details:</strong></td><td style="text-align: left;"><?php echo $currentuser['special_details']; ?></td></tr>
				</table>
				<hr></hr>
				<table width="100%" style="">
					<tr><td><strong>Travel Capable:</strong></td><td style="text-align: right;"><?php echo $currentuser['travel_capable'] ; ?></td></tr>
					<tr><td><strong>How far out Appts. set:</strong></td><td style="text-align: right;"><?php echo $currentuser['how_far_out_by_week']  . " weeks"; ?></td></tr>
				</table>
				<hr></hr>
				<table width="100%" style="">
					<tr><td><strong>Send EPrints on behalf of agent?</strong></td><td style="text-align: right;"><?php echo $currentuser['send_eprint'] ; ?></td></tr>
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
					<tr><td><strong>Appts. are set with:</strong></td><td style="text-align: right;"><?php echo $currentuser['alerts_where_to_set_appts'] ; ?></td></tr>
					<tr><td><strong>1st Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $currentuser['alerts_primary_appt_pref'] ; ?></td></tr>
					<tr><td><strong>2nd Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $currentuser['alerts_secondary_appt_pref'] ; ?></td></tr>
					<tr><td><strong>Length of Appts:</strong></td><td style="text-align: right;"><?php echo $currentuser['alerts_initial_phone_appt_length'] ; ?></td></tr>
					<tr><td><strong>Special Details:</strong></td><td style="text-align: left;"><?php echo $currentuser['alerts_special_details']; ?></td></tr>
				</table>
			</div>
		</div>
	</div>
	<?php } ?>
</div>	
</div>
