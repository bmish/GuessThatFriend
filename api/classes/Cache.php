<?php
class Cache {
	public static function cacheAPIRequest($apiRequest, $jsonObj) {
		$filename = sha1($apiRequest).".json";
		Cache::cacheTextToFile("cache/".$filename, json_encode($jsonObj));
	}
	
	public static function checkForCachedAPIRequest($apiRequest) {
		$filename = sha1($apiRequest).".json";
		$secondsInTwoWeeks = 60 * 60 * 24 * 7 * 2;
		
		$jsonText = Cache::checkFileForText("cache/".$filename, $secondsInTwoWeeks);
		return json_decode($jsonText, true);
	}
	
	public static function cacheTextToFile($filepath, $text) {
		file_put_contents($filepath, $text, LOCK_EX);
	}
	
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