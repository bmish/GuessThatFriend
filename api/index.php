<?php
/**
 * GuessThatFriend entry page.
 *
 * @ * 2012 GuessThatFriend
 */

// Question includes.
require_once('classes/Question.php');
require_once('classes/FillBlankQuestion.php');
require_once('classes/MCQuestion.php');

// Misc includes.
require_once('classes/API.php');
require_once('classes/Cache.php');
require_once('classes/Category.php');
require_once('classes/DB.php');
require_once('classes/Error.php');
require_once('classes/FacebookAPI.php');
require_once('classes/JSON.php');
require_once('classes/Option.php');
require_once('classes/OptionType.php');
require_once('classes/StatisticType.php');
require_once('classes/Subject.php');
require_once('classes/Util.php');

require_once('config/config.php');
require_once('../references/facebook-php-sdk/src/facebook.php');

// Connect to database.
DB::connect();

// Handle an API request.
$cmd = $_GET['cmd'];
if ($cmd == 'getQuestions') {
	$facebookAccessToken = DB::cleanInputForDatabase($_GET['facebookAccessToken']);
	$questionCount = intval($_GET['questionCount']);
	$optionCount = intval($_GET['optionCount']);
	//$topicFacebookId = DB::cleanInputForDatabase($_GET['topicFacebookId']);
	//$categoryId = intval($_GET['categoryId']);
	API::getQuestions($facebookAccessToken, $questionCount, $optionCount, $topicFacebookId, $categoryId);
} else if ($cmd == 'submitQuestions') {
	$facebookAccessToken = DB::cleanInputForDatabase($_GET['facebookAccessToken']);
	$questionAnswers = API::getIDPairsFromGETVars("facebookIdOfQuestion", false);
	$questionTimes = API::getIDPairsFromGETVars("responseTimeOfQuestion", true);
	$skippedQuestionIds = DB::cleanArrayForDatabase($_GET['skippedQuestionIds'], true);
	API::submitQuestions($facebookAccessToken, $questionAnswers, $questionTimes, $skippedQuestionIds);
} else if ($cmd == 'getCategories') {
	API::getCategories();
} else if ($cmd == 'getStatistics') {
	$facebookAccessToken = DB::cleanInputForDatabase($_GET['facebookAccessToken']);
	$type = $_GET['type'];
	API::getStatistics($facebookAccessToken, $type);
} else {
	// Display page that describes the API.
	include_once("view/index.php");
}

// Close database connection.
DB::close();
?>
