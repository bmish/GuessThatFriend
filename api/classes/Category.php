<?php
class Category
{
	private $categoryId;		// ID of category as stored in server database.
	private $facebookName;		// The name that Facebook gives to this category.
	private $prettyName;		// The pretty name that we give to this category.
	
	public function __construct($categoryId, $facebookName = "", $prettyName = "")	{
		$this->categoryId = $categoryId;
		$this->facebookName = $facebookName;
		$this->prettyName = $prettyName;
		
		if (empty($this->facebookName) || empty($this->prettyName)) {
			$this->fillInFieldsFromDB();
		}
	}
	
	public function __get($field)	{
		return $this->$field;
	}
	
	private function fillInFieldsFromDB()	{
		$nameQuery = "SELECT facebookName, prettyName FROM categories WHERE categoryId = ".$this->categoryId." LIMIT 1";
		$queryResult = mysql_query($nameQuery);
		
		if ($queryResult && mysql_num_rows($queryResult) == 1) {
			$row = mysql_fetch_array($queryResult);
			
			$this->facebookName = API::cleanOutputFromDatabase($row["facebookName"]);
			$this->prettyName = API::cleanOutputFromDatabase($row["prettyName"]);
		}
	}
	
	private static function addFacebookNameToDB($facebookName) {
		mysql_query("INSERT INTO categories (facebookName, prettyName) VALUES ('".API::cleanInputForDatabase($facebookName)."', '".API::cleanInputForDatabase($facebookName)."')");
		return new Category(mysql_insert_id(), $facebookName, $facebookName);
	}
	
	public static function getCategoryByFacebookName($facebookName)	{
		$query = "SELECT * FROM categories WHERE facebookName = '".API::cleanInputForDatabase($facebookName)."' LIMIT 1";
		$queryResult = mysql_query($query);
		if ($queryResult && mysql_num_rows($queryResult) == 1) { // Category is already in database.
			$row = mysql_fetch_array($queryResult);
			return new Category($row["categoryId"], $row["facebookName"], $row["prettyName"]);
		}
		
		// Add new category to database.
		return Category::addFacebookNameToDB($facebookName);
	}
	
	public function jsonSerialize() {
		$obj = array();
		$obj["categoryId"] = $this->categoryId;
		$obj["facebookName"] = $this->facebookName;
		$obj["prettyName"] = $this->prettyName;
		
		return $obj;
	}

	public function enoughRandomPagesOfSameCategory() {
		$MIN_PAGES_OF_SAME_CATEGORY = 6;
		
		$query = "SELECT COUNT(*) AS count FROM randomPages WHERE categoryFacebookName = '".$this->facebookName."' LIMIT 1";
		$result = mysql_query($query);
		if ($result && mysql_num_rows($result) == 1) {
			$row = mysql_fetch_array($result);
			
			return $row["count"] >= $MIN_PAGES_OF_SAME_CATEGORY;
		}

		return false;
	}
}
?>
