<?php
require_once('simpletest/autorun.php');

class APIIntegrationTests extends UnitTestCase {
	function getQuizHelper($qCount, $oCount, $cat) {
		$url = 'http://guessthatfriend.jasonsze.com/api/';
		$addr=$url.'?cmd=getQuestions&facebookAccessToken=xxx&questionCount='.$qCount.'&optionCount='.$oCount.'&categoryId='.$cat;

		$jsonFile = fopen($addr, 'r');
		while ($out = fread($jsonFile, 1024)) $content .= $out;
		fclose($jsonFile);
		$json=json_decode($content);
		
		foreach ($json->questions as $question) {
			$category = $question->category->categoryId;
			$this->assertEqual($category, $cat);
			$opNum = sizeof($question->options);
			$this->assertEqual($opNum, $oCount);
		}
		$this->assertEqual($qCount, sizeof($json->questions));
	}

	function submitQuizHelper($questionId) {
		$url = 'http://guessthatfriend.jasonsze.com/api/';
		$addr = $url.'?cmd=submitQuestions&facebookAccessToken=xxx&optionIdOfQuestion'.questionId.'=12';
		$jsonFile = fopen($addr, 'r');
		while ($out = fread($jsonFile, 1024)) $content .= $out;
		fclose($jsonFile);

		$json = json_decode($content);

		$this->assertEqual($json->questions[0], $questionId);
	}

        function testGetQuizOneQuestion() {
                echo 'Questions=2,Options=2,Category=2<br><br>';
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

	function testGetQuizTwentyQuestions() {
                echo 'Questions=20,Options=7,Category=3<br><br>';
		$this->getQuizHelper(20,7,3);
	}

	function testGetQuizTwentyfiveQuestions() {
                echo 'Questions=25,Options=8,Category=3<br><br>';
		$this->getQuizHelper(25,8,3);
	}

	function testGetQuizLotsOfQuestions() {
                echo 'Questions=250,Options=3,Category=3<br><br>';
		$this->getQuizHelper(250,3,3);
	}

	function testGetQuizNegQCnt() {
                echo 'Questions=-1,Options=3,Category=3<br><br>';
		$this->getQuizHelper(-1,3,3);
	}

	function testGetQuizNegOpCnt() {
                echo 'Questions=1,Options=-3,Category=3<br><br>';
		$this->getQuizHelper(1,-3,3);
	}

	function testGetQuizNegCategory() {
                echo 'Questions=1,Options=3,Category=-3<br><br>';
		$this->getQuizHelper(1,3,-3);
	}

	function testGetQuizZeroOpt() {
                echo 'Questions=1,Options=0,Category=3<br><br>';
		$this->getQuizHelper(1,0,3);
	}

	function testGetQuizZeroQuestions() {
                echo 'Questions=0,Options=3,Category=3<br><br>';
		$this->getQuizHelper(0,3,3);
	}

	function testGetQuizStringQuestionNum() {
                echo 'Questions="1",Options=2,Category=2<br><br>';
		$this->getQuizHelper("1",2,2);
	}

        function testSubmitQuizQuestionIdEleven() {
                echo 'QuestionID=11<br><br>';
		$this->submitQuizHelper(11);
	}

	function testSubmitQuizQuestionIdTwelve() {
                echo 'QuestionID=12<br><br>';
		$this->submitQuizHelper(12);
	}

	function testSubmitQuizQuestionIdOneHundred() {
                echo 'QuestionID=100<br><br>';
		$this->submitQuizHelper(100);
	}

	function testSubmitQuizNegQuestion() {
                echo 'QuestionID=-1<br><br>';
		$this->submitQuizHelper(-1);
	}
}
?>
