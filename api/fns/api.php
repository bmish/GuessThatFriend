<?php
function getQuiz() {
	header('Content-type: application/json');
	require_once("examples/json/getQuiz.json");
}

function submitQuiz() {
	echo 'TODO: 1. Save quiz. 2. Output JSON response.';
}
?>
