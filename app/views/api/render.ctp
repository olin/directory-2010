<?php
	function csvPrint($lines, $query){
		//array_unshift($lines,);
		$resp = fopen("php://output", 'w');
		if(!isset($_GET['download'])){
			fputcsv($resp,array(count($lines)-1,$query));
		}
		foreach($lines as $line){
			fputcsv($resp,$line);
		}
	}
	
	function formatLeaves($leaves){
		$rtn = '';
		foreach($leaves as $attr=>$value){
			if(strlen($rtn)>0){ $rtn.=' '; }
			$rtn .= $attr.'="'.htmlspecialchars($value).'"';
		}
		return $rtn;
	}
	function firstLevelLeaves($tree){
		$leaves = array();
		foreach($tree as $k=>$v){
			if(is_array($v)){ continue; }
			$leaves[$k] = $v;
		}
		return $leaves;
	}
	function firstLevelSubtrees($tree){
		$subtrees = array();
		foreach($tree as $k=>$v){
			if(!is_array($v)){ continue; }
			$subtrees[$k] = $v;
		}
		return $subtrees;
	}
	function genIndentation($levels, $indentString="\t"){
		$rtn = '';
		for($i=0; $i<$levels; $i++){
			$rtn .= $indentString;
		}
		return $rtn;
	}
	function xmlPrintTree($tree, $indentLevel=0){
		//echo "<hr />tree=";print_r($tree);
		foreach($tree as $k=>$v){
			$leaves = firstLevelLeaves($v);
			$branches = firstLevelSubtrees($v);
			//echo  "leaves=";print_r($leaves);
			//echo "branches=";print_r($branches);
			$hasLeaves = count($leaves)!=0;
			$hasBranches = count($branches)!=0;
			$indent = genIndentation($indentLevel);
			if(!$hasBranches){
				if(!$hasLeaves){
					echo "$indent<$k />\n";
				}else{
					echo "$indent<$k ".formatLeaves($leaves)." />\n";
				}
			}else{
				if(!$hasLeaves){
					echo "$indent<$k>\n";
				}else{
					echo "$indent<$k ".formatLeaves($leaves).">\n";
				}
				xmlPrintTree($branches,$indentLevel+1);
				echo "$indent</$k>\n";
			}
		}
	}
	
	function getDefault($val,$default){
		if($val===false){ return $default; }
		return $val;
	}
	
	function vCardPrintUser($user, $absBase){
		$first = getDefault(@$user['name']['first'],'');
		$last = getDefault(@$user['name']['last'],'');
		
		echo "BEGIN:VCARD\n";
		echo "VERSION:3.0\n";
		echo "CLASS:PUBLIC\n";
		echo "N:$last;$first\n";
		echo "FN:$first $last\n";
		if(isset($user['classOf'])){
			echo "ORG:Class of $user[classOf]\n";
		}
		if(isset($user['img'])){
			echo "PHOTO;VALUE=URL;TYPE=JPG:$absBase/$user[img]\n";
		}
		if(isset($user['phone']['mobile'])){
			echo "TEL;TYPE=CELL,VOICE:".$user['phone']['mobile']."\n";
		}
		if(isset($user['campus']['mailbox'])){
			echo "ADR;TYPE=HOME:;MB ".$user['campus']['mailbox'].";1000 Olin Way;Needham;MA;02453;United States of America\n";
		}
		if(isset($user['email'])){
			echo "EMAIL;TYPE=PREF,INTERNET:".$user['email']."\n";
		}
		if(isset($user['im']['AOL'])){
			echo "X-AIM:".$user['im']['AOL']."\n";
		}
		if(isset($user['im']['ICQ'])){
			echo "X-ICQ:".$user['im']['ICQ']."\n";
		}
		if(isset($user['im']['MSN'])){
			echo "X-MSN:".$user['im']['MSN']."\n";
			echo "X-MS-IMADDRESS:".$user['im']['MSN']."\n";
		}
		if(isset($user['im']['Skype'])){
			echo "X-SKYPE:".$user['im']['Skype']."\n";
			echo "X-SKYPE-USERNAME:".$user['im']['Skype']."\n";
		}
		
		echo "END:VCARD\n";
	}
	
	function vCardPrint($lines, $absBase){
		foreach($lines as $line){
			vCardPrintUser($line, $absBase);
			echo "\n";
		}
	}
	
	function guessFilename($lines, $query){
		$n = count($lines);
		if($n==0){ return "error"; }
		if($n==1){
			$name = @$lines[0]['name']['first'].' '.@$lines[0]['name']['last'];
			return fileNameMangle($name);
		}
		return fileNameMangle("olindirectory results for $query");
	}
	
	function fileNameMangle($candidate){
		$candidate = str_replace(":","=",$candidate);
		$candidate = preg_replace("/\/\\@/","_",$candidate);
		return preg_replace("/[^A-Za-z0-9 =\-+_]+/","",$candidate);
	}
	
	function xmlPrint($lines, $query, $itemName, $queryType){
		echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		echo '<results '.$queryType.'="'.htmlspecialchars($query).'" numMatches="'.count($lines).'"';
		if(count($lines)==0){
			echo " />";
			return;
		}
		
		echo '>'."\n";
		foreach($lines as $line){
			xmlPrintTree(array($itemName=>$line),1);
		}
		echo "</results>";
	}
	
	function jsonPrint($results, $query, $js, $queryType){
		$results = array(
			$queryType => $query,
			"numMatches" => "".count($results),
			"data" => $results
		);
		print $js->object($results);
	}
	
	function sendFileNameHeader($filename){
		header('Content-Disposition: attachment; filename="'.$filename.'"');
	}
	
	if(!isset($itemName)){
		$itemName="item";
	}
	
	if(!isset($queryType)){
		$queryType="query";
	}
	
	$query = str_replace("%","*",$query);
	
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
	$filename = guessFilename($results,$query);
	$download = isset($_GET['download']);
	if($format=='csv'){
		if($download){ sendFileNameHeader("$filename.csv"); }
		csvPrint($results, $query);
	}else if($format=='xml'){
		if($download){ sendFileNameHeader("$filename.xml"); }
		xmlPrint($results, $query, $itemName, $queryType);
	}else if($format=='vcard'){
		if($download){ sendFileNameHeader("$filename.vcf"); }
		vCardPrint($results, $absBaseHttp);
	}else{
		jsonPrint($results, $query, $javascript, $queryType);
	}
?>
