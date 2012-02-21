<?php
class API {
	public static function getQuiz() {
		header('Content-type: application/json');
		require_once("examples/json/getQuiz.json");
	}

	public static function submitQuiz() {
		header('Content-type: application/json');
		require_once("examples/json/submitQuiz.json");
	}

	public static function getCategories() {
		// Get categories from database.
		$result = mysql_query("SELECT * FROM categories");
		if (!$result || mysql_num_rows($result) == 0) {
			return;
		}

		$arr = getArrayOfResult($result);

		outputJSON($arr);
	}
	
	private static function getArrayOfResult($result) {
		$arr = array();
		while ($row = mysql_fetch_assoc($result)) {
			$arr[] = $row;
		}
		
		return $arr;
	}
	
	private static function outputJSON($json) {
		header('Content-type: application/json');
		echo json_encode($json);
	}
}
?>
