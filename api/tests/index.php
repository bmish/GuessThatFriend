<?php
require_once('../libraries/simpletest/autorun.php');

// Question includes.
require_once('../classes/Question.php');
require_once('../classes/FillBlankQuestion.php');
require_once('../classes/MCQuestion.php');

// Misc includes.
require_once('../classes/API.php');
require_once('../classes/Cache.php');
require_once('../classes/Category.php');
require_once('../classes/DB.php');
require_once('../classes/Error.php');
require_once('../classes/FacebookAPI.php');
require_once('../classes/JSON.php');
require_once('../classes/Option.php');
require_once('../classes/OptionType.php');
require_once('../classes/StatisticType.php');
require_once('../classes/Subject.php');
require_once('../classes/Util.php');

class IntegrationTests extends UnitTestCase {
	private static function getJSONFromAPI($url) {
		$contents = file_get_contents('http://localhost/GuessThatFriend/api/?facebookAccessToken='.$_GET['facebookAccessToken'].'&'.$url);
		$json = json_decode($contents);
		
		return $json;
	}
	
	function testGetQuestionsWithQuestionCountOne() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getQuestions&questionCount=1");
		
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertTrue(count($json->questions) == 1);
		MCQuestion::assert($this, $json->questions[0], OptionType::DEFAULT_TYPE);
	}

	/*function testGetQuestionsWithQuestionCountOneAndOptionCountTwo() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getQuestions&questionCount=1&optionCount=".OptionType::MC_2);
		
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertTrue(count($json->questions) == 1);
		MCQuestion::assert($this, $json->questions[0], OptionType::MC_2);
	}*/
	
	function testGetQuestionsWithQuestionCountDefault() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getQuestions");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertTrue(count($json->questions) == API::DEFAULT_QUESTION_COUNT);
	}
}
?>