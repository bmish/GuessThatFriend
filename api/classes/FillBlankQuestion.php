<?php
class FillBlankQuestion extends Question
{
	protected $correctAnswerText;	// Text of the correct answer.
	protected $chosenAnswerText;	// Text of the user's answer.	
	
	public function __construct($subjectFacebookId, $categoryId)	{
		parent::__construct($subjectFacebookId, $categoryId);
	}
		
	protected function makeQuestionText() {
		return "What ".strtolower($category->prettyName)." does ".$subject->name." like?";
	}
	
	public function jsonSerialize() {
		$obj = array();
		$obj["questionId"] = $this->questionId;
		$obj["category"] = $this->category->jsonSerialize();
		$obj["text"] = $this->text;
		$obj["subject"] = $this->subject->jsonSerialize();
		$obj["correctFacebookId"] = $this->correctSubject->facebookId;
		
		return $obj;
	}
}
?>
