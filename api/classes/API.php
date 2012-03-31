<?php
class API {
	public static function getQuestions($facebookAccessToken, $questionCount, $optionCount, $subjectFacebookId, $categoryId) {
		global $facebookAPI;
		
		// Check authentication.
		if (!$facebookAPI->authenticate($facebookAccessToken)) { // Show example if not authenticated.
			API::outputExampleJSON("getQuestions.json");
			return;
		}
		
		// Use defaults if necessary.
		if ($questionCount == "") {
			$questionCount = 10;
		}
		if ($optionCount == "") {
			$optionCount = 4;
		}
		
		// Create questions.
		$questions = API::getQuestionsArray($questionCount, $optionCount, $subjectFacebookId, $categoryId);

		// Build object to represent the JSON we will display.
		$output = array();
		$output["questions"] = API::jsonSerializeArray($questions);
		$output["success"] = true;
 		
		API::outputArrayInJSON($output);
	}

	public static function submitQuestions($facebookAccessToken, $questionAnswers) {
		global $facebookAPI;
		
		// Check authentication.
		if (!$facebookAPI->authenticate($facebookAccessToken)) { // Show example if not authenticated.
			API::outputExampleJSON("submitQuestions.json");
			return;
		}
		
		// Update the user's answers for the given questions.
		$questionIdsOfSavedAnswers = API::saveQuestionAnswers($questionAnswers);
		
		// Build object to represent the JSON we will display.
		$output = array();
		$output["questionIds"] = $questionIdsOfSavedAnswers;
		$output["success"] = true;
 		
		API::outputArrayInJSON($output);
	}
	
	public static function getCategories() {
		// Get categories from database.
		$result = mysql_query("SELECT * FROM categories");
		if (!$result || mysql_num_rows($result) == 0) {
			return;
		}

		$arr = API::getArrayOfResult($result);
		API::outputArrayInJSON($arr);
	}
	
	private static function getQuestionsArray($questionCount, $optionCount, $subjectFacebookId, $categoryId) {
		global $facebookAPI;
		
		$questions = array();
		for ($i = 0; $i < $questionCount; $i++) { 
			if ($optionCount == 0) { // Fill in the blank.
				$question = new FillBlankQuestion($facebookAPI->getLoggedInUserId(), $subjectFacebookId, $categoryId);
			} elseif ($optionCount == -1) { // Random type.
				
			} else { // Multiple choice.
				$question = new MCQuestion($facebookAPI->getLoggedInUserId(), $subjectFacebookId, $categoryId, $optionCount);
			}
			
			$questions[] = $question;
		}
		
		return $questions;
	}
	
	private static function saveQuestionAnswers($questionAnswers) {
		global $facebookAPI;
		
		$questionIdsOfSavedAnswers = array();
		for($i = 0; $i < count($questionAnswers); $i++) {
			$questionId = $questionAnswers[$i]["questionId"];
			$facebookId = $questionAnswers[$i]["facebookId"]; // What the user chose.
			
			// Update the user's answer for this question.
			// Note: We only update the answer if the user owned and had not already answered the question.
			mysql_query("UPDATE questions SET chosenFacebookId = '$facebookId' WHERE chosenFacebookId = '' AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' LIMIT 1");
			if (mysql_affected_rows() == 1) { // Keep track of which questions we saved the answer for correctly.
				$questionIdsOfSavedAnswers[] = $questionId;
			}
		}
		
		return $questionIdsOfSavedAnswers;
	}
	
	private static function getArrayOfResult($result) {
		$arr = array();
		while ($row = mysql_fetch_assoc($result)) {
			$arr[] = $row;
		}
		
		return $arr;
	}
	
	// Input an array and return a new one out of the desired JSON-use objects of each of input's elements.
	// php 5.4.0 will bring automatic JsonSerializable functionality.
	public static function jsonSerializeArray($array) {
		$ret = array();
		
		for ($i = 0; $i < count($array); $i++) {
			$ret[] = $array[$i]->jsonSerialize();
		}
		
		return $ret;
	}
	
	private static function outputExampleJSON($filename) {
		header('Content-type: application/json');
		require_once("examples/json/".$filename);
	}
	
	private static function outputArrayInJSON($json) {
		header('Content-type: application/json');
		echo json_encode($json);
	}
	
	private static function outputFailure() {
		$output = array();
		$output["success"] = false;
		
		header('Content-type: application/json');
		echo json_encode($output);
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
