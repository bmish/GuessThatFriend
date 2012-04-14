<?php
class API {
	public static function getQuestions($facebookAccessToken, $questionCount, $optionCount, $topicFacebookId, $categoryId) {
		global $facebookAPI;
		
		// Start timing.
		$timeStart = microtime(true);
		
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
		$questions = API::getQuestionsArray($questionCount, $optionCount, $topicFacebookId, $categoryId);

		// Build object to represent the JSON we will display.
		$output = array();
		$output["questions"] = API::jsonSerializeArray($questions);
		$output["success"] = true;
		$output["duration"] = API::calculateLoadingDuration($timeStart);

		API::outputArrayInJSON($output);
	}

	public static function submitQuestions($facebookAccessToken, $questionAnswers) {
		global $facebookAPI;
		
		// Start timing.
		$timeStart = microtime(true);
		
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
		$output["duration"] = API::calculateLoadingDuration($timeStart);
 		
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

	public static function getStatistics($facebookAccessToken, $type){
		global $facebookAPI;
		
		// Start timing.
		$timeStart = microtime(true);
		
		// Check authentication.
		if (!$facebookAPI->authenticate($facebookAccessToken)) { // Show example if not authenticated.
			if ($type == "answerCounts") {
				API::outputExampleJSON("getStatistics-answerCounts.json");
			} elseif ($type == "history") {
				API::outputExampleJSON("getStatistics-history.json");
			}

			return;
		}
		
		// Use defaults if necessary.
		if (empty($type)) {
			$type = "answerCounts";
		}

		// Build object to represent the JSON we will display.
		$output = array();
		$output["success"] = true;

		// Choose what kind of statistics to generate.
		if ($type == "answerCounts") {
			$output["friends"] = API::getFriendAnswerCounts();
		} elseif ($type == "history") {
			$output["questions"] = API::getQuestionHistory();
		}
		$output["duration"] = API::calculateLoadingDuration($timeStart);

		API::outputArrayInJSON($output);
	}
	
	private static function calculateLoadingDuration($timeStart) {
		return round(microtime(true) - $timeStart, 2);
	}

	private static function getQuestionHistory() {
		global $facebookAPI;
		
		$questions = mysql_query("SELECT text, correctFacebookId, chosenFacebookId FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` != ''");
		if (!$questions) {
			return array();
		}

		$questionsArray = array();
		while($questionRow = mysql_fetch_array($questions)) {
			$questionArray = array();
			$questionArray["text"] = $questionRow["text"];
			$questionArray["correctFacebookId"] = $questionRow["correctFacebookId"];
			$questionArray["chosenFacebookId"] = $questionRow["chosenFacebookId"];
			
			$questionsArray[] = $questionArray;
		}

		return array_values($questionsArray);
	}

	private static function getFriendAnswerCounts() {
		global $facebookAPI;
		$correctCountResult = mysql_query("SELECT COUNT(*) AS count, `topicFacebookId` FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` = `correctFacebookId`
										GROUP BY `topicFacebookId`");
		$totalCountResult = mysql_query("SELECT COUNT(*) AS count, `topicFacebookId` FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` != ''
										GROUP BY `topicFacebookId`");
		if (!$correctCountResult || !$totalCountResult) {
			return;
		}

		$friendsArray = array();
		while($totalCountRow = mysql_fetch_array($totalCountResult)){
			$friendSubject = new Subject($totalCountRow["topicFacebookId"]);
			
			$friendArray = array();
			$friendArray["subject"] = $friendSubject->jsonSerialize();
			$friendArray["correctAnswerCount"] = 0;
			$friendArray["totalAnswerCount"] = $totalCountRow["count"];
			
			$friendsArray[$totalCountRow["topicFacebookId"]] = $friendArray;
		}

		while($correctCountRow = mysql_fetch_array($correctCountResult)){
			$friendsArray[$correctCountRow["topicFacebookId"]]["correctAnswerCount"] = $correctCountRow["count"];
		}
		
		return array_values($friendsArray);
	}
	
	private static function getQuestionsArray($questionCount, $optionCount, $topicFacebookId, $categoryId) {
		global $facebookAPI;
		
		$questions = array();
		for ($i = 0; $i < $questionCount; $i++) { 
			if ($optionCount == 0) { // Fill in the blank.
				$questions[] = new FillBlankQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId);
			} elseif ($optionCount == -1) { // Random type.
				
			} else { // Multiple choice.
				$questions[] = new MCQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId, $optionCount);
			}
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
			$updateQuery = "UPDATE questions SET chosenFacebookId = '$facebookId', answeredAt = NOW() WHERE chosenFacebookId = '' AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' AND questionId = '$questionId' LIMIT 1";
			mysql_query($updateQuery);
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
				$pair["facebookId"] = API::cleanInputForDatabase($facebookId);
				
				// Add this pair to our list.
				if ($pair["questionId"] > 0) {
					$questionAnswers[] = $pair;
				}
			}
		}
		
		return $questionAnswers;
	}

	public static function getQuestionTimesFromGETVars() {
		$frontOfParameterName = "responseTimeOfQuestion";
		$questionTimes = array();

		foreach ($_GET as $parameterName => $responseTime) {
			if (strncmp($parameterName, $frontOfParameterName, strlen($frontOfParameterName)) == 0){
				$pair = array();
				$questionId = substr($paramaterName, strlen($frontOfParameterName), strlen($parameterName) - strlen($frontOfParameterName));
				$pair["questionId"] = intval($questionId);
				$pair["responseTime"] = $responseTime;

				if ($pair["questionId"] > 0) {
					$questionTimes[] = $pair;
				}
			}
		}
		return $questionTimes;
	}
	
	public static function cleanInputForDatabase($input) {
		return addslashes(trim($input));
	}

	public static function cleanInputForDisplay($input) {
		return htmlentities(trim($input), ENT_QUOTES, 'UTF-8');
	}

	public static function cleanOutputFromDatabase($output) {
		return stripslashes($output);
	}
}
?>
