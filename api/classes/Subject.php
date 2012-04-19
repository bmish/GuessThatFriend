<?php
class Subject
{
	private $facebookId;
	private $name;
	private $picture;
	private $link;
	private $category;
	
	public function __construct($facebookId, $name = "", $category = null) {
		global $facebookAPI;
		
		$this->facebookId = $facebookId;
		$this->name = $name;
		$this->picture = 'https://graph.facebook.com/'.$facebookId.'/picture';
		$this->link = 'https://www.facebook.com/'.$facebookId;
		$this->category = $category;
		
		// Get these fields from Facebook if we haven't stored them yet.
		if (empty($this->name) && !$this->fillInFieldsFromDB()) {
			$this->name = $facebookAPI->getNameFromId($facebookId);
			
			$this->saveToDB();
		}
	}
	
	public function __get($field)	{
        return $this->$field;
	}
	
	public function jsonSerialize() {
		$obj = array();
		$obj["facebookId"] = $this->facebookId;
		$obj["name"] = $this->name;
		$obj["picture"] = $this->picture;
		$obj["link"] = $this->link;
		if ($this->category) {
			$obj["category"] = $this->category->jsonSerialize();
		}
		
		return $obj;
	}
	
	private function fillInFieldsFromDB() {
		$query = "SELECT name, picture, link FROM subjects WHERE facebookId = '".$this->facebookId."' LIMIT 1";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result) == 1) {
			$row = mysql_fetch_array($result);
			
			$this->name = API::cleanOutputFromDatabase($row["name"]);
			$this->picture = API::cleanOutputFromDatabase($row["picture"]);
			$this->link = API::cleanOutputFromDatabase($row["link"]);
			
			return true;
		}
		
		return false;
	}
	
	private function saveToDB()	{
		$insertQuery = "INSERT INTO subjects (facebookId, name, picture, link) VALUES ('".$this->facebookId."', '".$this->name."', '".$this->picture."', '".$this->link."')";
		$queryResult = mysql_query($insertQuery);
		
		if (!$queryResult) {
			return false;
		}

		return true;
	}
	
	public function isPerson() {
		return ($this->category == null);
	}
	
	public function nameIsLikelyASentence() {
		$NAME_MAX_WORDS = 7;
		
		return str_word_count($this->name) > $NAME_MAX_WORDS;
	}
}
?>
