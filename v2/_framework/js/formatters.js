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

var lastScrollPosition = 0;

function formatAsCards(data, query){
	$("#results_detailed").empty().hide();
	container = $("#results");
	container.empty().show();
	
	if(data==null||data.length==0){ return; }
	$.each(data,function(index,user){
		var name_full = user.name_first+" "+user.name_last;
		
		//first the card header, including name and class
		var card = $$("div","ContactCard");
		var nameDiv = $$("div","Name");
		nameDiv.append($$x("span",name_full,"FullName"));
		nameDiv.append($$x("span",user.year_expected,"Class"));
		card.append(nameDiv);
		card.click(function(e){
			lastScrollPosition = $(window).scrollTop();
			formatDetailedCard(user,query);
			var restext = $("#resulttext_detailed");
			var a = $$x('a',"<< Return to all "+data.length+" results for \""+query+"\"");
			a.attr('href','?q='+query+'&r=y');
			a.click(function(e){
				$("#resulttext_detailed").empty().hide();
				$("#results").show();
				$("#resulttext").show();
				$("#resulttext_detailed").hide();
				$("#results_detailed").empty().hide();
				$(window).scrollTop(lastScrollPosition);
				return false;
				});
			restext.empty();
			restext.append(a);
			restext.show();
			$("#resulttext").hide();
			});
		
		//next, the image, or a stand-in if no image is available
		var img = $$("img");
		img.attr('src',user.photo_path);
		img.attr('width','105'); img.attr('height','140'); img.attr('title',name_full);
		card.append(img);
		
		//next, the contact info
		var contact = $$('div','ContactInfo');
		var p;
		
		p = $$('p');
		if(user.year_isaway){
			p.append("Currently Away from Olin"); p.append($$('br'));
			if(!user.away_hide){ //detailed address available
				if(user.away_street){
					p.append(user.away_street);
					if(user.away_apt){ p.append('<br />Apt/Unit '+user.away_apt+''); }
					p.append($$('br'));
					}
				if(user.away_city||user.away_state||user.away_zip||user.away_country){ p.append(user.away_city+", "+user.away_state+"  "+user.away_zip+" ("+user.away_country+")"); p.append($$('br')); }
				}
		}else{
			p.append('MB '+user.olin_mbox); p.append(', ');
			p.append('1000 Olin Way'); p.append($$('br'));
			p.append('Needham, MA  02492');
			}
		contact.append(p);
		
		if(!user.phone_hide&&user.phone_number){
			p = $$('p');
			im = $$('div','Icon Phone');
				im.text(user.phone_number);
				p.append(im);
			contact.append(p);
			}
		
		if(user.room_number||user.room_bid){
			p = $$('p');
			p.append('Room: '+user.room_bid+" "+user.room_number);
			contact.append(p);
			}
		
		card.append(contact);
		
		container.append(card);
		
		});

	}

function formatDetailedCard(user, query){
	$("#results").hide();
	container = $("#results_detailed");
	container.empty().show();
	
	var name_full = user.name_first+" "+user.name_last;
	
	//first the card header, including name and class
	var card = $$("div","Detailed ContactCard");
	var nameDiv = $$("div","Name");
	nameDiv.append($$x("span",name_full,"FullName"));
	nameDiv.append($$x("span",user.year_expected,"Class"));
	card.append(nameDiv);
	
	//next, the image, or a stand-in if no image is available
	var img = $$("img");
	img.attr('src',user.photo_path);
	img.attr('width','210'); img.attr('height','280'); img.attr('title',name_full);
	card.append(img);
	
	//next, the contact info
	var contact = $$('div','ContactInfo');
	var section, p;
	
	//Current Address Section
	section = $$('div','Mail Current Section');
	p = $$('p');
	if(user.year_isaway){
		p.append("<b>Currently Away from Olin</b>"); p.append($$('br'));
		if(!user.away_hide){ //detailed address available
			if(user.away_street){
				p.append(user.away_street);
				if(user.away_apt){ p.append('<br />Apt/Unit '+user.away_apt+''); }
				p.append($$('br'));
				}
			if(user.away_city||user.away_state||user.away_zip||user.away_country){ p.append(user.away_city+", "+user.away_state+"  "+user.away_zip+" ("+user.away_country+")"); p.append($$('br')); }
			}
	}else{
		p.append("<b>On-Campus Address</b>"); p.append($$('br'));
		p.append('MB '+user.olin_mbox); p.append(', ');
		p.append('1000 Olin Way'); p.append($$('br'));
		p.append('Needham, MA  02492');
		}
	section.append(p);
	contact.append(section);
	
	//Home Address Section
	if(!user.home_hide){
		section = $$('div','Mail Home Section');
		p = $$('p');
		p.append("<b>Home Address</b>"); p.append($$('br'));
		if(user.home_street){
			p.append(user.home_street);
			if(user.home_apt){ p.append('<br />Apt/Unit '+user.home_apt+''); }
			p.append($$('br'));
			}
		if(user.home_city||user.home_state||user.home_zip||user.home_country){ p.append(user.home_city+", "+user.home_state+"  "+user.home_zip+" ("+user.home_country+")"); p.append($$('br')); }
		section.append(p);
		contact.append(section);
		}

	//Room section
	if(user.room_number||user.room_bid){
		section = $$('div','Room Section');
		p = $$('p');
		p.append('<b>Room</b><br />'+user.room_bid+" "+user.room_number);
		section.append(p);
		contact.append(section);
		}
	
	//Phone Section
	if(!user.phone_hide){
		section = $$('div','Phone Section');
		p = $$('p');
		
		im = $$('div','Icon Phone');
			im.text(user.phone_number);
			p.append(im);
		
		section.append(p);
		contact.append(section);
		}
	
	//IM Section
	if(!user.im_hide){
		section = $$('div','IM Section');
		
		if(user.im_aol){
			im = $$('div','IM Icon AIM');
			im.text(user.im_aol);
			section.append(im);
			}
		
		if(user.im_gtalk){
			im = $$('div','IM Icon GTalk');
			im.text(user.im_gtalk);
			section.append(im);
			}
		
		if(user.im_icq){
			im = $$('div','IM Icon ICQ');
			im.text(user.im_icq);
			section.append(im);
			}
		
		if(user.im_msn){
			im = $$('div','IM Icon MSN');
			im.text(user.im_msn);
			section.append(im);
			}
		
		if(user.im_skype){
			im = $$('div','IM Icon Skype');
			im.text(user.im_skype);
			section.append(im);
			}
		
		//section.append(p);
		contact.append(section);
		}
	

	card.append(contact);
	
	container.empty();
	container.append(card);

	}