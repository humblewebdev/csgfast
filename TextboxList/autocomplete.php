<?php

include '../dbc.php';

$req = "SELECT firstname,lastname,user_email,id "
	."FROM users "; 

$query = mysql_query($req)or die(mysql_error()); 

while($row = mysql_fetch_array($query))
{
	$names[] = ("{$row['firstname']} {$row['lastname']} +{$row['user_email']}+{$row['id']}");
}
//var_dump($names);
//echo "<br><br><br><br><br><br><br><br><br>";

// TextboxList Autocomplete sample data for queryRemote: true (server is queried as user types)

// get names  (eg: database)
// the format is: 
// id, searchable plain text, html (for the textboxlist item, if empty the plain is used), html (for the autocomplete dropdown)
/*
$response = array();
$names = array('Abraham Lincoln', 'Adolf Hitler', 'Agent Smith', 'Agnus', 'AIAI', 'Akira Shoji', 'Akuma', 'Alex', 'Antoinetta Marie', 'Baal', 'Baby Luigi', 'Backpack', 'Baralai', 'Bardock', 'Baron Mordo', 'Barthello', 'Blanka', 'Bloody Brad', 'Cagnazo', 'Calonord', 'Calypso', 'Cao Cao', 'Captain America', 'Chang', 'Cheato', 'Cheshire Cat', 'Daegon', 'Dampe', 'Daniel Carrington', 'Daniel Lang', 'Dan Severn', 'Darkman', 'Darth Vader', 'Dingodile', 'Dmitri Petrovic', 'Ebonroc', 'Ecco the Dolphin', 'Echidna', 'Edea Kramer', 'Edward van Helgen', 'Elena', 'Eulogy Jones', 'Excella Gionne', 'Ezekial Freeman', 'Fakeman', 'Fasha', 'Fawful', 'Fergie', 'Firebrand', 'Fresh Prince', 'Frylock', 'Fyrus', 'Lamarr', 'Lazarus', 'Lebron James', 'Lee Hong', 'Lemmy Koopa', 'Leon Belmont', 'Lewton', 'Lex Luthor', 'Lighter', 'Lulu');
*/

// make sure they're sorted alphabetically, for binary search tests
sort($names);

$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

foreach ($names as $i => $name)
{
	if (!preg_match("/\b$search/i", $name)) continue;
	
	$user_search = $name;
	$user_first1 = explode(" ", $user_search);
	$user_first = $user_first1[0];
	$user_last = $user_first1[1];
	$email1 = explode("+", $user_search);
	$email = trim($email1[1], " ");
	$rowid = $email1[2];
	$fullname = $user_first . " " . substr($user_last, 0, 1) . "." ;

	$response[] = array($rowid,  $user_first, $fullname, "$fullname  |  $email");
}

header('Content-type: application/json');
if($response != NULL){
echo json_encode($response);
}
?>