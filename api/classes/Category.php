<?php
class Category
{
	private $categoryId;		// ID of category as stored in server database.
	private $facebookName;		// The name that Facebook gives to this category.
	private $prettyName;		// The pretty name that we give to this category.
	
	public function __construct($categoryId)	{
		$this->categoryId = $categoryId;
		$this->facebookName = "";
		$this->prettyName = "";
		
		$this->fillInFieldsFromDB();
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
	
	private static function addCategoryToDB($facebookName) {
		mysql_query("INSERT INTO categories (facebookName, prettyName) VALUES ('".API::cleanInputForDatabase($facebookName)."', '".API::cleanInputForDatabase($facebookName)."')");
		return mysql_insert_id();
	}
	
	public static function getCategoryId($facebookName)	{
		$query = "SELECT categoryId FROM categories WHERE facebookName = '".API::cleanInputForDatabase($facebookName)."' LIMIT 1";
		$queryResult = mysql_query($query);
		
		if ($queryResult && mysql_num_rows($queryResult) == 1) { // Category is already in database.
			$row = mysql_fetch_array($queryResult);
			return $row["categoryId"];
		} else { // Add new category to database.
			return Category::addCategoryToDB($facebookName);
		}
		
		return false;
	}
	
	public function jsonSerialize() {
		$obj = array();
		$obj["categoryId"] = $this->categoryId;
		$obj["facebookName"] = $this->facebookName;
		$obj["prettyName"] = $this->prettyName;
		
		return $obj;
	}
}
?>
