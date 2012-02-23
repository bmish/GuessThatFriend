<?php
class API {
	public static function getQuiz() {
		//Change this ...


		/*
		Quiz q = new Quiz;
		1.GENERATE QUIZ: initialize quiz variables with facebook data
		2.PRINT QUIZ: print quiz details in json format 
		3.STORE QUIZ: in db
		*/

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

		$arr = API::getArrayOfResult($result);
		API::outputJSON($arr);
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
