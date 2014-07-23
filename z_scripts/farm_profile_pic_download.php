<?php
    
	//If this page is called from the edit profile page of farmers dashboad, manually set variables here, else grab from parent include file
	if($_GET['redo'] == 'yes'){
	$website = $_POST['web'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$this_users_id = $_POST['uid'];
	}
	
	$url = $website;
	 
	$ch1 = curl_init($url);
	curl_setopt($ch1, CURLOPT_NOBODY, true);
	curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch1);
	$retcode = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
	curl_close($ch1);
	if (200==$retcode) {// Farmers Agent website was found, continue
	

	 $pull_pic = parse_url($url, PHP_URL_PATH);
     $url_pic = "http://www.farmersagent.com/Assets/Agents$pull_pic/ProfileImages$pull_pic.jpg";
	 
	 
		$url2 = "http://www.farmersagent.com$pull_pic/Contact-Me.htm";

		$sites_html = file_get_contents($url2);

		$html = new DOMDocument();
		@$html->loadHTML($sites_html);
		$meta_og_img = null;
		$meta_og_title = null;
		//Get all meta tags and loop through them.
		foreach($html->getElementsByTagName('meta') as $meta) {
			//If the property attribute of the meta tag is og:image
			if($meta->getAttribute('property')=='og:image'){ 
				//Assign the value from content attribute to $meta_og_img
				$meta_og_img = $meta->getAttribute('content');
			}
			if($meta->getAttribute('property')=='og:title'){ 
				//Assign the value from content attribute to $meta_og_img
				$meta_og_title = $meta->getAttribute('content');
			}
			if($meta->getAttribute('property')=='og:url'){ 
				//Assign the value from content attribute to $meta_og_img
				$meta_og_url = $meta->getAttribute('content');
			}
			if($meta->getAttribute('property')=='og:description'){ 
				//Assign the value from content attribute to $meta_og_img
				$meta_og_desc = $meta->getAttribute('content');
			}
		}
	 
     $picname = $firstname . "_" . $lastname . "_" . $this_users_id . ".jpg";
	 $echo_url = $meta_og_img;
	 if($meta_og_img == NULL){$echo_url = "http://www.farmersagent.com/Images/FarmersLogo_placements.jpg";}
	 $iid = $this_users_id;
	 
	 //Done through dosignup script now
	 //if($_GET['redo'] != 'yes'){
	 //$mysqli->query("UPDATE users SET profile_pic='$picname' WHERE users_id='$iid'")or die($mysqli->error); 
	 //}

	 $output_filename = "../profile_pics/$picname";

	$host = $echo_url;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $host);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, false);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$result = curl_exec($ch);
	curl_close($ch);

	//print_r($result); // prints the contents of the collected file before writing..


	// the following lines write the contents to a file in the same directory (provided permissions etc)
	$fp = fopen($output_filename, 'w');
	fwrite($fp, $result);
	fclose($fp);
	
	}

?>