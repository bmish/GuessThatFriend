<?php
// Report all PHP errors.
error_reporting(-1);

require_once('fns/misc.php');
require_once('fns/api.php');
require_once('fns/facebook.php');
require_once('fns/config.php');
require_once('fns/db.php');

// Connect to database.
dbConnect();

// Handle an API request.
$cmd = $_GET['cmd'];
if ($cmd == 'getQuiz') {
	apiGetQuiz();
} else if ($cmd == 'submitQuiz') {
	apiSubmitQuiz();
} else if ($cmd == 'getCategories') {
	apiGetCategories();
} else {
	// Display page that describes the API.
	include_once("view/index.php");
	exit();
}

// Close database connection.
dbClose();
?>
