<?php 
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
error_reporting(0);

/*----------------------*/
if(isset($_GET['tfilter_field'])){
	$filterfield = $_GET['tfilter_field'];
}
if(isset($_GET['tfilter_value'])){
	$filtervalue = $_GET['tfilter_value']; 
}

if(isset($filterfield) == NULL || $filtervalue == NULL){
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
							  ON t.users_id = t5.users_id WHERE user_level='1';");
} else {

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
							  ON t.users_id = t5.users_id WHERE user_level='1' AND $filterfield='$filtervalue';");

}
$allfarmers = array(); // Associative array of all farmers users
$idfarm = 1;

while ($finfo = $search_farm_users->fetch_assoc()) {
  $allfarmers[$idfarm] = $finfo; 
  $idfarm++;
}

/* Grab All products from Table */
$qproducts_all = $mysqli->query("SELECT * FROM fast_products;") or die($mysqli->error);
$products_all = array(); //Associative array of signed up items
$idpall = 1;

while ($pall = $qproducts_all->fetch_assoc()) {
  $products_all[$idpall] = $pall; 
  $idpall++;
}
?>
<div class="report-widgets hidden-480 hidden-768">
	<div class="row-fluid">
		<div class="span4">
			<div class="widget blue clearfix">
				<div class="content">
					<div class="icon">
						<i class="icon-user"></i>
						Total Farmers Agents
					</div>
					<div class="value">
						<?php echo count($allfarmers); ?>
					</div>
				</div>
				<a href="home_page_admin.php?ic=admin_farm_agent_table&tfilter_field=&tfilter_value=" class="more"><i class="icon-arrow-right"></i></a>
			</div>
		</div>
		<div class="span4">
			<div class="widget yellow clearfix">
				<div class="content">
					<div class="icon">
						<i class="icon-time"></i>
						Users Pending Login Approval
					</div>
					<div class="value">
						<?php 
						$pfiltered = array_filter($allfarmers, function($v1) { return $v1['approved'] == '0'; });
						echo count($pfiltered);
						?>
					</div>
				</div>
				<a href="home_page_admin.php?ic=admin_farm_agent_table&tfilter_field=approved&tfilter_value=0" class="more"><i class="icon-arrow-right"></i></a>
			</div>
		</div>
		<div class="span4">
			<div class="widget orange clearfix">
				<div class="content">
					<div class="icon">
						<i class="icon-warning-sign"></i>
						Locked Users
					</div>
					<div class="value">
						<?php 
						$lfiltered = array_filter($allfarmers, function($v) { return $v['banned'] == '1'; });
						echo count($lfiltered);
						?>
					</div>
				</div>
				<a href="home_page_admin.php?ic=admin_farm_agent_table&tfilter_field=banned&tfilter_value=1" class="more"><i class="icon-arrow-right"></i></a>
			</div>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Complete Farmers User List</h5>
				<ul>
                <li><a href="#" class="refreshthis"><i class="icon-rotate-right"></i></a></li>                
			</div>
            <form name="bulk_update" id="bulk_update" method="post">
			<div class="well-content no-search">
				<table class="table table-striped table-bordered table-hover datatable">
					<thead>
						<tr>
						    <th>ID</th>
							<th>Edit</th>
							<th>Approved Products</th>
							<th>Pending Products</th>
							<th>Reg. Date</th>
							<th>Login Status</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($allfarmers as $farmuser){ ?>
							<tr>
								<!---------------- ID ---------------->
							    <td><?php echo $farmuser['users_id']; ?></td>
							    
								<!---------------- Edit ---------------->
								<td><a href="admin_farm_agent_editor.php?uid=<?php echo $farmuser['users_id']; ?>" data-keyboard="false" agentname="<?php echo $farmuser['firstname'] . " " . $farmuser['lastname']; ?>"><img style="width: 40px;" src="<?php echo $farmuser['profile_pic']; ?>" /></a>
								<a href="#" rel="popover" data-placement="right" 
															  data-trigger="hover" 
															  data-content="<div>
																			<strong>Main Phone: </strong><?php echo formatPhone($farmuser['mainphone']); ?>
																			<br><strong>Second Line: </strong><?php echo formatPhone($farmuser['secondphone']); ?>
																			<br><strong>Route Back: </strong><?php echo formatPhone($farmuser['rback']); ?>
																			<br><strong>Cell Phone: </strong><?php echo formatPhone($farmuser['cellphone']); ?>
																			<br><strong>Email: </strong><?php echo $farmuser['email']; ?>
																			<br><strong>POC: </strong><?php echo formatPhone($farmuser['poc']); ?>
																			<br><strong>Agent ID: </strong><?php echo formatPhone($farmuser['agent_code']); ?>
																			
																			<br>
																			</div>" 
															  data-html="true" title="" 
															  data-original-title="<?php echo $farmuser['firstname'] . " " . $farmuser['lastname']; ?> Contact Info">
															  <?php echo $farmuser['firstname'] . " " . $farmuser['lastname']; ?>
									</a>
								</td>
								
								<!---------------- Approved Products ---------------->
								<td><?php 
										  $productids_appr = explode("#", $farmuser['fast_products_approved']); 
										  if($farmuser['fast_products_approved'] != "") {
											//var_dump($productids_appr);
											foreach($productids_appr as $app_prod) { 
												if($app_prod != ""){
												echo "<span class='label label-success'>" . $products_all[$app_prod]['product_name'] /*$info_user_full_name*/ . "</span>&nbsp;&nbsp;";
												}
											} 
										  }
									?>
								</td>
								
								<!---------------- Pending Products ---------------->
								<td><?php 
								          $productids_want = explode("#", $farmuser['fast_products']); 
								          $productids_pen = array_diff($productids_want, $productids_appr); //Grab the difference of the arrays to determine pending approvals
								          if($farmuser['fast_products'] != "") {
										  foreach($productids_pen as $pen_prod) { echo "<span class='label label-warning'>" . $products_all[$pen_prod]['product_name']. "</span>&nbsp;&nbsp;";}
										  }
									?>
								</td>
								
								<!---------------- Reg. Date ---------------->
								<td><?php echo $farmuser['reg_date']; ?></td>
								
								<!---------------- Login Status ---------------->
								<td><?php if($farmuser['approved'] == '0' && $farmuser['banned'] != '1'){echo "<span class='label label-warning'>Pending.."; } 
								     else if($farmuser['approved'] == '1' && $farmuser['banned'] != '1'){echo "<span class='label label-success'>Approved"; } 
									 else if($farmuser['banned'] == '1'){echo "<span class='label label-alert'>Locked"; } ?></span></td>
							    <td><input type="checkbox" value="<?php echo $farmuser['users_id']; ?>" class="uniform" name="bulk_action_id[]"/></td>
							</tr>
						<?php } ?> <!-------- end of user loop -------->
					</tbody>
				</table>
				<div class="dropdown">
					<a href="#" data-toggle="dropdown" class="btn"><i class="icon-check"></i> Bulk Action<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class="bulkupdate_ajax" myaction="approve" doreload="1"><a href="#" >Approve User for Login</a></li>
						<li class="bulkupdate_ajax" myaction="lock" doreload="1"><a href="#" >Lock User</a></li>
					</ul>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Popup Modal Start -->
	
	<div id="uniqueUser" class="modal container hide fade" style="width:900px;" tabindex="-1">
        <div class="modal-header">
            <button type="button" class="close emptymodal" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
            <h3>Editing User (<a class="editinguser"></a>)</h3>
        </div>
        <div class="modal-body" style="height: 700px;" id="thisUser">
            
        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal" aria-hidden="true" class="btn grey emptymodal">Close</a>
            <!--<a href="#" class="btn green">Save changes</a>-->
        </div>
    </div>
	
<!-- Popup Modal End -->

<!-- Script to load in agent editing tools into modal -->
<script src="js/jquery-1.10.2.js"></script>
<script>
$("a[data-toggle=modal]").click(function() {
  $('#thisUser').html("<center><img src='img/loading_bar_dots.gif'/></center>");
  var target, url, agentname;
  target = $(this).attr('data-target-base');
  url = $(this).attr('href');
  agentname = $(this).attr('agentname');
  
  $('.editinguser').text(agentname);
  
  return $(target).load(url);
  
});

$(".emptymodal").click(function() {
	$('#thisUser').empty();
	
});

$(".bulkupdate_ajax").click(function() {
    var action = $(this).attr('myaction');
    var url = "z_scripts/admin_farm_agent_table_update.php?type=bulk_update_"+action; // the script where you handle the form input.
    var doreload = $(this).attr('doreload');
	
    $.ajax({
           type: "POST",
           url: url,
           data: $("#bulk_update").serialize(), // serializes the form's elements.
           success: function(data)
           {
               alert(data); // show response from the php script.
			   if(doreload=='1'){ location.reload(); }
			   
           }
         });

    return false; // avoid to execute the actual submit of the form.
});

$(".refreshthis").click(function() {
	location.reload();
});
</script>    

<script>
$(function ()  
{ $("#example").popover();  
});  
</script>           