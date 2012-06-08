<?php
/**
 * This class implements a fill-in-the-blanks question in the GuessThatFriend app.
 *
 * @property string $correctAnswerText
 * @property string $chosenAnswertext
 *
 *
 */
class FillBlankQuestion extends Question	{

	private $correctAnswerText;		// Text of the correct answer.
	private $chosenAnswerText;		// Text of the user's answer.	
	
	/**
	 * __construct
	 *
	 * @param string $ownerFacebookId Facebook ID of question owner (app user)
	 * @param string $topicFacebookId Facebook ID of the question topic
	 * @param int $categoryId ID of topic category
	 * @return void
	 */
	public function __construct($ownerFacebookId, $topicFacebookId, $categoryId)	{
		parent::__construct($ownerFacebookId, $topicFacebookId, $categoryId);
		
		$correctAnswerText = "";
		$chosenAnswerText = "";
	}
	
	/**
	 * Makes question text based on the type of question.
	 *
	 * @see Question::makeQuestionText()
	 */
	protected function makeQuestionText() {
		$this->text = "What ".strtolower($this->category->prettyName)." does ".$this->topicSubject->name." like?";
	}
}
?>
