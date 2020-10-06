<?php
// 	header("Access-Control-Allow-Origin: *");
// 	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

// 	$file = 'tes.txt';
// 	$fp = fopen($file, 'a') or die('Cannot open file:  '.$file);
// 	$data = 'Line no.1: Data on first line'. "\n";
// 	$data = json_encode($_POST['data']). "\n";
	
// 	// $data = file_get_contents('php://input');
// 	// $array = json_decode($data, true);
// 	// $data = $array[0]['time'];
	
// 	if($_POST['data']!==null) {
// 		fwrite($fp, $data);
// 	}
// 	fclose($fp);
	
// 	echo "AAA";

echo date("Y-m-d H:i:s");
date_default_timezone_set("Asia/Bangkok");
echo date("Y-m-d H:i:s");