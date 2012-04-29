<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>GuessThatFriend API</title>
<link href="view/css/style.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="view/js/fns.js"></script>
</head>
<body>
<div id="wrapper">
	<div id="header">
		<div id="pageTitle">GuessThatFriend API</div>
		<div id="subtitle"><a target="_blank" href="https://github.com/bmish/GuessThatFriend">github</a> - <a target="_blank" href="/mysql">phpMyAdmin</a> - <a target="_blank" href="/tests">tests</a> - <a target="_blank" href="https://wiki.engr.illinois.edu/display/cs428sp12/GuessThatFriend">Wiki</a></div>
	</div>
	<div id="page">
		<div id="body">
			<div id="pageDescription">Parameters are GET. Some commands require you to pass along an access token from Facebook.</div>
			<?php include_once("view/apiCommandTables/getQuestions.php"); ?>
			<?php include_once("view/apiCommandTables/submitQuestions.php"); ?>
			<?php include_once("view/apiCommandTables/getCategories.php"); ?>
			<?php include_once("view/apiCommandTables/getStatistics.php"); ?>
		</div>
	</div>
</div>
</body>
</html>
