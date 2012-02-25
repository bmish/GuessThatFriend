<?php
class Question
{
	protected $questionId;
	protected $categoryId;
	protected $categoryFacebookName;	//eg movie
	protected $categoryPrettyName;
	protected $topicFacebookId;		//eg 300
	
	//Main question string
	protected $text;
	protected $success;


	//Will behave differently based on Question type
	// $makeQuestionText(){}

}
?>
