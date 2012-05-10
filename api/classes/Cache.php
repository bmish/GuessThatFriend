<?php
/**
 * This class implements the cache API in the GuessThatFriend app.
 *
 * @copyright  2012 GuessThatFriend
 */

class Cache {
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
		$responseString = DB::cleanInputForDatabase(json_encode($responseObj));
		
		// Cache the response.
		$sql = "INSERT INTO facebookAPICache (request, response, timestamp) VALUES ('$requestString','$responseString',UNIX_TIMESTAMP()) ON DUPLICATE KEY UPDATE response = '$responseString', timestamp = UNIX_TIMESTAMP()";
		$result = mysql_query($sql);
		
		return $responseObj;
	}
	
	private static function checkDatabaseForCachedResponse($requestString) {
		$SECONDS_PER_WEEK = 60*60*24*7;
		$secondsBeforeExpiring = $SECONDS_PER_WEEK * 2;
		$minUnixTimestamp = time() - $secondsBeforeExpiring;
		$sql = "SELECT response FROM facebookAPICache WHERE request = '$requestString' AND timestamp > '$minUnixTimestamp' LIMIT 1";
		$result = mysql_query($sql);
		if ($result && mysql_num_rows($result) == 1) { // Found an unexpired cached response.
			$row = mysql_fetch_array($result);
			
			return json_decode($row["response"], true);
		}
		
		return null;
	}
}
?>