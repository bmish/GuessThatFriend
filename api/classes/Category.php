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
			$this->fillInNamesFromDB();
		}
	}
	
	public function __get($field)	{
		return $this->$field;
	}
	
	private function fillInNamesFromDB()	{
		$nameQuery = "SELECT facebookName, prettyName FROM categories WHERE categoryId = ".$this->categoryId." LIMIT 1";
		$queryResult = mysql_query($nameQuery);
		
		if ($queryResult && mysql_num_rows($queryResult) == 1) {
			$row = mysql_fetch_array($queryResult);
			
			$this->facebookName = cleanOutputFromDatabase($row["facebookName"]);
			$this->prettyName = cleanOutputFromDatabase($row["prettyName"]);
		}
	}
	
	public static function getCategoryId($facebookName)	{
		$idQuery = "SELECT categoryId FROM categories WHERE facebookName = '$facebookName' LIMIT 1";
		$queryResult = mysql_query($idQuery);
		
		if (!$queryResult) {
			echo $nameQuery;
			echo mysql_error();
		} else {
			return mysql_fetch_array($queryResult);
		}
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
