<?php
class Option
{
	private $optionId;
	private $questionId;		// ID of the question that this option is part of.
	private $topicSubject;		// The person or page that this option is about.
	
	public function __construct($questionId, $topicSubject)	{
		$this->optionId = -1;
		$this->questionId = $questionId;
		$this->topicSubject = $topicSubject;
		
		$this->saveToDB();
	}

	public function __get($field) {
		return $this->$field;
	}

	private function saveToDB()	{
		$insertQuery = "INSERT INTO options (questionId, facebookId) VALUES ('".$this->questionId."', '".$this->topicSubject->facebookId."')";
		$queryResult = mysql_query($insertQuery);
		
		if (!$queryResult) {
			return false;
		}
		
		$this->optionId = mysql_insert_id();
		
		return true;
	}
	
	public function jsonSerialize() {
		$obj = array();
		$obj["optionId"] = $this->optionId;
		$obj["topicSubject"] = $this->topicSubject->jsonSerialize();
		
		return $obj;
	}
}
?>
