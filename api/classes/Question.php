<?php
class Question
{
	protected $questionId;
	protected $categoryId;		// Category of this Question (like books or movies).
	protected $subjectId;		// Subject of this Question (a person or page).
	protected $text;			// Question text.
	protected $correctOptionId;	// The correct answer to this question.
	protected $chosenOptionid;	// The answer that the user chose (if the question has been answered).

	//Will behave differently based on Question type
	// $makeQuestionText(){}
}
?>
