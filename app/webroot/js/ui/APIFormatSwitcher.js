/* Tabs on API output sections that let user switch format */
$(document).ready(function(){

var formats = ['JSON','XML','CSV'];
$('.SampleTabGroup').each(function(i,e){
	var group = $(e);
	var menu = $('<div class="FormatMenu"></div>');
	group.prepend(menu);
	var section = $(menu.parents('.SampleTabGroup')[0]);
	var sectionName = section.attr('id');
	for(var i=0; i<formats.length; i++){
		var tab = $('<div id="'+formats[i]+'">'+formats[i]+'</div>');
		menu.append(tab);
	}
	menu.children().click(function(evt){
		evt.stopPropagation();
		var mi = $(this);
		if(mi.hasClass('Current')){ return; }
		var format = mi.attr('id');
		$('#'+sectionName+format).addClass('Current').show();
		mi.addClass('Current');
		mi.siblings().removeClass('Current').each(function(n,sib){
			var otherFormat = $(sib).attr('id');
			$('#'+sectionName+otherFormat).removeClass('Current').hide();
		});
	});
	menu.children().first().click();
});

});
