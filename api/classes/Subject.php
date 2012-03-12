<?php
class Subject
{
	protected $facebookId;
	protected $name;
	protected $picture;
	protected $link;
	
	public function __set($field, $value)	{
        $this->$field = $value;
    }

    public function __get($field)	{
        return $this->$field;
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
