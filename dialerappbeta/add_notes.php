<?php 
include ("db_session.php");

//Grabs RecordID from POST or GET
$recordid = $_GET['affid'];

if($recordid == NULL){
	$recordid = $_POST['recid'];
}

if($_POST['newnote'] == 1){
	$date = date("Y-m-d H:i:s",time());
	$unix_time = time();
	$notecontent = mysql_real_escape_string(trim($_POST['notecontent']));
	$typen = mysql_real_escape_string(trim($_POST['type']));
	$audit_record = mysql_query("
	INSERT INTO dialer_list_record_link
	(`link_record_id`,`link_record_type`,`link_notes`,`link_timestamp`,`link_unix_timestamp`,`link_action_by`,`link_action_by_un`)

	VALUES 
	(
	'$recordid',
	'$typen',
	'$notecontent',
	NOW(),
	'$unix_time',
	'$nam',
	'$sam'
	)
	;") or die(mysql_error()); 
    echo " now has this note posted to their account";
	exit;
}
$rs_results = mysql_query("select * from dialer_list WHERE id='$recordid'") or die(mysql_error());
$record = mysql_fetch_assoc($rs_results);

$tabcount = 0;
$contentcount = 0;
?>
<div id="notepage">
<div class="row text-center">

<h2><?php echo ucwords($record['name']); ?></h2>
<hr></hr>

</div>
<div class="row text-center">

 
    <textarea id="mynote" class="field span5" placeholder="Type in some notes" rows="4"></textarea>
    <!--<div><a href="#" class="submitnote btn-success btn">Submit Note</a></div>-->
	<input class="input" style="display: none;" type="text" id="typen" value="<?php echo $record['type']; ?>"/>

</div>
</div>

	<script>
	$(".submitnote").click(function(){
	  var notecontent = document.getElementById("mynote").value;
	  $('.submitnote_holder').html('<img src="img/loader-circular.gif"/>');
   
	  var typen = document.getElementById( 'typen' ).value;
	  $.ajax({
	  type: "POST",
       url: "add_notes.php",
       data: "newnote=1&recid=<?php echo $recordid; ?>&notecontent="+notecontent+"&type="+typen,
       success: function(msg){
		 $("#notepage").empty();
		 $("#notepage").html('<br><p class="alert alert-success"><?php echo $record['name']; ?> '+msg+'</p>');
		 $('.submitnote_holder').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>');
		 $(".modal").modal("hide");
		 recordshow('<?php echo $recordid; ?>');
       }
      }); 
    });
	
	</script>
