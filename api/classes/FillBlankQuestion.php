<?php
class FillBlankQuestion extends Question
{
	protected $correctAnswerText;	// Text of the correct answer.
	protected $chosenAnswerText;	// Text of the user's answer.	
		
	protected function makeQuestionText() {
		return "What ".strtolower($category->prettyName)." does ".$subject->name." like?";
	}
}
?>
