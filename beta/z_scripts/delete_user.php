<?php
include 'db_connect.php';

/**
* Script to insert users from the old database (nssoluti) User's table
* to the new one in csg_fast_prod 
*
*  NOTES of things to do or consider:
*
*  in farm_inctact_info: Skill needs attention, what is poc_contactcode
*
*
*
*
*  FIRST THING TO NOTICE:  Deleting all rows in each table!!!!    */

$users_id = 82;

$delete_qry = "DELETE FROM users WHERE users_id = ".$users_id;
if(!$mysqli->query($delete_qry)){
	printf("Error: %s $full_name<br>", $mysqli->error);
	}else{echo "Deleted users<br>";}

	$delete_qry = "DELETE FROM farm_agent_info WHERE users_id = ".$users_id;
 if(!$mysqli->query($delete_qry)){printf("Error: %s $full_name<br>", $mysqli->error);
	}else{echo "Deleted Farm_agent_info<br>";} 

$delete_qry = "DELETE FROM farm_agent_staff_info WHERE users_id = ".$users_id;
 if(!$mysqli->query($delete_qry)){printf("Error: %s $full_name<br>", $mysqli->error);
	}else{echo "Deleted Farm_staff_info<br>";} 

$delete_qry = "DELETE FROM farm_incontact_info WHERE users_id = ".$users_id;
 if(!$mysqli->query($delete_qry)){printf("Error: %s $full_name<br>", $mysqli->error);
	}else{echo "Deleted Farm_incontact_info<br>";} 

$delete_qry = "DELETE FROM products_ext WHERE users_id = ".$users_id;
 if(!$mysqli->query($delete_qry)){printf("Error: %s $full_name<br>", $mysqli->error);
	}else{echo "Deleted products_ext<br>";} 




?>