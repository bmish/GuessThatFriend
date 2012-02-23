<?php
$output = exec("python api/testAPI.py");
header("Content-type: text/plain");
include("api/log_file.txt");

?>
