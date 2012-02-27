<?php

final class Config {
	
	private static $conf = null;
	
	public static function getHostname(){
		return @strtolower(@php_uname('n'));
		}
	
	private static function load(){
		$host = self::getHostname();
		$conf = parse_ini_file('cfgini.php',true);
		
		$main = $conf['main'];
		if(isset($conf[$host])){ $conf = array_merge($main,$conf[$host]);
			}else{ $conf = $main;
			}
		$conf['host.name'] = $host;
		
		self::$conf = $conf;
		}
	
	public static function get($key){
		if(!self::$conf){ self::load(); }
		return self::$conf[$key];
		}
	
	}

?>