<?php
class DB {
	public static function connect() {
		global $con;
		
		$con = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		if (!$con) {
			echo 'Could not connect: '.mysql_error();
		}
		
		$dbSelected = mysql_select_db(DB_NAME, $con);
		if (!$dbSelected) {
		    echo 'Can\'t select database: '.mysql_error();
		}
	}

	public static function close() {
		global $con;
		mysql_close($con);
	}
	
	public static function cleanInputForDatabase($input) {
		return addslashes(trim($input));
	}

	public static function cleanOutputFromDatabase($output) {
		return stripslashes($output);
	}
}
?>