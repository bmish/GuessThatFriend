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
		$correctOptionIndex = rand(0, $optionCount-1);
		for ($i = 0; $i < $optionCount; $i++)	{
			if ($i == $correctOptionIndex)	{
				$this->options[$i] = new Option($this->questionId, $this->correctSubject);
			} else	{
				$this->options[$i] = new Option($this->questionId, FacebookAPI::getRandomPage());
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
