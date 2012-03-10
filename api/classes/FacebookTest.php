<?php

require_once 'Facebook.php';

class FacebookTest extends PHPUnit_Framework_Test{

	public $test_obj;	

	public function setUp(){
		$this->test_obj = new Facebook_API();
		$this->test_obj->setupFacebook();
	}

	public function testGetFriends(){
		$userID = "1436983640";				//Colin's fb id
		$this->test_obj->getFriends(userID);


	}

}




?>
