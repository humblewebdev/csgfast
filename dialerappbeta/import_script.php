<?php
include ("db_session.php");

$allowed = array('csv');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

    $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

    if(!in_array(strtolower($extension), $allowed)){
        echo '<a style="display: none";">{"status":"error"}</a>';
		echo '<strong>Invalid filetype!</strong>';
        exit;
    }
    
	$unixts = time();
    if(1 > 0){
    echo '<a style="display: none";">{"status":"success"}</a>';
		
	//get the csv file 
    $file = $_FILES['upl']['tmp_name']; 
    $handle = fopen($file,"r"); 
    $ignored = 0;
	$added = 0;
	$total = 0;
	$valid = 0;
	$filename = $_FILES['upl']['name']; 
	$postname = substr($filename, 0, -4)."_".$unixts;
	
	$row = 1;
	 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);  //Counts the number of fields in the row
        
		/* The first row should contain column name, therefore a 1-D column name array is made */
		if($row == 1){
        for ($c=0; $c < $num; $c++) {
		    $nice_csv_header = preg_replace('~[\W\s]~', '_', (mysql_real_escape_string(strtolower(trim($data[$c])))));
			$colarr[] = $nice_csv_header;
        }
		}
		
		/* 
		   After the first row, all other rows are record to be added and are put into
		   a single record array on an idividual basis and then inserted into the allrecs
		   array which will contain a multidimentional array with all the data to be traversed
		*/
		
		if($row > 1)
		{
	       for ($c2=0; $c2 < $num; $c2++) {
		        $nice_csv_fields = preg_replace("/[^a-zA-Z0-9 .]+/i", "",mysql_real_escape_string(trim($data[$c2])));
				$singlerec[] = array($colarr[$c2] => $nice_csv_fields);
		   };
	    }
	
		$allrecs[] = $singlerec;
		$singlerec = array();
	    $row++;
		
	 }
	 fclose($handle); //File is closed, records now accessed through allrecs multi-dimensional array
	
	$shift = array_shift($allrecs); //Removes column header from workable array
	
	/* Check to see if uploaded csv contains the following columns */
	
	$has_phone_flag = 0;
	$has_type_flag = 0;
	$has_name_flag = 0;
	$has_start_date_flag = 0;
	$has_agent_name_flag = 0;
	
	foreach($colarr as $colitem){
	  if($colitem == "phone_number"){
	   $has_phone_flag = 1;
	  }
	  if($colitem == "type"){
	   $has_type_flag = 1;
	  }
	  if($colitem == "name"){
	   $has_name_flag = 1;
	  }
	  if($colitem == "assign_start_date"){
	   $has_start_date_flag = 1;
	  }
	  if($colitem == "agent_name"){
	   $has_agent_name_flag = 1;
	  }
	  
	}
	
	if (($has_phone_flag + $has_type_flag + $has_type_flag + $has_start_date_flag + $has_agent_name_flag) != 5){
	
     echo "<strong>ERROR:</strong> Columns missing, nothing has been uploaded!<br><br>";
	 echo "CSV file must contain 'phone_number', 'type', 'assign_start_date', 'name' and 'agent_name' columns to be accepted<br>";
     exit;	 
	
	}
	
	if ($colarr[1] != 'phone_number'){
	
     echo "<strong>ERROR:</strong> Columns out of required order, nothing has been uploaded!<br><br>";
	 echo "CSV cile must contain the field 'phone_number' as the second column on the list<br>";
     exit;	 
	
	}
	
	/* End of Check for column names */
	
	$c_recs = count($allrecs);  //Total number of records to attempt to upload
	$c_cols = count($colarr);   //Number of columns in header to match up against
	
	if($c_cols > 15){
	  echo "<strong>ERROR:</strong> Too many columns in csv file!<br><br>";
	  echo "Max number of columns currently allowed is 15<br>";
      exit;
	}
	
	/* Put current dialer_list db table columns into sqlcols array */
	$searchcols = mysql_query("SHOW COLUMNS FROM dialer_list") or die(mysql_error());
	if (mysql_num_rows($searchcols ) > 0) {
		while ($row = mysql_fetch_assoc($searchcols)) {
			$sqlcols_array[] = ($row['Field']); //Puts list cols into array to compare against
		}
	}
	
	$sql_cols_added = 0; //Initialize cols added to db table to 0
	
	/* If a column from the csv file is not in the db table, add it */
	foreach($colarr as $colitem){
	  if (!in_array("$colitem", $sqlcols_array))
		{
		$result = mysql_query("ALTER TABLE dialer_list ADD $colitem VARCHAR(120)");
		$sql_cols_added++;
		}
	}
	
	$comma_separated_cols = implode(", ", $colarr); //columns formatted for mysql insertion
	
	for($zz = 0; $zz < $c_recs; $zz++){
	  for($cc = 0; $cc < $c_cols; $cc++){
	   /* Build 1-D single record array of individual rows with all col items inside */
	   $single_rec_arr[] = "'{$allrecs[$zz][$cc][$colarr[$cc]]}'";
	  }
	   /* 1-D single record array formatted for mysql insertion */
	   $comma_separated_single = implode(",", $single_rec_arr);
       
	   /* Grab phone number from record and check if it exists in table already */
	   $phonenum = str_replace("'", "", $single_rec_arr[1]);
	   if($phonenum != NULL){ //Ignores running query for records with no phone numbers
	   $search_duplicate = mysql_query("SELECT count(*) FROM dialer_list WHERE phone_number='$phonenum'");
	   $sd_count = mysql_result($search_duplicate, 0); 
	   } else{$sd_count=0;}
	   
	   /* If the record does not exist in the table continue with insertion */
	   /* Change 2 to 1 to ignore duplicates */
	   if($sd_count < 2){

	    mysql_query("INSERT INTO dialer_list ($comma_separated_cols, post_timestamp, post_unixts, post_name) VALUES 
                ( 
                    $comma_separated_single,
					NOW(),
					'$unixts',
					'$postname'
                ) 
            ") or die(mysql_error());
		$added++; 
		$total++;
		
		/* Put added records into a new array to create final saved csv file later */
		$imported_single_records[] = $single_rec_arr;
		} else{ $ignored++; $total++;}
	  
	  /* Reset array to null to be ready for next item in loop */
	  $single_rec_arr = array();
	 
	}
	
	/* File is finished parsing and will now be saved into uploads directory with only added records*/
	if($added > 0){ //If no records were added, no file will be created

	  $fp = fopen('uploads/'.substr($_FILES['upl']['name'], 0, -4)."_".$unixts.".csv", 'w');
      fputcsv($fp, $colarr); //Writes csv headers to file
	    foreach ($imported_single_records as $fields) {
		   
		   $clean_fields = preg_replace("/[^a-zA-Z0-9 .]+/i", "", $fields);
           fputcsv($fp, $clean_fields); //Writes csv record by record cleanly
       }
      fclose($fp);
	  
	  
	}
	
	/* Print Results of import to the screen */
	echo "<strong>Import Results</strong><br>Total: $total";
	echo "&nbsp;&nbsp;Added: $added";
	echo "&nbsp;&nbsp;Ignored: $ignored";
	if($sql_cols_added != 0){echo "<br><br>Table Columns added: $sql_cols_added";}
	
	
    //var_dump($allrecs); //Reveal full array worked with
    
        exit;
    }
}

?>
