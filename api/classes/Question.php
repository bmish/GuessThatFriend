<?php
require_once 'Category.php';
require_once 'Subject.php';
require_once 'MCQuestion.php';

abstract class Question
{
	protected $questionId;
	protected $category; 			// Category of this Question (like books or movies).
	protected $text; 				// Question text.
	protected $ownerSubject;		// The person who this question was generated for.
	protected $topicSubject; 		// Person or page that this question is about.
	protected $correctSubject; 		// The correct answer to this question.
	protected $chosenSubject; 		// The answer that the user chose (if the question has been answered).
	
	public function __construct($ownerFacebookId, $topicFacebookId, $categoryId)	{
		global $facebookAPI;
		
		$this->questionId = -1;
		$this->category = new Category($categoryId);
		$this->text = "";
		$this->ownerSubject = new Subject($ownerFacebookId);
		$this->topicSubject = new Subject($topicFacebookId);
		$this->correctSubject = null;
		$this->chosenSubject = null;
	
		// Pick a subject (topic).
		//$this->pickSubject();
		
		// Pick a correct answer.
		//$this->pickLike();
		
		// Save question to database after choosing a subject and correct answer.
		$this->saveToDB();
	}
	
	public function __get($field)	{
        return $this->$field;
	}
	
	/*
	 * TODO: currently assumes subject = a person
	 * Picks a random friend from the list if there are more than one and sets the subject ID.
	 * @return the list of 'likes' for the chosen friend.
	 */
	protected function pickTopicSubject()	{
		global $facebookAPI;
	
		$friendsWithLikes = $facebookAPI->getLikesOfAllMyFriends();
		if ($this->topicSubject->facebookId <= 0)	{
			$this->topicSubject->facebookId = array_rand($friendsWithLikes, 1);
		}
		$this->topicSubject = $friendsWithLikes[$this->topicSubject->facebookId]['subject'];
	}
	
	/*
	 * Picks a random 'like' from the list and sets the category ID.
	 * @param likes: the list of 'likes'
	 * @return the chosen 'like'
	 */
	protected function pickLike()	{
		global $facebookAPI;
	
		$likes = $facebookAPI->likes[$this->topicSubject->facebookId]['likes'];
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
	
	public function jsonSerialize() {
		$obj = array();
		$obj["questionId"] = $this->questionId;
		$obj["category"] = $this->category->jsonSerialize();
		$obj["text"] = $this->text;
		$obj["topicSubject"] = $this->topicSubject->jsonSerialize();
		$obj["correctSubject"] = $this->correctSubject->jsonSerialize();
		
		return $obj;
	}
	
	protected function saveToDB()	{
		global $facebookAPI;
		
		$insertQuery = "INSERT INTO questions (categoryId, text, ownerFacebookId, topicFacebookId, correctFacebookId) VALUES ('".$this->category->categoryId."', '".API::cleanInputForDatabase($this->text)."', '".$this->ownerSubject->facebookId."','".$this->topicSubject->facebookId."','".$this->correctSubject->facebookId."')";
		$result = mysql_query($insertQuery);
		
		if (!$result) {
			return false;
		}
		
		$this->questionId = mysql_insert_id();
		
		return true;
	}
}
?>
