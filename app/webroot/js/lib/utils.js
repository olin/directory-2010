//focuses on the first empty field
function focusFirstEmpty(selectors, focusLast){
	if(!(selectors instanceof Array)){
		selectors = [selectors];
	}
	var last = null;
	for(var i=0; i<selectors.length; i++){
		var foundFocus = false;
		$(selectors[i]).each(function(i,e){
			last = $(e);
			if(last.val().length==0){
				last.focus();
				foundFocus = true;
				return false; //break
			}
			return true; //continue
		});
		if(foundFocus){ return; }
	}
	console.log(last);
	if(last!=null && focusLast){
		last.focus();
		var p = last.val().length;
		if (p>0 && last.length>0) {
			last[0].selectionStart = p;
			last[0].selectionEnd = p;
		}
	}
};

function setupStandardInputFocus(focusLast){
	$(document).ready(function(){
		focusFirstEmpty("input:visible[type='text'], input:visible[type='password'], select:visible, textarea", focusLast);
	});
};

String.prototype.startsWith = function(s){
	return this.indexOf(s) == 0;
};

String.prototype.endsWith = function(s){
    return this.match(s+"$") == s;
};

//This next function is from http://jquery-howto.blogspot.com/2009/09/get-url-parameters-values-with-jquery.html
$.extend( {
	urlVars : function() {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for ( var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	},
	urlVar : function(name) {
		return $.urlVars()[name];
	}
});
