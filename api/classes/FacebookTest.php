<html>
<body>
<h1> PHP Tests </h1>
<?php

require_once 'Facebook.php';
require_once 'Subject.php';
//require_once 'PHPUnit/Framework.php';

class FacebookTest{

	public static function testGetFriends(){
		$userID = "1436983640";				//Colin's fb id
		$friends_array = Facebook_API::getFriends($userID);
		FacebookTest::testSubjects($friends_array);

	}

	public static function testGetLikes(){
		$userID = "1436983640";				//Colin's fb id
		$likes_array = Facebook_API::getLikes($userID);
		FacebookTest::testSubjects($likes_array);

	}
	
	public static function testSubjects($subject_array){

		//Make sure the 'friends' json has more than 1 friend
		$arr_len = sizeof($subject_array);
		echo '<p> Testing NumSubjects >0 : '.($arr_len > 0).'</p>';

		/* Loop through all friends, check if friend information is valid */
		echo 'Running string checks on Subject info...</p>';
		for($i=0; $i<$arr_len; $i++){
			$curr_subject = $subject_array[$i];

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

	public static function runalltests(){
		FacebookTest::testGetFriends();
		FacebookTest::testGetLikes();
	}

}

FacebookTest::runalltests();

?>

</body>
</html>
