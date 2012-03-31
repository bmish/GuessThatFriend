<?php
function cleanInputForDatabase($input) {
	return addslashes(trim($input));
}

function cleanInputForDisplay($input) {
	return htmlentities(trim($input), ENT_QUOTES, 'UTF-8');
}

function cleanOutputFromDatabase($output) {
	return stripslashes($output);
}
?>