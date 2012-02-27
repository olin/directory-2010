<?php
	header("Location: ../");
	die("Profiles are not editable at this time.");

/* format recognition & recovery functions */

/* standardises formatting for US phone numbers */
function format_us_phone($str){
	$sep = "[^0-9]*";
	$pat = "^ *\+?1?$sep([0-9]{3})$sep([0-9]{3})$sep([0-9]{4}).*$";
	if(ereg($pat,$str)){
		return ereg_replace($pat, "\\1-\\2-\\3", $str);
		}
	return FALSE;
	}

?>
