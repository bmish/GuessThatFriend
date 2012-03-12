<?php
class Category
{
	protected $categoryId;		// ID of category as stored in server database.
	protected $facebookName;	// The name that Facebook gives to this category.
	protected $prettyName;		// The pretty name that we give to this category.
	
	public function __construct($categoryId)	{
		$this->categoryId = $categoryId;
		$this->facebookName = $this->getFacebookName();
		$this->prettyName = $this->getPrettyName();
	}
	
	public function getFacebookName()	{
		if (isset($this->facebookName)) return $this->facebookName;
		return $this->getName ("facebookName");
	}
	
	public function getPrettyName()	{
		if (isset($this->prettyName))	return $this->prettyName;
		return $this->getName ("prettyName");
	}
	
	private function getName($nameType)	{
		global $categoryId;
		
		$nameQuery = "SELECT ".$nameType." FROM categories WHERE categoryId = ".$categoryId;
		$queryResult = mysql_query($nameQuery);
		
		if (!$queryResult) {
			echo $nameQuery;
			echo mysql_error();
		} else {
			return mysql_fetch_array($queryResult);
		}
	}
	
	public static function getCategoryId($facebookName)	{
		$idQuery = "SELECT categoryId FROM categories WHERE facebookName = ".$facebookName;
		$queryResult = mysql_query($idQuery);
		
		if (!$queryResult) {
			echo $nameQuery;
			echo mysql_error();
		} else {
			return mysql_fetch_array($queryResult);
		}
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
