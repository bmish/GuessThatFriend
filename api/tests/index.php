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

require_once('../config/config.php');
require_once('../libraries/facebook-php-sdk/src/facebook.php');

// Connect to database.
DB::connect();

// Require a facebookAccessToken in the URL.
$facebookAPI = FacebookAPI::singleton();
if (!$facebookAPI->authenticate($_GET['facebookAccessToken'])) {
	echo '<p>Error: URL must contain ?facebookAccessToken=xxx to run tests.</p>';
	exit;
}

// Close database connection.
DB::close();

class IntegrationTests extends UnitTestCase {
	const SAMPLE_FACEBOOK_ID = "zuck";
	
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
		$this->assertEqual(count($json->questions), 1);
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
		$this->assertEqual(count($json->questions), 1);
		MCQuestion::assert($this, $json->questions[0], OptionType::MC_2);
	}
	
	function testGetQuestionsWithQuestionCountDefault() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getQuestions");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertEqual(count($json->questions), API::DEFAULT_QUESTION_COUNT);
	}
	
	function testGetQuestionsWithQuestionCountTwo() {
		$json = IntegrationTests::getJSONFromAPI("cmd=getQuestions&questionCount=2");
		
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertEqual(count($json->questions), 2);
		MCQuestion::assert($this, $json->questions[0], OptionType::DEFAULT_TYPE);
		MCQuestion::assert($this, $json->questions[1], OptionType::DEFAULT_TYPE);
	}
	
	function testSubmitQuestionsWithNoParameters() {
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->savedQuestionIds);
		$this->assertEqual(count($json->savedQuestionIds), 0);
		$this->assertNotNull($json->skippedQuestionIds);
		$this->assertEqual(count($json->skippedQuestionIds), 0);
		$this->assertTrue($json->duration > 0);
	}
	
	function testSubmitQuestionsWithOneAnsweredQuestionAndResponseTime() {
		// Get question.
		$question = IntegrationTests::getAndTestOneQuestion();
		$questionId = $question->questionId;
		
		// Submit answer to this question.
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions&facebookIdOfQuestion".$questionId.'='.IntegrationTests::SAMPLE_FACEBOOK_ID.'&responseTimeOfQuestion'.$questionId.'=100000');
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->savedQuestionIds);
		$this->assertEqual(count($json->savedQuestionIds), 1);
		$this->assertEqual($json->savedQuestionIds[0], $questionId);
		
		$this->assertNotNull($json->skippedQuestionIds);
		$this->assertEqual(count($json->skippedQuestionIds), 0);
		$this->assertTrue($json->duration > 0);
	}
	
	function testSubmitQuestionsWithOneResponseTimeOnly() {
		// Get question.
		$question = IntegrationTests::getAndTestOneQuestion();
		$questionId = $question->questionId;
		
		// Submit answer to this question.
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions&responseTimeOfQuestion".$questionId.'=100000');
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->savedQuestionIds);
		$this->assertEqual(count($json->savedQuestionIds), 0);
		$this->assertNotNull($json->skippedQuestionIds);
		$this->assertEqual(count($json->skippedQuestionIds), 0);
		$this->assertTrue($json->duration > 0);
	}
	
	function testSubmitQuestionsWithOneSkippedQuestion() {
		// Get question.
		$question = IntegrationTests::getAndTestOneQuestion();
		$questionId = $question->questionId;
		
		// Skip question.
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions&skippedQuestionIds[]=".$questionId);
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->savedQuestionIds);
		$this->assertEqual(count($json->savedQuestionIds), 0);
		
		$this->assertNotNull($json->skippedQuestionIds);
		$this->assertEqual(count($json->skippedQuestionIds), 1);
		$this->assertEqual($json->skippedQuestionIds[0], $questionId);
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
	
	function testGetStatisticsWithTypeHistoryBySubmittingAQuestion() {
		// Get a question.
		$question = IntegrationTests::getAndTestOneQuestion();
		$questionId = $question->questionId;
		
		// Answer the question.
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions&facebookIdOfQuestion".$questionId."=".IntegrationTests::SAMPLE_FACEBOOK_ID."&responseTimeOfQuestion".$questionId.'=100000');
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->savedQuestionIds);
		$this->assertEqual(count($json->savedQuestionIds), 1);
		$this->assertEqual($json->savedQuestionIds[0], $questionId);
		
		// Get the most recent question from the history.
		$json = IntegrationTests::getJSONFromAPI("cmd=getStatistics&type=history");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertTrue(count($json->questions) >= 1);
		
		// Check if this question matches the one we just submitted.
		$mostRecentQuestion = $json->questions[0];
		MCQuestion::assert($this, $mostRecentQuestion, OptionType::DEFAULT_TYPE);
		$this->assertEqual($mostRecentQuestion->questionId, $questionId);
		Subject::assert($this, $mostRecentQuestion->chosenSubject);
		$this->assertEqual($mostRecentQuestion->chosenSubject->facebookId, IntegrationTests::SAMPLE_FACEBOOK_ID);
		$this->assertEqual($mostRecentQuestion->responseTime, 100000);
		$this->assertNotNull($mostRecentQuestion->answeredAt);
		$this->assertFalse(empty($mostRecentQuestion->answeredAt));
	}
	
	function testGetStatisticsWithTypeHistoryBySkippingAQuestion() {
		// Get a question.
		$question = IntegrationTests::getAndTestOneQuestion();
		$questionId = $question->questionId;
		
		// Skip the question.
		$json = IntegrationTests::getJSONFromAPI("cmd=submitQuestions&skippedQuestionIds[]=".$questionId);
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertNotNull($json->skippedQuestionIds);
		$this->assertEqual(count($json->skippedQuestionIds), 1);
		$this->assertEqual($json->skippedQuestionIds[0], $questionId);
		
		// Get the most recent question from the history.
		$json = IntegrationTests::getJSONFromAPI("cmd=getStatistics&type=history");
		$this->assertNotNull($json);
		$this->assertTrue($json->success);
		$this->assertTrue($json->duration > 0);
		$this->assertNotNull($json->questions);
		$this->assertTrue(count($json->questions) >= 1);
		
		// The most recent question should not be the one we skipped.
		$mostRecentQuestion = $json->questions[0];
		MCQuestion::assert($this, $mostRecentQuestion, OptionType::DEFAULT_TYPE);
		$this->assertNotEqual($mostRecentQuestion->questionId, $questionId);
	}
}
?>