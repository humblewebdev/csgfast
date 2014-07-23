<?php
$cc = $_POST['cc_email'];

$allowedExts = array("xml","pdf");
$extension = end(explode(".", $_FILES['file']['name']));
if (($_FILES['file']['type'] == "application/xml")
|| ($_FILES['file']['type'] == "text/xml")
&& ($_FILES['file']['size'] < 20971520) //20mB Limit
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
	date_default_timezone_set('America/Chicago');
    $datenow = date('m/d/Y h:i:s a', time());
	$datenow2 = date('mdYhis', time());
	$confirmno = $datenow2 . rand(100, 999);
	
    echo "Upload: " . $_FILES['file']['name'] . "<br>";
    echo "Type: " . $_FILES['file']['type'] . "<br>";
    echo "Size: " . ($_FILES['file']['size'] / 1024) . " kB<br>";
	echo "Confirmation # " . $confirmno . "<br>";
	echo "CC Email: " . $cc . "<br>";
    
    //if (file_exists("upload/" . $_FILES["file"]["name"]))
      //{
      //echo $_FILES["file"]["name"] . " already exists. ";
      //}
    //else
     // {
      move_uploaded_file($_FILES['file']['tmp_name'],
      "upload/" . $_FILES['file']['name']);
      echo "Stored in: " . "upload/" . $_FILES['file']['name'];
	    

		$filesize = ($_FILES['file']['size'] / 1024);
		$remoteip = $_SERVER['REMOTE_ADDR'];
  
		$file = 'upload/upload_logs.txt';
		// Open the file to get existing content
		$current = file_get_contents($file);
		// Append a new person to the file
		$current .= "($datenow) ($confirmno) File Name: {$_FILES['file']['name']} uploaded with filesize: $filesize kB from ip: $remoteip\n";
		// Write the contents back to the file
		file_put_contents($file, $current);
	    
		$from = 'zachr@csg-email.com';
		$xheaders = "";
		$xheaders .= "From: <$from>\n";
		$xheaders .= "X-Sender: <$from>\n";
		$xheaders .= "X-Mailer: PHP\n"; // mailer
		$xheaders .= "X-Priority: 1\n"; //1 Urgent Message, 3 Normal
		$xheaders .= "Content-Type:text/html; charset=\"iso-8859-1\"\n";
		 
		 $to = "zachr@csg-email.com,$cc";
		 $subject = "NEW BetaDental XML Post";
		 $body = "
		 File Name: {$_FILES['file']['name']}<br>
		 File Size: $filesize kB<br>
		 Confirmation Number: $confirmno<br><br><br>
		 Sender IP: $remoteip<br>
		 Timestamp: $datenow<br>
         ";
		 if (mail($to, $subject, $body, $xheaders)) {
		   echo("<p>Email notification sent successfully!</p>");
		  } else {
		   echo("<p>Email message delivery failed...</p>");
		  }
		 echo '<center><a class="fancybox button" data-fancybox-type="ajax" href="members_table.php">Click Here to View Updated List</a></center>';
/***************************************Do SQL Query*********************************************************/

include 'grab_xml_and_parse.php';

/***************************************End SQL Query*********************************************************/
     // }
    }
  }
else
  {
  echo "Invalid file";
  }
  

/*
function bytesToSize1024($bytes, $precision = 2) {
    $unit = array('B','KB','MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}

$sFileName = $_FILES['image_file']['name'];
$sFileType = $_FILES['image_file']['type'];
$sFileSize = bytesToSize1024($_FILES['image_file']['size'], 1);

echo <<<EOF
<p>Your file: {$sFileName} has been successfully received.</p>
<p>Type: {$sFileType}</p>
<p>Size: {$sFileSize}</p>
EOF;

move_uploaded_file($_FILES['image_file']['temp_name'],
      "upload/" . $_FILES['image_file']['name']);
      echo "Stored in: " . "upload/" . $_FILES['image_file']['name'];
*/
?>