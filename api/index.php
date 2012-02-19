<?php
// Report all PHP errors
error_reporting(-1);

require_once('fns/misc.php');
require_once('fns/api.php');
require_once('fns/facebook.php');

// Handle an API request.
$cmd = $_GET['cmd'];
if ($cmd == 'getQuiz') {
	getQuiz();
} else if ($cmd == 'submitQuiz') {
	submitQuiz();
} else {
	// Display page that describes the API.
	include_once("view/index.php");
	exit();
}
?>
