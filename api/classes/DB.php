<?php
class DB {
	public static function connect() {
		global $con,$dbHost,$dbUser,$dbPass;
		$con = mysql_connect($dbHost,$dbUser,$dbPass);
		if (!$con) {
			echo 'Could not connect: '.mysql_error();
		}
		
		$db_selected = mysql_select_db($dbName, $con);
		if (!$db_selected) {
		    echo 'Can\'t select database: '.mysql_error();
		}
	}

	public static function close() {
		global $con;
		mysql_close($con);
	}
}
?>