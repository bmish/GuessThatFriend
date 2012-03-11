<?php
$output = exec("python testAPI.py");
header("Content-type: text/plain");
include("log_file.txt");
?>
