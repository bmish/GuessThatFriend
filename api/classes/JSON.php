<?php
/**
 * This class implements JSON utility functions.
 *
 */
class JSON {

	/**
	 * Input an array and return a new one out that is ready to be converted to JSON.
	 * php 5.4.0 will bring automatic JsonSerializable functionality.
	 *
	 * @param array $array Array of objects
	 * @return array Array ready to be converted to JSON
	 */
	public static function jsonSerializeArray($array) {
		$ret = array();

		for ($i = 0; $i < count($array); $i++) {
			$ret[] = $array[$i]->jsonSerialize();
		}

		return $ret;
	}

	/**
	 * Outputs a sample JSON file.
	 *
	 * @param string $filename Filename of the sample JSON file
	 * @return void
	 */
	public static function outputExampleJSON($filename) {
		header('Content-type: application/json');
		require_once("examples/json/".$filename);
	}

	/**
	 * Outputs an array as a JSON string.
	 *
	 * @param array $json Array to encode as JSON
	 * @return void
	 */
	public static function outputArrayInJSON($json) {
		header('Content-type: application/json');
		echo json_encode($json);
	}

	/**
	 * Outputs a failure message in JSON.
	 *
	 * @param string $message Failure message
	 * @return void
	 */
	public static function outputFatalErrorAndExit($errorType, $friendlyMessage = "", $saveErrorToDB = true) {
		$output = array();
		if (!empty($friendlyMessage)) {
			$output["message"] = $friendlyMessage;
		}
		$output["success"] = false;
		
		// Record error in database.
		if ($saveErrorToDB) {
			Error::saveErrorToDB($errorType, $friendlyMessage);
		}

		header('Content-type: application/json');
		echo json_encode($output);

		exit;
	}
}
?>