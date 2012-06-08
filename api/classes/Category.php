<?php
/**
 * This class implements a page category in the GuessThatFriend app.
 *
 *
 */
class Category	{

	private $categoryId;		// ID of category as stored in server database.
	private $facebookName;		// The name that Facebook gives to this category.
	private $prettyName;		// The pretty name that we give to this category.
	
	/**
	 * __construct
	 *
	 * @param int $categoryId ID of category to be created
	 * @param string $facebookName Category name used by Facebook
	 * @param string $prettyName Pretty/User friendly category name
	 * @return void
	 */	
	public function __construct($categoryId, $facebookName = "", $prettyName = "")	{
		$this->categoryId = $categoryId;
		$this->facebookName = $facebookName;
		$this->prettyName = $prettyName;
		
		if (empty($this->facebookName) || empty($this->prettyName)) {
			$this->fillInFieldsFromDB();
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
	 * Fill in class fields using data from the database.
	 *
	 * @return bool True on successful query, false otherwise
	 */
	private function fillInFieldsFromDB()	{
		$nameQuery = "SELECT facebookName, prettyName FROM categories WHERE categoryId = ".$this->categoryId." LIMIT 1";
		$result = mysql_query($nameQuery);
		
		if ($result && mysql_num_rows($result) == 1) {
			$row = mysql_fetch_array($result);
			
			$this->facebookName = DB::cleanOutputFromDatabase($row["facebookName"]);
			$this->prettyName = DB::cleanOutputFromDatabase($row["prettyName"]);
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Add new category to database.
	 *
	 * @param string $facebookName The name that Facebook gives to this category
	 * @return Category The newly created category
	 */
	private static function addFacebookNameToDB($facebookName) {
		$insertQuery = "INSERT INTO categories (facebookName, prettyName) VALUES ('".DB::cleanInputForDatabase($facebookName)."', '".DB::cleanInputForDatabase($facebookName)."')";
		$result = mysql_query($insertQuery);
		if (!result)
			return false;
		return new Category(mysql_insert_id(), $facebookName, $facebookName);
	}
	
	/**
	 * Returns the Category corresponding to a category name used by Facebook.
	 * 
	 * @param string $facebookName Category name used by Facebook.
	 * @return Category Category with the supplied facebookName
	 */
	public static function getCategoryByFacebookName($facebookName)	{
		$query = "SELECT * FROM categories WHERE facebookName = '".DB::cleanInputForDatabase($facebookName)."' LIMIT 1";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result) == 1) { // Category is already in database.
			$row = mysql_fetch_array($result);
			return new Category($row["categoryId"], DB::cleanOutputFromDatabase($row["facebookName"]), DB::cleanOutputFromDatabase($row["prettyName"]));
		}
		
		// Add new category to database.
		return Category::addFacebookNameToDB($facebookName);
	}
	
	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return array Array formatted for JSON output
	 */
	public function jsonSerialize() {
		$obj = array();
		$obj["categoryId"] = $this->categoryId;
		$obj["facebookName"] = $this->facebookName;
		$obj["prettyName"] = $this->prettyName;
		
		return $obj;
	}

	/**
	 * Checks if there are enough pages for this category.
	 *
	 * @return bool True if there are enough pages, false otherwise.
	 */	
	public function enoughRandomPagesOfSameCategory() {
		$MIN_PAGES_OF_SAME_CATEGORY = 6;
		
		$query = "SELECT COUNT(*) AS count FROM randomPages WHERE categoryFacebookName = '".DB::cleanInputForDatabase($this->facebookName)."' LIMIT 1";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result) == 1) {
			$row = mysql_fetch_array($result);
			
			return $row["count"] >= $MIN_PAGES_OF_SAME_CATEGORY;
		}

		return false;
	}
}
?>
