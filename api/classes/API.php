<?php
/**
 * This class implements the API functions for the GuessThatFriend app.
 *
 *
 */
class API {
	/**
	 * Gets a specified number of questions from the server. On success, prints JSON output containing the newly created questions.
	 *
	 * @param string $facebookAccessToken The users access token.
	 * @param integer $questionCount The number of questions requested.
	 * @param integer $optionCount The number of answer options per question.
	 * @param string $topicFacebookId The Facebook ID of who questions should focus on.
	 * @param integer $categoryId The category the questions should focus on.
	 * @return void
	 */	
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

	/**
	 * Submits questions to the server. On success, prints JSON output confirming the submitted questions.
	 *
	 * @param string $facebookAccessToken The users access token.
	 * @param array $questionAnswers Array containing the answers for the submitted questions.
	 * @param array $questionTimes Array containing the response time for the submitted questions.
	 * @return void
	 */	
	public static function submitQuestions($facebookAccessToken, $questionAnswers, $questionTimes, $skippedQuestionIds) {
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
		$skippedQuestionIdsSucceeded = API::skippedQuestionIds($skippedQuestionIds);

		// Build object to represent the JSON we will display.
		$output = array();
		$output["questionIds"] = $questionIdsOfSavedAnswers;
		$output["skippedQuestionIds"] = $skippedQuestionIdsSucceeded;
		$output["success"] = true;
		$output["duration"] = Util::calculateLoadingDuration($timeStart);
 		
		JSON::outputArrayInJSON($output);
	}
	
	/**
	 * Prints JSON output containing all of the categories.
	 *
	 * @return bool True if query is successful, false otherwise 
	 */	
	public static function getCategories() {
		// Get categories from database.
		$categoryQuery = "SELECT * FROM categories ORDER BY facebookName";
		$result = mysql_query($categoryQuery);
		if (!$result || mysql_num_rows($result) == 0) {
			JSON::outputFatalErrorAndExit("No categories found in database.");
			return false;
		}

		$arr = DB::getArrayOfDBResult($result);
		JSON::outputArrayInJSON($arr);
		
		return true;
	}

	/**
	 * Prints JSON output containing the requested statistics.
	 *
	 * @param string $facebookAccessToken The users access token.
	 * @param int $type The type of statistics to generate.
	 * @return void
	 */	
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
	
	/**
	 * Returns statistics sorted by categories.
	 *
	 * @return array Array of statistics
	 */
	private static function getCategoryStats() {
		$facebookAPI = FacebookAPI::singleton();
		
		$correctCountQuery = "SELECT COUNT(*) AS count, `categoryId`, MIN(`responseTime`) AS fastest FROM questions
							WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
							AND skipped = false
							AND `chosenFacebookId` = `correctFacebookId`
							GROUP BY `categoryId`";
    	$correctCountResult = mysql_query($correctCountQuery);
		
		$totalCountQuery = "SELECT COUNT(*) AS count, `categoryId`, AVG(`responseTime`) AS average FROM questions
							WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
							AND skipped = false
							AND `chosenFacebookId` != ''
							GROUP BY `categoryId`";
		$totalCountResult = mysql_query($totalCountQuery);
		
		if (!$correctCountResult || !$totalCountResult) {
			JSON::outputFatalErrorAndExit("Statistics database query failed.");
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
	
	/**
	 * Returns question history.
	 *
	 * @return array Array of Questions
	 */
	private static function getQuestionHistory() {
		$facebookAPI = FacebookAPI::singleton();
		
		return JSON::jsonSerializeArray(Question::getAnsweredQuestionsFromDB($facebookAPI->getLoggedInUserId()));
	}

	/**
	 * Returns statistics sorted by friends.
	 *
	 * @return array Array of statistics
	 */
	private static function getFriendStats() {
		$facebookAPI = FacebookAPI::singleton();
		
		$correctQuery = "SELECT COUNT(*) AS count, `topicFacebookId`, MIN(`responseTime`) AS fastest FROM questions
						WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
						AND skipped = false
						AND `chosenFacebookId` = `correctFacebookId`
						GROUP BY `topicFacebookId`";
		$correctResult = mysql_query($correctQuery);
		
		$totalQuery = "SELECT COUNT(*) AS count, `topicFacebookId`, AVG(`responseTime`) AS average FROM questions
						WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
						AND skipped = false
						AND `chosenFacebookId` != ''
						GROUP BY `topicFacebookId`";
		$totalResult = mysql_query($totalQuery);
		
		if (!$correctResult || !$totalResult) {
			JSON::outputFatalErrorAndExit("Statistics database query failed.");
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

	/**
	 * Builds a list of questions.
	 *
	 * @param int $questionCount Number of questions to build
	 * @param int $optionCount Number of options to build
	 * @param string $topicFacebookId Facebook ID of question topic
	 * @param int $categoryId ID of topic category
	 * @return array Array of Questions
	 */
	private static function getQuestionsArray($questionCount, $optionCount, $topicFacebookId, $categoryId) {
		$facebookAPI = FacebookAPI::singleton();
		
		// Get existing unanswered questions if available.
		$questions = Question::getUnansweredQuestionsFromDB($facebookAPI->getLoggedInUserId(), $questionCount);
		
		// Build a list of questions depending upon the type of questions desired.
		$questionsNeededCount = $questionCount - count($questions);
		for ($i = 0; $i < $questionsNeededCount; $i++) {
			try {
				if ($optionCount == OptionType::FILL_IN_THE_BLANK) {
					$questions[] = new FillBlankQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId);
				} elseif ($optionCount == OptionType::RANDOM) {
					$optionCountForQuestion = rand(OptionType::MC_MIN, OptionType::MC_MAX);
					$questions[] = new MCQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId, $optionCountForQuestion);
				} else { // Multiple choice.
					$questions[] = new MCQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId, $optionCount);
				}
			} catch (Exception $e) { // If we fail to generate a question, record the error, and continue.
				Error::saveErrorToDB($e->getMessage());
			}
		}
		
		// Check if we generated enough questions.
		$ACCEPTABLE_QUESTION_GENERATION_FAILURE_RATE = 0.50;
		if (count($questions) < $questionCount * (1 - $ACCEPTABLE_QUESTION_GENERATION_FAILURE_RATE)) {
			JSON::outputFatalErrorAndExit("Failed to generate enough questions. There may not be sufficient Facebook data to work with.");
		}
		
		return $questions;
	}
	
	/**
	 * Takes an array of questions and their answers and update those questions in the database.
	 *
	 * @param array $questionAnswers Array of question-answer pairs
	 * @return void
	 */
	private static function saveQuestionAnswers($questionAnswers) {
		$facebookAPI = FacebookAPI::singleton();
		
		$questionIdsOfSavedAnswers = array();
		for($i = 0; $i < count($questionAnswers); $i++) {
			$questionId = $questionAnswers[$i]["id"];
			$facebookId = $questionAnswers[$i]["value"]; // What the user chose.
			
			// Update the user's answer for this question.
			// Note: We only update the answer if the user owned and had not already answered the question.
			$updateQuery = "UPDATE questions SET chosenFacebookId = '$facebookId', answeredAt = NOW() WHERE chosenFacebookId = '' AND skipped = false AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' AND questionId = '$questionId' LIMIT 1";
			mysql_query($updateQuery);
			if (mysql_affected_rows() == 1) { // Keep track of which questions we saved the answer for correctly.
				$questionIdsOfSavedAnswers[] = $questionId;
			}
		}
		
		return $questionIdsOfSavedAnswers;
	}
	
	private static function skippedQuestionIds($questionIds) {
		$facebookAPI = FacebookAPI::singleton();
		
		$skippedQuestionIds = array();
		for($i = 0; $i < count($questionIds); $i++) {
			$questionId = $questionIds[$i];
			
			$updateQuery = "UPDATE questions SET skipped = true, answeredAt = NOW() WHERE chosenFacebookId = '' AND skipped = false AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' AND questionId = '$questionId' LIMIT 1";
			mysql_query($updateQuery);
			if (mysql_affected_rows() == 1) { // Keep track of which questions we successfully skipped.
				$skippedQuestionIds[] = $questionId;
			}
		}
		
		return $skippedQuestionIds;
	}

	/**
	 * Saves question response times to database.
	 *
	 * @param array $questionTimes Array of response times
	 * @return bool True if at least one response time is saved, false otherwise
	 */
	private static function saveQuestionTimes($questionTimes){
		$facebookAPI = FacebookAPI::singleton();

		$success = false;
		for($i = 0; $i <count($questionTimes); $i++){
			$questionId = $questionTimes[$i]["id"];
			$responseTime = $questionTimes[$i]["value"];

			$updateQuery = "UPDATE questions SET responseTime = '$responseTime' WHERE responseTime = '' AND skipped = false AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' AND questionId = '$questionId' LIMIT 1";
			$result = mysql_query($updateQuery);
		}
	}

	public static function getIDPairsFromGETVars($parameterPrefix, $valueIsInt = true) {
		$pairs = array();
		
		foreach ($_GET as $parameterName => $value) {
			// Is this parameter name in the form of "$parameterPrefix[X]"?
			if (strncmp($parameterName, $parameterPrefix, strlen($parameterPrefix)) == 0){
				// Create a pair of the id and the value.
				$pair = array();
				$id = substr($parameterName, strlen($parameterPrefix), strlen($parameterName) - strlen($parameterPrefix));
				$pair["id"] = intval($id);
				$pair["value"] = $valueIsInt ? intval($value) : DB::cleanInputForDatabase($value);

				// Add this pair to our list.
				if ($pair["id"] > 0) {
					$pairs[] = $pair;
				}
			}
		}
		return $pairs;
	}
}
?>
