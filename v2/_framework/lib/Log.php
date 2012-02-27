<?php

//logging stuff
final class Log {
	
	public static function error($where,$message){
		self::logg('E',$where,$message);
		}
	
	public static function warn($where,$message){
		self::logg('w',$where,$message);
		}
	
	public static function info($where,$message){
		self::logg(' ',$where,$message);
		}
	
	private static function logg($symbol,$where,$message){
		$where = str_pad($where,7," ",STR_PAD_LEFT);
		$line = " $symbol | $where | ".date('D Y-m-d H:i:s').' | '.$message."\n";
		if(!isset($root)){
			$root = ".";
			for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
				if($root=="."){ $root=".."; }else{ $root = "../$root"; }
				}
			}
		@file_put_contents("$root/admin/logs/logfile.php", $line, FILE_APPEND);
		}
	
	}

//stopwatch
final class Stopwatch {
	
	private static $lastTick = 0;
	
	//trigger a new tick (restart the stopwatch)
	public static function tick(){
		self::$lastTick = microtime(true);
		}
	
	//how many ms since last tick?
	public static function tock(){
		return round((microtime(true) - self::$lastTick)*1000.0,2);
		}
	
	}
//init stopwatch
Stopwatch::tick();

?>