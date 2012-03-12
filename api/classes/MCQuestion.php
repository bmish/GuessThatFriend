<?php
class MCQuestion extends Question
{
	protected $correctOptionId;	//Id of the correct answer for the question
	protected $chosenOptionId; 	//Id of the option chosen by the user
	
	private $numOptions;

	public function __construct($likes, $isFriendSelected, $numOptions)	{
		parent::__construct($likes, $isFriendSelected);
		$this->numOptions = numOptions;
	}
	
	public function makeQuestionText()	{
		require_once 'Subject.php';
		require_once 'Facebook.php';

		$category = new Category($this->categoryId);
		$categoryName = $category->getPrettyName();
		$subjectName = Subject::getNameFromId($this->subjectId);
		
		// TODO: modify question to depend on subject type (person v.s. page)
		$this->text = "<p>Which of the following ".$categoryName." does ".$subjectName." like?</p><ol>";
		$options = $this->makeOptions($this->like['id']);
		for ($i = 0; $i < numOptions; $i++)	{
			$this->text = $this->text."<li>".Facebook::getNameFromId($options[$i]->subjectId)."</li>";
		}
		$this->text = $this->text."</ol>";
	}
	
	private function makeOptions($correctFacebookId)	{
		$correctOption = rand(0, $this->numOptions-1);
		for ($i = 0; $i < $this->numOptions; $i++)	{
			if ($i == $correctOption)	{
				$options[$i] = new Option ($this->$questionId, $correctFacebookId);
				$correctOptionId = $options[$i]->optionId;
			} else	{
				$options[$i] = new Option ($this->$questionId, randIncorrectFacebookId());
			}
		}
		return $options;
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
