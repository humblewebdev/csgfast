<?php include 'custom_incontact_functions.php';?>

<?php 
										$b = get_LiveAgents();
										$ACW = 0;
										$Available = 0;
										$Outbound = 0;
										$Inbound = 0;
										$Unavailable = 0;
										foreach($b as $person){ 

										$a = $person->CurrentState; 
										if($person->TeamName == "FAST"){
										if($a == 'ACW'){$ACW = $ACW + 1;} else
										if($a == 'Available'){$Available = $Available + 1;} else
										if($a == 'InboundContact'){$Inbound = $Inbound + 1;} else
										if($a == 'OutboundContact'){$Outbound = $Outbound + 1;} else
										if($a == 'Unavailable'){$Unavailable = $Unavailable + 1;} else
										{$Unavailable = $Unavailable + 1;}
										} 
										} ?>

		<script>
		$(document).ready(function(){
        var line1 = [['ACW', <?php echo $ACW; ?>], ['Available', <?php echo $Available; ?>], ['Outbound', <?php echo $Outbound; ?>], ['Inbound', <?php echo $Inbound; ?>], [' Unavailable', <?php echo $Unavailable; ?>]];
        var plot1 = $.jqplot('chartdiv', [line1], {
        title: 'Agent Count By State',
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
		
		<div id="chartdiv"></div>