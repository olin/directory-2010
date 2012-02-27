<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}

	// dependencies
	require_once("$root/_framework/lib/HTTPManager.php");

	//do not serve search results *internally* with SSL (too slow)
	HTTPManager::forbidSSL(true);

	$customScript = <<<ENDCUSTOMSCRIPT

var lastScrollPosition = 0;

var formatResults = function(data, query){
	//no results, show nothing in the results area
	if(data.length==0){
		$("#results").empty();
		$("#results_detailed").empty();
	//only one result, show it as a detailed card
	}else if(data.length==1){
		formatDetailedCard(data[0],query);
	//more than one result, show them as small cards
	}else{
		formatAsCards(data,query);
		}
	}

var isSearching = false;
var searchFor = function(query){
	$("#qname").val(query);
	$("#qname").focus();
	doSearch();
	}
var doSearch = function(){
	if(isSearching){ setTimeout(doSearch,1000); return; }
	var q = $("#qname");
	var query = q.val();
	if(query==''){
		$("#resulttext").text("To use the Directory, please start typing in the box above.").show();
		$("#results").empty().show();
		$("#results_detailed").empty().hide();
		$("#resulttext_detailed").empty().hide();
		return;
		}
	isSearching = true;
	$("#resulttext").text("Querying server...").show();
	$.get("$root/api/query/?"+$.param({q:query}),{}, function(data,statusText){
		data = JSON.parse(data);

		var restext = $("#resulttext");
		restext.text("Found "+data.length+(data.length==1?" result":" results")+" for \""+query+"\"");

		if(data.length>=1){
			var a = $\$x('a','(Link to this search)');
			a.attr('href','?q='+query);
			a.addClass('LinkThisSearch');
			restext.append(' ');
			if(data.length>1){
				restext.append(" - click a card for more details  ");
				}
			restext.append(a);
			}
		$("#results").empty().show();
		$("#results_detailed").empty().hide();
		$("#resulttext_detailed").empty().hide();
		
		formatResults(data,query);
		isSearching = false;
		},'text');
	}

$(document).ready(function(){

	var q = $("#qname");

	setTimeout(function(){
		q.focus();
		},50);

	var userSearchCounter = 0;
	var lastVal = q.val();
	q.change(function(){
		if($.trim(q.val())==lastVal){ return; }
		lastVal = $.trim(q.val());
		//$("#resulttext").text(" ");
		if(!q.hasClass("Active")){ q.addClass("Active"); }
		userSearchCounter++;
		setTimeout(function(){
			userSearchCounter--;
			if(userSearchCounter<=0){ //if it has been long enough since user activity
				userSearchCounter = 0;
				q.removeClass("Active");
				doSearch();
				}
			},650); //delays until this many milliseconds after the user finishes typing
		});
	q.keyup(function(e){ q.change(); });
	q.keydown(function(e){ q.change(); });
	q.keypress(function(e){ q.change(); });

	if(q.val()!=""){
		doSearch();
		}

	$("#searchbox").submit(function(e){
		return false;
		});

	//$.jGrowl("This is a rough beta version of <strong>OlinDirectory</strong> version 2; Not all functionality is available just yet.<hr />You can log in using the username <strong>demo</strong> and password <strong>demo</strong><hr />Please direct any feedback to <strong>jeff@nomagicsmoke.com</strong>",{life: 10000, close: function(e,m,o){\$("#qname").focus();} });

	});
ENDCUSTOMSCRIPT;


	$pageTitle = "Olin Directory &raquo; Search";
	$current = "Search";
	$accessPermissions = "none";

	include("$root/_framework/header.php");
/* ##################################################### */ ?>

<form id="searchbox" action="./" method="get">
    Look for <input type="text" name="qname" id="qname"<?php if(@$_GET['q']){ ?> value="<?php print htmlspecialchars(@$_GET['q']); ?>"<?php } ?> />
    in Olin Students<?php if(@$showQuicksearch){ ?> (Quicksearch: <a href="javascript:searchFor('2010');">2010</a>, <a href="javascript:searchFor('2011');">2011</a>, <a href="javascript:searchFor('2012');">2012</a>, <a href="javascript:searchFor('2013');">2013</a>) <?php } ?>
    </form><br />
<div id="resulttext">To use the Directory, please start typing in the box above.</div>
<div id="resulttext_detailed"></div>
<div id="results"></div>
<div id="results_detailed"></div>

<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>
