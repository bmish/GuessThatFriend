<?php
/**
 * This class models a question in the GuessThatFriend app.
 *
 * Abstract class to be extended by specific question types.
 *
 */
abstract class Question	{

	protected $questionId;
	protected $category; 			// Category of this Question (like books or movies).
	protected $text; 			// Question text.
	protected $ownerSubject;		// The person who this question was generated for.
	protected $topicSubject; 		// Person or page that this question is about.
	protected $correctSubject; 		// The correct answer to this question.
	
	// If the question has been answered:
	protected $chosenSubject; 		// The answer that the user chose.
	protected $answeredAt;			// The date and time that the user answered the question in string format.
	protected $responseTime;		// The number of milliseconds that the user took to answer the question.
	
	/**
	 * __construct
	 *
	 * @param string $ownerFacebookId Facebook ID of question owner (app user)
	 * @param string $topicFacebookId Facebook ID of the question topic
	 * @param int $categoryId ID of topic category
	 * @param int $questionId ID of question
	 * @return void
	 */	
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
			// Pick a correct topic and answer.
			$this->pickTopicAndAnswer();
			
			// Create question text.
			$this->makeQuestionText();

			// Save question to database after choosing a topic and correct answer.
			$this->saveToDB();
		}
	}
	
	/**
	 * Generic getter method.
	 *
	 * @param string $field Name of field
	 * @return object Value of field
	 */
	public function __get($field)	{
        return $this->$field;
	}
	
	protected function pickTopicAndAnswer() {
		$facebookAPI = FacebookAPI::singleton();
		
		do {
			$friend = $facebookAPI->getRandomFriend(); // Only returns friends with enough likes.
			$page = $facebookAPI->getRandomLikedPage($friend->facebookId); // Only returns pages with enough randomPages of the same category.
		} while (!$page);
		
		$this->topicSubject = $friend;
		$this->correctSubject = $page;
		$this->category = $this->correctSubject->category;
	}
	
	/**
	 * Makes question text based on the type of question.
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
			$obj["answeredAt"] = date("c", $this->answeredAt);
		}
		if ($this->responseTime > 0) {
			$obj["responseTime"] = $this->responseTime;
		}
		
		return $obj;
	}
	
	/**
	 * Save question to database.
	 *
	 * @return bool True on successful query, false otherwise
	 */
	protected function saveToDB()	{
		$insertQuery = "INSERT INTO questions (categoryId, text, ownerFacebookId, topicFacebookId, correctFacebookId, createdAt) VALUES ('".$this->category->categoryId."', '".DB::cleanInputForDatabase($this->text)."', '".$this->ownerSubject->facebookId."','".$this->topicSubject->facebookId."','".$this->correctSubject->facebookId."',UNIX_TIMESTAMP())";
		$result = mysql_query($insertQuery);
		
		if (!$result) {
			JSON::outputFatalErrorAndExit("InsertQuestionToDBFailed","Unable to save question to database.");
			
			return false;
		}
		
		$this->questionId = mysql_insert_id();
		
		return true;
	}
	
	protected function removeFromDB() {
		return Question::removeFromDBById($this->questionId);
	}
	
	public static function removeFromDBById($questionId) {
		if ($questionId < 1) {
			return false;
		}
		
		$deleteQuery = "DELETE FROM questions WHERE questionId = ".$questionId." LIMIT 1";
		$result = mysql_query($deleteQuery);
		
		if (!$result) {
			Error::saveErrorToDB("QuestionDeletionFailed", "Unable to delete question #".$questionId." from database.");
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * Get questions from the database that the owner has answered.
	 *
	 * @param string $ownerFacebookId Facebook ID of question owner (app user)
	 * @return array Array of Questions
	 *
	 */
	public static function getAnsweredQuestionsFromDB($ownerFacebookId, $questionCount = 0) {
		// May need to limit how many questions we retrieve.
		$limitClause = "";
		if ($questionCount > 0) {
			$limitClause = " LIMIT ".$questionCount;
		}
		
		$questionQuery = "SELECT * FROM questions WHERE ownerFacebookId = '$ownerFacebookId' AND skipped = false AND chosenFacebookId != '' ORDER BY questionId DESC".$limitClause;
		$result = mysql_query($questionQuery);
		if (!$result || mysql_num_rows($result) == 0) {
			return array();
		}
		
		$questions = array();
		while ($row = mysql_fetch_array($result)) {
			try {
				$question = new MCQuestion($ownerFacebookId, $row["topicFacebookId"], $row["categoryId"], -1, $row["questionId"]);
			} catch (Exception $e) {
				Error::saveExceptionToDB($e);
				continue;
			}
			
			// Fill in remaining fields that constructor didn't handle.
			$question->text = DB::cleanOutputFromDatabase($row["text"]);
			try {
				$question->correctSubject = new Subject($row["correctFacebookId"]);
				$question->chosenSubject = new Subject($row["chosenFacebookId"]);
			} catch (Exception $e) {
				Error::saveExceptionToDB($e);
				continue;
			}
			$question->answeredAt = $row["answeredAt"];
			$question->responseTime = $row["responseTime"];
				
			$questions[] = $question;
		}
		
		return $questions;
	}
	
	public static function getUnansweredQuestionsFromDB($ownerFacebookId, $questionCount, $optionCount, $topicFacebookId, $categoryId) {
		$sqlTopicFacebookId = empty($topicFacebookId) ? '' : " AND topicFacebookId = '$topicFacebookId'";
		$sqlCategoryId = empty($categoryId) ? '' : " AND categoryId = '$categoryId'";
		$questionQuery = "SELECT * FROM questions WHERE ownerFacebookId = '$ownerFacebookId' AND answeredAt = 0 AND createdAt >= ".Cache::minUnexpiredUnixTimestamp()." AND (SELECT COUNT(*) FROM options WHERE questions.questionId = options.questionId) = $optionCount $sqlTopicFacebookId $sqlCategoryId ORDER BY questionId LIMIT ".$questionCount;
		$result = mysql_query($questionQuery);
		if (!$result || mysql_num_rows($result) == 0) {
			return array();
		}
		
		$questions = array();
		while ($row = mysql_fetch_array($result)) {
			try {
				$question = new MCQuestion($ownerFacebookId, $row["topicFacebookId"], $row["categoryId"], -1, $row["questionId"]);
			} catch (Exception $e) {
				Error::saveExceptionToDB($e);
				continue;
			}
			
			// Fill in remaining fields that constructor didn't handle.
			$question->text = DB::cleanOutputFromDatabase($row["text"]);
			try {
				$question->correctSubject = new Subject($row["correctFacebookId"]);
			} catch (Exception $e) {
				Error::saveExceptionToDB($e);
				continue;
			}
				
			$questions[] = $question;
		}
		
		return $questions;
	}
	
	public static function countUnansweredQuestionsFromDB($ownerFacebookId) {
		$questionQuery = "SELECT COUNT(*) FROM questions WHERE ownerFacebookId = '$ownerFacebookId' AND answeredAt = 0 AND createdAt >= ".Cache::minUnexpiredUnixTimestamp();
		$result = mysql_query($questionQuery);
		if (!$result || mysql_num_rows($result) == 0) {
			return 0;
		}
		
		$row = mysql_fetch_array($result);
		return $row["COUNT(*)"];
	}
	
	public static function assert($testInstance, $json) {
		$testInstance->assertNotNull($json);
		$testInstance->assertTrue($json->questionId > 0);
		
		Category::assert($testInstance, $json->category);
		$testInstance->assertNotNull($json->text);
		$testInstance->assertFalse(empty($json->text));
		Subject::assert($testInstance, $json->topicSubject);
		Subject::assert($testInstance, $json->correctSubject);
	}
}
?>
