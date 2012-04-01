<?php
class FillBlankQuestion extends Question
{
	private $correctAnswerText;		// Text of the correct answer.
	private $chosenAnswerText;		// Text of the user's answer.	
	
	public function __construct($ownerFacebookId, $topicFacebookId, $categoryId)	{
		parent::__construct($ownerFacebookId, $topicFacebookId, $categoryId);
		
		$correctAnswerText = "";
		$chosenAnswerText = "";
	}
		
	protected function makeQuestionText() {
		$this->text = "What ".strtolower($this->category->prettyName)." does ".$this->topicSubject->name." like?";
	}
}
?>
