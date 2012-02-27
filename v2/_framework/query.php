<?php
	require_once("lib/json/json.php");
	
	include('lib/data.php');

	function has($haystack,$needle){
		return strpos(strtolower($haystack),strtolower($needle))!==FALSE;
		}
	function matches($row,$q,$ignores=array()){
		foreach($row as $k=>$v){
			//if(in_array($k,$ignores)){ continue; }
			if(has($v,$q)){ return TRUE; }
			}
		return FALSE;
		}
	
	function getReq($key,$default=null){
		if(isset($_REQUEST[$key])){ return trim($_REQUEST[$key]); }
		return $default;
		}
	
	function getMatches($data){
		$matched_rows = array();
		$q = getReq('q');
		$i = getReq('i');	
		if($q==null||$q==''){ return $matched_rows; }
		if($i==null){ $i='email,has_photo,uid'; }
		
		$qp = explode(' ',$q);
		$if = explode(',',$i);
		
		foreach($data as $row){
			$flag = true;
			foreach($if as $ip){ //strip out ignored info
				if(isset($row[$ip])){
					unset($row[$ip]);
					}
				}
			foreach($qp as $part){
				$part = trim($part);
				if(strlen($part)==0){ continue; }
				if(!matches($row,$part,$if)){ $flag=false; break; }
				}
			if($flag || $q=='*'){ array_push($matched_rows,$row); }
			}
		return $matched_rows;
		}

	$sortOrder = array("name_last","name_first","year_expected");
	
	function multiObjectComparator($A,$B){
		global $sortOrder;
		foreach($sortOrder as $sq){ //loop through criteria
			if(!isset($A[$sq]) || !isset($B[$sq])){ continue; }
			$r = strcasecmp($A[$sq],$B[$sq]);
			if($r==0){ continue; }
			return $r;
			}
		return 0;
		}
	
	function sortMatches($matches){
		usort($matches,"multiObjectComparator");
		return $matches;
		}
	
	$matches = getMatches($data);
	$matches = sortMatches($matches);
	print json_encode($matches);

	?>
