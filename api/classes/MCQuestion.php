<?php
require_once "Question.php";
class MCQuestion extends Question
{
	protected $options;
	protected $optionCount;

	public function __construct($optionCount, $friendFacebookId, $categoryId)	{
		parent::__construct($friendFacebookId, $categoryId);
		$this->optionCount = $optionCount;
		$this->makeOptions();
	}
	
	public function makeQuestionText() {
		// TODO: modify question to depend on subject type (person v.s. page)
		$this->text = "Which of the following ".strtolower($this->category->prettyName)." does ".$this->subject->name." like?";
	}
	
	private function makeOptions()	{
		$correctOption = rand(0, $this->optionCount-1);
		for ($i = 0; $i < $this->numOptions; $i++)	{
			if ($i == $correctOption)	{
				$this->options[$i] = new Option ($this->$questionId, $correctSubject->facebookId);
			} else	{
				$this->options[$i] = new Option ($this->$questionId, randIncorrectFacebookId());
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
}
?>
