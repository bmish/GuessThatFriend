<?php
require_once('simpletest/autorun.php');

class APIIntegrationTests extends UnitTestCase {
	var $fbtok;
	function getQuizHelper($qCount, $oCount, $cat) {
		$this->fbtok = $_GET['facebookAccessToken'];
		$url = 'http://guessthatfriend.jasonsze.com/api/';
		$addr=$url.'?cmd=getQuestions&facebookAccessToken='.$this->fbtok.'&questionCount='.$qCount.'&optionCount='.$oCount.'&categoryId='.$cat;

		$jsonFile = fopen($addr, 'r');
		$content = NULL;
		while ($out = fread($jsonFile, 1024)) $content .= $out;
		fclose($jsonFile);
		$json=json_decode($content);
		
		$question = NULL;
		foreach ($json->questions as $question) {
			$category = $question->category->categoryId;
			//$this->assertEqual(intval($category), $cat);
			$opNum = sizeof($question->options);
			$this->assertEqual($opNum, $oCount);
		}
		$this->assertEqual($qCount, sizeof($json->questions));
		return $question->questionId;
	}

	function submitQuizHelper($questionId) {
		$this->fbtok = $_GET['facebookAccessToken'];
		$url = 'http://guessthatfriend.jasonsze.com/api/';
		$addr = $url.'?cmd=submitQuestions&facebookAccessToken='.$this->fbtok.'&facebookIdOfQuestion'.$questionId.'=1';
		$jsonFile = fopen($addr, 'r');
		$content = NULL;
		while ($out = fread($jsonFile, 1024)) $content .= $out;
		fclose($jsonFile);

		$json = json_decode($content);

		$this->assertEqual($json->questionIds[0], $questionId);
	}

        function testGetQuizOneQuestion() {
                echo 'Questions=1,Options=2,Category=2<br><br>';
		$this->getQuizHelper(1,2,2);
	}

        function testGetQuizFiveQuestions() {
                echo 'Questions=5,Options=4,Category=3<br><br>';
		$this->getQuizHelper(5,4,3);
	}

        function testGetQuizTenQuestions() {
                echo 'Questions=10,Options=4,Category=3<br><br>';
		$this->getQuizHelper(10,5,3);
	}

	function testGetQuizFifteenQuestions() {
                echo 'Questions=15,Options=6,Category=3<br><br>';
		$this->getQuizHelper(15,6,3);
	}

	
	function testSubmitQuizQuestionIdEleven() {
                echo 'QuestionID=11<br><br>';
		$this->submitQuizHelper(intval($this->getQuizHelper(1,2,2)));
	}

	function testSubmitQuizQuestionIdTwelve() {
                echo 'QuestionID=12<br><br>';
		$this->submitQuizHelper(intval($this->getQuizHelper(1,2,2)));
	}

	function testSubmitQuizQuestionIdOneHundred() {
                echo 'QuestionID=100<br><br>';
		$this->submitQuizHelper(intval($this->getQuizHelper(1,2,2)));
	}
	
}
?>
