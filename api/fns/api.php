<?php
function apiGetQuiz() {
	header('Content-type: application/json');
	require_once("examples/json/getQuiz.json");
}

function apiSubmitQuiz() {
	header('Content-type: application/json');
	require_once("examples/json/submitQuiz.json");
}

function apiGetCategories() {
	// Get categories from database.
	$result = mysql_query("SELECT * FROM categories");
	if (!$result || mysql_num_rows($result) == 0) {
		return;
	}
	
	// Put categories into an array.
	$arr = array();
	while ($row = mysql_fetch_array($result)) {
		$arr[] = $row;
	}
	
	header('Content-type: application/json');
	echo json_encode($arr);
}
?>
