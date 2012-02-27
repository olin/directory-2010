var searchInFlight = null;
var lastBoxValue = ""; //reference to the last value seen in the box
var searchBox = null; //reference to the jQuery elements
var searchButton = null;
var searchStatus = null;
var searchStatusDownload = null;
var searchStatusDefault = null;
var searchResults = null;
var searchUrl = null;
var searchCardTemplate = null;
var searchHoldoff = 1000; //ms to wait after last keypress before searching
var reSearch = false; //if true, search should run again when current search terminates
var searching = false; //is a search currently running?
var MAX_RESULTS = 20; //max # of results to show

function setCardValue(card, value, container, defVal, hideIfOmitted){
	if(hideIfOmitted && !value){
		card.find(hideIfOmitted).hide();
	}else{
		card.find(container).text(value||defVal);
	}
}

function setCardHTML(card, value, container, defVal, hideIfOmitted){
	if(hideIfOmitted && !value){
		card.find(hideIfOmitted).hide();
	}else{
		card.find(container).html(value||defVal);
	}
}

//map from API return values to UI frontend names
var contactProtoMapping = {
	'aol':   { name:'AIM'  , proto:'aim://'   },
	'gtalk': { name:'GTalk', proto:'gtalk://' },
	'icq':   { name:'ICQ'  , proto:'icq://'   },
	'msn':   { name:'MSN'  , proto:'msn://'   },
	'skype': { name:'Skype', proto:'skype://' }
};

function addCardContactIcon(card, type, proto, sn){
	if(!sn){ return; }
	var im = card.find('.ContactInfo .IM');
	var a = im.find('.Template').clone().removeClass('Template');
	a.attr('href',proto+sn).attr('title',type+': '+sn);
	a.find('img').attr('src',document.cakeBase+'/img/contact/'+type.toLowerCase()+'.png');
	a.find('.Details').text((type=='email'?'':(type+': '))+sn);
	im.append(a);
}

function get(val,def){
	def = typeof(def)!='undefined' ? def : '';
	return typeof(val)!='undefined' ? val : def;
}

function buildCard(user){
	//build some useful vars first
	var firstName = user.name ? get(user.name.first, '') : '';
	var lastName = user.name ? get(user.name.last) : '';
	var nickName = user.name ? get(user.name.nick) : '';
	if(nickName==firstName){ nickName = ''; }
	if(nickName){ nickName = ' "'+nickName+'"'; }
	var fullName = firstName + ' ' + lastName;
	var phone = user.phone ? get(user.phone.mobile) : '';
	var phoneTel = phone.replace(/[^0-9x]/g,''); //phone number with only 0-9 and x
	//var profilePic = user.img || 'img/u/unknown.jpg';
	//now clone & populate template
	var t = searchCardTemplate.clone().removeClass('Template').attr('id','User'+(user.uid||'9999999'));
	setCardValue(t, firstName, '.Name .FirstName', '', '.FirstName');
	//setCardValue(t, nickName, '.Name .NickName', '', '.NickName');
	setCardValue(t, lastName, '.Name .LastName', '', '.LastName');
	//setCardValue(t, fullName, '.Name .FullName', '', '.FullName');
	setCardValue(t, user.classOf, '.Class', '', '.Class');
	if(phone.length>0){
		t.find('.ContactInfo > a.Phone.UserAction').attr('href','tel:'+phoneTel).children('.text').text(phone);
	}else{
		t.find('.ContactInfo > a.Phone.UserAction').hide();
	}
	
	//setCardValue(t, get(get(user.campus,{}).mailbox), '.Campus.Address .MailBox', '', '.Campus.Address');
	//dorm
	var dormName = get(get(get(get(user.campus,{}).dorm,{}).building,{}).shortName, null);
	var dormRoom = get(get(get(user.campus,{}).dorm,{}).room, null);
	if(dormName || dormRoom){
		var dorm = (dormName||'')+'<br />'+(dormRoom||'');
		t.find('.ContactInfo > .Dorm.UserAction > .text').html(dorm);
	}else{
		t.find('.ContactInfo > .Dorm.UserAction').hide();
	}
	//IM/contact info
	if(user.email){
		t.find('.ContactInfo a.Email.UserAction').attr('href','mailto:'+user.email);
	}else{
		t.find('.ContactInfo a.Email.UserAction').hide();
	}
	return t;
}

//start the search, or queue the search if the current search is 
function searchStart(){
	searchInFlight = null;
	if(searching){
		reSearch = true;
		return;
	}
	clearSearchResults();
	if(lastBoxValue==""){
		return;
	}
	searching = true;
	searchButton.addClass('Working');
	setSearchMessage('Searching for "'+lastBoxValue+'"');
	searchStatusDownload.hide();
	searchUrl = document.cakeBase+"/api/users/search/"+lastBoxValue; 
	$.ajax({
		url: searchUrl+"/json",
		dataType: "json",
		success: searchComplete,
		error: searchError
	});
}

//the search is done; check if the user changed anything while we were searching
//if so, run that search again
function searchComplete(obj,status,xhr){
	searching = false;
	//if the user called for another search while this one was happening
	//then drop the results from this search and do the next one instead.
	if(searchInFlight==null && reSearch){ //another search ought to be done
		reSearch = false;
		searchStart();
		return;
	}
	searchButton.removeClass('Working');
	//setSearchStatus('Finished searching for '+lastBoxValue);
	//parse the results
	if(status!="success"){
		setSearchMessage("Error: Search failed");
		return;
	}
	var query = obj.query;
	var matches = parseInt(obj.numMatches);
	var users = obj.data;
	//format and output the results
	clearSearchResults();
	//setSearchStatus('Search for "'+lastBoxValue+'" returned '+matches+' result'+(matches!=1?'s':''));
	if(matches>0){ searchStatusDownload.show(); }
	var isSingleMode = users.length==1; //if only one result, go ahead and just make it big.
	setSearchMessage('formatting results...');
	searchResults = searchResults.detach();
	for(var i=0; i<users.length && i<MAX_RESULTS; i++){
		searchResults.append(buildCard(users[i]));
	}
	//limit visible results (for performance)
	if(users.length > MAX_RESULTS){
		var numOmitted = users.length - MAX_RESULTS;
		var pl = numOmitted==1 ? "user" : "users";
		setSearchMessage("... and "+numOmitted+" more "+pl+" (not shown)");
	}else if(users.length==0){
		setSearchMessage('no results for "'+lastBoxValue+'"');
	}else if(users.length==1){
		setSearchMessage('showing only match for "'+lastBoxValue+'"');
	}else{
		setSearchMessage('');
	}
	searchResults.insertAfter($('#SearchBar'));
	//auto-show single-results
	if(isSingleMode){ searchResults.find('.ContactCard .Name').click(); }
}

function searchError(xhr,status,err){
	clearSearchResults();
	setSearchStatus("Error: Search failed!");
}

function searchBoxChanged(immediate){
	//enter key means go immediately
	var immediate = immediate || false;
	//only fire if the value actually changed
	var newVal = searchBox.val();
	if(!immediate || newVal == lastBoxValue){ return; }
	lastBoxValue = newVal;
	//cancel any currently-running timeout
	if(searchInFlight!=null){
		clearTimeout(searchInFlight);
	}
	//issue a new timeout
	reSearch = false;
	if(immediate){
		searchStart();
	}else{
		searchInFlight = setTimeout(searchStart, searchHoldoff);
	}
}

function clearSearchResults(){
	setSearchStatus('(olindirectory mobile)');
	setSearchMessage('(olindirectory mobile)');
	searchResults.empty();
}

function setSearchStatus(stat){
	searchStatus.find('#searchText').text(stat);
	if(stat.length==0){
		searchStatus.hide();
		searchStatusDefault.show();
	}else{
		searchStatus.show();
		searchStatusDefault.hide();
	}
}

//change the value in the searchbox
function setSearchBoxValue(newValue, forceLoad){
	if(!forceLoad){ forceLoad = false; }
	$('#searchBox').val(newValue).focus();
	if(forceLoad){ searchBoxChanged(true); }
}

//change the view mode
function setViewMode(viewClass, elem){
	elem = $(elem);
	elem.addClass('selected').siblings().removeClass('selected');
	var cv = $('.Content.Section');
	setClass(cv,'FaceCards',viewClass=='faces');
	setClass(cv,'BusinessCards',viewClass=='cards');
}

$(document).ready(function(){
	//quicksearch event handlers
	$('.ShortcutsBar a[rel]').click(function(e){
		setSearchBoxValue($(this).attr('rel'), true);
		e.stopPropagation();
		return false;
	});
	//searchbox event handlers
	searchStatus = $('#searchStatus');
	searchStatusDownload = searchStatus.find('#download');
	searchStatusDefault = $('#searchStatusDefault');
	searchResults = $('#searchResults');
	searchCardTemplate = $('#searchCardTemplate');
	searchBox = $('#searchBox');
	searchButton = $('.SearchButton');
	//init ui controls
	setSearchStatus('');
	searchButton.click(function(e){
		searchBoxChanged(true);
		searchBox.blur();
	});
	//capture onsubmit
	$('#SearchBar form').submit(function(e){
		searchBoxChanged(true);
		e.preventDefault();
		searchBox.blur();
		return false;
	});

	$('.Content.Section .ContactCard.Person .Name').live('click',function(e){
		$card = $(this).parent();
		$card.siblings('.Expanded').removeClass('Expanded').find('.ContactInfo').hide();
		$card.addClass('Expanded').find('.ContactInfo').toggle();
	});
	
	setSearchMessage('(olindirectory mobile)');

	//force initial search on value in text box
	if($.urlVar('q')){
		searchBox.val($.urlVar('q'));
		searchBoxChanged(true);
	}
});

function setSearchMessage(message){
	message = (typeof message == "undefined") ? "" : message.toString().trim();
	if(!message || message.length==0){
		showHideSearchMessage(false);
		return;
	}
	showHideSearchMessage(true);
	$('#searchMessage .FullName').text(message);
};

function showHideSearchMessage(isVisible){
	$('#searchMessage .FullName').text('');
	if(isVisible||false){
		$('#searchMessage').show();
	}else{
		$('#searchMessage').hide();
	}
};


