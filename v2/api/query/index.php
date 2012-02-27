<?php

	if(!isset($root)){ $root = "."; for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){ if($root=="."){ $root=".."; }else{ $root = "../$root"; } } }

	require_once("$root/_framework/lib/UserManager.php");
	require_once("$root/_framework/lib/Status.php");
	require_once("$root/_framework/lib/json/json.php");
	require_once("$root/_framework/lib/Log.php");
	
	Stopwatch::tick();
	//======================================================

	$q = @$_REQUEST['q'];
	//@Log::info("QUERY","Query for \"".addslashes($q)."\"");
	$matches = UserManager::getMatches($q);
	
	if(Status::isError($matches)){
		@Log::error("QUERY",'Query for "'.addslashes($q).'" : Cannot fetch matches: '.$matches['message']);
		die(json_encode_assoc($matches));
		}
	
	$numMatches = count($matches);
	
	//API calls for max-results behaviour (if max-results is set in ?m= in query)
	$maxResults = false;
	if(isset($_REQUEST['m'])){ $maxResults = @intval($_REQUEST['m']); }
	if($maxResults===false){ $maxResults = 0; }
	if($maxResults>0){
		$elapsed = Stopwatch::tock();
		if(count($matches)>$maxResults){
			@Log::warn("QUERY",'Query for "'.addslashes($q)."\" returned too many matches ($numMatches matches, limit $maxResults) in $elapsed ms");
			die("TOOMANYRESULTS");
			}
		if(count($matches)==0){
			@Log::warn("QUERY",'Query for "'.addslashes($q).'" returned 0 matches in $elapsed ms');
			die("ZERORESULTS");
			}
		}
	
	//post-process matches for essential fields	
	for($i=0; $i<$numMatches; $i++){
		$e = $matches[$i];
		$e = DataManager::maskFields($e); //remove data hidden by user preferences
		$hsfile = "$root/_headshots/uid/".$e['uid'].".jpg";
		if(file_exists($hsfile)){ $e['photo_path'] = "_headshots/uid/".$e['uid'].".jpg";
			}else{ $e['photo_path'] = "_headshots/unknown.jpg";
			}
		$matches[$i] = $e;
		}
	
	//output
	print json_encode_assoc($matches);
	
	//logging
	$elapsed = Stopwatch::tock();
	@Log::info("QUERY","Query for \"".addslashes($q)."\" returned $numMatches matches in $elapsed ms");

	?>
