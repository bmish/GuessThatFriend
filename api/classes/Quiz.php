<?php
class Quiz
{	
	//date
	public $questionCount;
	protected $optionCount;
	protected $userFacebookId;		//TODO: does int fit?
	Question $questionArray[];

	function __construct(){
		print "In constructor.<br />";
	}

	function testPrint(){
		print "Testing print from quiz object.";
	}
}
?>
