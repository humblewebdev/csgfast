<?php

include ("dbc.php");

$con=mysqli_connect("localhost","root","QwertY4321", "test_bucketdb");

// $tableList = array();
// $res = mysqli_query($con,"SHOW TABLES");
// while($cRow = mysqli_fetch_array($res))
// {
// $tableList[] = $cRow[0];
// }

// foreach( $tableList as $table){
			// $sql = ("SELECT TOP 1 * FROM $table");
			// $data = mysqli_query($con, "SELECT * FROM $table WHERE id=1");
			
			// $result = mysqli_fetch_array($data);
			
			// $bucketype = $result['type'];
			// $bucketid = $result['id'];
			
			// $t_count = $result['total'];
			// $att_count = $result['attempted'];
			// $rt_count = $result['remaining_today'];
			// $c_count = $result['completed'];
			// $appt_count = $result['appts'];
			// $r_count = $result['remaining_overall'];
			
			// echo $bucketype."<br>";
			
// }
  
$bucket_results = mysql_query("SELECT DISTINCT(Type) AS type from dialer_list") or die(mysql_error());

while( $result = mysql_fetch_assoc($bucket_results)){

		//Create Table $rec
		$rec = $result["type"];
		$name = str_replace(' ', '_', $rec);
		$name = preg_replace('/__/', '_', $name);
		
		$sql="CREATE TABLE $name(id INT NOT NULL AUTO_INCREMENT, 
			PRIMARY KEY(id),
			phone_number VARCHAR(45), 
			name VARCHAR(90),
			type VARCHAR(45),
			assign_start_date VARCHAR(120),
			account VARCHAR(100),
			language VARCHAR(45),
			agent_name VARCHAR(90),
			post_timestamp VARCHAR(45),
			post_unixts VARCHAR(45),
			post_name VARCHAR(120),
			agent_id VARCHAR(120),
			agent_office_number VARCHAR(120),
			type_2 VARCHAR(120),
			language_preference VARCHAR(120),
			wireless VARCHAR(120),
			type2 VARCHAR(120),
			pay_plan VARCHAR(120),
			balance_due VARCHAR(120),
			due_date VARCHAR(120),
			action_date VARCHAR(120),
			record_type VARCHAR(120),
			notes mediumtext,
			disp_type VARCHAR(100),
			call_attempt VARCHAR(45),
			timestamp VARCHAR(45),
			unix_timestamp VARCHAR(45),
			action_by text,
			action_by_un text,
			assoc_post_name VARCHAR(120),
			total INT(20),
			attempted INT(20),
			remaining_today INT(20),
			completed INT(20),
			appts INT(20),
			remaining_overall INT(20))";
			
			mysqli_query($con,$sql);
			echo "$name<br>";
			
			$bucketype = $result["type"];
			$bucketlist = mysql_query("select * from dialer_list WHERE type='$bucketype' ");
			
			while( $item = mysql_fetch_assoc($bucketlist) ){
				$phone = $item['phone_number'];
				$name = $item['name'];
				
				$rec = $item["type"];
				$table = str_replace(' ', '_', $rec);
				$table = preg_replace('/__/', '_', $table);
		
				$type = $item['type'];
				$assign_start_date = $item['assign_start_date'];
				$account = $item['account'];
				$language = $item['language'];
				$agent_name = $item['agent_name'];
				$post_timestamp = $item['post_timestamp'];
				$post_unixts = $item['post_unixts'];
				$post_name = $item['post_name'];
				$agent_id = $item['agent_id'];
				$agent_office_number['agent_office_number'];
				$type_2 = $item['type_2'];
				$language_preference = $item['language_preference'];
				$wireless = $item['wireless'];
				$type2 = $item['type2'];
				$pay_plan = $item['pay_plan'];
				$balance_due = $item['balance_due'];
				$due_date = $item['due_date'];
				$action_date = $item['action_date'];
				
				mysqli_query($con, "INSERT INTO $table(phone_number, name,type,assign_start_date,account,language,agent_name,post_timestamp,post_unixts,post_name,agent_id,agent_office_number,type_2,language_preference,wireless,type2,pay_plan,balance_due,due_date,action_date,total,attempted,remaining_today,completed,appts,remaining_overall) 
																VALUES (
																'$phone',
																'$name',
																'$type',
																'$assign_start_date',
																'$account',
																'$language',
																'$agent_name',
																'$post_timestamp',
																'$post_unixts',
																'$post_name',
																'$agent_id',
																'$agent_office_number',
																'$type_2',
																'$language_preference',
																'$wireless',
																'$type2',
																'$pay_plan',
																'$balance_due',
																'$due_date',
																'$action_date',
																40,
																28,
																12,
																2,
																4,
																3)");
			}

}

?>

