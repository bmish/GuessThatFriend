<?php
// Question includes.
require_once('classes/Question.php');
require_once('classes/FillBlankQuestion.php');
require_once('classes/MCQuestion.php');

// Misc includes.
require_once('classes/API.php');
require_once('classes/Option.php');
require_once('classes/DB.php');
require_once('classes/Facebook.php');
require_once('fns/config.php');
require_once('fns/misc.php');

// Connect to database.
DB::connect();

// Handle an API request.
$cmd = $_GET['cmd'];
if ($cmd == 'getQuestions') {
	$facebookAccessToken = cleanInputForDatabase($_GET['facebookAccessToken']);
	$questionCount = intval($_GET['questionCount']);
	$optionCount = intval($_GET['optionCount']);
	$friendFacebookId = cleanInputForDatabase($_GET['friendFacebookId']);
	$categoryId = intval($_GET['categoryId']);
	API::getQuestions($facebookAccessToken, $questionCount, $optionCount, $friendFacebookId, $categoryId);
} else if ($cmd == 'submitQuestions') {
	$facebookAccessToken = cleanInputForDatabase($_GET['facebookAccessToken']);
	$questionAnswers = API::getQuestionAnswersFromGETVars();
	API::submitQuestions($facebookAccessToken, $questionAnswers);
} else if ($cmd == 'getCategories') {
	API::getCategories();
} else {
	// Display page that describes the API.
	include_once("view/index.php");
	exit();
}

// Close database connection.
DB::close();
?>
