<?php
include ("db_session.php");

$con=mysqli_connect("localhost","root","QwertY4321", "test_bucketdb");

$tableList = array();
$res = mysqli_query($con,"SHOW TABLES");
while($cRow = mysqli_fetch_array($res))
{
$tableList[] = $cRow[0];
}

//Grab all distinct types from record list
//$bucket_results = mysql_query("SELECT DISTINCT(Type) AS type from dialer_list") or die(mysql_error());

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
		$count = 0;
		foreach( $tableList as $table){
			$count++;
			$sql = ("SELECT TOP 1 * FROM $table");
			$data = mysqli_query($con, "SELECT * FROM $table WHERE id=1");
			
			$result = mysqli_fetch_array($data);
			
			$bucketype = $result['type'];
			$bucketid = $result['id'];
			
			$t_count = $result['total'];
			$att_count = $result['attempted'];
			$rt_count = $result['remaining_today'];
			$c_count = $result['completed'];
			$appt_count = $result['appts'];
			$r_count = $result['remaining_overall'];
			
			if($r_count != 0){
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
		
        var plot1 = $.jqplot('chartdiv<?php echo $count; ?>', [line1], {
        title: '<?php echo ucwords($bucketype); ?>',
	    seriesDefaults:{
        renderer:$.jqplot.BarRenderer,
        rendererOptions:{ varyBarColor : true },
        pointLabels: { show : true }
        },
		seriesColors: [ "#F9AD81", "#82CA9D", "#FFF79A", "#A187BE", "#F7977A"],
		//seriesColors: [ "#FF6F0F", "#008000", "#FFD427", "#9F0FFF", "red"],
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
		   <div id="chartdiv<?php echo $count; ?>"></div><hr>

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
	  
	<?php }
	//Reset all these variables to 0, so they can be used again for the next bucket type in the loop
	$r_count=0; $t_count=0; $att_count=0; $appt_count=0; $c_count=0;
	
	} //End of the loop iteration for this type of bucket, loop will then continue with the next type of bucket 
	?>
	
   </ul>
   
</div>


		
		
		
</html>