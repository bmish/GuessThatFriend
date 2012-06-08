<?php
/**
 * An enumeration for the type of options.
 *
 *
 */
class OptionType {
	const RANDOM = -1;
	const FILL_IN_THE_BLANK = 0;
	const MC_2 = 2;
	const MC_3 = 3;
	const MC_4 = 4;
	const MC_5 = 5;
	const MC_6 = 6;
	
	const MC_MIN = OptionType::MC_2;
	const MC_MAX = OptionType::MC_6;
	const DEFAULT_TYPE = OptionType::MC_4;
	
	/**
	 * Checks if a type is valid.
	 *
	 * @param integer $type Type to check
	 * @return bool True if the type is valid, false otherwise
	 */
	public static function isValid($type) {
		return $type != "" && ($type == OptionType::RANDOM || $type == OptionType::FILL_IN_THE_BLANK || ($type >= OptionType::MC_MIN && $type <= OptionType::MC_MAX));
	}
}
?>
