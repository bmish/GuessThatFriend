<?php
/**
 * This class implements general utility functions.
 *
 *
 */
class Util {

	/**
	 * Calculates time taken to load.
	 *
	 * @param float $timeStart Start time
	 * @return float Duration
	 */
	public static function calculateLoadingDuration($timeStart) {
		return round(microtime(true) - $timeStart, 2);
	}
	
	/**
	 * Returns a random element in an array.
	 *
	 * @param array $arr Array containing the elements
	 * @return object Random element
	 */
	public static function getRandomElement($arr = null)	{
		if ($arr && count($arr) > 0) {
			return $arr[array_rand($arr, 1)];
		}
		
		return null;
	}
}
?>