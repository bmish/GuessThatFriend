<?php
class Question
{
	int questionId;
	int categoryId;
	string categoryFacebookName;	//eg movie
	string categoryPrettyName;
	string topicFacebookId;		//eg 300
	
	//Main question string
	string text;
	boolean success;


	//Will behave differently based on Question type
	// string makeQuestionText(){}

}
?>
