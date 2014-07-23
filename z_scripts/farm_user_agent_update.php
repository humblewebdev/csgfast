<?php
//Uncomment these 3 lines when developing to display php errors on the screen
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
*/

session_start();
if(isset($_SESSION['invalid'])){
$invalid = $_SESSION['invalid'];
}else{
$invalid = 0;
}
if(isset($_SESSION['email'])){
$user_email = $_SESSION['email'];
}

if(isset($_SESSION['user_name'])){
$user_logged_in = $_SESSION['user_name'];
}

if(isset($_SESSION['user_id'])){
$user_id_logged_in = $_SESSION['user_id'];
}
session_write_close();

include 'db_connect.php';

$debugmode=FALSE;

$erroralert = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
$debugalert = '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
$successalert = '<div class="alert alert-success"><i class="icon-ok-circle"></i> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
$alertclose = '</div>';

$type = $_GET['type'];

/*----------------------------------------------------------------------------------------------
-
- 											SUBMIT (general)
-
-
-------------------------------------------------------------------------------------------------*/

if($type == "submit"){ 

if($invalid){
	echo $erroralert."Invalid Fields".$alertclose;
} else {

$users_id = $_POST['users_id'];
//$portal_update_time = $_POST['portal_update_time'];
//$current_time = $_POST['portal_update_time--tosql_farm_agent_info'];
$current_time = date("Y-m-d H:i:s");
$tablenames[] = NULL; //Set first value of array equal to null

foreach ($_POST as $field => $value){
      $fieldsplit = explode("--tosql_", $field); //Splits the name between the actual column name and the table name
	  
	  if(isset($fieldsplit[1])){ //If to verify that a tablename is included
	  $thistable = $fieldsplit[1]; //Grabs the table name
	  $field = $fieldsplit[0];  //Grabs the first part from name to make name match db column name
	  
	  /* Check if table name is in table array, if not add it as a new table to be queried to*/
	  if (!in_array("$thistable", $tablenames)){$tablenames[] = $thistable;}
	  
	  //Strips all characters except digits from phone numbers, only for number fields in incontact_agent_info
	  $phone_array = array("poc", "mainphone", "secondphone", "cellphone", "rback", "fax");
	  if(in_array($field, $phone_array)){
			//echo "field = $field<br>";
			if(strlen(preg_replace("/[^0-9]/","",$value)) == 10 || strlen(preg_replace("/[^0-9]/","",$value)) == 11){ $value = preg_replace("/[^0-9]/","",$value);}
	  }
	  //Cleans up all values posted to this page to be safe for sql insertion
	  $value = trim($mysqli->real_escape_string($value));  //Filters values for mysql insertion later
	 // echo "col = $field, value = $value<br>";
	  if($field == 'pwd'){ $value = PwdHash($value); } //Stores password in sha1 encoding
	  
	  ${"fields_$thistable"}[] = $field; //puts clean fields into array for this table
	  ${"vals_$thistable"}[] = "'" . $value . "'"; //puts clean values into array for this table
	  $$field = $value; //makes every post into a variable
	  
	  }
}

$tablecount = count($tablenames) -1; //Count the number of elements in the tablenames array minus the null element

$querysuccess = 0; //Initialize query success count to 0 to check against actual number of queries later
$queryignored = 0; //Initialize query ignored count to 0 to check against actual number of queries later

foreach($tablenames as $table){

if($table != NULL){ //Skips null values in array

foreach(array_combine(${"fields_$table"}, ${"vals_$table"}) as $col => $asset){
$old_asset_statement = "SELECT $col FROM $table WHERE users_id='$users_id'";
$old_result = $mysqli->query($old_asset_statement);
$old_asset_row = mysqli_fetch_array($old_result);

//echo "col=$col, asset = $asset<br>";

$sql_update_statement = "UPDATE $table SET $col=$asset WHERE users_id='$users_id';";

/***** Set Debug mode variable at the top of page equal to true to debug issues visually *******/
	if($debugmode == TRUE){

		echo "$debugalert<u><b>Debug Info of Executed Query:</b></u><br><br>";

	}
	if ($mysqli->query($sql_update_statement)) {
	
		//** added 2/12/2014 ---> area  to add in mod time/ mod by/ mod desc **//
		//if query was successfull, update the mod time date and by column in users table
		$asset2 = str_replace("'", "", $asset);
		$mod_desc = $col. " changed from ".$old_asset_row["$col"]. " to ". $asset2;
		if($mysqli->query("UPDATE users SET last_mod_by='$user_logged_in', mod_desc='$mod_desc', mod_time='$current_time' WHERE users_id='$users_id'")){
			//echo"worked!";
		}else{
			//echo "$col <br> $asset2";
			echo $mysqli->error;
		}
		
		//********** end of add remove if it breaks anything *****************//
		
		//** removed the portal_update_time thing from home_page_farm.js to make the script work **//
		//"&portal_update_time--tosql_farm_agent_info=" + current_time,
		
		$mysqli->query("UPDATE farm_agent_info SET portal_update_time='$current_time' WHERE users_id='$users_id'");
		
		/*********** remove this section if it doesnt work ..****************/
		
		
		/**-------------------- update the AUDIT TRAIL ---------------------**/
		
		$browserinfo = getBrowser();
		$browser_type = $browserinfo['name'];
		$browser_version = $browserinfo['version'];
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$audit_insert= "INSERT INTO audit_trail (affected_user_id, resp_user_id, name_of_table, field_name, field_value, ip_address, browser_type, browser_version, timestamp) values('$users_id','$user_id_logged_in','$table','$col','$asset2','$ip_address','$browser_type','$browser_version','$current_time')";
		if($mysqli->query($audit_insert)){
		
		}else{
			echo $mysqli->error;
		}
		/** --------------- end of updating audit trail --------------------**/
		
		if($debugmode == TRUE){
		printf("%d Row updated successfully.<br>", $mysqli->affected_rows);
		
		printf("%s<br>", $mysqli->info);
		}
		if($mysqli->affected_rows == 0){ $queryignored++; }
		$querysuccess++;
		
	} else { printf("$erroralert Error: %s $alertclose", $mysqli->error); }

		if($debugmode == TRUE){
		echo "<br><br>Table Name: $table<br>";
		echo "<br><br><b>Update Field:</b> $col<br> <b>Value String:</b> $asset<hr>$alertclose";	
		}
	} //End loop of update statements
}
/**************************** End of Debug Info ***************************************/

//Reset variables to NULL for use in next iteration
$sql_update_statement = "";
}


/** Final check to verify all queries have completed successfully.
    If the query success count($querysuccess) does not equal the 
	amount of insert queries performed, a query has failed.  If 
	a query has failed, removed all records created in all tables,
	even the successful ones to omit table inconcistancy.
**/
    
if((intval($querysuccess)-intval($queryignored)) >= 1){
  $submission_result = "$successalert Your profile has been successfully updated. $alertclose";
  
  if($debugmode == TRUE){
	echo "$successalert Success," . (intval($querysuccess)-intval($queryignored)) . " field(s) updated and $queryignored fields ignored... $alertclose";
	}
} else if((intval($querysuccess)-intval($queryignored)) == 0){
   $submission_result = "$successalert No fields were changed, nothing has been updated. $alertclose";
}

else {
	
	$submission_result = "failure";
	echo "$erroralert Update has failed! Please contact the webmaster for assistance. $alertclose";
}

/**** The print statement below should be the only item echoed out on a successful completion,
      with the exception of echoed debug statements when $debug is set to "TRUE"
**/

echo $submission_result; //String returned to page caller

$mysqli->close(); //Close mysql connection that was started in the include file

}  // end fix yoru shit

}  // submit end


/*----------------------------------------------------------------------------------------------
-
- 											SUBMIT PRODUCT
-
-
-------------------------------------------------------------------------------------------------*/


if($type == "submit_product"){ 

if($invalid){
	echo $erroralert."Invalid Fields".$alertclose;
} else {

$users_id = $_POST['users_id'];
$tablenames[] = NULL; //Set first value of array equal to null

foreach ($_POST as $field => $value){
      $fieldsplit = explode("--tosql_", $field); //Splits the name between the actual column name and the table name
	  
	  if(isset($fieldsplit[1])){ //If to verify that a tablename is included
	  $thistable = $fieldsplit[1]; //Grabs the table name
	  $field = $fieldsplit[0];  //Grabs the first part from name to make name match db column name
	  
	  /* Check if table name is in table array, if not add it as a new table to be queried to*/
	  if (!in_array("$thistable", $tablenames)){$tablenames[] = $thistable;}
	  
/***********  2/21/2014 TOOK THIS SECTION OUT because it removes all t information if a number is 10 or 11 chars long **************************/
	  //Strips all characters except digits from phone numbers
	  //if(strlen(preg_replace("/[^0-9]/","",$value)) == 10 || strlen(preg_replace("/[^0-9]/","",$value)) == 11){ $value = preg_replace("/[^0-9]/","",$value);}
	  
	  //Cleans up all values posted to this page to be safe for sql insertion
	  $value = trim($mysqli->real_escape_string($value));  //Filters values for mysql insertion later
	  
	  if($field == 'pwd'){ $value = PwdHash($value); } //Stores password in sha1 encoding
	  
	  ${"fields_$thistable"}[] = $field; //puts clean fields into array for this table
	  ${"vals_$thistable"}[] = "'" . $value . "'"; //puts clean values into array for this table
	  $$field = $value; //makes every post into a variable
	  
	  }
}

$tablecount = count($tablenames) -1; //Count the number of elements in the tablenames array minus the null element

$querysuccess = 0; //Initialize query success count to 0 to check against actual number of queries later
$queryignored = 0; //Initialize query ignored count to 0 to check against actual number of queries later

foreach($tablenames as $table){

if($table != NULL){ //Skips null values in array

foreach(array_combine(${"fields_$table"}, ${"vals_$table"}) as $col => $asset){
$old_asset_statement = "SELECT $col FROM $table WHERE users_id='$users_id'";
$old_result = $mysqli->query($old_asset_statement);
$old_asset_row = mysqli_fetch_array($old_result);

/*//create an array of status
$status_arr = array(0 => "m_status", 1 => "t_status", 2 => "w_status", 3 => "r_status", 4 => "f_status", 5 => "sa_status", 6 => "su_status");
foreach($status_arr as $val){
	if(strcmp(
	
}
*/

$sql_update_statement = "UPDATE $table SET $col=$asset WHERE users_id='$users_id';";


/***** Set Debug mode variable at the top of page equal to true to debug issues visually *******/
	if($debugmode == TRUE){

		echo "$debugalert<u><b>Debug Info of Executed Query:</b></u><br><br>";

	}
	
	//This is the area that does the query update... add email notification to it. 
	if ($mysqli->query($sql_update_statement)) {
	
		//** added 2/12/2014 ---> area  to add in mod time/ mod by/ mod desc **//
		//if query was successful, update the mod time date and by column in users table
		$asset2 = str_replace("'", "", $asset);
		$mod_desc = $col. " changed from ".$old_asset_row["$col"]. " to ". $asset2;
		if($mysqli->query("UPDATE users SET last_mod_by='$user_logged_in', mod_desc='$mod_desc', mod_time='$current_time' WHERE users_id='$users_id'")){
			//echo"worked!";
		}else{
			//echo "$col <br> $asset2";
			echo $mysqli->error;
		}
		
		/**-------------------- update the AUDIT TRAIL ---------------------**/
		
		$browserinfo = getBrowser();
		$browser_type = $browserinfo['name'];
		$browser_version = $browserinfo['version'];
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$audit_insert= "INSERT INTO audit_trail (affected_user_id, resp_user_id, name_of_table, field_name, field_value, ip_address, browser_type, browser_version, timestamp) values('$users_id','$user_id_logged_in','$table','$col','$asset2','$ip_address','$browser_type','$browser_version','$current_time')";
		if($mysqli->query($audit_insert)){
		
		}else{
			echo $mysqli->error;
		}
		/** --------------- end of updating audit trail --------------------**/
		
		//********** end of add remove if it breaks anything *****************//
	
		
		if($debugmode == TRUE){
		printf("%d Row updated successfully.<br>", $mysqli->affected_rows);
		
		printf("%s<br>", $mysqli->info);
		}
		if($mysqli->affected_rows == 0){ $queryignored++; }
		$querysuccess++;
		
	} else { printf("$erroralert Error: %s $alertclose", $mysqli->error); }

		if($debugmode == TRUE){
		echo "<br><br>Table Name: $table<br>";
		echo "<br><br><b>Update Field:</b> $col<br> <b>Value String:</b> $asset<hr>$alertclose";	
		}
	} //End loop of update statements
	
	
}
/**************************** End of Debug Info ***************************************/

//Reset variables to NULL for use in next iteration
$sql_update_statement = "";
}

$product_chosen = $_POST['product_chosen'];

		/*******************************************************************
		*
		*  add in email notifying them of their recent sign up
		*
		********************************************************************/
		// 1: grab the product chosen from the post
		switch($product_chosen){
			case 1:
				$temp_id = 32;
				break;
			case 2: 
				$temp_id = 29;
				break;
			case 3:
				$temp_id = 30;
				break;
		}
		
		// 2: query the correct email temp based on the product chosen
		$email_template_qry  = "SELECT * FROM email_templates WHERE id_email_template = $temp_id";
		$email_template_result = $mysqli->query($email_template_qry);
		$email_template_row = mysqli_fetch_array($email_template_result);
		
		//3: query user's information to send personalized email
		$users_qry = "SELECT * FROM users c
					JOIN farm_incontact_info f 
					ON c.users_id = f.users_id 
					AND c.users_id = '$users_id' 
					JOIN farm_agent_info a 
					ON a.users_id = f.users_id";
		$users_info_result = $mysqli->query($users_qry);
		$users_row = mysqli_fetch_array($users_info_result);
		
		//4: Store the retrieved information into variables which will be used in template.
		$first = $users_row['firstname'];
		$last = $users_row['lastname'];
		$full = $users_row['full_name'];
		$un = $users_row['username'];
		$un = $users_row['username'];
		$timezone = $users_row['timezone'];
		$email = $users_row['email'];
		$mainline = $users_row['mainphone'];
		$pif = $users_row['pif'];
		$agentcode = $users_row['agent_code'];
		$regdate = $users_row['reg_date'];
		$approvedate = $users_row['start_request_date'];
				
		//5. Store email information into variables.
		$reg_title = $email_template_row['template_title'];
		$reg_message = html_entity_decode($email_template_row['template_content']);
		$headers =	'From: FAST_Support@csg-email.com' . "\r\n" .
		'Reply-To: FAST_Support@csg-email.com' . "\r\n" .
		'BCC: amberb@csg-email.com' . "\r\n" .
		'Content-type: text/html' . "\r\n";
		
		//replace all the placeholders with the user's information
		preg_match_all( '#\[.+\]#U', $reg_message, $shortcodes );
		foreach($shortcodes[0] as $replace){
			$trimmed_sc = ereg_replace("[^A-Za-z0-9-]", "", $replace); //Cuts off left and right bracket to use as clean variable name
			$new_bodytext = str_replace("$replace", $$trimmed_sc, $reg_message);
			$reg_message = $new_bodytext;
		}

		//test mail
		if(mail($user_email, $reg_title, $reg_message, $headers)){
			echo "email has been sent!";
		}else{
			echo "email failed";
		}

/** Final check to verify all queries have completed successfully.
    If the query success count($querysuccess) does not equal the 
	amount of insert queries performed, a query has failed.  If 
	a query has failed, removed all records created in all tables,
	even the successful ones to omit table inconcistancy.
**/
    
if((intval($querysuccess)-intval($queryignored)) >= 1){
  $submission_result = "$successalert Your profile has been successfully updated. $alertclose";
  
  if($debugmode == TRUE){
	echo "$successalert Success," . (intval($querysuccess)-intval($queryignored)) . " field(s) updated and $queryignored fields ignored... $alertclose";
	}
} else if((intval($querysuccess)-intval($queryignored)) == 0){
   $submission_result = "$successalert No fields were changed, nothing has been updated. $alertclose";
}

else {
	
	$submission_result = "failure";
	echo "$erroralert Update has failed! Please contact the webmaster for assistance. $alertclose";
}

/**** The print statement below should be the only item echoed out on a successful completion,
      with the exception of echoed debug statements when $debug is set to "TRUE"
**/

echo $submission_result; //String returned to page caller

$mysqli->close(); //Close mysql connection that was started in the include file

}  // end fix it!


}  // submit product end

/*----------------------------------------------------------------------------------------------
-
- 											SUBMIT PHOTO
-
-
-------------------------------------------------------------------------------------------------*/

if($type == "submit_photo"){ 

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$uid = $_POST['uid'];

$referexp = explode("&picstatus" , $_SERVER['HTTP_REFERER']);
$cleanrefer = $referexp[0];

if (1)
  {
  if ($_FILES["file"]["error"] > 0)
    {
    header('Location: ' . $cleanrefer . "&picstatus=error_noneselected");
    }
  else
    {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

      move_uploaded_file($_FILES["file"]["tmp_name"],
      "../profile_pics/" . $firstname."_".$lastname."_".$uid.".jpg");
      echo "Stored in: " . "../profile_pics/" . $firstname."_".$lastname."_".$uid.".jpg";
      
	  header('Location: ' . $cleanrefer . "&picstatus=success&filename=" . $_FILES["file"]["name"] . "&size=" . ($_FILES["file"]["size"] / 1024));
    }
  }
else
  {
  header('Location: ' . $cleanrefer . "&picstatus=error_invalid");
  }

}
?>