<?php
class Option
{
	protected $optionId;
	protected $questionId;		// ID of the Question that this Option is part of.
	protected $subjectId;		// The person or page of this Option.
	
	public function __construct($questionId, $subjectId)	{
		$this->questionId = $questionId;
		$this->subjectId = $subjectId;
		saveToDB();
	}
	
	/*
	 * Writes the new option into the database.
	 */
	private function saveToDB()	{
		$insertQuery = "INSERT INTO options (questionId, facebookId) VALUES (".$this->questionId.", ".$this->subjectId.")";
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
	
	public function __get($field)	{
		return $this->$field;
	}
}
?>
