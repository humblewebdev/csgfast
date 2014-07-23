<?php
include ("db_session.php");

// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);

//Grab all distinct types from record list
$bucket_results = mysql_query("SELECT DISTINCT(Type) AS type from dialer_list") or die(mysql_error());
$curtime = time();
//The unix time twelve hours ago from now 
$twelvebefore = ($curtime - 43200);	
?>
<!DOCTYPE HTML>
<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<!-- After the page is loaded in, this script calls for the menu to reappear -->
<script>
$('.mentog').show();
</script>

<!-- chart scripts -->
        <script src="https://csgfast.com/oldcsgfast/js/jquery.js"></script>
        <script src="https://csgfast.com/oldcsgfast/plugins/jqPlot/jquery.jqplot.min.js"></script>
        <script src="https://csgfast.com/oldcsgfast/plugins/jqPlot/plugins/jqplot.cursor.min.js"></script>
        <script src="https://csgfast.com/oldcsgfast/plugins/jqPlot/plugins/jqplot.highlighter.min.js"></script>
        <script src="https://csgfast.com/oldcsgfast/plugins/jqPlot/plugins/jqplot.barRenderer.min.js"></script>
        <script src="https://csgfast.com/oldcsgfast/plugins/jqPlot/plugins/jqplot.pointLabels.min.js"></script>
		
		<script type="text/javascript" src="https://csgfast.com/oldcsgfast/plugins/jqPlot/plugins/jqplot.dateAxisRenderer.min.js"></script>
		<script type="text/javascript" src="https://csgfast.com/oldcsgfast/plugins/jqPlot/plugins/jqplot.canvasTextRenderer.min.js"></script>
		<script type="text/javascript" src="https://csgfast.com/oldcsgfast/plugins/jqPlot/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
		<script type="text/javascript" src="https://csgfast.com/oldcsgfast/plugins/jqPlot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
		<script type="text/javascript" src="https://csgfast.com/oldcsgfast/plugins/jqPlot/plugins/jqplot.barRenderer.min.js"></script>
<!-- /chart -->
		</head>
<div class="span12">
    <a href="exportview.php" target="_blank" class="btn btn-block"> View Data for all buckets </a> </br>
   <ul class="thumbnails">
   
   <?php 
   // A loop that iterates for each instance of a distinct "type" as grabbed from the bucket_results query above
   $att_count=0;$c_count=0;$appt_count=0;
   while ($bucket_rec = mysql_fetch_assoc($bucket_results)) { 
	   
	   //Sets the variable equal to the bucket "type" column
	   $bucketype = $bucket_rec["type"];
	   
	   //Initializes the amount of worked records to zero
	   $w_count = 0;
	   
	   //Query to grab all the records in the dialer list of this specific type
	   $bucketlist = mysql_query("select * from dialer_list WHERE type='$bucketype' ") or die(mysql_error());
	       
		   // A loop that iterates for each seperate record of the bucket type above and executes actions on a per record basis
		   while ($bl = mysql_fetch_array($bucketlist)) {
		     //Sets the id of the current record to this variable
			 $bucketid = $bl['id'];
			 //Array of completion dispositions to be used in the below query
			 $disptypes_arr = array("fa01","fa02","fa03","fa06","fa07","fa09","fa12","fr01","fr02","fr03","fr04","fr05","fr08");
// add in fa12 to array -amber
			 //Array of appointment dispositions to be used in the below query to count appointments made
			 $disptypes_arr_c = array("fr01");
			 
			 //Implode both arrays with commas and quotations, so that it is in suitable format for the query
			 $disptypes = '"' . implode('","', $disptypes_arr) . '"';
			 $disptypes_c = '"' . implode('","', $disptypes_arr_c) . '"';
			 
              //Grab all the associated links from the record_link table that are associated with this record that match the following conditions			  
			  $bucket_link_list = mysql_query(
			  "SELECT * FROM dialer_list_record_link WHERE 
			  (link_unix_timestamp>'$twelvebefore' OR SUBSTRING(link_disp_type, 1, 4) IN ($disptypes)) AND
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='1'") or die(mysql_error());
			  
			  /* Out of the query above, count the number of rows returned and set it to this variable,
                 the rows returned will be only ones that have been truely worked or have been called in
				 the pase twelve hours
			  */

			  $count_true_worked = mysql_num_rows($bucket_link_list); 
			  
			  //If the amount of worked calls is greater than 0, increase the overall worked count of all records by 1
			  if($count_true_worked > 0){ $w_count++; };
			  
			  //Grab all the associated links from the record_link table that are associated with this record that match the following conditions	
			  $bucket_link_list_completed = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
              SUBSTRING(link_disp_type, 1, 4) IN ($disptypes) AND
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='1'") or die(mysql_error());
			  
			  /* Out of the query above, count the number of rows returned and set it to this variable,
                 the rows returned will be only ones that have been truely completed and does NOT include
				 those that have been called in the past twelve hours
			  */
			  
			  $count_true_completed = mysql_num_rows($bucket_link_list_completed); 
			  
			  //If the amount of completed calls is greater than 0, increase the overall completed count of all records by 1
			  if($count_true_completed > 0){ $c_count++; };
			  
			  //Grab all the associated links from the record_link table that are associated with this record that match the following conditions	
			  $bucket_link_list_att = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='1'") or die(mysql_error());
			  
			  /* Out of the query above, count the number of rows returned and set it to this variable,
                 the rows returned will be only ones that have had a call attempted on them
			  */
			  
			  $count_true_att = mysql_num_rows($bucket_link_list_att); 
			  
			  //If the amount of attempted calls is greater than 0, increase the overall attempt count of all records by 1
			  if($count_true_att > 0){ $att_count++; };
			  //If the amount of attempted calls is greater than or equal to 3, increase the overall attempt count of all records by 1
			  if($count_true_att >= 3 && $count_true_completed == 0){ $c_count++; };
			  
			  
			  $bucket_link_list_appts = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
              SUBSTRING(link_disp_type, 1, 4) IN ($disptypes_c) AND
			  link_record_type='$bucketype' AND 
			  link_record_id='$bucketid' AND 
			  link_call_attempt='1'") or die (mysql_error());
			  
			   /* Out of the query above, count the number of rows returned and set it to this variable,
                 the rows returned will be only ones that have had appointments set (The disposition is the the $disptypes_c array)
			  */
			  
			  $count_true_appts = mysql_num_rows($bucket_link_list_appts); 
			  
			  //If the amount of appt calls is greater than 0, increase the overall appt count of all records by 1
			  if($count_true_appts > 0){ $appt_count++; };
			  
		   }
	   
	   //Another query to grab all records in the dialer_list that match this bucket type
	   $bucket_list = mysql_query("SELECT * FROM dialer_list WHERE type='$bucketype'") or die(mysql_error());
	   
	   //The total amount of records for this type are set to this variable
	   $t_count = mysql_num_rows($bucket_list); 

	   $r_count = ($t_count - $c_count); //Remaining after completed
	   $rt_count = ($t_count - $w_count); //Remaining today after worked(called in past twelve hours &|| worked)
   ?>
	    <!-- Script to initialize charts plugin for each distinct bucket type and set parameters -->
		<script>
		$(document).ready(function(){
        var line1 = [
		['Total', <?php echo $t_count ?>], 
		['Attempted', <?php echo $att_count; ?>], 
		['Rem. Today', <?php echo $rt_count; ?>], 
		['Completed', <?php echo $c_count; ?>], 
		['Appts.', <?php echo $appt_count; ?>], 
		['Rem. Overall', <?php echo $r_count; ?>]];
		
        var plot1 = $.jqplot('chartdiv<?php echo $bucketid; ?>', [line1], {
        title: '<?php echo ucwords($bucketype); ?>',
	    seriesDefaults:{
        renderer:$.jqplot.BarRenderer,
        rendererOptions:{ varyBarColor : true },
        pointLabels: { show : true }
        },
		seriesColors: [ "#FF6F0F", "#008000", "#FFD427", "#9F0FFF", "red"],
		series:[{renderer:$.jqplot.BarRenderer}],
		
		axesDefaults: {
        tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
        tickOptions: {
        angle: -30,
         fontSize: '10pt'
        }
        },
        axes: {
        xaxis: {
        renderer: $.jqplot.CategoryAxisRenderer
        }
        }
        });
        });
		</script>
		
	  <li class="span3">
		<div class="thumbnail">
		    
		   <?php 
		   //Statement is used to only show the content within brackets to people that are in the following groups in the CULTURE.Local domain
		   if (in_array("WFM", $memberlist) || in_array("Admins", $memberlist) || in_array("Administrators", $memberlist)) { 
		   ?>
		   <a href="exportview.php?bt=<?php echo $bucketype; ?>" target="_blank" title="Export Data"><i class="icon-download-alt"></i></a>
		   <?php } ?>
		   
		   <!-- This is the div that the chart initialized above is loaded into -->
		   <div id="chartdiv<?php echo $bucketid; ?>"></div><hr>

		  <?php 
		  //If there is no more records remaining for this bucket, mark it as completed and prohibit people from assigning themselves to it
		  if($r_count == 0){ 
		  ?>
		  
		  <center><?php echo $bucketype; ?></center>
		  <button class="btn btn-large btn-block btn-success disabled" type="button">Completed!</button>
		  
		  <?php } 
		  //If the bucket still has records to be worked, allow users to see the "Dive in" button and enter the bucket
		  else { ?>
		  <center><?php echo $bucketype; ?></center>
		  <button class="btn btn-large btn-block btn-primary" type="button" onclick="assign_bucket('<?php echo $bucketype; ?>','<?php echo $sam; ?>','')">Dive in</button>
		  
		 <?php } ?>
		 
		</div>
		
	  </li>
	  
	<?php 
	//Reset all these variables to 0, so they can be used again for the next bucket type in the loop
	$r_count=0; $t_count=0; $att_count=0; $appt_count=0; $c_count=0;
	
	} //End of the loop iteration for this type of bucket, loop will then continue with the next type of bucket 
	?>
	
   </ul>
   
</div>


		
		
		
</html>