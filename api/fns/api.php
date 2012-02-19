<?php
function getQuiz() {
	echo '1. Generate quiz. 2. Show quiz json';
	$quiz = new Quiz();
	json_encode($quiz);
}

function submitQuiz() {
	echo '1. Save quiz. 2. Show json response';
}
?>
