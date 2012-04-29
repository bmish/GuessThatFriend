<?php
// Question includes.
require_once('classes/Question.php');
require_once('classes/FillBlankQuestion.php');
require_once('classes/MCQuestion.php');

// Misc includes.
require_once('classes/API.php');
require_once('classes/Cache.php');
require_once('classes/Category.php');
require_once('classes/Option.php');
require_once('classes/DB.php');
require_once('classes/Subject.php');
require_once('classes/FacebookAPI.php');
require_once('config/config.php');
require_once('../references/facebook-php-sdk/src/facebook.php');

// Connect to database.
DB::connect();

// Setup Facebook API.
$facebookAPI = new FacebookAPI();

// Handle an API request.
$cmd = $_GET['cmd'];
if ($cmd == 'getQuestions') {
	$facebookAccessToken = API::cleanInputForDatabase($_GET['facebookAccessToken']);
	$questionCount = intval($_GET['questionCount']);
	$optionCount = intval($_GET['optionCount']);
	//$topicFacebookId = API::cleanInputForDatabase($_GET['topicFacebookId']);
	//$categoryId = intval($_GET['categoryId']);
	API::getQuestions($facebookAccessToken, $questionCount, $optionCount, $topicFacebookId, $categoryId);
} else if ($cmd == 'submitQuestions') {
	$facebookAccessToken = API::cleanInputForDatabase($_GET['facebookAccessToken']);
	$questionAnswers = API::getQuestionAnswersFromGETVars();
	$questionTimes = API::getQuestionTimesFromGETVars();
	API::submitQuestions($facebookAccessToken, $questionAnswers, $questionTimes);
} else if ($cmd == 'getCategories') {
	API::getCategories();
} else if ($cmd == 'getStatistics') {
	$facebookAccessToken = API::cleanInputForDatabase($_GET['facebookAccessToken']);
	$type = $_GET['type'];
	API::getStatistics($facebookAccessToken, $type);
} else {
	// Display page that describes the API.
	include_once("view/index.php");
}

// Close database connection.
DB::close();
?>
