<?php include 'dbc.php'; ?>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<style type="text/css" title="currentStyle">
	@import "DataTables-1.9.4/media/css/demo_table_jui.css";
	@import "DataTables-1.9.4/examples/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
	@import "DataTables-1.9.4/extras/ColReorder/media/css/ColReorder.css";
	@import "DataTables-1.9.4/extras/ColVis/media/css/ColVis.css";
	@import "DataTables-1.9.4/TableTools-2.1.5/css/TableTools.css";
	<!--@import "DataTables-1.9.4/media/css/TableTools_JUI.css";-->
</style>
<script type="text/javascript" language="javascript" src="DataTables-1.9.4/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="DataTables-1.9.4/extras/ColVis/media/js/ColVis.js"></script>
<script type="text/javascript" charset="utf-8" src="DataTables-1.9.4/extras/ColReorder/media/js/ColReorder.js"></script>
<script type="text/javascript" charset="utf-8" src="DataTables-1.9.4/TableTools-2.1.5/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="DataTables-1.9.4/TableTools-2.1.5/media/js/TableTools.js"></script>
<script type="text/javascript" src="DataTables-1.9.4/media/js/jquery.dataTables.editable.js"></script>

<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				
				oTable = $('.members_table').dataTable({
				    
		            "bJQueryUI": true,
					"sDom": /*'R<"H"TlCfr>t<"F"ip>'*/'<"H"f>t<"F"ip>',
					/*"oColVis": {"activate": "mouseover"}, */
		            "sPaginationType": "full_numbers",
					"sScrollY": "250px",
					"bPaginate": true,
					"aLengthMenu": [10, 22, 50],
					"iDisplayLength" : 10,
					"aaSorting": [],
					"bStateSave": false,
					"bFilter": true,
	//				"aoColumns": [ 
	//		/* Empty */   null,
	//		/* Edit */  null,
	//		/* ID */ null,
	//		/* Reg. Date */ null,
	//		/* Agent Name */  null,
	//		/* Main Line */    null,
	//		/* Cell Phone */    null,
	//		/* PIF */    null,
	//		/* Outbound */    null,
	//		/* Approval */    null,
	//		/* Locked */    null,
	//		
	//		
	//		/* Agent Code */   { "bVisible":    false },
	//		/* POC */    { "bVisible":    false },
	//		/* Routeback */    { "bVisible":    false },
	//		/* Fax */    { "bVisible":    false },
	//		/* Skill */    { "bVisible":    false },
	//		/* User Email */    { "bVisible":    false },
	//		/* User CC */    { "bVisible":    false },
	//		/* VM Email */    { "bVisible":    false },
	//		/* State */    { "bVisible":    false },
	//		/* City */    { "bVisible":    false },
	//		/* Address */    { "bVisible":    false },
	//		/* County */    { "bVisible":    false },
	//		/* Spanish */    { "bVisible":    false },
	//		/* Agent Age */    { "bVisible":    false },
	//		/* Start Request Date */    { "bVisible":    false },
	//		/* Last Mod By */  { "bVisible":    false },
	//		/* Mod Time */    { "bVisible":    false },
	//		/* Last Portal Update */    { "bVisible":    false },
	//		/* Last Login */    { "bVisible":    false }
	//					
	//	]
	//				
					
					"oTableTools": {
					"sSwfPath": "DataTables-1.9.4/TableTools-2.1.5/media/swf/copy_csv_xls_pdf.swf",
			"aButtons": [
			
			"print",
			/*,
"sExtends": "pdf",
"sButtonText": "Print PDF",
"mColumns": "visible"
},*/
{
"sExtends": "xls",
"sButtonText": "Export to Spreadsheet",
"mColumns": [ 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33  ]
}

]
		}
					})
			} );
		</script>	 	
</head>
<table class="members_table">
<thead>
<tr>
<th>Member ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>City</th>
<th>State</th>
<th>Email</th>
<th>Timestamp</th>
</tr>
</thead>

<tbody>
<?php
$rs_results = mysql_query("select * from xml_post") or die(mysql_error());
while ($member = mysql_fetch_array($rs_results)) { 

$memberid = $member['MembersID'];
$firstname = $member['FirstName'];
$midinit = $member['MidInit'];
$lastname = $member['LastName'];
$city = $member['City'];
$state = $member['State'];
$zip = $member['Zip'];
$homephone = $member['HomePhone'];
$email = $member['Email'];
$ts = $member['post_timestamp'];

?>
<tr>
<td><?php echo $memberid; ?></td>
<td><?php echo $firstname; ?></td>
<td><?php echo $lastname; ?></td>
<td><?php echo $city; ?></td>
<td><?php echo $state; ?></td>
<td><?php echo $email; ?></td>
<td><?php echo $ts; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
