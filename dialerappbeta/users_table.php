<?php
include ("db_session.php");

?>
<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" language="javascript" src="DataTables-1.9.4/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/DT_bootstrap.js"></script>
<script>
$('.mentog').show();
</script>
</head>
			
			<div class="span12" style="margin-top: 10px" class="table-responsive">
			<div class="text-center"><h4>User List</h4></div>
<table id="recs_table2" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover">
			<thead>
			<tr>
			<th>User</th>
			<th>Full Name</th>
			<th>Current Record</th>
			<th>Current Bucket</th>
			<th>Last Activity</th>
			</tr>
			</thead>

			<tbody>
			<?php
			$rs_results2 = mysql_query("select * from dialer_list_user_profiles") or die(mysql_error());
			
			while ($record2 = mysql_fetch_array($rs_results2)) {

			$username2 = $record2['user_un'];
            $fullname2 = $record2['user_full_name'];
			$currec2 = $record2['user_current_record'];
			$curbuc2 = ucwords($record2['user_current_bucket']);
			$lastact2 = $record2['user_last_activity'];

			?>
			<tr>

			<td>
			<div class="btn-group">
			  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				<?php echo $username2; ?>
				<span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu">
			       <li class="dropdown-submenu">
					<a tabindex="-1" href="#">Assign Bucket</a>
					<ul class="dropdown-menu">
					 <?php 
					        $bucket_results = mysql_query("SELECT DISTINCT(Type) AS type from dialer_list") or die(mysql_error());
                            while ($bucket_rec = mysql_fetch_assoc($bucket_results)) { 
	                        $bucketype = $bucket_rec["type"];
					 ?>
					  <li><a href="#" onclick="admin_assign_bucket('<?php echo $bucketype; ?>','<?php echo $username2; ?>'); newpage('utable','users_table', 'mp5');"><?php echo $bucketype; ?></a></li>
					 <?php } ?>
					</ul>
				  </li>
				  <?php if($curbuc2 != 'None'){ ?>
                  <li><a href="#" onclick="admin_assign_bucket('none','<?php echo $username2; ?>'); newpage('utable','users_table', 'mp5');">Un-Assign Bucket</a></li>
				  <?php } ?>
				  <?php if($currec2 != 0){ ?>
                  <li><a href="#" onclick="admin_assign_bucket_rec('<?php echo $username2; ?>','0'); newpage('utable','users_table', 'mp5');">Un-Assign Current Record</a></li>
				  <?php } ?>
                  
                </ul>
			</div>
			</td>
			<td><?php echo $fullname2; ?></td>
			<td <?php if($currec2 != 0){?>onclick="recordshow('<?php echo $currec2; ?>')" style="cursor: pointer; background-color: #B4EEB4;"<?php } ?>>
			   
			   <?php echo formatPhone($currec2); ?>
				
			</td>
			<td><?php echo $curbuc2;?></td>
			<td><?php echo $lastact2; ?></td>
			</tr>
			<?php
			
			}
			?>
			</tbody>
			</table>
			</div>
<html>