<?php
class Option
{
	private $optionId;
	private $questionId;		// ID of the Question that this Option is part of.
	private $subject;			// The person or page of this Option.
	
	public function __construct($questionId, $subjectId)	{
		$this->optionId = -1;
		$this->questionId = $questionId;
		$this->subject = new Subject($subjectId);
		
		$this->saveToDB();
	}

	public function __get($field)	{
		return $this->$field;
	}

	private function saveToDB()	{
		$insertQuery = "INSERT INTO options (questionId, facebookId) VALUES ('".$this->questionId."', '".$this->subject->facebookId."')";
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
		$obj["subject"] = $this->subject->jsonSerialize();
		
		return $obj;
	}
}
?>
