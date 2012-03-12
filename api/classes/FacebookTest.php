<html>
<body>
<h1> PHP Tests </h1>
<?php

require_once 'Facebook.php';
require_once 'Subject.php';
//require_once 'PHPUnit/Framework.php';

class FacebookTest{

	//public $test_obj;	


	public static function testGetFriends(){
		$userID = "1436983640";				//Colin's fb id
		$subject_array = Facebook_API::getFriends($userID);
		$arr_len = sizeof($subject_array);
	
		//Make sure the 'friends' json has more than 1 friend
		echo '<p> Testing NumFriends >0 : '.($arr_len > 0).'</p>';

		/* Loop through all friends, check if friend information is valid */
		echo 'Running string checks on Friend info...</p>';
		for($i=0; $i<$arr_len; $i++){
			$curr_subject = $subject_array[$i];

			/* Check if friend name is not blank */
			if(strlen($curr_subject->$name)<=0)
				echo '<p>array index='.$i.' has 0 length friend name</p>';

			/* Check if facebookId is not blank */
			if(strlen($curr_subject->$facebookId)<=0)
				echo '<p>array index='.$i.' has 0 length facebook id</p>';

			/* Check if Friend's display picture info is not corrupted */
			if(strlen($curr_subject->$picture)<=0)
				echo '<p>array index='.$i.' has corrupted picture information </p>';

			/* Check if Friend's facebook link info is not corrupted */
			if(strlen($curr_subject->$link)<=0)
				echo '<p>array index='.$i.' has corrupted facebook link info</p>';

			/* Loop through all friends, check if friend name breaks code (eg hypenated names) */
			$result = strlen(strpos($curr_subject->$name, "-"));
			if($result != 0)
				echo '<p> Failed sanity checks (hyphenated word) on '.$curr_subject->$name.', array index='.$i.'</p>';

		}
		
		
		return 0;
	}
	
	public static function foo(){
		echo '<p>Hello World</p>';

	}

	public static function runalltests(){
		//$this->test_obj = new Facebook_API();
		//$this->test_obj->setupFacebook();
		FacebookTest::testGetFriends();
		//FacebookTest::foo();
	}

}

//$testObj= new FacebookTest(); 
//$testObj->runalltests();
FacebookTest::runalltests();


?>


</body>
</html>
