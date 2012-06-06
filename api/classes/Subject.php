<?php
/**
 * This class implements a facebook subject in the GuessThatFriend app.
 *
 * @copyright  2012 GuessThatFriend
 */
class Subject	{

	private $facebookId;
	private $name;
	private $picture;
	private $link;
	private $category;
	
	/**
	 * __construct
	 *
	 * @param string $faceboookId Facebook ID of subject
	 * @param string $name Name of the subject
	 * @param string $category Pretty/User friendly category name
	 * @return void
	 */	
	public function __construct($facebookId, $name = "", $category = null) {
		$facebookAPI = FacebookAPI::singleton();
		
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
	
	/**
	 * Generic getter method.
	 *
	 * @param string $field Name of field
	 * @return object Value of field
	 */
	public function __get($field)	{
        return $this->$field;
	}
	
	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return array Array formatted for JSON output
	 */
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
	
	/**
	 * Fill in class fields using data from the database.
	 *
	 * @return bool True on successful query, false otherwise
	 */
	private function fillInFieldsFromDB() {
		$query = "SELECT name, picture, link FROM subjects WHERE facebookId = '".$this->facebookId."' LIMIT 1";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result) == 1) {
			$row = mysql_fetch_array($result);
			
			$this->name = DB::cleanOutputFromDatabase($row["name"]);
			$this->picture = DB::cleanOutputFromDatabase($row["picture"]);
			$this->link = DB::cleanOutputFromDatabase($row["link"]);
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Save subject to database.
	 *
	 * @return bool True on successful query, false otherwise
	 */
	private function saveToDB()	{
		$insertQuery = "INSERT INTO subjects (facebookId, name, picture, link) VALUES ('".$this->facebookId."', '".DB::cleanInputForDatabase($this->name)."', '".$this->picture."', '".$this->link."')";
		$result = mysql_query($insertQuery);
		
		if (!$result) {
			JSON::outputFatalErrorAndExit("Unable to save subject to database.");
			return false;
		}

		return true;
	}
	
	/**
	 * Checks if the subject is a person or not.
	 *
	 * @return bool True if the subject is a person, false if it is a page
	 */
	public function isPerson() {
		return ($this->category == null);
	}
	
	/**
	 * Checks if the name is likely a sentence.
	 *
	 * @return bool True if it is likely a sentence, false otherwise
	 */
	public function nameIsLikelyASentence() {
		$NAME_MAX_WORDS = 7;
		
		return str_word_count($this->name) > $NAME_MAX_WORDS;
	}
}
?>
