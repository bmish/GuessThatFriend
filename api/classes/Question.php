<?php
// TODO: currently assumes subject = a person
abstract class Question
{
	protected $questionId;
	protected $categoryId;		// Category of this Question (like books or movies).
	protected $subjectId;		// Subject of this Question (a person or page).
	protected $text;			// Question text.
	
	private static $numQuestions;
	
	protected $like;
	
	//protected $correctOptionId;	// The correct answer to this question.
	//protected $chosenOptionid;	// The answer that the user chose (if the question has been answered).
	
	public function __get($field)	{
        return $this->$field;
	}
	
	/*
	 * Constructor
	 */
	public function __construct($likes, $isFriendSelected)	{
		require_once 'Category.php';
	
		$this->setQuestionId();
		$likes = $this->pickSubject($likes, $isFriendSelected);
		$this->like = $this->pickLike($likes);
	}
	
	/*
	 * Sets the question ID.
	 */
	protected function setQuestionId()	{
		$this->questionId = self::$numQuestions;
		self::$numQuestions++;
	}
	
	/*
	 * Picks a random friend from the list if there are more than one and sets the subject ID.
	 * @param	likes: the list of 'likes'
	 * 			isFriendSelected: whether or not the list has been previously filtered to contain the
									'likes' of a single friend
	 * @return the list of 'likes' for the chosen friend.
	 */
	protected function pickSubject($likes, $isFriendSelected)	{
		if (!$isFriendSelected)	{
			$randFriend = rand(0,size($likes)-1);
			$likes = $likes[$randFriend];
		}
		$this->subjectId = $likes['friend']->facebookId;
		return $likes;
	}
	
	/*
	 * Picks a random 'like' from the list and sets the category ID.
	 * @param likes: the list of 'likes'
	 * @return the chosen 'like'
	 */
	protected function pickLike($likes)	{
		$randLike = $likes['friendLikes'][rand(0,size($likes['friendLikes'])-1)];
		$this->categoryId = Category::getCategoryId($randLike['category']);
		return $randLike;
	}
	
	/*
	 * Makes question text based on the type of question.
	 * @param like : the chosen 'like'
	 * @return the question text
	 */
	abstract protected function makeQuestionText();
	
	/*
	 * Saves the question to the database.
	 */
	protected function saveToDB()	{
		// TODO: missing implementation
	}
}
?>
