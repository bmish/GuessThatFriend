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
	API::getQuestions();
} else if ($cmd == 'submitQuestions') {
	API::submitQuestions();
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
