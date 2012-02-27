<?php

//find site root relative path
if(!isset($root)){ $root = "."; for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){ if($root=="."){ $root=".."; }else{ $root = "../$root"; } } }

require_once("$root/_framework/conf/Config.php");

//manages database connections
final class DBManager {
	//PDO database connection
	private static $conn = null;
	
	//obtains an open PDO connectoin to the database
	//this will only open one connection on the first call
	//subsequent calls will return the same PDO object to save resources
	public static function getConnection(){
		if(self::$conn==null){
			self::$conn = new PDO(
				Config::get('db.adapter').':host='.Config::get('db.host').';dbname='.Config::get('db.dbname'),
				Config::get('db.username'),
				Config::get('db.password')
				);
			}
		return self::$conn;
		}
	}

?>