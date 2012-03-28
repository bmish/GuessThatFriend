<?php
class API {
	public static function getQuestions($facebookAccessToken, $questionCount, $optionCount, $friendFacebookId, $categoryId) {
		/* TODO:
		Quiz q = new Quiz;
		1.GENERATE QUIZ: initialize quiz variables with facebook data
		2.PRINT QUIZ: print quiz details in json format 
		3.STORE QUIZ: in db
		*/

		header('Content-type: application/json');
		require_once("examples/json/getQuestions.json");
	}

	public static function submitQuestions($facebookAccessToken, $questionAnswers) {
		header('Content-type: application/json');
		require_once("examples/json/submitQuestions.json");
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
	
	public static function getQuestionAnswersFromGETVars() {
		$frontOfParameterName = "facebookIdOfQuestion";
		$questionAnswers = array();
		
		foreach ($_GET as $parameterName => $facebookId) {
			// Is this parameter name in the form of "facebookIdOfQuestion[X]"?
			if (strncmp($parameterName, $frontOfParameterName, strlen($frontOfParameterName)) == 0) {
				// Create a pair of the questionId and the facebookId (what the user chose for that question).
				$pair = array();
				$questionId = substr($parameterName, strlen($frontOfParameterName), strlen($parameterName) - strlen($frontOfParameterName));
				$pair["questionId"] = intval($questionId);
				$pair["facebookId"] = cleanInputForDatabase($facebookId);
				
				// Add this pair to our list.
				if ($pair["questionId"] > 0) {
					$questionAnswers[] = $pair;
				}
			}
		}
		
		return $questionAnswers;
	}
}
?>
