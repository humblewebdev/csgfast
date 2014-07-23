<?php 
include ("db_session.php");
?>
<script>
$('.mentog').show();
</script>
<html>

<link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel='stylesheet' />
	
<link href="css/file_upload_form.css" rel="stylesheet" />
<div class="span12">
<a class="btn btn_primary" title="Please download this file and fill the contents in the columns with data, any other columns you add to it will be accepted." href="sample_template.csv"><i class="icon-download-alt"></i>Download Blank Template</a><br><br>
<form id="upload" method="post" action="import_script.php" enctype="multipart/form-data">
            <div id="drop">
                Drop Here

                <a>Browse</a>
                <input type="file" name="upl" multiple />
            </div>

            <ul>
                <!-- The file uploads will be shown here -->
            </ul>

</form>
</div>
        <!-- JavaScript Includes -->
        <script src="js/jquery.knob.js"></script>

        <!-- jQuery File Upload Dependencies -->
        <script src="js/jquery.ui.widget.js"></script>
        <script src="js/jquery.iframe-transport.js"></script>
        <script src="js/jquery.fileupload.js"></script>

        <!-- Our main JS file -->
        <script src="js/file_upload_script.js"></script>
		

</html>