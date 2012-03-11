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
}
?>
