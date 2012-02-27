var D_EFFECT = "blind";
var D_SPEED = 400;

function showIfChecked(chk,showMe,useDefaultEffect){
	if(showIfChecked==null){ return; }
	var checked = $(chk).attr('checked')
	if(useDefaultEffect){
		if(checked){
			$(showMe).show(D_EFFECT,{},D_SPEED);
		}else{
			$(showMe).hide(D_EFFECT,{},D_SPEED);
			}
	}else{
		if(checked){
			$(showMe).show();
		}else{
			$(showMe).hide();
			}
		}
	}

function hideIfChecked(chk,hideMe,useDefaultEffect){
	if(showIfChecked==null){ return; }
	var checked = $(chk).attr('checked')
	if(useDefaultEffect){
		if(checked){
			$(hideMe).hide(D_EFFECT,{},D_SPEED);
		}else{
			$(hideMe).show(D_EFFECT,{},D_SPEED);
			}
	}else{
		if(checked){
			$(hideMe).hide();
		}else{
			$(hideMe).show();
			}
		}
	}

function pickIfChecked(chk,showMe,hideMe,useDefaultEffect){
	showIfChecked(chk,showMe,useDefaultEffect);
	hideIfChecked(chk,hideMe,useDefaultEffect);
	}

$.fn.image = function(src, f)
{
	return this.each(function()
	{
		var i = new Image();
		i.onload = f;
		i.src = src;
		this.appendChild(i);
	});
}


$(document).ready(function(){
	//phone field masking
	$("#phone_number").mask("(999) 999-9999",{placeholder:" "});

	baseHeadshotURL = $("#headshot").attr('src');
	$("#uploadStatus").hide(); //hide "Uploading..." status message
	new AjaxUpload('#uploadHeadshot', {
		action: 'imageUpload.php',
		onSubmit: function(file, ext){
			if(!(ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
				alert('Only JPG, PNG and GIF are allowed');
				return false; // cancel upload
				}
			var delay = 400;
			$("#uploadHeadshot").hide();
			$("#uploadStatus").show().text("Uploading image...");
			},
		onComplete: function(file, response){
			//alert(file+"\n"+response);
			$("#headshot").attr('src',baseHeadshotURL+"&t="+(new Date().getTime()));
			$("#uploadStatus").fadeOut(400,function(){
				$("#uploadStatus").text("Done uploading image!");
				$("#uploadStatus").fadeIn(400, function(){
					setTimeout(function(){
						$("#uploadStatus").fadeOut(400,function(){ $("#uploadHeadshot").fadeIn(400); });
						},2000);
					});
				
				
				});
			}
		});
	
	
	var USE_DEFAULT_ANIMATIONS = true;
	$("#year_isaway").change(function(){
		pickIfChecked(this,"#away","#olin",USE_DEFAULT_ANIMATIONS);
		});
	$("#away_hide").change(function(){
		hideIfChecked(this,"#away_details",USE_DEFAULT_ANIMATIONS);
		});
	$("#home_hide").change(function(){
		hideIfChecked(this,"#home_details",USE_DEFAULT_ANIMATIONS);
		});
	$("#phone_hide").change(function(){
		pickIfChecked(this,"#phone_warning","#phone_details",USE_DEFAULT_ANIMATIONS);
		});
	$("#im_hide").change(function(){
		hideIfChecked(this,"#im_details",USE_DEFAULT_ANIMATIONS);
		});
	//initialize shown/hidden sections (no animation)
	USE_DEFAULT_ANIMATIONS = false;
	$("#year_isaway").change();
	$("#away_hide").change();
	$("#home_hide").change();
	$("#phone_hide").change();
	$("#im_hide").change();
	//now re-enable animation for user interactions
	USE_DEFAULT_ANIMATIONS = true;
	
	});
