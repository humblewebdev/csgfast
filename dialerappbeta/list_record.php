<?php
include ("db_session.php");

//Grabs RecordID from POST or GET
$recordid = $_GET['recid'];

if($recordid == NULL){
	$recordid = $_POST['recid'];
}


//Assigns a record to a User upon action
if($_POST['addattempt'] == 1){
    $notes = mysql_real_escape_string(trim($_POST['notes']));
	$disp = mysql_real_escape_string(trim($_POST['disp']));
	$pname = mysql_real_escape_string(trim($_POST['pname']));
	$typ = mysql_real_escape_string(trim($_POST['typename']));
	$date = date("Y-m-d H:i:s",time());
	$unixt = time();
	$audit_record = mysql_query("
	INSERT INTO dialer_list_record_link
	(`link_record_id`,`link_record_type`,`link_call_attempt`,`link_notes`,`link_disp_type`,`link_timestamp`,`link_unix_timestamp`,`link_action_by`,`link_action_by_un`,`link_assoc_post_name`)

	VALUES 
	(
	'$recordid',
	'$typ',
	'1',
	'$notes',
	'$disp',
	NOW(),
	'$unixt',
	'$nam',
	'$sam',
	'$pname'
	)
	;") or die(mysql_error()); 
    
	echo "A call attempt has now been added to ";
	exit;
}

//Unassigns a record from a User upon action
if($_POST['removeattempt'] == 9){
    $ts = $_POST['ts'];
	$delete_record = mysql_query("DELETE FROM dialer_list_record_link WHERE link_record_id='$recordid' AND link_action_by_un='$sam' AND link_timestamp='$ts'") or die(mysql_error()); 
	echo " has now had a call attempt removed";
	exit;
}


/********************************************************* END OF CHECKED PAGE LOAD SCRIPTS **********************************************************/

$rs_results = mysql_query("select * from dialer_list WHERE id='$recordid'") or die(mysql_error());
$record = mysql_fetch_assoc($rs_results);


/*
$record_query = mysql_query("select * from dialer_list_record_link WHERE id='$recordid'") or die(mysql_error());
$record_row = mysql_fetch_assoc($disp_query);
*/

?>
<link rel="stylesheet" href="css/bootstrap-select.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="js/bootstrap-select.js"></script>
<script>$('.selectpicker').selectpicker();</script>
<div class="row">
<div class="span12">
<hr></hr>
<?php
		    $search_attempts = mysql_query("SELECT * FROM dialer_list_record_link WHERE link_record_id='$recordid' AND link_call_attempt='1'");
			$a_count = mysql_num_rows($search_attempts);
            
            $search_notes = mysql_query("select * from dialer_list_record_link WHERE link_record_id='$recordid' AND link_call_attempt='0' AND link_notes IS NOT NULL") or die(mysql_error());			
            $n_count = mysql_num_rows($search_notes);
			
			$users_link_list2 = mysql_query("SELECT * FROM dialer_list_user_profiles WHERE user_current_record='$recordid'");
			$users_link_list2_name = mysql_fetch_assoc($users_link_list2);
			$count_assigned_users2 = mysql_num_rows($users_link_list2); 
			
			$search_phone_recs = mysql_query("SELECT * FROM dialer_list WHERE phone_number='{$record['phone_number']}' AND name=''{$record['name']}''");
			$pr_count = mysql_num_rows($search_phone_recs);
			
?>
<?php if($pr_count > 1){ $rc = 1;?>

<div class="btn-group">
		 <?php while($spr = mysql_fetch_assoc($search_phone_recs)){ ?>
		<button class="btn" onclick="recordshow('<?php echo $spr['id']; ?>')"href="#tab1" data-toggle="tab">Record <?php echo $rc; $rc++;?></button>
		 <?php } ?>
</div>
<br><br>
<?php } ?>
<?php if($count_assigned_users2 == 0 || $users_link_list2_name['user_full_name'] == $nam){ ?>
<div class="btn-primary btn contd">Add Attempt <?php echo $a_count +1; ?></div>
<div class="btn" id="closerec">Close</div>
<?php } else{echo "This record is currently assigned to another user...(".$users_link_list2_name['user_full_name'].")";}?>
<div style="float: right;"><a class="btn-warning btn" href="#add_notes" data-toggle="modal" role="button" onclick="aff('<?php echo $recordid; ?>', 'add_notes')">Add Side Notes</a></div>
<div style="float: right; margin-right: 5px;"><a class="btn-warning btn" href="#view_notes" data-toggle="modal" role="button" onclick="aff('<?php echo $recordid; ?>','view_notes')"><?php if($n_count != 0){ ?><span class="badge badge-inverse"><?php echo $n_count; ?></span>&nbsp;<?php } ?>View Side Notes</a></div>
<hr></hr>
</div>
</div>


<div class="row attemptoptions" style="display: none;">

<div class="span12">
<div class="well">

<strong>Disposition Type</strong> <br>
<div class="picker">
								<select id="disp" name="disp" class="selectpicker">
									<option value="#" disabled>Choose one...</option>
									<optgroup label="Alerts">
									<option value="FA01 - Closed, Paid in Full">FA01 - Closed, Paid in Full</option>
									<option value="FA02 - Closed, 3rd Attempt Complete - VM/Email">FA02 - Closed, 3rd Attempt Complete - VM/Email</option>
									<option value="FA03 - Set Task, Partial Payment">"FA03 - Set Task, Partial Payment</option>
									<option value="FA04 - Set Task, Pay at a later date">FA04 - Set Task, Pay at a later date</option>
									<option value="FA05 - No Answer, Left VM/Sent Email">FA05 - No Answer, Left VM/Sent Email</option>
									<option value="FA06 - Wrong Number, Sent Email/Updated Phone">FA06 - Wrong Number, Sent Email/Updated Phone</option>
									<option value="FA07 - Wrong Number, No Number/Email - Eprint">FA07 - Wrong Number, No Number/Email - Eprint</option>
									<option value="FA08 - FA08 - Cust. Ans. and Hung Up">FA08 - Cust. Ans. and Hung Up</option>
									<option value="FA09 - Policy has Cancelled">FA09 - Policy has Cancelled</option>
									<option value="FA10 - Wrong Number, No Email - Alt. # Entered">FA10 - Wrong Number, No Email - Alt. # Entered</option>
									<option value="FA11 - Call Answered / Left Message">FA11 - Call Answered / Left Message</option>
									<option value="FA12 - Previously Worked (Closed)">FA12 - Previously Worked (Closed)</option>
									<!--
									 <//?php
									if(strcmp($record_row["link_disp_type"],"FA12 - Previously Worked (Closed)") !=0){
										<option value="FA12 - Previously Worked (Closed)">FA12 - Previously Worked (Closed)</option>
									}
									?>
									-->
									</optgroup>
									<optgroup label="FFR">
									<option value="FR01 - Appointment Set">FR01 - Appointment Set</option>
									<option value="FR02 - Do Not Call">FR02 - Do Not Call</option>
									<option value="FR03 - ePolicy">FR03 - ePolicy</option>
									<option value="FR04 - Invalid Number/Eprint">FR04 - Invalid Number/Eprint</option>
									<option value="FR05 - Not Interested">FR05 - Not Interested</option>
									<option value="FR06 - No Answer,Left VM/Sent Email">FR06 - No Answer,Left VM/Sent Email</option>
									<option value="FR07 - Cust. Ans. and Hung Up">FR07 - Cust. Ans. and Hung Up</option>
									<option value="FR08 - Policy has Cancelled">FR08 - Policy has Cancelled</option>
									<option value="FR09 - Wrong Number, No Email - Alt. # Entered">FR09 - Wrong Number, No Email - Alt. # Entered</option>
									<option value="FA10 - Call Answered / Left Message">FA10 - Call Answered / Left Message</option>
									</optgroup>
								</select>
							</div>

<strong>Call Notes</strong> 
<p class="field"><textarea id="notes" placeholder="Type in some notes about the call..." rows="4" style="width: 98.5%;" class="field span6"></textarea></p>
<input class="input" style="display: none;" type="text" id="pname" value="<?php echo $record['post_name']; ?>"/>
<input class="input" style="display: none;" type="text" id="typename" value="<?php echo $record['type']; ?>"/>

<div class="btn-success btn addattempt" id="addattempt" data-value="1">Confirm</div>
<div class="btn-danger btn cancelattempt" id="cancelattempt" data-value="1">Cancel</div>
<!-- added "final attempt" chckbox 2/5/14 E.R. -->
<label class="checkbox inline">
  <input type="checkbox" id="finalattempt" data-value="1"> Final Attempt
</label>
</div>
<hr></hr>
</div>


</div>


<div class="row">
            <div class="span6">
			<h4 class="lead">Information</h4>
			<div class="well">
	
				<form>
				    <p></p>
					<table>
				    <tr><td><i class="icon-hand-right"></i><strong>&nbsp;Phone</strong></td><td>&nbsp;</td><td><?php echo formatPhone($record['phone_number']); ?></td></tr>
					<tr><td><i class="icon-user"></i><strong>&nbsp;Name</strong></td><td>&nbsp;</td><td><?php echo ucwords($record['name']); ?></td></tr>
					<tr><td><i class="icon-info-sign"></i><strong>&nbsp;Type</strong></td><td>&nbsp;</td><td><?php echo ucwords($record['type']); ?></td></tr>
					<tr><td><i class="icon-calendar"></i><strong>&nbsp;Post TimeStamp</strong></td><td>&nbsp;</td><td><?php echo $record['post_timestamp']; ?></td></tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
					
					<?php  foreach($record as $keyrec => $recordfills){ 
					if(
					    $keyrec != "phone_number" && 
						$keyrec != "assign_start_date" && 
						$keyrec != "type" && 
						$keyrec != "id" && 
						$keyrec != "name" && 
						$keyrec != "post_timestamp" && 
						$keyrec != "post_name" && 
						$keyrec != "uniqueid" && 
						$keyrec != "post_unixts" &&
						$recordfills != NULL
					){
					$nice_keyrec = ucwords(preg_replace('~[\W_]~', ' ', $keyrec));
					$nice_recordfills = ucwords($recordfills);
					?>
					<tr>
					<td><i class="icon-chevron-right"></i><strong>&nbsp;<?php echo $nice_keyrec; ?></strong></td>
					<?php if($keyrec == "agent_name") { ?>
					<td>&nbsp;</td><td><a href="#ipage" data-toggle="modal" role="button" class="btn btn-info btn-small" href="#"><?php echo $nice_recordfills; ?></a></td>
					<div class="modal hide fade"  style="width: 700px; margin-left: -350px !important; " id="ipage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						 <div class="modal-header">
						   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						   <h3 id="myModalLabel">Portal Page for <?php echo $nice_recordfills; ?></h3>
						 </div>
						 <div class="modal-body" style="max-height: 700px;">
						   <div id="ipage_content">
						   <iframe src="https://csgfast.com/ipage_view.php?un=<?php echo (preg_replace('~[\W_]~', '', $nice_recordfills)); ?>" width="650" frameborder='0' height="1000"></iframe>
						   </div>
						 </div>
						 <div class="modal-footer">
						   <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
						   <!--<button class="btn btn-primary">Save changes</button>-->
						 </div>
					</div>
					
					
					
					
					<?php }else { ?>
					<td>&nbsp;</td><td><?php echo $nice_recordfills; ?></td>
					<?php } ?>
					</tr>
					<?php }} ?>

					</table>
					<br>

				</form>
			</div>	
			</div>
			<div class="span6">
			<h4 class="lead">Attempt History</h4>
			<div class="well">    

		    <?php if($a_count == 0){echo "No attempts have been made..."; } else {echo "<br>";}?>
			<?php $i=1; ?>
            <?php while($countlist = mysql_fetch_array($search_attempts)){
			echo "<a class='badge-info badge' style='position: absolute; margin-left: -20px !important; margin-top: -15px !important;'>" . $i++ . "</a>";
			echo "<table width='95%' style='margin-left: 20px !important;'";
			echo "<col width='70%'><col width='30%'>";
			echo "<tr><td><strong>Attempt Date:&nbsp;&nbsp;</strong></td><td>{$countlist['link_timestamp']}</td></tr>";
			echo "<tr><td><strong>Call Made By:&nbsp;&nbsp;</strong></td><td>{$countlist['link_action_by']}</td></tr>";
			echo "<tr><td><strong>Disposition:&nbsp;&nbsp;</strong></td><td>{$countlist['link_disp_type']}</td></tr>";
			echo "<tr><td><strong>Notes:&nbsp;&nbsp;</strong></td><td>{$countlist['link_notes']}</td></tr>";
			echo "</table>";
			if($countlist['link_action_by'] == $nam){ echo "<div style='float: right !important;' ><a href=\"#\" class=\"btn-danger btn removeattempt\" id=\"removeattempt\"  data-value=\"9\" ts-value=\"{$countlist['link_timestamp']}\">Remove Attempt</a></div>"; }
			if($i <= $a_count){ echo "<br><hr></hr>"; }else{ echo "<br>"; }
			}
			?>
			
			</div>
			</div>
	        
			
		</div>

	<script>
	$(".contd").click(function(){
	$(".attemptoptions").slideDown();
	});
	$(".cancelattempt").click(function(){
	$(".attemptoptions").slideUp();
	});
	$(".addattempt").click(function(){
	  
	  var assignval = document.getElementById( 'addattempt' ).getAttribute('data-value');
	  var disp = document.getElementById( 'disp' ).value;
	  var notes = document.getElementById( 'notes' ).value;
	  var pname = document.getElementById( 'pname' ).value;
	  var typename = document.getElementById( 'typename' ).value;
	  var finalattempt = document.getElementById('finalattempt').value;
      $.ajax({
	  type: "POST",
       url: "list_record.php",
       data: "addattempt="+assignval+"&recid=<?php echo $recordid; ?>&notes="+notes+"&disp="+disp+"&pname="+pname+"&typename="+typename,
       success: function(msg){
         alert( msg + "<?php echo $record['name']; ?> " ); //Anything you want
		 recordshow('<?php echo $recordid; ?>');
       }
      });
	 
    });
	
	</script>
	
	<script>
	$(".removeattempt").click(function(){
	  
	  var assignval = document.getElementById( 'removeattempt' ).getAttribute('data-value');
	  var ts = document.getElementById( 'removeattempt' ).getAttribute('ts-value');
      $.ajax({
	  type: "POST",
       url: "list_record.php",
       data: "removeattempt="+assignval+"&recid=<?php echo $recordid; ?>&ts="+ts,
       success: function(msg){
         alert( "<?php echo $record['name']; ?>" + msg); //Anything you want
		 recordshow('<?php echo $recordid; ?>');
       }
      });
	 
    });
	</script>

	
		