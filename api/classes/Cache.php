<?php
/**
 * This class implements the cache API in the GuessThatFriend app.
 *
 */

class Cache {
	const SECONDS_PER_WEEK = 604800; // 60*60*24*7
	const SECONDS_BEFORE_EXPIRING = 1209600; // SECONDS_PER_WEEK * 2
	
	public static function requestFacebookAPIWithCaching($requestString) {
		$facebookAPI = FacebookAPI::singleton();
		$requestString = DB::cleanInputForDatabase($requestString);
		
		// Is there an unexpired cached response in the database?
		$cachedResponse = Cache::checkDatabaseForCachedResponse($requestString);
		if ($cachedResponse) {
			return $cachedResponse;
		}
		
		// Response is not cached or it was expired so send the request to Facebook.
		$responseObj = $facebookAPI->api($requestString);
		$responseString = json_encode($responseObj);
		
		// Cache the response.
		$sql = "INSERT INTO facebookAPICache (request, response, retrievedAt) VALUES ('".DB::cleanInputForDatabase($requestString)."','".DB::cleanInputForDatabase($responseString)."',UNIX_TIMESTAMP()) ON DUPLICATE KEY UPDATE response = '".DB::cleanInputForDatabase($responseString)."', retrievedAt = UNIX_TIMESTAMP()";
		$result = mysql_query($sql);
		
		return $responseObj;
	}
	
	private static function checkDatabaseForCachedResponse($requestString) {
		$sql = "SELECT response FROM facebookAPICache WHERE request = '".DB::cleanInputForDatabase($requestString)."' AND retrievedAt > '".Cache::minUnexpiredUnixTimestamp()."' LIMIT 1";
		$result = mysql_query($sql);
		if ($result && mysql_num_rows($result) == 1) { // Found an unexpired cached response.
			$row = mysql_fetch_array($result);
			
			return json_decode($row["response"], true);
		}
		
		return null;
	}
	
	public static function minUnexpiredUnixTimestamp() {
		return time() - Cache::SECONDS_BEFORE_EXPIRING;
	}
}
?>