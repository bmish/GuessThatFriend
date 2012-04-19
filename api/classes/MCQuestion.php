<?php
class MCQuestion extends Question
{
	private $options;

	public function __construct($ownerFacebookId, $topicFacebookId, $categoryId, $optionCount)	{
		parent::__construct($ownerFacebookId, $topicFacebookId, $categoryId);

		$this->options = array();
		$this->makeOptions($optionCount);
		
		$this->makeQuestionText();
	}
	
	public function makeQuestionText() {
		// TODO: modify question to depend on subject type (person v.s. page)
		$this->text = "Which of the following ".strtolower($this->category->prettyName)." does ".$this->topicSubject->name." like?";
	}
	
	private function makeOptions($optionCount)	{
		// Build a list of random pages to use for our incorrect options.
		$randomPages = FacebookAPI::getRandomPage($this->category, $optionCount - 1, $topicSubject->facebookId);
		$currentRandomPageIndex = 0;
		
		$correctOptionIndex = rand(0, $optionCount-1);
		for ($i = 0; $i < $optionCount; $i++)	{
			if ($i == $correctOptionIndex)	{ // This should be the correct option.
				$this->options[$i] = new Option($this->questionId, $this->correctSubject);
			} else	{ // This should be an incorrect option.
				$this->options[$i] = new Option($this->questionId, $randomPages[$currentRandomPageIndex++]);
			}
		}
	}
	
	public function jsonSerialize() {
		$obj = parent::jsonSerialize();
		$obj["options"] = API::jsonSerializeArray($this->options);
		
		return $obj;
	}
}
?>
