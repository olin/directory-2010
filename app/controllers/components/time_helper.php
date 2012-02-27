<?php
class TimeHelperComponent extends Object {
	
	 //provides UNIX timestamp for date/time that is specified distance in future
	function future($hours=0,$minutes=30,$seconds=0){
		return time()+(($hours*60)+$minutes)*60 + $seconds;
		}
	
	function futureSQL($hours=0,$minutes=30,$seconds=0){
		return date("Y-m-d H:i:s", self::future($hours,$minutes,$seconds));
	}
	
}
?>
