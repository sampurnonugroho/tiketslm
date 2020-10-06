<?php
	// $new_image_name = "newimage_".mt_rand().".jpg";

	// move_uploaded_file($_FILES["file"]["tmp_name"], 'server/upload/'.$new_image_name);
	// echo $new_image_name ;
	
	// print_r($_REQUEST['wsid']);
	
	$wsid = $_REQUEST['wsid'];
	$name = $_REQUEST['name'];
	if($wsid!=="") {
		$dir = "server/upload/".$wsid;
		if( is_dir($dir) === false ) {
			mkdir($dir);
		}
		
		// Set new file name
		$new_image_name = $name.".jpg";
		
		// upload file
		move_uploaded_file($_FILES["file"]["tmp_name"], $dir.'/'.$new_image_name);

		echo "\n".$new_image_name;
	}