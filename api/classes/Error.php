<?php
class Error {
	public static function saveExceptionToDB($e) {
		$facebookAPI = FacebookAPI::singleton();
		
		$message = DB::cleanInputForDatabase($e->getMessage());
		$trace = DB::cleanInputForDatabase($e->getTraceAsString());
		
		$errorQuery = "INSERT INTO errors (message, trace, occurredAt, facebookId) VALUES ('".DB::cleanInputForDatabase($message)."','".DB::cleanInputForDatabase($trace)."',UNIX_TIMESTAMP(),'".$facebookAPI->getLoggedInUserId()."')";
		mysql_query($errorQuery);
	}
	
	public static function saveErrorToDB($message) {
		$facebookAPI = FacebookAPI::singleton();
		
		$errorQuery = "INSERT INTO errors (message, occurredAt, facebookId) VALUES ('".DB::cleanInputForDatabase($message)."',UNIX_TIMESTAMP(),'".$facebookAPI->getLoggedInUserId()."')";
		mysql_query($errorQuery);
	}
}
?>