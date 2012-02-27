<?php
	// older versions of PHP don't have JSON support
	// so we add it back in if it doesn't exist

	if( !function_exists("json_encode_assoc") || !function_exists("json_decode_assoc") ){

		require_once("Services_JSON.php");

		if(!function_exists("json_encode_assoc")){
			function json_encode_assoc($data){
				$value = new Services_JSON();
				return($value->encode($data));
				}
			}

		if(!function_exists("json_decode_assoc")){
			function json_decode_assoc($data){
				$value = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
				return($value->decode($data));
				}
			}

		}

	?>