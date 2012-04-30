<?php
class API {
	public static function getQuestions($facebookAccessToken, $questionCount, $optionCount, $topicFacebookId, $categoryId) {
		$facebookAPI = FacebookAPI::singleton();

		// Start timing.
		$timeStart = microtime(true);
		
		// Check authentication.
		if (!$facebookAPI->authenticate($facebookAccessToken)) { // Show example if not authenticated.
			JSON::outputExampleJSON("getQuestions.json");
			return;
		}
		
		// Use defaults if necessary.
		if ($questionCount == "") {
			$questionCount = 10;
		}
		if (!OptionType::isValid($optionCount)) {
			$optionCount = OptionType::DEFAULT_TYPE;
		}
		
		// Create questions.
		$questions = API::getQuestionsArray($questionCount, $optionCount, $topicFacebookId, $categoryId);

		// Build object to represent the JSON we will display.
		$output = array();
		$output["questions"] = JSON::jsonSerializeArray($questions);
		$output["success"] = true;
		$output["duration"] = Util::calculateLoadingDuration($timeStart);
		
		JSON::outputArrayInJSON($output);
	}

	public static function submitQuestions($facebookAccessToken, $questionAnswers, $questionTimes) {
		$facebookAPI = FacebookAPI::singleton();
		
		// Start timing.
		$timeStart = microtime(true);
		
		// Check authentication.
		if (!$facebookAPI->authenticate($facebookAccessToken)) { // Show example if not authenticated.
			JSON::outputExampleJSON("submitQuestions.json");
			return;
		}
		
		// Update the user's answers for the given questions.
		$questionIdsOfSavedAnswers = API::saveQuestionAnswers($questionAnswers);
		API::saveQuestionTimes($questionTimes);

		// Build object to represent the JSON we will display.
		$output = array();
		$output["questionIds"] = $questionIdsOfSavedAnswers;
		$output["success"] = true;
		$output["duration"] = Util::calculateLoadingDuration($timeStart);
 		
		JSON::outputArrayInJSON($output);
	}
	
	public static function getCategories() {
		// Get categories from database.
		$categoryQuery = "SELECT * FROM categories";
		$result = mysql_query($categoryQuery);
		if (!$result || mysql_num_rows($result) == 0) {
			JSON::outputFailure("No categories found in database.");
			return false;
		}

		$arr = DB::getArrayOfDBResult($result);
		JSON::outputArrayInJSON($arr);
		
		return true;
	}

	public static function getStatistics($facebookAccessToken, $type){
		$facebookAPI = FacebookAPI::singleton();
		
		// Start timing.
		$timeStart = microtime(true);
		
		// Use defaults if necessary.
		if (!StatisticType::isValid($type)) {
			$type = StatisticType::DEFAULT_TYPE;
		}
		
		// Check authentication.
		if (!$facebookAPI->authenticate($facebookAccessToken)) { // Show example if not authenticated.
			if ($type == StatisticType::FRIENDS) {
				JSON::outputExampleJSON("getStatistics-friends.json");
			} elseif ($type == StatisticType::CATEGORIES) {
				JSON::outputExampleJSON("getStatistics-categories.json");
			} elseif ($type == StatisticType::HISTORY) {
				JSON::outputExampleJSON("getStatistics-history.json");
			}
			
			return;
		}

		// Build object to represent the JSON we will display.
		$output = array();
		$output["success"] = true;

		// Choose what kind of statistics to generate.
		if ($type == StatisticType::FRIENDS) {
			$output["friends"] = API::getFriendStats();
		} elseif ($type == StatisticType::CATEGORIES) {
			$output["categories"] = API::getCategoryStats();
		} elseif ($type == StatisticType::HISTORY) {
			$output["questions"] = API::getQuestionHistory();
		}
		$output["duration"] = Util::calculateLoadingDuration($timeStart);

		JSON::outputArrayInJSON($output);
	}
	
  private static function getCategoryStats() {
		$facebookAPI = FacebookAPI::singleton();
		
		$correctCountQuery = "SELECT COUNT(*) AS count, `categoryId`, MIN(`responseTime`) AS fastest FROM questions
							WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
							AND `chosenFacebookId` = `correctFacebookId`
							GROUP BY `categoryId`";
    	$correctCountResult = mysql_query($correctCountQuery);
		
		$totalCountQuery = "SELECT COUNT(*) AS count, `categoryId`, AVG(`responseTime`) AS average FROM questions
							WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
							AND `chosenFacebookId` != ''
							GROUP BY `categoryId`";
		$totalCountResult = mysql_query($totalCountQuery);
		
		if (!$correctCountResult || !$totalCountResult) {
			JSON::outputFailure("Statistics database query failed.");
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
	
	private static function getQuestionHistory() {
		$facebookAPI = FacebookAPI::singleton();
		
		return JSON::jsonSerializeArray(Question::getQuestionsFromDB($facebookAPI->getLoggedInUserId()));
	}

	private static function getFriendStats() {
		$facebookAPI = FacebookAPI::singleton();
		
		$correctQuery = "SELECT COUNT(*) AS count, `topicFacebookId`, MIN(`responseTime`) AS fastest FROM questions
						WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
						AND `chosenFacebookId` = `correctFacebookId`
						GROUP BY `topicFacebookId`";
		$correctResult = mysql_query($correctQuery);
		
		$totalQuery = "SELECT COUNT(*) AS count, `topicFacebookId`, AVG(`responseTime`) AS average FROM questions
						WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
						AND `chosenFacebookId` != ''
						GROUP BY `topicFacebookId`";
		$totalResult = mysql_query($totalQuery);
		
		if (!$correctResult || !$totalResult) {
			JSON::outputFailure("Statistics database query failed.");
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
			if ($optionCount == OptionType::FILL_IN_THE_BLANK) {
				$questions[] = new FillBlankQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId);
			} elseif ($optionCount == OptionType::RANDOM) {
				$optionCountForQuestion = rand(OptionType::MC_MIN, OptionType::MC_MAX);
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

		$success = false;
		for($i = 0; $i <count($questionTimes); $i++){
			$questionId = $questionTimes[$i]["questionId"];
			$responseTime = $questionTimes[$i]["responseTime"];

			$updateQuery = "UPDATE questions SET responseTime = '$responseTime' WHERE responseTime = '' AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' AND questionId = '$questionId' LIMIT 1";
			$result = mysql_query($updateQuery);

			if ($result)
				$success = true;
		}
		return $success;
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
