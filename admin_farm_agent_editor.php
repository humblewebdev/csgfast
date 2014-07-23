<!DOCTYPE html>
<?php
error_reporting(0);
//display_errors(0); 
include 'z_scripts/db_connect.php';
page_protect();
checkAdmin("logout");

/**** Set all PHP Variables as $info_(fieldname) for the info of the logged in user **/
include 'z_scripts/set_php_info_vars.php'; 

$uid = $_GET['uid'];
if(isset($_GET['menu'])){
$menucontent = $_GET['menu'];
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CSG FAST:Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/stylesheet.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    
  </head>

  <body>

    <header class="<?php echo $info_ui_color; ?>"> <!-- Header start -->
        <a href="#" class="logo_image"><span class="hidden-480">CSG FAST</span></a>
        
        <ul class="header_actions">
            <li><a href="#"> <?php echo $info_user_full_name . " (Admin)"; ?></a>
            </li>
            <li><a href="z_scripts/do_logout.php"><i class="icon-signout"></i> <span class="hidden-768 hidden-480">Logout</span></a></li>
            <li class="responsive_menu"><a class="iconic" href="#"><i class="icon-reorder"></i></a></li>
        </ul>
    </header>

    <div id="main_navigation" class="<?php echo $info_ui_color; ?>"> <!-- Main navigation start -->
        <div class="inner_navigation">
            <ul class="main">
				<?php
				//if the user has an admin level of 7, then show this tab.
				if($_SESSION['user_level'] == '7'){
				?>
					<li><a href="home_page_admin.php?ic=admin_users"><i class="icon-user"></i>LDAP Users</a></li>
					<li><a href="home_page_admin.php?ic=admin_products"><i class="icon-shopping-cart"></i>Products</a></li>
				<?php } ?>
				<li><a href="home_page_admin.php?ic=admin_farm_agent_table"><i class="icon-headphones"></i>Agents</a></li>
				<li><a href="home_page_admin.php?ic=admin_email"><i class="icon-envelope"></i>Email</a></li>
				<li><a href="home_page_admin.php?ic=admin_audit_trail"><i class="icon-reorder"></i>Audit Trail</a></li>
				<li><a href="home_page_admin.php?ic=admin_prospects"><i class="icon-group"></i>Prospective Users</a></li>
			</ul>
        </div>
    </div>  

    <div id="content" class="no-sidebar"> <!-- Content start -->
            <div class="widgets_area">
			<input id="hidden_users_id" value="<?php echo $info_users_id; ?>" style="display: none;"> <!-- Do not remove!! User id that is used in user updates.-->
			<div id="updatemsgs"></div>
	
<?php 
$admin_id = $_SESSION['user_id'];

$search_farm_users = $mysqli->query("SELECT * FROM users t
                              LEFT JOIN farm_agent_info t1
							  ON t.users_id = t1.users_id
							  LEFT JOIN farm_agent_staff_info t2
							  ON t.users_id = t2.users_id
							  LEFT JOIN farm_agent_staff_info t3
							  ON t.users_id = t3.users_id
							  LEFT JOIN farm_incontact_info t4
							  ON t.users_id = t4.users_id
							  LEFT JOIN products_ext t5
							  ON t.users_id = t5.users_id WHERE t.users_id='$uid';");

$thisuser = array(); // Associative array of all farmers users
$idfarm = 1;

while ($finfo = $search_farm_users->fetch_assoc()) {
  $thisuser[$idfarm] = $finfo; 
  $idfarm++;
}

$currentuser = $thisuser[1];

$productids = explode("#", $currentuser['fast_products']);
$productids_appr = explode("#", $currentuser['fast_products_approved']);

$qproducts_all = $mysqli->query("SELECT * FROM fast_products;") or die($mysqli->error);
$qproducts = $mysqli->query("SELECT * FROM fast_products WHERE product_id IN ('" . implode("','", $productids) . "');") or die($mysqli->error);
$qproducts_pend = $mysqli->query("SELECT * FROM fast_products WHERE product_id NOT IN ('" . implode("','", $productids_appr) . "') AND product_id IN ('" . implode("','", $productids) . "');") or die($mysqli->error);
$qproducts_appr = $mysqli->query("SELECT * FROM fast_products WHERE product_id IN ('" . implode("','", $productids_appr) . "') AND product_id IN ('" . implode("','", $productids) . "');") or die($mysqli->error);
$qproducts_oth = $mysqli->query("SELECT * FROM fast_products WHERE product_id NOT IN ('" . implode("','", $productids) . "');") or die($mysqli->error);

$products_all = array(); //Associative array of signed up items
$products_signedup = array(); //Associative array of signed up items
$products_pending = array(); //Associative array of pending items
$products_approved = array(); //Associative array of approved items
$products_other = array(); // Associative array of items not yet signed up for

/********** amber added ******/
$in_products = $mysqli->query("SELECT * FROM fast_products WHERE product_id IN ('" . implode("','", $productids) . "');") or die($mysqli->error);
$products_inbound = array(); 

//Build Associative arrays that can be used throughout the program
$idpinfo = 1;
$idpinfop = 1;
$idpinfoa = 1;
$idpinfoo = 1;
$idpall = 1;
$in_info = 1;
while ($pinfo = $qproducts->fetch_assoc()) {
  $products_signedup[$idpinfo] = $pinfo; 
  $idpinfo++;
}

while ($pinfop = $qproducts_pend->fetch_assoc()) {
  $products_pending[$idpinfop] = $pinfop; 
  $idpinfop++;
}

while ($pinfoa = $qproducts_appr->fetch_assoc()) {
  $products_approved[$idpinfoa] = $pinfoa; 
  $idpinfoa++;
}

while ($pinfoo = $qproducts_oth->fetch_assoc()) {
  $products_other[$idpinfoo] = $pinfoo; 
  $idpinfoo++;
}

while ($pall = $qproducts_all->fetch_assoc()) {
  $products_all[$idpall] = $pall; 
  $idpall++;
}

/**** amber added ******/
while ($inbound_info = $in_products->fetch_assoc()) {
  $products_find_inbound[$in_info] = $inbound_info; 
  $in_info++;
}

?>

  <link href="css/stylesheet.css" rel="stylesheet">

<div class="row-fluid">
	<div class="span12">
	<table class="table table-condensed well-head">	
			<td><div class="well well-head">
				<div class="well-content well-head">	
					<form name="login_status" id="login_status" method="post">
						<h5><b>Login Status:</b>	
							<div class="switch login_update" style="float: right;"  data-animated="false" data-on="success" data-on-label="Approved" data-off="danger" data-off-label="Locked">
								<input name="users_id" type="hidden" value="<?php echo $currentuser['users_id'];?>">
								<input name="approve" type="hidden" value="0">
								<input type="checkbox" name="approve" value="1" <?php if($currentuser['approved'] == 1){echo "checked";} ?>>					
							</div>
						</h5>
					</form>
				</div>
			</div></td>

			<td><div class="well well-head">
				<div class="well-content well-head">
					<h5><b>Last Login:</b>
					<div style="float: right;"><?php if(isset($currentuser['last_login_timestamp'])){echo $currentuser['last_login_timestamp'];}else{ echo "Never";}?></div></h5>
				</div>
			</div></td>

			<td><div class="well well-head">
				<div class="well-content well-head">
					<h5><b>Last Update:</b> 
					<div style="float: right;"><?php if(isset($currentuser['portal_update_time'])){echo $currentuser['portal_update_time'];}else{ echo "Never";} ?></div></h5>
				</div>
			</div></td>
			
				<?php
				//if the user has an admin level of 7, then show this tab.
				if($_SESSION['user_level'] == '7'){
				?>
				
					<td><div class="well well-head">
				<div class="well-content well-head">
					<h5><b>Delete User:</b> 
					<div style="float: right;"><a href="#deleteusermodal" data-toggle="modal" class="btn btn-primary red"> Delete <?php echo $currentuser['full_name']; ?> ?</a></div></h5>
				</div>
			</div></td>
				
			
				
			<?php } ?>
				
			<!------------------------------- Modal for Deleting User ---------------------------------------->
			<div id="deleteusermodal" class="modal hide fade" data-backdrop="static">
				<div class="modal-header">
				  <h3>Delete User</h3>
				</div>
				<div class="modal-body">
				  <h4><center><p>You are about to delete the user <b><?php echo $currentuser['full_name']; ?></b>. This will remove all associated data including recordings, skills,
				  and POC's. This action cannot be undone.</p>
				  <p>Do you want to proceed?</p></h4></center>
				</div>
				<div class="modal-footer">
				 <center> <button type="button" onclick="farm_user_delete(<?php echo $currentuser['users_id']; ?>)" class="btn btn-primary green">Yes</button>
				   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn red">No</a></center>
				</div>
			</div>
			<!------------------------------ End of Modal for Deleting User ----------------------------------->	
				
	</table>
	</div>
</div> 

<div class="row-fluid">
	<div class="span12">
		<div class="navbar-inner">
			<ul class="nav nav-tabs">
			    <li class="active"><a href="#ainfo" data-toggle="tab"><?php echo $currentuser['full_name']; ?>'s Profile</a></li>
				<?php 
					$product_search = explode("#", $currentuser['fast_products']);
					if(in_array("1",$product_search)){
				?>
				<li><a href="#ainbound" data-toggle="tab">Inbound</a></li>
				<?php } ?>
			
				<?php 
					if(in_array("2",$product_search) || in_array("3",$product_search)){
				?>
				<li><a href='#aoutbound' data-toggle='tab'>Outbound</a></li>
				<?php }	?>
				
				<li><a href="#padmin" data-toggle="tab">Products</a></li>
				<li><a href="#adminnotes" data-toggle="tab">Admin Notes</a></li>
				<li><a href="#loginhist" data-toggle="tab">Login History</a></li>
			</ul>
		</div>
		
		<div class="tab-content">
			
			
			<!--------------------------------------------------
			- 					AGENCY PROFILE
			-
			---------------------------------------------------->			
			<div class="tab-pane no_padding  active" id="ainfo">
						
				<div class="row-fluid">
					<div class="span12">
						<div class="well <?php echo $info_ui_color;?>">
			
							<!--------------- Well Header --------------->
							<div class="well-header">
								<h5><?php echo $currentuser['full_name']."'s Profile"; ?> </h5>
							</div>
		
							<!--------------- Well Content --------------->
							<div class="well-content no-search">
			
			
								<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
									<li class="active"><a href="#basicinfo" data-toggle="tab"><strong>Basic Info</strong></a></li>
									<li><a href="#csgview" data-toggle="tab"><strong>What CSG Sees</strong></a></li>
									<li><a href="#urgentnotes" data-toggle="tab"><strong>Urgent Notes</strong></a></li>
									<li><a href="#yourstaff" data-toggle="tab"><strong> <?php echo $currentuser['full_name']; ?>'s Staff</strong></a></li>
									<li><a href="#hoursoperation" data-toggle="tab"><strong>Hours of Operation</strong></a></li>
									<li><a href="#incontactinfo" data-toggle="tab"><strong>InContact Information</strong></a></li>
									<!--<li><a href="#changepassword" data-toggle="tab"><strong>Change User's Password</strong></a></li> -->
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
										<form id="editprof1" name="editprof1" enctype="multipart/form-data">
											
											<div class="span6">
											
											
												<!--
												-
												- Unchangable Information
												-
												-
												-->

												<div class="form_row control-group error">
													<label class="field_name"><strong>Agent Name:</strong></label>
													<div class="field">
														<span class="field_value"><?php echo $currentuser['firstname'] . " " . $currentuser['lastname']; ?></span>
													</div>
												</div>
												<div class="form_row control-group error">
													<label class="field_name"><strong>Agency Name:</strong></label>
													<div class="field">
														<span class="field_value"><?php echo $currentuser['agencyname']; ?></span>
													</div>
												</div>
												<div class="form_row control-group error">
													<label class="field_name"><strong>Reg. Date:</strong></label>
													<div class="field">
														<span class="field_value"><?php echo $currentuser['reg_date']; ?></span>
													</div>
												</div>
												<div class="form_row control-group error">
													<label class="field_name"><strong>Agent Code:</strong></label>
													<div class="field">
														<span class="field_value"><?php echo $currentuser['agent_code']; ?></span>
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
															<input class="span5 input-medium mask-phone tendigits" id="fax" value="<?php echo $currentuser['fax']; ?>" name="fax--tosql_farm_agent_info" type="text" AUTOCOMPLETE=ON>
														</div>
													</div>
													<div class="form_row">
														<label class="field_name align_left"><strong>Main Business Phone:</strong><span class="required">*</span></label>
														<div class="field">
															<input class="required span5  mask-phone" id="mainphone" value="<?php echo $currentuser['mainphone']; ?>" name="mainphone--tosql_farm_incontact_info" type="text" AUTOCOMPLETE=ON>
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
														<input class="required span8  email" id="changeemail" type="text" value="<?php echo $currentuser['email']; ?>" name="email--tosql_users">
														<label for="changeemail" id="dup_email"></label>
													</div>
													</div>
																		
													<div class="form_row">
														<label class="field_name align_left"><strong>Address:</strong><span class="required">*</span></label>
														<div class="field">
															<input class="span8 requiredmin" type="text" value="<?php echo $currentuser['address']; ?>" name="address--tosql_farm_agent_info">
														</div>
													</div>
													<div class="form_row">
														<label class="field_name align_left"><strong>City:</strong><span class="required">*</span></label>
														<div class="field">
															<input class="span8 requiredmin" type="text" value="<?php echo $currentuser['city']; ?>" name="city--tosql_farm_agent_info">
														</div>
													</div>
														<div class="form_row">
                                                            <label class="field_name align_left"><strong>State</strong><span class="required">*</span></label>
                                                            <div class="field">
                                                                <div class="span4 no-search">
                                                                    <select class="required" id="stateselect" name="state--tosql_farm_agent_info">
                                                                      
																		<option value="AL" <?php if($currentuser['state'] == "AL"){echo "selected";}?>>Alabama</option>
																		<option value="AK" <?php if($currentuser['state'] == "AK"){echo "selected";}?>>Alaska</option>
																		<option value="AZ" <?php if($currentuser['state'] == "AZ"){echo "selected";}?>>Arizona</option>
																		<option value="AR" <?php if($currentuser['state'] == "AR"){echo "selected";}?>>Arkansas</option>
																		<option value="CA" <?php if($currentuser['state'] == "CA"){echo "selected";}?>>California</option>
																		<option value="CO" <?php if($currentuser['state'] == "CO"){echo "selected";}?>>Colorado</option>
																		<option value="CT" <?php if($currentuser['state'] == "CT"){echo "selected";}?>>Connecticut</option>
																		<option value="DE" <?php if($currentuser['state'] == "DE"){echo "selected";}?>>Delaware</option>
																		<option value="FL" <?php if($currentuser['state'] == "FL"){echo "selected";}?>>Florida</option>
																		<option value="GA" <?php if($currentuser['state'] == "GA"){echo "selected";}?>>Georgia</option>
																		<option value="HI" <?php if($currentuser['state'] == "HI"){echo "selected";}?>>Hawaii</option>
																		<option value="ID" <?php if($currentuser['state'] == "ID"){echo "selected";}?>>Idaho</option>
																		<option value="IL" <?php if($currentuser['state'] == "IL"){echo "selected";}?>>Illinois</option>
																		<option value="IN" <?php if($currentuser['state'] == "IN"){echo "selected";}?>>Indiana</option>
																		<option value="IA" <?php if($currentuser['state'] == "IA"){echo "selected";}?>>Iowa</option>
																		<option value="KS" <?php if($currentuser['state'] == "KS"){echo "selected";}?>>Kansas</option>
																		<option value="KY" <?php if($currentuser['state'] == "KY"){echo "selected";}?>>Kentucky</option>
																		<option value="LA" <?php if($currentuser['state'] == "LA"){echo "selected";}?>>Louisiana</option>
																		<option value="ME" <?php if($currentuser['state'] == "ME"){echo "selected";}?>>Maine</option>
																		<option value="MD" <?php if($currentuser['state'] == "MD"){echo "selected";}?>>Maryland</option>
																		<option value="MA" <?php if($currentuser['state'] == "MA"){echo "selected";}?>>Massachusetts</option>
																		<option value="MI" <?php if($currentuser['state'] == "MI"){echo "selected";}?>>Michigan</option>
																		<option value="MN" <?php if($currentuser['state'] == "MN"){echo "selected";}?>>Minnesota</option>
																		<option value="MS" <?php if($currentuser['state'] == "MS"){echo "selected";}?>>Mississippi</option>
																		<option value="MO" <?php if($currentuser['state'] == "MO"){echo "selected";}?>>Missouri</option>
																		<option value="MT" <?php if($currentuser['state'] == "MT"){echo "selected";}?>>Montana</option>
																		<option value="NE" <?php if($currentuser['state'] == "NE"){echo "selected";}?>>Nebraska</option>
																		<option value="NV" <?php if($currentuser['state'] == "NV"){echo "selected";}?>>Nevada</option>
																		<option value="NH" <?php if($currentuser['state'] == "NH"){echo "selected";}?>>New Hampshire</option>
																		<option value="NJ" <?php if($currentuser['state'] == "NJ"){echo "selected";}?>>New Jersey</option>
																		<option value="NM" <?php if($currentuser['state'] == "NM"){echo "selected";}?>>New Mexico</option>
																		<option value="NY" <?php if($currentuser['state'] == "NY"){echo "selected";}?>>New York</option>
																		<option value="NC" <?php if($currentuser['state'] == "NC"){echo "selected";}?>>North Carolina</option>
																		<option value="ND" <?php if($currentuser['state'] == "ND"){echo "selected";}?>>North Dakota</option>
																		<option value="OH" <?php if($currentuser['state'] == "OH"){echo "selected";}?>>Ohio</option>
																		<option value="OK" <?php if($currentuser['state'] == "OK"){echo "selected";}?>>Oklahoma</option>
																		<option value="OR" <?php if($currentuser['state'] == "OR"){echo "selected";}?>>Oregon</option>
																		<option value="PA" <?php if($currentuser['state'] == "PA"){echo "selected";}?>>Pennsylvania</option>
																		<option value="RI" <?php if($currentuser['state'] == "RI"){echo "selected";}?>>Rhode Island</option>
																		<option value="SC" <?php if($currentuser['state'] == "SC"){echo "selected";}?>>South Carolina</option>
																		<option value="SD" <?php if($currentuser['state'] == "SD"){echo "selected";}?>>South Dakota</option>
																		<option value="TN" <?php if($currentuser['state'] == "TN"){echo "selected";}?>>Tennessee</option>
																		<option value="TX" <?php if($currentuser['state'] == "TX"){echo "selected";}?>>Texas</option>
																		<option value="UT" <?php if($currentuser['state'] == "UT"){echo "selected";}?>>Utah</option>
																		<option value="VT" <?php if($currentuser['state'] == "VT"){echo "selected";}?>>Vermont</option>
																		<option value="VA" <?php if($currentuser['state'] == "VA"){echo "selected";}?>>Virginia</option>
																		<option value="WA" <?php if($currentuser['state'] == "WA"){echo "selected";}?>>Washington</option>
																		<option value="WV" <?php if($currentuser['state'] == "WV"){echo "selected";}?>>West Virginia</option>
																		<option value="WI" <?php if($currentuser['state'] == "WI"){echo "selected";}?>>Wisconsin</option>
																		<option value="WY" <?php if($currentuser['state'] == "WY"){echo "selected";}?>>Wyoming</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>  
													<div class="form_row">
														<label class="field_name align_lef"><strong>Zip / Postal Code:</strong></label>
														<div class="field">
															<input class="span8 mask-zip" type="text" value="<?php echo $currentuser['zipcode']; ?>" name="zipcode--tosql_farm_agent_info">
														</div>
													</div>
													<div class="form_row">
														<label class="field_name align_left"><strong>County:</strong></label>
														<div class="field">
															<input class="span8" type="text" value="<?php echo $currentuser['county']; ?>" name="county--tosql_farm_agent_info">
														</div>
													</div>
													<div class="form_row">
														<label class="field_name align_left"><strong>Time Zone:</strong><span class="required">*</span></label>
														<div class="field">
															<div class="span4 no-search">
																<!-- took chosen out--><select class="chosen" name="timezone--tosql_farm_agent_info">
																	<option><?php echo $currentuser['timezone']; ?></option>
																	<option value="Eastern">Eastern</option>
																	<option value="Central">Central</option>
																	<option value="Mountain">Mountain</option>
																	<option value="Mountain_no_dst">Mountain (no DST)</option>
																	<option value="Pacific">Pacific</option>
																</select>
															</div>
														</div>
													</div>
													<div class="form_row">
															<label class="field_name align_left"><strong>District Manager:</strong></label>
															<div class="field">
																<div class="span4">
																	<!-- took chosen out --><select class="required" name="dm_id--tosql_farm_agent_info">
																		<?php 
																		$cur_dm_id = $currentuser['dm_id'];
																		$dm_req1 = "SELECT * from dm_list WHERE id_dm = '$cur_dm_id' LIMIT 1;";
																		$dm_query1 = $mysqli->query($dm_req1)or die($mysqli->error); 
																		$dm_row1 = $dm_query1->fetch_assoc();
																		$dmname = $dm_row1['firstname'] . " " . $dm_row1['lastname'] . " | " . $dm_row1['state_office'];
																			
																		?>
																		<option value="<?php echo $currentuser['dm_id']; ?>"><?php echo $dmname; ?></option>
																		<?php
																		$dm_req2 = "SELECT * from dm_list;";
																		$dm_query2 = $mysqli->query($dm_req2)or die($mysqli->error); 

																			while($dm_row2 = $dm_query2->fetch_assoc())
																			{
																			  echo "<option value='{$dm_row2['id_dm']}' >{$dm_row2['firstname']} {$dm_row2['lastname']} | {$dm_row2['state_office']}</option>";
																			} //$mysqli->close();
																		?>
																	 </select>
																</div>
															</div>
														</div> 
												
													<div class="form_row">
														<label class="field_name"><strong>Spanish Speaking:</strong></label>
														<div class="field">
															<label class="radio">
																<input type="radio" class="uniform" name="spanish--tosql_farm_agent_info" value="Yes" <?php if($currentuser['spanish']=="Yes"){echo "checked";} ?>> Yes
															</label>
															<label class="radio">
																<input type="radio" class="uniform" name="spanish--tosql_farm_agent_info" value="No" <?php if($currentuser['spanish']=="No"){echo "checked";} ?>> No
															</label>
														</div>
													</div>		
											</div><!-- 2nd span 5-->
										</form>	<!-- End of form -->	

										<!-- Update button-->
										<div class="span11">
											<div class="form_row">
													<br>
													<a href="#" class="btn btn-block update_user_data" formid="editprof1" users_id="<?php echo $currentuser['users_id'];?>" dorefresh="1"><i class="icon-share"></i> Update</a>
											</div>
										</div>										
									</div> <!-- End of Row fluid-->
								</div> <!-- End of Basic Info Tab -->
								
							
							
					<!---------------------------------
					-
					- What CSG sees tab
					-
					------------------------------------>
					<div class="tab-pane" id="csgview">
						<?php include 'ipage_admin.php'; ?>
					</div><!-- End of What CSG Sees Tab -->

							
					<!--------------------  URGENT NOTES TAB ----------
					-
					-   Show agent notes
					-  
					----------------------------------------------->			
					<div class="tab-pane" id="urgentnotes">
						
						
						<div class="widgets_area">	
							 <div class="row-fluid">
								<div class="span7">
									<div class="well blue">
										<div class="well-header">
											<h5>Urgent Notes</h5>
										</div>

										<div class="well-content no-search">
										<form id="addednotes">
											<textarea class="textarea"  name="livestatus--tosql_farm_agent_info" placeholder="Enter text(200 chars max)..." style="width: 100%; height: 300px"><?php echo $currentuser['livestatus'];?></textarea>
										</form>
										<div class="form_row">
														<br><a href="#" class="btn btn-block update_user_data" formid="addednotes" dorefresh="1" users_id="<?php echo $currentuser['users_id'];?>"><i class="icon-share"></i> Update</a>
										</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					
					</div><!-- End of CSG Notes Tab -->






							<!--------------------  YOUR STAFF TAB ----------
								-
								-   Show agecy staff members and call routing options
								-  
								----------------------------------------------->							
								<div class="tab-pane" id="yourstaff"> 
								<div id="" style="overflow-y:scroll; height:450px;">
								<form id='stafflist'>
									<?php
											for($staff = 1; $staff <= 6; $staff++){ 
												//if(${"info_staffname".$staff} == ""){ //if it's null, put blank information there
													
													echo "<strong><h4> Staff Member ".$staff."<br></h4></strong>";
													
													//gets the values from the info get variables.
													$current_staff_name = $currentuser["staffname$staff"];
													$current_staff_open = $currentuser["staff$staff"."open"];
													$current_staff_close = $currentuser["staff$staff"."close"];
													$current_staff_phone = $currentuser["staffphone$staff"];
													$current_staff_email = $currentuser["staffemail$staff"];
													$current_staff_position = $currentuser["staffposition$staff"];
													
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
									</div>
									<!-- Update staff button -->
									 <div class="form_row">
										<br><a href="#" class="btn btn-block update_user_data" formid="stafflist" dorefresh="1" users_id="<?php echo $currentuser['users_id'];?>"><i class="icon-share"></i> Update </a>
									</div>
									
									
								</div> <!--End of Your Staff Tab -->
							



							
								<!---------Hours of Op TAB -------------------
								-
								-   Hours of Operation Tab
								-  
								----------------------------------------------->			
								<div class="tab-pane" id="hoursoperation"><!-- start of Hours of Operation Tab -->

									<div class="">
							<form id="hoursofopadmin" name="hoursofopadmin" enctype="multipart/form-data">
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
												<span class="add-on"><input readonly class="input-small timepicker3" name="mopen--tosql_farm_agent_info" type="text" <?php if($currentuser['m_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['mopen']}'"; } ?>/>
												</span> 
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="mclose--tosql_farm_agent_info" type="text" <?php if($currentuser['m_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['mclose']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="m_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="m_status--tosql_farm_agent_info" value="1" <?php if($currentuser['m_status'] == 1){echo "checked";} ?>>
												
											</div>
										</td>
									</tr>
									<tr>
										<td>Tuesday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="topen--tosql_farm_agent_info" type="text" <?php if($currentuser['t_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['topen']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class=" bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="tclose--tosql_farm_agent_info" type="text" <?php if($currentuser['t_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['tclose']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="t_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="t_status--tosql_farm_agent_info" value="1" <?php if($currentuser['t_status'] == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Wednesday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="wopen--tosql_farm_agent_info" type="text" <?php if($currentuser['w_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['wopen']}'"; } ?>/>
												<!--<i class="icon-time"></i>--></span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="wclose--tosql_farm_agent_info" type="text" <?php if($currentuser['w_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['wclose']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="w_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="w_status--tosql_farm_agent_info" value="1" <?php if($currentuser['w_status'] == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Thursday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="ropen--tosql_farm_agent_info" type="text" <?php if($currentuser['r_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['ropen']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class=" bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="rclose--tosql_farm_agent_info" type="text" <?php if($currentuser['r_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['rclose']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="r_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="r_status--tosql_farm_agent_info" value="1" <?php if($currentuser['r_status'] == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Friday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="fopen--tosql_farm_agent_info" type="text" <?php if($currentuser['f_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['fopen']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="fclose--tosql_farm_agent_info" type="text" <?php if($currentuser['f_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['fclose']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
											<input name="f_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="f_status--tosql_farm_agent_info" value="1" <?php if($currentuser['f_status'] == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Saturday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="saopen--tosql_farm_agent_info" type="text" <?php if($currentuser['sa_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['saopen']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="saclose--tosql_farm_agent_info" type="text" <?php if($currentuser['sa_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['saclose']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="sa_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="sa_status--tosql_farm_agent_info" value="1" <?php if($currentuser['sa_status'] == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									<tr>
										<td>Sunday</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="suopen--tosql_farm_agent_info" type="text" <?php if($currentuser['su_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['suopen']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="suclose--tosql_farm_agent_info" type="text" <?php if($currentuser['su_status'] == 0){echo "disabled";}else{ echo "value='{$currentuser['suclose']}'"; } ?>/>
												</span>
											</div>
										</td>
										<td>
											<div class="switch" data-animated="false" data-on="success" data-on-label="Open" data-off="danger" data-off-label="Closed">
												<input name="su_status--tosql_farm_agent_info"  type="hidden" value="0">
												<input type="checkbox" name="su_status--tosql_farm_agent_info" value="1" <?php if($currentuser['su_status'] == 1){echo "checked";} ?>>
											</div>
										</td>
									</tr>
									
									<tr>
										<td>Lunch</td>
										<td>
											<div class="bootstrap-timepicker">
												<span class="add-on"><input readonly class="input-small timepicker3" name="lunch_open--tosql_farm_agent_info" type="text" value="<?php echo $currentuser['lunch_open']; ?>"/>
												</span>
											</div>
										</td>
										<td>
											<div class="bootstrap-timepicker">										   
												<span class="add-on"><input readonly class="input-small timepicker3" name="lunch_close--tosql_farm_agent_info" type="text" value="<?php echo $currentuser['lunch_close']; ?>"/>
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
									<a href="#" class="btn btn-block update_user_data" formid="hoursofopadmin"  users_id="<?php echo $currentuser['users_id'];?>" dorefresh="1"><i class="icon-share"></i> Update</a>
							</div>
						</div>
												
									
									
									
								</div> <!--End of Hours of Operation Tab -->
								
								
								
								<!----------------------------------------
								-
								- InContact Information
								-
								-
								----------------------------------------->
								<div class="tab-pane" id="incontactinfo">
									<?php
										//poc
										if(empty($currentuser['poc'])){
											$poc = "This user does not have a poc yet.";
										}else{
											$poc = $currentuser['poc'];
										}
										
										//rec
										if(empty($currentuser['rec'])){
											$rec = "This user does not have a rec yet.";
										}else{
											$rec = $currentuser['rec'];
										}
										
										//skill name
										if(empty($currentuser['skill'])){
											$skill_name = "This user does not have a skill name yet.";
										}else{
											$skill_name = $currentuser['skill'];
										}
										echo "<b>POC: </b>".$poc."
											<br><b>Rec: </b>".$rec."
											<br><b>Skill: </b>".$skill_name;
									?>
								</div>
								
							</div> <!-- inner tab content -->
							</div><!-- End of Well Content-->		
						</div><!-- End of Well declaration -->
					</div> <!-- End of span12 -->
				</div> <!-- real row fluid -->
			</div><!-- end of agency info tab -->
		    
			<!-----------------
			- END OF AGENCY INFO
			-
			-------------------->
			
			<!------------------------------------------------
			- INBOUND INFO
			-
			--------------------------------------------------->
			<div class="tab-pane no_padding" id="ainbound">
			  
			  
			<?php   
			$skillno = $currentuser['skillno'];
			
			//echo $skillno;


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
					<li class="active"><a href="#inboundoptions" data-toggle="tab"><strong>Inbound Options</strong></a></li>
					<li><a href="#callhistorytoday" data-toggle="tab"><strong>Call History: Today</strong> (Total: <?php echo $count_today[0];?> )</a></li>
					<li><a href="#callhistory7days" data-toggle="tab"><strong>Call History: Last 7 days</strong> (Total: <?php echo $count_7days[0];?> )</a></li>
					
								
				</ul>
				
				<!----------------------- Tab Content ----------------------
				-   Display all agency information in series of tabs
				-   logically displaying general information and product specific information
				-   
				-   Make the first tab be the most frequently updated info.
				 ---------------------------------------------->
				<div id="my-tab-content" class="tab-content">
				
			
					
					<!----- Show call History Today TAB ------------
					-
					-         Show Call History:today
					-  
					----------------------------------------------->	
					<div class="tab-pane" id="callhistorytoday">			
						<table class='table'>
							<thead>
								<tr>
									<th width='12%'>Contact ID</th>
									<th width='11%'>Skill Name</th>
									<th width='15%'>Agent Name</th>
									<th width='13%'>Start</th>
									<th width='12%'>End</th>
									<th width='17%'>Total Time<br>(Minutes)</th>
									<th>Description</th>
								</tr>
							</thead>
						</table>
						<div class="responsive_table_scroll" style=" overflow-y: scroll; height:400px;">
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
									<th width='11%'>Contact ID</th>
									<th width='10%'>Skill Name</th>
									<th width='14%'>Agent Name</th>
									<th width='10%'>Start</th>
									<th width='8%'>End</th>
									<th width='17%'>Total Time<br>(Minutes)</th>
									<th>Description</th>
								</tr>
							</thead>
						</table>
						<div class="responsive_table_scroll">
							<div id="sevencontainer" style="overflow-y: scroll; height:400px;">
								This should fill with data in a few seconds...
							</div>
						</div><!-- -->
					
					</div><!-- End of Call History 7 days Tab-->
					
					
					
					
					<!-----------------------------------------------------
					-
					- Inbound Options Tab
					-
					-
					------------------------------------------------------>	
					<div class="tab-pane active" id="inboundoptions">
						<?php $callchoice = explode('#', $currentuser['calltypes_notes']);?>
						<?php $callchoice2 = explode('#', $currentuser['calltypes_notes2']);?>

						<form id="prodid1" name="prodid1">
						
						
						<div class="row-fluid col-wrap">
							<div  class="well span8 <?php echo $info_ui_color; ?>">
								<div class="well-header">
									<h5>Alternate Numbers and Additional Recipients</h5>
								</div>
								
								<div style="height:190px;" class="well-content no-search">
										<div class="form_row">
											<label class="field_name"><strong>Secondary Phone:</strong></label>
											<div class="span2">
												<input class="mask-phone" value="<?php echo $currentuser['secondphone']; ?>" name="secondphone--tosql_farm_incontact_info" type="text" AUTOCOMPLETE=ON>
											</div>
											
											<div class="span3 offset1">
											<label class="field_name" style="width:600px;"><strong> Voicemail Additional Recipients:</strong></label>
											</div>
											<div class="span3">
												<textarea class="autosize" name="vmemails--tosql_farm_incontact_info" cols="63" rows="4" style="resize: vertical; height:100px; width:200px;"><?php echo $currentuser['vmemails']; ?></textarea>
											</div>
											
										</div>
										<div class="form_row">
											<label class="field_name align_left"><strong>Route Back Number (Kept Private):</strong></label>
											<div class="field">
												<input class="mask-phone" id="rback" value="<?php echo $currentuser['rback']; ?>" name="rback--tosql_farm_incontact_info" type="text" AUTOCOMPLETE=ON>
											</div>
										</div>
																						
								</div>
							</div><!-- End of alt numbers-->
							
							<!-- additional recipients here -->
							
							
							<div class="well span4 <?php echo $info_ui_color; ?>">
								<div class="well-header">
									<h5>Line Forwarding</h5>
								</div>
							
								<div class="well-content no-search">						
									<div class="form_row added_fields prod_id_1">
										<label class="field_name" style="width:200px;"><strong>Forwarding Lines Capable?</strong></label>
										<div class="field">
											<label class="radio">
												<div class="radio"><input type="radio" class="uniform" name="forwardinglines--tosql_farm_agent_info" checked value="Yes"></div> Yes
											</label>
											<label class="radio">
												<div class="radio"><input type="radio" class="uniform" name="forwardinglines--tosql_farm_agent_info" value="No"></div> No
											</label>
											<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Do you currently have call forwarding capabilities?" title="Help" class="btn orange">?</a></span>
										</div>
									</div>	
								</div>	
							</div>
							
							
							<div class="well span4 <?php echo $info_ui_color; ?>">
								<div class="well-header">
									<h5>Inbound Option</h5>
								</div>
							
								<div class="well-content no-search">
									<label class="field_name" style="width:200px;"><strong>Inbound Option?</strong></label>														
									<div class="field">
										<label class="radio">
											<input type="radio" class="uniform inbound_option_update" name="inbound_option" value="agent" <?php if($currentuser["inbound_option"] == "agent"){ echo "checked";}?>/> Agent First
										</label>
										<label class="radio">
											<input type="radio" class="uniform inbound_option_update" name="inbound_option" value="csg" <?php if($currentuser["inbound_option"] == "csg"){ echo "checked";}?>/> CSG First
										</label>
									</div>
								</div>
							</div>
						
						</div>
						
						
						
						<div class="row-fluid">

							<div class="well span6 <?php echo $info_ui_color; ?>">
								<div class="well-header">
									<h5>Call Routing</h5>
								</div>
								<div class="well-content no-search"> 
								<table class="table table-striped table-bordered">
								   <thead>
									  <tr>
										<th>Call Types</th>
										 <th>Farmers</th>
										 <th>My Office</th>
										 
									  </tr>
								   </thead>
								   <tbody>
										<tr>
											<td>Cancellations</td>
											<td align="center"><input type="radio" class="uniform cn0 calltypes" name="cn0" value="0_seamless" <?php if($currentuser['calltypes_notes'] == NULL || in_array("0_seamless", $callchoice)){echo "checked";} ?>/></td>
											<td align="center"><input type="radio" class="uniform cn0 calltypes" name="cn0" value="0_office" <?php if(in_array("0_office", $callchoice)){echo "checked";} ?>/></td>
											
										</tr>
										<tr>
											<td>Coverage Changes</td>
											<td align="center"><input type="radio" class="uniform cn1 calltypes" name="cn1" value="1_seamless" <?php if($currentuser['calltypes_notes'] == NULL || in_array("1_seamless", $callchoice)){echo "checked";} ?>/></td>
											<td align="center"><input type="radio" class="uniform cn1 calltypes" name="cn1" value="1_office" <?php if(in_array("1_office", $callchoice)){echo "checked";} ?>/></td>
											
										</tr>
										<tr>
											<td>Claims</td>
											<td align="center"><input type="radio" class="uniform cn2 calltypes" name="cn2" value="2_seamless" <?php if($currentuser['calltypes_notes'] == NULL || in_array("2_seamless", $callchoice)){echo "checked";} ?>/></td>
											<td align="center"><input type="radio" class="uniform cn2 calltypes" name="cn2" value="2_office" <?php if(in_array("2_office", $callchoice)){echo "checked";} ?>/></td>
											
										</tr>
										<tr>
											<td>New Business</td>
											<td align="center"><input type="radio" class="uniform cn3 calltypes" name="cn3" value="3_seamless" <?php if($currentuser['calltypes_notes'] == NULL || in_array("3_seamless", $callchoice)){echo "checked";} ?>/></td>
											<td align="center"><input type="radio" class="uniform cn3 calltypes" name="cn3" value="3_office" <?php if(in_array("3_office", $callchoice)){echo "checked";} ?>/></td>
											
										</tr>
								   </tbody>
								</table>
								<!--<div class="form_row">
								<br>
								<a href="#" class="btn update_user_data btn-block" formid="prodid1"><i class="icon-share"></i> Update</a>
								</div> -->
								</div>
							</div>
							
							<div class="well span6 <?php echo $info_ui_color; ?>">
								<div class="well-header">
									<h5>Call Routing - Outside Business Hours</h5>
								</div>
								<div class="well-content no-search"> 
								<table class="table table-striped table-bordered">
								   <thead>
									  <tr>
										<th>Call Types</th>
										 <th>Farmers</th>
										 <th>My Office</th>
										 
									  </tr>
								   </thead>
								   <tbody>
										<tr>
											<td>Cancellations</td>
											<td align="center"><input type="radio" name="cnao0" class="cnao0 uniform calltypes" value="0_seamless" <?php if($currentuser['calltypes_notes2'] == NULL || in_array("0_seamless", $callchoice2)){echo "checked";} ?> /></td>
											<td align="center"><input type="radio" name="cnao0" class="cnao0 uniform calltypes" value="0_office" <?php if(in_array("0_office", $callchoice2)){echo "checked";} ?> /></td>
											
										</tr>
										<tr>
											<td>Coverage Changes</td>
											<td align="center"><input type="radio" name="cnao1" class="cnao1 uniform calltypes" value="1_seamless" <?php if($currentuser['calltypes_notes2'] == NULL || in_array("1_seamless", $callchoice2)){echo "checked";} ?> /></td>
											<td align="center"><input type="radio" name="cnao1" class="cnao1 uniform calltypes" value="1_office" <?php if(in_array("1_office", $callchoice2)){echo "checked";} ?> /></td>
											
										</tr>
										<tr>
											<td>Claims</td>
											<td align="center"><input type="radio" name="cnao2" class="cnao2 uniform calltypes" value="2_seamless" <?php if($currentuser['calltypes_notes2'] == NULL || in_array("2_seamless", $callchoice2)){echo "checked";} ?> /></td>
											<td align="center"><input type="radio" name="cnao2" class="cnao2 uniform calltypes" value="2_office" <?php if(in_array("2_office", $callchoice2)){echo "checked";} ?> /></td>
											
										</tr>
										<tr>
											<td>New Business</td>
											<td align="center"><input type="radio" name="cnao3" class="cnao3 uniform calltypes" value="3_seamless" <?php if($currentuser['calltypes_notes2'] == NULL || in_array("3_seamless", $callchoice2)){echo "checked";} ?> /></td>
											<td align="center"><input type="radio" name="cnao3" class="cnao3 uniform calltypes" value="3_office" <?php if(in_array("3_office", $callchoice2)){echo "checked";} ?> /></td>
											
										</tr>
								   </tbody>
								</table>
								<!--<div class="form_row">
								<br>
								<a href="#" class="btn update_user_data btn-block" formid="prodid1"><i class="icon-share"></i> Update</a>
								</div> -->
								</div>
							</div>
							
				
						</div>
						
									<div class="form_row">
								<br>
								<a href="#" class="btn update_user_data btn-block" formid="prodid1" users_id="<?php echo $currentuser['users_id'];?>"><i class="icon-share"></i> Update</a>
							</div>
						<input name="calltypes_notes--tosql_products_ext" type="text" id="calltypes" style="display: none;"/>
						<input name="calltypes_notes2--tosql_products_ext" type="text" id="calltypes2" style="display: none;"/>
						</form>
					
					</div> <!-- End of Inbound Options Tab -->					
				
					
				</div><!--End of Tab Content -->
			</div><!-- End of Well Content-->		
		</div><!-- End of Well declaration -->
	</div> <!-- End of span12 -->
</div> <!-- End of row-fluid -->

			</div> <!-- End of inbound tab-->

			
			<!----------------------------------------------------
			-
			- OUTBOUND TAB
			-
			----------------------------------------------------------->
			 <div class="tab-pane no_padding" id="aoutbound">
				
				<?php 
				
					$outbound_selected = explode("#", $currentuser['fast_products']);
					//var_dump($outbound_selected);
				
				if(in_array("2",$outbound_selected)){


				?>
		<!--------------------------------------- OUTBOUND SERVICE well---------------------------------------------->
				<div class="span5">
				<div class="well <?php echo $info_ui_color; ?>">
					<div class="well-header">
						<h5>FFR Outbound Services</h5>
					</div>

					<div class="well-content no-search"> 
						<!-- Form Begin -->
						<form id="prodid2">
						<div class="form_row added_fields prod_id_2 prod_id_3">
						<label class="field_name align_right">Set my appts with</label>
							<div class="field">
								<div class="span8">
							
									<select id="ffr_where_to_set_appts" name="where_to_set_appts--tosql_products_ext" type="text" > <!-- took out chosen class -->
										<option value="Agent" <?php if($currentuser['where_to_set_appts']=="Agent"){echo "selected";}?>>Myself</option>
										<?php for($staff = 1; $staff <= 6; $staff++){
													$current_staff_name = $currentuser["staffname$staff"];
										
												if($current_staff_name == ""){
													$nostaff =TRUE; 
												} else {
													$nostaff=FALSE;
												}?>
										<?php if(!$nostaff){ ?>
													<option value="<?php echo $current_staff_name; ?>" <?php if($currentuser['where_to_set_appts']== $current_staff_name){echo "selected";}?>><?php echo $current_staff_name; ?></option>
										<?php } ?>

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
									<input class="mask-digits" type="text" name="max_day_appts--tosql_products_ext" value="<?php echo $currentuser['max_day_appts']; ?>"/>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Please provide the max appointments you want in a day" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
						</div>
						<div class="form_row added_fields prod_id_2">
							<label class="field_name align_right">Max week appts</label>
							<div class="field">
								<div class="span8">
									<input class="mask-digits" type="text" name="max_week_appts--tosql_products_ext" value="<?php echo $currentuser['max_week_appts']; ?>"/>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Please provide the max appointments you want in a week" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
						</div>													
						<div class="form_row added_fields prod_id_2 prod_id_3">
							<label class="field_name align_right">Primary Appt. Preference</label>
							<div class="field">
								<div class="span8">
									<select name="primary_appt_pref--tosql_products_ext"><!-- took out class="chosen" -->
										<option></option>
										<option value="In Office" <?php if($currentuser['primary_appt_pref']=="In Office"){ echo "selected"; }?>>In Office</option>
										<option value="Home Visits" <?php if($currentuser['primary_appt_pref']=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
										<option value="Phone Appts" <?php if($currentuser['primary_appt_pref']=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
									</select>
								</div>

							</div>
						</div> 
						<div class="form_row added_fields prod_id_2 prod_id_3">
							<label class="field_name align_right">Secondary Appt. Preference</label>
							<div class="field">
								<div class="span8">
									<select name="secondary_appt_pref--tosql_products_ext"><!-- class=chosen gone -->
										<option></option>
										<option value="In Office" <?php if($currentuser['secondary_appt_pref']=="In Office"){ echo "selected"; }?>>In Office</option>
										<option value="Home Visits" <?php if($currentuser['secondary_appt_pref']=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
										<option value="Phone Appts" <?php if($currentuser['secondary_appt_pref']=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
									</select>
								</div>
								
							</div>
						</div> 
						<div class="form_row added_fields prod_id_2 prod_id_3">
							<label class="field_name align_right">Appt. length</label>
							<div class="field">
								<div class="span8">
									<select name="initial_phone_appt_length--tosql_products_ext"><!-- class=chosen gone -->
										<option></option>
										<option value="15 minutes" <?php if($currentuser['initial_phone_appt_length']=="15 minutes"){ echo "selected"; }?>>15 minutes</option>
										<option value="30 minutes" <?php if($currentuser['initial_phone_appt_length']=="30 minutes"){ echo "selected"; }?>>30 minutes</option>
										<option value="60 minutes" <?php if($currentuser['initial_phone_appt_length']=="60 minutes"){ echo "selected"; }?>>60 minutes</option>
									</select>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="What length of time would you like your appts set for?" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
						</div>
						<div class="form_row added_fields prod_id_2">
							<label class="field_name align_right">Appt. Seperation</label>
							<div class="field">
								<div class="span8">
									<select name="timeframe_between_appts--tosql_products_ext"> <!-- class=chosen gone -->
										<option></option>
										<option value="15 minutes" <?php if($currentuser['timeframe_between_appts']=="15 minutes"){ echo "selected"; }?>>15 minutes</option>
										<option value="30 minutes" <?php if($currentuser['timeframe_between_appts']=="30 minutes"){ echo "selected"; }?>>30 minutes</option>
										<option value="60 minutes" <?php if($currentuser['timeframe_between_appts']=="60 minutes"){ echo "selected"; }?>>60 minutes</option>
										<option value="90 minutes" <?php if($currentuser['timeframe_between_appts']=="90 minutes"){ echo "selected"; }?>>90 minutes</option>
										<option value="120 minutes" <?php if($currentuser['timeframe_between_appts']=="120 minutes"){ echo "selected"; }?>>120 minutes</option>
									</select>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="How long would you like in-between appts?" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
						</div>
						<div class="form_row added_fields prod_id_2">
							<label class="field_name align_right">Appt. Range</label>
							<div class="field">
								<div class="span8">
									<!-- chosen --><select class="" name="how_far_out_by_week--tosql_products_ext">
										<option></option>
										<option value="1 week out" <?php if($currentuser['how_far_out_by_week']=="1 week out"){ echo "selected"; }?>>1 week out</option>
										<option value="2 weeks out" <?php if($currentuser['how_far_out_by_week']=="2 weeks out"){ echo "selected"; }?>>2 weeks out</option>
										<option value="3 weeks out" <?php if($currentuser['how_far_out_by_week']=="3 weeks out"){ echo "selected"; }?>>3 weeks out</option>
									</select>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="How many weeks out do you want your appts scheduled?" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
							
						</div>
						<div class="form_row added_fields prod_id_2">
							<label class="field_name align_right">Travel Capable</label>
							<div class="field">
								<div class="span8">
									<!-- chosen --><select class="" name="travel_capable--tosql_products_ext">
										<option></option>
										<option value="Yes" <?php if($currentuser['travel_capable']=="Yes"){ echo "selected"; }?>>Yes</option>
										<option value="No" <?php if($currentuser['travel_capable']=="No"){ echo "selected"; }?>>No</option>
									</select>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Are you capable of traveling to your client?" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
							
						</div>

						<div class="form_row added_fields prod_id_2 prod_id_3">
							<label class="field_name align_right">Special Details</label>
							<div class="field">
								<div class="span8">
									<textarea class="autosize" name="special_details--tosql_products_ext" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $currentuser['special_details']; ?></textarea>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Do you have any special details we need to be aware of?" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
						</div>
						<div class="form_row added_fields prod_id_2">
							<label class="field_name align_right">Other Services</label>
							<div class="field">
								<div class="span8">
									 <textarea class="autosize" name="other_services_provided--tosql_products_ext" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $currentuser['other_services_provided']; ?></textarea>
									 <span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="What other services does your office provide?" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
						</div>
						<div class="form_row added_fields prod_id_2">
							<label class="field_name align_right">Allow Eprint?</label>
							<div class="field">
								<div class="span8">
									<!-- chosen --><select  name="send_eprint--tosql_products_ext">
										<option></option>
										<option value="Yes" <?php if($currentuser['send_eprint']=="Yes"){ echo "selected"; }?>>Yes</option>
										<option value="No" <?php if($currentuser['send_eprint']=="No"){ echo "selected"; }?>>No</option>
									</select>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="In the event there is incorrect contact info for one of your clients, would you like us to send an EPRINT letter on your behalf?" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
						</div>
						</form>
						<!-- Form End -->
						
						<div class="form_row">
						<br>
						<a href="#" class="btn update_user_data btn-block" formid="prodid2" users_id="<?php echo $currentuser['users_id'];?>" ><i class="icon-share"></i> Update</a>
						</div>
					</div>
				</div>

				</div><!-- Span 5 for outbound well -->

				<?php
				} //End of if statement for Outbound Services
				

					
					
					if(in_array("3",$outbound_selected)){ //Start of check for Alert Services
				?>

				<!--------------------------------------------
				-
				- ALERTS WELL
				-
				-
				---------------------------------------------->
				<div class="span5">
				<div class="well <?php echo $info_ui_color; ?>">
					<div class="well-header">
						<h5>Alerts Services</h5>
					</div>
					
					<div class="well-content no-search">
						<!-- Form Begin -->
						<form id="prodid3">
						<div class="form_row added_fields prod_id_2 prod_id_3">
						<label class="field_name align_right">Set my appts with</label>
							<div class="field">
								<div class="span8">
									<select id="ffr_where_to_set_appts" name="alerts_where_to_set_appts--tosql_products_ext" type="text" >
										<option value="Agent" <?php if($currentuser['alerts_where_to_set_appts']=="Agent"){echo "selected";}?>>Myself</option>
										<?php for($staff = 1; $staff <= 6; $staff++){
											$current_staffname = $currentuser["staffname$staff"];
											if($current_staffname == ""){ 
												$nostaff =TRUE; 
											} else {
												$nostaff=FALSE;
											}?>
											<?php if(!$nostaff){ ?>
												<option value="<?php echo $current_staffname; ?>" <?php if($currentuser['alerts_where_to_set_appts'] == $current_staffname){echo "selected";}?>><?php echo $current_staffname; ?></option>
											<?php } else ?>
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
									<select name="alerts_primary_appt_pref--tosql_products_ext"> <!-- class=chosen gone -->
										<option></option>
										<option value="In Office" <?php if($currentuser['alerts_primary_appt_pref']=="In Office"){ echo "selected"; }?>>In Office</option>
										<option value="Home Visits" <?php if($currentuser['alerts_primary_appt_pref']=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
										<option value="Phone Appts" <?php if($currentuser['alerts_primary_appt_pref']=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
									</select>
								</div>

							</div>
						</div> 
						<div class="form_row added_fields prod_id_2 prod_id_3">
							<label class="field_name align_right">Secondary Appt. Preference</label>
							<div class="field">
								<div class="span8">
									<select name="alerts_secondary_appt_pref--tosql_products_ext"><!-- class=chosen gone -->
										<option></option>
										<option value="In Office" <?php if($currentuser['alerts_secondary_appt_pref']=="In Office"){ echo "selected"; }?>>In Office</option>
										<option value="Home Visits" <?php if($currentuser['alerts_secondary_appt_pref']=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
										<option value="Phone Appts" <?php if($currentuser['alerts_secondary_appt_pref']=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
									</select>
								</div>
								
							</div>
						</div> 
						<div class="form_row added_fields prod_id_2 prod_id_3">
							<label class="field_name align_right">Appt. length</label>
							<div class="field">
								<div class="span8">
									<select name="alerts_initial_phone_appt_length--tosql_products_ext"> <!-- class=chosen gone -->
										<option></option>
										<option value="15 minutes" <?php if($currentuser['alerts_initial_phone_appt_length']=="15 minutes"){ echo "selected"; }?>>15 minutes</option>
										<option value="30 minutes" <?php if($currentuser['alerts_initial_phone_appt_length']=="30 minutes"){ echo "selected"; }?>>30 minutes</option>
										<option value="60 minutes" <?php if($currentuser['alerts_initial_phone_appt_length']=="60 minutes"){ echo "selected"; }?>>60 minutes</option>
									</select>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="What length of time would you like your appts set for?" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
						</div>

						<div class="form_row added_fields prod_id_2 prod_id_3">
							<label class="field_name align_right">Special Details</label>
							<div class="field">
								<div class="span8">
									<textarea class="autosize" name="alerts_special_details--tosql_products_ext" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $currentuser['alerts_special_details']; ?></textarea>
									<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Do you have any special details we need to be aware of?" title="Help" class="btn orange">?</a></span>
								</div>
								
							</div>
						</div>
						</form>
						<!-- Form End -->
						
						<div class="form_row">
						<br>
						<a href="#" class="btn btn-block update_user_data" formid="prodid3" users_id="<?php echo $currentuser['users_id'];?>"><i class="icon-share"></i> Update</a>
						</div>
					</div>
				</div>
				</div> <!-- Second span6 -->

				<?php
				} // End of if statement for Alert Services
				?>
			</div> <!-- end of outbound tab -->
			  
			  
			  
			  
			  
			
			<!----------------------------------------------------
			-
			- PRODUCTS TAB
			-
			----------------------------------------------------------->
			<div class="tab-pane no_padding" id="padmin">
					<div class="row-fluid">
						<div class="well <?php echo $info_ui_color; ?>">
							<div class="well-header">
								<h5>Products Signed Up</h5>
							</div>
							
							<div class="well-content">
								<?php 
								if(!empty($products_signedup)){ ?>
								<table class="table table-bordered">
										
											<tr>
												<th><center>ID</center></th>
												<th><center>Status</center></th>
												<th>Product Name</th>
												<th>Approve</th>
											</tr>				
									
							
								
									<?php foreach($products_signedup as $pall){ 
									if(in_array($pall['product_id'], $productids_appr)){ $p_status = "<span class='label label-success'>Approved</span>"; } else { $p_status = "<span class='label label-warning'>Not Approved</span>"; } ?>
									<tr><td><center><?php echo $pall['product_id']; ?></center></td>
										<td><center><?php echo $p_status; ?></center></td>
										<td><center><?php echo $pall['product_name'] ?></center></td>
										<?php if(in_array($pall['product_id'], $productids_appr)){ ?>
											<td><center><b>Already Approved</b></center></td>
										<?php } else {?>	
											<td><center><a href="#" id="app" class="btn btn-large green productupdate_ajax"  style="width:200px;" myaction="approve" doreload="1" prodid=" <?php echo $pall['product_id']; ?>">Approve</a></center></td>
										<?php } ?>
									</tr>
								<?php	} ?>
							
									</table>
								</div>
							</div>
					<?php }else{ ?>
						<h4><b><center>This User has yet to sign up for products</center></b></h4>
					<?php }?>
<!-----------------------------------------------------------------------------------------------------------------------------   
- ADDED
-AMBER ADDED 3/5/2014  
-ADDED
-
-
AMBER ADDED 3/5/2014 -------------------------------------------------->


					<?php if(!empty($products_other)){ ?>

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
													<select id="ffr_where_to_set_appts" name="where_to_set_appts--tosql_products_ext" type="text">
														<option value="Agent" >Myself</option>
														<?php for($staff = 1; $staff <= 6; $staff++){
																$current_staff_name = $currentuser["staffname$staff"];
																
																if($current_staff_name  == ""){
																	$nostaff =TRUE; 
																} else {
																	$nostaff=FALSE;
																}?>
														<?php if(!$nostaff){ ?><option value="<?php echo $current_staff_name ; ?>" <?php if($currentuser['where_to_set_appts']==$current_staff_name){echo "selected";}?>><?php echo $current_staff_name ; ?></option><?php } else ?>

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
													<input class="mask-digits" type="text" name="max_day_appts--tosql_products_ext" value="<?php echo $currentuser['max_day_appts']; ?>"/>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Please provide the max appointments you want in a day" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Max week appts</label>
											<div class="field">
												<div class="span8">
													<input class="mask-digits" type="text" name="max_week_appts--tosql_products_ext" value="<?php echo $currentuser['max_week_appts']; ?>"/>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Please provide the max appointments you want in a week" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>													
										<div class="form_row added_fields prod_id_2 prod_id_3">
											<label class="field_name align_right">Primary Appt. Preference</label>
											<div class="field">
												<div class="span8">
													<select name="primary_appt_pref--tosql_products_ext"><!-- chosen gone -->
														<option></option>
														<option value="In Office">In Office</option>
														<option value="Home Visits">Home Visits</option>
														<option value="Phone Appts">Phone Appts</option>
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
													<select name="secondary_appt_pref--tosql_products_ext"><!-- chosen gone -->
														<option></option>
														<option value="In Office" <?php if($currentuser['secondary_appt_pref']=="In Office"){ echo "selected"; }?>>In Office</option>
														<option value="Home Visits" <?php if($currentuser['secondary_appt_pref']=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
														<option value="Phone Appts" <?php if($currentuser['secondary_appt_pref']=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
													</select>
												</div>
												
											</div>
										</div> 
										<div class="form_row added_fields prod_id_2 prod_id_3">
											<label class="field_name align_right">Appt. length</label>
											<div class="field">
												<div class="span8">
													<select name="initial_phone_appt_length--tosql_products_ext"><!-- chosen gone -->
														<option></option>
														<option value="15 minutes" <?php if($currentuser['initial_phone_appt_length']=="15 minutes"){ echo "selected"; }?>>15 minutes</option>
														<option value="30 minutes" <?php if($currentuser['initial_phone_appt_length']=="30 minutes"){ echo "selected"; }?>>30 minutes</option>
														<option value="60 minutes" <?php if($currentuser['initial_phone_appt_length']=="60 minutes"){ echo "selected"; }?>>60 minutes</option>
													</select>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="What length of time would you like your appts set for?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Appt. Seperation</label>
											<div class="field">
												<div class="span8">
													<select  name="timeframe_between_appts--tosql_products_ext"><!-- chosen gone -->
														<option></option>
														<option value="15 minutes" <?php if($currentuser['timeframe_between_appts']=="15 minutes"){ echo "selected"; }?>>15 minutes</option>
														<option value="30 minutes" <?php if($currentuser['timeframe_between_appts']=="30 minutes"){ echo "selected"; }?>>30 minutes</option>
														<option value="60 minutes" <?php if($currentuser['timeframe_between_appts']=="60 minutes"){ echo "selected"; }?>>60 minutes</option>
														<option value="90 minutes" <?php if($currentuser['timeframe_between_appts']=="90 minutes"){ echo "selected"; }?>>90 minutes</option>
														<option value="120 minutes" <?php if($currentuser['timeframe_between_appts']=="120 minutes"){ echo "selected"; }?>>120 minutes</option>
													</select>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="How long would you like in-between appts?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Appt. Range</label>
											<div class="field">
												<div class="span8">
													<select name="how_far_out_by_week--tosql_products_ext"><!-- chosen gone -->
														<option></option>
														<option value="1 week out" <?php if($currentuser['how_far_out_by_week']=="1 week out"){ echo "selected"; }?>>1 week out</option>
														<option value="2 weeks out" <?php if($currentuser['how_far_out_by_week']=="2 weeks out"){ echo "selected"; }?>>2 weeks out</option>
														<option value="3 weeks out" <?php if($currentuser['how_far_out_by_week']=="3 weeks out"){ echo "selected"; }?>>3 weeks out</option>
													</select>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="How many weeks out do you want your appts scheduled?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
											
										</div>
										<div class="form_row added_fields prod_id_2 prod_id_3">
											<label class="field_name align_right">Special Details</label>
											<div class="field">
												<div class="span8">
													<textarea class="autosize" name="special_details--tosql_products_ext" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $currentuser['special_details']; ?></textarea>
													<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="Do you have any special details we need to be aware of?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Other Services</label>
											<div class="field">
												<div class="span8">
													 <textarea class="autosize" name="other_services_provided--tosql_products_ext" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $currentuser['other_services_provided']; ?></textarea>
													 <span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="What other services does your office provide?" title="Help" class="btn orange">?</a></span>
												</div>
												
											</div>
										</div>
										<div class="form_row added_fields prod_id_2">
											<label class="field_name align_right">Allow Eprint?</label>
											<div class="field">
												<div class="span8">
													<select name="send_eprint--tosql_products_ext"><!-- chosen gone -->
														<option></option>
														<option value="Yes" <?php if($currentuser['send_eprint']=="Yes"){ echo "selected"; }?>>Yes</option>
														<option value="No" <?php if($currentuser['send_eprint']=="No"){ echo "selected"; }?>>No</option>
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
																<select id="ffr_where_to_set_appts" name="alerts_where_to_set_appts--tosql_products_ext" type="text">
																	<option value="Agent" <?php if($currentuser['where_to_set_appts']=="Agent"){echo "selected";}?>>Myself</option>
																	<?php
																		for($staff = 1; $staff <= 6; $staff++){ 
																		$current_staffname = $currentuser["staffname$staff"];
																			
																			if($current_staffname == ""){ 
																				$nostaff =TRUE;
																			}else{
																				$nostaff=FALSE;
																			}
																			if(!$nostaff){ ?>
																				<option value="<?php echo $current_staffname; ?>" <?php if($currentuser['where_to_set_appts']==$current_staffname){echo "selected";}?>><?php echo $current_staffname; ?></option>
																		<?php } 
																		} 
																	?>					
																</select>
															<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="With whom should we set your appts with?" title="Help" class="btn orange">?</a></span>
															</div>
															
														</div>
													</div> 
																								
													<div class="form_row added_fields prod_id_2 prod_id_3">
														<label class="field_name align_right">Primary Appt. Preference</label>
														<div class="field">
															<div class="span8">
																<select  name="alerts_primary_appt_pref--tosql_products_ext">
																	<option></option>
																	<option value="In Office" <?php if($currentuser['alerts_primary_appt_pref']=="In Office"){ echo "selected"; }?>>In Office</option>
																	<option value="Home Visits" <?php if($currentuser['alerts_primary_appt_pref']=="Home Visits"){ echo "selected"; }?>>Home Visits</option>
																	<option value="Phone Appts" <?php if($currentuser['alerts_primary_appt_pref']=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
																</select>
															</div>

														</div>
													</div> 
													<div class="form_row added_fields prod_id_2 prod_id_3">
														<label class="field_name align_right">Secondary Appt. Preference</label>
														<div class="field">
															<div class="span8">
																<select name="alerts_secondary_appt_pref--tosql_products_ext">
																	<option></option>
																	<option value="In Office"  selected>In Office</option>
																	<option value="Home Visits" >Home Visits</option>
																	<option value="Phone Appts" <?php if($currentuser['alerts_secondary_appt_pref']=="Phone Appts"){ echo "selected"; }?>>Phone Appts</option>
																</select>
															</div>
															
														</div>
													</div> 
													<div class="form_row added_fields prod_id_2 prod_id_3">
														<label class="field_name align_right">Appt. length</label>
														<div class="field">
															<div class="span8">
																<select name="initial_phone_appt_length--tosql_products_ext">
																	<option></option>
																	<option value="15 minutes" <?php if($currentuser['alerts_initial_phone_appt_length']=="15 minutes"){ echo "selected"; }?>>15 minutes</option>
																	<option value="30 minutes" <?php if($currentuser['alerts_initial_phone_appt_length']=="30 minutes"){ echo "selected"; }?>>30 minutes</option>
																	<option value="60 minutes" <?php if($currentuser['alerts_initial_phone_appt_length']=="60 minutes"){ echo "selected"; }?>>60 minutes</option>
																</select>
																<span class="help"><a href="#" rel="popover" data-trigger="hover" data-placement="right" data-content="What length of time would you like your appts set for?" title="Help" class="btn orange">?</a></span>
															</div>
															
														</div>
													</div>

													<div class="form_row added_fields prod_id_2 prod_id_3">
														<label class="field_name align_right">Special Details</label>
														<div class="field">
															<div class="span8">
																<textarea class="autosize" name="alerts_special_details--tosql_products_ext" cols="63" rows="3" style="resize: vertical; height: 88px;"><?php echo $currentuser['alerts_special_details']; ?></textarea>
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
					
					
					
					
<!--3/5/2014-->		<?php }?> <!-------------------------------------- END OF AMBER ADDED ---------------------->
					

				</div><!-- end of row-fluid -->
			</div> <!-- end of products tab -->
			
			
			<!------------------------------------------------
			- 		ADMIN NOTES TAB
			-		
			--------------------------------------------------->
			<div class="tab-pane no_padding" id="adminnotes">
					
				<div class="row-fluid">
					<div class="well <?php echo $info_ui_color; ?>">
						<div class="well-header">
							<h5>Notes about <?php echo $currentuser['full_name']; ?></h5>
						</div>
						
						<div class="well-content"><!-- removed no-search -->
							<table class="table table-striped table-bordered table-hover datatable">
								<thead>
									<tr>
										<th>Note ID</th>
										<th>Admin Name</th>
										<th>Content</th>
										<th>Date</th>
									</tr>	
								</thead>
								<tbody>
									<?php
										//Select the notes from the db which correspond to this user's id
										
										$admin_notes_qry = "SELECT * FROM admin_notes WHERE users_id ='$uid'";
										$notes_result = $mysqli->query($admin_notes_qry);
										
										while($notes_row = mysqli_fetch_array($notes_result)){
											$notes_id = $notes_row['notes_id'];
											$notes_uid = $notes_row['users_id'];
											$notes_admin_id	= $notes_row['admin_id'];
											$notes_content = $notes_row['content'];
											$note_timestamp = $notes_row['note_timestamp'];
											
											//query the admin's full name based on their user_id
											$admin_name_qry = "SELECT user_full_name FROM users_ldap WHERE users_id='$notes_admin_id'";
											$admin_name_result = $mysqli->query($admin_name_qry);
											$admin_name_row = mysqli_fetch_array($admin_name_result);
											$admin_name = $admin_name_row['user_full_name'];
											echo"
											<tr>
												<td>$notes_id</td>
												<td>$admin_name</td>
												<td>$notes_content</td>
												<td>$note_timestamp</td>
											</tr>
											";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="well <?php echo $info_ui_color; ?>">
						<div class="well-header">
							<h5>Add a note about <?php echo $currentuser['full_name']; ?></h5>
						</div>
						
						<div class="well-content">
							<form id="admin_notes">
								<textarea class="textarea"  name="notes_content" placeholder="Enter text(200 chars max)..." style="width: 100%; height: 200px"></textarea>
							</form>
							<div class="form_row">
								<br><a href="#" class="btn btn-block add_admin_note" formid="admin_notes" dorefresh="1" admin_id="<?php echo $admin_id;?>"
								users_id="<?php echo $currentuser['users_id'];?>"><i class="icon-share"></i> Create</a>
							</div>
						</div>
					</div>
					
				</div>
						
			</div> <!-- end of admin notes tab -->
			
			
			<div class="tab-pane no_padding" id="loginhist">
				<div class="row-fluid">
					<div class="well <?php echo $info_ui_color; ?>">
						<div class="well-header">
							<h5>Login History of <?php echo $currentuser['full_name']; ?></h5>
						</div>
						
						<div class="well-content"><!-- removed no-search -->
							<table class="table table-striped table-bordered table-hover datatable">
								<thead>
									<tr>
										<th>Timestamp</th>
										<th>IP Address</th>
										<th>Browser Type</th>
										<th>Browser Version</th>
									</tr>	
								</thead>
								<tbody>
									<?php
										//Select the notes from the db which correspond to this user's id
										
										$login_qry = "SELECT * FROM login_tracker WHERE users_id ='$uid'";
										$login_result = $mysqli->query($login_qry);
										
										while($login_row = mysqli_fetch_array($login_result)){
											$login_timestamp = $login_row['login_timestamp'];
											$login_ip = $login_row['ip_address'];
											$login_browser_type = $login_row['browser_type'];
											$login_browser_version = $login_row['browser_version'];
											echo"
											<tr>
												<td>$login_timestamp</td>
												<td>$login_ip</td>
												<td>$login_browser_type</td>
												<td>$login_browser_version</td>
											</tr>
											";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>			
				</div>
			
			</div><!-- end of login history tab -->
			
		</div> <!-- end of tab content -->
	</div> <!-- end of span 9 -->
</div> <!-- end of row fluid -->
	
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery-ui-1.10.3.js"></script>
    <script src="js/bootstrap.js"></script>

    <script src="js/library/jquery.collapsible.min.js"></script>
    <script src="js/library/jquery.mCustomScrollbar.min.js"></script>
    <script src="js/library/jquery.mousewheel.min.js"></script>
    <script src="js/library/jquery.uniform.min.js"></script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCL6XtCGot7S7cfxnO6tRfeZx9kLQQRMtA&amp;sensor=false"></script>
    <script src="js/library/jquery.sparkline.min.js"></script>
    <script src="js/library/chosen.jquery.min.js"></script>
    <script src="js/library/jquery.easytabs.js"></script>
    <script src="js/library/flot/excanvas.min.js"></script>
    <script src="js/library/flot/jquery.flot.js"></script>
    <script src="js/library/flot/jquery.flot.pie.js"></script>
    <script src="js/library/flot/jquery.flot.selection.js"></script>
    <script src="js/library/flot/jquery.flot.resize.js"></script>
    <script src="js/library/flot/jquery.flot.orderBars.js"></script>
    <script src="js/library/maps/jquery.vmap.js"></script>
    <script src="js/library/maps/maps/jquery.vmap.world.js"></script>
    <script src="js/library/maps/data/jquery.vmap.sampledata.js"></script>
    <script src="js/library/jquery.autosize-min.js"></script>
    <script src="js/library/charCount.js"></script>
    <script src="js/library/jquery.minicolors.js"></script>
    <script src="js/library/jquery.tagsinput.js"></script>
    <script src="js/library/fullcalendar.min.js"></script>
    <script src="js/library/footable/footable.js"></script>
    <script src="js/library/footable/data-generator.js"></script>
	<script src="js/library/jquery.validate.js"></script>
	
    <script src="js/library/bootstrap-datetimepicker.js"></script>
    <script src="js/library/bootstrap-timepicker.js"></script>
    <script src="js/library/bootstrap-datepicker.js"></script>
    <script src="js/library/bootstrap-fileupload.js"></script>
	<script src="js/library/bootstrap-editable.js"></script>
	
	<script src="js/library/editor/wysihtml5-0.3.0.js"></script>
    <script src="js/library/editor/bootstrap-wysihtml5.js"></script>

    <script src="js/flatpoint_core.js"></script>
	<script src="js/fast_web_user_settings.js"></script>
	
	
	  <script src="js/library/jquery.inputmask.bundle.js"></script>
	<script src="js/library/bootstrap-select.js"></script>
	<script src="js/jquery.maskedinput.js"></script>
	<script src="js/home_page_admin.js"></script>
	
	<script src="js/library/bootstrap-modal.js"></script>
    <script src="js/library/bootstrap-modalmanager.js"></script>
	
	<script src="js/library/jquery.dataTables.js"></script>
    <script src="js/datatables.js"></script>
	<script src="js/forms_advanced.js"></script>
	<script src="js/library/bootstrapSwitch.js"></script>
	<script src="js/forms_layout.js"></script> 
	<script src="js/reg_wizard.js"></script>
	
	
	
	
<script>
function farm_user_delete(id){
	//alert("id="+id);
	
	$.ajax({
			type: 'POST',
			url: 'z_scripts/delete_farm_agent.php',
			data: "&users_id="+id,
			success: function(data, textStatus, jqXHR){
				alert(data);
				//location.reload();
				window.location = 'home_page_admin.php?ic=admin_farm_agent_table';
			}
		});
}
</script>	
	

<script>
$(".productupdate_ajax").click(function() {
    var action = $(this).attr('myaction');
	var prodid = $(this).attr('prodid');
    var url = "z_scripts/admin_farm_agent_product_update.php?type="+action+"_product"; // the script where you handle the form input.
    var doreload = $(this).attr('doreload');
	
    $.ajax({
           type: "POST",
           url: url,
           data: {uid: <?php echo $currentuser['users_id'] ; ?>, prodid: prodid}, // serializes the form's elements.
           success: function(data)
           {
               alert(data); // show response from the php script.
			   location.reload();
			   
           }
         });

    return false; // avoid to execute the actual submit of the form.
});

$(".login_update").click(function(){
	if(confirm("Are you sure you want to change this user's login status?")){
	
		var url = "z_scripts/admin_farm_agent_table_update.php?type=login_status";
		
		$.ajax({
		   type: "POST",
		   url: url,
		   data: $("#login_status").serialize(), // serializes the form's elements.
		   success: function(data)
		   {
			   alert(data); // show response from the php script.
			   //if(doreload=='1'){ location.reload(); }   
		   }
		});
		
		return false;
	}
	location.reload();
});
</script>

<!-- Added for admin notes --->
<script>
$(".add_admin_note").click(function(){  

	var formid = $(this).attr('formid');
	var users_id = $(this).attr('users_id');
	var admin_id = $(this).attr('admin_id');
	var dorefresh = $(this).attr('dorefresh');

	var updatebutton = this;
	var current_time = new Date().getTime()/1000;
	
	$(this).html("<img src='img/animated_loading.gif' style='height: 15px; margin-top: -4px;'/> Updating...");
	
	$.ajax({
			type: 'POST',
			url: 'z_scripts/admin_notes_update.php',
			data: $("#"+formid).serialize() + "&users_id=" + users_id + "&admin_id=" + admin_id,
			success: function(data, textStatus, jqXHR){
			    $(updatebutton).html("<i class='icon-ok-circle' style='color: green;'></i> Completed");
				
				setTimeout(function(){
				  $(updatebutton).html("<i class='icon-share'></i> Update?");
				  //$("#updatemsgs").fadeOut();
				}, 2000);

				alert(data);
			}
		});
		
		return false; // required to block normal submit since you used ajax	




}); 
</script>
<!-- end of admin notes -->

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

<!-- CSG Live Agent List (InContact API) -->
<script>
   $(document).ready(function() {
   
   var skillno = <?php echo $currentuser['skillno']; ?>;
   var page_choice = "incontact/admin_csg_agent_call_history_today.php?skillno="+skillno; 
	
  $("#todaycontainer").load(page_choice);
   $.ajaxSetup({ cache: false });
   });
</script>

<script>
   $(document).ready(function() {
  
	var skillno = <?php echo $currentuser['skillno']; ?>;
	var page_choice = "incontact/admin_csg_agent_call_history_7days.php?skillno="+skillno;
   
   $("#sevencontainer").load(page_choice);
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

<script>
//On email field update run check for duplicate user with same info
$('#changeemail').on('input focusout', function() {
	var email = $(this).val();
	if(typeof email != 'undefined'){
		$('#dup_email').html('<img style="height: 25px;" src="http://greatamericandisposal.com/wp-content/plugins/wp-homepage-slideshow/images/loading.gif"/>');
		$('#dup_email').load('z_scripts/do_check_dup_user.php?email='+email);
	}
});

var successalert = '<div class="alert alert-success"><i class="icon-ok-circle"></i>&nbsp;';
var erroralert = '<div class="alert alert-error"><i class="icon-warning-sign"></i>&nbsp;<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
var alertclose = '</div>';

//Password Update Response Codes: 
	var r000 = 'Broken response from Reset authentication';
	var r11 = 'Password has been updated, a notice has been sent to your email';
	var r12 = 'User does not exist';
	var r13 = 'Password does not meet these complexity requirements.  <br><br>* contain at least (1) upper case letter<br>* contain at least (1) lower case letter<br>* contain at least (1) number or special character<br>* contain at least (7) characters in length';
	var r14 = 'Current Password is not correct';

$("#changeyourpassword").click(function(){

	var suburl = "z_scripts/do_user_pwd_reset.php?type=ChangePW"; // the script where you handle the form input.
	
	var newpwd = $("#new_password").val();
	var conpwd = $("#confirm_password").val();
	var required = "New Password required.";
	var pwdmatch = "Passwords do not match.";
	
	if(newpwd == "")
	{
	  $('#modal_notify_msg').html(erroralert + required + alertclose).hide().html(erroralert + required + alertclose).fadeIn('slow');
	  return false;
	}
	
	if(newpwd != conpwd)
	{
	  $('#modal_notify_msg').html(erroralert + pwdmatch + alertclose).hide().html(erroralert + pwdmatch + alertclose).fadeIn('slow');
	  return false;
	}
	
	$.ajax({
		   type: "POST",
		   url: suburl,
		   data: $("#change_password_form").serialize(), // serializes the form's elements.
		   success: function(data)
		   {   
		      //alert(data);
			  switch(data)
			   {
			    case '11':
				   $('.update_pass').html('<a href="#" class="btn btn-block green" ><i class=icon-check></i> Success</a>');
				   //$('#notify_msg').html('');
				   $('#modal_notify_msg').html(successalert + r11 + alertclose).hide().html(successalert + r11 + alertclose).fadeIn('slow');
				   window.location.href="home_autoredirect.php";
				  break;
				case '12':
				   $('#modal_notify_msg').html(erroralert + r12 + alertclose).hide().html(erroralert + r12 + alertclose).fadeIn('slow');
				  break;
				case '13':
				   $('#modal_notify_msg').html(erroralert + r13 + alertclose).hide().html(erroralert + r13 + alertclose).fadeIn('slow');
				  break;
				case '14':
				   $('#modal_notify_msg').html(erroralert + r14 + alertclose).hide().html(erroralert + r14 + alertclose).fadeIn('slow');
				  break;
				default:
				   $('#modal_notify_msg').html(erroralert + r000 + alertclose).hide().html(erroralert + r000 + alertclose).fadeIn('slow');
				} 
			   
			  
		   }
		 });
	
	return false; // avoid to execute the actual submit of the form.
});
</script>


	
<script>
$(".inbound_option_update").click(function(){
	if(confirm("**NOTICE** This will refresh the page, leaving any other changes unsaved. Please allow a few moments for the change to complete.**")){
		var uid = <?php echo $uid; ?>;
		var option = $(this).attr('value');
		//alert(option);
		$.ajax({
			type: 'POST',
			url: 'z_scripts/iboption_toggle.php',
			data: "inbound_option="+option+"&users_id="+uid,
			success: function(data, textStatus, jqXHR){
				alert(data);
				location.reload();
			}
		});

	}
});
</script>

<script>
function farm_user_update_prod(id){
	
		if(id == 1){ //if the product is Inbound, no additional fields needed
		
			var currentprods = '<?php echo $currentuser['fast_products']; ?>';
			var cprodarry = currentprods.split('#');
			
			cprodarry.push(id);

			var newstring = cprodarry.toString();
			var readystring = newstring.replace(/\,/g, '#');
			var users_id = '<?php echo $currentuser['users_id']; ?>';

			//alert("The new String after " +id+" is added is " + readystring);
			
			//var sendData = $("#product_update1").serialize() + "&users_id=<?php echo $info_users_id; ?>&fast_products--tosql_farm_agent_info="+readystring;
			
			$.ajax({
				type: 'POST',
				url: 'z_scripts/admin_farm_user_agent_update.php?type=submit_product',
				data: $("#product_update1").serialize()+"&product_chosen="+id+"&users_id="+users_id+"&fast_products--tosql_farm_agent_info="+readystring,
				success: function(data, textStatus, jqXHR){
					//alert(data);
					location.reload();
				}
			});
			
		}else if(id == 2){ // else the product ID is Outbound and needs additional info
		
			var currentprods = '<?php echo $currentuser['fast_products']; ?>';
			var cprodarry = currentprods.split('#');
			
			cprodarry.push(id);

			var newstring = cprodarry.toString();
			var readystring = newstring.replace(/\,/g, '#');
			var users_id = '<?php echo $currentuser['users_id']; ?>';

			//alert("The new String after " +id+" is added is " + readystring);
			
			var sendData = $("#product_update2").serialize()+"&product_chosen="+id+"&users_id="+users_id+"&fast_products--tosql_farm_agent_info="+readystring;
			
			$.ajax({
				type: 'POST',
				url: 'z_scripts/admin_farm_user_agent_update.php?type=submit_product',
				data: sendData,
				success: function(data, textStatus, jqXHR){
					//alert(data);
					location.reload();
				}
			});
		
		}else{ //ID is equal to 3
		
			var currentprods = '<?php echo $currentuser['fast_products']; ?>';
			var cprodarry = currentprods.split('#');
			
			cprodarry.push(id);

			var newstring = cprodarry.toString();
			var readystring = newstring.replace(/\,/g, '#');
			var users_id = '<?php echo $currentuser['users_id']; ?>';

			//alert("The new String after " +id+" is added is " + readystring);
			
			//var sendData = $("#product_update3").serialize() + "&users_id=<?php echo $info_users_id; ?>&fast_products--tosql_farm_agent_info="+readystring;
			
			$.ajax({
				type: 'POST',
				url: 'z_scripts/admin_farm_user_agent_update.php?type=submit_product',
				data: $("#product_update3").serialize()+"&product_chosen="+id+"&users_id="+users_id+"&fast_products--tosql_farm_agent_info="+readystring,
				success: function(data, textStatus, jqXHR){
					//alert(data);
					location.reload();
				}
			});

		}	
}
</script>

		</div>
		
		   </div> 
	
  </body>
  
  
 <?php 
	include 'footer.php';
  ?>
</html>