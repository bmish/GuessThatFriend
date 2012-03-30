<?php
require_once 'Category.php';

// TODO: currently assumes subject = a person
abstract class Question
{
	protected $questionId;
	protected $category; 			// Category of this Question (like books or movies).
	protected $text; 				// Question text.
	protected $subject; 			// Person or page that this question is about.
	protected $correctSubject; 		// The correct answer to this question.
	protected $chosenSubject; 		// The answer that the user chose (if the question has been answered).
	
	public function __construct($subjectFacebookId, $categoryId)	{
		$this->questionId = -1;
		$this->category = new Category($categoryId);
		$this->text = "";
		$this->subject = new Subject($subjectFacebookId);
		$this->correctSubject = "";
		$this->chosenSubject = "";
	
		//$this->pickSubject();
		//$this->pickLike();
	}
	
	public function __get($field)	{
        return $this->$field;
	}
	
	/*
	 * Picks a random friend from the list if there are more than one and sets the subject ID.
	 * @return the list of 'likes' for the chosen friend.
	 */
	protected function pickSubject()	{
		global $facebookAPI;
	
		$friendsWithLikes = $facebookAPI->getLikesOfAllMyFriends();
		if ($this->subject->facebookId <= 0)	{
			$this->subject->facebookId = array_rand($friendsWithLikes, 1);
		}
		$this->subject = $friendsWithLikes[$this->subject->facebookId]['subject'];
	}
	
	/*
	 * Picks a random 'like' from the list and sets the category ID.
	 * @param likes: the list of 'likes'
	 * @return the chosen 'like'
	 */
	protected function pickLike()	{
		global $facebookAPI;
	
		$likes = $facebookAPI->likes[$this->subject->facebookId]['likes'];
		if ($this->categoryId > 0)	{
			$likesInCategory = array();
			foreach ($likes as $like)	{
				if ($like['category'] == $this->categoryId)	{
					$likesInCategory[] = $like;
				}
			}
			$this->correctSubject = $likesInCategory[rand(0,sizeof($likesInCategory)-1)];
		} else {
			$this->correctSubject = $likes[rand(0,sizeof($likes)-1)];
			$this->category = new Category(Category::getCategoryId($this->correctSubject['category']));
		}
	}
	
	/*
	 * Makes question text based on the type of question.
	 * @param like : the chosen 'like'
	 * @return the question text
	 */
	protected abstract function makeQuestionText();
	
	public abstract function jsonSerialize();
	
	protected function saveToDB()	{
		// TODO: missing implementation
	}
}
?>
