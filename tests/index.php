<?php
$output = exec("python api/testAPI.py");
header("Content-type: text/plain");
include("log_file.txt");

?>
