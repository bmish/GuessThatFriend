<?php
/**
 * This class implements a fill-in-the-blanks question in the GuessThatFriend app.
 *
 * @property array $options
 *
 *
 */
class MCQuestion extends Question	{

	private $options;

	/**
	 * __construct
	 *
	 * @param string $ownerFacebookId Facebook ID of question owner (app user)
	 * @param string $topicFacebookId Facebook ID of the question topic
	 * @param int $categoryId ID of topic category
	 * @param int $optionCount Number of options
	 * @param int $questionId ID of question
	 * @return void
	 */
	public function __construct($ownerFacebookId, $topicFacebookId, $categoryId, $optionCount, $questionId = -1) {
		parent::__construct($ownerFacebookId, $topicFacebookId, $categoryId, $questionId);

		if ($questionId > 0) { // Existing question from database.
			$this->options = Option::getOptionsFromDB($questionId);
			$this->optionCount = count($this->options);
		} else { // New question.
			$this->makeOptions($optionCount);
		}
	}
	
	/**
	 * Makes question text based on the type of question.
	 *
	 * @see Question::makeQuestionText()
	 * @todo Modify question to depend on subject type (person v.s. page)
	 */
	public function makeQuestionText() {
		$this->text = "Which of the following ".strtolower($this->category->prettyName)." does ".$this->topicSubject->name." like?";
	}
	
	/**
	 * Generate list of options.
	 *
	 * @param int $optionCount The number of options to generate
	 * @return void
	 */
	private function makeOptions($optionCount)	{
		$facebookAPI = FacebookAPI::singleton();
		
		// Build a list of random pages to use for our incorrect options.
		$randomPages = $facebookAPI->getRandomPage($this->category, $optionCount - 1, $this->topicSubject->facebookId);
		$currentRandomPageIndex = 0;
		
		$this->options = array();
		$correctOptionIndex = rand(0, $optionCount-1);
		for ($i = 0; $i < $optionCount; $i++)	{
			if ($i == $correctOptionIndex)	{ // This should be the correct option.
				$this->options[$i] = new Option($this->questionId, $this->correctSubject);
			} else	{ // This should be an incorrect option.
				$this->options[$i] = new Option($this->questionId, $randomPages[$currentRandomPageIndex++]);
			}
		}
		
		if ($this->duplicateOptionsExist($this->options)) {
			throw new Exception("Detected duplicate options.");
		}
	}
	
	private function duplicateOptionsExist() {
		for ($index = 0; $index < count($this->options); $index++) {
			for ($index2 = 0; $index2 < count($this->options), $index2 != $index; $index2++) {
				if ($this->options[$index]->topicSubject->facebookId == $this->options[$index2]->topicSubject->facebookId) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return array Array formatted for JSON output
	 */
	public function jsonSerialize() {
		$obj = parent::jsonSerialize();
		$obj["options"] = JSON::jsonSerializeArray($this->options);
		
		return $obj;
	}
}
?>
