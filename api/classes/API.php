<?php
class API {
	public static function getQuestions($facebookAccessToken, $questionCount, $optionCount, $topicFacebookId, $categoryId) {
		$facebookAPI = FacebookAPI::singleton();

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
		$facebookAPI = FacebookAPI::singleton();
		
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
			API::outputFailure("No categories found in database.");
			return;
		}

		$arr = API::getArrayOfDBResult($result);
		API::outputArrayInJSON($arr);
	}

	public static function getStatistics($facebookAccessToken, $type){
		$facebookAPI = FacebookAPI::singleton();
		
		// Start timing.
		$timeStart = microtime(true);
		
		// Use defaults if necessary.
		if (empty($type) || ($type != "friends" && $type != "categories" && $type != "history")) {
			$type = "friends";
		}
		
		// Check authentication.
		if (!$facebookAPI->authenticate($facebookAccessToken)) { // Show example if not authenticated.
			if ($type == "friends") {
				API::outputExampleJSON("getStatistics-friends.json");
			} elseif ($type == "categories") {
				API::outputExampleJSON("getStatistics-categories.json");
			} elseif ($type == "history") {
				API::outputExampleJSON("getStatistics-history.json");
			}
			
			return;
		}

		// Build object to represent the JSON we will display.
		$output = array();
		$output["success"] = true;

		// Choose what kind of statistics to generate.
		if ($type == "friends") {
			$output["friends"] = API::getFriendStats();
		} elseif ($type == "history") {
			$output["questions"] = API::getQuestionHistory();
		} elseif ($type == "categories") {
			$output["categories"] = API::getCategoryStats();
		}
		$output["duration"] = API::calculateLoadingDuration($timeStart);

		API::outputArrayInJSON($output);
	}
	
  private static function getCategoryStats() {
		$facebookAPI = FacebookAPI::singleton();
    	$correctCountResult = mysql_query("SELECT COUNT(*) AS count, `categoryId`, MIN(`responseTime`) AS fastest FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` = `correctFacebookId`
										GROUP BY `categoryId`");
		$totalCountResult = mysql_query("SELECT COUNT(*) AS count, `categoryId`, AVG(`responseTime`) AS average FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` != ''
										GROUP BY `categoryId`");
		if (!$correctCountResult || !$totalCountResult) {
			API::outputFailure("Statistics database query failed.");
			return array();
		}

		// Store total counts for all categories that user has answered about.
		$categoriesArray = array();
		while($totalCountRow = mysql_fetch_array($totalCountResult)){
			$categoryArray = array();
			
      		$category = new Category($totalCountRow["categoryId"]);
			$categoryArray["category"] = $category->jsonSerialize();
			$categoryArray["correctAnswerCount"] = 0;
			$categoryArray["totalAnswerCount"] = $totalCountRow["count"];
      		$categoryArray["fastestCorrectResponseTime"] = -1;
			$categoryArray["averageResponseTime"] = round($totalCountRow["average"]);
			
			$categoriesArray[$totalCountRow["categoryId"]] = $categoryArray;
		}

		// Store correct counts for categories that user has answered any questions correctly about.
		while($correctCountRow = mysql_fetch_array($correctCountResult)){
			$categoriesArray[$correctCountRow["categoryId"]]["correctAnswerCount"] = $correctCountRow["count"];
      		$categoriesArray[$correctCountRow["categoryId"]]["fastestCorrectResponseTime"] = $correctCountRow["fastest"];
		}
	
		// Sorts the categories by decreasing percentage of correct answers and then by total correct answers.
		function cmp($a, $b) {
			$totalA = $a["totalAnswerCount"];
			$totalB = $b["totalAnswerCount"];
			$fractionA = $a["correctAnswerCount"]/$totalA;
			$fractionB = $b["correctAnswerCount"]/$totalB;
			if ($fractionA == $fractionB) { // Defer to second sorting criteria.
				if ($totalA == $totalB) {
					return 0;
				}
				
				return ($totalA > $totalB) ? -1 : 1;
			}
			
			return ($fractionA > $fractionB) ? -1 : 1;
		}
		
		usort($categoriesArray, "cmp");

		return array_values($categoriesArray);
	}
  
	private static function calculateLoadingDuration($timeStart) {
		return round(microtime(true) - $timeStart, 2);
	}
	
	private static function getQuestionHistory() {
		$facebookAPI = FacebookAPI::singleton();
		
		return API::jsonSerializeArray(Question::getQuestionsFromDB($facebookAPI->getLoggedInUserId()));
	}

	private static function getFriendStats() {
		$facebookAPI = FacebookAPI::singleton();
		$correctResult = mysql_query("SELECT COUNT(*) AS count, `topicFacebookId`, MIN(`responseTime`) AS fastest FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` = `correctFacebookId`
										GROUP BY `topicFacebookId`");
		$totalResult = mysql_query("SELECT COUNT(*) AS count, `topicFacebookId`, AVG(`responseTime`) AS average FROM questions
										WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
										AND `chosenFacebookId` != ''
										GROUP BY `topicFacebookId`");
		if (!$correctResult || !$totalResult) {
			API::outputFailure("Statistics database query failed.");
			return array();
		}

		// Store total counts for all friends that user has answered about.
		$friendsArray = array();
		while($totalRow = mysql_fetch_array($totalResult)){
			$friendSubject = new Subject($totalRow["topicFacebookId"]);
			
			$friendArray = array();
			$friendArray["subject"] = $friendSubject->jsonSerialize();
			$friendArray["correctAnswerCount"] = 0;
			$friendArray["totalAnswerCount"] = $totalRow["count"];
			$friendArray["fastestCorrectResponseTime"] = -1;
			$friendArray["averageResponseTime"] = round($totalRow["average"]);
			
			$friendsArray[$totalRow["topicFacebookId"]] = $friendArray;
		}

		// Store correct counts for friends that user has answered any questions correctly about.
		while($correctRow = mysql_fetch_array($correctResult)){
			$friendsArray[$correctRow["topicFacebookId"]]["correctAnswerCount"] = $correctRow["count"];
			$friendsArray[$correctRow["topicFacebookId"]]["fastestCorrectResponseTime"] = $correctRow["fastest"];
		}
	
		// Sorts the friends by decreasing percentage of correct answers and then by total correct answers.
		function cmp($a, $b) {
			$totalA = $a["totalAnswerCount"];
			$totalB = $b["totalAnswerCount"];
			$fractionA = $a["correctAnswerCount"]/$totalA;
			$fractionB = $b["correctAnswerCount"]/$totalB;
			if ($fractionA == $fractionB) { // Defer to second sorting criteria.
				if ($totalA == $totalB) {
					return 0;
				}
				
				return ($totalA > $totalB) ? -1 : 1;
			}
			
			return ($fractionA > $fractionB) ? -1 : 1;
		}
		
		usort($friendsArray, "cmp");

		return array_values($friendsArray);
	}

	private static function getQuestionsArray($questionCount, $optionCount, $topicFacebookId, $categoryId) {
		$facebookAPI = FacebookAPI::singleton();
		
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
		$facebookAPI = FacebookAPI::singleton();
		
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
		$facebookAPI = FacebookAPI::singleton();

		for($i = 0; $i <count($questionTimes); $i++){
			$questionId = $questionTimes[$i]["questionId"];
			$responseTime = $questionTimes[$i]["responseTime"];

			$updateQuery = "UPDATE questions SET responseTime = '$responseTime' WHERE responseTime = '' AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' AND questionId = '$questionId' LIMIT 1";
			mysql_query($updateQuery);
		}
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
				$pair["facebookId"] = DB::cleanInputForDatabase($facebookId);
				
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
}
?>
