<?php
include ("db_session.php");
$users_prof = mysql_query("select * from dialer_list_user_profiles WHERE user_un='$sam'") or die(mysql_error());
$up = mysql_fetch_assoc($users_prof); 
$cbuck = $up['user_current_bucket'];
?>
<!doctype html>
 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

	

	<title>CSG FAST Dialer App</title>
 

	<!-- We highly recommend you use SASS and write your custom styles in sass/_custom.scss.
			 However, there is a blank style.css in the css directory should you prefer -->
	<link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="plugins/select2/select2.css">

	
	<script src="js/libs/modernizr-2.6.2.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="plugins/select2/select2.js" type="text/javascript"></script>
	
	<style>

	</style>

</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">FAST Dialer App</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
			
			  <?php if($up['user_current_bucket'] == NULL || $up['user_current_bucket'] == 'none') { ?>
              <!--<li id="mp1" class="active menupieces" onclick="newpage('homepiece','home_piece', 'mp1')"><a href="#">Home</a></li>-->
			  <?php if (in_array("WFM", $memberlist) || in_array("Admins", $memberlist) || in_array("Administrators", $memberlist)) { ?>
              <li id="mp2" class="menupieces mentog"><a href="#" onclick="newpage('rtable','records_table', 'mp2')">Records</a></li>
			  <li id="mp5" class="menupieces mentog"><a href="#" onclick="newpage('utable','users_table', 'mp5')">Users</a></li>
              <li id="mp3" class="menupieces mentog"><a href="#contact" onclick="newpage('listmanager','list_manager', 'mp3')">List Manager</a></li>
			  <?php } ?>
			  <li id="mp4" class="menupieces mentog" onclick="newpage('buckets','buckets', 'mp4')"><a href="#">Buckets</a></li>
			  <?php } ?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" title="<?php echo "Groups: "; foreach($memberlist as $group){echo $group . ", ";} ?>" data-toggle="dropdown"><?php echo $giv."'s";?> Profile <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li onclick="assign_bucket_rec('<?php echo $sam; ?>','0');"><a href="#" class="logout">Logout</a></li>
                  <!--<li class="nav-header">Notices</li>-->
             
                </ul>
              </li>
            </ul>
            <!--<form class="navbar-form pull-right" >
			  <input type="text" name="search_da_list" id="search_da_list" data-provide="typeahead" class="x-large" autocomplete="off" />
			  <input type="hidden" name="hidden_search_ID" id="hidden_search_ID"/>
              <button type="button" class="btn search_records">Search</button>
            </form>-->
			
			<select data-placeholder="Search..." style="width:300px; margin-top: 6px;" id="search_records" tabindex="10" class="navbar-form pull-right">
            <option value=""></option>
          </select>
	 </div>
			
			
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
     <br><br><br>
	 

	<div class="modal hide fade" id="affrecord" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 <div class="modal-header">
		   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		   <h3 id="myModalLabel">Aff Record</h3>
		 </div>
		 <div class="modal-body">
		   <div id="aff_content"></div>
		 </div>
		 <div class="modal-footer">
		   <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		   <!--<button class="btn btn-primary">Save changes</button>-->
		 </div>
	</div>
	
	<div class="modal hide fade" id="add_notes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
		   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		   <h3 id="myModalLabel">Add Notes</h3>
		 </div>
		 <div class="modal-body">
		   <div id="add_notes_content"></div>
		 </div>
		 <div class="modal-footer">
		   <div class="submitnote_holder"></div>
		 </div>
	</div>
	
	<div class="modal hide fade" id="view_notes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
		   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		   <h3 id="myModalLabel">View Notes</h3>
		 </div>
		 <div class="modal-body" style="padding-left: 40px;">
		   <div class="row text-center" >
			  <div class="nine columns center removenotemsg" style="cursor: pointer"; title="Close Message" onclick="closemsg('removenotemsg')" id="msg_view_notes"></div>
		   </div>
		   <div id="view_notes_content"></div>
		 </div>
		 <div class="modal-footer">
		   <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		 </div>
	</div>
	
	
     <?php if($up['user_current_bucket'] == NULL || $up['user_current_bucket'] == 'none') { ?>
	 <div class="row">
	 <div id="homepiece" class="pagepieces"></div>
	 </div>
	 <?php } ?>
	 
	 <?php if($up['user_current_bucket'] != NULL && $up['user_current_bucket'] != 'none') { ?>
	 <div class="row">
		 <div id="random_rec" class="pagepieces">
			 <div class="span12">
			 <?php
			 
			 $blist = mysql_query("select * from dialer_list WHERE type='$cbuck' AND assign_start_date<NOW() ORDER BY assign_start_date ASC") or die(mysql_error());
			   while ($bl = mysql_fetch_array($blist)) {
				 $bucketid = $bl['id'];
				  $twelvebefore = time() - 43200;
				  $disptypes_arr = array("fa01","fa02","fa03","fa06","fa07","fa09","fa12","fr01","fr02","fr03","fr04","fr05","fr08");
// add in fa12 to array -amber
				  $disptypes = '"' . implode('","', $disptypes_arr) . '"';
				  
				  $bucket_link_list = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
				  link_unix_timestamp>'$twelvebefore' AND
				  link_record_type='$cbuck' AND 
				  link_record_id='$bucketid' AND 
				  link_call_attempt='1'
				  ");
				  
				  $bucket_link_list2 = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
				  SUBSTRING(link_disp_type, 1, 4) IN ($disptypes) AND
				  link_record_type='$cbuck' AND 
				  link_record_id='$bucketid' AND 
				  link_call_attempt='1'
				  ");
				  
				  $bucket_link_list3 = mysql_query("SELECT * FROM dialer_list_record_link WHERE 
				  link_record_type='$cbuck' AND 
				  link_record_id='$bucketid' AND 
				  link_call_attempt='1'
				  ");
				  
				  $count_true_worked = (mysql_num_rows($bucket_link_list)) + (mysql_num_rows($bucket_link_list2));
				  $count_attempts = mysql_num_rows($bucket_link_list3);
				  
				  $users_link_list = mysql_query("SELECT * FROM dialer_list_user_profiles WHERE user_current_record='$bucketid'");
				  $count_assigned_users = mysql_num_rows($users_link_list); 
				  
				  
				  if($count_true_worked == 0 && $count_assigned_users == 0 && $count_attempts < 3)
				  { 
				     $gimme_name = $bl['name']; $gimme_phone = $bucketid; 
					 break;
				  };
			   }
			 ?>
			 <?php if($gimme_name != NULL){ ?>
			  <div class="row">
			    <div class="span12">
			      <div class="span4"><p><h5><strong>Name</strong></h5> <h4><?php echo ucwords($gimme_name); ?></h4></div><div class="span3"><h5><strong>Record ID</strong></h5> <h4><?php echo formatPhone($gimme_phone); ?></h4></p></div>
			      <div class="span4">
				  <br>
				     <button class="btn btn-info" onClick="recordshow('<?php echo $gimme_phone; ?>'); assign_bucket_rec('<?php echo $sam; ?>','<?php echo $gimme_phone; ?>');"><i class="icon-thumbs-up"></i>&nbsp;Give to me</button>
			         <button class="btn btn-warning" onClick="window.location.reload()"><i class="icon-thumbs-down"></i>&nbsp;Next Item</button>
				  </div>
			   </div>
			 </div>
			<?php } else {?>
			<div class="row">
			    <div class="span12">
			      <div class="span12 text-center"><h4>All Remaining Record are currently assigned to other users... please try again later</h4></div>
				  <div class="span12 text-center">
				     <div class="btn-group">
                       <button class="btn btn-warning" onClick="window.location.reload()"><i class="icon-retweet"></i> Retry</button>
					   <button class="btn btn-danger" onclick="assign_bucket('none','<?php echo $sam; ?>'); assign_bucket_rec('<?php echo $sam; ?>','0');"><i class="icon-remove"></i>&nbsp;Leave Bucket</button>
					   </div>
			      </div>			      
			   </div>
			 </div>
			<?php }?>
		 </div>
	 </div>
	 <?php } ?>
	 
     <div class="row"><div class="span12"><span id="filename"></span></div></div>
	 <div class="row">
	 <div id="rtable" class="pagepieces"></div>
	 </div>
	 
	 <div class="row">
	 <div id="utable" class="pagepieces"></div>
	 </div>
	 
	 <div class="row">
	 <div id="buckets" class="pagepieces">
		 
	 </div>
	 </div>
	 
	 <div class="row">
	 <div id="listmanager" class="pagepieces"></div>
	 </div>
     
	 
	  <div class="row">
		<div id="listrecord" class="span12 listrecord pagepieces"></div>
      </div>
	  
	  <div class="navbar navbar-fixed-bottom">
		<div class="navbar-inner">
		<?php if($up['user_current_bucket'] != NULL && $up['user_current_bucket'] != 'none') { ?>
		 <p class="brand">&nbsp;Bucket: <?php echo ucwords($up['user_current_bucket']); ?></p>
			<div class="btn-group">

			</div>
			<div class="pull-right">
			   <button class="btn btn-danger" onclick="assign_bucket('none','<?php echo $sam; ?>'); assign_bucket_rec('<?php echo $sam; ?>','0');"><i class="icon-remove"></i>&nbsp;Leave Bucket</button>
			   <a>&nbsp;</a>
			</div>
		<?php } ?>
		</div>
      </div>
	  <input type="text" style="display: none;" value="<?php echo $sam; ?>" id="un_hidden"/>
	  
      <hr>

      <!--<footer>
        <p>&copy; CSG FAST 2013</p>
      </footer>-->

    </div> <!-- /container -->


 
    <script src="js/bootstrap.js"></script>
    <script src="js/z_scripts.js"></script>

	<form action="index.php" method="GET" id="logoutform" style="display: none;">
	<input type="text" value="Logout" name="out" />
	</form>
</body>
</html>