<?php
/**
 * This class implements an option (for a question) in the GuessThatFriend app.
 *
 *
 */
class Option	{

	private $optionId;
	private $questionId;		// ID of the question that this option is part of.
	private $topicSubject;		// The person or page that this option is about.
	
	/**
	 * __construct
	 *
	 * @param int $questionId ID of the question which this option belongs to
	 * @param Subject $topicSubject The topic subject of the option
	 * @param int $optionId ID of the option
	 * @return void
	 */	
	public function __construct($questionId, $topicSubject, $optionId = -1)	{
		$this->optionId = $optionId;
		$this->questionId = $questionId;
		$this->topicSubject = $topicSubject;
		
		if ($optionId == -1) {
			$this->saveToDB();
		}
	}

	/**
	 * Generic getter method.
	 *
	 * @param string $field Name of field
	 * @return object Value of field
	 */
	public function __get($field) {
		return $this->$field;
	}

	/**
	 * Save option to database.
	 *
	 * @return bool True on successful query, false otherwise
	 */
	private function saveToDB()	{
		$insertQuery = "INSERT INTO options (questionId, topicFacebookId) VALUES ('".$this->questionId."', '".$this->topicSubject->facebookId."')";
		$result = mysql_query($insertQuery);
		
		if (!$result) {
			JSON::outputFatalErrorAndExit("Unable to save question options to database.");
			
			return false;
		}
		
		$this->optionId = mysql_insert_id();
		
		return true;
	}
	
	/**
	 * Get the options for a particular question from the database.
	 *
	 * @param int $questionId ID of the question
	 * @return array Array of Options
	 *
	 */
	public static function getOptionsFromDB($questionId) {
		$optionQuery = "SELECT * FROM options WHERE questionId = '$questionId' ORDER BY optionId";
		$result = mysql_query($optionQuery);
		if (!$result || mysql_num_rows($result) == 0) {
			return array();
		}
		
		$options = array();
		while ($row = mysql_fetch_array($result)) {
			$optionId = $row["optionId"];
			$topicSubject = new Subject($row["topicFacebookId"]);
			$options[] = new Option($questionId, $topicSubject, $optionId);
		}
		
		return $options;
	}
	
	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return array Array formatted for JSON output
	 */
	public function jsonSerialize() {
		$obj = array();
		$obj["optionId"] = $this->optionId;
		$obj["topicSubject"] = $this->topicSubject->jsonSerialize();
		
		return $obj;
	}
}
?>
