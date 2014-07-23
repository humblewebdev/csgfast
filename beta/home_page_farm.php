<?php

include 'z_scripts/db_connect.php';
page_protect();
checkFarmUser("logout");

/**** Set all PHP Variables as $info_(fieldname) for the info of the logged in user **/
include 'z_scripts/set_php_info_vars.php'; 

if(isset($_GET['ic'])){
	$innercontent = $_GET['ic'];
}
//$menucontent = $_GET['menu'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CSG FAST - Farmers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/stylesheet.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="img/favicon.png">
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>

    <header class="<?php echo $info_ui_color; ?>"> <!-- Header start -->
        <a href="#" class="logo_image"><span class="hidden-480">CSG FAST</span></a>
        <ul class="header_actions" align="left">
            <li><a href="home_page_farm.php?ic=farm_profile"><img src="profile_pics/<?php echo $info_profile_pic; ?>" onError="this.src='http://farmersagent.com/Images/FarmersLogo_placements.jpg';" alt="User image" class="avatar"> <?php echo "Welcome, " . $info_full_name;?></a>
            </li>
            <li><a href="z_scripts/do_logout.php"><i class="icon-signout"></i> <span class="hidden-768 hidden-480">Logout</span></a></li>
            <li class="responsive_menu"><a class="iconic" href="#"><i class="icon-reorder"></i></a></li>
        </ul>
    </header>

    <div id="main_navigation" class="<?php echo $info_ui_color; ?>"> <!-- Main navigation start -->
        <div class="inner_navigation">
            <ul class="main">
                <li <?php if(isset($innercontent) && $innercontent == "farm_profile"){ echo 'class="active"'; }?>><a href="home_page_farm.php?ic=farm_profile"><i class="icon-home"></i>Agency Profile</a></li>
				
				<?php 
				/*
				*
				*grab the products wanted (both pending and approved from the fast_products column under the farm_agent_info table
				*
				*/
				$products_wanted = explode("#", $info_fast_products);	
				
				foreach($products_wanted as $inbound_search){ //search through products list for the corrent product number. (1)
					if($inbound_search == 1){ ?>
					<li <?php if(isset($innercontent) && $innercontent == "farm_inbound"){echo 'class="active"';}?>><a href="home_page_farm.php?ic=farm_inbound"><i class="icon-arrow-right"></i>Inbound Services</a></li>
						
				<?php	
					break;//break if inbound found
				}
				} // end of inbound products foreach
				
				foreach($products_wanted as $outbound_search){ //outbound counts as alerts (2 and 3)
					if($outbound_search == 2 || $outbound_search == 3){ ?>
				<li <?php if( isset($innercontent) && $innercontent == "farm_outbound"){echo 'class="active"';}?>><a href="home_page_farm.php?ic=farm_outbound"><i class="icon-arrow-left"></i>Outbound Services</a></li>
			<?php	
					break; //break if outbound is found
					}
 
				}// end of outbound products foreach
				?>
				
				<li <?php if(isset($innercontent) && $innercontent == "farm_products"){ echo 'class="active"'; }?>><a href="home_page_farm.php?ic=farm_products"><i class="icon-shopping-cart"></i> Products</a></li>
			<li><a href="#csg-view" data-toggle="modal"><i class="icon-user"></i>Contact Us</a></li>
            </ul>
        </div>
    </div>  
	
	<!-- Popup Modal Start -->
	
	<div id="csg-view" class="modal container hide fade" tabindex="-1">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
            <h3>Contact Us</h3>
        </div>
        <div class="modal-body">
            <?php include 'contact_us.php'; ?>
        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal" aria-hidden="true" class="btn grey">Close</a>
            <!--<a href="#" class="btn red">Save changes</a>-->
        </div>
    </div>
	
	<!-- Popup Modal End -->

    <div id="content" class="no-sidebar"> <!-- Content start -->
        <div class="top_bar">
            <ul class="breadcrumb">
              <li><a href="home_page_farm.php?ic=farm_profile"><i class="icon-home"></i></a> <span class="divider">/</span></li>
              <li class="active"><a href="#">Profile</a></li>
            </ul>
        </div>

			<!-- 
			*
			* This area is where the "inner content" is decided. whatever the get grabs from IC,
			* that name is appended with ".php
			*
			*
			-->
            <div class="widgets_area">
			<input id="hidden_users_id" value="<?php echo $info_users_id; ?>" style="display: none;"> <!-- Do not remove!! User id that is used in user updates.-->
			<div id="updatemsgs"></div>
				<?php if(isset($innercontent) && $innercontent != NULL){include $innercontent . ".php";} else{include "farm_profile.php";}?>
            </div> 
			
			
			
			
		
    </div>

	
  

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

	<script src="js/jquery.maskedinput.js"></script>
	<script src="js/home_page_farm.js"></script>
	
	<script src="js/library/bootstrap-modal.js"></script>
    <script src="js/library/bootstrap-modalmanager.js"></script>
	<script src="js/forms_advanced.js"></script>
	<script src="js/library/bootstrapSwitch.js"></script>
	
	   <script>
        jQuery(document).ready(function($) {
            // pass in your custom templates on init
            $('.textarea').wysihtml5();
            $('.uniform').uniform();

            $('.chosen').chosen();
        });
    </script>

	
	
  </body>
  
  <?php 
	include 'footer.php';
  ?>
</html>
