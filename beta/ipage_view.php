<!DOCTYPE html>
<html>
	<head>
<link rel="stylesheet" href="css/reset.css" />
<link rel="stylesheet" href="css/esplendido.css" />
<link rel="stylesheet" href="css/light.css" />
<link rel="stylesheet" href="css/grid-fluid.css" />

<script src="js/jquery.js"></script>
<script src="js/esplendido.js"></script>



<?php

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/

$uid = $_GET['users_id'];


define ("DB_HOST", "localhost"); // set database host
define ("DB_USER", "root"); // set database user
define ("DB_PASS","QwertY4321"); // set database password
define ("DB_NAME","csg_fast_prod"); // set database name

date_default_timezone_set("America/Chicago");

$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("Error " . mysqli_error($mysqli));

/**** Select all data for the current user accross multiple tables that include the users_id column******/
$searchinfo = $mysqli->query("SELECT * FROM users t
                              LEFT JOIN farm_agent_info t1
							  ON t.users_id = t1.users_id
							  LEFT JOIN farm_agent_staff_info t2
							  ON t.users_id = t2.users_id
							  LEFT JOIN farm_agent_staff_info t3
							  ON t.users_id = t3.users_id
							  LEFT JOIN farm_incontact_info t4
							  ON t.users_id = t4.users_id
							  LEFT JOIN products_ext t5
							  ON t.users_id = t5.users_id
                              WHERE t.users_id='$uid';");

$userinfo = $searchinfo->fetch_assoc();


foreach($userinfo as $field => $value){
	${"info_$field"} = $value;
}
?>

	<?php
		 $fast_prod_array = explode('#', $info_fast_products);
	?>
	<title><?php Print "Agent:".$info_full_name. " ";  ?></title>


	</head>
	<body onload="moveTo(0,0); resizeTo(650,950);">

        <div id="wrapper" class="container_12">

            

    <div class="grid_12 alpha omega">
	<div class="grid_6 column">
	
	<!---------------------------------------------------------------------------------
	-
	- 								Agent Info
	-
	------------------------------------------------------------------------------------>
	<div class="widget minimizable" >
	<header>
		<div class="title">
			<h2><?php Print "<b>Agent    : <font color='red'>". $info_firstname . " " . $info_lastname . "</font></b> "; ?></h2>
		</div>
	</header>
	<div class="content">
	<?php
 
    // Print out the contents of the entry 

    Print "<img src='https" . substr($info_profile_pic, 4) . "' align='left' style='margin: 5px; width: 89px; height: 109px; border: 3px solid #fff;'	/>";
	Print "<br><b>DO NOT GIVE OUT   </b> "; 
	Print "</br><b>Route Back #</b> ".$info_rback . ""; 
	
	?>
	<br><br><a href="<?php Print "".$info_website . "";?>" style="text-decoration: none;" target="_blank" >Click for Website</a><br><br><br>
	</div>
	</div>
   
   
	<!---------------------------------------------------------------------------------
	-
	- 								Last Updated Area
	-
	------------------------------------------------------------------------------------>
	<?php
    $fromMYSQL = $info_portal_update_time;

	Print "<center><b>Last Updated:</b> ". date("m-d-Y g:ia", strtotime($fromMYSQL)) . "</center><br>"; ?>
	
	
	
	<!---------------------------------------------------------------------------------
	-
	- 								Staff Information
	-
	------------------------------------------------------------------------------------>
	<div class="widget minimizable" >

	<header >
		<div class="title" id="staff" style="cursor: pointer;">
			<h2>Staff (Show/Hide)</h2>
		</div>
	</header>

	<div class="content">
	
	
	<div class="staff" style="display: none;">
	
		<table class="table_hours" border="1" width="100%" style="background-color:#FFF;  z-index: 100; font-size: 11px;" cellpadding="2" cellspacing="3">
				<?php for($istaff = 1; $istaff <=6; $istaff++){ if(${"info_staffname".$istaff} != ""){?>
				
				<tr >
					<td style="border-bottom: 1px solid #23628d;">
					    <table>
						<?php if(${"info_staffname".$istaff} != NULL){ ?><tr><td><strong>NAME: </strong></td><td><?php Print "".${"info_staffname".$istaff}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staffposition".$istaff} != NULL){ ?><tr><td><strong>POSITION: </strong></td><td><?php Print "".${"info_staffposition".$istaff}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staffphone".$istaff} != NULL){ ?><tr><td><strong>PHONE: </strong></td><td><?php Print "". /*formatPhone(${"info_staffphone".$istaff})*/ ${"info_staffphone".$istaff} . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staffemail".$istaff} != NULL){ ?><tr><td><strong>EMAIL: </strong></td><td><?php Print "".${"info_staffemail".$istaff}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staff".$istaff."open"} != NULL){ ?><tr><td><strong>IN: </strong></td><td><?php Print "".${"info_staff".$istaff."open"}  . " ";   ?></td></tr><?php } ?>
						<?php if(${"info_staff".$istaff."close"} != NULL){ ?><tr><td><strong>OUT: </strong></td><td><?php Print "".${"info_staff".$istaff."close"}  . " ";   ?></td></tr><?php } ?>
				        </table>
				</tr>
				<?php }} ?>
				
				</table>
	
	</div>
	
	<!-- Script to show/hide NonEdit More div -->
    <script>
		$('#staff').click(function() {
        $('.staff').slideToggle("slow");
         return false;
        });

    </script>
	</div><!-- End of Div content -->
    </div> 
	
	
	
	
	<!---------------------------------------------------------------------------------
	-
	- 								Hours of Operation
	-
	------------------------------------------------------------------------------------>
	<div class="widget minimizable" >
	<header>
		<div class="title">
			<h2>Hours of Operation</h2>
		</div>
	</header>
	<div class="content inner">

 <table class="table_hours" border="1" style="background-color:#FFF" width="100%" cellpadding="2" cellspacing="3">
	<tr>
		<td>Monday</td>
		<td><?php if(isset($info_m_status) && $info_m_status == 0){ echo "Closed"; } else{ Print "".$info_mopen  . " ";  }    ?></td>
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

<table class="table_hours" border="1" style="background-color:#FFF" width="100%" cellpadding="2" cellspacing="3">
	<tr>
		<td>Lunch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><?php Print "".$info_lunch_open . " ";   ?></td>
		<td>to</td>
		<td><?php Print "".$info_lunch_close . " ";   ?></td>
	</tr>
</table>
</div><!-- End of Div content -->
    </div> 



	<!---------------------------------------------------------------------------------
	-
	- 								Agency Information
	-
	------------------------------------------------------------------------------------>	
<div class="widget minimizable" > 
<header>
		<div class="title">
			<h2>Agency Info</h2>
		</div>
	</header>
	<div class="content inner">

<strong>Agent Code : </strong><?php Print "".$info_agent_code . " ";   ?>
<br>
<strong>Email :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><?php Print "".$info_email . " ";   ?>
<br>
<strong>Address :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><?php Print "".$info_address . " ";   ?>
<br>
<strong>City, State: &nbsp;&nbsp;&nbsp; </strong><?php Print "".$info_city." ".$info_state;   ?>
<br>
<strong>Zip Code: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><?php Print "".$info_zipcode . " ";   ?>
<br>
<strong>Fax #: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><?php Print "".$info_fax . " ";   ?>
<br>

</div><!-- End of Div content -->
    </div> 
<br>

</div>
<div class="grid_6 column">



	<!---------------------------------------------------------------------------------
	-
	- 								Urgent Notes
	-
	------------------------------------------------------------------------------------>
	<div class="widget minimizable" >
	<header>
		<div class="title">
			<h2>Urgent Notes</h2>
		</div>
	</header>
	<div class="content inner">

	<?php
	if(isset($info_livestatus)){
		Print "</br><b></b> ".$info_livestatus . " <br>";
	}
	?>
	
	</div>
	</div>

	
	
	<!---------------------------------------------------------------------------------
	-
	- 								Call Routing
	-
	------------------------------------------------------------------------------------>
	<?php if(in_array("1",$fast_prod_array)) { ?> <!-- Checks to make sure not FFR -->
	<div class="widget minimizable" >
    <header>
		<div class="title">
			<h2>Call Route Location</h2>
		</div>
	</header>
	<div class="content">
	<style>
	.calltypes table {
		width: 300px;
		border: 1px solid #000;
		border-collapse:collapse;
		margin: 0 0 0 20px;
	}
	.calltypes th {
		background-color:#3E6DB0;
		color: white;
		padding: 5px;
	}
	</style>
<?php $callchoice = explode('#', $info_calltypes_notes);?>

<table class="calltypes"  style="border: 1px solid #9AC0CD;" width="100%">
<tr>
    <th>Farmers</th>
    <th>My Office</th>
    <th>Call Types</th>
</tr>
<tr>
    <td align="center"><input type="radio" class="calltypes" name="cn0" value="0_seamless" <?php if($info_calltypes_notes == NULL || $callchoice[0] == '0_seamless'){echo "checked";} ?> disabled='disabled'/></td>
    <td align="center"><input type="radio" class="calltypes" name="cn0" value="0_office" <?php if($callchoice[0] == '0_office'){echo "checked";} ?> disabled='disabled'/></td>
    <td>Cancellations</td>
</tr>
<tr>
    <td align="center"><input type="radio" class="calltypes" name="cn1" value="1_seamless" <?php if($info_calltypes_notes == NULL || $callchoice[1] == '1_seamless'){echo "checked";} ?> disabled='disabled'/></td>
	<td align="center"><input type="radio" class="calltypes" name="cn1" value="1_office" <?php if($callchoice[1] == '1_office'){echo "checked";} ?> disabled='disabled'/></td>
    <td>Coverage Changes</td>
</tr>
<tr>
    <td align="center"><input type="radio" class="calltypes" name="cn2" value="2_seamless" <?php if($info_calltypes_notes == NULL || $callchoice[2] == '2_seamless'){echo "checked";} ?> disabled='disabled'/></td>
	<td align="center"><input type="radio" class="calltypes" name="cn2" value="2_office" <?php if($callchoice[2] == '2_office'){echo "checked";} ?> disabled='disabled'/></td>
    <td>Claims</td>
</tr>
<tr>
    <td align="center"><input type="radio" class="calltypes" name="cn3" value="3_seamless" <?php if($info_calltypes_notes == NULL || $callchoice[3] == '3_seamless'){echo "checked";} ?> disabled='disabled'/></td>
	<td align="center"><input type="radio" class="calltypes" name="cn3" value="3_office" <?php if($callchoice[3] == '3_office'){echo "checked";} ?> disabled='disabled'/></td>
    <td>New Business</td>
</tr>

</table>
<?php $callchoice2 = explode('#', $info_calltypes_notes2);?>
</div>
	</div>
	
	
	
	
	<!---------------------------------------------------------------------------------
	-
	- 								After Hours Call Routing
	-
	------------------------------------------------------------------------------------>
<div class="widget minimizable" >
<header>
                                <div class="title">
                                    <h2>After Hours Routing</h2>
                                </div>
                            </header>

	<div class="content">

<table class="calltypes"  style="border: 1px solid #9AC0CD;" width="100%">
<tr>
    <th>Farmers</th>
    <th>My Office</th>
    <th>Call Types</th>
</tr>
<tr>
    <td align="center"><input type="radio" class="calltypes" name="cn20" value="0_seamless" <?php if($info_calltypes_notes2 == NULL || $callchoice2[0] == '0_seamless'){echo "checked";} ?> disabled='disabled'/></td>
    <td align="center"><input type="radio" class="calltypes" name="cn20" value="0_office" <?php if($callchoice2[0] == '0_office'){echo "checked";} ?> disabled='disabled'/></td>
    <td>Cancellations</td>
</tr>
<tr>
    <td align="center"><input type="radio" class="calltypes" name="cn21" value="1_seamless" <?php if($info_calltypes_notes2 == NULL || $callchoice2[1] == '1_seamless'){echo "checked";} ?> disabled='disabled'/></td>
	<td align="center"><input type="radio" class="calltypes" name="cn21" value="1_office" <?php if($callchoice2[1] == '1_office'){echo "checked";} ?> disabled='disabled'/></td>
    <td>Coverage Changes</td>
</tr>
<tr>
    <td align="center"><input type="radio" class="calltypes" name="cn22" value="2_seamless" <?php if($info_calltypes_notes2 == NULL || $callchoice2[2] == '2_seamless'){echo "checked";} ?> disabled='disabled'/></td>
	<td align="center"><input type="radio" class="calltypes" name="cn22" value="2_office" <?php if($callchoice2[2] == '2_office'){echo "checked";} ?> disabled='disabled'/></td>
    <td>Claims</td>
</tr>
<tr>
    <td align="center"><input type="radio" class="calltypes" name="cn23" value="3_seamless" <?php if($info_calltypes_notes2 == NULL || $callchoice2[3] == '3_seamless'){echo "checked";} ?> disabled='disabled'/></td>
	<td align="center"><input type="radio" class="calltypes" name="cn23" value="3_office" <?php if($callchoice2[3] == '3_office'){echo "checked";} ?> disabled='disabled'/></td>
    <td>New Business</td>
</tr>

</table>
    </div>
	</div>
<?php } ?> 


<!---------------------------------------------------------------------------------
-
- 								FFR Only
-
------------------------------------------------------------------------------------>
<?php if(in_array("2",$fast_prod_array)){ ?>
<div class="widget minimizable" > 
	<header>
		<div class="title">
			<h2>FFR Outbound</h2>
		</div>
	</header>

	<div class="content inner">	
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
<?php }?>


<!---------------------------------------------------------------------------------
-
- 								Alerts Only
-
------------------------------------------------------------------------------------>
<?php if(in_array("3",$fast_prod_array)){ ?>
<div class="widget minimizable" > 
	<!--<div class="prod_id_2 prod_id_3 hide">-->
		<header>	
			<div class="title">
				<h2>ALERTS ONLY</h2>
			</div>
		</header>
		
		<div class="content inner">	
			<table width="100%">
				<tr><td><strong>Appts. are set with:</strong></td><td style="text-align: right;"><?php echo $info_alerts_where_to_set_appts ; ?></td></tr>
				<tr><td><strong>1st Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $info_alerts_primary_appt_pref ; ?></td></tr>
				<tr><td><strong>2nd Appt. Preference:</strong></td><td style="text-align: right;"><?php echo $info_alerts_secondary_appt_pref ; ?></td></tr>
				<tr><td><strong>Length of Appts:</strong></td><td style="text-align: right;"><?php echo $info_alerts_initial_phone_appt_length ; ?></td></tr>
				<tr><td><strong>Special Details:</strong></td><td style="text-align: left;"><?php echo $info_alerts_special_details; ?></td></tr>
			</table>
		</div>
	<!--</div>-->
</div>
<?php } ?>

	</div>
	</div>

 <?php 

$agencyname = 'CSG-Rep';
if(isset($info_full_name)){
	$chatboxtitle = $info_full_name;
}
?>

<?php 
// Close the database connection
	$mysqli->close();
	?>
	
	
	</div>
	</body>
	</html>
