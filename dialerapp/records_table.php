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
<div class="text-center"><h4>Active Records List</h4></div>
<table id="recs_table" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover">
			<thead>
			<tr>
			<th>Phone Number</th>
			<th>Name</th>
			<th>Type</th>
			<th>Start Calling After</th>
			<th>Attempts</th>
			<th>Timestamp</th>
			</tr>
			</thead>

			<tbody>
			<?php
			$rs_results = mysql_query("select * from dialer_list") or die(mysql_error());
			
			while ($record = mysql_fetch_array($rs_results)) {

			$recordid = $record['id'];
			
			$search_attempts = mysql_query("SELECT * FROM dialer_list_record_link WHERE link_record_id='$recordid' AND link_call_attempt='1'");
			
			$a_count = mysql_num_rows($search_attempts); 
			
			$name = ucwords($record['name']);
            $phone = $record['phone_number'];
			$type = ucwords($record['type']);
			$asd = $record['assign_start_date'];
			$timestamp = $record['post_timestamp'];

			?>
			<tr onclick="recordshow('<?php echo $recordid; ?>')" style="cursor: pointer;">
			<td><?php echo formatPhone($phone); ?><a style="display: none"><?php echo $phone ?></a></td>
			<td><?php echo $name; ?></td>
			<td><?php echo $type; ?></td>
			<td><?php echo $asd; ?></td>
			<td><?php echo $a_count;?></td>
			<td><?php echo $timestamp; ?></td>
			</tr>
			<?php
			
			}
			?>
			</tbody>
			</table>
			</div>
			

<html>