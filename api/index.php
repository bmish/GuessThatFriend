<?php
// Question includes.
require_once('classes/Question.php');
require_once('classes/FillBlankQuestion.php');
require_once('classes/MCQuestion.php');

// Misc includes.
require_once('classes/API.php');
require_once('classes/Option.php');
require_once('classes/DB.php');
require_once('classes/FacebookAPI.php');
require_once('config/config.php');

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
	$topicFacebookId = API::cleanInputForDatabase($_GET['topicFacebookId']);
	$categoryId = intval($_GET['categoryId']);
	API::getQuestions($facebookAccessToken, $questionCount, $optionCount, $topicFacebookId, $categoryId);
} else if ($cmd == 'submitQuestions') {
	$facebookAccessToken = API::cleanInputForDatabase($_GET['facebookAccessToken']);
	$questionAnswers = API::getQuestionAnswersFromGETVars();
	API::submitQuestions($facebookAccessToken, $questionAnswers);
} else if ($cmd == 'getCategories') {
	API::getCategories();
} else {
	// Display page that describes the API.
	include_once("view/index.php");
}

// Close database connection.
DB::close();
?>
