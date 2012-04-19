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
		if ($optionCount == "" || $optionCount < -1 || $optionCount == 0 || $optionCount == 1 || $optionCount > 6) {
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

	public static function submitQuestions($facebookAccessToken, $questionAnswers, $questionTimes) {
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
		API::saveQuestionTimes($questionTimes);

		
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

		$arr = API::getArrayOfDBResult($result);
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
			} elseif ($type == "responseTimes") {
				API::outputExampleJSON("getStatistics-responseTimes.json");
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
		} elseif ($type == "responseTimes") {
			$output["friends"] = API::getFriendResponseTimes();
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

		// Build list of questions that user has answered.
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
			return array();
		}

		// Store total counts for all friends that user has answered about.
		$friendsArray = array();
		while($totalCountRow = mysql_fetch_array($totalCountResult)){
			$friendSubject = new Subject($totalCountRow["topicFacebookId"]);
			
			$friendArray = array();
			$friendArray["subject"] = $friendSubject->jsonSerialize();
			$friendArray["correctAnswerCount"] = 0;
			$friendArray["totalAnswerCount"] = $totalCountRow["count"];
			
			$friendsArray[$totalCountRow["topicFacebookId"]] = $friendArray;
		}

		// Store correct counts for friends that user has answered any questions correctly about.
		while($correctCountRow = mysql_fetch_array($correctCountResult)){
			$friendsArray[$correctCountRow["topicFacebookId"]]["correctAnswerCount"] = $correctCountRow["count"];
		}
	
		// Sorts the friends by decreasing percentage of correct answers
		function cmp($a, $b)
		{
			 if ($a["correctAnswerCount"]/$a["totalAnswerCount"] == $b["correctAnswerCount"]/$b["totalAnswerCount"]) {
				  return 0;
			 }
			 return ($a["correctAnswerCount"]/$a["totalAnswerCount"] > $b["correctAnswerCount"]/$b["totalAnswerCount"]) ? -1 : 1;
		}
		usort($friendsArray, "cmp");
		
		return array_values($friendsArray);
	}

	private static function getFriendResponseTimes() {
		global $facebookAPI;
		$fastestResponseTime = mysql_query("SELECT MIN(`responseTime`) AS fastest, `topicFacebookId` FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` = `correctFacebookId`
										GROUP BY `topicFacebookId`");
		$slowestResponseTime = mysql_query("SELECT MAX(`responseTime`) AS slowest, `topicFacebookId` FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` != ''
										GROUP BY `topicFacebookId`");
		$averageResponseTime = mysql_query("SELECT AVG(`responseTime`) AS average, `topicFacebookId` FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` != ''
										GROUP BY `topicFacebookId`");

		if (!$fastestResponseTime || !$slowestResponseTime || !$averageResponseTime) {
			return array();
		}

		// Store fastest response times for friends user has answered questions about correctly.
		$friendsArray = array();
		while($averageResponseRow = mysql_fetch_array($averageResponseTime)){
			$friendSubject = new Subject($averageResponseRow["topicFacebookId"]);
			
			$friendArray = array();
			$friendArray["subject"] = $friendSubject->jsonSerialize();
			$friendArray["slowestResponseTime"] = 0;
			$friendArray["fastestResponseTime"] = 0;
			$friendArray["averageResponseTime"] = $averageResponseRow["average"];
			
			$friendsArray[$averageResponseRow["topicFacebookId"]] = $friendArray;
		}

		// Store slowest response times for friends that user has answered any questions about.
		while($slowestResponseRow = mysql_fetch_array($slowestResponseTime)){
			$friendsArray[$slowestResponseRow["topicFacebookId"]]["slowestResponseTime"] = $slowestResponseRow["slowest"];
		}

		// Store average response times for friends that user has answered any questions about.
		while($fastestResponseRow = mysql_fetch_array($fastestResponseTime)){
			$friendsArray[$fastestResponseRow["topicFacebookId"]]["fastestResponseTime"] = $fastestResponseRow["fastest"];
		}
		
		return array_values($friendsArray);
	}

	private static function getQuestionsArray($questionCount, $optionCount, $topicFacebookId, $categoryId) {
		global $facebookAPI;
		
		// Build a list of questions depending upon the type of questions desired.
		$questions = array();
		for ($i = 0; $i < $questionCount; $i++) { 
			if ($optionCount == 0) { // Fill in the blank.
				$questions[] = new FillBlankQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId);
			} elseif ($optionCount == -1) { // Random type.
				$optionCountForQuestion = rand(2, 6);
				$questions[] = new MCQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId, $optionCountForQuestion);
			} else { // Multiple choice.
				$questions[] = new MCQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId, $optionCount);
			}
		}
		
		return $questions;
	}
	
	// Take an array of questions and their answers and update those questions in the database.
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

	private static function saveQuestionTimes($questionTimes){
		global $facebookAPI;

		for($i = 0; $i <count($questionTimes); $i++){
			$questionId = $questionTimes[$i]["questionId"];
			$responseTime = $questionTimes[$i]["responseTime"];

			$updateQuery = "UPDATE questions SET responseTime = '$responseTime' WHERE responseTime = '' AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' AND questionId = '$questionId' LIMIT 1";
			mysql_query($updateQuery);
		}
		return;
	}
	
	private static function getArrayOfDBResult($result) {
		$arr = array();
		while ($row = mysql_fetch_assoc($result)) {
			$arr[] = $row;
		}
		
		return $arr;
	}
	
	// Input an array and return a new one out that is ready to be converted to JSON.
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
	
	public static function outputFailure($error = "") {
		$output = array();
		if (!empty($error)) {
			$output["error"] = $error;
		}
		$output["success"] = false;
		
		header('Content-type: application/json');
		echo json_encode($output);
		
		exit;
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
			// Is this parameter name in the form of "responseTimeOfQuestion[X]"?
			if (strncmp($parameterName, $frontOfParameterName, strlen($frontOfParameterName)) == 0){
				// Create a pair of the questionId and the responseTime.
				$pair = array();
				$questionId = substr($parameterName, strlen($frontOfParameterName), strlen($parameterName) - strlen($frontOfParameterName));
				$pair["questionId"] = intval($questionId);
				$pair["responseTime"] = intval($responseTime);

				// Add this pair to our list.
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
