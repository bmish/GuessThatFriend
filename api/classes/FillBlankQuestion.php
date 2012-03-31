<?php
class FillBlankQuestion extends Question
{
	private $correctAnswerText;		// Text of the correct answer.
	private $chosenAnswerText;		// Text of the user's answer.	
	
	public function __construct($subjectFacebookId, $categoryId)	{
		parent::__construct($subjectFacebookId, $categoryId);
	}
		
	protected function makeQuestionText() {
		$this->text = "What ".strtolower($category->prettyName)." does ".$subject->name." like?";
	}
}
?>
