<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CSG FAST: Map</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/stylesheet.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    
  </head>

  <body>
	<div class="span6">
		<div class="well dark_blue">
			<div class="well-header">
				<h5>Google maps</h5>
			</div>

			<div class="well-content no_padding">
				<div id="google-map" style="height: 380px;"></div>
			</div>
		</div>
	</div>

</body>
</html>
<!--------------------------  Le Java Scripts ------------------------------------>
<script src="js/forms.js"></script>

<script>
jQuery(document).ready(function($) {
	function initialize()
	{
		var mapProp= {
			center: new google.maps.LatLng(52.515535,13.405380),
			zoom: 15,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		var map=new google.maps.Map(document.getElementById("google-map"),mapProp);

	};
	google.maps.event.addDomListener(window, 'load', initialize);
});
</script>