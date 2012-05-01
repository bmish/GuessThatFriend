<?php
/**
 * This class implements the cache API in the GuessThatFriend app.
 *
 * @copyright  2012 GuessThatFriend
 */
class Cache {

	/**
	 * Processes a cache request.
	 *
	 * @param string $apiRequest API request
	 * @param string $jsonObj JSON object
	 * @return void
	 */
	public static function cacheAPIRequest($apiRequest, $jsonObj) {
		$filename = sha1($apiRequest).".json";
		Cache::cacheTextToFile("cache/".$filename, json_encode($jsonObj));
	}
	
	/**
	 * Checks if an API request exists in the cache.
	 *
	 * @param string $apiRequest API request
	 * @return object Decoded JSON object
	 */
	public static function checkForCachedAPIRequest($apiRequest) {
		$filename = sha1($apiRequest).".json";
		$secondsInTwoWeeks = 60 * 60 * 24 * 7 * 2;
		
		$jsonText = Cache::checkFileForText("cache/".$filename, $secondsInTwoWeeks);
		return json_decode($jsonText, true);
	}
	
	/**
	 * Saves text to file.
	 *
	 * @param string $filepath Filepath to save to
	 * @param string $text Text to save
	 * @return void
	 */
	public static function cacheTextToFile($filepath, $text) {
		file_put_contents($filepath, $text, LOCK_EX);
	}
	
	/**
	 * Checks if a file exists.
	 *
	 * @param string $filepath Filepath to check for
	 * @param int $secondsToExpire Duration before cache expires in seconds
	 * @return string|bool Contents of the file if it exists, false otherwise
	 */
	public static function checkFileForText($filepath, $secondsToExpire) {
		if (!file_exists($filepath)) {
			return false;
		}
	
		// Cache is too old to use?
		$mtime = filemtime($filepath);
		if (time() - $mtime > $secondsToExpire) {
			return false;
		}
		
		// Cache is empty?
		$contents = file_get_contents($filepath, LOCK_EX);
		if (empty($contents)) {
			return false;
		}
		
		return $contents;
	}
}
?>