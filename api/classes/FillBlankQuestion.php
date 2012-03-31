<?php
class FillBlankQuestion extends Question
{
	private $correctAnswerText;		// Text of the correct answer.
	private $chosenAnswerText;		// Text of the user's answer.	
	
	public function __construct($ownerFacebookId, $subjectFacebookId, $categoryId)	{
		parent::__construct($ownerFacebookId, $subjectFacebookId, $categoryId);
		
		$correctAnswerText = "";
		$chosenAnswerText = "";
	}
		
	protected function makeQuestionText() {
		$this->text = "What ".strtolower($category->prettyName)." does ".$subject->name." like?";
	}
}
?>
