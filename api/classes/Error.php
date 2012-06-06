<?php
class Error {
	public static function saveErrorToDB($msg) {
		$facebookAPI = FacebookAPI::singleton();
		
		$errorQuery = "INSERT INTO errors (msg, occurredAt, facebookId) VALUES ('".DB::cleanInputForDatabase($msg)."',NOW(),'".$facebookAPI->getLoggedInUserId()."')";
		mysql_query($errorQuery);
	}
}
?>