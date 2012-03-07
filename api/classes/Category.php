<?php
class Category
{
	protected $categoryId;		// ID of category as stored in server database.
	protected $facebookName;	// The name that Facebook gives to this category.
	protected $prettyName;		// The pretty name that we give to this category.
	
	public function setCategoryId($id)	{
		$this->categoryId = $id;
	}
	
	public function getFacebookName()	{
		return $this->getName ("facebookName");
	}
	
	public function getPrettyName()	{
		return $this->getName ("prettyName");
	}
	
	private function getName($nameType)	{
		require_once "DB.php";
		global $categoryId;
	
		DB::connect();
		
		$nameQuery = "SELECT ".$nameType." FROM categories WHERE categoryId = ".$categoryId;
		$queryResult = mysql_query($nameQuery);
		
		if (!$queryResult) {
			echo $nameQuery;
			echo mysql_error();
		} else {
			$name = mysql_fetch_array($queryResult);
		}
		
		DB::close();
		
		if (isset($name))	{
			return $name;
		} else {
			return;
		}
	}
}
?>
