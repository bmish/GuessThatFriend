<?php
function dbConnect() {
	global $con,$dbHost,$dbUser,$dbPass;
	$con = mysql_connect($dbHost,$dbUser,$dbPass);
	if (!$con) {
		echo 'Could not connect: '.mysql_error();
	}
}

function dbClose() {
	global $con;
	mysql_close($con);
}
?>