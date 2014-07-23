<!DOCTYPE html>
<?php
error_reporting(0);
include 'z_scripts/db_connect.php';
//page_protect();
checkAdmin("logout");

/**** Set all PHP Variables as $info_(fieldname) for the info of the logged in user **/
include 'z_scripts/set_php_info_vars.php'; 

$innercontent = $_GET['ic'];
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
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
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
					<li <?php if($innercontent == "admin_users"){ echo 'class="active"'; }?>><a href="home_page_admin.php?ic=admin_users"><i class="icon-user"></i>LDAP Users</a></li>
					<li <?php if($innercontent == "admin_products"){ echo 'class="active"'; }?>><a href="home_page_admin.php?ic=admin_products"><i class="icon-shopping-cart"></i>Products</a></li>
				<?php } ?>
				<li <?php if($innercontent == "admin_farm_agent_table"){ echo 'class="active"'; }?>><a href="home_page_admin.php?ic=admin_farm_agent_table"><i class="icon-headphones"></i>Agents</a></li>
				<li <?php if($innercontent == "admin_email"){ echo 'class="active"'; }?>><a href="home_page_admin.php?ic=admin_email"><i class="icon-envelope"></i>Email</a></li>
				<li <?php if($innercontent == "admin_audit_trail"){ echo 'class="active"'; }?>><a href="home_page_admin.php?ic=admin_audit_trail"><i class="icon-reorder"></i>Audit Trail</a></li>
				<li <?php if($innercontent == "admin_prospects"){ echo 'class="active"'; }?>><a href="home_page_admin.php?ic=admin_prospects"><i class="icon-group"></i>Prospective Users</a></li>
			</ul>
        </div>
    </div>  

    <div id="content" class="no-sidebar"> <!-- Content start -->
      <!--  <div class="top_bar">
            <ul class="breadcrumb">
              <li><a href="dashboard.html"><i class="icon-home"></i></a> <span class="divider">/</span></li>
              <li class="active"><a href="#">Dashboard</a></li>
            </ul>
        </div> -->

            <div class="widgets_area">
			<input id="hidden_users_id" value="<?php echo $info_users_id; ?>" style="display: none;"> <!-- Do not remove!! User id that is used in user updates.-->
			<div id="updatemsgs"></div>
				<?php if($innercontent != NULL){include $innercontent . ".php";} else{ include "admin_farm_agent_table.php";}?>
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
	<script src="js/home_page_admin.js"></script>
	
	<script src="js/library/bootstrap-modal.js"></script>
    <script src="js/library/bootstrap-modalmanager.js"></script>
	
	<script src="js/library/jquery.dataTables.js"></script>
    <script src="js/datatables.js"></script>
	<script src="js/forms_advanced.js"></script>
	<script src="js/library/bootstrapSwitch.js"></script>
	
	
  </body>
  
 <?php 
	include 'footer.php';
  ?>
</html>
