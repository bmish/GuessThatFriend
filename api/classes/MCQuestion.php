<?php
/**
 * This class implements a multiple choice question in the GuessThatFriend app.
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
			try {
				$this->makeOptions($optionCount);
			} catch (Exception $e) { // Delete half-finished question if any of the options fail.
				$this->removeFromDB();
				throw $e;
			}
		}
	}
	
	/**
	 * Makes question text based on the type of question.
	 *
	 * @see Question::makeQuestionText()
	 */
	public function makeQuestionText() {
		$this->text = "Which of the following ".$this->category->prettyName." ".$this->category->hasOrDoes." ".$this->topicSubject->name." ".$this->category->verb."?";
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
		$randomPages = $facebookAPI->getRandomPages($this->category, $optionCount - 1, $this->topicSubject->facebookId);
		$currentRandomPageIndex = 0;
		
		$this->options = array();
		$correctOptionIndex = rand(0, $optionCount-1);
		for ($i = 0; $i < $optionCount; $i++)	{
			if ($i == $correctOptionIndex)	{ // This should be the correct option.
				$this->options[] = new Option($this->questionId, $this->correctSubject);
			} else	{ // This should be an incorrect option.
				$this->options[] = new Option($this->questionId, $randomPages[$currentRandomPageIndex++]);
			}
		}
		
		// Check for problems with this question.
		if (count($this->options) != $optionCount) {
			Error::saveErrorToDB("GeneratedInsufficientOptions", "Failed to generate sufficient options for question #".$this->questionId);
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
	
	protected function removeFromDB() {
		return MCQuestion::remove($this->questionId);
	}
	
	public static function removeFromDBById($questionId) {
		Question::removeFromDBByID($questionId);
		
		$deleteQuery = "DELETE FROM options WHERE questionId = ".$questionId;
		$result = mysql_query($deleteQuery);
		
		if (!$result) {
			Error::saveErrorToDB("OptionDeletionFailed", "Unable to delete options for question #".$questionId." from database.");
			
			return false;
		}
		
		return true;
	}
	
	public static function assert($testInstance, $json, $expectedOptionCount) {
		parent::assert($testInstance, $json);
		
		$testInstance->assertNotNull($json->options);
		$testInstance->assertTrue(count($json->options) == $expectedOptionCount);
		
		foreach ($json->options as $option) {
			Option::assert($testInstance, $option);
		}
	} 
}
?>
