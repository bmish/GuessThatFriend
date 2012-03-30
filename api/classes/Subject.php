<?php
class Subject
{
	private $facebookId;
	private $name;
	private $picture;
	private $link;
	
	public function __construct($facebookId, $name = "") {
		global $facebookAPI;
		
		$this->name = $name;
		$this->facebookId = $facebookId;
		$this->picture = 'https://graph.facebook.com/'.$facebookId.'/picture';
		$this->link = 'https://www.facebook.com/'.$facebookId;
		
		if ($this->name == "") {
			$this->name = $facebookAPI->getNameFromId($facebookId);
		}
	}
	
	public function __get($field)	{
        return $this->$field;
	}
	
	public function jsonSerialize() {
		$obj = array();
		$obj["facebookId"] = $this->facebookId;
		$obj["name"] = $this->name;
		$obj["picture"] = $this->picture;
		$obj["link"] = $this->link;
		
		return $obj;
	}
}
?>
