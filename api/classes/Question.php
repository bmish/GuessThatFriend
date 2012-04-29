<?php
abstract class Question
{
	protected $questionId;
	protected $category; 			// Category of this Question (like books or movies). TODO: This will become redundant because it will be stored inside topicSubject.
	protected $text; 				// Question text.
	protected $ownerSubject;		// The person who this question was generated for.
	protected $topicSubject; 		// Person or page that this question is about.
	protected $correctSubject; 		// The correct answer to this question.
	
	// If the question has been answered:
	protected $chosenSubject; 		// The answer that the user chose.
	protected $answeredAt;			// The date and time that the user answered the question in string format.
	protected $responseTime;		// The number of milliseconds that the user took to answer the question.
	
	public function __construct($ownerFacebookId, $topicFacebookId, $categoryId, $questionId = -1) {
		$this->questionId = $questionId;
		$this->category = empty($categoryId) ? null : new Category($categoryId);
		$this->text = "";
		$this->ownerSubject = empty($ownerFacebookId) ? null : new Subject($ownerFacebookId);
		$this->topicSubject = empty($topicFacebookId) ? null : new Subject($topicFacebookId);
		$this->correctSubject = null;
		$this->chosenSubject = null;
		$this->answeredAt = null;
		$this->responseTime = -1;
		
		if ($this->questionId == -1) { // New question.
			// Pick a topic.
			$this->pickTopic();

			// Pick a correct answer.
			$this->pickAnswer();
			
			// Create question text.
			$this->makeQuestionText();

			// Save question to database after choosing a topic and correct answer.
			$this->saveToDB();
		}
	}
	
	public function __get($field)	{
        return $this->$field;
	}
	
	protected function pickTopic()	{
		global $facebookAPI;
		
		// Was the topic provided to us?
		if (!$this->topicSubject) {
			$this->topicSubject = $facebookAPI->getRandomSubject($this->category); // Generate a random topic of the desired category or a random friend.
		}
		
		// Store the category of the topic if we don't already know it.
		if (!$this->category) {
			$this->category = $this->topicSubject->category;
		}
	}
	
	protected function pickAnswer() {
		global $facebookAPI;
		
		if ($this->topicSubject->isPerson()) {
			$this->correctSubject = $facebookAPI->getRandomLikedPage($this->topicSubject->facebookId, $this->category); // Generate a random page of the desired category.
			$this->category = $this->correctSubject->category;
		} else { // Topic is a page.
			$this->correctSubject = $facebookAPI->getRandomFriendWhoLikes($this->topicSubject->facebookId, true); // Generate a random friend.
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
		if ($this->chosenSubject) {
			$obj["chosenSubject"] = $this->chosenSubject->jsonSerialize();
		}
		if ($this->answeredAt) {
			$obj["answeredAt"] = $this->answeredAt;
		}
		if ($this->responseTime > 0) {
			$obj["responseTime"] = $this->responseTime;
		}
		
		return $obj;
	}
	
	protected function saveToDB()	{
		$insertQuery = "INSERT INTO questions (categoryId, text, ownerFacebookId, topicFacebookId, correctFacebookId) VALUES ('".$this->category->categoryId."', '".DB::cleanInputForDatabase($this->text)."', '".$this->ownerSubject->facebookId."','".$this->topicSubject->facebookId."','".$this->correctSubject->facebookId."')";
		$result = mysql_query($insertQuery);
		
		if (!$result) {
			API::outputFailure("Unable to save question to database.");
			
			return false;
		}
		
		$this->questionId = mysql_insert_id();
		
		return true;
	}
	
	// Get questions from the database that the owner has answered.
	public static function getQuestionsFromDB($ownerFacebookId) {
		$result = mysql_query("SELECT * FROM questions WHERE ownerFacebookId = '$ownerFacebookId' AND chosenFacebookId != ''");
		if (!$result || mysql_num_rows($result) == 0) {
			return array();
		}
		
		$questions = array();
		while ($row = mysql_fetch_array($result)) {
			$question = new MCQuestion($ownerFacebookId, $row["topicFacebookId"], $row["categoryId"], -1, $row["questionId"]);
			
			// Fill in remaining fields that constructor didn't handle.
			$question->text = $row["text"];
			$question->correctSubject = new Subject($row["correctFacebookId"]);
			$question->chosenSubject = new Subject($row["chosenFacebookId"]);
			$question->answeredAt = $row["answeredAt"];
			$question->responseTime = $row["responseTime"];
				
			$questions[] = $question;
		}
		
		return $questions;
	}
}
?>
