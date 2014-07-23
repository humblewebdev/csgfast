<!DOCTYPE HTML>
<html>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<style type="text/css" title="currentStyle">
	@import "DataTables-1.9.i/media/css/demo_table_jui.css";
	@import "DataTables-1.9.i/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
	@import "DataTables-1.9.i/extras/ColReorder/media/css/ColReorder.css";
	@import "DataTables-1.9.i/extras/ColVis/media/css/ColVis.css";
	@import "DataTables-1.9.i/TableTools-2.1.5/css/TableTools.css";
	<!--@import "DataTables-1.9.i/media/css/TableTools_JUI.css";-->
</style>
<script type="text/javascript" language="javascript" src="DataTables-1.9.i/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="DataTables-1.9.i/extras/ColVis/media/js/ColVis.js"></script>
<script type="text/javascript" charset="utf-8" src="DataTables-1.9.i/extras/ColReorder/media/js/ColReorder.js"></script>
<script type="text/javascript" charset="utf-8" src="DataTables-1.9.i/TableTools-2.1.5/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="DataTables-1.9.i/TableTools-2.1.5/media/js/TableTools.js"></script>
<script type="text/javascript" src="DataTables-1.9.i/media/js/jquery.dataTables.editable.js"></script>

<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				
				oTable = $('#recs_table3').dataTable({
				    
		            "bJQueryUI": true,
					"sDom": 'R<"H"TlCfr>t<"F"ip>',
					"aoColumns": [
					{"bVisible": false},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": true},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					{"bVisible": false},
					],
					"oColVis": {"activate": "click",
					},
					
		            "sPaginationType": "full_numbers",
					"bPaginate": true,
					"aLengthMenu": [[5, 10, 15, 25, 50, 100 , -1], [5, 10, 15, 25, 50, 100, "All"]],
		            "iDisplayLength": 5,
					"aaSorting": [],
					"bStateSave": false,
					"bFilter": true,		
					"oTableTools": {
					"sSwfPath": "DataTables-1.9.i/TableTools-2.1.5/media/swf/copy_csv_xls_pdf.swf",
			        "aButtons": [
			        "print",
{			
"sExtends": "pdf",
"sButtonText": "Print PDF",
"mColumns": "visible"
},
{
"sExtends": "csv",
"sButtonText": "Export to Spreadsheet",
"mColumns": [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,30 ]
}


]

		}
					})
			} );
		</script>

<div class="container" style="padding: 15px;">
<div class="row">
<div class="span12">

<?php
include ("db_session.php");
$bucketype = $_GET['bt'];
echo "<table border='1' id='recs_table3' class='table-striped table-hover'>";
			  echo "<thead>";
			  echo "<th>Record ID</th>";
			  echo "<th>Phone Number</th>";
			  echo "<th>Name</th>";
			  echo "<th>Type</th>";
			  echo "<th>Assign Start Date</th>";
			  echo "<th>Agent Name</th>";
			  echo "<th>Agent ID</th>";
			  echo "<th>Agent Office Number</th>";
			  echo "<th>Type 2</th>";
			  echo "<th>Language Preference</th>";
			  echo "<th>Wireless?</th>";
			  echo "<th>Call Attempts</th>";
			  echo "<th>Appt. Set</th>";
			  
			  echo "<th>Disp 1</th>";
			  echo "<th>Notes 1</th>";
			  echo "<th>Rep 1</th>";
			  echo "<th>Time 1</th>";
			  
			  echo "<th>Disp 2</th>";
			  echo "<th>Notes 2</th>";
			  echo "<th>Rep 2</th>";
			  echo "<th>Time 2</th>";
			  
			  echo "<th>Disp 3</th>";
			  echo "<th>Notes 3</th>";
			  echo "<th>Rep 3</th>";
			  echo "<th>Time 3</th>";
			  
			  echo "<th>Disp 4</th>";
			  echo "<th>Notes 4</th>";
			  echo "<th>Rep 4</th>";
			  echo "<th>Time 4</th>";
              
			  echo "<th>All Disp.</th>";
			  echo "<th>Side Notes</th>";
			  echo "</thead>";
			  echo "<tbody>";
if($bucketype == ""){
$bucketlist = mysql_query("select * from dialer_list") or die(mysql_error());
} else {
$bucketlist = mysql_query("select * from dialer_list WHERE type='$bucketype'") or die(mysql_error());
}
while ($bl = mysql_fetch_array($bucketlist)) {
			 $bucketid = $bl['id'];
			 $disptypes_arr = array("fa01","fa02","fa03","fa05","fa06","fa07","fa09","fa12","fr01","fr02","fr03","fr04","fr05","fr08");
// add in fa12 to array -amber
			 $disptypes_arr_c = array("fr01");
			 $disptypes = '"' . implode('","', $disptypes_arr) . '"';
			 $disptypes_c = '"' . implode('","', $disptypes_arr_c) . '"';
			 
			  $bucket_link_list = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
			  (link_unix_timestamp>'$twentyfourbefore' OR
			  SUBSTRING(link_disp_type, 1, 4) IN ($disptypes)) AND
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='1'");
			  
			  $count_true_worked = mysql_num_rows($bucket_link_list); 
			  
			  if($count_true_worked > 0){ $w_count++; };
			  
			  $bucket_link_list_completed = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
              SUBSTRING(link_disp_type, 1, 4) IN ($disptypes) AND
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='1'");
			  
			  $count_true_completed = mysql_num_rows($bucket_link_list_completed); 
			  
			  if($count_true_completed > 0){ $c_count++; };
			  
			  $bucket_link_list_att = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='1'");
			  
			  $bucket_link_list_att2 = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='1'");
			  
			  $count_true_att = mysql_num_rows($bucket_link_list_att);

			  $bucket_link_list_notes = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='0' AND
			  link_notes <> 'NULL'");			  
			  
			  if($count_true_att > 0){ $att_count++; };
			  
			  $bucket_link_list_appts = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
              SUBSTRING(link_disp_type, 1, 4) IN ($disptypes_c) AND
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='1'");
			  
			  $count_true_appts = mysql_num_rows($bucket_link_list_appts); 
			  
			  if($count_true_appts > 0){ $appt_count++; };
			  
			  
			  echo "<tr><td>{$bl['id']}</td>";
			  echo "<td>{$bl['phone_number']}</td>";
			  echo "<td>{$bl['name']}</td>";
			  echo "<td>{$bl['type']}</td>";
			  echo "<td>{$bl['assign_start_date']}</td>";
			  echo "<td>{$bl['agent_name']}</td>";
			  echo "<td>{$bl['agent_id']}</td>";
			  echo "<td>{$bl['agent_office_number']}</td>";
			  echo "<td>{$bl['type_2']}</td>";
			  echo "<td>{$bl['language_preference']}</td>";
			  echo "<td>{$bl['wireless']}</td>";
			  echo "<td> $count_true_att </td>";
			  echo "<td>"; if($count_true_appts==1){echo "Y";}else{echo "N";} echo "</td>";
			  for($i=0; $i<=3; $i++){
			  $disp = mysql_fetch_array($bucket_link_list_att);
			  echo" 
			        
			        <td>{$disp['link_disp_type']}</td>
			        <td>{$disp['link_notes']}</td>
					<td>{$disp['link_action_by']}</td>
					<td>{$disp['link_timestamp']}</td>
					";
			  }
			  echo "<td>";
			  while($disp2 = mysql_fetch_array($bucket_link_list_att2)){
			  
			  echo" 
			        
			        <strong>Disp:</strong> {$disp2['link_disp_type']}<br>
			        <strong>Notes:</strong> {$disp2['link_notes']}<br>
					<strong>Agent:</strong> {$disp2['link_action_by']}<br>
					<strong>Time:</strong> {$disp2['link_timestamp']}<br><br>
					";
			  }
			  
			  echo "</td><td>";
			  while($note = mysql_fetch_array($bucket_link_list_notes)){
			  
			  echo" 
			        <strong>Side Note:</strong> {$note['link_notes']}, <br>
					<strong>Agent:</strong> {$note['link_action_by']}, <br>
					<strong>Time:</strong> {$note['link_timestamp']} <br><br>";
			  }
			  echo "</td>";

		   }
		   echo "<tbody>";
           echo "<table>";
		   $t_count = mysql_num_rows($bucket_list); //Total Count
		   $r_count = ($t_count - $c_count); //Remaining after completed
	       $rt_count = ($t_count - $w_count); //Remaining today after worked 

?>
</div>
</div>
</div>
<html>