<?php 
include ("db_session.php");
//Grabs RecordID from POST or GET
$recordid = $_GET['affid'];

if($recordid == NULL){
	$recordid = $_POST['recid'];
}

if($_POST['deletenote'] == 1){

	$notedate = $_POST['notedate'];
	$recid = $_POST['recid'];
	$delete_record = mysql_query("DELETE FROM dialer_list_record_link WHERE link_timestamp='$notedate' AND link_action_by_un='$sam'") or die(mysql_error()); ; 
    echo " ($notedate) note has now been removed.";
	exit;
}

$rs_results = mysql_query("select * from dialer_list WHERE id='$recordid'") or die(mysql_error());
$record = mysql_fetch_assoc($rs_results);

$rs_results1 = mysql_query("select * from dialer_list_record_link WHERE link_record_id='$recordid' AND link_call_attempt='0' AND link_notes IS NOT NULL") or die(mysql_error());
$rs_results2 = mysql_query("select * from dialer_list_record_link WHERE link_record_id='$recordid' AND link_call_attempt='0' AND link_notes IS NOT NULL") or die(mysql_error());
$rs_results3 = mysql_query("select * from dialer_list_record_link WHERE link_record_id='$recordid' AND link_call_attempt='0' AND link_notes IS NOT NULL") or die(mysql_error());
$check = mysql_fetch_assoc($rs_results3);

if($check == NULL){echo "No notes have been posted.";}
$tabcount = 0;
$tabcount2 = 0;
$contentcount = 0;
$active = "class='active'";
$active2 = "active";


?>

<div id="notepage2" >

<div class="row" >
  <div class="tabbable tabs-left">
  
	<ul class="nav nav-tabs">
	  <?php
	  while ($note = mysql_fetch_assoc($rs_results1)){
	    if($tabcount != 0){$active = "";}
		echo "<li $active><a href=\"#T$tabcount\" data-toggle=\"tab\">" . $note['link_timestamp'] ."</a></li>";
		$tabcount = $tabcount + 1;
	 }
	  ?>
      </ul>
	  <div class="tab-content">
	  <?php
	  while ($note2 = mysql_fetch_assoc($rs_results2)){
	    if($tabcount2 != 0){$active2 = "";}
	    $ndate = $note2['link_timestamp'];
		echo "<div class=\"tab-pane $active2\" id=\"T$tabcount2\">";
		echo "<p>" . $note2['link_notes'] . "</p><br>";
		echo "<p><strong>Made By: </strong>" . $note2['link_action_by'] . "</p><br>";
		if($note2['link_action_by'] == $nam){
		echo "<a href=\"#\" class=\"btn-danger btn\" onclick=\"deleteNote('$ndate','$recordid')\">Delete Note</a>";
		}
		echo "</div>";
		$tabcount2 = $tabcount2 + 1;
	 }
	  ?>
	  </div>
 
 
  </div>
</div>
</div>

	