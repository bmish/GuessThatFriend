<?php
class Option
{
	protected $optionId;
	protected $questionId;		// ID of the Question that this Option is part of.
	protected $subject;		// The person or page of this Option.
	
	public function __construct($questionId, $subjectId)	{
		$this->optionId = -1;
		$this->questionId = $questionId;
		$this->subject = new Subject($subjectId);
		
		//saveToDB();
	}

	public function __get($field)	{
		return $this->$field;
	}

	private function saveToDB()	{
		$insertQuery = "INSERT INTO options (questionId, facebookId) VALUES (".$this->questionId.", ".$this->subject->subjectId.")";
		$queryResult = mysql_query($insertQuery);
		
		if (!$queryResult) {
			echo $nameQuery;
			echo mysql_error();
		} else {
			$id = mysql_insert_id();
			if ($id == 0)	{
				echo "Previous query does not generate an AUTO_INCREMENT value";
			} else if (!$id)	{
				echo "No MySQL connection was established";
			} else {
				$this->optionId = $id;
			}
		}
	}
	
	public function jsonSerialize() {
		$obj = array();
		$obj["optionId"] = $this->optionId;
		$obj["subject"] = $this->subject->jsonSerialize();
		
		return $obj;
	}
}
?>
