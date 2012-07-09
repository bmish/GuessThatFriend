<?php
/**
 * This class implements the API functions for the GuessThatFriend app.
 *
 */
class API {
	const OUTLIER_RESPONSE_TIME_THRESHOLD_MS = 30000;
	const MIN_UNANSWERED_QUESTION_STOCKPILE_SIZE = 25;
	const ACCEPTABLE_QUESTION_GENERATION_FAILURE_RATE = 0.50;
	const DEFAULT_QUESTION_COUNT = 10;
	const DEFAULT_STATS_HISTORY_QUESTION_COUNT = 20;
	const MIN_ACCEPTABLE_FRIEND_COUNT = 5;
	
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
			$questionCount = API::DEFAULT_QUESTION_COUNT;
		}
		if (!OptionType::isValid($optionCount)) {
			$optionCount = OptionType::DEFAULT_TYPE;
		}
		if (!Category::exists($categoryId)) {
			$categoryId = null;
		}
		
		// Create questions.
		$questions = API::getQuestionsArray($questionCount, $optionCount, $topicFacebookId, $categoryId);

		// Build object to represent the JSON we will display.
		$output = array();
		$output["questions"] = JSON::jsonSerializeArray($questions);
		$output["success"] = true;
		$output["duration"] = Util::calculateLoadingDuration($timeStart);
		
		JSON::outputArrayInJSON($output);
		
		// Refill questions.
		API::sendAsyncRefillUnansweredQuestionsRequest();
	}
	
	private static function sendAsyncRefillUnansweredQuestionsRequest() {
		$facebookAPI = FacebookAPI::singleton();
		
		$requestURL = Util::curPageURLWithoutGETParams();
		
		$params = array();
		$params["cmd"] = 'refillUnansweredQuestions';
		$params["facebookAccessToken"] = $facebookAPI->getAccessToken();
		
		Util::curl_request_async($requestURL,$params,"GET");
	}
	
	public static function refillUnansweredQuestions($facebookAccessToken) {
		$facebookAPI = FacebookAPI::singleton();
		
		// Start timing.
		$timeStart = microtime(true);
		
		// Check authentication.
		if (!$facebookAPI->authenticate($facebookAccessToken)) { // Show example if not authenticated.
			JSON::outputExampleJSON("refillUnansweredQuestions.json");
			return;
		}
		
		// Determine how many questions to generate.
		$unansweredQuestionCount = Question::countUnansweredQuestionsFromDB($facebookAPI->getLoggedInUserId());
		$questionsToGenerateCount = 0;
		if ($unansweredQuestionCount < API::MIN_UNANSWERED_QUESTION_STOCKPILE_SIZE) {
			$questionsToGenerateCount = API::MIN_UNANSWERED_QUESTION_STOCKPILE_SIZE - $unansweredQuestionCount;
			$questionsNew = API::generateQuestions($questionsToGenerateCount, OptionType::DEFAULT_TYPE, null, null);
		}
		
		// Build object to represent the JSON we will display.
		$output = array();
		$output["questionsGenerated"] = $questionsToGenerateCount;
		$output["success"] = true;
		$output["duration"] = Util::calculateLoadingDuration($timeStart);
		
		JSON::outputArrayInJSON($output);
	}

	/**
	 * Submits questions to the server. On success, prints JSON output confirming the submitted questions.
	 *
	 * @param string $facebookAccessToken The users access token.
	 * @param array $questionAnswers Array containing the answers for the submitted questions.
	 * @param array $questionResponseTimes Array containing the response time for the submitted questions.
	 * @return void
	 */	
	public static function submitQuestions($facebookAccessToken, $questionAnswers, $questionResponseTimes, $skippedQuestionIds) {
		$facebookAPI = FacebookAPI::singleton();
		
		// Start timing.
		$timeStart = microtime(true);
		
		// Check authentication.
		if (!$facebookAPI->authenticate($facebookAccessToken)) { // Show example if not authenticated.
			JSON::outputExampleJSON("submitQuestions.json");
			return;
		}
		
		// Update the user's answers for the given questions.
		$questionIdsOfSavedAnswers = API::saveQuestionAnswersAndResponseTimes($questionAnswers, $questionResponseTimes);
		$skippedQuestionIdsSucceeded = API::skippedQuestionIds($skippedQuestionIds);

		// Build object to represent the JSON we will display.
		$output = array();
		$output["savedQuestionIds"] = $questionIdsOfSavedAnswers;
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
			JSON::outputFatalErrorAndExit("SelectCategoriesFromDBFailed","No categories found in database.");
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
	public static function getStatistics($facebookAccessToken, $type, $questionCount){
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
			$output["questions"] = API::getQuestionHistory($questionCount);
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

		$averageQuery = "SELECT `categoryId`, AVG(`responseTime`) AS average FROM questions
							WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
							AND skipped = false
							AND `chosenFacebookId` != ''
							AND responseTime < ".API::OUTLIER_RESPONSE_TIME_THRESHOLD_MS."
							GROUP BY `categoryId`";
		$averageResult = mysql_query($averageQuery);
		
		$totalCountQuery = "SELECT COUNT(*) AS count, `categoryId` FROM questions
							WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
							AND skipped = false
							AND `chosenFacebookId` != ''
							GROUP BY `categoryId`";
		$totalCountResult = mysql_query($totalCountQuery);
		
		if (!$correctCountResult || !$totalCountResult) {
			JSON::outputFatalErrorAndExit("SelectCategoryStatsFromDBFailed","Statistics database query failed.");
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
			$categoryArray["averageResponseTime"] = -1;
			
			$categoriesArray[$totalCountRow["categoryId"]] = $categoryArray;
		}
		
		// Fill in the averages.
		while($averageRow = mysql_fetch_array($averageResult)){
			$categoriesArray[$averageRow["categoryId"]]["averageResponseTime"] = round($averageRow["average"]);
		}

		// Store correct counts for categories that user has answered any questions correctly about.
		while($correctCountRow = mysql_fetch_array($correctCountResult)){
			$categoriesArray[$correctCountRow["categoryId"]]["correctAnswerCount"] = $correctCountRow["count"];
      		$categoriesArray[$correctCountRow["categoryId"]]["fastestCorrectResponseTime"] = $correctCountRow["fastest"];
		}
		
		usort($categoriesArray, "API::cmpFriendsOrCategories");

		return array_values($categoriesArray);
	}
	
	/**
	 * Returns question history.
	 *
	 * @return array Array of Questions
	 */
	private static function getQuestionHistory($questionCount) {
		$facebookAPI = FacebookAPI::singleton();
		
		// If user hasn't specified how many questions to retrieve, use the default count.
		if ($questionCount == 0) {
			$questionCount = API::DEFAULT_STATS_HISTORY_QUESTION_COUNT;
		}
		
		return JSON::jsonSerializeArray(Question::getAnsweredQuestionsFromDB($facebookAPI->getLoggedInUserId(), $questionCount));
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
		
		$averageQuery = "SELECT `topicFacebookId`, AVG(`responseTime`) AS average FROM questions
						WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
						AND skipped = false
						AND `chosenFacebookId` != ''
						AND responseTime < ".API::OUTLIER_RESPONSE_TIME_THRESHOLD_MS."
						GROUP BY `topicFacebookId`";
		$averageResult = mysql_query($averageQuery);
		
		$totalQuery = "SELECT COUNT(*) AS count, `topicFacebookId`, AVG(`responseTime`) AS average FROM questions
						WHERE `ownerFacebookId` = '".$facebookAPI->getLoggedInUserId()."'
						AND skipped = false
						AND `chosenFacebookId` != ''
						GROUP BY `topicFacebookId`";
		$totalResult = mysql_query($totalQuery);
		
		if (!$correctResult || !$averageQuery || !$totalResult) {
			JSON::outputFatalErrorAndExit("SelectFriendsStatsFromDBFailed","Statistics database query failed.");
			return array();
		}

		// Store total counts for all friends that user has answered about.
		$friendsArray = array();
		while($totalRow = mysql_fetch_array($totalResult)){
			try {
				$friendSubject = new Subject($totalRow["topicFacebookId"]);
			} catch (Exception $e) {
				Error::saveExceptionToDB($e);
				continue;
			}
			
			$friendArray = array();
			$friendArray["subject"] = $friendSubject->jsonSerialize();
			$friendArray["correctAnswerCount"] = 0;
			$friendArray["totalAnswerCount"] = $totalRow["count"];
			$friendArray["fastestCorrectResponseTime"] = -1;
			$friendArray["averageResponseTime"] = -1;
			
			$friendsArray[$totalRow["topicFacebookId"]] = $friendArray;
		}
		
		// Fill in the averages.
		while($averageRow = mysql_fetch_array($averageResult)){
			$friendsArray[$averageRow["topicFacebookId"]]["averageResponseTime"] = round($averageRow["average"]);
		}

		// Store correct counts for friends that user has answered any questions correctly about.
		while($correctRow = mysql_fetch_array($correctResult)){
			$friendsArray[$correctRow["topicFacebookId"]]["correctAnswerCount"] = $correctRow["count"];
			$friendsArray[$correctRow["topicFacebookId"]]["fastestCorrectResponseTime"] = $correctRow["fastest"];
		}
	
		usort($friendsArray, "API::cmpFriendsOrCategories");

		return array_values($friendsArray);
	}
	
	// Sorts by decreasing percentage of correct answers, then by total correct answers, then by names alphabetically.
	private static function cmpFriendsOrCategories($a, $b) {
		$totalA = $a["totalAnswerCount"];
		$totalB = $b["totalAnswerCount"];
		$fractionA = $a["correctAnswerCount"]/$totalA;
		$fractionB = $b["correctAnswerCount"]/$totalB;
		
		// Handle either an object with a category or subject.
		$hasCategory = isset($a["category"]);
		$nameA = $hasCategory ? $a["category"]["facebookName"] : $a["subject"]["name"];
		$nameB = $hasCategory ? $b["category"]["facebookName"] : $b["subject"]["name"];

		// Check sorting criteria.
		if ($fractionA == $fractionB) { // Defer to second sorting criterion.
			if ($totalA == $totalB) { // Defer to third sorting criterion.
				return strcmp($nameA, $nameB); // Third sorting criterion.
			}
			
			return ($totalA > $totalB) ? -1 : 1; // Second sorting criterion.
		}
		
		return ($fractionA > $fractionB) ? -1 : 1; // First sorting criterion.
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
		$questionsExisting = Question::getUnansweredQuestionsFromDB($facebookAPI->getLoggedInUserId(), $questionCount, $optionCount, $topicFacebookId, $categoryId);
		
		// Build a list of questions depending upon the type of questions desired.
		$questionsNeededCount = $questionCount - count($questionsExisting);
		$questionsNew = API::generateQuestions($questionsNeededCount, $optionCount, $topicFacebookId, $categoryId);
		
		// Combine existing and new questions.
		$questions = array_merge($questionsExisting, $questionsNew);
		
		return $questions;
	}
	
	private static function generateQuestions($questionCount, $optionCount, $topicFacebookId, $categoryId) {
		$facebookAPI = FacebookAPI::singleton();
		
		// User must meet preconditions before we attempt to generate questions for them.
		API::checkQuestionGenerationPreconditions();
		
		// Only allow a certain number of errors.
		$errorCount = 0;
		$maxAcceptableErrorCount = round($questionCount * API::ACCEPTABLE_QUESTION_GENERATION_FAILURE_RATE);
		
		$questions = array();
		for ($i = 0; $i < $questionCount; $i++) {
			try {
				if ($optionCount == OptionType::FILL_IN_THE_BLANK) {
					// Not implemented.
				} elseif ($optionCount == OptionType::RANDOM) {
					$optionCountForQuestion = rand(OptionType::MC_MIN, OptionType::MC_MAX);
					$questions[] = new MCQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId, $optionCountForQuestion);
				} else { // Multiple choice.
					$questions[] = new MCQuestion($facebookAPI->getLoggedInUserId(), $topicFacebookId, $categoryId, $optionCount);
				}
			} catch (Exception $e) { // If we fail to generate a question, record the error, and continue.
				Error::saveExceptionToDB($e);
				
				// Have we reached the error limit? 
				if (++$errorCount >= $maxAcceptableErrorCount) {
					JSON::outputFatalErrorAndExit("GeneratingQuestionsReachedErrorLimit","Failed to generate enough questions. There may not be sufficient Facebook data to work with.");
				}
				
				// Repeat iteration.
				$i--;
				
				continue;
			}
		}
		
		return $questions;
	}
	
	private static function checkQuestionGenerationPreconditions() {
		$facebookAPI = FacebookAPI::singleton();
		
		if ($facebookAPI->countFriendsOf() < API::MIN_ACCEPTABLE_FRIEND_COUNT) {
			JSON::outputFatalErrorAndExit("InsufficientFriends","You must have at least ".API::MIN_ACCEPTABLE_FRIEND_COUNT." Facebook friends in order to play.");
		}
	}
	
	/**
	 * Takes an array of questions and their answers and update those questions in the database.
	 *
	 * @param array $questionAnswers Array of question-answer pairs
	 * @return void
	 */
	private static function saveQuestionAnswersAndResponseTimes($questionAnswers, $questionResponseTimes) {
		$facebookAPI = FacebookAPI::singleton();
		
		$questionIdsOfSavedAnswers = array();
		foreach ($questionAnswers as $questionId => $facebookId) {
			$responseTime = $questionResponseTimes[$questionId];
			
			// Update the user's answer for this question.
			// Note: We only update the answer if the user owned and had not already answered the question.
			$updateQuery = "UPDATE questions SET chosenFacebookId = '$facebookId', responseTime = '$responseTime', answeredAt = UNIX_TIMESTAMP() WHERE questionId = '$questionId' AND answeredAt = 0 AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' LIMIT 1";
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
			
			$updateQuery = "UPDATE questions SET skipped = true, answeredAt = UNIX_TIMESTAMP() WHERE chosenFacebookId = '' AND skipped = false AND ownerFacebookId = '".$facebookAPI->getLoggedInUserId()."' AND questionId = '$questionId' LIMIT 1";
			mysql_query($updateQuery);
			if (mysql_affected_rows() == 1) { // Keep track of which questions we successfully skipped.
				$skippedQuestionIds[] = $questionId;
			}
		}
		
		return $skippedQuestionIds;
	}

	public static function getIDPairsFromGETVars($parameterPrefix, $valueIsInt = true) {
		$pairs = array();
		
		foreach ($_GET as $parameterName => $value) {
			// Is this parameter name in the form of "$parameterPrefix[X]"?
			if (strncmp($parameterName, $parameterPrefix, strlen($parameterPrefix)) == 0){
				// Create a pair of the id and the value.
				$id = intval(substr($parameterName, strlen($parameterPrefix), strlen($parameterName) - strlen($parameterPrefix)));

				// Add this pair to our list.
				if ($id > 0) {
					$pairs[$id] = $valueIsInt ? intval($value) : DB::cleanInputForDatabase($value);
				}
			}
		}
		return $pairs;
	}
}
?>
