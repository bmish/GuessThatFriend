<?php
class Category
{
	protected $categoryId;		// ID of category as stored in server database.
	protected $facebookName;	// The name that Facebook gives to this category.
	protected $prettyName;		// The pretty name that we give to this category.
	
	public function __construct($categoryId)	{
		$this->categoryId = $categoryId;
		$this->facebookName = $this->getName('facebookName');
		$this->prettyName = $this->getName('prettyName');
	}
	
	public function __get($field)	{
		return $this->$field;
	}
	
	private function getName($nameType)	{
		global $categoryId;
		
		$nameQuery = "SELECT $nameType FROM categories WHERE categoryId = $categoryId LIMIT 1";
		$queryResult = mysql_query($nameQuery);
		
		if (!$queryResult) {
			echo $nameQuery;
			echo mysql_error();
		} else {
			return mysql_fetch_array($queryResult);
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
