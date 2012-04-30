<?php
class StatisticType {
	const FRIENDS = "friends";
	const CATEGORIES = "categories";
	const HISTORY = "history";
	
	const DEFAULT_TYPE = StatisticType::FRIENDS;
	
	public static function isValid($type) {
		return $type == StatisticType::FRIENDS || $type == StatisticType::CATEGORIES || $type == StatisticType::HISTORY;
	}
}
?>