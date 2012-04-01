<?php
require_once 'Category.php';
require_once 'Subject.php';
require_once 'MCQuestion.php';

abstract class Question
{
	protected $questionId;
	protected $category; 			// Category of this Question (like books or movies). TODO: This will become redundant because it will be stored inside topicSubject.
	protected $text; 				// Question text.
	protected $ownerSubject;		// The person who this question was generated for.
	protected $topicSubject; 		// Person or page that this question is about.
	protected $correctSubject; 		// The correct answer to this question.
	protected $chosenSubject; 		// The answer that the user chose (if the question has been answered).
	
	public function __construct($ownerFacebookId, $topicFacebookId, $categoryId)	{
		$this->questionId = -1;
		$this->category = empty($categoryId) ? null : new Category($categoryId);
		$this->text = "";
		$this->ownerSubject = new Subject($ownerFacebookId);
		$this->topicSubject = empty($topicFacebookId) ? null : new Subject($topicFacebookId);
		$this->correctSubject = null;
		$this->chosenSubject = null;
	
		// Pick a topic.
		$this->pickTopic();
		
		// Pick a correct answer.
		$this->pickAnswer();
		
		// Save question to database after choosing a topic and correct answer.
		$this->saveToDB();
	}
	
	public function __get($field)	{
        return $this->$field;
	}
	
	protected function pickTopic()	{
		global $facebookAPI;
		
		// Was the topic provided to us?
		if (!$this->topicSubject) {
			$this->topicSubject = $facebookAPI->getRandomSubject($this->category); // Generate a random topic of the desired category of a random friend.
		}
		
		// Store the category of the topic if we don't already know it.
		if (!$this->category) {
			$this->category = $this->topicSubject->getCategory();
		}
	}
	
	protected function pickAnswer() {
		global $facebookAPI;
		
		if ($this->topicSubject->isPerson()) {
			$this->correctSubject = $facebookAPI->getRandomPage($this->category); // Generate a random page of the desired category.
		} else { // Topic is a page.
			$this->correctSubject = $facebookAPI->getRandomFriend(); // Generate a random friend.
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
