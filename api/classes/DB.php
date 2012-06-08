<?php
/**
 * This class implements the API functions for connecting to the database server.
 *
 *
 */
class DB {

	/**
	 * Opens a connection to the database.
	 */
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

	/**
	 * Closes the connection to the database.
	 */
	public static function close() {
		global $con;
		mysql_close($con);
	}
	
	/**
	 * Cleans input for database.
	 *
	 * @param string $input Input text to be cleaned
	 * @return string Cleaned input
	 */
	public static function cleanInputForDatabase($input) {
		return addslashes(trim($input));
	}
	
	public static function cleanArrayForDatabase($array, $isIntValues = false) {
		if (!is_array($array)) {
			return;
		}
		
		for ($index = 0; $index < count($array); $index++) {
			$array[$index] = $isIntValues ? intval($array[$index]) : DB::cleanInputForDatabase($array[$index]);
		}
		
		return $array;
	}

	/**
	 * Cleans output from database.
	 *
	 * @param string $output Output text to be cleaned
	 * @return string Cleaned output
	 */
	public static function cleanOutputFromDatabase($output) {
		return stripslashes($output);
	}
	
	/**
	 * Parses database result into an array.
	 *
	 * @param resource $result Database result
	 * @return array Result in array form
	 */
	public static function getArrayOfDBResult($result) {
		$arr = array();
		while ($row = mysql_fetch_assoc($result)) {
			$arr[] = $row;
		}
		
		return $arr;
	}
}
?>