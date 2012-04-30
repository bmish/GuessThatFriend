<?php
class JSON {
	// Input an array and return a new one out that is ready to be converted to JSON.
	// php 5.4.0 will bring automatic JsonSerializable functionality.
	public static function jsonSerializeArray($array) {
		$ret = array();

		for ($i = 0; $i < count($array); $i++) {
			$ret[] = $array[$i]->jsonSerialize();
		}

		return $ret;
	}

	public static function outputExampleJSON($filename) {
		header('Content-type: application/json');
		require_once("examples/json/".$filename);
	}

	public static function outputArrayInJSON($json) {
		header('Content-type: application/json');
		echo json_encode($json);
	}

	public static function outputFailure($message = "") {
		$output = array();
		if (!empty($message)) {
			$output["message"] = $message;
		}
		$output["success"] = false;

		header('Content-type: application/json');
		echo json_encode($output);

		exit;
	}
}
?>