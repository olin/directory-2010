var searchInFlight = null;
var lastBoxValue = ""; //reference to the last value seen in the box
var searchBox = null; //reference to the jQuery elements
var searchStatus = null;
var searchStatusDownload = null;
var searchStatusDefault = null;
var searchResults = null;
var searchUrl = null;
var searchCardTemplate = null;
var searchHoldoff = 1000; //ms to wait after last keypress before searching
var reSearch = false; //if true, search should run again when current search terminates
var searching = false; //is a search currently running?

function setCardValue(card, value, container, defVal, hideIfOmitted){
	if(hideIfOmitted && !value){
		card.find(hideIfOmitted).hide();
	}else{
		card.find(container).text(value||defVal);
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

function buildCard(user, isSingle){
	//build some useful vars first
	var firstName = user.name ? get(user.name.first, '') : '';
	var lastName = user.name ? get(user.name.last) : '';
	var nickName = user.name ? get(user.name.nick) : '';
	if(nickName==firstName){ nickName = ''; }
	if(nickName){ nickName = ' "'+nickName+'"'; }
	var fullName = firstName + ' ' + lastName;
	var phone = user.phone ? get(user.phone.mobile) : '';
	var phoneTel = phone.replace(/[^0-9x]/g,''); //phone number with only 0-9 and x
	var profilePic = user.img || 'img/u/unknown.jpg';
	//now clone & populate template
	var t = searchCardTemplate.clone().removeClass('Template').attr('id','User'+(user.uid||''));
	if(isSingle){ t.addClass('Large'); }
	setCardValue(t, firstName, '.Name .FirstName', '', '.FirstName');
	setCardValue(t, nickName, '.Name .NickName', '', '.NickName');
	setCardValue(t, lastName, '.Name .LastName', '', '.LastName');
	//setCardValue(t, fullName, '.Name .FullName', '', '.FullName');
	setCardValue(t, user.classOf, '.Class', '', '.Class');
	t.find('.ContactInfo > .Headline > a').attr('href','tel:'+phoneTel).text(phone);
	t.find('.Profile').attr('alt',fullName).attr('title',fullName).attr('src',profilePic);
	setCardValue(t, get(get(user.campus,{}).mailbox), '.Campus.Address .MailBox', '', '.Campus.Address');
	setCardValue(t, get(get(get(get(user.campus,{}).dorm,{}).building,{}).shortName), '.Dorm .DormName', '', '.Dorm .DormName')
	setCardValue(t, get(get(get(user.campus,{}).dorm,{}).room), '.Dorm .RoomNumber', '', '.Dorm .RoomNumber')
	//IM/contact info
	if(user.email){
		var isGMail = user.email.endsWith('@gmail.com');
		var type = isGMail ? "GMail" : "email";
		addCardContactIcon(t, type, "mailto:", user.email||'');
	}
	if(user.uid){ 
		var im = t.find('.ContactInfo .IM');
		var a = im.find('.Template').clone().removeClass('Template');
		var url = document.cakeBase+"/api/users/"+user.uid+"/vcard?download";
		var title = 'Download a vCard for '+fullName;
		a.attr('href',url).attr('title',title).attr('alt',title);
		a.find('img').attr('src',document.cakeBase+'/img/contact/vcard.png');
		a.find('.Details').text('Download a vCard');
		im.append(a);
	}
	//if(!(document.isMobile||false)){ t.find('.ContactInfo').show(); }
	if(!user.im){ user.im = {}; }
	for(var service in user.im){
		var sn = user.im[service];
		service = (service||'').toLowerCase();
		var name = contactProtoMapping[service].name || service;
		var proto = contactProtoMapping[service].proto || (service+"://");
		addCardContactIcon(t, name, proto, sn);
	}
	//allow css styling of focusing
	t.focus(function(e){ $(this).addClass('Focused'); });
	t.blur(function(e){ $(this).removeClass('Focused'); });
	//key navigation between cards
	//keycodes: 37 left, 38 up, 39 right, 40 down
	t.keyup(function(e){
		switch(e.keyCode){
			case 37: case 38: //left and up
				e.stopPropagation();
				focusPrev(this);
				break;
			case 39: case 40: //right and down
				e.stopPropagation();
				focusNext(this);
				break;
			case 13: //enter should trigger click
				e.stopPropagation();
				$(this).click();
				break;
		}
	});
	//campus address
	return t;
}

function focusPrev(elem){
	elem = $(elem);
	prev = elem.prev();
	if(prev.length!=0){
		elem.blur();
		prev.focus();
		if(showingLightbox){
			showInLightbox(prev[0]);
		}
	}
}
function focusNext(elem){
	elem = $(elem);
	next = elem.next();
	if(next.length!=0){
		elem.blur();
		next.focus();
		if(showingLightbox){
			showInLightbox(next[0]);
		}
	}
}

//start the search, or queue the search if the current search is 
function searchStart(){
	searchInFlight = null;
	if(searching){
		reSearch = true;
		return;
	}
	if(lastBoxValue==""){
		clearSearchResults();
		return;
	}
	searching = true;
	searchBox.addClass('Working');
	setSearchStatus('Searching for "'+lastBoxValue+'"');
	searchStatusDownload.hide();
	searchUrl = document.cakeBase+"/api/users/search/"+lastBoxValue; 
	$.ajax({
		url: searchUrl+"/json",
		dataType: "json",
		success: searchComplete,
		error: searchError
	});
	hideLightbox();
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
	hideLightbox();
	searchBox.removeClass('Working');
	setSearchStatus('Finished searching for '+lastBoxValue);
	//parse the results
	if(status!="success"){ return; }
	var query = obj.query;
	var matches = parseInt(obj.numMatches);
	var users = obj.data;
	//format and output the results
	clearSearchResults();
	setSearchStatus('Search for "'+lastBoxValue+'" returned '+matches+' result'+(matches!=1?'s':''));
	if(matches>0){ searchStatusDownload.show(); }
	var isSingleMode = users.length==1; //if only one result, go ahead and just make it big.
	for(var i=0; i<users.length; i++){
		searchResults.append(buildCard(users[i],isSingleMode));
	}
	searchStatusDownload.find('.CSV').attr('href',searchUrl+"/csv?download");
	searchStatusDownload.find('.vCard').attr('href',searchUrl+"/vcard?download");
}

function searchError(xhr,status,err){
	alert("Error! "+status+"\n"+err)
}

function searchBoxChanged(e){
	//enter key means go immediately
	var immediate = false;
	if(e!=null){
		if(typeof(e)=='object'){
			immediate = e.keyCode==13; //user pressed the enter key, let's search right away
			e.stopPropagation();
		}else if(typeof(e)=='boolean'){
			immediate = e;
		}
	}
	//only fire if the value actually changed
	var newVal = searchBox.val();
	if(!immediate && newVal == lastBoxValue){ return; }
	lastBoxValue = newVal;
	//cancel any currently-running timeout
	if(searchInFlight!=null){
		clearTimeout(searchInFlight);
	}
	//issue a new timeout
	reSearch = false;
	if(immediate){
		searchStart();
	}else if(!(document.isMobile||false)){
		searchInFlight = setTimeout(searchStart, searchHoldoff);
	}
}

function clearSearchResults(){
	setSearchStatus('');
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

//adds or removes a class based on the elem
function setClass(elem,cssClass,doAdd){
	if(doAdd)
		$(elem).addClass(cssClass);
	else
		$(elem).removeClass(cssClass);
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
	//init ui controls
	setSearchStatus('');
	searchBox.keyup(searchBoxChanged);
	searchBox.keypress(searchBoxChanged);
	searchBox.change(searchBoxChanged);
	searchBox.focus();
	if(document.isMobile) $('.ContactInfo').hide();
	//force initial search on value in text box
	if($.urlVar('q')){
		searchBox.val($.urlVar('q'));
		searchBoxChanged(true);
	}
});




var showingLightbox = true;

function hideLightbox(){
	if(!showingLightbox){ return; }
	var shade = $('.DetailsView');
	var lightBox = $(this).children('.Lightbox');
	lightBox.children().remove();
	shade.hide();
	showingLightbox = false;
}

function showInLightbox(card){
	card = $(card);
	var shade = $('.DetailsView');
	var lightBox = shade.children('.Lightbox'); 
	lightBox.children().remove();
	var clone = card.clone().addClass('Large').removeClass('Focused');
	clone.attr('id','Detail'+card.attr('id'));
	lightBox.append(clone);
	shade.show();
	showingLightbox = true;
}

$(document).ready(function(){
	$(document).keyup(function(e) {
		if(e.keyCode == 27){ //escape key
			hideLightbox();
		}
	});
	
	$('.Content.Section .ContactCard').live('click',function(e){
		if($(this).hasClass('Large')){ return; }
		showInLightbox(this);
	});
	
	//prevent clicking tel link from opening details view
	$('a[href^="tel:"], .IM > a').live('click',function(e){
		e.stopPropagation();
	});

	$('.Content.Section').css('background','#eee');
	
	$('.DetailsView').click(hideLightbox);
	$('.DetailsView .Lightbox').click(function(e){
		e.stopPropagation();
	});
	hideLightbox();
	
	//make some copies to populate
	/*var card = $('.Content.Section .ContactCard').first();
	for(var i=1; i<=100; i++){
		var clone = card.clone();
		clone.find('div.Name > span.FullName').text('Jeffrey Stanton '+i);
		card.parent().append(clone);
	}
	card.remove();*/
	
});
