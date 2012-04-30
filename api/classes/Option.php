<?php
class Option
{
	private $optionId;
	private $questionId;		// ID of the question that this option is part of.
	private $topicSubject;		// The person or page that this option is about.
	
	public function __construct($questionId, $topicSubject, $optionId = -1)	{
		$this->optionId = $optionId;
		$this->questionId = $questionId;
		$this->topicSubject = $topicSubject;
		
		if ($optionId == -1) {
			$this->saveToDB();
		}
	}

	public function __get($field) {
		return $this->$field;
	}

	private function saveToDB()	{
		$insertQuery = "INSERT INTO options (questionId, topicFacebookId) VALUES ('".$this->questionId."', '".$this->topicSubject->facebookId."')";
		$queryResult = mysql_query($insertQuery);
		
		if (!$queryResult) {
			JSON::outputFailure("Unable to save question options to database.");
			
			return false;
		}
		
		$this->optionId = mysql_insert_id();
		
		return true;
	}
	
	public static function getOptionsFromDB($questionId) {
		$result = mysql_query("SELECT * FROM options WHERE questionId = '$questionId' ORDER BY optionId");
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
	
	public function jsonSerialize() {
		$obj = array();
		$obj["optionId"] = $this->optionId;
		$obj["topicSubject"] = $this->topicSubject->jsonSerialize();
		
		return $obj;
	}
}
?>
