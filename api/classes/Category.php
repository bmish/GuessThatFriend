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
		
		if ($categoryId > 0) {
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
			
			$this->facebookName = cleanOutputFromDatabase($row["facebookName"]);
			$this->prettyName = cleanOutputFromDatabase($row["prettyName"]);
		}
	}
	
	public static function getCategoryId($facebookName)	{
		$query = "SELECT categoryId FROM categories WHERE facebookName = '".cleanInputForDatabase($facebookName)."' LIMIT 1";
		$queryResult = mysql_query($query);
		
		if ($queryResult && mysql_num_rows($queryResult) == 1) {
			$row = mysql_fetch_array($queryResult);
			
			return $row["categoryId"];
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

// Testing
if ($_GET['testCategory'] == 'true') {
	for ($i = 1; $i <= 5; $i++)	{
		$cat = new Category($i);
		echo "<p>Category ".$i.": fbName = ".$cat->getFacebookName()."; prettyName = ".$cat->getPrettyName()."</p>";
	}
}
?>
