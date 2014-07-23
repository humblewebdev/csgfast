<?php 
/*****************************************************
* Author: Amber Bryson
* Date: 1/19/2014
* Audit Trail
*
*
*****************************************************/
?>

<div class="row-fluid icon">
	<div class="span12">
		<!---------------------------------------------------
		-
		-        Audit trail Search text box
		-  
		----------------------------------------------------->
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Search Audit Trail</h5>
			</div>
			<div class="well-content no-search">
				Search: <input class="span9" type="text" name="audit_search" id="audit_search" placeholder="Please enter the user ID to search" onkeyup="auditQuery()">
			</div>
		</div>

		<!---------------------------------------------------
		-
		-        Audit trail Search Result
		-  
		----------------------------------------------------->
		<div class="well <?php echo $info_ui_color; ?>">
			<div class="well-header">
				<h5>Audit Trail Results</h5>
			</div>
			<div class="well-content no-search">
				<div id="audit_results">
					Waiting for search input..
				</div>
			</div>
		</div>
	</div>
</div>

<!------------------------------------------------------------------ Javascript Area ----------------------------------------------->
<script src="js/jquery-1.10.2.js"></script>	

<script>
$(document).ready(function () {
   
	// Icon Click Focus
	$('div.icon').click(function(){
		$('#audit_search').focus();
	});
});
</script>

<script>
function auditQuery() {
var sval = $('#audit_search').val();
$.ajax({
		type: "POST",
		url: "z_scripts/audit_trail_results.php?searchval="+sval,
		success: function(data, textStatus, jqXHR){
			$("#audit_results").load("z_scripts/audit_trail_results.php?searchval="+sval);
		}
	});
}
</script>