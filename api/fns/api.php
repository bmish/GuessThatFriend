<?php
function getQuiz() {
	header('Content-type: application/json');
	require_once("examples/json/getQuiz.json");
}

function submitQuiz() {
	header('Content-type: application/json');
	require_once("examples/json/submitQuiz.json");
}
?>
