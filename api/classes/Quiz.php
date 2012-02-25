<?php
class Quiz
{	
	//date
	public $questionCount;
	protected $optionCount;
	protected $userFacebookId;		
	//protected $questionArray = array[];

	public function __construct(){
		print "In constructor.<br />";
	}

	public function testPrint(){
		print "Testing print from quiz object.";
	}
}
?>
