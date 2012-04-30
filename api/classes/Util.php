<?php
class Util {
	public static function calculateLoadingDuration($timeStart) {
		return round(microtime(true) - $timeStart, 2);
	}
	
	public static function getRandomElement($arr = null)	{
		if ($arr && count($arr) > 0) {
			return $arr[array_rand($arr, 1)];
		}
		
		return null;
	}
}
?>