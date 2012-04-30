<html>
<body>
<h1>PHP Tests</h1>
<?php
require_once "../../references/facebook-php-sdk/src/facebook.php";
require_once 'Subject.php';
//require_once 'PHPUnit/Framework.php';

class FacebookTest{
	private $facebookAPI;

	public function testGetFriends(){
		$facebookId = "1436983640";
		$friends = $this->facebookAPI->getFriendsOf($facebookId);
		$this->testSubjects($friends);
	}

	public function testGetLikes(){
		$facebookId = "1436983640";
		$likes = $this->facebookAPI->getLikesOfFriend($facebookId);
		$this->testSubjects($likes);
	}
	
	public function testSubjects($subjects){
		//Make sure the 'friends' json has more than 1 friend
		echo '<p> Testing NumSubjects >0 : '.(sizeof($subjects) > 0).'</p>';

		/* Loop through all friends, check if friend information is valid */
		echo 'Running string checks on Subject info...</p>';
		for($i=0; $i < sizeof($subjects); $i++){
			$curr_subject = $subjects[$i];

			/* Check if subject name is not blank */
			if(strlen($curr_subject->$name)<=0)
				echo '<p>array index='.$i.' has 0 length friend name</p>';

			/* Check if facebookId is not blank */
			if(strlen($curr_subject->$facebookId)<=0)
				echo '<p>array index='.$i.' has 0 length facebook id</p>';

			/* Check if subject's display picture info is not corrupted */
			if(strlen($curr_subject->$picture)<=0)
				echo '<p>array index='.$i.' has corrupted picture information </p>';

			/* Check if subject's facebook link info is not corrupted */
			if(strlen($curr_subject->$link)<=0)
				echo '<p>array index='.$i.' has corrupted facebook link info</p>';

			/* Loop through all subjects, check if subject name breaks code (eg hypenated names) */
			$result = strlen(strpos($curr_subject->$name, "-"));
			if($result != 0)
				echo '<p> Failed sanity checks (hyphenated word) on '.$curr_subject->$name.', array index='.$i.'</p>';

		}
	}

	public function runAllTests(){
		$this->testGetFriends();
		$this->testGetLikes();
	}
	
	public function FacebookTest() {
		$this->facebookAPI = FacebookAPI::singleton();
		$this->facebookAPI->authenticate("");
	}
}

$facebookTest = new FacebookTest();
$facebookTest->runAllTests();
?>
</body>
</html>