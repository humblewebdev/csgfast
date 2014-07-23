<!--<html>
<body>


<html>
<body>

<form action="upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>

</body>
</html>
</body>
</html>
-->
<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <title>CSG | BetaDental XML Post</title>
        <link href="css/upload.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="plugins/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
        <script src="js/upload_live.js"></script>
		<!-- Add fancyBox -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="plugins/fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$(".fancybox").fancybox({
				fitToView	: true,
				width		: '800px',
				height		: '660px',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		});
	    </script>
    </head>
    <body>
      
        <div class="container" id="allcontent">
            <div class="contr"><h2><center>Beta Dental XML Post</center></h2></div>
            <div class="upload_form_cont">
                <form id="upload_form" enctype="multipart/form-data" method="post" action="upload.php">
                    <div>
                        <div><label for="file">Please select xml file</label></div>
                        <div><input type="file" name="file" id="file" onchange="fileSelected();" /></div><br>
						<div><label for="file">Email for Confirmation Message</label></div>
						<div><input type="text" style="width: 300px;" name="cc_email"/></div>
                    </div>
                    <div>
                        <input type="button" value="Upload" onclick="startUploading()" />
                    </div>
                    <div id="fileinfo">
                        <div id="filename"></div>
                        <div id="filesize"></div>
                        <div id="filetype"></div>
                        <div id="filedim"></div>
                    </div>
                    <div id="error">You should select valid xml files only!</div>
                    <div id="error2">An error occurred while uploading the file</div>
                    <div id="abort">The upload has been canceled by the user or the browser dropped the connection</div>
                    <div id="warnsize">Your file is very big. We can't accept it. Please select more small file</div>

                    <div id="progress_info">
                        <div id="progress"></div>
                        <div id="progress_percent">&nbsp;</div>
                        <div class="clear_both"></div>
                        <div>
                            <div id="speed">&nbsp;</div>
                            <div id="remaining">&nbsp;</div>
                            <div id="b_transfered">&nbsp;</div>
                            <div class="clear_both"></div>
                        </div>
                        <div id="upload_response"></div>
                    </div>
                </form>

                <img id="preview" />
            </div>
        </div>
    </body>
</html>