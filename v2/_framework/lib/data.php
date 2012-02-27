<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}
	
	require_once("$root/_framework/lib/UserManager.php");

	$data = UserManager::getAllData();
	for($i=0; $i<count($data); $i++){
		$e = $data[$i];
		$e = DataManager::maskFields($e);
		$hsfile = "../_headshots/uid/".$e['uid'].".jpg";
		if(file_exists($hsfile)){ $e['photo_path'] = "_headshots/uid/".$e['uid'].".jpg";
			}else{ $e['photo_path'] = "_headshots/unknown.jpg";
			}
		$data[$i] = $e;
		}
	
	//print "<pre>";print_r($data); //DEBUG message
	
	?>