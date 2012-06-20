<?php
require_once('../libraries/simpletest/autorun.php');

// Question includes.
require_once('../classes/Question.php');
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
		$contents = file_get_contents(Util::parentDirectoryURL().'?facebookAccessToken='.$_GET['facebookAccessToken'].'&'.$url,true);
		$json = json_decode($contents);
		
		return $json;
	}
	
	private function getAndTestOneQuestion() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getQuestions&questionCount=1");
		
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertTrue(count($json->questions) == 1);
		MCQuestion::assert($this, $json->questions[0], OptionType::DEFAULT_TYPE);
		
		return $json->questions[0];
	}
	
	function testGetQuestionsWithQuestionCountOne() {
		$question = IntegrationTests::getAndTestOneQuestion();
	}

	function testGetQuestionsWithQuestionCountOneAndOptionCountTwo() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getQuestions&questionCount=1&optionCount=".OptionType::MC_2);
		
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertTrue(count($json->questions) == 1);
		MCQuestion::assert($this, $json->questions[0], OptionType::MC_2);
	}
	
	function testGetQuestionsWithQuestionCountDefault() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getQuestions");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertTrue(count($json->questions) == API::DEFAULT_QUESTION_COUNT);
	}
	
	function testGetQuestionsWithQuestionCountTwo() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getQuestions&questionCount=2");
		
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertTrue(count($json->questions) == 2);
		MCQuestion::assert($this, $json->questions[0], OptionType::DEFAULT_TYPE);
		MCQuestion::assert($this, $json->questions[1], OptionType::DEFAULT_TYPE);
	}
	
	function testSubmitQuestionsWithNoParameters() {
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->savedQuestionIds);
		$this->assertTrue(count($json->savedQuestionIds) == 0);
		$this->assertNotNull($json->skippedQuestionIds);
		$this->assertTrue(count($json->skippedQuestionIds) == 0);
		$this->assertTrue($json->duration > 0);
	}
	
	function testSubmitQuestionsWithOneAnsweredQuestionAndResponseTime() {
		// Get question.
		$question = IntegrationTests::getAndTestOneQuestion();
		$questionId = $question->questionId;
		$correctSubjectFacebookId = $question->correctSubject->facebookId;
		
		// Submit answer to this question.
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions&facebookIdOfQuestion".$questionId.'='.$correctSubjectFacebookId.'&responseTimeOfQuestion'.$questionId.'=100000');
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->savedQuestionIds);
		$this->assertTrue(count($json->savedQuestionIds) == 1);
		$this->assertTrue($json->savedQuestionIds[0] == $questionId);
		
		$this->assertNotNull($json->skippedQuestionIds);
		$this->assertTrue(count($json->skippedQuestionIds) == 0);
		$this->assertTrue($json->duration > 0);
	}
	
	function testSubmitQuestionsWithOneResponseTimeOnly() {
		// Get question.
		$question = IntegrationTests::getAndTestOneQuestion();
		$questionId = $question->questionId;
		$correctSubjectFacebookId = $question->correctSubject->facebookId;
		
		// Submit answer to this question.
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions&responseTimeOfQuestion".$questionId.'=100000');
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->savedQuestionIds);
		$this->assertTrue(count($json->savedQuestionIds) == 0);
		$this->assertNotNull($json->skippedQuestionIds);
		$this->assertTrue(count($json->skippedQuestionIds) == 0);
		$this->assertTrue($json->duration > 0);
	}
	
	function testSubmitQuestionsWithOneSkippedQuestion() {
		// Get question.
		$question = IntegrationTests::getAndTestOneQuestion();
		$questionId = $question->questionId;
		$correctSubjectFacebookId = $question->correctSubject->facebookId;
		
		// Skip question.
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions&skippedQuestionIds[]=".$questionId);
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->savedQuestionIds);
		$this->assertTrue(count($json->savedQuestionIds) == 0);
		
		$this->assertNotNull($json->skippedQuestionIds);
		$this->assertTrue(count($json->skippedQuestionIds) == 1);
		$this->assertTrue($json->skippedQuestionIds[0] == $questionId);
		$this->assertTrue($json->duration > 0);
	}
	
	function testGetCategories() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getCategories");
		$this->assertNotNull($json);
		
		foreach ($json as $category) {
			Category::assert($this, $category);
		}
	}
	
	function testGetStatisticsWithNoSetup() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getStatistics");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
	}
	
	function testGetStatisticsWithTypeFriends() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getStatistics&type=friends");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		
		$this->assertNotNull($json->friends);
	}
	
	function testGetStatisticsWithTypeCategories() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getStatistics&type=categories");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		
		$this->assertNotNull($json->categories);
	}
	
	function testGetStatisticsWithTypeHistory() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getStatistics&type=history");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		
		$this->assertNotNull($json->questions);
	}
}
?>