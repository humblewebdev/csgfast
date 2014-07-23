<?php
//Uncomment these 3 lines when developing to display php errors on the screen
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$type = $_GET['type'];
if($type != "submit"){ exit; }

include 'db_connect.php';

/** Variable with html tags to be wrapped around response messages
    throughout the code in $alert ... $alertclose format 
**/

$debugmode = TRUE; //Set equal to true to troubleshoot issues on form signup submission

$erroralert = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
$debugalert = '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
$successalert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
$alertclose = '</div>';

$row_cnt_result = $mysqli->query('SELECT users_id FROM users ORDER BY users_id DESC LIMIT 1;');
/* determine the id to assign to this new user based on the latest id used in the users table +1 */
$row_cnt = $row_cnt_result->num_rows;
if ($row_cnt >= 0) {
   $num_of_rows = $row_cnt_result->fetch_row();
   $this_users_id = $num_of_rows[0] + 1;  //Here is where the user id gets assigned
} else
if($this_users_id == NULL){
echo "$erroralert Error: No user id can be assigned! Check SQL syntax. $alertclose";
exit;}

/************************* User ID has now been declared *********************************/

/************************* Iterate through each post submitted by form *******************/

$tablenames[] = NULL; //Set first value of array equal to null

foreach ($_POST as $field => $value){
      $fieldsplit = explode("--tosql_", $field); //Splits the name between the actual column name and the table name
	  
	  if(isset($fieldsplit[1])){ //If to verify that a tablename is included
	  $thistable = $fieldsplit[1]; //Grabs the table name
	  $field = $fieldsplit[0];  //Grabs the first part from name to make name match db column name
	  
	  /* Check if table name is in table array, if not add it as a new table to be queried to*/
	  if (!in_array("$thistable", $tablenames)){$tablenames[] = $thistable;}
	  
	  //Strips all characters except digits from phone numbers
	  if(strlen(preg_replace("/[^0-9]/","",$value)) == 10 || strlen(preg_replace("/[^0-9]/","",$value)) == 11){ $value = preg_replace("/[^0-9]/","",$value);}
	  
	  //Cleans up all values posted to this page to be safe for sql insertion
	  $value = trim($mysqli->real_escape_string($value));  //Filters values for mysql insertion later
	  
	  if($field == 'pwd'){ $value = PwdHash($value); } //Stores password in sha1 encoding
	  
	  ${"fields_$thistable"}[] = $field; //puts clean fields into array for this table
	  ${"vals_$thistable"}[] = "'" . $value . "'"; //puts clean values into array for this table
	  $$field = $value; //makes every post into a variable
	  
	  }
}

$tablecount = count($tablenames) -1; //Count the number of elements in the tablenames array minus the null element

/******* Re-Check to make sure another user is not registered with the same agent code or email address ****/
$dup_user_cnt_result = $mysqli->query("SELECT users_id FROM users WHERE email='$email' OR agent_code='$agent_code';");
$dup_user_cnt = $dup_user_cnt_result->num_rows;
if ($dup_user_cnt > 0 || $email == NULL) { echo "$erroralert Error: The agent code or email you supplied is already in use by another user! Please change this information. $alertclose"; exit; }
/******* End of Check for duplicate user *********/

$querysuccess = 0; //Initialize query success count to 0 to check against actual number of queries later

foreach($tablenames as $table){

if($table != NULL){ //Skips null values in array

if($table == "users"){ // Sets additional field inputs for insert query
$md5_id = md5($this_users_id);
$ipaddress = $_SERVER["REMOTE_ADDR"];

$extra_var = ", users_id, md5_id, reg_date, username, full_name, last_pwd_timestamp, users_ip, profile_pic"; //Additional cols and values that are not submitted via form
$extra_val = ", $this_users_id, '$md5_id', NOW(), '$email', '$firstname $lastname', NOW(), '$ipaddress', '" . $firstname . "_" . $lastname . "_" . $this_users_id .".jpg'";

} else{
$extra_var = ", users_id"; //Additional cols and values that are not submitted via form
$extra_val = ", $this_users_id";
}

$var_string = implode(', ', ${"fields_$table"}); //Implode the array and seperate by commas for proper insert query format
$value_string = implode(', ', ${"vals_$table"}); //Implode the arrays and seperate by commas for proper insert query format

$final_var_string = $var_string . $extra_var;
$final_value_string = $value_string . $extra_val;

$sql_insert_statement = "INSERT INTO $table($final_var_string) VALUES ($final_value_string);";

/***** Set Debug mode variable at the top of page equal to true to debug issues visually *******/
if($debugmode == TRUE){

echo "$debugalert<u><b>Debug Info of Executed Query:</b></u><br><br>";

}
if ($mysqli->query($sql_insert_statement)) {
    
	if($debugmode == TRUE){
    printf("%d Row inserted successfully.<br>", $mysqli->affected_rows);
	printf("%s<br>", $mysqli->info);
	printf ("New Record has id %d in $table table.", $mysqli->insert_id);
	}
	
	$querysuccess++;
	
} else { printf("$erroralert Error: %s $alertclose", $mysqli->error); }

    if($debugmode == TRUE){
	echo "<br><br>Table Name: $table<br>";
	echo "<br><br><b>Insert String:</b> $final_var_string<br> <b>Value String:</b> $final_value_string<hr>$alertclose";	
    }
}
/**************************** End of Debug Info ***************************************/

//Reset variables to NULL for use in next iteration
$extra_var = $extra_val = $var_string = $value_string = $final_var_string = $final_value_string = NULL;
$sql_insert_statement = "";
}

/** Final check to verify all queries have completed successfully.
    If the query success count($querysuccess) does not equal the 
	amount of insert queries performed, a query has failed.  If 
	a query has failed, removed all records created in all tables,
	even the successful ones to omit table inconcistancy.
**/
    
if($querysuccess == $tablecount){  
    $submission_result = "success";
	
	//include 'farm_profile_pic_download.php'; //Download users profile pic to server
	
	//added registration email 12/31/2013
	include '../email_widget/email_registration_script.php';
	
	if($debugmode == TRUE){
	echo "$successalert Success! User has been created with no errors. $alertclose";
	}
} else {
	foreach($tablenames as $table_del){
	if($table_del != NULL){ //Skips null values in array
      $delete = $mysqli->query("DELETE FROM $table_del WHERE users_id='$this_users_id'");
	    if($debugmode == TRUE){
	       echo "$erroralert Error: Not all queries were successful, created record from $table_del has been removed! $alertclose";
	    }
	  }
	}
	$submission_result = "failure";
	echo "$erroralert Registration has failed! Please contact the webmaster for assistance. $alertclose";
}

/**** The print statement below should be the only item echoed out on a successful completion,
      with the exception of echoed debug statements when $debug is set to "TRUE"
**/

echo $submission_result; //String returned to page caller

$mysqli->close(); //Close mysql connection that was started in the include file
?>