<?php
class Subject
{
	protected $facebookId;
	protected $name;
	protected $picture;
	protected $link;

    public function __get($field)	{
        return $this->$field;
	}
	
	public function __construct($friendData) {
		$this->name = $friendData['name'];
		$this->facebookId = $friendData['id'];
		$this->picture = 'https://graph.facebook.com/'.$friendData['id'].'/picture';
		$this->link = 'https://www.facebook.com/'.$friendData['id'];
	}
	
	public static function getNameFromId($facebookId)	{
		$nameQuery = "SELECT name FROM subjects WHERE facebookId = ".$facebookId;
		$queryResult = mysql_query($nameQuery);
		
		if (!$queryResult) {
			echo $nameQuery;
			echo mysql_error();
		} else {
			return mysql_fetch_array($queryResult);
		}
	}
}
?>
