<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Guess That Friend API</title>
<link href="view/css/style.css" rel="stylesheet" type="text/css">
<script src="view/js/jquery-1.7.1.js"></script>
<script src="view/js/fns.js"></script>
</head>
<body>
<div id="wrapper">
	<div id="header">
		<div id="pageTitle">Guess That Friend API</div>
		<div id="subtitle"><a target="_blank" href="https://github.com/bmish/GuessThatFriend">github</a> - <a target="_blank" href="/mysql">phpMyAdmin</a> - <a target="_blank" href="https://wiki.engr.illinois.edu/display/cs428sp12/bmishk2nirh1">Wiki</a></div>
	</div>
	<div id="page">
		<div id="body">
			<div id="pageDescription">Parameters are GET. Some of these commands will eventually require authentication with OAuth.</div>
			<?php include_once("view/apiCommandTables/getQuiz.php"); ?>
			<?php include_once("view/apiCommandTables/submitQuiz.php"); ?>
			<?php include_once("view/apiCommandTables/getCategories.php"); ?>
		</div>
	</div>
</div>
</body>
</html>