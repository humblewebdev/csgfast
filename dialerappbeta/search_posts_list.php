<?php
include ("db_session.php");
$term = $_GET['q'];
$json = $_GET['json'];
$rs_results = mysql_query("select * from dialer_list WHERE phone_number LIKE '%$term%' OR name LIKE '%$term%'") or die(mysql_error());
		
			while ($record = mysql_fetch_array($rs_results)) {
			  if($json == 1){
                $list_arr[] = array(
			      "id" => "{$record['id']}",
			      "name" => "{$record['name']} - {$record['phone_number']}",
			    );
			  } else { echo "<option value='{$record['id']}'>{$record['name']} - {$record['phone_number']} - {$record['type']}</option>"; }
			}
			
 if($json == 1){ echo json_encode($list_arr); }


?>