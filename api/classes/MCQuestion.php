<?php
require_once "Question.php";
class MCQuestion extends Question
{
	private $optionCount;
	private $options;

	public function __construct($ownerFacebookId, $subjectFacebookId, $categoryId, $optionCount)	{
		parent::__construct($ownerFacebookId, $subjectFacebookId, $categoryId);
		
		$this->optionCount = $optionCount;
		$this->options = array();
		
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
				$this->options[$i] = new Option($this->$questionId, getRandomFacebookId());
			}
		}
	}
		
	public function setChosenOptionId($optionId)	{
		$this->chosenOptionId = $optionId;
		// TODO: update database
	}
	
	public function jsonSerialize() {
		$obj = parent::jsonSerialize();
		$obj["options"] = API::jsonSerializeArray($this->options);
		
		return $obj;
	}
}
?>
