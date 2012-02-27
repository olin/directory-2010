function $$(tagName,className,id){
	var elem = $(document.createElement(tagName));
	if(className){ elem.addClass(className); }
	if(id){ elem.attr('id',id); }
	return elem;
	}

function $$x(tagName,innerText,className){
	var elem = $$(tagName,className);
	elem.text(innerText);
	return elem;
	}

function subst(container,newContents){
	container.empty();
	container.append(newContents);
	}


var D_EFFECT = "blind";
var D_SPEED = 400;

function show(elem,useDefaultEffect){
	if(useDefaultEffect){
		$(elem).show(D_EFFECT,{},D_SPEED);
	}else{
		$(elem).show();
		}
	}

function hide(elem,useDefaultEffect){
	if(useDefaultEffect){
		$(elem).hide(D_EFFECT,{},D_SPEED);
	}else{
		$(elem).hide();
		}
	}


function showWelcome(){
	//UI state
	$('#addnew_subtitle').text("");
	$('#addnew_init').show();
	$('#addnew_requesting').hide();
	$('#addnew_waiting').hide();
	$('#addnew_confirmed').hide();
	}

var _timeout = null;
var confCode = "";

function requestCode(){
	$.get("api/?a=req",{}, function(data,statusText){
		confCode = data;
		
		$("#btnCancel").click(function(e){
			clearTimeout(_timeout);
			$.get("api/?a=delc&c="+escape(confCode),{}, function(data,statusText){
				showWelcome();
				},'text');
			return false;
			});
		
		$("#confCode").text(confCode);
		hide('#addnew_requesting');
		show('#addnew_waiting');
		checkStatus();
		},'text');
	}

function checkStatus(){
	$.get("api/?a=chk&c="+escape(confCode),{}, function(data,statusText){
		if(data!="PENDING"){
			$('#addnew_subtitle').text(" - Success!");
			$("#emailConf").attr("href","mailto:"+data);
			$("#emailConf").text(data);
			hide('#addnew_waiting');
			show('#addnew_confirmed');
			show('#addnew_init');
			addAddressToList(data);
		}else{
			_timeout = setTimeout(checkStatus,2000);
			}
		},'text');
	}


function deleteEntry(elem, email){
	var msg = email+"\nAre you sure you want to delete this address?";
	if(!confirm(msg)){ return; }
	$.get("api/?a=dele&e="+escape(email),{}, function(data,statusText){
		$(elem).parent().remove();
		if($('#existing').children().length<=1){
			$("#noAddrs").show();
			}
		
		},'text');
	}

function addAddressToList(email){
	
	var p = $$('p','Address');
	var a = $$('a','Delete');
	
	var img = $$('img');
	img.attr('src','delete.png');
	img.attr('border','0');
	img.attr('align','absmiddle');
	img.attr('title','Delete this address');
	a.append(img);
	
	a.click(function(e){
		deleteEntry(this,email);
		});
	
	p.append(a);
	p.append(" "+email);
	$("#existing").append(p);
	
	$("#noAddrs").hide();
	
	}

$(document).ready(function(){
	//help system
	$('#help_devices').hide();
						   
	//delete click handler
	$('#existing p a').click(function(e){
		var email = $(this).parent().text().trim();
		deleteEntry(this,email);
		});
	
	showWelcome();
	
	$('#btnAdd').click(function(e){
		
		$('#addnew_subtitle').text(" - Waiting...");
		hide('#addnew_init');
		hide('#addnew_confirmed');
		show('#addnew_requesting');
		
		requestCode();
		
		return false;
		
		
		});
	
	});