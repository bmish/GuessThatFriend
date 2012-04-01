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
	
	/*
	 * Generates an incorrect option for the question.
	 * @return incorrect Facebook ID
	 */
	private function getRandomFacebookId()	{
		while (true)	{
			// Only generates Ids up to 12 digits long
			$randId = rand(1,9);
			for ($i = 1; $i <= 12; $i++)	{
				if (rand(0,1) == 1)
					$randId = $randId.rand(0,9);
			}
			$contents = @file_get_contents('http://graph.facebook.com/'.$randId);
			// Check if valid facebook Id
			if (($contents != false) && (strcmp($contents, 'false') != 0))	{
				$contents = json_decode($contents);
				// Check if Id belongs to a page of the correct category
				if (strcmp($contents->category, $this->category->get($facebookName)) == 0) {
					if (!$this->likesPage($contents->id))
						return $randId;
				}
			}
		}
	}
	
	/*
	 * Returns whether the subject likes a particular page.
	 * @param facebookId - the facebook Id of the page
	 * @return true if the friend likes the page, false otherwise
	 */
	private function likesPage($facebookId)	{
		global $facebookAPI;
	
		$likes = $facebookAPI->getLikesOfFriend($this->subject->facebookId);
		foreach ($likes as $like)	{
			if ($like->facebookId == $facebookId)	{
				return true;
			}
		}
		return false;
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
