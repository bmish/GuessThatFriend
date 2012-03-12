<?php
require_once('simpletest/autorun.php');

class TestOfTesting extends UnitTestCase {
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

	function testGetQuiz1() {
		$this->getQuizHelper(1,2,2);
	}

	function testGetQuiz2() {
		$this->getQuizHelper(5,4,3);
	}

	function testGetQuiz3() {
		$this->getQuizHelper(10,5,3);
	}

	function testGetQuiz4() {
		$this->getQuizHelper(15,6,3);
	}

	function testGetQuiz5() {
		$this->getQuizHelper(20,7,3);
	}

	function testGetQuiz6() {
		$this->getQuizHelper(25,8,3);
	}

	function testGetQuizLots() {
		$this->getQuizHelper(250,3,3);
	}

	function testGetQuizNegQCnt() {
		$this->getQuizHelper(-1,3,3);
	}

	function testGetQuizNegOpCnt() {
		$this->getQuizHelper(1,-3,3);
	}

	function testGetQuizNegCategory() {
		$this->getQuizHelper(1,3,-3);
	}

	function testGetQuizZeroOpt() {
		$this->getQuizHelper(1,0,3);
	}

	function testGetQuizZeroQuestions() {
		$this->getQuizHelper(0,3,3);
	}

	function testGetQuizStringQuestionNum() {
		$this->getQuizHelper("1",2,2);
	}

	function testSubmitQuiz1() {
		$this->submitQuizHelper(11);
	}

	function testSubmitQuiz2() {
		$this->submitQuizHelper(12);
	}

	function testSubmitQuiz3() {
		$this->submitQuizHelper(100);
	}

	function testSubmitQuizNegQuestion() {
		$this->submitQuizHelper(-1);
	}
}
?>
