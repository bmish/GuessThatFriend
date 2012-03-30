<?php
require_once "Question.php";
class MCQuestion extends Question
{
	protected $options;
	protected $optionCount;

	public function __construct($optionCount, $subjectFacebookId, $categoryId)	{
		parent::__construct($subjectFacebookId, $categoryId);
		$this->optionCount = $optionCount;
		$this->options = null;
		
		//$this->makeOptions();
	}
	
	public function makeQuestionText() {
		// TODO: modify question to depend on subject type (person v.s. page)
		$this->text = "Which of the following ".strtolower($this->category->prettyName)." does ".$this->subject->name." like?";
	}
	
	private function makeOptions()	{
		$correctOption = rand(0, $this->optionCount-1);
		for ($i = 0; $i < $this->numOptions; $i++)	{
			if ($i == $correctOption)	{
				$this->options[$i] = new Option($this->$questionId, $correctSubject->facebookId);
			} else	{
				$this->options[$i] = new Option($this->$questionId, randIncorrectFacebookId());
			}
		}
	}
	
	/*
	 * Generates an incorrect option for the question.
	 * @return incorrect Facebook ID
	 */
	private function randIncorrectFacebookId()	{
		// TODO: missing implementation
	}
		
	public function setChosenOptionId($optionId)	{
		$this->chosenOptionId = $optionId;
		// TODO: update database
	}
	
	public function jsonSerialize() {
		$obj = array();
		$obj["questionId"] = $this->questionId;
		$obj["category"] = $this->category->jsonSerialize();
		$obj["text"] = $this->text;
		$obj["subject"] = $this->subject->jsonSerialize();
		$obj["correctFacebookId"] = $this->correctSubject->facebookId;
		$obj["options"] = API::jsonSerializeArray($this->options);
		
		return $obj;
	}
}
?>
