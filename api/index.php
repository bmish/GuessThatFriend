<?php
// Includes.
require_once('classes/API.php');
require_once('classes/DB.php');
require_once('classes/Facebook.php');
require_once('fns/config.php');
require_once('fns/misc.php');

// Connect to database.
DB::connect();

// Handle an API request.
$cmd = $_GET['cmd'];
if ($cmd == 'getQuiz') {
	API::getQuiz();
} else if ($cmd == 'submitQuiz') {
	API::submitQuiz();
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