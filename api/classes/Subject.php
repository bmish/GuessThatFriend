<?php
/**
 * This class implements a Facebook subject in the GuessThatFriend app.
 *
 */
class Subject	{

	private $facebookId;
	private $name;
	private $picture;
	private $link;
	private $category;
	
	const MIN_WORDS_IN_TITLE_TO_BE_CONSIDERED_A_SENTENCE = 7;
	
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
		$this->category = $category;
		
		// Get the name from Facebook if we haven't stored it yet.
		if (empty($this->name)) {
			$this->name = $facebookAPI->getNameFromId($facebookId);
			
			// Got nothing from Facebook?
			if (empty($this->name)) {
				throw new Exception("Failed to retrieve name of ".$this->facebookId." from Facebook.");
			}
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
		$obj["name"] = $this->name ? $this->name : "N/A";
		if ($this->category) {
			$obj["category"] = $this->category->jsonSerialize();
		}
		
		return $obj;
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
		return str_word_count($this->name) > Subject::MIN_WORDS_IN_TITLE_TO_BE_CONSIDERED_A_SENTENCE;
	}
}
?>
