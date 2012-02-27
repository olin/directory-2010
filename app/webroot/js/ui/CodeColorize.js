/* Replaces all tabs with a pre.Output
 * with a <span> that is styled to show an indentation-level line */
$(document).ready(function(){

$('pre.Output').each(function(i,e){
	e=$(e);
	var colorize = e.hasClass('Colorized');
	var lines = e.html().split('\n');
	for(var i=0; i<lines.length; i++){
		var odd = i%2==1 ? '' : ' class="Odd"';
		lines[i] = lines[i].replace(/\t/g,'<span class="ND">\t</span>');
		if(!colorize || (i==lines.length-1 && lines[i].length==0)){ continue; }
		lines[i] = '<span'+odd+'>'+lines[i]+'</span>';
	}
	e.html(lines.join(colorize?'':'\n'));
	});

});
