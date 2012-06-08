<?php
/**
 * An enumeration for the type of statistics.
 *
 *
 */
class StatisticType {
	const FRIENDS = "friends";
	const CATEGORIES = "categories";
	const HISTORY = "history";
	
	const DEFAULT_TYPE = StatisticType::FRIENDS;
	
	/**
	 * Checks if a type is valid.
	 *
	 * @param integer $type Type to check
	 * @return bool True if the type is valid, false otherwise
	 */
	public static function isValid($type) {
		return $type == StatisticType::FRIENDS || $type == StatisticType::CATEGORIES || $type == StatisticType::HISTORY;
	}
}
?>